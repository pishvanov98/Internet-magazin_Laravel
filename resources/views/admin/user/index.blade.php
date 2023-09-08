@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">

            <table class="table">
                <thead>
                <tr>
                    <th scope="col">id</th>
                    <th scope="col">ФИО</th>
                    <th scope="col">Почта</th>
                    <th scope="col">Дата Регистрации</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>

                @if(!empty($users))

                    @foreach($users as $item)

                        <tr>
                            <th scope="row">{{$item->id}}</th>
                            <td>{{$item->name}}</td>
                            <td>{{$item->email}}</td>
                            <td>{{$item->created_at}}</td>
                            <td><a href="{{route('admin.user.show',$item->id)}}">Просмотреть</a></td>
                        </tr>

                    @endforeach

                @endif

                </tbody>
            </table>

        </div><!-- /.container-fluid -->


    </section>

@endsection
