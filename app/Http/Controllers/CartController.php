<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
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
}
