<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/home/exclusive',[\App\Http\Controllers\HomeController::class,'getAjaxProduct']);
Route::get('/elasticSearch/index/create/productName',function (){
    app('Search')->InsertDataProductName();
});
Route::get('/elasticSearch/index/create/productCategory',function (){
    app('Search')->InsertDataProductCategory();
});
Route::get('/elasticSearch/index/search',function (){
    app('Search')->SearchProduct();
});
