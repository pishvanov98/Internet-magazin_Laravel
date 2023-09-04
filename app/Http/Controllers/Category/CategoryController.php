<?php

namespace App\Http\Controllers\Category;

use App\Components\ImageComponent;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CategoryDescription;
use App\Models\Img;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function show(Request $request)
    {

        $category = CategoryDescription::where('slug', $request->route('slug'))->firstOrFail();
        $CategoryTree=$this->categoryTree($category->category_id);
        $products_id_category=app('Search')->GetSearchAllProductToCategory($category->category_id);
        $page=0;
        $page = $request->get('page');
        $Products=app('Product')->ProductInit(array_column($products_id_category, 'id_product'),24,$page);

        $image=new ImageComponent();//ресайз картинок
        $Products->map(function ($item)use(&$image){
            if(!empty($item->image)){
                $image_name=substr($item->image,  strrpos($item->image, '/' ));
                $image->resizeImg($item->image,'product',$image_name,258,258);
                $item->image='/image/product/resize'.$image_name;
                return $item;
            }
        });

        $AttrCategory=app('Search')->GetSearchCategoryAttr($category->category_id);

        $slider=DB::table('sliders')->where('location','Category')->first();
        $images_slider=[];
        if(!empty($slider)){
            $data=(array)$slider;
            if(!empty($data['id_image'])){
                $images_explode=explode(',',$slider->id_image);

                $img = Img::whereIn('id', $images_explode)->orderBy('order','ASC')->get();
                foreach ($img as $val){
                    $images_slider[]=$val->toArray();
                }
            }
        }


        return view('category.index',compact('images_slider','Products','CategoryTree','AttrCategory'));

    }


    public function categoryTree($category_id){

       $array_category_out=[];
       $mainCategory = DB::connection('mysql2')->table('sd_category')->where('sd_category.category_id','=',$category_id)->where('sd_category.status','=',1)->select('sd_category.category_id','sd_category.parent_id','sd_category_description.name','sd_category_description.slug')
       ->join('sd_category_description','sd_category_description.category_id','=','sd_category.category_id')->first();
        $array_category_out[]=$mainCategory;
        //ищем родителей категории
       if(!empty($mainCategory->parent_id)){
           $ParentCategory1 = DB::connection('mysql2')->table('sd_category')->where('sd_category.category_id','=',$mainCategory->parent_id)->where('sd_category.status','=',1)->select('sd_category.category_id','sd_category.parent_id','sd_category_description.name','sd_category_description.slug')
               ->join('sd_category_description','sd_category_description.category_id','=','sd_category.category_id')->first();
           if(empty($ParentCategory1->slug)){
               $ParentCategory1->slug=app('Header')->SlugCategory($ParentCategory1->category_id);
           }
           $array_category_out[]=$ParentCategory1;

           if(!empty($ParentCategory1->parent_id)){
               $ParentCategory2 = DB::connection('mysql2')->table('sd_category')->where('sd_category.category_id','=',$ParentCategory1->parent_id)->where('sd_category.status','=',1)->select('sd_category.category_id','sd_category.parent_id','sd_category_description.name','sd_category_description.slug')
                   ->join('sd_category_description','sd_category_description.category_id','=','sd_category.category_id')->first();
               if(empty($ParentCategory2->slug)){
                   $ParentCategory2->slug=app('Header')->SlugCategory($ParentCategory2->category_id);
               }
               $array_category_out[]=$ParentCategory2;
           }

       }
       //ищем дочерние элементы категории
        $array_category_out = array_reverse($array_category_out, true);
        $Children = DB::connection('mysql2')->table('sd_category')->where('sd_category.parent_id','=',$mainCategory->category_id)->where('sd_category.status','=',1)->select('sd_category.category_id','sd_category.parent_id','sd_category_description.name','sd_category_description.slug')
            ->join('sd_category_description','sd_category_description.category_id','=','sd_category.category_id')->get();
        $Children->map(function ($item){
            if(empty($item->slug)){
                $item->slug=app('Header')->SlugCategory($ParentCategory2->category_id);
            }
            return $item;
        });
        $array_category_out['Children']=$Children->all();

        return $array_category_out;

    }
}
