<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class PageGeneratorController extends Controller
{

    public function index(){

        $title='Генератор страниц';
        $page = "pageGenerator";

        $pageGenerators=PageGenerator::all();

        return view('admin.pageGenerator.index',compact('page','title','pageGenerators'));

    }

    public function create(){

        $title='Генератор страниц';
        $page = "pageGenerator";

        return view('admin.pageGenerator.create',compact('page','title'));

    }

    public function store(Request $request){


    $data=$request->all();
            $validator = Validator::make($request->all(), [
                'name'=>'required',
                'exampleInputNameContent'=>'required'
            ]);


        if ($validator->fails()) {

            return redirect()->route('admin.pageGenerator.create')
                ->withErrors($validator)
                ->withInput();
        }

        $pageGenerator= new PageGenerator();
        $pageGenerator->name=$data['name'];
        $pageGenerator->content=$data['exampleInputNameContent'];
        $pageGenerator->save();

        return redirect()->route('admin.pageGenerator');

    }

    public function destroy(Request $request){

        $pageGenerator=PageGenerator::findOrFail($request->route('id'));
        $pageGenerator->delete();
        return redirect()->route('admin.pageGenerator');
    }

    public function edit(Request $request){
        $pageGenerator=PageGenerator::findOrFail($request->route('id'));
        $title=$pageGenerator->name;
        $page = "pageGenerator";
        return view('admin.pageGenerator.create',compact('page','title','pageGenerator'));

    }

    public function update(Request $request){
        $data=$request->all();
        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'exampleInputNameContent'=>'required'
        ]);


        if ($validator->fails()) {

            return redirect()->route('admin.pageGenerator.edit',$request->route('id'))
                ->withErrors($validator)
                ->withInput();
        }

        $pageGenerator= PageGenerator::findOrFail($request->route('id'));
        $pageGenerator->name=$data['name'];
        $pageGenerator->content=$data['exampleInputNameContent'];
        $pageGenerator->update();
        return redirect()->route('admin.pageGenerator');
    }


}
