@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">


               @guest

                   Вы гость

                @endguest


                   <div class="owl-carousel owl-theme" id="slider">
                       <!--Слайд 1-->
                       <div class="slide" >
                           <h2 class="slide__title">Заголовок слайда</h2>
                           <a href="#" class="slide__link">Кнопка</a>
                       </div>
                       <div class="slide" >
                           <h2 class="slide__title">Заголовок слайда</h2>
                           <a href="#" class="slide__link">Кнопка</a>
                       </div>
                       <div class="slide" >
                           <h2 class="slide__title">Заголовок слайда</h2>
                           <a href="#" class="slide__link">Кнопка</a>
                       </div>
                       <!--Остальные слайды-->
                       ...
                   </div>

                   <script type="module">

                       $(document).ready(function(){
                           const slider = $("#slider").owlCarousel({
                               loop:true,
                               margin:10,
                               nav:true,
                               responsive:{
                                   0:{
                                       items:1
                                   },
                                   600:{
                                       items:3
                                   },
                                   1000:{
                                       items:5
                                   }
                               }
                           });
                       });

                   </script>

        </div>
    </div>
</div>
@endsection
