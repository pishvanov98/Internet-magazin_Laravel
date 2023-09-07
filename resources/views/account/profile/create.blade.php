@extends('layouts.app')

@section('content')

    <div class="container">
        <h4>Профили пользователя</h4>
        <div class="block_account">
            <div class="left_block">
                <ul>
                    <li><a href="{{route('account')}}">Профиль</a></li>
                    <li class="active"><a href="{{route('account.profile')}}">Мои профили</a></li>
                    <li><a href="#">Мои заказы</a></li>
                    <li><a href="#">Избранное</a></li>
                    <li class="mt-4"><a href="{{route('exit')}}">Выйти</a></li>
                </ul>
            </div>
            <div class="right_block">

                @if(!empty($errors->any()))
                    <ul class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                @endif
                    <form action="{{route('account.profile.store')}}" method="post">
                        @csrf
                        <div class="mb-2">
                            <div class="d-flex mb-2">
                                <div class="mb-3 col-input">
                                    <label for="name" class="form-label">ФИО</label>
                                    <input type="text"  name="name" class="form-control" id="name">
                                </div>
                                <div class="mb-3 col-input">
                                    <label for="Tel" class="form-label">Контактный телефон</label>
                                    <input type="text" maxlength="12" onkeyup="this.value = this.value.replace (/[^0-9+^\d]/, '')"  name="Tel" class="form-control" id="Tel" >
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="mb-3 col-input">
                                    <label for="mail" class="form-label">Электронная почта</label>
                                    <input type="email"  name="mail" class="form-control" id="mail" >
                                </div>
                                <div class="mb-3 col-input">
                                    <label for="address" class="form-label">Адрес</label>
                                    <input type="text"  name="address" class="form-control" id="address" >
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary">Добавить</button>
                    </form>

            </div>
        </div>
    </div>


@endsection
