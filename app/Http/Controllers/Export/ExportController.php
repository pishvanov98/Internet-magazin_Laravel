<?php
namespace App\Http\Controllers\Export;

use App\Components\ImageComponent;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\ProductOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExportController extends Controller
{
    public function index(Request $request){
        $data=$request->all();
        if(!empty($data['type']) && !empty($data['mode'])){
            $cookie=$request->cookie();
            switch($data['mode']){
                case "checkauth":
                   $this->checkauth($cookie);
                    break;
                case "query":
                    $this->modeQueryOrders($cookie);
                    break;
                case 'init':
                    $this->modeCatalogInit();
                    break;
                case 'success':
                    $this->modeOrdersChangeStatus();
                    break;
            }
        }
    }


    private function checkauth($cookie){
//
        echo "success\n";
        echo "key\n";

        //Log::info('key',$cookie['exchange1c_password']);
        //echo md5($cookie['exchange1c_password']) . "\n";
    }
    private function modeQueryOrders($cookie){

//        if (!isset($cookie['key'])) {
//            echo "Cookie fail\n";
//            return;
//        }
//
//        if ($cookie['key'] != md5($cookie('exchange1c_password')) && env('Exchange1c_password') != $cookie('exchange1c_password')) {
//            echo "failure\n";
//            echo "Session error";
//            return;
//        }
        $document=[];
        $document_counter=1;
        $id_order_mass=[];
        $order=Order::where('status','0')->get();

        $order->each(function ($item_order)use(&$document_counter,&$document,&$id_order_mass){
         $dt=   Carbon::create($item_order->created_at);
         $shopping_name='';
         if($item_order->shipping == 1){
             $shopping_name='Оплата при получении';
         }elseif ($item_order->shipping == 1){
             $shopping_name='Оплата онлайн';
         }else{
             $shopping_name='Оплата по счёту';
         }
         $coupon='';
            if(!empty($item_order->coupon)){
                $coupon="Купон: ".$item_order->coupon." Скидка: ".$item_order->discount."р.";
            }

            $document['Документ' . $document_counter] = array(
                'Ид'          => $item_order->id
            ,'Номер'       => $item_order->id
            ,'Дата'        => $dt->toDateString()
            ,'Время'       => $dt->toTimeString()
            ,'Валюта'      => "руб."
            ,'Курс'        => 1
            ,'ХозОперация' => 'Заказ товара'
            ,'Роль'        => 'Продавец'
            ,'Сумма'       => $item_order->price
            ,'ОткудаУзнал'       => ""
            ,'АдресДоставки'=>$item_order->address
            ,'ТипОтправки'=>$item_order->shipping
            ,'КудаВеземТермИлиАдрес'=>1//$order['kyda_term_adress'] // 1 - до Терминала :
                // 2 -  до Дверей :
            ,'КодПоляВыбораДругиеТК'=>''
            ,'ФИОПолучателя'=>''
            ,'АдресПолучателяДляТК'=>$item_order->address
            ,'заЧейСчетОтправка'=>1
            ,'ТелефонДоставки'=>$item_order->telephone
            ,'КомментарийКурьеру'=>''
            ,'КомментарийКурьеруОтправкаТК'=>''
                //,'КомментарийКурьеруОтправкаТК'=>$order['comment']
            ,'Комментарий' =>"".env('APP_NAME')." , ".$item_order->comment.", ".$coupon." , ".$item_order->address.", ".$item_order->name." , ".$item_order->mail." , ".$item_order->telephone." , ".$shopping_name

            );

            if (!empty($item_order->customer)){
                $telephone=preg_replace('/[^0-9]/', '', $item_order->telephone);
                $email=$item_order->mail;
                if(!empty($item_order->inn)){
                    $inn=$item_order->inn;
                    $name=$item_order->company;
                }else{
                    $inn=$telephone;
                    $name=$item_order->name;
                    $kontragent_to_1c=DB::connection('mysql2')->table('kontragent_to_1c')->where('phone','like',$telephone)->orWhere('phone1','like',$telephone)->orWhere('phone2','like',$telephone)->orWhere('phone3','like',$telephone)->orWhere('phone4','like',$telephone)->first();
                    if(empty($kontragent_to_1c)){
                        $tel = substr($telephone, 1);
                        $kontragent_to_1c=DB::connection('mysql2')->table('kontragent_to_1c')->where('phone','like',$tel)->orWhere('phone1','like',$tel)->orWhere('phone2','like',$tel)->orWhere('phone3','like',$tel)->orWhere('phone4','like',$tel)->first();
                    }
                    if(!empty($kontragent_to_1c)){
                        $inn=$kontragent_to_1c->inn;
                        $name=$kontragent_to_1c->name;
                        $email=$kontragent_to_1c->email;
                    }

                }
                $mass_name=explode(' ',$item_order->name);
                if(empty($mass_name[1])){
                    $mass_name[1]='';
                }
                $document['Документ' . $document_counter]['Контрагенты']['Контрагент'] = array(
                    'Ид'                 => $item_order->id."".$item_order->mail
                ,'ИдСайта'                 => ''
                ,'ИНН'                 => $inn//$inn//1234567890 //$order['customer_id'] . '#' . $order['email']
                ,'ТелефонОбмен'                 => $telephone//1234567890 //$order['customer_id'] . '#' . $order['email']
                ,'Наименование'		    => $mass_name[0]
                ,'Роль'               => 'Покупатель'
                ,'ПолноеНаименование'	=> $name
                ,'Фамилия'            => $mass_name[1]
                ,'Имя'			          => $mass_name[0]
                ,'Адрес' => array(
                        'Представление'	=>  $item_order->address
                    )
                ,'Контакты' => array(
                        'Контакт1' => array(
                            'Тип' => 'ТелефонРабочий'
                        ,'Значение'	=> $telephone
                        )
                    ,'Контакт2'	=> array(
                            'Тип' => 'Почта'
                        ,'Значение'	=> $email
                        )
                    )
                );
            }else{
                $telephone=preg_replace('/[^0-9]/', '', $item_order->telephone);
                if(!empty($item_order->inn)){
                    $inn=$item_order->inn;
                    $name=$item_order->company;
                }else{
                    $inn=$telephone;
                    $name=$item_order->name;
                }
                $mass_name=explode(' ',$item_order->name);
                if(empty($mass_name[1])){
                    $mass_name[1]='';
                }
                $document['Документ' . $document_counter]['Контрагенты']['Контрагент'] = array(
                    'Ид'                 => $item_order->id."".$item_order->mail
                ,'ИдСайта'                 => ''
                ,'ИНН'                 => $inn//$inn//1234567890 //$order['customer_id'] . '#' . $order['email']
                ,'ТелефонОбмен'                 => $telephone//1234567890 //$order['customer_id'] . '#' . $order['email']
                ,'Наименование'		    => $mass_name[0]
                ,'Роль'               => 'Покупатель'
                ,'ПолноеНаименование'	=> $name
                ,'Фамилия'            => $mass_name[1]
                ,'Имя'			          => $mass_name[0]
                ,'Адрес' => array(
                        'Представление'	=>  $item_order->address
                    )
                ,'Контакты' => array(
                        'Контакт1' => array(
                            'Тип' => 'ТелефонРабочий'
                        ,'Значение'	=> $telephone
                        )
                    ,'Контакт2'	=> array(
                            'Тип' => 'Почта'
                        ,'Значение'	=> $item_order->mail
                        )
                    )
                );
            }


            $order_prod=ProductOrder::where('id_order',$item_order->id)->get();
            $product_mass=[];

            $order_prod->map(function ($val)use(&$product_mass){
                $product_mass[$val->id_prod]=['id'=>$val->id_prod,'count'=>$val->count,'price'=>$val->price,'total'=>(int)$val->price * (int)$val->count];//price за единицу
            });
            $products_init=app('Product')->ProductInit(array_column($product_mass,'id'));
            $discount='';
            if(!empty($coupon)){
                $discount=$item_order->discount;
            }

            $products_init->map(function ($item,$key)use (&$product_mass,&$discount,$document_counter,&$document){

                $item->quantity_buy=$product_mass[$item->product_id]['count'];
                $item->price=$product_mass[$item->product_id]['price'];
                $item->total=$product_mass[$item->product_id]['total'];
                $prod_id=0;
                $prod_id_1c=DB::connection('mysql2')->table('sd_product_to_1c')->where('product_id',$item->product_id)->first();
                if(!empty($prod_id_1c)){
                    $prod_id_1c=(array)$prod_id_1c;
                    $prod_id=$prod_id_1c['1c_id'];
                }
                $document['Документ' . $document_counter]['Товары']['Товар' . $key] = array(
                    'Ид'             => $prod_id//тут id из 1c
                ,'Наименование'   => $item->name
                ,'ЦенаЗаЕдиницу'  => $item->price
                ,'Количество'     => $item->quantity_buy
                ,'Сумма'          => $item->total //$product['total']
                );
                if(!empty($discount)){
                    $document['Документ' . $document_counter]['Товары']['Товар' . $key]['Скидки'] = array(
                        'Скидка'=>array(
                            'Наименование'=>'Скидка на товар',
                            'Сумма'=>$discount,
                            'УчтеноВСумме'=>true,
                        )
                    );
                }
                return $item;
            });


//            $document['Документ' . $document_counter]['Товары']['Товар' . $product_counter] = array(
//                'Ид'             => "ORDER_DELIVERY",
//                'Наименование'   => 'Доставка'
//            ,'ЦенаЗаЕдиницу'  => 600
//            ,'Количество'     => 1
//            ,'Сумма'          => 600
//            ,'ЗначенияРеквизитов'=>array(
//                    'ЗначениеРеквизита'=>array(
//                        'Наименование'=>'ТипНоменклатуры',
//                        'Значение'=>'Услуга'
//                    )
//                )
//
//            );

            $id_order_mass[]=$item_order->id;
            $document_counter++;
        });

        $root = '<?xml version="1.0" encoding="utf-8"?>
		<КоммерческаяИнформация ВерсияСхемы="2.04" ДатаФормирования="' . date('Y-m-d', time()) . '" />';
        $xml1 = new \SimpleXMLElement($root);
        $xml = $this->array_to_xml($document, $xml1);
        $orders=  $xml->asXML();

        $badchar=array(
            // control characters
            chr(0), chr(1), chr(2), chr(3), chr(4), chr(5), chr(6), chr(7), chr(8), chr(9), chr(10),
            chr(11), chr(12), chr(13), chr(14), chr(15), chr(16), chr(17), chr(18), chr(19), chr(20),
            chr(21), chr(22), chr(23), chr(24), chr(25), chr(26), chr(27), chr(28), chr(29), chr(30),
            chr(31),
            // non-printing characters
            chr(127)
        );

//replace the unwanted chars
        $orders = str_replace($badchar, '', $orders);
        //$_out = mb_convert_encoding($orders, 'WINDOWS-1251', 'UTF-8');
        DB::table('Orders')->whereIn('id',$id_order_mass)->update(['status'=>1]);
        echo $orders;

    }

    function array_to_xml($data, &$xml) {

        foreach($data as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $xml->addChild(preg_replace('/\d/', '', $key));
                    $this->array_to_xml($value, $subnode);
                }
            }
            else {
                $xml->addChild($key, htmlentities($value, ENT_XML1));
            }
        }

        return $xml;
    }

    private function modeCatalogInit(){


        $limit = 100000 * 1024 * 1024;


            echo "zip=yes\n";
            echo "file_limit=" . $limit . "\n";


    }

    private function modeOrdersChangeStatus(){
        if (!isset($this->request->cookie['key'])) {
            echo "Cookie fail\n";
            return;
        }

        if ($this->request->cookie['key'] != md5($this->config->get('exchange1c_password'))) {
            echo "failure\n";
            echo "Session error";
            return;
        }

        echo "success\n";

    }

}
