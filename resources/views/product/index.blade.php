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
                <div>
                    <img class="img" src="{{asset($Product['image'])}}">
                </div>
                <div class="info">
                    @if(!empty($Product['manufacturer_image']))   <img style="width: 65px" src="{{asset($Product['manufacturer_image'])}}"> @endif
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

                            <div class="card card-body">

                                {!!$Product['description']!!}

                            </div>

                        </div>

                        <div class="collapse" id="button2">

                            <div class="card card-body">

                                Информация

                            </div>
                        </div>

                    </div>


                </div>
            </div>
            <div class="right_block">
                <p class="price">{{number_format($Product['price'], 0, '', ' ')}} ₽</p>
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

        <script>
            $(document).ready(function(){
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

@endsection
