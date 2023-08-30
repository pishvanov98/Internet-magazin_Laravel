@extends('layouts.app')
@section('content')
    <div class="container">
    Способ оплаты и доставки



<div class="flex_cart_item_input__all">

            <div class="cart_item_input">
                <label for="loginname">ФИО *</label>
                <input value="" type="text" id="loginname">
            </div>


            <div class="cart_item_input">
                <label for="loginTel">Контактный телефон *</label>
                <input value="" type="text" maxlength="12" onkeyup="this.value = this.value.replace (/[^0-9+^\d]/, '')" id="loginTel">
            </div>
            <div class="cart_item_input">
                <label for="loginEmail">Электронная почта *</label>
                <input value="" type="text" id="loginEmail">
            </div>

</div>


    </div>
@endsection
