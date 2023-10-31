@extends('layouts.app')

@section('content')

    <div class="container">
        <h4>Мои заказы</h4>
        <div class="block_account">
            <div class="left_block">
                @include('components.accountMenu')
            </div>
            <div class="right_block">

                <table class="table">
                    <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col">Номер заказа</th>
                        @notmobile
                        <th scope="col">Фио</th>
                        <th scope="col">Телефон</th>
                        <th scope="col">Email</th>
                        <th scope="col">Адрес</th>
                        <th scope="col">Цена</th>
                        @endnotmobile
                        <th scope="col">Дата</th>
                        <th scope="col">Действие</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(!empty($order_list))
                        @foreach($order_list as $key=>$item )
                            @php $key++; @endphp
                            <tr>
                                <th scope="row">{{$key}}</th>
                                <th>{{$item['id']}}</th>
                                @notmobile
                                <td>{{$item['name']}}</td>
                                <td>{{$item['telephone']}}</td>
                                <td>{{$item['mail']}}</td>
                                <td>{{$item['address']}}</td>
                                <td>{{number_format($item['price'], 0, '', ' ')}} ₽</td>
                                @endnotmobile
                                <td>{{$item['created_at']}}</td>
                                <td><a href="{{route('account.order.show',$item['id'])}}">Посмотреть</a></td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                </table>

            </div>
        </div>
    </div>


@endsection
