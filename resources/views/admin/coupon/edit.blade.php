@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('admin.couponGenerator.update',$coupon->id)}}"  method="post">
                @csrf
                @method('put')

                <div class=" mb-3">
                    <div class="col-sm-3 mb-3">
                        <input type="text" name="name" value="{{$coupon->name}}"  class="form-control" placeholder="Наименование">
                    </div>
                    <div class="col-sm-3 mb-3">
                        <select name="type" class="custom-select" >
                            <option @if($coupon->type != 1 && $coupon->type != 2) selected @endif>Тип купона</option>
                            <option @if($coupon->type == 1) selected @endif value="1">Процент от заказа</option>
                            <option @if($coupon->type == 2) selected @endif value="2">Фиксированный</option>
                        </select>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <input type="text" name="value" value="{{$coupon->value}}"  class="form-control" placeholder="Значение">
                    </div>
                    <div class="col-sm-3 mb-3">
                        <input type="text" name="limit" value="{{$coupon->limit}}"  class="form-control" placeholder="Лимит использований">
                    </div>
                    <div class="col-sm-3 mb-3">
                        <select name="status" class="custom-select" >
                            <option @if($coupon->status == 1) selected @endif value="1">Активно</option>
                            <option @if($coupon->status == 0) selected @endif value="0">Не активно</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary m-lg-2">Изменить</button>
                </div>
            </form>
        </div>
    </section>

@endsection
