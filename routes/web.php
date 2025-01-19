<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\AuthAdmin;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');


Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
});

Route::middleware(['auth',AuthAdmin::class])->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');

    Route::get('/admin/brands', [AdminController::class, 'brands'])->name('admin.brands');
    Route::get('/admin/brands/add', [AdminController::class, 'addBrand'])->name('admin.brands.add');
    Route::post('/admin/brands/store', [AdminController::class, 'brandStore'])->name('admin.brands.store');
    Route::get('/admin/brands/edit/{id}', [AdminController::class, 'brandEdit'])->name('admin.brands.edit');
    Route::put('/admin/brands/update', [AdminController::class, 'brandUpdate'])->name('admin.brands.update');
    Route::delete('/admin/brands/{id}/delete', [AdminController::class, 'brandDelete'])->name('admin.brands.delete');

    Route::get('/admin/categories', [AdminController::class, 'categories'])->name('admin.categories');
    Route::get('/admin/categories/add', [AdminController::class, 'addCategory'])->name('admin.categories.add');
    Route::post('/admin/categories/store', [AdminController::class, 'categoryStore'])->name('admin.categories.store');
    Route::get('/admin/categories/edit/{id}', [AdminController::class, 'categoryEdit'])->name('admin.categories.edit');
    Route::put('/admin/categories/update', [AdminController::class, 'categoryUpdate'])->name('admin.categories.update');
    Route::delete('/admin/categories/{id}/delete', [AdminController::class, 'categoryDelete'])->name('admin.categories.delete');

    Route::get('/admin/products', [AdminController::class, 'products'])->name('admin.products');
    Route::get('/admin/products/add', [AdminController::class, 'addProduct'])->name('admin.products.add');
    Route::post('/admin/products/store', [AdminController::class, 'productStore'])->name('admin.products.store');
    Route::get('/admin/products/edit/{id}', [AdminController::class, 'productEdit'])->name('admin.products.edit');
    Route::put('/admin/products/update', [AdminController::class, 'productUpdate'])->name('admin.products.update');
});