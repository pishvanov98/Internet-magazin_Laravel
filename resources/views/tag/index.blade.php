@extends('layouts.app')

@section('content')

    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Главная</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$title}}</li>
            </ol>
        </nav>
        <h4>{{$title}}</h4>

        <div class="products_tag">
            <div class="wrapper_goods">
                @if($products)

                    @foreach($products as $product)

                        @include('components.product')

                    @endforeach

                @endif
            </div>
            <div class="pagination_wrapper">  {{$products->links()}} </div>
        </div>

    </div>

@endsection
