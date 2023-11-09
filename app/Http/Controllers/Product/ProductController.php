<?php

namespace App\Http\Controllers\Product;

use App\Components\ImageComponent;
use App\Http\Controllers\Controller;
use App\Models\CategoryDescription;
use App\Models\ProductDescription;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
        $attr_out_group=[];
        $category_id=$initProduct['category_id'];
        //18 атрибут группа товара свойство которое связано с другими товарами
        if(!empty($initProduct['product_attr'])){
            foreach ($initProduct['product_attr'] as $item){

                if ($item->attribute_id == 18){
                    $attr_out_group_mass_id=DB::connection('mysql2')->table('sd_product_attribute')->where('text','=',$item->text)->pluck('product_id');
                }

                $attr_mass[]=[$item->attribute_id,$item->text];
            }

        }
        if(!empty($attr_out_group_mass_id)){

            $attr_out_group_attr=DB::connection('mysql2')->table('sd_product_attribute')->whereIn('product_id',$attr_out_group_mass_id->all())->select('sd_product_attribute.product_id','sd_product_attribute.attribute_id','sd_product_attribute.text','sd_attribute_description.name')
                ->join('sd_attribute_description','sd_attribute_description.attribute_id','=','sd_product_attribute.attribute_id')
                ->whereIn('sd_product_attribute.attribute_id',array_column($attr_mass,0))
                ->where('sd_product_attribute.attribute_id','!=','18')
                ->where('sd_product_attribute.attribute_id','!=','16')
                ->get();

            $attr_out_group_by=$attr_out_group_attr->groupBy('product_id');

            $attr_out_group_by->map(function ($item) use(&$attr_out_group){
                foreach ($item->all() as $item_attr){
                    $attr_out_group[$item_attr->name][$item_attr->text]=$item_attr->product_id;
                }
            });

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

        return view('product.index',['Product'=>$initProduct,'category'=>$category,'initProductAttr'=>$initProductAttr,'initProductViewed'=>$initProductViewed,'attr_out_group'=>$attr_out_group]);
    }


    public function GetProduct(Request $request){

        $data=$request->all();
        if(!empty($data['id'])){
            $initProduct=app('Product')->ProductInit($data['id']);
            return(route('product.show',$initProduct[0]->slug));
        }

      return '';

    }


}
