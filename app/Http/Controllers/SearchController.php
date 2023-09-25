<?php
namespace App\Http\Controllers;
use App\Components\ImageComponent;
use App\Models\CategoryDescription;
use App\Models\CategoryDescriptionAdmin;
use App\Models\ProductDescriptionAdmin;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SearchController extends Controller
{

    public function find(Request $request)
    {

        $category=app('Search')->GetSearchProductCategory(mb_strtolower($request->route('name')));
        $products_id=app('Search')->GetSearchProductName(mb_strtolower($request->route('name')));//получили id товаров, инициализируем
        $Products=app('Product')->ProductInit(array_column($products_id, 'product_id'));
        $Products_out=[];
        foreach ($Products as $key=> $item){
            $item_mass=(array)$item;
            if(!empty($item_mass['slug'])){
                $item_mass['slug']=route('product.show',$item_mass['slug']);
                $Products_out[$key]['name']=$item_mass['name'];
                $Products_out[$key]['slug']=$item_mass['slug'];
            }
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
                $slug=route('category.show',$category->slug);
                $One_category_massive=['slug_category'=>$slug,'name'=>$category->name];
            }

            array_unshift($Products_out,$One_category_massive);
        }

    return $Products_out;

    }

    public function find_admin_category(Request $request){

        $category = CategoryDescriptionAdmin::search($request->route('name'))->select('category_id','name')->get();
        return $category;

    }

    public function find_admin_product(Request $request){

        $category = ProductDescriptionAdmin::search($request->route('name'))->select('product_id','name')->get();
        return $category;

    }

    public function index(Request $request){
        $data=$request->all();
        $Products=[];
        $category_mass=[];
        $search='';
        $category='';
        $category_id='';
        $AttrCategory=[];
        if(!empty($data['search'])){
            $search=$data['search'];

            $page=0;

            if(!empty($data['category'])){
                $category_id=$data['category'];
                $array_column=  app('Search')->GetSearchProductNameSortToCategory(mb_strtolower($search),$data['category'], 300);//получили id товаров, инициализируем
                $array_column = array_column($array_column, 'product_id');
                $array_column = [$search, $array_column];
            }else{
                $array_column = $this->getArray_column($search);
            }

            if(!empty($data['page'])){
                $page=$data['page'];
            }
            $Products=app('Product')->ProductInit($array_column[1],24,$page);
            $category_mass_id=[];
            $image=new ImageComponent();//ресайз картинок
            $Products->map(function ($item)use(&$image,&$category_mass_id){
                $category_mass_id[$item->category_id]=$item->category_id;
                if(!empty($item->image)){
                    $image_name=substr($item->image,  strrpos($item->image, '/' ));
                    $image->resizeImg($item->image,'product',$image_name,258,258);
                    $item->image='/image/product/resize'.$image_name;
                }
                return $item;
            });

            //получаем фильтр атрибуты
            if(!empty($category_mass_id)){
                foreach ($category_mass_id as $item_cat){
                    $AttrCategory=app('Search')->GetSearchCategoryAttr($item_cat);
                }
            }

            $category_mass= app('Search')->GetSearchCategoryName($category_mass_id);
            if(!empty($data['category'])){
                $category=$category_mass[$data['category']][1];
            }
            $Products = $Products->appends(request()->query());
        }

        return view('search.index',compact('Products','search','category_mass','category','AttrCategory','category_id'));
    }

    /**
     * @param mixed $search
     * @return array|mixed|object
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    private function getArray_column(mixed $search): mixed
    {
        if (Cache::has('SearchMass_'.session()->getId())) {
            $array_column = Cache::get('SearchMass_'.session()->getId());
            if ($array_column[0] != $search) {
                $products_id = app('Search')->GetSearchProductName(mb_strtolower($search), 300);//получили id товаров, инициализируем
                $array_column = array_column($products_id, 'product_id');
                Cache::put('SearchMass_'.session()->getId(),[$search, $array_column],18000);
                $array_column = [$search, $array_column];
            }
        } else {
            $products_id = app('Search')->GetSearchProductName(mb_strtolower($search), 300);//получили id товаров, инициализируем
            $array_column = array_column($products_id, 'product_id');
            Cache::put('SearchMass_'.session()->getId(),[$search, $array_column],18000);
            $array_column = [$search, $array_column];
        }
        return $array_column;
    }

    public function getFilterProducts(Request $request){
        $data=$request->all();
        $category="";
        if(!empty($data['category'])){
            $category=$data['category'];
        }
        $array_search_id=[];

        if(!empty($data['string_art']) && !empty($data['search'])){

            $mass_art=explode('|',$data['string_art']);
            $mass_art_explode=[];
            foreach ($mass_art as $item) {
                if(!empty($item)){
                    $mass_art_explode[]=explode('#',$item);
                }

            }

            if(!empty($category)){
                $array_column=  app('Search')->GetSearchProductNameSortToCategory(mb_strtolower($data['search']),$category, 300);//получили id товаров, инициализируем
                $array_column = array_column($array_column, 'product_id');
                $filterProduct= array_unique($array_column);
            }else{
                if(Cache::has('SearchMass_'.session()->getId()) && !empty($data['search'])){
                    $array_search=Cache::get('SearchMass_'.session()->getId());
                    if ($array_search[0] == $data['search']) {
                        $array_search_id=$array_search[1];
                    }else{
                        return '';
                    }
                }else{
                    return '';
                }
                $filterProduct= $array_search_id;
            }
//            $page=0;
//            if(!empty($data['page'])){
//                $page=$data['page'];
//            }

            $Products=app('Product')->ProductInit($filterProduct);
            $image=new ImageComponent();//ресайз картинок
            $count_mass_art_explode=count($mass_art_explode);
            $Products->map(function ($item,$key)use(&$image,$mass_art_explode,$count_mass_art_explode,$Products){
                $count_true=0;
                if(!empty($item->product_attr)){
                    foreach ($item->product_attr as $itemAttr){
                       foreach ($mass_art_explode as $item_atr){
                        if($itemAttr->attribute_id == $item_atr[0] && $itemAttr->text == $item_atr[1]){
                            $count_true++;
                        }
                       }
                    }
                }
                if($count_true == $count_mass_art_explode){
                    if(!empty($item->image)){
                        $image_name=substr($item->image,  strrpos($item->image, '/' ));
                        $image->resizeImg($item->image,'product',$image_name,258,258);
                        $item->image='/image/product/resize'.$image_name;
                    }
                    return $item;
                }else{
                    $Products->forget($key);
                }
                return false;
            });
            $Products = collect($Products)->paginate(24);

                return view('components.categoryFilterSearch',['Products'=>$Products,'string_art'=>$data['string_art'],'search'=>$data['search'],'category'=>$category]);
        }else{
            if(!empty($data['search'])){

                if(!empty($category)){
                    $array_column=  app('Search')->GetSearchProductNameSortToCategory(mb_strtolower($data['search']),$category, 300);//получили id товаров, инициализируем
                    $array_column = array_column($array_column, 'product_id');
                    $array_column= array_unique($array_column);
                    $array_column = [$data['search'], $array_column];
                }else{
                    $array_column = $this->getArray_column($data['search']);
                }
                $page=0;
                if(!empty($data['page'])){
                    $page=$data['page'];
                }
                $Products=app('Product')->ProductInit($array_column[1],24,$page);

                $image=new ImageComponent();//ресайз картинок
                $Products->map(function ($item)use(&$image){
                    if(!empty($item->image)){
                        $image_name=substr($item->image,  strrpos($item->image, '/' ));
                        $image->resizeImg($item->image,'product',$image_name,258,258);
                        $item->image='/image/product/resize'.$image_name;
                    }
                    return $item;
                });

                    return view('components.categoryFilterSearch',['Products'=>$Products,'search'=>$data['search'],'category'=>$category]);
            }
        }
        return false;
    }

}
