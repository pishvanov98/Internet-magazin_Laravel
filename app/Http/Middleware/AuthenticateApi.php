<?php

namespace App\Http\Middleware;


use Closure;

class AuthenticateApi
{

    public function handle($request, Closure $next)
    {
//AveldentMob1

        if($request->route('hash')){
            if (strpos($request->route('hash'), "Test") !== false) {
                //берем и создаем сессию с ид пользователя и в дальнейшем туда записываем и сохр данные
                return $next($request);
            }
        }
        return response('');
    }

}
