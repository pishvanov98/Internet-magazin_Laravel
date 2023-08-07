<?php

namespace App\Http\Controllers\Category;

use App\Http\Controllers\Controller;
use App\Models\CategoryDescription;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function show(Request $request)
    {
        $category = CategoryDescription::where('slug', $request->route('slug'))->firstOrFail();
        dd($category);
    }
}
