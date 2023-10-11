<?php
namespace App\Components;



use App\Models\CartUser;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

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
                    $product_id[]=(int)$val['id'];
                    $quantity[$val['id']]=$val['quantity'];
                }
            }

            $coupon=[];
            if(session()->has('coupon')){
                $coupon=session()->get('coupon');
            }

           $products= App('Product')->ProductInit($product_id);
            $itogo=0;
            $itogoDiscount=0;
            $products->map(function ($item)use (&$itogo,&$quantity,$coupon,&$itogoDiscount){
                if(!empty($coupon) && $coupon['type'] == 2){
                    $itogoDiscount=$itogoDiscount + ($item->old_price * $quantity[$item->product_id]);
                }

                $itogo= $itogo + ($item->price * $quantity[$item->product_id]);
            });
            if(!empty($coupon)){
                if( $coupon['type'] == 2){
                    $coupon['discount']=(int)round($itogoDiscount-$itogo);
                    session()->put('coupon',$coupon);
                }else{
                    $coupon['discount']=(int)$coupon['value'];
                    session()->put('coupon',$coupon);
                    $itogo=$itogo-(int)$coupon['value'];
                }
            }


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
                    $product_id[]=(int)$val['id'];
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
            $coupon=[];
            if(session()->has('coupon')){
                $coupon=session()->get('coupon');
            }
            $products= App('Product')->ProductInit($product_id);
            $itogo=0;
            $itogoDiscount=0;
            $discount=0;
            $products->map(function ($item)use (&$itogo,&$quantity,$coupon,&$itogoDiscount){

                if(!empty($coupon) && $coupon['type'] == 2){
                    $itogoDiscount=$itogoDiscount + ($item->old_price * $quantity[$item->product_id]);
                }

                $itogo= $itogo + ($item->price * $quantity[$item->product_id]);
            });

            if(!empty($coupon)){
                if( $coupon['type'] == 2){
                    $coupon['discount']=(int)round($itogoDiscount-$itogo);
                    $discount=$coupon['discount'];
                    session()->put('coupon',$coupon);
                }elseif($coupon['type'] == 1){
                    $coupon['discount']=(int)$coupon['value'];
                    $discount=$coupon['discount'];
                    session()->put('coupon',$coupon);
                    $itogo=$itogo-(int)$coupon['value'];
                }
            }
            $itogoAddDiscount=0;
            if(!empty($coupon['discount'])){
                $itogoAddDiscount=number_format((int)$discount + (int)$itogo, 0, '', ' ');
            }

            return ['itogoAddDiscount'=>$itogoAddDiscount,'discount'=>number_format($discount, 0, '', ' '),'count_prod'=>$quantity_find_prod,'count_all_prod'=>$count,'itogo'=>number_format($itogo, 0, '', ' ')];
        }else{
            return ['itogoAddDiscount'=>0,'discount'=>0,'count_prod'=>0,'count_all_prod'=>0,'itogo'=>0];
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
        $this->update_cart($cart,$id);
        return $this->checkCart();
    }
    public function deleteCart($id){

        if(session()->has('cart')) {
            $cart = session()->get('cart');

            if (!empty($cart[$id]) && $cart[$id]['quantity'] > 1) {
                $cart[$id]['quantity'] = $cart[$id]['quantity'] - 1;
            } else {
                if(isset($cart[$id])){
                    unset($cart[$id]);
                }
            }

            $this->update_cart($cart,$id);
        }
        return $this->checkCart();
    }
    public function DelAllCart($id){

        if(!empty($id)){
            $cart = session()->get('cart');
            unset($cart[$id]);

           $this->update_cart($cart,$id);

        }else{
            session()->forget('cart');
            $this->update_cart(0,0);
        }
        return true;
    }

    public function update_cart($cart,$id=0){

        if(empty(Auth::user()->id)){
            return $this->update_cart_guest($cart,$id);
        }

        if($cart == 0){
            $cartDb=CartUser::where('user_id',Auth::user()->id)->first();//обновляю корзину
            $cartDb->delete();
            return false;
        }

        $cartDb=CartUser::where('user_id',Auth::user()->id)->first();//обновляю корзину
        if(!empty($cartDb->Cart)){
            $cart_massDb=unserialize($cartDb->Cart);
            foreach ($cart_massDb as $item){
                if(empty($cart[$item['id']]) && $item['id'] != $id){
                    $cart[$item['id']]=$item;
                }else{
                    if(!empty($cart[$item['id']]['quantity']) && $cart[$item['id']]['quantity'] < $item['quantity'] && $item['id'] != $id){
                        $cart[$item['id']]=$item;
                    }
                }
            }
            $cartDb->Cart=serialize($cart);
            $cartDb->update();
        }else{
            CartUser::create(['user_id'=>Auth::user()->id,'Cart'=>serialize($cart)]);
        }

        session()->put('cart',$cart);
        return $cart;
    }
    public function update_cart_guest($cart,$id=0){
    $session_id=session()->getId();
        if($cart == 0){
            $cartDb=CartUser::where('session_id',$session_id)->first();//обновляю корзину
            $cartDb->delete();
            return false;
        }

        $cartDb=CartUser::where('session_id',$session_id)->first();//обновляю корзину
        if(!empty($cartDb->Cart)){
            $cart_massDb=unserialize($cartDb->Cart);
            foreach ($cart_massDb as $item){
                if(empty($cart[$item['id']]) && $item['id'] != $id){
                    $cart[$item['id']]=$item;
                }else{
                    if(!empty($cart[$item['id']]['quantity']) && $cart[$item['id']]['quantity'] < $item['quantity'] && $item['id'] != $id){
                        $cart[$item['id']]=$item;
                    }
                }
            }
            $cartDb->Cart=serialize($cart);
            $cartDb->update();
        }else{
            CartUser::create(['session_id'=>$session_id,'Cart'=>serialize($cart)]);
        }

        session()->put('cart',$cart);
        return $cart;

    }
}
