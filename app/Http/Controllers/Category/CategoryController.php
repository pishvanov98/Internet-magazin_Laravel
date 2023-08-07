<?php

namespace App\Http\Controllers\Category;

use App\Components\ImageComponent;
use App\Http\Controllers\Controller;
use App\Models\CategoryDescription;
use App\Models\Img;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function show(Request $request)
    {

        $category = CategoryDescription::where('slug', $request->route('slug'))->firstOrFail();

        $products_id_category=app('Search')->GetSearchAllProductToCategory($category->category_id);

        $Products=app('Product')->ProductInit($products_id_category,20);

        $image=new ImageComponent();//ресайз картинок
        $Products->map(function ($item)use(&$image){
            if(!empty($item->image)){
                $image_name=substr($item->image,  strrpos($item->image, '/' ));
                $image->resizeImg($item->image,'product',$image_name,258,258);
                $item->image='/image/product/resize'.$image_name;
                return $item;
            }
        });

        $slider=DB::table('sliders')->where('location','Категории')->first();
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


        return view('category.index',compact('images_slider','Products'));

    }
}
