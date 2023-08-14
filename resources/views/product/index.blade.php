@extends('layouts.app')
@section('content')

    <div class="container product_container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{route('home')}}">Главная</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{$Product['name']}}</li>
            </ol>
        </nav>
        <h1>{{$Product['name']}}</h1>
        <div class="content_prod">
            <div class="left_block">
                <div>
                    <img src="{{asset($Product['image'])}}">
                </div>
                <div class="info">
                    <p>Артикул {{$Product['model']}}</p>
                    <p>Производитель {{$Product['manufacturer_name']}}</p>
                    <p>Страна {{$Product['manufacturer_region']}}</p>
                    <p>В наличии ({{$Product['quantity']}} шт.)</p>

                </div>
            </div>
            <div class="right_block">
                <p>95 640 ₽</p>
                <p>Купить</p>
            </div>

        </div>

        <div>

            <p>
                <button class="btn btn-info btn-collapse" type="button" data-bs-toggle="collapse" data-bs-target="#button1" aria-expanded="false" aria-controls="button1">

                    Show/Hide Content - Button 1

                </button>

                <button class="btn btn-info btn-collapse" type="button" data-bs-toggle="collapse" data-bs-target="#button2" aria-expanded="false" aria-controls="button2">

                    Show/Hide Content - Button 2

                </button>
            </p>
            <div class="row">
                <div class="collapse" id="button1">

                    <div class="card card-body">

                       {!!$Product['description']!!}

                    </div>

                </div>

                <div class="collapse" id="button2">

                    <div class="card card-body">

                        Content associated with button 2.

                    </div>
            </div>

        </div>


    </div>

        <script>
            $(document).ready(function(){
            $('.btn-collapse').on('click',function (){
             var attr=  $(this).attr("data-bs-target");
               if(attr == "#button1"){
                   console.log('1');
                   $("#button2").removeClass('show');
               }else{
                   $("#button1").removeClass('show');
               }
            });
            });
        </script>

@endsection
