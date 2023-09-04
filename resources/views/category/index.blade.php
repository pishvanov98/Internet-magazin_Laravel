@extends('layouts.app')
@section('content')
    <div class="container block_category">
        <div>
            @if($CategoryTree)
                <ul class="CategoryTree">
                @foreach($CategoryTree as $key=>$item)
                    @if($key !== 'Children')
                            <li> <a href="{{route('category.show',$item->slug)}}">{{$item->name}}</a></li>
                    @endif
                @endforeach
                </ul>
            @endif
                @if($CategoryTree['Children'])
                    <ul class="CategoryTreeChildren">
                        @foreach($CategoryTree['Children'] as $item)
                                <li> <a href="{{route('category.show',$item->slug)}}">{{$item->name}}</a></li>
                        @endforeach
                    </ul>
                @endif
{{--         Фильтр и категорий дерево--}}
            @if($AttrCategory && count($AttrCategory) > 1)
                <ul class="CategoryAttr">
                    <li>  <h5 class="mt-3 mb-2">Фильтры</h5></li>
                    @foreach($AttrCategory as $item)
                        <li>  <strong>{{$item['attribute_name']}}</strong></li>
                        <ul class="CategoryAttrName">
                            @foreach($item['attribute_text'] as $item_attr_text)
                            <li><input class="form-check-input attr_prod" data-id="{{$item['attribute_id']}}" type="checkbox" value=""> {{$item_attr_text}}</li>
                            @endforeach
                        </ul>


                    @endforeach
                </ul>

            @endif
        </div>
        <div>
            <div class="row justify-content-center">
                @if($images_slider)
                    <div class="owl-carousel owl-theme w-75" id="slider">
                        <!--Слайд 1-->
                        @foreach($images_slider as $image)
                            <div class="slide">
                                <img src="{{asset($image['path'])}}">
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
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




    @push('script')

        <script type="module">

            $(document).ready(function () {

                var cssLastCategory=$('.CategoryTree li:last').css('padding-left');
                $('.CategoryTreeChildren li').css('padding-left',cssLastCategory);
                const slider = $("#slider").owlCarousel({
                    loop: true,
                    margin: 5,
                    autoplay: true,
                    autoplayTimeout: 10000,
                    nav: false,
                    responsive: {
                        0: {
                            items: 1
                        },
                    }
                });
            });


        </script>
    @endpush

@endsection
