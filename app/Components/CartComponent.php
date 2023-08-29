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
                if($val['active'] == 1){
                    $count=$count+$val['quantity'];
                    $product_id[]=$val['id'];
                    $quantity[$val['id']]=$val['quantity'];
                }
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

    }

    public function CheckCountProduct($id = false){
        if(session()->has('cart')){
            $cart=session()->get('cart');



            $count=0;
            $product_id=[];
            $quantity=[];
            $quantity_find_prod=0;
            foreach ($cart as $val){
                if($val['active'] == 1){
                    $count=$count+$val['quantity'];
                    $product_id[]=$val['id'];
                    $quantity[$val['id']]=$val['quantity'];
                    if($id == $val['id'] && $id !== false){
                        $quantity_find_prod=$val['quantity'];
                    }
                }else{
                    if($id == $val['id'] && $id !== false){
                        $quantity_find_prod=$val['quantity'];
                    }
                }
            }

            $products= App('Product')->ProductInit($product_id);
            $itogo=0;
            $products->map(function ($item)use (&$itogo,&$quantity){
                $itogo= $itogo + ($item->price * $quantity[$item->product_id]);
            });



            return ['count_prod'=>$quantity_find_prod,'count_all_prod'=>$count,'itogo'=>number_format($itogo, 0, '', ' ')];
        }
    }

    public function UpdateCountProduct($id,$Count){
        if(session()->has('cart')) {
            $cart = session()->get('cart');

            if(!empty($cart[$id])){
                $cart[$id]['quantity']=$Count;
                session()->put('cart', $cart);
                return true;
            }

        }
        return false;
    }
    public function ActiveProduct($id){
        if(session()->has('cart')) {
            $cart = session()->get('cart');

            if(!empty($cart[$id])){
                if($cart[$id]['active'] == 0){
                    $cart[$id]['active']=1;
                }else{
                    $cart[$id]['active']=0;
                }
                session()->put('cart', $cart);
                return true;
            }

        }
        return false;
    }

    public function ActiveAllProduct($result){
        if(session()->has('cart')) {
            $cart = session()->get('cart');

            foreach ($cart as $val){

                if($result == 0){
                    $cart[$val['id']]['active']=0;
                }else{
                    $cart[$val['id']]['active']=1;
                }

            }
            session()->put('cart', $cart);
            return true;
        }
        return false;
    }

    public function addCart($id,$quantity){
        $cart=[];

        if(session()->has('cart')){
            $cart=session()->get('cart');
        }
        if(!empty($cart[$id])){
            $cart[$id]['quantity']=$cart[$id]['quantity'] + $quantity;
        }else{
            $cart[$id]=['id'=>$id,'quantity'=>$quantity,'active'=>1];
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
