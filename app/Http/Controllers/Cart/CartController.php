<?php

namespace App\Http\Controllers\Cart;


use App\Components\ImageComponent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class CartController extends Controller
{
    public function index(){
        $cart=[];
        $products_init=[];
        if(session()->has('cart')){
          $cart=session()->get('cart');
        }

        $products_init=app('Product')->ProductInit(array_column($cart,'id'));



        $image=new ImageComponent();//ресайз картинок

        $products_init->map(function ($item)use (&$cart,&$image){
            $item->quantity_cart=$cart[$item->product_id]['quantity'];
            if(!empty($item->image)){
                $image_name=substr($item->image,  strrpos($item->image, '/' ));
                $image->resizeImg($item->image,'product',$image_name,258,258);
                $item->image='/image/product/resize'.$image_name;
                return $item;
            }
            return $item;
        });

        return view('cart.index',['Products'=>$products_init]);
    }

    public function addToCart(Request $request)
    {
    return json_encode(app('Cart')->addCart($request->get('id'),1));
    }
    public function DelToCart(Request $request)
    {
        return json_encode(app('Cart')->deleteCart($request->get('id')));
    }
}
