<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CouponController extends Controller
{
    public function index(){
        $title='Генератор купонов';
        $page="couponGenerator";
        $coupons=Coupon::all();
        if(!empty($coupons->all())){
            $coupons->map(function ($item){
                if($item->type == 1){
                    $item->type="Процент от заказа";
                }
                if($item->type == 2){
                    $item->type="Фиксированный";
                }
                });
        }

        return view('admin.coupon.index',compact('page','title','coupons'));
    }
    public function create(){
        $title='Генератор купонов';
        $page="couponGenerator";
        return view('admin.coupon.create',compact('page','title'));
    }
    public function store(Request $request){

    $data=$request->all();

            $validator = Validator::make($request->all(), [
                'name'=>'required',
                'type'=>'required',
                'value'=>'required',
                'limit'=>'required',
            ]);

        if ($validator->fails()) {
            return redirect()->route('admin.couponGenerator.create')
                ->withErrors($validator)
                ->withInput();
        }

        $coupon=new Coupon();
        $coupon->name=$data['name'];
        $coupon->type=$data['type'];
        $coupon->value=$data['value'];
        if (!empty($data['min_value'])){
            $coupon->min_value=$data['min_value'];
        }
        $coupon->limit=$data['limit'];
        $coupon->count_use=0;
        $coupon->status=true;
        $coupon->save();



        return redirect()->route('admin.couponGenerator');
    }

    public function edit(Request $request){
        if(!empty($request->route('id'))){
            $coupon=Coupon::findOrFail($request->route('id'));
           return view('admin.coupon.edit',compact('coupon'));
        }else{
            return redirect()->route('admin.couponGenerator');
        }
    }
    public function update(Request $request){
        $id=$request->route('id');
        $data=$request->all();

        $validator = Validator::make($request->all(), [
            'name'=>'required',
            'type'=>'required',
            'value'=>'required',
            'limit'=>'required',
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.couponGenerator.update',$id)
                ->withErrors($validator)
                ->withInput();
        }

        $coupon=Coupon::findOrFail($id);
        $coupon->name=$data['name'];
        $coupon->type=$data['type'];
        $coupon->value=$data['value'];
        if (!empty($data['min_value'])){
            $coupon->min_value=$data['min_value'];
        }
        $coupon->limit=$data['limit'];
        $coupon->status=$data['status'];
        $coupon->update();
        return redirect()->route('admin.couponGenerator');
    }


    public function destroy(Request $request){
        $category=Coupon::findOrFail($request->route('id'));
        $category->delete();
        return redirect()->route('admin.couponGenerator');
    }

}
