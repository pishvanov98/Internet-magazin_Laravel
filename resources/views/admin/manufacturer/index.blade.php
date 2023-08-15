@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
                    <a href="{{route('admin.manufacturer.create')}}" class="btn btn-primary" type="button">Добавить Производителя</a>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Наименование</th>
                    <th scope="col">Сортировка</th>
                    <th scope="col">Страна</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                @if($manufacturers)
                    @foreach($manufacturers as $manufacturer)

                        <tr>
                            <th scope="row">{{$manufacturer->manufacturer_id}}</th>
                            <td>{{$manufacturer->name}}</td>
                            <td>{{$manufacturer->sort_order}}</td>
                            <td>{{$manufacturer->strana}}</td>
                            <td>
                                <form method="post" action="{{route('admin.manufacturer.destroy',$manufacturer->manufacturer_id)}}">
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
        {{$manufacturers->links()}}

    </section>

@endsection
