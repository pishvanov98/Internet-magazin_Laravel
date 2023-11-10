<?php

namespace App\Http\Middleware;


use Closure;

class AuthenticateApi
{

    public function handle($request, Closure $next)
    {
        $data=$request->all();

        if($data['token']){
            if ($data['token'] == env('TokenApi')) {

                return $next($request);//если токен есть и он совпадает с токеном, пропускаем
            }
        }

        return response()->json('error');
    }

}
