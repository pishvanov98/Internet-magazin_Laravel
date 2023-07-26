@extends('layouts.app')

@section('content')
<div class="container">
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

<?php
    if(!empty($NewGoodsSlaider)){
    ?>
    <div class="filterslider_home">
        <ul>
            <li data-name="Новинки" data-selectid="GoodsSlaiderHome1" data-link="/entrance"  class="item activ_" >Новинки</li>
            <li data-name="Эксклюзивное предложение" data-selectid="GoodsSlaiderHome2" data-link="/exclusive" class="item" >Эксклюзивные предложения</li>
            <li data-name="aciya" data-selectid="GoodsSlaiderHomeAciya" data-link="/action" class="item" >Акции</li>
            <!--<li class="action_prod_home"><a href="/promo?product"> Товар дня </a></li>-->
        </ul>
        <div id="GoodsSlaiderHome1" class="owl-carousel owl-theme ">
            <?php
                foreach ($NewGoodsSlaider as $product) {  ?>
                <div class="card" style="width: 18rem;">
                    @if($product['DB'] == "Aveldent")
                    <img src="https://aveldent.ru/image/{{$product['image']}}"  class="card-img-top" alt="">
                    @else
                        <img src="" class="card-img-top" alt="...">
                    @endif
                    <div class="card-body">
                        <h5 class="card-title">{{$product['name']}}</h5>
                        <p class="card-text">Стоимость {{$product['price']}}</p>
                        <a href="#" class="btn btn-primary">Купить</a>
                    </div>
                </div>
                <?php } ?>


        </div>
    </div>
<?php } ?>


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

        jQuery(document).ready(function() {
            $("#GoodsSlaiderHome1").owlCarousel({
                items: 5,
                autoPlay: false,
                lazyLoad: true,
                navigation: true,
                nav:true,
                dots: false,
                navigationText: ['‹', '›']
            });
        });

        // $('.filterslider_home ul li.item').click(function() {
        //     if($(this).hasClass("activ_")) {
        //         window.location = $(this).data('link');
        //         return;
        //     }
        //     $(".filterslider_home ul li.item").removeClass('activ_');
        //     $(this).addClass('activ_');
        //
        //
        //     var idSlider = $(this).data("selectid");
        //     idSlider = $("#"+idSlider);
        //
        //     if (idSlider.length > 0) {
        //         $(".filterslider_home div.carousel_goods").addClass('hide_');
        //         idSlider.removeClass('hide_');
        //         return;
        //     }
        //     var name = $(this).data("name");
        //
        //
        //     $.ajax({
        //         url: '/index.php?route=product/search&taghome='+name, //Путь к файлу, который нужно подгрузить https://aveldent.ru/index.php?route=product/search&tag=Эксклюзивное предложение
        //         type: 'GET',
        //         beforeSend: function(){
        //             //$("#GoodsSlaiderHome1").addClass('hide_');
        //             $(".filterslider_home div.carousel_goods").addClass('hide_');
        //             idSlider.removeClass('hide_');
        //         },
        //         success: function(data){
        //             $('.container_carousel').append(data); //Подгрузка внутрь блока
        //         },
        //         error: function(){
        //             alert('Error!');
        //         }
        //     });
        //
        //
        //
        // });




    </script>
@endpush

@endsection
