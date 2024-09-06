<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Intervention\Image\Laravel\Facades\Image;

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.index');
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
                    array_push($gallery_arr, $file_name);
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

}
