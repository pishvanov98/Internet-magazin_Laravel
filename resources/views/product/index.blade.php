@extends('layouts.app')
@section('content')

    <div class="container product_container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Главная</a></li>
                @if($category)  <li class="breadcrumb-item"><a href="{{route('category.show',$category->slug)}}">{{$category->name}}</a></li> @endif
                <li class="breadcrumb-item active" aria-current="page">{{$Product['name']}}</li>
            </ol>
        </nav>

        <h1>{{$Product['name']}}</h1>
        <div class="content_prod">
            <div class="left_block">
                <div class="d-flex">
                <div class="wrapper_product_image">
                    @if(!empty($Product['icon_img']))
                        <span class="specsale product"><img width="80" height="80" src="{{asset($Product['icon_img'])}}"></span>
                    @endif
                        <a class="m-auto" href="{{asset($Product['image'])}}" data-fancybox>
                            <img class="img" src="{{asset($Product['image'])}}">
                        </a>
                </div>
                <div class="info">
                    @if(!empty($Product['manufacturer_image']))   <img style="width: 65px;padding-bottom: 5px;" src="{{asset($Product['manufacturer_image'])}}"> @endif
                    <p><b>Код товара:</b> {{$Product['model']}}</p>
                    <p><b>Производитель:</b> {{$Product['manufacturer_name']}}</p>
                    <p><b>Страна:</b> {{$Product['manufacturer_region']}}</p>
                    <p><b>Наличие:</b> ({{$Product['quantity']}} шт.)</p>
                </div>
                </div>
                <div class="page_info_prod">

                    <p class="mb-0">
                        <button class="btn btn-info btn-collapse show" type="button" data-bs-toggle="collapse" data-bs-target="#button1" aria-expanded="true" aria-controls="button1">

                            Описание

                        </button>

                        <button class="btn btn-info btn-collapse" type="button" data-bs-toggle="collapse" data-bs-target="#button2" aria-expanded="false" aria-controls="button2">

                            Информация

                        </button>
                    </p>
                    <div class="row">
                        <div class="collapse show" id="button1">

                            <div class="card ">
                                <h4>Описание</h4>
                                {!!$Product['description']!!}
                                @if(!empty($Product['description_two']))
                                    <div class="collapse" id="collapseExample">
                                        <div class="card card-body">
                                            {!!$Product['description_two']!!}
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-sm all_show_description" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                                        Показать еще...
                                    </button>
                                @endif
                            </div>

                        </div>

                        <div class="collapse" id="button2">

                            <div class="card ">

                                @if($Product['product_attr'])
                                    <h4>Свойства</h4>
                                    <div class="d-flex" style="flex-direction: column">
                                    @foreach($Product['product_attr'] as $item)
                                            <div>
                                                <span><strong>{{$item->name}}:</strong></span>
                                                <span>{{$item->text}}</span>
                                            </div>
                                    @endforeach
                                    </div>

                                @endif


                            </div>
                        </div>

                    </div>


                </div>
                @if(!empty($initProductAttr))
                    <h4>Аналоги</h4>
                    <div class="container_carousel">
                        <div id="GoodsSlaiderProductAttr" class="owl-carousel owl-theme slaider_prod">
                            @foreach ($initProductAttr as $product)
                                @include('components.product')
                            @endforeach
                        </div>
                    </div>
                @endif

                @if(!empty($initProductViewed))
                    <h4>Просмотренные товары</h4>
                    <div class="container_carousel">
                        <div id="GoodsSlaiderProductViewed" class="owl-carousel owl-theme slaider_prod">
                            @foreach ($initProductViewed as $product)
                                @include('components.product')
                            @endforeach
                        </div>
                    </div>
                @endif

            </div>
            <div class="right_block">
                @if(!empty($Product['old_price']))
                    <p style="color: red" class="price">{{number_format($Product['price'], 0, '', ' ')}} ₽ <s style="opacity: 0.5;font-size: 14px">{{number_format($Product['old_price'], 0, '', ' ')}} ₽ </s></p>
                @else
                    <p class="price">{{number_format($Product['price'], 0, '', ' ')}} ₽</p>
                @endif
                <div class="d-flex ">
                    <div class="d-flex element-buttons-count">
                    <span class="decr">-</span>
                    <input name="quantity" type="number" value="1" min="1" step="1">
                    <span class="incr">+</span>
                    </div>
                        <button class="btn btn-info buy_button" onclick="addToCartProduct({{$Product['product_id']}})" type="button" >
                            Купить
                        </button>
                </div>
                </div>

        </div>
        @push('css')
            <link rel="stylesheet" href="{{asset('css/jquery.fancybox.min.css')}}" />
        @endpush
        @push('script')
            <script src="{{asset('js/jquery.fancybox.min.js')}}"></script>
        <script>

            $(document).ready(function(){
                $(".wrapper_product_image a").fancybox({
                    transitionIn: 'elastic',
                    transitionOut: 'elastic',
                    speedIn: 500,
                    speedOut: 500,
                    hideOnOverlayClick: false,
                    titlePosition: 'over'
                });
            });

            $(document).ready(function(){

               var height1= $("#button1").height();
                $(".page_info_prod .row").css('min-height',height1);

            $('.btn-collapse').on('click',function (){


                $('.btn-collapse').removeClass('show');

                if($(this).hasClass('show')){
                    $(this).removeClass('show');
                }else{
                    $(this).addClass('show');
                }

             var attr=  $(this).attr("data-bs-target");
               if(attr == "#button1"){
                   $("#button2").removeClass('show');
               }else{
                   $("#button1").removeClass('show');
               }
            });

            $('.decr').on('click',function (){
                var value=$('.element-buttons-count input').val();

                if(Number(value) > 1){
                    var valueSet=Number(value) - 1;
                    $('.element-buttons-count input').val(valueSet);
                }
            });
                $('.incr').on('click',function (){
                    var value=$('.element-buttons-count input').val();

                        var valueSet=Number(value) + 1;
                        $('.element-buttons-count input').val(valueSet);

                });

            });

            jQuery(document).ready(function () {
                $("#GoodsSlaiderProductAttr").owlCarousel({
                    items: 5,
                    responsive: {

                        // Ширина от 500 пикселей
                        500: {
                            items: 2,
                        },
                        1200: {
                            items: 3,
                        }
                    },
                    autoPlay: false,
                    lazyLoad: true,
                    navigation: true,
                    margin: 10,
                    nav: true,
                    dots: false,
                    navigationText: ['‹', '›']
                });
            });

            jQuery(document).ready(function () {
                $("#GoodsSlaiderProductViewed").owlCarousel({
                    items: 5,
                    responsive: {

                        // Ширина от 500 пикселей
                        500: {
                            items: 2,
                        },
                        1200: {
                            items: 3,
                        }
                    },
                    autoPlay: false,
                    lazyLoad: true,
                    navigation: true,
                    margin: 10,
                    nav: true,
                    dots: false,
                    navigationText: ['‹', '›']
                });
            });

$('.all_show_description').on('click',function (){
    if ($(this).hasClass('roll')){
        $(this).text('Показать еще...');
        $(this).removeClass('roll');
    }else{
        $(this).text('Свернуть текст');
        $(this).addClass('roll');
    }
});

            function addToCartProduct(id){
            var count = 1;
            var count_input=$(".element-buttons-count input").val();
            if(count_input > 1){
                count=count_input;
            }
                $.ajax({
                    url: '{{route('addCart')}}',
                    method: 'get',
                    dataType: 'json',
                    data: {id: id,count:count},
                    success: function(data){
                        $('#cart-total').text(data);
                    }
                });

            }



        </script>
    @endpush
@endsection
