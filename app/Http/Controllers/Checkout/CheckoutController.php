<?php
namespace App\Http\Controllers\Checkout;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessSendingEmail;
use App\Models\Order;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;


class CheckoutController extends Controller
{

    public function index(){
        $cart_info=app('Cart')->CheckCountProduct();
        if($cart_info['count_all_prod'] == 0){
            return redirect()->route('cart');
        }

        $Profile=Profile::where('id_user',Auth::user()->id)->get();

$address=[];
        if(session()->has('address')){
            $address=session()->get('address');

        }

        return view('checkout.index',compact('cart_info','address','Profile'));
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

$user_id=0;
if(!empty(Auth::user()->id)){
    $user_id=Auth::user()->id;
}
        $order= new Order();
        $order->name=$data['name'];
        $order->telephone=$data['Tel'];
        $order->mail=$data['mail'];
        $order->address=$data['address'];
        $order->shipping=$data['shipping'];
        $order->products=serialize(session()->get('cart'));
        $order->price=(int)str_replace(' ', '', $data['price']);
        $order->customer=$user_id;
        $order->save();
        session()->forget('cart');
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
            if (session()->has('address') && !empty($data['id_key_address'])){
                $address= session()->get('address');
                $address=['name'=>$data['name'],'Tel'=>$data['Tel'],'mail'=>$data['mail'],'address'=>$data['address']];
            }else{
                $address=['name'=>$data['name'],'Tel'=>$data['Tel'],'mail'=>$data['mail'],'address'=>$data['address']];
            }
            session()->put('address',$address);
        }
    }

}
