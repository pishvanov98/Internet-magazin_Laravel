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
            <form action="{{route('admin.category.store')}}"  method="post">
                @csrf

                <div class=" mb-3">
                    <div class="col-sm-3 mb-3">
                        <input type="text" name="name"  class="form-control" placeholder="Наименование">
                    </div>
                    <div class="col-sm-3 mb-3">
                        <input type="text" name="description"  class="form-control" placeholder="Описание">
                    </div>
                    <div class="col-sm-3 mb-3">
                    <select class="form-control" name="parent" aria-label="Родитель">
                        <option value="0" selected>Родитель категории</option>
                        @if(!empty($categories))
                            @foreach($categories as $category)

                                <option value="{{$category->category_id}}">{{$category->name}}</option>

                            @endforeach

                        @endif
                    </select>
                    </div>
                    <button type="submit" class="btn btn-primary m-lg-2">Добавить</button>
                </div>
            </form>
        </div>
    </section>
@endsection
