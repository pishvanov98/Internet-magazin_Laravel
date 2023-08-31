<?php

namespace App\Http\Controllers\Header;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;

class HeaderController extends Controller
{
    public function index(){


        if(Cache::has('CategoryList') && Cache::has('BrandList')) {
            $categories=Cache::get('CategoryList');
            $brands=Cache::get('BrandList');
        }else{
            $categories=app('Header')->CategoryList();
            $brands=app('Header')->BrandList();
            Cache::put('CategoryList',$categories,43200);
            Cache::put('BrandList',$brands,43200);
        }
        return view('components.categoryHeader',compact('categories','brands'));
    }
}
