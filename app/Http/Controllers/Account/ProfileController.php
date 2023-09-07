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
        $profile='';
        $profile=Profile::where('id_user',Auth::user()->id)->get();

        return view('account.profile.index',compact('profile'));
    }

    public function create(){

        return view('account.profile.create');

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
        $profile->save();

        return redirect()->route('account.profile');

    }


}
