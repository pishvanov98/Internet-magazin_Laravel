<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/home/exclusive',[\App\Http\Controllers\HomeController::class,'getAjaxProduct']);
Route::get('/elasticSearch/index/create',function (){
    app('Search')->InsertData();
});
Route::get('/elasticSearch/index/search',function (){
    app('Search')->Search();
});
