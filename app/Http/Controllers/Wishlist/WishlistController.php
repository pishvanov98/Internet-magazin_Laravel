<?php

namespace App\Http\Controllers\Wishlist;

use App\Http\Controllers\Controller;

use App\Models\WishlistUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


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
                $this->update_wishlist($wishlist,(int)$data['id']);
                return 0;
            }else{
                $wishlist[$data['id']]=(int)$data['id'];
                $this->update_wishlist($wishlist,(int)$data['id']);
                return 1;
            }

        }

    }

    public function update_wishlist($wishlist,$id=0){

        if(empty(Auth::user()->id)){
            return  session()->put('wishlist',$wishlist);
        }

        $wishlistDb=WishlistUser::where('user_id',Auth::user()->id)->first();//обновляю корзину

        if(!empty($wishlistDb->wishlist)){
            $wishlist_massDb=unserialize($wishlistDb->wishlist);
            foreach ($wishlist_massDb as $item){

                if(empty($wishlist[$item]) && $item != $id){
                    $wishlist[$item]=$item;
                }
            }
            $wishlistDb->wishlist=serialize($wishlist);
            $wishlistDb->update();
        }else{
            WishlistUser::create(['user_id'=>Auth::user()->id,'wishlist'=>serialize($wishlist)]);
        }

        session()->put('wishlist',$wishlist);
    }


}
