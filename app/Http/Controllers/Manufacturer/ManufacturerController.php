<?php
namespace App\Http\Controllers\Manufacturer;

use App\Components\ImageComponent;
use App\Http\Controllers\Controller;
use App\Models\Manufacturer;

class ManufacturerController extends Controller
{
    public function index(){

       $manufacturers= Manufacturer::where('image','!=','')->select('manufacturer_id','name','image')->orderBy('name','asc')->paginate(49);

        $image=new ImageComponent();//ресайз картинок
        $manufacturers->map(function ($item)use(&$image){
            if(!empty($item->image)){
                $image_name=substr($item->image,  strrpos($item->image, '/' ));
                $image->resizeImg($item->image,'brand',$image_name,200,125);
                $item->image='/image/brand/resize'.$image_name;
            }
            return $item;
        });

        return view('manufacturer.index',compact('manufacturers'));
    }
    public function show(){
        return '1';
    }
}
