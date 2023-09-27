@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{route('manufacturer')}}">Производители</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$brand->name}}</li>
            </ol>
        </nav>
        <h4>{{$brand->name}}</h4>
        @if(!empty($brand->description))

            <p>{!! html_entity_decode($brand->description) !!}</p>
        @endif

        !!!!!!!!!!!!!!!!!!!!!!!!!!!!!1Тут будут товары бренда !!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    </div>
@endsection
