@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">ФИО</th>
                    <th scope="col">Телефон</th>
                    <th scope="col">Почта</th>
                    <th scope="col">Адрес</th>
                    <th scope="col">Тип доставки</th>
                    <th scope="col">Стоимость</th>
                    <th scope="col">id Пользователя</th>
                    <th scope="col">Дата заказа</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>

                @if(!empty($orders))

                    @foreach($orders as $item)

                        <tr>
                            <th scope="row">{{$item->id}}</th>
                            <td>{{$item->name}}</td>
                            <td>{{$item->telephone}}</td>
                            <td>{{$item->mail}}</td>
                            <td>{{$item->address}}</td>
                            <td>
                                @if($item->shipping == 1) Оплата при получении @endif
                                @if($item->shipping == 2) Оплата онлайн @endif
                                @if($item->shipping == 3) Оплата по счёту @endif
                            </td>
                            <td>{{number_format($item->price, 0, '', ' ')}} ₽</td>
                            <td>{{$item->customer}}</td>
                            <td>{{$item->created_at}}</td>
                            <td><a href="{{route('admin.order.show',$item->id)}}">Просмотреть</a></td>
                        </tr>

                    @endforeach

                @endif

                </tbody>
            </table>

        </div><!-- /.container-fluid -->


    </section>

@endsection
