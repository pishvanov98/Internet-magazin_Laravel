<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\ManufacturerAdmin;
use App\Models\ManufacturerDescriptionAdmin;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ManufacturerController extends Controller
{
    public function index(){

        $manufacturers=DB::connection('mysql2')->table('sd_manufacturer')->select('sd_manufacturer.manufacturer_id','sd_manufacturer.name','sd_manufacturer.sort_order','sd_manufacturer.strana')
            ->orderBy('sd_manufacturer.name', 'ASC')
            ->paginate(40);

        $title='Список Производителей';
        $page="manufacturer";
        return view('admin.manufacturer.index',compact('title','manufacturers','page'));
    }

    public function create(){
        $title='Создание производителя';

        return view('admin.manufacturer.create',compact('title'));
    }

    public function store(Request $request){


        $validate=$request->validate([
            'name'=>'required',
            'sort'=>'required',
            'strana'=>'required',
            'description'=>'required',
            'image'=>'required',
        ]);



        if($request->file('image')){

            $filename = $validate['image']->getClientOriginalName();

            //Сохраняем оригинальную картинку
            $validate['image']->move(public_path('/image/brand/'),$filename);

            $manufacturer=new ManufacturerAdmin();
            $manufacturer->name=$validate['name'];
            $manufacturer->image='/'.$filename;
            $manufacturer->sort_order=$validate['sort'];
            $manufacturer->strana=$validate['strana'];
            $manufacturer->slider=0;
            $manufacturer->save();

            $manufacturerDescription= new ManufacturerDescriptionAdmin();
            $manufacturerDescription->manufacturer_id=$manufacturer->manufacturer_id;
            $manufacturerDescription->language_id=1;
            $manufacturerDescription->description=$validate['description'];
            $manufacturerDescription->meta_description='';
            $manufacturerDescription->meta_keyword='';
            $manufacturerDescription->seo_title='';
            $manufacturerDescription->seo_h1='';
            $manufacturerDescription->save();
        }

        return redirect()->route('admin.manufacturer');

    }


    public function destroy(Request $request){
        $Manufacturer=ManufacturerAdmin::findOrFail($request->route('id'));
        $file = new Filesystem();
        if($file->exists(public_path('/image/brand/'),$Manufacturer->image)){
            $file->delete(public_path('/image/brand/'),$Manufacturer->image);
        }
        $Manufacturer->delete();
        $ManufacturerDescription=ManufacturerDescriptionAdmin::findOrFail($request->route('id'));
        $ManufacturerDescription->delete();
        return redirect()->route('admin.manufacturer');
    }


}
