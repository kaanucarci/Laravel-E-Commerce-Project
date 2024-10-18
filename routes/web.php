<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class, 'ProductDetails'])->name('shop.product.details');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'AddToCart'])->name('cart.add');
Route::put('/cart/increase-quantity/{rowId}', [CartController::class, 'IncreaseCartQuantity'])->name('cart.qty.increase');
Route::put('/cart/decrease-quantity/{rowId}', [CartController::class, 'DecreaseCartQuantity'])->name('cart.qty.decrease');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'RemoveItem'])->name('cart.item.remove');
Route::delete('/cart/remove-all', [CartController::class, 'EmptyCart'])->name('cart.item.remove-all');

Route::post('cart/apply-coupon', [CartController::class, 'ApplyCouponCode'])->name('cart.coupon.apply');
Route::post('cart/remove-coupon', [CartController::class, 'RemoveCoupon'])->name('cart.coupon.remove');

Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::post('/wishlist/add', [WishlistController::class, 'AddToWishlist'])->name('wishlist.add');
Route::post('/wishlist/remove', [WishlistController::class, 'RemoveFromWishlist'])->name('wishlist.remove');
Route::put('/wishlist/increase-quantity/{rowId}', [WishlistController::class, 'IncreaseWishlistQuantity'])->name('wishlist.qty.increase');
Route::put('/wishlist/decrease-quantity/{rowId}', [WishlistController::class, 'DecreaseWishlistQuantity'])->name('wishlist.qty.decrease');
Route::delete('/wishlist/remove/{rowId}', [WishlistController::class, 'RemoveItem'])->name('wishlist.item.remove');
Route::delete('/wishlist/remove-all', [WishlistController::class, 'EmptyWishlist'])->name('wishlist.item.remove-all');


Route::get('/checkout', [CartController::class, 'Checkout'])->name('cart.checkout');
Route::post('/place-an-order', [CartController::class, 'PlaceAnOrder'])->name('cart.place.an.order');
Route::get('/order-confirmation', [CartController::class, 'OrderConfirmation'])->name('cart.order.confirmation');

Route::middleware(['auth'])->group(function (){
   Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
   Route::get('/account-dashboard/orders', [UserController::class, 'Orders'])->name('user.orders');
   Route::get('/account-dashboard/order/{order_id}/details', [UserController::class, 'OrderDetails'])->name('user.order.details');
});


Route::middleware(['auth', AuthAdmin::class])->group(function (){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin/brands', [AdminController::class, 'Brands'])->name('admin.brands');
    Route::get('/admin/brand/add', [AdminController::class, 'AddBrand'])->name('admin.brand-add');
    Route::post('/admin/brand/store', [AdminController::class, 'StoreBrand'])->name('admin.brand.store');
    Route::get('/admin/brand/edit/{id}', [AdminController::class, 'EditBrand'])->name('admin.brand.edit');
    Route::put('/admin/brand/update', [AdminController::class, 'UpdateBrand'])->name('admin.brand.update');
    Route::delete('/admin/brand/{id}/delete', [AdminController::class, 'DeleteBrand'])->name('admin.brand.delete');

    Route::get('/admin/categories', [AdminController::class, 'Categories'])->name('admin.categories');
    Route::get('/admin/category/add', [AdminController::class, 'AddCategory'])->name('admin.category-add');
    Route::post('/admin/category/store', [AdminController::class, 'StoreCategory'])->name('admin.category.store');
    Route::get('/admin/category/edit/{id}', [AdminController::class, 'EditCategory'])->name('admin.category.edit');
    Route::put('/admin/category/update', [AdminController::class, 'UpdateCategory'])->name('admin.category.update');
    Route::delete('/admin/category/{id}/delete', [AdminController::class, 'DeleteCategory'])->name('admin.category.delete');

    Route::get('/admin/products', [AdminController::class, 'Products'])->name('admin.products');
    Route::get('/admin/product/add', [AdminController::class, 'AddProduct'])->name('admin.product-add');
    Route::post('/admin/product/store', [AdminController::class, 'StoreProduct'])->name('admin.product.store');
    Route::get('/admin/product/edit/{id}', [AdminController::class, 'EditProduct'])->name('admin.product.edit');
    Route::put('/admin/product/update', [AdminController::class, 'UpdateProduct'])->name('admin.product.update');
    Route::delete('/admin/product/{id}/delete', [AdminController::class, 'DeleteProduct'])->name('admin.product.delete');

    Route::get('/admin/coupons', [AdminController::class, 'Coupons'])->name('admin.coupons');
    Route::get('/admin/coupon/add', [AdminController::class, 'CreateCoupon'])->name('admin.coupon.add');
    Route::post('/admin/coupon/store', [AdminController::class, 'StoreCoupon'])->name('admin.coupon.store');
    Route::get('/admin/coupon/edit/{id}', [AdminController::class, 'EditCoupon'])->name('admin.coupon.edit');
    Route::put('/admin/coupon/update', [AdminController::class, 'UpdateCoupon'])->name('admin.coupon.update');
    Route::delete('/admin/coupon/{id}/delete', [AdminController::class, 'DeleteCoupon'])->name('admin.coupon.delete');

    Route::get('/admin/orders', [AdminController::class, 'Orders'])->name('admin.orders');
    Route::get('/admin/order/{order_id}/details', [AdminController::class, 'OrderDetails'])->name('admin.order.details');
    Route::put('/admin/order/update-status', [AdminController::class, 'UpdateOrderStatus'])->name('admin.order.update-status');
});
