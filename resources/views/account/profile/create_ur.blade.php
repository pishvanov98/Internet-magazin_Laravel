@extends('layouts.app')

@section('content')

    <div class="container">
        <h4>Профили пользователя</h4>
        <div class="block_account">
            <div class="left_block">
                @include('components.accountMenu')
            </div>
            <div class="right_block">

                @if(!empty($errors->any()))
                    <ul class="alert alert-danger">
                        @foreach($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                    </ul>
                @endif
                    <form @if(!empty($profile)) action="{{route('account.profile.update',$profile->id)}}"   @else  action="{{route('account.profile.store')}}"  @endif method="post">
                        @csrf
                        @if(!empty($profile))  @method('put') @endif
                        <div class="mb-2">


                            <div class="d-flex mb-2">
                                <div class="mb-3 col-input">
                                    <label for="inn_ur" class="form-label">Инн</label>
                                    <input class="form-control" name="inn"  type="text" @if(!empty($profile->inn)) value="{{$profile->inn}}" @endif maxlength="12" onkeyup="this.value = this.value.replace (/[^0-9^\d]/, '')" id="inn_ur">
                                </div>
                                <div class="mb-3 col-input">
                                    <label for="company_ur" class="form-label">Название компании</label>
                                    <input class="form-control" name="company"  type="text" @if(!empty($profile->company)) value="{{$profile->company}}" @endif id="company_ur">
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="mb-3 col-input">
                                    <label for="name" class="form-label">ФИО ответственного лица</label>
                                    <input type="text" @if(!empty($profile->name)) value="{{$profile->name}}" @endif name="name" class="form-control" id="name">
                                </div>
                                <div class="mb-3 col-input">
                                    <label for="Tel" class="form-label">Контактный телефон</label>
                                    <input type="text" @if(!empty($profile->telephone)) value="{{$profile->telephone}}"  @endif maxlength="12" onkeyup="this.value = this.value.replace (/[^0-9+^\d]/, '')"  name="Tel" class="form-control" id="Tel" >
                                </div>
                            </div>
                            <div class="d-flex mb-2">
                                <div class="mb-3 col-input">
                                    <label for="mail" class="form-label">Электронная почта</label>
                                    <input type="email" @if(!empty($profile->mail)) value="{{$profile->mail}}"  @endif  name="mail" class="form-control" id="mail" >
                                </div>
                                <div class="mb-3 col-input">
                                    <label for="address" class="form-label">Адрес</label>
                                    <input type="text" @if(!empty($profile->address)) value="{{$profile->address}}"  @endif   name="address" class="form-control" id="address" >
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary">@if(!empty($profile)) Изменить   @else  Добавить  @endif</button>
                    </form>

            </div>
        </div>
    </div>


@endsection
