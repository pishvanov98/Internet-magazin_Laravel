<?php
namespace App\Http\Controllers\Page;

use App\Http\Controllers\Controller;
use App\Models\PageGenerator;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index( Request $request){


        $page= PageGenerator::where('name',$request->route('name'))->first();
        if(!empty($page->content)){
            return view('page.index',['content'=>$page->content]);
        }else{
            return redirect()->route('home');
        }
    }
}
