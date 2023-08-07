<?php

namespace App\Components;

use App\Models\CategoryDescription;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\DB;

class HeaderComponent
{

    public function __construct()
    {
        $this->CategoryList();
        $this->BrandList();
    }

    public function CategoryList(){


            $category = DB::connection('mysql2')->table('sd_category')
                ->select('sd_category.category_id', 'sd_category.parent_id', 'sd_category_description.name', 'sd_category_description.slug')
                ->join('sd_category_description','sd_category.category_id','=','sd_category_description.category_id')
                ->where('sd_category.status', '=', '1')
                ->where('sd_category.top', '=', '1')
                ->orderBy('sd_category_description.name', 'asc')
                ->get();

            $category_mass=[];
            foreach ($category as $key=> $item) {
                $data=(array)$item;
                $parent=$data['parent_id'];
                if(empty($parent)) {//проверка на главную категорию
                    $category_mass[$data['category_id']] = $data;

                    if(empty($data['slug'])){
                        $slug=  $this->SlugCategory($data['category_id']);
                        $category_mass[$data['category_id']]['href'] = url('/category/' . $slug);
                    }else{
                        $category_mass[$data['category_id']]['href'] = url('/category/' . $data['slug']);
                    }
                }

            }
            foreach ($category as $key=> $item) {
                $data=(array)$item;
                $parent=$data['parent_id'];
                if(!empty($parent)) {//проверка на дочерние элементы
                    if(!empty($category_mass[$parent])){
                        $category_mass[$parent]['children'][$data['category_id']]=$data;

                        if(empty($data['slug'])){
                            $slug=  $this->SlugCategory($data['category_id']);
                            $category_mass[$parent]['children'][$data['category_id']]['href'] = url('/category/' . $slug);
                        }else{
                            $category_mass[$parent]['children'][$data['category_id']]['href']=url('/category/' . $data['slug']);
                        }
                    }
                }

            }
            foreach ($category as $key1=> $item) {
                $data=(array)$item;
                $parent=$data['parent_id'];
                //проверка на дочерние элементы дочерних элементов

                foreach ($category_mass as $key2=>$val){//если у ребенка есть ребенок то наследуем

                    if(!empty($val['children'][$parent])){
                        $category_mass[$key2]['children'][$parent]['children_children'][$data['category_id']]=$data;

                        if(empty($data['slug'])){
                            $slug=  $this->SlugCategory($data['category_id']);
                            $category_mass[$key2]['children'][$parent]['children_children'][$data['category_id']]['href'] = url('/category/' . $slug);
                        }else{
                            $category_mass[$key2]['children'][$parent]['children_children'][$data['category_id']]['href']=url('/category/' . $data['slug']);
                        }
                    }
                }
            }

        view()->composer('layouts.app', function ($view) use ($category_mass) {
            $view->with('categories', $category_mass);
        });


    }

    public function BrandList(){

        $brand=DB::connection('mysql2')->table('sd_manufacturer')
            ->select('manufacturer_id', 'name')
            ->orderBy('sort_order','ASC')
            ->where('sort_order','>','0')
            ->get();
        $brand_mass=[];
        foreach ($brand as $key=> $item) {
            $data=(array)$item;
                    $brand_mass[$data['manufacturer_id']] = $data;
                    $brand_mass[$data['manufacturer_id']]['href'] = url('/manufacturer/' . $data['manufacturer_id']);
        }

        view()->composer('layouts.app', function ($view) use ($brand_mass) {
            $view->with('brands', $brand_mass);
        });

    }

    public function SlugCategory($id){

            //чпу
            $category_description=CategoryDescription::findOrFail($id);
            $slug = SlugService::createSlug(CategoryDescription::class, 'slug', $category_description->name);//чпу slug
            $category_description->slug=$slug;
            $category_description->save();
            return $slug;

    }


}
