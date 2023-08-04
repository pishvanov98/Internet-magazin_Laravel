<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class SearchController extends Controller
{
public function index(){

return '2';

}

    public function find(Request $request)
    {

        if( preg_match("/[А-Яа-я]/", $request->route('name')) ) {

            if (strlen($request->route('name')) < 7){
                return '';
            }

        }else{
            if (strlen($request->route('name')) < 4){
                return '';
            }
        }
        return app('Search')->GetSearchProduct($request->route('name'));
// return ProductDescription::search($request->route('name'))->select('product_id','name')->get();
    }

}
