<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\Slide;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        $orders = Order::orderBy('created_at', 'DESC')->get()->take(10);

        $monthlyDatas = DB::select("SELECT M.id AS MonthNo, M.name AS MonthName,
                                    IFNULL(D.TotalAmount,0) AS TotalAmount,
                                    IFNULL(D.TotalOrderedAmount,0) AS TotalOrderedAmount,
                                    IFNULL(D.TotalDeliveredAmount,0) AS TotalDeliveredAmount,
                                    IFNULL(D.TotalCanceledAmount,0) AS TotalCanceledAmount FROM month_names M
                                    LEFT JOIN (Select DATE_FORMAT(created_at, '%b') AS MonthName,
                                    MONTH(created_at) AS MonthNo,
                                    sum(total) AS TotalAmount,
                                    sum(if(status='ordered',total,0)) AS TotalOrderedAmount,
                                    sum(if(status='delivered',total,0)) AS TotalDeliveredAmount,
                                    sum(if(status='canceled',total,0)) AS TotalCanceledAmount
                                    FROM Orders WHERE YEAR(created_at) = YEAR(NOW()) GROUP BY YEAR(created_at), MONTH(created_at), DATE_FORMAT(created_at, '%b')
                                    ) D on D.MonthNo = M.id ORDER BY M.id");

        $AmountM = implode(",", collect($monthlyDatas)->pluck('TotalAmount')->toArray());
        $orderAmountM = implode(",", collect($monthlyDatas)->pluck('TotalOrderedAmount')->toArray());
        $deliveredAmountM = implode(",", collect($monthlyDatas)->pluck('TotalDeliveredAmount')->toArray());
        $canceledAmountM = implode(",", collect($monthlyDatas)->pluck('TotalCanceledAmount')->toArray());

        $totalAmount = collect($monthlyDatas)->sum('TotalAmount');
        $totalOrderedAmount = collect($monthlyDatas)->sum('TotalOrderedAmount');
        $totalDeliveredAmount = collect($monthlyDatas)->sum('TotalDeliveredAmount');
        $totalCanceledAmount = collect($monthlyDatas)->sum('TotalCanceledAmount');

        return view('admin.index', compact('orders', 'AmountM', 'orderAmountM', 'deliveredAmountM', 'canceledAmountM', 'totalAmount', 'totalOrderedAmount', 'totalDeliveredAmount', 'totalCanceledAmount'));
    }

    public function Brands()
    {
        $brands = Brand::orderBy('id', 'DESC')->paginate(10);

        return view('admin.brands', compact('brands'));
    }

    public function AddBrand()
    {
        return view('admin.brand-add');
    }

    public function StoreBrand(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug',
            'image' => 'mimes:jpg,jpeg,png|max:2048'
        ]);

        $brand = new Brand();

        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;
        $this->GenerateThumbnailsImage($image, $file_name, 'brands');
        $brand->image = $file_name;
        $brand->save();

        return redirect()->route('admin.brands')->with('status', 'Brand Added Successfully');
    }

    public function EditBrand($id)
    {
        $brand = Brand::find($id);
        return view('admin.brand-edit', compact('brand'));
    }

    public function UpdateBrand(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $request->id,
            'image' => 'mimes:jpg,jpeg,png|max:2048'
        ]);

        $brand = Brand::find($request->id);

        $brand->name = $request->name;
        $brand->slug = Str::slug($request->name);

        if ($request->hasFile('image'))
        {
            if (File::exists(public_path('uploads/brands') . '/' . $brand->image))
            {
                File::delete(public_path('uploads/brands') . '/' . $brand->image);
            }

            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;
            $this->GenerateThumbnailsImage($image, $file_name, 'brands');
            $brand->image = $file_name;
        }


        $brand->save();
        return redirect()->route('admin.brands')->with('status', 'Brand Updated Successfully');
    }
    public function GenerateThumbnailsImage($image, $imageName, $folderName, $width = 124, $height = 124)
    {
        $destination = public_path('uploads/'.$folderName);
        $img = Image::read($image->path());
        $img->cover($width, $width, "top");
        $img->resize($height,$height, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destination . '/' . $imageName);
    }

    public function DeleteBrand($id)
    {
        $brand = Brand::find($id);
        if (File::exists(public_path('uploads/brands') . '/' . $brand->image))
        {
            File::delete(public_path('uploads/brands') . '/' . $brand->image);
        }

        $brand->delete();
        return redirect()->route('admin.brands')->with('status', 'Brand Deleted Successfully');
    }

    public function Categories()
    {
        $categories = Category::orderBy('id', 'DESC')->paginate(10);

        return view('admin.categories', compact('categories'));
    }


    public function AddCategory()
    {
        return view('admin.category-add');
    }

    public function StoreCategory(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:categories,slug',
            'image' => 'mimes:jpg,jpeg,png|max:2048'
        ]);

        $category = new Category();

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;
        $this->GenerateThumbnailsImage($image, $file_name, 'categories');
        $category->image = $file_name;
        $category->save();

        return redirect()->route('admin.categories')->with('status', 'Category Added Successfully');
    }


    public function EditCategory($id)
    {
        $category = Category::find($id);
        return view('admin.category-edit', compact('category'));
    }


    public function UpdateCategory(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:brands,slug,' . $request->id,
            'image' => 'mimes:jpg,jpeg,png|max:2048'
        ]);

        $category = Category::find($request->id);

        $category->name = $request->name;
        $category->slug = Str::slug($request->name);

        if ($request->hasFile('image'))
        {
            if (File::exists(public_path('uploads/categories') . '/' . $category->image))
            {
                File::delete(public_path('uploads/categories') . '/' . $category->image);
            }

            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;
            $this->GenerateThumbnailsImage($image, $file_name, 'categories');
            $category->image = $file_name;
        }


        $category->save();
        return redirect()->route('admin.categories')->with('status', 'Category Updated Successfully');
    }


    public function DeleteCategory($id)
    {
        $category = Category::find($id);
        if (File::exists(public_path('uploads/categories') . '/' . $category->image))
        {
            File::delete(public_path('uploads/categories') . '/' . $category->image);
        }

        $category->delete();
        return redirect()->route('admin.categories')->with('status', 'Category Deleted Successfully');
    }

    public function Products()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);

        return view('admin.products', compact('products'));
    }


    public function AddProduct()
    {
        $brands = Brand::select('id', 'name')->orderBy('name')->get();
        $categories = Category::select('id', 'name')->orderBy('name')->get();
        return view('admin.product-add', compact('brands', 'categories'));
    }


    public function StoreProduct(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug',
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required',
            'brand_id' => 'required'
        ]);

        $product = new Product();


        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        if ($request->hasFile('image'))
        {
            $image = $request->file('image');
            $file_name = Carbon::now()->timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailsImage($image, $file_name);
            $product->image = $file_name;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if ($request->hasFile('images'))
        {
            $allowedExtensions = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
            foreach ($files as $file)
            {
                $extension = $file->getClientOriginalExtension();
                if (in_array($extension, $allowedExtensions))
                {
                    $file_name = Carbon::now()->timestamp . '-' . $counter . '.' . $extension;
                    $this->GenerateProductThumbnailsImage($file, $file_name);
                    $gallery_arr = array_merge($gallery_arr, [$file_name]);
                    $counter++;
                }
            }



            $gallery_images = implode(',', $gallery_arr);
        }
        $product->images = $gallery_images;
        $product->save();

        return redirect()->route('admin.products')->with('status', 'Product Added Successfully');
    }


    public function GenerateProductThumbnailsImage($image, $imageName)
    {
        $destinationThumbnail = public_path('uploads/products/thumbnails');
        $destination = public_path('uploads/products');
        $img = Image::read($image->path());
        $img->cover(540, 689, "top");
        $img->resize(540,689, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destination . '/' . $imageName);

        $img->resize(104,104, function ($constraint) {
            $constraint->aspectRatio();
        })->save($destinationThumbnail . '/' . $imageName);
    }

    public function EditProduct($id)
    {
       $product = Product::find($id);
       $categories = Category::select('id', 'name')->orderBy('name')->get();
       $brands = Brand::select('id', 'name')->orderBy('name')->get();

       return view('admin.product-edit', compact('product', 'categories', 'brands'));
    }

    public function UpdateProduct(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'slug' => 'required|unique:products,slug,' . $request->id,
            'short_description' => 'required',
            'description' => 'required',
            'regular_price' => 'required',
            'sale_price' => 'required',
            'SKU' => 'required',
            'stock_status' => 'required',
            'featured' => 'required',
            'quantity' => 'required',
            'image' => 'mimes:jpg,jpeg,png|max:2048',
            'category_id' => 'required',
            'brand_id' => 'required'
        ]);


        $product = Product::find($request->id);

        $product->name = $request->name;
        $product->slug = Str::slug($request->name);
        $product->short_description = $request->short_description;
        $product->description = $request->description;
        $product->regular_price = $request->regular_price;
        $product->sale_price = $request->sale_price;
        $product->SKU = $request->SKU;
        $product->stock_status = $request->stock_status;
        $product->featured = $request->featured;
        $product->quantity = $request->quantity;
        $product->category_id = $request->category_id;
        $product->brand_id = $request->brand_id;

        if ($request->hasFile('image'))
        {
            if (File::exists(public_path('uploads/products/' . $product->images)))
            {
                File::delete(public_path('uploads/products/' . $product->images));
            }

            if (File::exists(public_path('uploads/products/thumbnails' . $product->images)))
            {
                File::delete(public_path('uploads/products/thumbnails' . $product->images));
            }
            $image = $request->file('image');
            $file_name = Carbon::now()->timestamp . '.' . $image->extension();
            $this->GenerateProductThumbnailsImage($image, $file_name);
            $product->image = $file_name;
        }

        $gallery_arr = array();
        $gallery_images = "";
        $counter = 1;

        if ($request->hasFile('images'))
        {
            foreach (explode(',',$product->images) as $image)
            {
                if (File::exists(public_path('uploads/products/' . $image)))
                {
                    File::delete(public_path('uploads/products/' . $image));
                }

                if (File::exists(public_path('uploads/products/thumbnails' . $image)))
                {
                    File::delete(public_path('uploads/products/thumbnails' . $image));
                }
            }

            $allowedExtensions = ['jpg', 'png', 'jpeg'];
            $files = $request->file('images');
            foreach ($files as $file)
            {
                $extension = $file->getClientOriginalExtension();
                if (in_array($extension, $allowedExtensions))
                {
                    $file_name = Carbon::now()->timestamp . '-' . $counter . '.' . $extension;
                    $this->GenerateProductThumbnailsImage($file, $file_name);
                    $gallery_arr = array_merge($gallery_arr, [$file_name]);
                    $counter++;
                }
            }



            $gallery_images = implode(',', $gallery_arr);

            $product->images = $gallery_images;

        }
        $product->save();


        return redirect()->route('admin.products')->with('status', 'Product Updated Successfully');
    }

    public function DeleteProduct($id)
    {
        $product = Product::find($id);
        if (File::exists(public_path('uploads/products') . '/' . $product->image))
        {
            File::delete(public_path('uploads/products') . '/' . $product->image);
        }

        if (File::exists(public_path('uploads/products/thumbnails') . '/' . $product->image))
        {
            File::delete(public_path('uploads/products/thumbnails') . '/' . $product->image);
        }

        foreach (explode(',',$product->images) as $image)
        {
            if (File::exists(public_path('uploads/products/' . $image)))
            {
                File::delete(public_path('uploads/products/' . $image));
            }

            if (File::exists(public_path('uploads/products/thumbnails' . $image)))
            {
                File::delete(public_path('uploads/products/thumbnails' . $image));
            }
        }


        $product->delete();
        return redirect()->route('admin.products')->with('status', 'Product Deleted Successfully');
    }

    public function Coupons()
    {
        $coupons = Coupon::orderBy('expiry_date', 'ASC')->paginate(10);
        return view('admin.coupons', compact('coupons'));
    }


    public function CreateCoupon()
    {
        return view('admin.create-coupon');
    }


    public function StoreCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:App\Models\Coupon',
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date'
        ]);

        $coupon = new Coupon();
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status', 'Coupon Created Successfully');
    }

    public function EditCoupon($id)
    {
        $coupon = Coupon::find($id);
        return view('admin.edit-coupon', compact('coupon'));
    }

    public function UpdateCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:App\Models\Coupon,code,' . $request->id,
            'type' => 'required',
            'value' => 'required|numeric',
            'cart_value' => 'required|numeric',
            'expiry_date' => 'required|date'
        ]);
        $coupon = Coupon::find($request->id);
        $coupon->code = $request->code;
        $coupon->type = $request->type;
        $coupon->value = $request->value;
        $coupon->cart_value = $request->cart_value;
        $coupon->expiry_date = $request->expiry_date;
        $coupon->save();
        return redirect()->route('admin.coupons')->with('status', 'Coupon Updated Successfully');
    }


    public function DeleteCoupon($id)
    {
        $coupon = Coupon::find($id);
        $coupon->delete();
        return redirect()->route('admin.coupons')->with('status', 'Coupon Deleted Successfully');
    }

    public function Orders()
    {
        $orders = Order::orderBy('created_at', 'DESC')->paginate(10);
        return view('admin.orders', compact('orders'));
    }

    public function OrderDetails($order_id){
        $order = Order::find($order_id);
        $order_items = OrderItem::where('order_id', $order_id)->orderBy('id')->paginate(10);
        $transaction = Transaction::where('order_id', $order_id)->first();

        return view('admin.order-details', compact('order', 'order_items', 'transaction'));
    }

    public function UpdateOrderStatus(Request $request)
    {
        $order = Order::find($request->order_id);
        $order->status = $request->order_status;

        if ($request->order_status == 'delivered'){
            $order->delivered_date = Carbon::now();
        }
        elseif ($request->order_status == 'cancelled'){
             $order->canceled_date = Carbon::now();
        }
        $order->save();

        $transaction = Transaction::where('order_id', $request->order_id)->first();

        if ($request->order_status == 'delivered')
        {
            $transaction->status = 'approved';
        }
        elseif ($request->order_status == 'canceled')
        {
            $transaction->status = 'declined';
        }
        elseif ($request->order_status == 'ordered')
        {
            $transaction->status = 'pending';
        }
        $transaction->save();


        return back()->with('status', 'Order Status Updated Successfully');
    }

    public function Slide()
    {
         $slides = Slide::orderBy('id', 'DESC')->paginate(10);
         return view('admin.slides', compact('slides'));
    }

    public function AddSlide()
    {
        return view('admin.slide-add');
    }

    public function StoreSlide(Request $request)
    {
        $request->validate([
            'tagline' => 'required',
            'title' => 'required',
            'subtitle' => 'required',
            'link' => 'required',
            'status' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png|max:2048'
        ]);

        $slide = new Slide();
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;

        $image = $request->file('image');
        $file_extention = $request->file('image')->extension();
        $file_name = Carbon::now()->timestamp . '.' . $file_extention;
        $this->GenerateThumbnailsImage($image, $file_name, 'slides', 400, 690);
        $slide->image = $file_name;
        $slide->save();

         return redirect()->route('admin.slides')->with('status', 'Slide Added Successfully');
    }

    public function EditSlide($id)
    {
        $slide = Slide::find($id);
        return view('admin.slide-edit', compact('slide'));
    }

    public function UpdateSlide(Request $request)
    {
        $request->validate([
            'tagline' => 'required',
            'title' => 'required',
            'subtitle' => 'required',
            'link' => 'required',
            'status' => 'required',
            'image' => 'required|mimes:jpg,jpeg,png|max:2048'
        ]);

        $slide = Slide::find($request->id);
        $slide->tagline = $request->tagline;
        $slide->title = $request->title;
        $slide->subtitle = $request->subtitle;
        $slide->link = $request->link;
        $slide->status = $request->status;

        if ($request->hasFile('image'))
        {
            if (File::exists(public_path('uploads/slides') . '/' . $slide->image))
            {
                File::delete(public_path('uploads/slides') . '/' . $slide->image);
            }

            $image = $request->file('image');
            $file_extention = $request->file('image')->extension();
            $file_name = Carbon::now()->timestamp . '.' . $file_extention;
            $this->GenerateThumbnailsImage($image, $file_name, 'slides', 400, 690);
            $slide->image = $file_name;
        }

        $slide->save();

         return redirect()->route('admin.slides')->with('status', 'Slide Updated Successfully');

    }

    public function DeleteSlide($id)
    {
        $slide = Slide::find($id);
        if (File::exists(public_path('uploads/slides') . '/' . $slide->image))
        {
            File::delete(public_path('uploads/slides') . '/' . $slide->image);
        }
        $slide->delete();
        return redirect()->route('admin.slides')->with('status', 'Slide Deleted Successfully');
    }

}
