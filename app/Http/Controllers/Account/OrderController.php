<?php

namespace App\Http\Controllers\Account;

use App\Components\ImageComponent;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{

    public function index(){
        $select='order';

        $order_list=Order::where('customer',Auth::user()->id)->get();

        return view('account.order.index',compact('select','order_list'));
    }

    public function show(Request $request){

        $order=Order::findOrFail($request->route('id'));
        $order_prod=ProductOrder::where('id_order',$request->route('id'))->get();
        $product_mass=[];

        $order_prod->map(function ($val)use(&$product_mass){
            $product_mass[$val->id_prod]=['id'=>$val->id_prod,'count'=>$val->count,'price'=>$val->price,'total'=>(int)$val->price * (int)$val->count];//price за единицу
        });
        $select='order';
        $products_init=app('Product')->ProductInit(array_column($product_mass,'id'));



        $image=new ImageComponent();//ресайз картинок

        $products_init->map(function ($item)use (&$product_mass,&$image){
            $item->quantity_buy=$product_mass[$item->product_id]['count'];
            $item->price=$product_mass[$item->product_id]['price'];
            $item->total=$product_mass[$item->product_id]['total'];
            if(!empty($item->image)){
                $image_name=substr($item->image,  strrpos($item->image, '/' ));
                $image->resizeImg($item->image,'product',$image_name,258,258);
                $item->image='/image/product/resize'.$image_name;
                return $item;
            }
            return $item;
        });

        return view('account.order.show',compact('products_init','order','select'));

    }

}
