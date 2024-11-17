<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\UserController;
use App\Http\Controllers\User\AboutController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\User\DetailController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\User\CheckoutController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\User\DashboardController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\OrderItemController;
use App\Http\Controllers\User\ProductListController;
use App\Http\Controllers\Admin\UserController as AdminUserController;



// user route
Route::get('/', [UserController::class,'index'])->name('home');
Route::get('/dashboard',[DashboardController::class,'index'])->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/search-products', [ProductController::class, 'search'])->name('search.products');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // confirm order
    Route::prefix('checkout')->controller(CheckoutController::class)->group((function() {
        Route::post('order', 'store')->name('checkout.store');
    }));
    Route::post('/payment/callback', [CheckoutController::class, 'paymentCallback']);


    Route::get('/products', [ProductListController::class, 'products'])->name('user.products');
    // product detail
    Route::get('/detail/{id}', [DetailController::class, 'productDetail'])->name('user.detail');

    //add to cart
    Route::prefix('cart')->controller(CartController::class)->group(function () {
        Route::get('view','view')->name('cart.view');
        Route::post('store/{product}','store')->name('cart.store');
        Route::patch('update/{product}','update')->name('cart.update');
        Route::delete('delete/{product}','delete')->name('cart.delete');
    });
    // route product list and filter
    Route::prefix('products')->controller(ProductListController::class)->group(function ()  {
    Route::get('/','index')->name('products.index');

});



});
Route::get('/about', [AboutController::class, 'about'])->name('user.about');
// end

// end




//admin route
Route::group(['prefix' => 'admin', 'middleware' => 'redirectAdmin'], function () {
    Route::get('login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AdminAuthController::class, 'login'])->name('admin.login.post');
    Route::post('logout', [AdminAuthController::class, 'logout'])->name('logout');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');

    //products routes
    Route::get('/products', [ProductController::class, 'index'])->name('admin.products.index');
    Route::post('/products/store',[ProductController::class,'store'])->name('admin.products.store');
    Route::put('/products/update/{id}',[ProductController::class,'update'])->name('admin.products.update');
    Route::delete('/products/image/{id}',[ProductController::class,'deleteImage'])->name('admin.products.image.delete');
    Route::delete('/products/destroy/{id}', [ProductController::class, 'destroy'])->name('admin.products.destroy');

    //categories routes
    Route::get('/categories', [CategoryController::class, 'index'])->name('admin.categories.index');
    Route::post('/categories/store',[CategoryController::class,'store'])->name('admin.categories.store');
    Route::put('/categories/update/{id}',[CategoryController::class,'update'])->name('admin.categories.update');
    Route::delete('/categories/destroy/{id}',[CategoryController::class,'destroy'])->name('admin.categories.destroy');

    //locations routes
    Route::get('/locations', [LocationController::class, 'index'])->name('admin.locations.index');
    Route::post('/locations/store',[LocationController::class,'store'])->name('admin.locations.store');
    Route::put('/locations/update/{id}',[LocationController::class,'update'])->name('admin.locations.update');
    Route::delete('/locations/destroy/{id}', [LocationController::class, 'destroy'])->name('admin.locations.destroy');

    //order_item routes
    Route::get('/orderitems', [OrderItemController::class, 'index'])->name('admin.orderitems.index');
    Route::post('/orderitems/store',[OrderItemController::class,'store'])->name('admin.orderitems.store');
    Route::put('/orderitems/update/{id}',[OrderItemController::class,'update'])->name('admin.orderitems.update');
    Route::delete('/orderitems/destroy/{id}',[OrderItemController::class,'destroy'])->name('admin.orderitems.destroy');
    // order item detail
    Route::get('order/detail/{id}', [OrderItemController::class, 'orderDetail'])->name('admin.orderitems.detail');

     //users routes
     Route::get('/users', [AdminUserController::class, 'index'])->name('admin.users.index');
     Route::post('/users/store',[AdminUserController::class,'store'])->name('admin.users.store');
     Route::put('/users/update/{id}',[AdminUserController::class,'update'])->name('admin.users.update');
     Route::delete('/users/destroy/{id}',[AdminUserController::class,'destroy'])->name('admin.users.destroy');
});


//end

require __DIR__.'/auth.php';
