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
            <form action="{{route('admin.slider.store')}}" enctype="multipart/form-data" method="post">
                @csrf

                <div class=" mb-3">
                    <div class="col-sm-3 mb-3">
                        <input type="text" name="name" value="{{old('name')}}" class="form-control" placeholder="Наименование">
                    </div>
                    <div class="col-sm-3 mb-3">
                        <input type="text" name="location" value="{{old('location')}}" class="form-control" placeholder="Расположение">
                    </div>
                    <div class="col-sm-2 mb-2">
                            <select name="status" class="form-control" >
                                <option selected value="1">Активный слайдер</option>
                                <option value="0">Не активный слайдер</option>
                            </select>
                    </div>
                    <button type="submit" class="btn btn-primary m-lg-2">Добавить</button>
                </div>
            </form>
        </div>
    </section>
@endsection
