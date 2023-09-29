<?php

namespace App\Http\Controllers\Product;

use App\Components\ImageComponent;
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
        $initProduct=$initProduct[0];
        $initProduct=(array)$initProduct;
        $attr_mass=[];
        $category_id=$initProduct['category_id'];
        if(!empty($initProduct['product_attr'])){
            foreach ($initProduct['product_attr'] as $item){
                $attr_mass[]=[$item->attribute_id,$item->text];
            }

        }

        $AttrProduct= app('Search')->GetSearchProductAttr($category_id,$attr_mass,20);
        $AttrProduct=array_unique($AttrProduct);
        $searchIdProdAttr=array_search($product->product_id, $AttrProduct);
        if(!empty($searchIdProdAttr)){
            unset($AttrProduct[$searchIdProdAttr]);
        }
        $initProductAttr=[];
        if(!empty($AttrProduct)){
            $initProductAttr=app('Product')->ProductInit($AttrProduct);

            if(count($initProductAttr) < 3){
                $initProductAttr=[];
            }
        }

        $category=CategoryDescription::findOrFail($initProduct['category_id']);

        if(empty($category->slug)){
            $slug = SlugService::createSlug(CategoryDescription::class, 'slug', $category->name);//чпу slug
            $category->slug=$slug;
            $category->save();
        }

        return view('product.index',['Product'=>$initProduct,'category'=>$category,'initProductAttr'=>$initProductAttr]);
    }
}
