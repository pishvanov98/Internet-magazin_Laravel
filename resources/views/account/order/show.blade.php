@extends('layouts.app')

@section('content')

    <div class="container">
        <h4>Заказ № {{$order->id}}</h4>
        <div class="block_account">
            <div class="left_block">
                @include('components.accountMenu')
            </div>
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
                        @notmobile
                        <th scope="col">Фото</th>
                        @endnotmobile
                        <th scope="col">Наименование</th>
                        <th scope="col">Количество</th>
                        <th scope="col">Сумма</th>
                        @notmobile
                        <th scope="col">Действие</th>
                        @endnotmobile
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($products_init))
                        @foreach($products_init as $key=>$item )
                            @php $key++; @endphp
                            <tr>
                                <th style="width: 25px;" scope="row">{{$key}}</th>
                                @notmobile
                                <th><img width="100" class="img" src="{{asset($item->image)}}"></th>
                                @endnotmobile
                                <th>{{$item->name}}</th>
                                <th>{{$item->quantity_buy}}</th>
                                <th style="white-space: nowrap">{{number_format($item->total, 0, '', ' ')}} ₽</th>
                                @notmobile
                                <th><a href="{{route('product.show',$item->slug)}}">Перейти</a></th>
                                @endnotmobile

                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>


@endsection
