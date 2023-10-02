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
        $statusCoupon='';
        if(session()->has('statusCoupon')){
         $statusCoupon=session()->get('statusCoupon');
        }

        $coupon=[];
        if(session()->has('coupon')){
            $coupon=session()->get('coupon');
        }
        $cart= app('Cart')->update_cart($cart);

        $cart_info=app('Cart')->CheckCountProduct();

        $products_init=app('Product')->ProductInit(array_column($cart,'id'));



        $image=new ImageComponent();//ресайз картинок

        $products_init->map(function ($item)use (&$cart,&$image){
            $item->quantity_cart=$cart[$item->product_id]['quantity'];
            $item->active=$cart[$item->product_id]['active'];
            if(!empty($item->image)){
                $image_name=substr($item->image,  strrpos($item->image, '/' ));
                $image->resizeImg($item->image,'product',$image_name,258,258);
                $item->image='/image/product/resize'.$image_name;
                return $item;
            }
            return $item;
        });

        return view('cart.index',['Products'=>$products_init,'cart_info'=>$cart_info,'statusCoupon'=>$statusCoupon,'coupon'=>$coupon]);
    }

    public function addToCart(Request $request)
    {
        $count=1;
        if(!empty($request->get('count'))){
            $count=$request->get('count');
        }
    return json_encode(app('Cart')->addCart($request->get('id'),$count));
    }
    public function DelToCart(Request $request)
    {
        return json_encode(app('Cart')->deleteCart($request->get('id')));
    }

    public function CheckCountProduct(Request $request){
        return json_encode(app('Cart')->CheckCountProduct($request->get('id')));
    }

    public function UpdateCountProduct(Request $request){
        return json_encode(app('Cart')->UpdateCountProduct($request->get('id'),$request->get('Count')));
    }

    public function ActiveProduct(Request $request){
        return json_encode(app('Cart')->ActiveProduct($request->get('id')));
    }

    public function ActiveAllProduct(Request $request){
        return json_encode(app('Cart')->ActiveAllProduct($request->get('result')));
    }

    public function DelAllCart(Request $request){
        return json_encode(app('Cart')->DelAllCart($request->get('id')));
    }
}
