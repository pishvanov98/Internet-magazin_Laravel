@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">
                <form action="{{route('admin.user.update',$user->id)}}" method="post">
                        @csrf
                        @method('put')
                    <div  class="d-flex mb-2 ">
                        <div class="mb-3 col-input pr-1">
                            <label for="name" class="form-label">ФИО</label>
                            <input type="text" value="{{$user->name}}" name="name" class="form-control" id="name">
                        </div>
                        <div class="mb-3 col-input">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" value="{{$user->email}}" name="email" class="form-control" id="email" >
                        </div>
                    </div>
                    <div  class="d-flex mb-2 ">
                        <div class="mb-3 col-input pr-1">
                            <label for="password" class="form-label">Пароль</label>
                            <input type="password" name="password" class="form-control" id="password" >
                        </div>
                        <div class="mb-3 col-input">
                        <label for="select" class="form-label">Тип пользователя</label>
                        <select name="type" class="form-control" id="select" >
                            <option @if(!empty($type_user->user_type) && $type_user->user_type == 0)  selected @else @if(empty($type_user)) selected @endif    @endif value="0" >Обычный пользователь</option>
                            <option @if(!empty($type_user->user_type) && $type_user->user_type == 2)  selected  @endif value="2">Вип</option>
                            <option @if(!empty($type_user->user_type) && $type_user->user_type == 3)  selected  @endif value="3">Закупка</option>
                        </select>
                        </div>
                    </div>
                    <button class="btn btn-primary">Обновить</button>
                </form>

        </div>
    </section>

@endsection
