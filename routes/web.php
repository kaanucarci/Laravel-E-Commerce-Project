<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\AuthAdmin;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::middleware(['auth'])->group(function (){
   Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
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
});
