<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuthMobileApiController extends Controller
{
    public function index(Request $request){

        return ($request->route('id'));

    }
}
