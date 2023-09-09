<?php

namespace App\Http\Controllers\Wishlist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class WishlistController extends Controller
{

    public function addToWishlist(Request $request)
    {
        $data=$request->all();
        if($data['id']){
            if(session()->has('wishlist')){
                $wishlist=session()->get('wishlist');
            }else{
                $wishlist=[];
            }
            if(!empty($wishlist[$data['id']])){
                unset($wishlist[$data['id']]);
                session()->put('wishlist',$wishlist);
                return 0;
            }else{
                $wishlist[$data['id']]=$data['id'];
                session()->put('wishlist',$wishlist);
                return 1;
            }

        }

    }



}
