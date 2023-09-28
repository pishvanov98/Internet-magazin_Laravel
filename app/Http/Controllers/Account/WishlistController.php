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

        }else{
            $Products=false;
        }

        return view('account.wishlist.index',compact('select','Products'));
    }


}
