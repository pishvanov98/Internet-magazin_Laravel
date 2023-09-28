<?php
namespace App\Http\Controllers\Manufacturer;

use App\Components\ImageComponent;
use App\Http\Controllers\Controller;
use App\Models\Manufacturer;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManufacturerController extends Controller
{
    public function index(){

       $manufacturers= Manufacturer::where('image','!=','')->select('manufacturer_id','name','image','slug')->orderBy('name','asc')->paginate(49);

        $image=new ImageComponent();//ресайз картинок
        $manufacturers->map(function ($item)use(&$image){
            if(empty($item->slug)){//чпу
                $manufacturer=Manufacturer::findOrFail($item->manufacturer_id);
                $slug = SlugService::createSlug(Manufacturer::class, 'slug', $manufacturer->name);//чпу slug
                $manufacturer->slug=$slug;
                $manufacturer->save();
                $item->slug=$slug;
            }
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
        $manufacturer=[];
        if(!empty($request->route('slug'))){
            $manufacturer=DB::connection('mysql2')->table('sd_manufacturer')
                ->select('sd_manufacturer.name','sd_manufacturer_description.description','sd_manufacturer.manufacturer_id')
                ->where('sd_manufacturer.slug',$request->route('slug'))
                ->join('sd_manufacturer_description','sd_manufacturer_description.manufacturer_id','sd_manufacturer.manufacturer_id')
                ->first();


            $Products_id_mass=app('Search')->GetSearchManufacturerProduct($manufacturer->manufacturer_id);

            $page=0;
            $page = $request->get('page');
            $Products=app('Product')->ProductInit($Products_id_mass,40,$page);

        }
        return view('manufacturer.show',compact('manufacturer','Products'));
    }
}
