<?php

namespace App\Components;

use Illuminate\Support\Facades\DB;

class ProductComponent
{
    public function index(){
        return 'тест';
    }

    public function NewGoodsSlaider(){//получение последних 20 товаров

        $mass_prod_id=[];
        $products_id=DB::connection('mysql2')->table('sd_product')->select('product_id')->where('quantity','>',0)->where('price','>',0)->latest('product_id')->limit(20)->get();
        $products_id->each(function ($item) use(&$mass_prod_id){
            $mass_prod_id[]=$item->product_id;
        });

       $products= $this->ProductInit($mass_prod_id);
        return $products;

    }


    public function ProductInit($mass_prod_id){//получение информации по товару
    $products_mass=[];
        $query=DB::connection('mysql2')->table('sd_product')->whereIn('sd_product.product_id',$mass_prod_id)
            ->select('sd_product.product_id', 'sd_product.price', 'sd_product.model', 'sd_product.mpn', 'sd_product.quantity', 'sd_product.manufacturer_id', 'sd_product.image', 'sd_product_description.name', 'sd_product_description.description', 'sd_product_description.seo_title', 'sd_product_description.seo_h1', 'sd_product_description.tag' )
            ->where('sd_product.status',1)
            ->join('sd_product_description','sd_product.product_id','=','sd_product_description.product_id');
        $products= $query->get();

        $products_discount=DB::connection('mysql2')->table('sd_product_discount')->whereIn('sd_product_discount.product_id',$mass_prod_id)//получил цены в зависимости от группы пользователя
            ->select('sd_product_discount.product_id', 'sd_product_discount.price', 'sd_product_discount.customer_group_id')
            ->get();

        $products_attr=DB::connection('mysql2')->table('sd_product_attribute')->whereIn('sd_product_attribute.product_id',$mass_prod_id)//получил атрибуты товара
        ->select('sd_product_attribute.product_id', 'sd_product_attribute.attribute_id', 'sd_product_attribute.text')
            ->get();

        $products->each(function ($item) use (&$products_mass, &$products_discount, &$products_attr){
            $data=(array)$item;
            $customer_group_id=0;
            $products_mass[$data['product_id']]=$data;
            if($products_mass[$data['product_id']]['mpn'] == 1){
                //mpn спец предложение в случае mpn = 1 делаем запрос на получение цены
                $product_special=DB::connection('mysql2')->table('sd_product_special')->where('sd_product_special.product_id',$products_mass[$data['product_id']]['product_id'])//получил спец цену на товар
                    ->select('sd_product_special.price', 'sd_product_special.customer_group_id', 'sd_product_special.priority')
                    ->get();
                $products_mass[$data['product_id']]['special_price']=$product_special->all();
            }
            $filtered_discount = $products_discount->where('product_id', $data['product_id']);
            if(!empty($filtered_discount)){
                $products_mass[$data['product_id']]['product_discount']=$filtered_discount->all();
                //если известен customer_group_id то заменяем price на нужный

                if(isset($customer_group_id)){
                    $price_customer_group=$filtered_discount->where('customer_group_id',$customer_group_id)->first();
                    $price_customer_group=(array)$price_customer_group;
                    $products_mass[$data['product_id']]['price']=$price_customer_group['price'];
                }

            }
            $filtered_attr = $products_attr->where('product_id', $data['product_id']);
            if(!empty($filtered_attr)){
                $products_mass[$data['product_id']]['product_attr']=$filtered_attr->all();
            }
            $products_mass[$data['product_id']]['DB']='Aveldent';//ставлю метку что товары загружены из базы авеля
        });

        return($products_mass);





    }
}
