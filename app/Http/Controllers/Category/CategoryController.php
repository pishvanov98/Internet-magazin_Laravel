<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\CategoryDescription;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show($slug)
    {
        $category = CategoryDescription::where('slug', $slug)->firstOrFail();
        dd($category);
    }
}
