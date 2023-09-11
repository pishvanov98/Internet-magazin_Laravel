@extends('layouts.admin')
@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
                <a href="{{route('admin.pageGenerator.create')}}" class="btn btn-primary" type="button">Добавить страницу</a>
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
                @if($pageGenerators)
                    @foreach($pageGenerators as $pageGenerator)

                        <tr>
                            <th scope="row">{{$pageGenerator->id}}</th>
                            <td>{{$pageGenerator->name}}</td>
                            <td class="d-flex align-items-center"><div><a href="{{route('admin.pageGenerator.edit',$pageGenerator->id)}}">Редактировать </a> /  </div>
                                <form method="post" action="{{route('admin.pageGenerator.destroy',$pageGenerator->id)}}">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" class="btn btn-link ml-0 pl-0" value=" Удалить">
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
