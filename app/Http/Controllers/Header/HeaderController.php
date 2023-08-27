<?php

namespace App\Http\Controllers\Header;

use App\Http\Controllers\Controller;

class HeaderController extends Controller
{
    public function index(){

        $categories=app('Header')->CategoryList();
        $brands=app('Header')->BrandList();

        return view('components.categoryHeader',compact('categories','brands'));
    }
}
