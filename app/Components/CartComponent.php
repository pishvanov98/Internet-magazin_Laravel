<?php
namespace App\Components;



use Illuminate\Support\Facades\App;

class CartComponent
{
    public function checkCart(){

        if(session()->has('cart')){
            $cart=session()->get('cart');
            $count=0;
            $product_id=[];
            $quantity=[];
            foreach ($cart as $val){
                $count=$count+$val['quantity'];
                $product_id[]=$val['id'];
                $quantity[$val['id']]=$val['quantity'];
            }

           $products= App('Product')->ProductInit($product_id);
            $itogo=0;
            $products->map(function ($item)use (&$itogo,&$quantity){
                $itogo= $itogo + ($item->price * $quantity[$item->product_id]);
            });


            return $count." Товаров - ".number_format($itogo, 0, '', ' ')." руб.";
        }else{
            return "0 Товаров - 0 руб.";
        }
        return "1 Товар - 32 руб.";
    }
    public function addCart($id,$quantity){
        $cart=[];

        if(session()->has('cart')){
            $cart=session()->get('cart');
        }
        if(!empty($cart[$id])){
            $cart[$id]['quantity']=$cart[$id]['quantity'] + $quantity;
        }else{
            $cart[$id]=['id'=>$id,'quantity'=>$quantity];
        }
        session()->put('cart',$cart);
        return $this->checkCart();
    }
    public function deleteCart($id){

        if(session()->has('cart')) {
            $cart = session()->get('cart');

            if (!empty($cart[$id]) && $cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity'] = $cart[$id]['quantity'] - 1;
                session()->put('cart', $cart);
            } else {
                if(isset($cart[$id])){
                    unset($cart[$id]);
                }
            }
            session()->put('cart', $cart);
        }
        return $this->checkCart();
    }
}
