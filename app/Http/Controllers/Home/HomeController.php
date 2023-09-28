<?php

namespace App\Http\Controllers\Home;


use App\Components\ImageComponent;
use App\Http\Controllers\Controller;
use App\Models\Img;
use App\Models\Manufacturer;
use Cviebrock\EloquentSluggable\Services\SlugService;
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

        $slider=DB::table('sliders')->where('location','Home')->first();
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

        if(Cache::has('NewGoodsSlaider')){
            $Products=Cache::get('NewGoodsSlaider');
        }else{
            $Products=app('Product')->NewGoodsSlaider();
            Cache::put('NewGoodsSlaider',$Products,10800);
        }

        $image=new ImageComponent();//ресайз картинок
        $Products->map(function ($item)use(&$image){
            if(!empty($item->image)){
                $image_name=substr($item->image,  strrpos($item->image, '/' ));
                $image->resizeImg($item->image,'product',$image_name,258,258);
                $item->image='/image/product/resize'.$image_name;
            }
            return $item;
        });

        //ресайз картинок

        $brands=$this->BrandListSlider();

        foreach ($brands as $key=>$brand){
                if(!empty($brand['image'])){
                    $image_name=substr($brand['image'],  strrpos($brand['image'], '/' ));
                    $image->resizeImg($brand['image'],'brand',$image_name,200,125);
                    $brand['image']='/image/brand/resize'.$image_name;
                    $brands[$key]=$brand;
                }
        }

        $brandsSlider1=[];
        $brandsSlider2=[];
        $i=1;
        foreach ($brands as $brand){
        if(($i % 2)){
            $brandsSlider1[]=$brand;
        }else{
            $brandsSlider2[]=$brand;
        }
            $i++;
        }
        $brandSliderOut=[$brandsSlider1,$brandsSlider2];

        return view('home',compact('images_slider','Products','brandSliderOut'));
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

        $Products->map(function ($item)use(&$image,&$AjaxProduct){
            if(!empty($item->image)){
                $image_name=substr($item->image,  strrpos($item->image, '/' ));
                $image->resizeImg($item->image,'product',$image_name,258,258);
                $item->image='/image/product/resize'.$image_name;
                $AjaxProduct[$item->product_id]=(array)$item;
            }
        });

        $idSlider=$data['idSlider'];

        $out='<div id="'.$idSlider.'" class="owl-carousel owl-theme slaider_prod">'; //формирую на отдачу в хтмл код с товарами

        foreach ($AjaxProduct as $product){
         $out .='<div class="card card_item'.$product['product_id'].'" style="width: 290px;min-height: 400px;">';

         if(!empty($product['image'])){
             $out .='<a class="card-title wrapper_img_card" href='.route('product.show',$product['slug']).'><img src="'.asset($product['image']).'"  class="card-img-top" alt=""></a>';
         }else{
             $out .='<img src="'.asset('img/zag_258x258.svg').'"  class="card-img-top" alt="">';
         }
          $out .='<div class="card-body">';
          $out .='<a class="card-title" href='.route('product.show',$product['slug']).'><h6 class="card-title">'.$product['name'].'</h6></a>';
          $out .='<div style="flex-direction: column" class="d-flex">';
          $out .='<div>';
          $out .='<p class="card-text">Стоимость '.$product['price'].'</p>';
          $out .='<div class="d-flex justify-content-between align-items-center">';
          $out .='<span onclick="addToCart('.$product['product_id'].')" class="btn btn-primary">Купить</span>';
          $out .='<span data-id="'.$product['product_id'].'" onclick="addToWishlist('.$product['product_id'].')" class="wishlist">';

          if(!empty($product['wishlist'])){
              $out .='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">';
              $out .='<path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>';
              $out .='</svg>';
          }else{
              $out .='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">';
              $out .='<path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>';
              $out .='</svg>';
          }
            $out .='</span>';

          $out .='</div>';
          $out .='</div>';
          $out .='<div class="d-flex justify-content-between pt-2">';
          $out .='<p class="mb-0">Код: '.$product['model'].'</p>';
            if($product['quantity'] < 1){
                $out .='<p class="mb-0"><span class="marker red"></span>Нет в наличии</p>';
            }else{
                $out .='<p class="mb-0"><span class="marker yellow"></span>В наличии</p>';
            }
          $out .='</div>';
          $out .='</div>';
          $out .='</div>';



         $out .='</div>';
    }
        $out .= '<div class="see_all_slider">';
        if($data['name'] == "actiya") {
            $out .= '<a href="'.route('action').'">Показать ещё →</a>';
        }else{
            $out .= '<a href="'.route('exclusive').'">Показать ещё →</a>';
        }
        $out .= '</div>';
   $out .='</div>';

    return $out;

    }

    public function BrandListSlider(){

        if(Cache::has('BrandListSlider')){
            $brand=Cache::get('BrandListSlider');
        }else{
            $brand=DB::connection('mysql2')->table('sd_manufacturer')
                ->select('manufacturer_id', 'name','image','slug')
                ->orderBy('sort_order','DESC')
                ->where('image','!=','')
                ->limit(40)
                ->get();
            Cache::put('BrandListSlider',$brand,86400);
        }
        $brand_mass=[];
        $imageComponent= new ImageComponent();
        foreach ($brand as $item) {

            if(empty($item->slug)){//чпу
                $manufacturer=Manufacturer::findOrFail($item->manufacturer_id);
                $slug = SlugService::createSlug(Manufacturer::class, 'slug', $manufacturer->name);//чпу slug
                $manufacturer->slug=$slug;
                $manufacturer->save();
                $item->slug=$slug;
            }

            $data=(array)$item;
            $brand_mass[$data['manufacturer_id']] = $data;
            if(!empty($brand_mass[$data['manufacturer_id']]['image'])){
                $image_name=substr($brand_mass[$data['manufacturer_id']]['image'],  strrpos($brand_mass[$data['manufacturer_id']]['image'], '/' ));
                $imageComponent->checkImg($brand_mass[$data['manufacturer_id']]['image'],$image_name,'brand');//проверяю есть ли на сервере эта картинка, если нет то создаю
                $brand_mass[$data['manufacturer_id']]['image']='/image/brand'.$image_name;
            }

        }

        return $brand_mass;


    }



}
