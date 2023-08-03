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




        return  app('Search')->GetSearchProduct($request->route('name'));
    }

}
