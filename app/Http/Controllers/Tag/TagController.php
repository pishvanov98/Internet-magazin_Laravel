<?php
namespace App\Http\Controllers\Tag;

use App\Components\ImageComponent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TagController extends Controller
{

    public function index(Request $request){
        $title=$request->path();
        $value='';
        if($title == 'exclusive'){
            $title= "Эксклюзивное предложение";
            $value="Эксклюзивное предложение";
        }else if($title == "action"){
                $title= "Акции";
                //$value=['6+1','3+1','1+1=3','Подарок за покупку', '10+2', 'акция', 'Специальная цена', '5+1', '3+1', 'asepta1', 'asepta2', 'asepta3', '2+1', 'asepta'];
                $value=['Специальная цена'];
        }else if($title == "entrance"){
            $title= "Новинки";
            $Products=app('Product')->NewGoodsSlaider();
        }
        $page=0;
        $page = $request->get('page');

        if($title == "Новинки"){
            $products=app('Product')->NewGoodsSlaider(true,24,$page);
        }else{
            $products=app('Product')->ExclusiveSlaider($value,true,24,$page);
        }

        return view('tag.index',compact('title','products'));
    }

}
