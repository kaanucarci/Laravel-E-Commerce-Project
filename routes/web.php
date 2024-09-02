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
});
