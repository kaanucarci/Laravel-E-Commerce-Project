<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
class CartController extends Controller
{
    public function index()
    {
        $items = Cart::instance('cart')->content();
        return view('cart', compact('items'));
    }

    public function AddToCart(Request $request)
    {
        Cart::instance('cart')->add($request->id, $request->name, $request->quantity, $request->price)->associate('App\Models\Product');
        return redirect()->back();
    }

    public function IncreaseCartQuantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function DecreaseCartQuantity($rowId)
    {
        $product = Cart::instance('cart')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('cart')->update($rowId, $qty);
        return redirect()->back();
    }

    public function RemoveItem($rowId)
    {
        Cart::instance('cart')->remove($rowId);
        return redirect()->back();
    }

    public function EmptyCart()
    {
        Cart::instance('cart')->destroy();
        return redirect()->back();
    }

    public function ApplyCouponCode(Request $request)
    {
        $coupon_code = $request->coupon_code;
        if(isset($coupon_code))
        {
            $subtotal = floatval(str_replace(',', '', Cart::instance('cart')->subtotal()));
            $coupon = Coupon::where('code', $coupon_code)
                ->where('expiry_date' , '>=', Carbon::today())
                ->where('cart_value', '<=', [$subtotal])
                ->first();

            if (!$coupon)
            {
                return redirect()->back()->with('error', 'Invalid Coupon Code');
            }
            else{
                Session::put('coupon', [
                    'code' => $coupon->code,
                    'type' => $coupon->type,
                    'value' => $coupon->value,
                    'cart_value' => $coupon->cart_value
                ]);

                $this->CalculateDiscount();
                return redirect()->back()->with('success', 'Coupon Applied Successfully');
            }
        }
        else
        {
            return redirect()->back()->with('error', 'Please Enter Coupon Code');
        }
    }

    public function CalculateDiscount()
    {
        $discount = 0;
        if(Session::has('coupon')){
            if(Session::get('coupon')['type'] == 'fixed'){
                $discount = Session::get('coupon')['value'];
            }
            else{
                $discount = ((float) str_replace(',', '', Cart::instance('cart')->subtotal()) * Session::get('coupon')['value'])/100;
            }

            $subtotalAfterDiscount = (float) str_replace(',', '', Cart::instance('cart')->subtotal()) - $discount;
            $taxAfterDiscount = ($subtotalAfterDiscount * config('cart.tax'))/100;
            $totalAfterDiscount = $subtotalAfterDiscount + $taxAfterDiscount;

            Session::put('discounts',[
               'discount' => number_format(floatval($discount), 2, '.', ''),
               'subtotal' => number_format(floatval($subtotalAfterDiscount), 2, '.', ''),
               'tax' => number_format(floatval($taxAfterDiscount), 2, '.', ''),
               'total' => number_format(floatval($totalAfterDiscount), 2, '.', '')
            ]);
        }
        else{

        }
    }

    public function RemoveCoupon(){
        Session::forget('coupon');
        Session::forget('discount');
        return redirect()->back()->with('success', 'Coupon Removed Successfully');
    }

    public function Checkout()
    {
        if (!Auth::check())
        {
            return redirect()->route('login');
        }

        $address = Address::where('user_id' , Auth::user()->id)->where('isdefault' , 1)->first();
        return view('checkout', compact('address'));
    }

    public function PlaceAnOrder(Request $request)
    {
        $user_id = Auth::user()->id;
        $address = Address::where('user_id' , $user_id)->where('isdefault' , 1)->first();

        if (!$address)
        {
            $request->validate([
                'name' => 'required|max:100',
                'phone' => 'required|numeric|digits:10',
                'zip' => 'required|numeric|digits:6',
                'state' => 'required',
                'city' => 'required',
                'address' => 'required',
                'locality' => 'required',
                'landmark' => 'required'
            ]);

            $address = new Address();

            $address->user_id = $user_id;
            $address->name = $request->name;
            $address->phone = $request->phone;
            $address->zip = $request->zip;
            $address->state = $request->state;
            $address->city = $request->city;
            $address->address = $request->address;
            $address->locality = $request->locality;
            $address->landmark = $request->landmark;
            $address->isdefault = true;
            $address->country = 'Turkey';
            $address->save();
        }

        $this->setAmountForCheckout();

        $order = new Order();

        $order->user_id  = $user_id;
        $order->subtotal  = str_replace(',', '',Session::get('checkout')['subtotal']);
        $order->discount  = Session::get('checkout')['discount'];
        $order->tax  = Session::get('checkout')['tax'];
        $order->total  = str_replace(',', '',Session::get('checkout')['total']);
        $order->name  = $address->name;
        $order->phone  = $address->phone;
        $order->locality  = $address->locality;
        $order->address  = $address->address;
        $order->city  = $address->city;
        $order->state  = $address->state;
        $order->country  = $address->country;
        $order->landmark  = $address->landmark;
        $order->zipcode  = $address->zip;
        $order->save();

        foreach(Cart::instance('cart')->content() as $item)
        {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $item->id;
            $orderItem->price = $item->price;
            $orderItem->quantity = $item->qty;
            $orderItem->save();
        }

        if ($request->mode == 'card')
        {
            //
        }
        elseif ($request->mode == 'paypal')
        {
            //
        }
        elseif ($request->mode == 'cod')
        {
            $transaction = new Transaction();
            $transaction->user_id = $user_id;
            $transaction->order_id = $order->id;
            $transaction->mode = $request->mode;
            $transaction->status = 'pending';
            $transaction->save();
        }



        Cart::instance('cart')->destroy();
        Session::forget('checkout');
        Session::forget('coupon');
        Session::forget('discounts');
        Session::put('order_id', $order->id);
        return view('order-confirmation', compact('order'));

    }

    public function SetAmountForCheckout()
    {
        if(!Cart::instance('cart')->content()->count() > 0)
        {
            Session::forget('checkout');
            return;
        }

        if(Session::has('coupon'))
        {
            Session::put('checkout', [
                'discount' => Session::get('discounts')['discount'],
                'subtotal' => Session::get('discounts')['subtotal'],
                'tax' => Session::get('discounts')['tax'],
                'total' => Session::get('discounts')['total']
            ]);
        }
        else
        {
            Session::put('checkout', [
                'discount' => 0,
                'subtotal' => Cart::instance('cart')->subtotal(),
                'tax' => Cart::instance('cart')->tax(),
                'total' => Cart::instance('cart')->total()
            ]);
        }
    }

    public function OrderConfirmation()
    {
        if (Session::has('order_id'))
        {
            $order = Order::find(Session::get('order_id'));
            return view('order-confirmation', compact('order'));
        }
        return redirect()->route('cart.index');
    }
}
