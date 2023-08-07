<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\ProductDescription;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request)
    {
        // $post = Post::where('id', $id)->where('published', 1)->firstOrFail();
        $product = ProductDescription::where('slug', $request->route('slug'))->firstOrFail();
        dd($product);
    }
}
