@extends('layouts.app')

@section('content')

    <div class="container">
        <h4>Профиль пользователя</h4>
        <div class="block_account">
            <div class="left_block">
                <ul>
                    <li class="active"><a href="#">Профиль</a></li>
                    <li ><a href="#">Мои адреса</a></li>
                    <li><a href="#">Мои заказы</a></li>
                    <li><a href="#">Избранное</a></li>
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
                <form action="{{route('account.user.update',$user->id)}}" method="post">
                        @csrf
                        @method('put')
                    <div class="d-flex mb-2">
                        <div class="mb-3 col-input">
                            <label for="name" class="form-label">ФИО</label>
                            <input type="text" value="{{$user->name}}" name="name" class="form-control" id="name">
                        </div>
                        <div class="mb-3 col-input">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" value="{{$user->email}}" name="email" class="form-control" id="email" >
                        </div>
                    </div>
                    <div class="d-flex mb-2">
                        <div class="mb-3 col-input">
                            <label for="password" class="form-label">Пароль</label>
                            <input type="password" name="password" class="form-control" id="password" >
                        </div>
                    </div>
                    <button class="btn btn-primary">Обновить</button>
                </form>
            </div>
        </div>
    </div>


@endsection
