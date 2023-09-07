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
                <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
                    <a href="{{route('account.profile.create')}}" class="btn btn-primary" type="button">Добавить Адрес</a>
                </div>
                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Фио</th>
                        <th scope="col">Телефон</th>
                        <th scope="col">Email</th>
                        <th scope="col">Адрес</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($profile))
                        @foreach($profile as $key=>$item )
                             @php $key++; @endphp
                            <tr>
                                <th scope="row">{{$key}}</th>
                                <td>{{$item['name']}}</td>
                                <td>{{$item['telephone']}}</td>
                                <td>{{$item['mail']}}</td>
                                <td>{{$item['address']}}</td>
                                <td>Изменить / удалить</td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>


@endsection
