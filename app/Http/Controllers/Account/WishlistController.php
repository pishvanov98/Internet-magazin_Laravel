<?php
namespace App\Http\Controllers\Account;

use App\Components\ImageComponent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class WishlistController extends Controller
{

    public function index(Request $request){
        $select='wishlist';

        if(session()->has('wishlist')){
            $wishlist=session()->get('wishlist');
            $page=0;
            if(!empty($request->get('page'))){
                $page = $request->get('page');
            }

            $Products=app('Product')->ProductInit($wishlist,40,$page);

            $image=new ImageComponent();//ресайз картинок
            $Products->map(function ($item)use(&$image){
                if(!empty($item->image)){
                    $image_name=substr($item->image,  strrpos($item->image, '/' ));
                    $image->resizeImg($item->image,'product',$image_name,258,258);
                    $item->image='/image/product/resize'.$image_name;
                }
                return $item;
            });

        }else{
            $Products=false;
        }

        return view('account.wishlist.index',compact('select','Products'));
    }


}
