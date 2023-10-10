<?php
namespace App\Http\Controllers\Export;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

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
        echo md5($cookie['exchange1c_password']) . "\n";
    }
    private function modeQueryOrders($cookie){

        if (!isset($cookie['key'])) {
            echo "Cookie fail\n";
            return;
        }

        if ($cookie['key'] != md5($cookie('exchange1c_password'))) {
            echo "failure\n";
            echo "Session error";
            return;
        }



        $document_counter=1;


        $document['Документ' . $document_counter] = array(
            'Ид'          => "555"//$order['order_id']
        ,'Номер'       => "555"//$order['order_id']
        ,'metrika_client_id'    => "111"
        ,'Дата'        => "2023-10-10"
        ,'Время'       => "10:37:21"
        ,'Валюта'      => "руб."
        ,'Курс'        => 1
        ,'ХозОперация' => 'Заказ товара'
        ,'Роль'        => 'Продавец'
        ,'Сумма'       => "2052.0000"//$order['total']
        ,'ОткудаУзнал'       => ""//$pop_text
        ,'АдресДоставки'=>"пушкина, колотушкина 4"//$_adresDos
        ,'ТипОтправки'=>2//$this->tipDostavki_1c($order['shipping_code'])
        ,'КудаВеземТермИлиАдрес'=>1//$order['kyda_term_adress'] // 1 - до Терминала :
            // 2 -  до Дверей :
        ,'КодПоляВыбораДругиеТК'=>'' //ТК КИТ УТ0000083
        ,'ФИОПолучателя'=>''
        ,'АдресПолучателяДляТК'=>""//$_adressTK
        ,'заЧейСчетОтправка'=>1//$this->tipOplaty_1c($order)
        ,'ТелефонДоставки'=>"7888888888888"//$order['telephone']
        ,'КомментарийКурьеру'=>''
        ,'КомментарийКурьеруОтправкаТК'=>''
            //,'КомментарийКурьеруОтправкаТК'=>$order['comment']
        ,'Комментарий' =>"комментарий"

        );
        $document['Документ' . $document_counter]['Контрагенты']['Контрагент'] = array(
            'Ид'                 => '6156#nikita@mail.ru'//$order['customer_id'] . '#' . $order['email']
        ,'ИдСайта'                 => '6156'
        ,'ИНН'                 => "7777"//$inn//1234567890 //$order['customer_id'] . '#' . $order['email']
        ,'ТелефонОбмен'                 => 88888888888//1234567890 //$order['customer_id'] . '#' . $order['email']
        ,'Наименование'		    => "Никита"
        ,'Роль'               => 'Покупатель'
        ,'ПолноеНаименование'	=> "Никита"
        ,'Фамилия'            => "еее"
        ,'Имя'			          => "Никита"
        ,'Адрес' => array(
                'Представление'	=>  "пушкина, колотушкина 4"
            )
        ,'Контакты' => array(
                'Контакт1' => array(
                    'Тип' => 'ТелефонРабочий'
                ,'Значение'	=> "4234234234"
                )
            ,'Контакт2'	=> array(
                    'Тип' => 'Почта'
                ,'Значение'	=> "nikita@mail.ru"
                )
            )
        );
        $product_counter = 1;
        $document['Документ' . $document_counter]['Товары']['Товар' . $product_counter] = array(
            'Ид'             => "ORDER_DELIVERY",
            'Наименование'   => 'Доставка'
        ,'ЦенаЗаЕдиницу'  => 600
        ,'Количество'     => 1
        ,'Сумма'          => 600
        ,'ЗначенияРеквизитов'=>array(
                'ЗначениеРеквизита'=>array(
                    'Наименование'=>'ТипНоменклатуры',
                    'Значение'=>'Услуга'
                )
            )

        );
        $document['Документ' . $document_counter]['Товары']['Товар' . $product_counter] = array(
            'Ид'             => "a3c1db6d-df49-11ed-80ee-0cc47aab4f67"
        ,'Наименование'   => "Зубная щетка PRESIDENT Baby 0-3 (1 шт) Мягкая 4 МИЛ"
        ,'ЦенаЗаЕдиницу'  => 217
        ,'Количество'     => 1
        ,'Сумма'          => 217 //$product['total']
        );

        if(!empty($_cupon)){
            $document['Документ' . $document_counter]['Товары']['Товар' . $product_counter]['Скидки'] = array(
                'Скидка'=>array(
                    'Наименование'=>'Скидка на товар',
                    'Сумма'=>$_cupon,
                    'УчтеноВСумме'=>true,
                )
            );
        }

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
