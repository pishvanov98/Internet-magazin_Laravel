@extends('layouts.app')

@section('content')
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Главная</a></li>
                <li class="breadcrumb-item"><a href="{{route('manufacturer')}}">Производители</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$manufacturer->name}}</li>
            </ol>
        </nav>
        <h4>{{$manufacturer->name}}</h4>
        @if(!empty($manufacturer->description))

            <p>{!! html_entity_decode($manufacturer->description) !!}</p>
        @endif

        <div class="products_category">
            <div class="wrapper_goods">
                @if($Products)

                    @foreach($Products as $product)

                        @include('components.product')

                    @endforeach

                @endif
            </div>
            <div class="pagination_wrapper">  {{$Products->links()}} </div>
        </div>
    </div>
@endsection
