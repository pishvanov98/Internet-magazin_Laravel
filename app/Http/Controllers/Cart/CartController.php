<?php

namespace App\Http\Controllers\Cart;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class CartController extends Controller
{
    public function addToCart(Request $request)
    {
    return json_encode(app('Cart')->addCart($request->get('id'),1));
    }
    public function DelToCart(Request $request)
    {
        return json_encode(app('Cart')->deleteCart($request->get('id')));
    }
}
