<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{

    public function index(){

        $title='Пользователи';
        $page = "user";
        $users=User::all();

        return view('admin.user.index',compact('page','title','users'));

    }

    public function show(Request $request){

        if(!empty($request->route('id'))){
          $user= User::findOrFail($request->route('id'));
            $title=$user->name;
            $page = "user";

            $type_user=UserType::where('user_id',$request->route('id'))->first();

          return view('admin.user.show',compact('user','title','page','type_user'));


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

                return redirect()->route('admin.user.show',$id)
                    ->withErrors($validator)
                    ->withInput();
            }

            $typeUser=UserType::where('user_id',$request->route('id'))->first();
            if(empty($typeUser)){
                $typeUser=new UserType();
                $typeUser->user_id=$request->route('id');
                $typeUser->user_type=$data['type'];
                $typeUser->save();
            }else{
                $typeUser=UserType::findOrFail($typeUser->id);
                $typeUser->user_type=$data['type'];
                $typeUser->update();
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
            return redirect()->route('admin.user');
        }


    }

}
