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
            <form action="{{route('admin.couponGenerator.store')}}"  method="post">
                @csrf

                <div class=" mb-3">
                    <div class="col-sm-3 mb-3">
                        <input type="text" name="name"  class="form-control" placeholder="Наименование">
                    </div>
                    <div class="col-sm-3 mb-3">
                        <select name="type" class="custom-select" >
                            <option selected>Тип купона</option>
                            <option value="1">Фиксированный</option>
                            <option value="2">Процент</option>
                        </select>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <input type="text" name="value"  class="form-control" placeholder="Значение">
                    </div>
                    <div class="col-sm-3 mb-3">
                        <input type="text" name="min_value"  class="form-control" placeholder="Минимальная сумма в заказе">
                    </div>
                    <div class="col-sm-3 mb-3">
                        <input type="text" name="limit"  class="form-control" placeholder="Лимит использований">
                    </div>
                    <button type="submit" class="btn btn-primary m-lg-2">Добавить</button>
                </div>
            </form>
        </div>
    </section>

@endsection
