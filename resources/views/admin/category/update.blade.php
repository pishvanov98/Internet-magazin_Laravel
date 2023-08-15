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
                <form action="{{route('admin.category.update',$id)}}"  method="post">
                    @csrf
                    @method('put')
                    <div class=" mb-3">
                        <div class="col-sm-3 mb-3">
                            <input type="text" name="name" value="{{$category_mass['name']}}"  class="form-control" placeholder="Наименование">
                        </div>
                        <div class="col-sm-3 mb-3">
                            <textarea class="form-control" placeholder="Описание" name="description">{{$category_mass['description']}}</textarea>
                        </div>
                        <div class="col-sm-3 mb-3">
                            <select class="form-control" name="parent" aria-label="Родитель">

                                @if(!empty($categories))
                                    @foreach($categories as $category)

                                        <option @if($category_mass['parent'] = $category->category_id) selected @endif  value="{{$category->category_id}}">{{$category->name}}</option>

                                    @endforeach

                                @endif
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary m-lg-2">Изменить</button>
                    </div>
                </form>
        </div>
    </section>

@endsection
