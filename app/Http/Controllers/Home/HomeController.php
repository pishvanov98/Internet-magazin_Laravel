<?php

namespace App\Http\Controllers\Home;


use App\Components\ImageComponent;
use App\Http\Controllers\Controller;
use App\Models\Img;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;
use function app;
use function asset;
use function public_path;
use function view;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       // $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $slider=DB::table('sliders')->where('location','На главной')->first();
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
        //Новинки
        $NewGoodsSlaider=[];
        $Products=app('Product')->NewGoodsSlaider();
        $image=new ImageComponent();//ресайз картинок
        $Products->map(function ($item)use(&$image){
            if(!empty($item->image)){
                $image_name=substr($item->image,  strrpos($item->image, '/' ));
                $image->resizeImg($item->image,'product',$image_name,258,258);
                $item->image='/image/product/resize'.$image_name;
                return $item;
            }
        });

        //ресайз картинок


        return view('home',compact('images_slider','Products'));
    }


    public function getAjaxProduct(Request $request){
    $data=$request->all();
        if(!empty($data['name'])){
            $value_search=$data['name'];
            if($value_search == "actiya"){
                $value_search=['6+1','3+1','1+1=3','Подарок за покупку', '10+2', 'акция', 'специальная цена', '5+1', '3+1', 'asepta1', 'asepta2', 'asepta3', '2+1', 'asepta'];
            }
        }else{
            return '';
        }
        $Products=app('Product')->ExclusiveSlaider($value_search);

        $AjaxProduct=[];
        //ресайз картинок
        $image=new ImageComponent();
        foreach ($Products as $product){
            $product=(array)$product;
            if (!empty($product['image'])){
                $image_name=substr($product['image'],  strrpos($product['image'], '/' ));
                $image->resizeImg($product['image'],'product',$image_name,258,258);
                $product['image']='/image/product/resize'.$image_name;
            }
            $AjaxProduct[$product['product_id']]=$product;
        }

        $idSlider=$data['idSlider'];

        $out='<div id="'.$idSlider.'" class="owl-carousel owl-theme slaider_prod">'; //формирую на отдачу в хтмл код с товарами

        foreach ($AjaxProduct as $product){
         $out .='<div class="card" style="width: 290px;min-height: 400px;">';

         if(!empty($product['image'])){
             $out .='<img src="'.asset($product['image']).'"  class="card-img-top" alt="">';
         }else{
             $out .='<img src="'.asset('img/zag_258x258.svg').'"  class="card-img-top" alt="">';
         }
          $out .='<div class="card-body">';
          $out .='<h6 class="card-title">'.$product['name'].'</h6>';
          $out .='<p class="card-text">Стоимость '.$product['price'].'</p>';
          $out .='<a href="#" class="btn btn-primary">Купить</a>';
          $out .='</div>';


         $out .='</div>';
    }
   $out .='</div>';

    return $out;

    }

    public function testRedis(){

        $cache=Cache::get('test');

        if($cache){
            Cache::forget('test');
        }else{
            Cache::put('test','текст');
        }

        return '';
    }

}
