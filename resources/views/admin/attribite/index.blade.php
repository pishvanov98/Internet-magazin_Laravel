@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
                    <a href="{{route('admin.attribute.create')}}" class="btn btn-primary" type="button">Добавить Атрибут</a>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Наименование</th>

                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                @if($attributes)
                    @foreach($attributes as $attribut)

                        <tr>
                            <th scope="row">{{$attribut->attribute_id}}</th>
                            <td>{{$attribut->name}}</td>
                            <td>
                                <form method="post" action="{{route('admin.attribute.destroy',$attribut->attribute_id)}}">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" class="btn btn-link ml-0 pl-0" value="Удалить">
                                </form>
                            </td>
                        </tr>

                    @endforeach
                @endif
                </tbody>
            </table>

        </div><!-- /.container-fluid -->
        {{$attributes->links()}}

    </section>

@endsection
