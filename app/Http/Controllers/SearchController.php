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

$category=app('Search')->GetSearchProductCategory(mb_strtolower($request->route('name')));
$product_name=app('Search')->GetSearchProductName(mb_strtolower($request->route('name')));

$massive=[];
$massive=array_merge($category,$product_name);
return $massive;

// return ProductDescription::search($request->route('name'))->select('product_id','name')->get();
    }

}
