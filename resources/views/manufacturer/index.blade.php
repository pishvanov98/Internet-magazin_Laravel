@extends('layouts.app')

@section('content')
<div class="container">
    @if(!empty($manufacturers))
        <h4>Производители</h4>
    <div class="wrapper_brand">
        @foreach($manufacturers as $manufacturer)
            <a class="item" href="{{route('manufacturer.show',$manufacturer->manufacturer_id)}}"><img alt="{{$manufacturer->name}}" src="{{asset($manufacturer->image)}}">
                <span>{{$manufacturer->name}}</span>
            </a>
        @endforeach
    </div>
        <div class="pagination_wrapper">{{$manufacturers->links()}}</div>
    @endif
</div>
@endsection
