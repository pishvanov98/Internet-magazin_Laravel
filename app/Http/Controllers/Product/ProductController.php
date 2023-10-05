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

        if(session()->has('viewed_products')){
            $viewed_products=session()->get('viewed_products');
            if (count($viewed_products) > 5){
                array_shift($viewed_products);
            }

            $searchViewed=array_search($product->product_id, $viewed_products);
            if($searchViewed !== false ){}else{
                $viewed_products[]=$product->product_id;
            }
        }else{
            $viewed_products[]=$product->product_id;
        }
        session()->put('viewed_products',$viewed_products);

        $initProduct=$initProduct->all();
        $initProduct=$initProduct[0];
        $initProduct=(array)$initProduct;
        //разделяем описание
        if(strlen($initProduct['description']) > 500){
            $arr = explode('<br />' , $initProduct['description']);
            $arr_2 = array_slice ($arr , 0, 15);
            $str_2 = implode('<br />' , $arr_2 );
            $arr_4 = array_slice ($arr , 15);
            $str_4 = implode('<br />' , $arr_4 );
            $initProduct['description_two'] = $str_4;
            $initProduct['description'] = $str_2;
        }

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
        if($searchIdProdAttr !== false){
            unset($AttrProduct[$searchIdProdAttr]);
        }
        $initProductAttr=[];
        if(!empty($AttrProduct)){
            $initProductAttr=app('Product')->ProductInit($AttrProduct);

            if(count($initProductAttr) < 3){
                $initProductAttr=[];
            }
        }

        $initProductViewed=[];

        if(!empty($viewed_products) && count($viewed_products) >= 5){
            $initProductViewed=app('Product')->ProductInit(array_reverse($viewed_products));
        }
        $category=CategoryDescription::findOrFail($initProduct['category_id']);

        if(empty($category->slug)){
            $slug = SlugService::createSlug(CategoryDescription::class, 'slug', $category->name);//чпу slug
            $category->slug=$slug;
            $category->save();
        }

        return view('product.index',['Product'=>$initProduct,'category'=>$category,'initProductAttr'=>$initProductAttr,'initProductViewed'=>$initProductViewed]);
    }
}
