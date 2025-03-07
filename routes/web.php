<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\WishlistController;
use App\Http\Middleware\AuthAdmin;

Auth::routes();

Route::get('/', [HomeController::class, 'index'])->name('home.index');
Route::get('/contact-us', [HomeController::class, 'contact'])->name('home.contact');
Route::post('/contact-us/store', [HomeController::class, 'contactStore'])->name('home.contact.store');

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product_slug}', [ShopController::class, 'productDetails'])->name('shop.product.details');


Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');
Route::put('/cart/increase/{rowId}', [CartController::class, 'increaseCartQty'])->name('cart.increase');
Route::put('/cart/decrease/{rowId}', [CartController::class, 'decreaseCartQty'])->name('cart.decrease');
Route::delete('/cart/remove/{rowId}', [CartController::class, 'removeItem'])->name('cart.remove');
Route::delete('/cart/empty', [CartController::class, 'emptyCart'])->name('cart.empty');

Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon.apply');
Route::delete('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.coupon.remove');

Route::post('/wishlist/add', [WishlistController::class, 'add_to_wishlist'])->name('wishlist.add');
Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
Route::delete('/wishlist/remove/{rowId}', [WishlistController::class, 'remove_from_wishlist'])->name('wishlist.remove');
Route::delete('/wishlist/empty', [WishlistController::class, 'empty_wishlist'])->name('wishlist.empty');
Route::post('/wishlist/moveToCart/{rowId}', [WishlistController::class, 'move_to_cart'])->name('wishlist.move');

Route::get('/checkout', [CartController::class, 'checkout'])->name('checkout.index');
Route::post('/place-order', [CartController::class, 'placeOrder'])->name('cart.place.order');
Route::get('/confirm-order', [CartController::class, 'orderConfirmation'])->name('cart.confirm.order');

Route::get('/search', [HomeController::class, 'search'])->name('home.search');

Route::middleware(['auth'])->group(function(){
    Route::get('/account-dashboard', [UserController::class, 'index'])->name('user.index');
    Route::get('/account-orders', [UserController::class, 'orders'])->name('user.orders');
    Route::get('/account-orders/{id}/details', [UserController::class, 'orderDetails'])->name('user.order.details');
    Route::put('/account/orders/cancel', [UserController::class, 'deleteOrder'])->name('user.order.cancel');
});


//Admin Routes
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
    Route::delete('/admin/products//delete/{id}', [AdminController::class, 'productDelete'])->name('admin.products.delete');

    Route::get('/admin/coupons', [AdminController::class, 'coupons'])->name('admin.coupons');
    Route::get('/admin/coupons/add', [AdminController::class, 'addCoupon'])->name('admin.coupons.add');
    Route::post('/admin/coupons/store', [AdminController::class, 'storeCoupon'])->name('admin.coupons.store');
    Route::get('/admin/coupons/edit/{id}', [AdminController::class, 'editCoupon'])->name('admin.coupons.edit');
    Route::put('/admin/coupons/update', [AdminController::class, 'updateCoupon'])->name('admin.coupons.update');
    Route::delete('/admin/coupons/delete/{id}', [AdminController::class, 'deleteCoupon'])->name('admin.coupons.delete');

    Route::get('/admin/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/admin/orders/{id}/details', [AdminController::class, 'orderDetails'])->name('admin.order.details');
    Route::put('/admin/orders/update', [AdminController::class, 'updateOrderStatus'])->name('admin.order.update.status');

    Route::get('/admin/slides', [AdminController::class, 'slides'])->name('admin.slides');
    Route::get('/admin/slides/add', [AdminController::class, 'addSlide'])->name('admin.slides.add');
    Route::post('/admin/slides/store', [AdminController::class, 'storeSlide'])->name('admin.slides.store');
    Route::get('/admin/slides/{id}/edit', [AdminController::class, 'editSlide'])->name('admin.slides.edit');
    Route::put('/admin/slides/update', [AdminController::class, 'updateSlide'])->name('admin.slides.update');
    Route::delete('/admin/slides/{id}/delete', [AdminController::class, 'deleteSlide'])->name('admin.slides.delete');

    Route::get('/admin/contact', [AdminController::class, 'contacts'])->name('admin.contacts');
    Route::get('/admin/contact/{id}/details', [AdminController::class, 'contactDetails'])->name('admin.contact.details');
    Route::delete('/admin/contact/{id}/delete', [AdminController::class, 'deleteContact'])->name('admin.contact.delete');
});