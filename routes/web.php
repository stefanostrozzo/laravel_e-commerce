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
    Route::get('/admin/brands/edit{id}', [AdminController::class, 'brandEdit'])->name('admin.brands.edit');
    Route::put('/admin/brands/update', [AdminController::class, 'brandUpdate'])->name('admin.brands.update');
    Route::delete('/admin/brands/{id}/delete', [AdminController::class, 'brandDelete'])->name('admin.brands.delete');
});