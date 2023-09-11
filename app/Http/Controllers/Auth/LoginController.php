<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\CartUser;
use App\Models\SessionId;
use App\Models\WishlistUser;
use App\Providers\RouteServiceProvider;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    protected function authenticated(Request $request, $user)
    {
        $this->SetUserId();
        $this->loadDataDb();
        //$this->saveSessionId();
    }
    public function SetUserId(){

        if(!empty(Auth::user()->id)){
            session()->put('user_id',Auth::user()->id);
            session()->put('time_auth',strtotime(Carbon::now()->format('Y-m-d')));
        }
    }

    public function loadDataDb(){

            $cartDb= CartUser::where('user_id',Auth::user()->id)->first();
            $wishlistDb= WishlistUser::where('user_id',Auth::user()->id)->first();

        if(session()->has('cart') && !empty($cartDb->Cart)){
                $cartDb->Cart=unserialize($cartDb->Cart);
                $cart=session()->get('cart');
                $cart=array_merge($cart,$cartDb->Cart);
                session()->put('cart',$cart);
            }else{
                if(!empty($cartDb->Cart)){
                    $cartDb->Cart=unserialize($cartDb->Cart);
                    session()->put('cart',$cartDb->Cart);
                }
            }
            if(session()->has('wishlist')&& !empty($wishlistDb->wishlist)) {
                $wishlistDb->wishlist=unserialize($wishlistDb->wishlist);
                $wishlist=session()->get('wishlist');
                $wishlist=array_merge($wishlist,$wishlistDb->wishlist);
                session()->put('wishlist',$wishlist);
            }else{
                if(!empty($wishlistDb->wishlist)){
                    $wishlistDb->wishlist=unserialize($wishlistDb->wishlist);
                    session()->put('wishlist',$wishlistDb->wishlist);
                }
            }
}
public function saveSessionId(){

    SessionId::updateOrCreate([
        'user_id'   => Auth::user()->id,
    ],['user_id'=>Auth::user()->id,'SessionId'=>session()->getId()]);

}

}
