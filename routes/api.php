<?php

use Illuminate\Support\Facades\Route;

Route::get('/home/exclusive',[\App\Http\Controllers\Home\HomeController::class,'getAjaxProduct']);
Route::get('/elasticSearch/index/create/productName',function (){
    app('Search')->InsertDataProduct();
});
Route::get('/elasticSearch/index/create/productCategory',function (){
    app('Search')->InsertDataProductCategory();
});
Route::get('/elasticSearch/index/search',function (){
    app('Search')->SearchProduct();
});
Route::get('/query/{name}', [\App\Http\Controllers\SearchController::class,'find'])->name('query');
