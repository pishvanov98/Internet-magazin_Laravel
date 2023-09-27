<?php
namespace App\Http\Controllers\Manufacturer;

use App\Components\ImageComponent;
use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use App\Models\ManufacturerDescription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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
    public function show(Request $request){
        if(!empty($request->route('id'))){
            $brand=DB::connection('mysql2')->table('sd_manufacturer')
                ->select('sd_manufacturer.name','sd_manufacturer_description.description')
                ->where('sd_manufacturer.manufacturer_id',$request->route('id'))
                ->join('sd_manufacturer_description','sd_manufacturer_description.manufacturer_id','sd_manufacturer.manufacturer_id')
                ->first();
        }
        return view('manufacturer.show',compact('brand'));
    }
}
