<?php

use Illuminate\Support\Facades\Route;

Route::get('/home/exclusive',[\App\Http\Controllers\Home\HomeController::class,'getAjaxProduct']);
Route::get('/elasticSearch/index/create/productName',function (){
    app('Search')->InsertDataProduct();
});
Route::get('/elasticSearch/index/create/productCategory',function (){
    app('Search')->InsertDataProductCategory();
});

Route::get('/elasticSearch/index/create/InsertDataProductAttr',function (){
    app('Search')->InsertDataProductAttr();
});
Route::get('/elasticSearch/index/search',function (){
    app('Search')->SearchProduct();
});
Route::get('/query/{name}', [\App\Http\Controllers\SearchController::class,'find'])->name('query');
Route::get('/query/admin/category/{name}', [\App\Http\Controllers\SearchController::class,'find_admin_category'])->name('query.admin.category');
Route::get('/query/admin/product/{name}', [\App\Http\Controllers\SearchController::class,'find_admin_product'])->name('query.admin.product');
