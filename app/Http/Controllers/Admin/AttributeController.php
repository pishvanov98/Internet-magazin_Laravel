<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\AttributeAdmin;
use App\Models\AttributeDescriptionAdmin;
use App\Models\ManufacturerAdmin;
use App\Models\ManufacturerDescriptionAdmin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index(){

        $attributes=DB::connection('mysql2')->table('sd_attribute')->select('sd_attribute.attribute_id','sd_attribute_description.name')
            ->join('sd_attribute_description','sd_attribute_description.attribute_id','=','sd_attribute.attribute_id')
            ->orderBy('sd_attribute_description.attribute_id', 'DESC')
            ->paginate(40);

        $title='Список Атрибутов товара';
        $page="attribute";
        return view('admin.attribite.index',compact('title','attributes','page'));
    }

    public function create(){
        $title='Создание Атрибута товара';

        return view('admin.attribite.create',compact('title'));
    }

    public function store(Request $request){


        $validate=$request->validate([
            'name'=>'required',
            'attribute_group_id'=>'required',
        ]);


        $attribute=new AttributeAdmin();
        $attribute->attribute_group_id=$validate['attribute_group_id'];
        $attribute->sort_order=0;
        $attribute->save();
        $attributeDescription=new AttributeDescriptionAdmin();
        $attributeDescription->attribute_id=$attribute->attribute_id;
        $attributeDescription->language_id=1;
        $attributeDescription->name=$validate['name'];
        $attributeDescription->save();

        return redirect()->route('admin.attribute');

    }


    public function destroy(Request $request){
        $Manufacturer=AttributeAdmin::findOrFail($request->route('id'));
        $Manufacturer->delete();
        $ManufacturerDescription=AttributeDescriptionAdmin::findOrFail($request->route('id'));
        $ManufacturerDescription->delete();
        return redirect()->route('admin.attribute');
    }


}
