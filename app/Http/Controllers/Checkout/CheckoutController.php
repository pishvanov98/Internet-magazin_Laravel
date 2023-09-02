<?php
namespace App\Http\Controllers\Checkout;
use App\Http\Controllers\Controller;
use App\Jobs\ProcessSendingEmail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class CheckoutController extends Controller
{

    public function index(){
        $cart_info=app('Cart')->CheckCountProduct();
        if($cart_info['count_all_prod'] == 0){
            return redirect()->route('cart');
        }
        return view('checkout.index',compact('cart_info'));
    }

    public function SaveOrder(Request $request){
        $validate=$request->validate([
            'name'=>'required',
            'Tel'=>'required|numeric',
            'mail'=>'required',
            'address'=>'required',
            'shipping'=>'required',
            'price'=>'required',
        ]);

$user_id=0;
if(!empty(Auth::user()->id)){
    $user_id=Auth::user()->id;
}
        $order= new Order();
        $order->name=$validate['name'];
        $order->telephone=$validate['Tel'];
        $order->mail=$validate['mail'];
        $order->address=$validate['address'];
        $order->shipping=$validate['shipping'];
        $order->products=serialize(session()->get('cart'));
        $order->price=(int)$validate['price'];
        $order->customer=$user_id;
        $order->save();
        session()->forget('cart');
        $this->sendMessage($order->mail,$validate['price'],$order->id);
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

}
