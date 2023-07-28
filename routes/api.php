<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/home/exclusive',[\App\Http\Controllers\HomeController::class,'getAjaxProduct']);
