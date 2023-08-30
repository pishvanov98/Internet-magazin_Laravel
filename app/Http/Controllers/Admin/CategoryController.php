<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\CategoryAdmin;
use App\Models\CategoryDescriptionAdmin;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index(){

        $categories_all=DB::connection('mysql2')->table('sd_category_description')->select('sd_category_description.category_id','sd_category_description.name')->get();

        $categories=DB::connection('mysql2')->table('sd_category')->select('sd_category.category_id','sd_category.parent_id','sd_category.status','sd_category_description.name')
            ->join('sd_category_description', 'sd_category.category_id','=','sd_category_description.category_id')
            ->orderBy('sd_category.category_id', 'DESC')
            ->groupBy('sd_category_description.name')
            ->paginate(60);

        $categories->map(function ($item) use (&$categories_all){

          $search_name=  $categories_all->where('category_id',$item->parent_id)->first();

            $search_name=(array)$search_name;
            if(!empty($search_name)){
                $item->parent_name=$search_name['name'];//если есть родитель записываем его имя
            }

            return $item;

        });

        $title='Список категорий';
        $page="category";
        return view('admin.category.index',compact('title','categories','page'));
    }

    public function create(){
        $title='Создание категории';

        $categories=DB::connection('mysql2')->table('sd_category')->select('sd_category.category_id','sd_category_description.name')
            ->join('sd_category_description', 'sd_category.category_id','=','sd_category_description.category_id')
            ->where('sd_category.status','=','1')
            ->orderBy('sd_category_description.name', 'asc')
            ->groupBy('sd_category_description.name')
            ->get();

        return view('admin.category.create',compact('title','categories'));
    }

    public function store(Request $request){


        $validate=$request->validate([
            'name'=>'required',
            'description'=>'required',
            'parent'=>'required',
        ]);


        $category=new CategoryAdmin();
        $category->parent_id=$validate['parent'];
        $category->top=1;
        $category->column=1;
        $category->sort_order=0;
        $category->status=1;
        $category->save();

        $categoryDescription= new CategoryDescriptionAdmin();
        $categoryDescription->category_id=$category->category_id;
        $categoryDescription->language_id=1;
        $categoryDescription->name=$validate['name'];
        $categoryDescription->description=$validate['description'];
        $categoryDescription->meta_description='';
        $categoryDescription->meta_keyword='';
        $categoryDescription->seo_title='';
        $categoryDescription->seo_h1='';
        $categoryDescription->slug='';
        $categoryDescription->save();
        app('Search')->InsertDataProductCategory();
        return redirect()->route('admin.category');

    }

    public function edit(Request $request){
        $category_mass=[];
        $category=CategoryAdmin::findOrFail($request->route('id'));
        $categoryDescription=CategoryDescriptionAdmin::findOrFail($request->route('id'));

        $categories=DB::connection('mysql2')->table('sd_category')->select('sd_category.category_id','sd_category_description.name')
            ->join('sd_category_description', 'sd_category.category_id','=','sd_category_description.category_id')
            ->where('sd_category.status','=','1')
            ->orderBy('sd_category_description.name', 'asc')
            ->groupBy('sd_category_description.name')
            ->get();

        $category_mass['parent']=$category->parent_id;
        $category_mass['name']=$categoryDescription->name;
        $category_mass['description']=$categoryDescription->description;
        $title='Редактирование категории';
        $id=$request->route('id');
        return view('admin.category.update',compact('title','categories','id','category_mass'));

    }

    public function update(Request $request){


        $validate=$request->validate([
            'name'=>'required',
            'description'=>'required',
            'parent'=>'required',
        ]);



        $category=CategoryAdmin::findOrFail($request->route('id'));
        $category->parent_id=$validate['parent'];
        $category->update();

        $categoryDescription= CategoryDescriptionAdmin::findOrFail($request->route('id'));
        $categoryDescription->name=$validate['name'];
        $categoryDescription->description=$validate['description'];
        $categoryDescription->update();

        return redirect()->route('admin.category');

    }

    public function destroy(Request $request){
        $category=CategoryAdmin::findOrFail($request->route('id'));
        $category->delete();
        $categoryDescription=CategoryDescriptionAdmin::findOrFail($request->route('id'));
        $categoryDescription->delete();
        return redirect()->route('admin.category');
    }


}
