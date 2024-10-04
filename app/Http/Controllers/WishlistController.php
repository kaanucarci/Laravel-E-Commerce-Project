<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Surfsidemedia\Shoppingcart\Facades\Cart;
class WishlistController extends Controller
{
    public function index()
    {
        $items = Cart::instance('wishlist')->content();
        return view('wishlist', compact('items'));
    }

    public function AddToWishlist(Request $request)
    {
        Cart::instance('wishlist')->add($request->id,$request->name,$request->quantity,$request->price)->associate('App\Models\Product');
        return redirect()->back();
    }

    public function RemoveFromWishlist(Request $request)
    {
        Cart::instance('wishlist')->remove($request->rowId);
        return redirect()->back();
    }

    public function IncreaseWishlistQuantity($rowId)
    {
        $product = Cart::instance('wishlist')->get($rowId);
        $qty = $product->qty + 1;
        Cart::instance('wishlist')->update($rowId, $qty);
        return redirect()->back();
    }

    public function DecreaseWishlistQuantity($rowId)
    {
        $product = Cart::instance('wishlist')->get($rowId);
        $qty = $product->qty - 1;
        Cart::instance('wishlist')->update($rowId, $qty);
        return redirect()->back();
    }

    public function RemoveItem($rowId)
    {
        Cart::instance('wishlist')->remove($rowId);
        return redirect()->back();
    }

    public function EmptyWishlist()
    {
        Cart::instance('wishlist')->destroy();
        return redirect()->back();
    }
}
