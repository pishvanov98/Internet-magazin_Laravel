<?php
namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{

    public function index(){
        $profile=[];
        $profile=Profile::where('id_user',Auth::user()->id)->get();
        $select='profile';
        return view('account.profile.index',compact('profile','select'));
    }

    public function create(){
        $select='profile';
        return view('account.profile.create',compact('select'));

    }

    public function create_ur(){
        $select='profile';
        return view('account.profile.create_ur',compact('select'));

    }

    public function store(Request $request){

        $data=$request->all();

        $validate=Validator::make($request->all(), [
            'name'=>'required',
            'Tel'=>'required|numeric',
            'mail'=>'required',
            'address'=>'required'
        ]);

        if ($validate->fails()) {

            return redirect()->route('account.profile.create')
                ->withErrors($validate)
                ->withInput();
        }

        $user_id=0;
        if(!empty(Auth::user()->id)){
            $user_id=Auth::user()->id;
        }
        $profile= new Profile();
        $profile->id_user=$user_id;
        $profile->name=$data['name'];
        $profile->telephone=$data['Tel'];
        $profile->mail=$data['mail'];
        $profile->address=$data['address'];

        if(!empty($data['inn']) && !empty($data['company'])){
            $profile->inn=$data['inn'];
            $profile->company=$data['company'];
        }

        $profile->save();

        return redirect()->route('account.profile');

    }

    public function edit(Request $request){



        if(!empty($request->route('id'))){

            $profile=Profile::findOrFail($request->route('id'));

            $select='profile';
            return view('account.profile.create',compact('select','profile'));

        }

    }

    public function edit_ur(Request $request){

        if(!empty($request->route('id'))){

            $profile=Profile::findOrFail($request->route('id'));

            $select='profile';
            return view('account.profile.create_ur',compact('select','profile'));

        }

    }


    public function update(Request $request){

        if(!empty($request->route('id'))){

            $data=$request->all();

            $validate=Validator::make($request->all(), [
                'name'=>'required',
                'Tel'=>'required|numeric',
                'mail'=>'required',
                'address'=>'required'
            ]);

            if ($validate->fails()) {

                return redirect()->route('account.profile.edit',$request->route('id'))
                    ->withErrors($validate)
                    ->withInput();
            }


            $profile=Profile::findOrFail($request->route('id'));
            $profile->name=$data['name'];
            $profile->telephone=$data['Tel'];
            $profile->mail=$data['mail'];
            $profile->address=$data['address'];
            $profile->update();

            return redirect()->route('account.profile');

        }
    }

    public function destroy(Request $request){
        if(!empty($request->route('id'))) {
            $profile = Profile::findOrFail($request->route('id'));
            $profile->delete();

            return redirect()->route('account.profile');
        }
    }


}
