@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
                <a href="{{route('admin.couponGenerator.create')}}" class="btn btn-primary" type="button">Добавить Купон</a>
            </div>

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Наименование</th>
                    <th scope="col">Тип</th>
                    <th scope="col">Значение</th>
                    <th scope="col">Лимит</th>
                    <th scope="col">Использовано</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Дата создания</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                @if($coupons)
                    @foreach($coupons as $coupon)
                        <tr>
                            <th scope="row">{{$coupon->id}}</th>
                            <td>{{$coupon->name}}</td>
                            <td>{{$coupon->type}}</td>
                            <td>{{$coupon->value}}</td>
                            <td>{{$coupon->limit}}</td>
                            <td>{{$coupon->count_use}}</td>
                            <td>{{$coupon->status}}</td>
                            <td>{{$coupon->created_at}}</td>
                            <td><a href="{{route('admin.couponGenerator.edit',$coupon->id)}}">Изменить</a>/Удалить</td>

                        </tr>

                    @endforeach
                @endif
                </tbody>
            </table>

        </div><!-- /.container-fluid -->

    </section>

@endsection
