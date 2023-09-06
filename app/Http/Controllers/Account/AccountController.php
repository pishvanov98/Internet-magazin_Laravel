<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function index(){

        $user=auth()->user();

        if(!empty($user)){
            return view('account.index',compact('user'));
        }else{
            return redirect()->route('home');
        }

    }

    public function update(Request $request){

        if(!empty($request->route('id'))){
            $id=$request->route('id');
            $data=$request->all();



            if(!empty($data['password'])){
                $validator = Validator::make($request->all(), [
                    'name'=>'required',
                    'email'=>'required',
                    'password' => [
                'required',
                'min:6',
            ],
                ]);
            }else{
                $validator = Validator::make($request->all(), [
                    'name'=>'required',
                    'email'=>'required'
                ]);
            }



            if ($validator->fails()) {

                return redirect()->route('account')
                    ->withErrors($validator)
                    ->withInput();
            }


            $mytime = Carbon::now();
            $user = User::findOrFail($id);

            if(!empty($data['password'])){
                $user->password= Hash::make($data['password']);
            }
            $user->name= $data['name'];
            $user->email= $data['email'];
            $user->updated_at= $mytime->toDateTimeString();
            $user->update();
            return redirect()->route('account');
        }


    }


    public function exit()
    {
        session()->flush();

        return redirect()->route('home');
    }


}
