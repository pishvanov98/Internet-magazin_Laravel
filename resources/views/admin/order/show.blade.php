@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">
                <h4>Заказ № {{$order->id}}</h4>
                    <div class="right_block">
                        <h4>Данные заказа</h4>
                        <p><strong>ФИО:</strong> {{$order->name}}</p>
                        <p><strong>Телефон:</strong> {{$order->telephone}}</p>
                        <p><strong>Почта:</strong> {{$order->mail}}</p>
                        <p><strong>Адрес:</strong> {{$order->address}}</p>
                        <p><strong>Сумма заказа:</strong> {{number_format($order->price, 0, '', ' ')}} ₽</p>
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Фото</th>
                                <th scope="col">Наименование</th>
                                <th scope="col">Количество</th>
                                <th scope="col">Сумма</th>
                                <th scope="col">Действие</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($products_init))
                                @foreach($products_init as $key=>$item )
                                    @php $key++; @endphp
                                    <tr>
                                        <th style="width: 25px;" scope="row">{{$key}}</th>
                                        <th><img width="100" class="img" src="{{asset($item->image)}}"></th>
                                        <th>{{$item->name}}</th>
                                        <th>{{$item->quantity_buy}}</th>
                                        <th>{{number_format($item->total, 0, '', ' ')}} ₽</th>
                                        <th><a href="{{route('product.show',$item->slug)}}">Перейти</a></th>

                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>

                    </div>
        </div><!-- /.container-fluid -->


    </section>

@endsection
