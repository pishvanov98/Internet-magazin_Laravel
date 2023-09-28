@extends('layouts.app')

@section('content')
<div class="container">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{route('home')}}">Главная</a></li>
            <li class="breadcrumb-item active" aria-current="page">Производители</li>
        </ol>
    </nav>
    @if(!empty($manufacturers))
        <h4>Производители</h4>
    <div class="wrapper_brand">
        @foreach($manufacturers as $manufacturer)
            <a class="item" href="{{route('manufacturer.show',$manufacturer['slug'])}}"><img alt="{{$manufacturer->name}}" src="{{asset($manufacturer->image)}}">
                <span>{!! html_entity_decode($manufacturer->name) !!}</span>
            </a>
        @endforeach
    </div>
        <div class="pagination_wrapper">{{$manufacturers->links()}}</div>
    @endif
</div>
@endsection
