<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CartSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $infoCart=app('Cart')->checkCart();
        view()->composer('layouts.app', function ($view) use ($infoCart) {
            $view->with('infoCart', $infoCart);
        });

        return $next($request);
    }
}
