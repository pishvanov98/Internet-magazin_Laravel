@extends('layouts.admin')
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
                <a href="{{route('admin.slider.create')}}" class="btn btn-primary" type="button">Добавить слайдер</a>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Наименование</th>
                    <th scope="col">Расположение</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                @if($sliders)
                    @foreach($sliders as $slider)

                        <tr>
                            <th scope="row">{{$slider->id}}</th>
                            <td>{{$slider->name}}</td>
                            <td>{{$slider->location}}</td>
                            <td>@if($slider->status == 1) Активный @else Не активный @endif</td>
                            <td><a href="{{route('admin.slider.edit',$slider->id)}}">Редактировать</a>
                                <form method="post" action="{{route('admin.slider.destroy',$slider->id)}}">
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
    </section>
@endsection
