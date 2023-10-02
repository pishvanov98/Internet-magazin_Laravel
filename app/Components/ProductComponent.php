<?php

namespace App\Components;

use App\Models\ProductDescription;
use App\Models\UserType;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class ProductComponent
{
    public function index(){
        return 'тест';
    }

    public function NewGoodsSlaider($all=false,$paginate = false,$page=false){//получение последних 20 товаров

        $mass_prod_id=[];
        $products_id=DB::connection('mysql2')->table('sd_product')->select('product_id')
            ->where('quantity','>',0)
            ->where('price','>',0)
            ->where('status','=',1)
            ->latest('product_id');
            if($all === false){
                $products_id->limit(20);
            }else{
                $products_id->limit(400);
            }
        $products_id= $products_id->get();
        $products_id->each(function ($item) use(&$mass_prod_id){
            $mass_prod_id[]=$item->product_id;
        });

        if($all === false){
            $products= $this->ProductInit($mass_prod_id);
        }else{
            $products= $this->ProductInit($mass_prod_id,$paginate,$page);
        }

        return $products;

    }
    public function ExclusiveSlaider($value_search,$all=false,$paginate = false,$page=false){//получение последних 20 товаров

        $mass_prod_id=[];
        $query=DB::connection('mysql2')->table('sd_product')->select('sd_product.product_id')
            ->where('sd_product.price','>',0)
            ->where('sd_product.status','=',1);
        if (is_array($value_search)){
            foreach ($value_search as  $key=> $val){

                if($key == 0){
                    $query->Where('sd_product_description.tag', 'LIKE', '%'.$val.'%');
                }else{
                    $query->orWhere('sd_product_description.tag', 'LIKE', '%'.$val.'%');
                }
                $query->where('sd_product.quantity','>',0);
            }
        }else{
            $query->where('sd_product_description.tag','LIKE','%'.$value_search.'%');
            $query->where('sd_product.quantity','>',0);
        }

        $query->join('sd_product_description','sd_product.product_id','=','sd_product_description.product_id')
            ->latest('sd_product.product_id');

        if($all === false){
            $query->limit(20);
        }

        $products_id=$query->get();

        $products_id->each(function ($item) use(&$mass_prod_id){
            $mass_prod_id[]=$item->product_id;
        });
        if($all === false){
            $products= $this->ProductInit($mass_prod_id);
        }else{
            $products= $this->ProductInit($mass_prod_id,$paginate,$page);
        }

        return $products;

    }


    public function ProductInit($mass_prod_id,$paginate = false,$page=false){//получение информации по товару

        if(is_array($mass_prod_id)){
            $products_out=$mass_prod_id;

            $max=$paginate;
            $min=0;
            if(!empty($page)){
                $max=$page * $paginate;
                $min=$max - $paginate;
            }

            if(!empty($paginate)){
                $mass_prod_id=array_slice($mass_prod_id, $min, $paginate,true);//срезаем не нужные id
            }




            foreach ($mass_prod_id as $key=>$item){//если существует товар в кеше берем его из кеша, если нет то делаем запрос и помещаем в кеш

                if(Cache::has('product_'.$item)) {
                    $result = Cache::get('product_' . $item);
                    $products_out[$key]=$result;
                    unset($mass_prod_id[$key]);
                }
            }
        }else{
            $products_out=[];
            if(Cache::has('product_'.$mass_prod_id)) {
                $result = Cache::get('product_' . $mass_prod_id);
                $products_out[]=$result;
                unset($mass_prod_id);
            }
        }


if(!empty($mass_prod_id)){

    $imageComponent= new ImageComponent();

    $query=DB::connection('mysql2')->table('sd_product')->whereIn('sd_product.product_id',$mass_prod_id)
        ->select('sd_product.product_id', 'sd_product.price', 'sd_product.model', 'sd_product.sku', 'sd_product.mpn', 'sd_product.quantity', 'sd_product.manufacturer_id', 'sd_manufacturer.image AS manufacturer_image', 'sd_manufacturer.name AS manufacturer_name' , 'sd_manufacturer.strana AS manufacturer_region', 'sd_product.image', 'sd_product_description.name', 'sd_product_description.description', 'sd_product_description.seo_title', 'sd_product_description.seo_h1', 'sd_product_description.tag', 'sd_product_description.slug','sd_product_to_category.category_id' )
        ->where('sd_product.status','=',1)
        ->where('sd_product_to_category.main_category','=',1)
        ->where('sd_product_to_category.category_id','!=',568)//убрал подарки
        ->join('sd_product_description','sd_product.product_id','=','sd_product_description.product_id')
        ->join('sd_product_to_category','sd_product.product_id','=','sd_product_to_category.product_id')
        ->leftJoin('sd_manufacturer','sd_product.manufacturer_id','=','sd_manufacturer.manufacturer_id');

        $products= $query->get();


    $products_discount=DB::connection('mysql2')->table('sd_product_discount')->whereIn('sd_product_discount.product_id',$mass_prod_id)//получил цены в зависимости от группы пользователя
    ->select('sd_product_discount.product_id', 'sd_product_discount.price', 'sd_product_discount.customer_group_id')
        ->get();

    $products_attr=DB::connection('mysql2')->table('sd_product_attribute')->whereIn('sd_product_attribute.product_id',$mass_prod_id)//получил атрибуты товара
    ->select('sd_product_attribute.product_id', 'sd_product_attribute.attribute_id', 'sd_product_attribute.text','sd_attribute_description.name')
        ->join('sd_attribute_description','sd_attribute_description.attribute_id','sd_product_attribute.attribute_id')
        ->get();




    $products->map(function ($item) use (&$products_out,&$products, &$products_discount, &$products_attr,&$imageComponent){


        $customer_group_id=0;//обычный пользователь 2 это вип
        if($item->mpn == 1){
            //mpn спец предложение в случае mpn = 1 делаем запрос на получение цены
            $product_special=DB::connection('mysql2')->table('sd_product_special')->where('sd_product_special.product_id',$item->product_id)//получил спец цену на товар
            ->select('sd_product_special.price', 'sd_product_special.customer_group_id', 'sd_product_special.priority')
                ->get();
            $item->special_price=$product_special->all();
        }
        $filtered_discount = $products_discount->where('product_id', $item->product_id);
        if(!empty($filtered_discount)){
            $item->product_discount=$filtered_discount->all();
            //если известен customer_group_id то заменяем price на нужный

            if(isset($customer_group_id)){
                $price_customer_group=$filtered_discount->where('customer_group_id',$customer_group_id)->first();
                $price_customer_group=(array)$price_customer_group;
                if(!empty($price_customer_group['price'])){
                    $item->price=$price_customer_group['price'];
                }
            }

        }

        $filtered_attr = $products_attr->where('product_id', $item->product_id);
        if(!empty($filtered_attr)){
            $item->product_attr=$filtered_attr->all();
        }

        if(!empty($item->image)){
            $image_name=substr($item->image,  strrpos($item->image, '/' ));
            $imageComponent->checkImg($item->image,$image_name,'product');//проверяю есть ли на сервере эта картинка, если нет то создаю
            $item->image='/image/product'.$image_name;
            //Ресайз
            $imageComponent->resizeImg($item->image,'product',$image_name,258,258);
            $item->image='/image/product/resize'.$image_name;
        }
        if(!empty($item->manufacturer_image)){
            $image_name_manufacturer=substr($item->manufacturer_image,  strrpos($item->manufacturer_image, '/' ));
            $imageComponent->checkImg($item->manufacturer_image,$image_name_manufacturer,'brand');//проверяю есть ли на сервере эта картинка, если нет то создаю
            $item->manufacturer_image='/image/brand'.$image_name_manufacturer;
        }



        if (!empty($item->model)){
            $item->model= mb_substr($item->model, -5);
        }


        if(empty($item->slug)){//чпу
            $product_description=ProductDescription::findOrFail($item->product_id);
            $slug = SlugService::createSlug(ProductDescription::class, 'slug', $product_description->name);//чпу slug
            $product_description->slug=$slug;
            $product_description->save();
            $item->slug=$slug;
        }

        if (is_array($products_out)){

            $key = array_search($item->product_id,$products_out, true);
            Cache::put('product_'.$item->product_id,$item);

            $products_out[$key]=$item;
        }else{
            Cache::put('product_'.$item->product_id,$item);

            $products_out=$item;
        }

        return $item;
    });

}

        $products_out=collect($products_out);
        $user_type=0;

    if(!empty(Auth::user()->id)) {
        $type_user = UserType::where('id', Auth::user()->id)->first();
        if(empty($type_user->user_type)){
            $user_type = 0;
        }else{
            $user_type = $type_user->user_type;
        }
        session()->put('user_type', $user_type);
    }


if(session()->has('wishlist')){
$wishlist=session()->get('wishlist');
}else{
$wishlist='';
}
//type=1 = фиксированная скидка
//type=2 = процент скидка
$coupon=[];
if(session()->has('coupon')){
    $coupon=session()->get('coupon');
}

if(!empty($wishlist) || !empty($user_type) || !empty($coupon)){
    $products_out=  $this->checkWishlistAndGroupUserAndCoupon($products_out,$wishlist,$user_type,$coupon);
}
if(!empty($paginate)){
    return($products_out->paginate($paginate));
}else{
    return($products_out);
}
    }


    public function checkWishlistAndGroupUserAndCoupon($products_out,$wishlist,$user_type,$coupon){

        $products_out->map(function ($item) use ($wishlist,$user_type,$coupon){
            if(!empty($item->product_id)){//проходим по всем элементам которые выводим и проверяем в избранном они и проверяем цену
                if(!empty($wishlist[$item->product_id])){
                    $item->wishlist=1;
                }
                if(!empty($coupon) && $coupon['type'] == 2){
                    $item->old_price=$item->price;
                    $item->price=(int)round($item->price - ($item->price * ($coupon['value'] / 100)));
                    return $item;
                }

                if(!empty($user_type) && $user_type == 2 || !empty($user_type) && $user_type == 3){
                    foreach ($item->product_discount as $value){
                        if($value->customer_group_id == $user_type){
                            $item->old_price=$item->price;
                            $item->price=$value->price;
                        }
                    }
                }
            }

            return $item;
        });
    return $products_out;
    }


}
