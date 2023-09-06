<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [\App\Http\Controllers\Home\HomeController::class, 'index']);

Auth::routes();

Route::get('/home', [\App\Http\Controllers\Home\HomeController::class, 'index'])->name('home');



Route::group(['namespace' => 'Admin', 'middleware' => ['role:admin'], 'prefix'=> 'admin'], function(){//prefix подставляет admin во всё что внутри группы в пути , namespace группа контрорреров в папке Admin middleware дал доступ роли админу

    Route::get('',[\App\Http\Controllers\Admin\AdminController::class,'index']);

    Route::get('/slider',[\App\Http\Controllers\Admin\SliderController::class, 'index'])->name('admin.slider');
    Route::get('/slider/create',[\App\Http\Controllers\Admin\SliderController::class, 'create'])->name('admin.slider.create');
    Route::post('/slider',[\App\Http\Controllers\Admin\SliderController::class, 'store'])->name('admin.slider.store');
    Route::post('/slider/image',[\App\Http\Controllers\Admin\SliderController::class, 'storeImage'])->name('admin.slider.image.store');
    Route::post('/slider/{id_slider}/image/{id}',[\App\Http\Controllers\Admin\SliderController::class, 'destroyImage'])->name('admin.slider.image.destroy');
    Route::get('/slider/edit/{id}',[\App\Http\Controllers\Admin\SliderController::class, 'edit'])->name('admin.slider.edit');
    Route::put('/slider/{id}',[\App\Http\Controllers\Admin\SliderController::class, 'update'])->name('admin.slider.update');
    Route::delete('/slider/{id}',[\App\Http\Controllers\Admin\SliderController::class, 'destroy'])->name('admin.slider.destroy');


    Route::get('/category',[\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.category');
    Route::get('/category/create',[\App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('admin.category.create');
    Route::post('/category',[\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.category.store');
    Route::get('/category/edit/{id}',[\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('admin.category.edit');
    Route::put('/category/{id}',[\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.category.update');
    Route::delete('/category/{id}',[\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('admin.category.destroy');

    Route::get('/manufacturer',[\App\Http\Controllers\Admin\ManufacturerController::class, 'index'])->name('admin.manufacturer');
    Route::get('/manufacturer/create',[\App\Http\Controllers\Admin\ManufacturerController::class, 'create'])->name('admin.manufacturer.create');
    Route::post('/manufacturer',[\App\Http\Controllers\Admin\ManufacturerController::class, 'store'])->name('admin.manufacturer.store');
    Route::delete('/manufacturer/{id}',[\App\Http\Controllers\Admin\ManufacturerController::class, 'destroy'])->name('admin.manufacturer.destroy');

    Route::get('/attribute',[\App\Http\Controllers\Admin\AttributeController::class, 'index'])->name('admin.attribute');
    Route::get('/attribute/create',[\App\Http\Controllers\Admin\AttributeController::class, 'create'])->name('admin.attribute.create');
    Route::post('/attribute',[\App\Http\Controllers\Admin\AttributeController::class, 'store'])->name('admin.attribute.store');
    Route::delete('/attribute/{id}',[\App\Http\Controllers\Admin\AttributeController::class, 'destroy'])->name('admin.attribute.destroy');

    Route::get('/product',[\App\Http\Controllers\Admin\ProductController::class, 'index'])->name('admin.product');
    Route::get('/product/create',[\App\Http\Controllers\Admin\ProductController::class, 'create'])->name('admin.product.create');
    Route::post('/product',[\App\Http\Controllers\Admin\ProductController::class,'store'])->name('admin.product.store');
    Route::delete('/product/{id}',[\App\Http\Controllers\Admin\ProductController::class, 'destroy'])->name('admin.product.destroy');


});


Route::get('/product/{slug}', [App\Http\Controllers\Product\ProductController::class,'show'])->name('product.show');
Route::get('/category/{slug}', [\App\Http\Controllers\Category\CategoryController::class,'show'])->name('category.show');


Route::get('/checkout',[\App\Http\Controllers\Checkout\CheckoutController::class,'index'])->name('checkout');
Route::post('/createOrder',[\App\Http\Controllers\Checkout\CheckoutController::class,'SaveOrder'])->name('SaveOrder');
Route::get('/successfully/{id}',[\App\Http\Controllers\Checkout\CheckoutController::class,'successfully'])->name('successfully');
Route::post('/saveAddress',[\App\Http\Controllers\Checkout\CheckoutController::class,'SaveAddress'])->name('save.address');


Route::get('/cart',[\App\Http\Controllers\Cart\CartController::class,'index'])->name('cart');

Route::get('/addCart',[\App\Http\Controllers\Cart\CartController::class,'addToCart'])->name('addCart');
Route::get('/delCart',[\App\Http\Controllers\Cart\CartController::class,'DelToCart'])->name('delCart');
Route::get('/delAllCart',[\App\Http\Controllers\Cart\CartController::class,'DelAllCart'])->name('delAllCart');
Route::get('/updateProductCart',[\App\Http\Controllers\Cart\CartController::class,'UpdateCountProduct'])->name('UpdateCountProduct');
Route::get('/activeProductCart',[\App\Http\Controllers\Cart\CartController::class,'ActiveProduct'])->name('ActiveProduct');
Route::get('/activeAllProductCart',[\App\Http\Controllers\Cart\CartController::class,'ActiveAllProduct'])->name('ActiveAllProduct');

Route::get('/CheckCountProduct',[\App\Http\Controllers\Cart\CartController::class,'CheckCountProduct'])->name('CheckCountProduct');
Route::post('/getCategoryList',[\App\Http\Controllers\Header\HeaderController::class,'index'])->name('header.category');
Route::post('/filter/product', [\App\Http\Controllers\Category\CategoryController::class,'getFilterProducts'])->name('query.filter.product');

Route::get('/account',[\App\Http\Controllers\Account\AccountController::class,'index'])->name('account');
Route::put('/account/user/{id}',[\App\Http\Controllers\Account\AccountController::class,'update'])->name('account.user.update');
