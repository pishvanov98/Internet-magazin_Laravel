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



Route::get('/admin',[\App\Http\Controllers\Admin\AdminController::class,'index']);

Route::get('/admin/slider',[\App\Http\Controllers\Admin\SliderController::class, 'index'])->name('admin.slider');
Route::get('/admin/slider/create',[\App\Http\Controllers\Admin\SliderController::class, 'create'])->name('admin.slider.create');
Route::post('/admin/slider',[\App\Http\Controllers\Admin\SliderController::class, 'store'])->name('admin.slider.store');
Route::post('/admin/slider/image',[\App\Http\Controllers\Admin\SliderController::class, 'storeImage'])->name('admin.slider.image.store');
Route::post('/admin/slider/{id_slider}/image/{id}',[\App\Http\Controllers\Admin\SliderController::class, 'destroyImage'])->name('admin.slider.image.destroy');
Route::get('/admin/slider/edit/{id}',[\App\Http\Controllers\Admin\SliderController::class, 'edit'])->name('admin.slider.edit');
Route::put('/admin/slider/{id}',[\App\Http\Controllers\Admin\SliderController::class, 'update'])->name('admin.slider.update');
Route::delete('/admin/slider/{id}',[\App\Http\Controllers\Admin\SliderController::class, 'destroy'])->name('admin.slider.destroy');


Route::get('/admin/category',[\App\Http\Controllers\Admin\CategoryController::class, 'index'])->name('admin.category');
Route::get('/admin/category/create',[\App\Http\Controllers\Admin\CategoryController::class, 'create'])->name('admin.category.create');
Route::post('/admin/category',[\App\Http\Controllers\Admin\CategoryController::class, 'store'])->name('admin.category.store');
Route::get('/admin/category/edit/{id}',[\App\Http\Controllers\Admin\CategoryController::class, 'edit'])->name('admin.category.edit');
Route::put('/admin/category/{id}',[\App\Http\Controllers\Admin\CategoryController::class, 'update'])->name('admin.category.update');
Route::delete('/admin/category/{id}',[\App\Http\Controllers\Admin\CategoryController::class, 'destroy'])->name('admin.category.destroy');

Route::get('/product/{slug}', [App\Http\Controllers\Product\ProductController::class,'show'])->name('product.show');
Route::get('/category/{slug}', [\App\Http\Controllers\Category\CategoryController::class,'show'])->name('category.show');

