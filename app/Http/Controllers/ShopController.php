<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ShopController extends Controller
{
    public function index()
    {
        $products = Product::orderBy('created_at', 'DESC')->paginate(10);
        return view('shop', compact('products'));
    }

    public function ProductDetails($product_slug)
    {
        $product = Product::where('slug', $product_slug)->first();
        $relatedProducts = Product::where('slug', '<>', $product_slug)->get()->take(8);
        return view('details', compact('product', 'relatedProducts'));
    }
}
