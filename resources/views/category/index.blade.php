@extends('layouts.app')
@section('content')
    <div class="container block_category">
        <div>
            Фильтр и категорий дерево
        </div>
        <div>
        <div class="row justify-content-center">
            @if($images_slider)
                <div class="owl-carousel owl-theme w-75" id="slider">
                    <!--Слайд 1-->
                    @foreach($images_slider as $image)
                        <div class="slide" >
                            <img src="{{asset($image['path'])}}">
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <div class="wrapper_goods">


            @if($Products)

                @foreach($Products as $product)

                    <div class="card" style="width: 290px;min-height: 400px;">

                        <a class="wrapper_img_card" href="product/{{$product->slug}}">@if($product->image) <img src="{{asset($product->image)}}"  class="card-img-top" alt=""> @else <img src="{{asset('img/zag_258x258.svg')}}"  class="card-img-top" alt=""> @endif </a>

                        <div class="card-body">
                            <a class="card-title" href="product/{{$product->slug}}"><h6>{{$product->name}}</h6></a>
                            <p class="card-text">Стоимость {{$product->price}}</p>
                            <a href="#" class="btn btn-primary">Купить</a>
                        </div>
                    </div>

                @endforeach


            @endif



        </div>
            <div class="pagination_wrapper">  {{$Products->links()}} </div>
        </div>
    </div>




    @push('script')

        <script type="module">

            $(document).ready(function(){
                const slider = $("#slider").owlCarousel({
                    loop:true,
                    margin:5,
                    autoplay: true,
                    autoplayTimeout: 10000,
                    nav:false,
                    responsive:{
                        0:{
                            items:1
                        },
                    }
                });
            });



        </script>
    @endpush

@endsection
