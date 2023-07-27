<?php

namespace App\Http\Controllers;


use App\Models\Img;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Intervention\Image\Facades\Image;

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

        //ресайз картинок
        foreach ($Products as $product){
            if (!empty($product['image'])){
                $image_name=substr($product['image'],  strrpos($product['image'], '/' ));
                $this->resizeImg($product['image'],'product',$image_name,258,258);
                $product['image']='/image/product/resize'.$image_name;
            }
            $NewGoodsSlaider[$product['product_id']]=$product;
        }



        return view('home',compact('images_slider','NewGoodsSlaider'));
    }

    public function resizeImg($url,$directory,$image_name,$width,$height){

        if (!file_exists( public_path('/image/'.$directory.'/resize').$image_name)){
            $thumbnail = Image::make(public_path($url));
            $thumbnail->resize($width,$height);
            $thumbnail->save(public_path('/image/product/resize').$image_name);
        }

    }

}
