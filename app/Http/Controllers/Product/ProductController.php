<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Controller;
use App\Models\CategoryDescription;
use App\Models\ProductDescription;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Request $request)
    {

        $product = ProductDescription::where('slug', $request->route('slug'))->select('product_id')->firstOrFail();

        if(!empty($product)){
            $initProduct=app('Product')->ProductInit($product->product_id);
        }

        $initProduct=$initProduct->all();

        $category=CategoryDescription::findOrFail($initProduct['category_id']);

        if(empty($category->slug)){
            $slug = SlugService::createSlug(CategoryDescription::class, 'slug', $category->name);//чпу slug
            $category->slug=$slug;
            $category->save();
        }

        return view('product.index',['Product'=>$initProduct,'category'=>$category]);
    }
}
