<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\ProductDescription;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request)
    {

        $product = ProductDescription::where('slug', $request->route('slug'))->firstOrFail();

        if(!empty($product)){
            $initProduct=app('Product')->ProductInit($product);
        }

        return view('product.index',['Product'=>$initProduct->all()]);
    }
}
