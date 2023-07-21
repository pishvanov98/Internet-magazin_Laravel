<?php

namespace App\Providers;

use App\Models\Category;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $category = Category::with(['description'])->where('status',1)->where('top',1)->get();
        $category_mass=[];
        foreach ($category as $key=> $item) {
            $data=$item->toArray();
            $parent=$data['parent_id'];
            if(empty($parent)) {//проверка на главную категорию
                $category_mass[$data['category_id']] = $data;
                $category_mass[$data['category_id']]['href'] = url('/category/' . $data['category_id']);
            }

        }
        foreach ($category as $key=> $item) {
            $data=$item->toArray();
            $parent=$data['parent_id'];
            if(!empty($parent)) {//проверка на дочерние элементы
                if(!empty($category_mass[$parent])){
                    $category_mass[$parent]['children'][$data['category_id']]=$data;
                    $category_mass[$parent]['children'][$data['category_id']]['href']=url('/category/' . $data['category_id']);
                }
            }

        }
        foreach ($category as $key1=> $item) {
            $data=$item->toArray();
            $parent=$data['parent_id'];
            //проверка на дочерние элементы дочерних элементов

            foreach ($category_mass as $key2=>$val){//если у ребенка есть ребенок то наследуем

                if(!empty($val['children'][$parent])){
                    $category_mass[$key2]['children'][$parent]['children_children'][$data['category_id']]=$data;
                    $category_mass[$key2]['children'][$parent]['children_children'][$data['category_id']]['href']=url('/category/' . $data['category_id']);;
                }
            }
        }

        view()->composer('layouts.app2', function ($view) use ($category_mass) {
            $view->with('categories', $category_mass);
        });
    }
}
