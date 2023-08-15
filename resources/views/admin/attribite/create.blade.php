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
            <form action="{{route('admin.attribute.store')}}" enctype="multipart/form-data"  method="post">
                @csrf
                <div class=" mb-3">
                    <div class="col-sm-3 mb-3">
                        <input type="text" name="name"  class="form-control" placeholder="Наименование">
                    </div>
                    <div class="col-sm-3 mb-3">
                        <input type="number" name="attribute_group_id"  class="form-control" placeholder="Порядок сортировки">
                    </div>
                    <button type="submit" class="btn btn-primary m-lg-2">Добавить</button>
                </div>
            </form>
        </div>
    </section>
@endsection
