<?php
namespace App\Http\Controllers;
use App\Models\CategoryDescription;
use App\Models\CategoryDescriptionAdmin;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;

class SearchController extends Controller
{

    public function find(Request $request)
    {

        $category=app('Search')->GetSearchProductCategory(mb_strtolower($request->route('name')));
        $products_id=app('Search')->GetSearchProductName(mb_strtolower($request->route('name')));//получили id товаров, инициализируем
        $Products=app('Product')->ProductInit(array_column($products_id, 'product_id'));
        $Products_out=[];
        foreach ($Products as $key=> $item){
            $item=(array)$item;
            $item['slug']=route('product.show',$item['slug']);
            $Products_out[$key]['name']=$item['name'];
            $Products_out[$key]['slug']=$item['slug'];
        }

        if(!empty($category[0])){

            $category=CategoryDescription::findOrFail($category[0]['id_category']);

            if(empty($category['slug'])){
                $slug = SlugService::createSlug(CategoryDescription::class, 'slug', $category->name);//чпу slug
                $category->slug=$slug;
                $category->save();
                $slug=route('category.show',$slug);
                $One_category_massive=['slug_category'=>$slug,'name'=>$category->name];
            }else{
                $One_category_massive=['slug_category'=>$category->slug,'name'=>$category->name];
            }

            array_unshift($Products_out,$One_category_massive);
        }

return $Products_out;

    }

    public function find_admin_category(Request $request){

        $category = CategoryDescriptionAdmin::search($request->route('name'))->select('category_id','name')->get();
        return $category;

    }

}
