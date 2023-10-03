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

            $coupon=Coupon::where( 'name', 'like', '%' . $data['value']. '%')->where('status',1)->first();
            if (!empty($coupon)){
                //проверка на кол-во использований
                if ($coupon->limit <= $coupon->count_use){
                    session()->forget('coupon');
                    return 0;
                }

                //проверка на тип юзера
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

                if (session()->has('coupon')){
                    $coupon_session= session()->get('coupon');
                    if($coupon_session['name'] == $coupon->name){
                        session()->flash('statusCoupon', 'Купон '.$coupon->name.' уже активирован');
                        return 1;
                    }
                }
                if ($coupon->type == 1){
                    $cart_info=app('Cart')->CheckCountProduct();
                    $sum = str_replace(" ", "", $cart_info['itogo']);
                    if((int)$sum <= (int)$coupon->min_value){
                        session()->flash('statusCoupon', 'Для активации Купона '.$coupon->name.' необходимо набрать товаров на минимальную сумму в размере '.number_format($coupon->min_value, 0, '', ' ')." ₽");
                        return 1;
                    }
                }

                session()->put('coupon',['type'=>$coupon->type,'name'=>$coupon->name,'value'=>$coupon->value,'min_value'=>$coupon->min_value,'discount'=>0]);
                session()->flash('statusCoupon', 'Купон '.$coupon->name.' активирован');

                $coupon->count_use++;
                $coupon->update();
                return 1;
            }
        }
        session()->forget('coupon');
        return 0;
    }
}
