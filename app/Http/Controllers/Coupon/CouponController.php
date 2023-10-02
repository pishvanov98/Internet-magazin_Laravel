<?php
namespace App\Http\Controllers\Coupon;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function CheckCoupon(Request $request){

        $data=$request->all();
        if(!empty($data['value'])){

            $coupon=Coupon::where( 'name', 'like', '%' . $data['value']. '%')->first();
            if (!empty($coupon)){
                $user_type=0;
                if (session()->has('user_type')){
                    $user_type=session()->get('user_type');
                }
                if (!empty($user_type)){
                    session()->forget('coupon');
                    return 0;
                }
                //type=1 = фиксированная скидка
                //type=2 = процент скидка
                session()->put('coupon',['type'=>$coupon->type,'name'=>$coupon->name,'value'=>$coupon->value,'min_value'=>$coupon->min_value,'discount'=>0]);
                session()->flash('statusCoupon', 'Купон '.$coupon->name.' активирован');
                return 1;
            }
        }
        session()->forget('coupon');
        return 0;
    }
}
