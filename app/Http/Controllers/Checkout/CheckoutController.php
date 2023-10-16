<?php
namespace App\Http\Controllers\Checkout;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessSendingEmail;
use App\Models\CartUser;
use App\Models\Order;
use App\Models\Profile;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class CheckoutController extends Controller
{

    public function index(){
        $cart_info=app('Cart')->CheckCountProduct();
        if($cart_info['count_all_prod'] == 0){
            return redirect()->route('cart');
        }
        $Profile_fiz=[];
        $Profile_ur=[];
        if(!empty(Auth::user()->id)){
            $Profile_fiz=Profile::where('id_user',Auth::user()->id)->where('inn',Null)->where('company',Null)->get();
            if(empty($Profile_fiz->all())){
                $Profile_fiz=[];
            }

            $Profile_ur=Profile::where('id_user',Auth::user()->id)->where('inn','!=',Null)->where('company','!=',Null)->get();
            if(empty($Profile_ur->all())){
                $Profile_ur=[];
            }

        }
        $address=[];
        if(session()->has('address')){
            $address=session()->get('address');

        }
        $addressUr=[];
        if(session()->has('addressUr')){
            $addressUr=session()->get('addressUr');
        }

        return view('checkout.index',compact('cart_info','address','Profile_fiz','Profile_ur','addressUr'));
    }

    public function SaveOrder(Request $request){
        $data=$request->all();
        $validate=Validator::make($request->all(), [
            'name'=>'required',
            'Tel'=>'required|numeric',
            'mail'=>'required',
            'address'=>'required',
            'shipping'=>'required',
            'price'=>'required',
        ]);


        if ($validate->fails()) {

            return redirect()->route('checkout')
                ->withErrors($validate)
                ->withInput();
        }

        return $this->savingOrder($data);

    }

    public function SaveOrderUr(Request $request){
        $data=$request->all();
        $validate=Validator::make($request->all(), [
            'inn'=>'required|numeric',
            'company'=>'required',
            'name'=>'required',
            'Tel'=>'required|numeric',
            'mail'=>'required',
            'address'=>'required',
            'shipping'=>'required',
            'price'=>'required',
        ]);


        if ($validate->fails()) {

            return redirect()->route('checkout')
                ->withErrors($validate)
                ->withInput();
        }


        return $this->savingOrder($data);

    }


    private function savingOrder($data){
        $user_id=0;
        if(!empty(Auth::user()->id)){
            $user_id=Auth::user()->id;
        }

        $cart=session()->get('cart');
        $cart_prod=[];

        foreach ($cart as $key=>$item){
            if($item['active'] == 1){
                $cart_prod[]=$item['id'];
            }
        }
        $products_init=app('Product')->ProductInit($cart_prod);
        $products_mass=[];
        $discount=0;
        $coupon_name='';
        $comment='';
        $company='';
        $inn='';
        if(!empty($data['company'])){
            $company=$data['company'];
        }
        if(!empty($data['inn'])){
            $inn=$data['inn'];
        }
        if(!empty($data['comment'])){
            $comment=$data['comment'];
        }
        if (session()->has('coupon')){
            $discount_mass=session()->get('coupon');
            if(!empty($discount_mass['discount'])){
                $discount=$discount_mass['discount'];
                $coupon_name=$discount_mass['name'];
            }
        }
        $order= new Order();
        $order->name=$data['name'];
        $order->telephone=$data['Tel'];
        $order->mail=$data['mail'];
        $order->address=$data['address'];
        $order->shipping=$data['shipping'];
        $order->price=(int)str_replace(' ', '', $data['price']);
        $order->discount=$discount;
        $order->coupon=$coupon_name;
        $order->comment=$comment;
        $order->company=$company;
        $order->inn=$inn;
        $order->customer=$user_id;
        $order->save();

        $mytime = Carbon::now();

        $products_init->map(function ($val)use($order,$cart,&$products_mass,$mytime){
            $products_mass[]=['id_prod'=>$val->product_id,'id_order'=>$order->id,'count'=>$cart[$val->product_id]['quantity'],'price'=>$val->price,'created_at'=>$mytime->toDateTimeString(),'updated_at'=>$mytime->toDateTimeString()];
        });

        DB::table('productOrder')->insert($products_mass);


        session()->forget('cart');
        session()->forget('coupon');
        if(!empty(Auth::user()->id)){
            $cartDb=CartUser::where('user_id',Auth::user()->id)->first();//обновляю корзину
            if(!empty($cartDb)){
                $cartDb->delete();
            }
        }else{
            $cartDb=CartUser::where('session_id',session()->getId())->first();//обновляю корзину
            if(!empty($cartDb)){
                $cartDb->delete();
            }
        }
        //$this->sendMessage($order->mail,$data['price'],$order->id);
        return route('successfully',$order->id);
    }

    public function successfully(Request $request){
        if(!empty($request->route('id'))){
            $id=$request->route('id');
            return view('checkout.successfully',compact('id'));
        }
    }
    public function sendMessage($mail,$price,$id){
        $message="Здравствуйте, вы оформили заказ на сумму".$price."р. Номер заказа ".$id." наши менеджеры свяжутся с вами в ближайшее время";
        ProcessSendingEmail::dispatch($mail,$message);
    }

    public function SaveAddress(Request $request){
        $data=$request->all();
        if(!empty($data)){
            $address=['name'=>$data['name'],'Tel'=>$data['Tel'],'mail'=>$data['mail'],'address'=>$data['address'],'comment'=>$data['comment']];
            session()->put('address',$address);
        }
    }
    public function SaveAddressUr(Request $request){
        $data=$request->all();
        if(!empty($data)){
            $address=['name'=>$data['name'],'Tel'=>$data['Tel'],'mail'=>$data['mail'],'address'=>$data['address'],'comment'=>$data['comment'],'inn'=>$data['inn'],'company'=>$data['company']];
            session()->put('addressUr',$address);
        }
    }


    public function SelectAddress(Request $request){
        $data=$request->all();
        if(!empty($data['id'])){

           $profile= Profile::findOrFail($data['id']);
            if(!empty($profile)){
                $comment='';
                if(session()->has('address')){
                    $address_mass=session()->get('address');
                    if(!empty($address_mass['comment'])){
                        $comment=$address_mass['comment'];
                    }
                }
                $address=['name'=>$profile->name,'Tel'=>$profile->telephone,'mail'=>$profile->mail,'address'=>$profile->address,'comment'=>$comment];
                session()->put('address',$address);
                return $address;
            }
        }

        return false;
    }
    public function SelectAddress_ur(Request $request){
        $data=$request->all();
        if(!empty($data['id'])){

            $profile= Profile::findOrFail($data['id']);
            if(!empty($profile)){
                $comment='';
                if(session()->has('addressUr')){
                    $address_mass=session()->get('addressUr');
                    if(!empty($address_mass['comment'])){
                        $comment=$address_mass['comment'];
                    }
                }
                $address=['name'=>$profile->name,'Tel'=>$profile->telephone,'mail'=>$profile->mail,'address'=>$profile->address,'comment'=>$comment,'inn'=>$profile->inn,'company'=>$profile->company];
                session()->put('addressUr',$address);
                return $address;
            }
        }

        return false;
    }

}
