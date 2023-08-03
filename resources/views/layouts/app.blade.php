<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Scripts -->
    <script src="{{asset('js/jquery-3.4.1.min.js')}}"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="{{asset('js/typeahead.bundle.min.js')}}"></script>
</head>
<body>
<div id="app">
    <nav  class="py-2  ">
        <div class="container d-flex flex-wrap top_header">
            <ul class="nav me-auto">
                <li class="nav-item menu_Info menu-hover">
                        <a href="tel:+74995020830" class="menu_Info">О компании <img style="width: 20px" src="{{asset('/img/caret.svg')}}"></a>
                        <ul class="submenu">
                            <li><a href="/about_avel">О нас</a></li>
                            <li><a href="/otzyvi">Отзывы</a></li>
                            <li><a href="/news">Новости</a></li>
                            <li><a href="/seminar">Семинары</a></li>
                            <li><a href="/team">Наша команда</a></li>
                            <li><a href="/region">Региональным клиентам</a></li>
                            <li><a href="/viezd-predstavitelya">Выезд нашего представителя</a></li>
                            <li><a href="/vakansii">Вакансии</a></li>
                            <li><a href="/contact">Контакты</a></li>
                            <li><a href="/faq">Вопрос-ответ</a></li>
                        </ul>

                </li>
                <li class="nav-item"><a href="https://aveldent.ru/dostavka" class="nav-link link-dark px-2">Доставка</a></li>
                <li class="nav-item"><a href="https://aveldent.ru/oplata" class="nav-link link-dark px-2">Оплата</a></li>
                <li class="nav-item"><a href="/vozvrat" class="nav-link link-dark px-2">Возврат</a></li>
                <li class="nav-item"><a href="https://aveldent.ru/samovivoz" class="nav-link link-dark px-2">Самовывоз</a></li>
                <li class="nav-item"><a href="https://aveldent.ru/predzakaz" class="nav-link link-dark px-2">Предзаказ</a></li>
                <li class="nav-item"><a href="/seminar" class="nav-link link-dark px-2">Семинары</a></li>
                <li class="nav-item" style="color: #e43535;"><a href="/promo" class="nav-link link-dark px-2">Акции</a></li>
            </ul>
            <ul class="nav right-menu">
                <a href="#"><img style="width: 25px;height: 25px;" src="{{asset('/img/account2.0.svg')}}">Войти</a>
                @if(!empty($cart)) {{$cart}} @else
                    <a href="/index.php?route=checkout/cart"><div style="line-height: 0.6;" id="cart"><img style="width: 25px;height: 25px;" src="{{asset('/img/cart.svg')}}"> <span class="text-items" id="cart-total">  1 Товар - 32 руб.</span>
                    </div></a>
                @endif
            </ul>
        </div>
    </nav>
    <header class="py-3 pb-0">
        <div class="container d-flex flex-wrap justify-content-center">
            <div class="d-flex align-items-center mb-3 mb-lg-0 me-lg-auto ">
            <a href="/" class="text-decoration-none">
                <img width="300" height="50" src="{{asset('/img/home_sd.png')}}">
            </a>
            <div class="centre-block-search">
                  <div onclick="openCategory()" class="centre-block-search-category  desktop_centre-block">
                      <img style="width: 20px;height: 17.5px;" src="{{asset('/img/menu.svg')}}">
                      <span class="centre-block-search-search">Каталог</span>
                  </div>
                  <div class="search" id="search"><img class="button-search" src="{{asset('/img/search.svg')}}">
                            <input type="text" name="search" placeholder="Введите название, код товара или артикул" value="" style="border: 1px solid #00b5d3;margin: 0;"/>
                            <img class="input__clear"  src="{{asset('/img/cross.svg')}}">
                  </div>
            </div>
            </div>
            <div class="centre-block-info ">
                <div class="menu_callback menu-hover">
                    <a href="tel:+74995020830" class="phone_callback">+7 499 502-08-30 <img style="width: 20px" src="{{asset('/img/caret.svg')}}"></a>
                    <ul class="submenu">
                        <li><span>Пункт выдачи:</span></li>
                        <li><p><img style="width: 22px;" src="{{asset('/img/marker.svg')}}"> г. Москва, Шереметьевская улица, 47</p></li>
                        <li><a href="mailto:zakaz@aveldent.ru"><img style="width: 22px" src="{{asset('/img/mail.svg')}}"> zakaz@aveldent.ru</a></li>
                        <li><a href="https://aveldent.ru/samovivoz"><img style="width: 22px" src="{{asset('/img/ok.svg')}}"> Пн–Пт: 09:00 – 18:00</a></li>
                        <li><span>Склад:</span></li>
                        <li><p><img style="width: 22px" src="{{asset('/img/marker.svg')}}"> г. Долгопрудный, Московская область, улица Жуковского, 3</p></li>
                        <li><a href="https://aveldent.ru/samovivoz"><img style="width: 22px" src="{{asset('/img/ok.svg')}}"> Пн–Пт: 09:00 – 18:00</a></li>
                    </ul>
                </div>
                <div class="button_callback popup-open-orderCall">Заказать звонок</div>
            </div>
            <div  class="icon_header">
                <a href="https://t.me/Aveldentru"><img style="height: 35px;width: 35px;cursor: pointer; padding: 0;" src="{{asset('/img/telegram.svg')}}"></a>
                <a href="https://vk.com/aveldentru"><img  style="height: 35px;width: 35px;cursor: pointer; padding: 0;margin-left: 5px" src="{{asset('/img/vk.svg')}}"></a>
            </div>
        </div>
        <div class="wrapper_category_list">
            <div class="catalog_list">
                <div class="catefory_list">
                    <div class="category_list_left">
                        <?php if ($categories) { ?>
                        <a class="category_lef_2" style="color:#e43535;" href="/promo">Акции</a>
                        <a class="category_lef_2" style="color:#e43535;" href="/exclusive">Эксклюзивные предложения</a>
                        <a class="category_lef" href="https://aveldent.ru/brands" data-name="Производители">Производители <img src="{{asset('/img/caret2.svg')}}"></a>
                        <?php foreach ($categories as $category) { ?>

                        <?php if (!empty($category['children']) && count($category['children']) != 0){ ?>
                        <a class="category_lef"  href="<?php echo $category['href']; ?>" data-name="<?php echo $category['name']; ?>"><?php echo $category['name']; ?>
                            <img src="{{asset('/img/caret2.svg')}}">
                            <?php  } else {?>
                            <a class="category_lef_2" style=""  href="<?php echo $category['href']; ?>" data-name="<?php echo $category['name']; ?>"><?php echo $category['name']; ?>

                                <? } ?>
                            </a>
                            <?php  } ?>
                            <a class="category_lef_2 item_img_seminar" href="/seminar"><img src="{{asset('/img/seminar2.jpg')}}"></a>
                        <?php } ?>
                    </div>
                    <div class="category_list_right">
                        <div class="category_children" data-name_children="Производители">
                            <div class="unstyled">
                                @if(!empty($brands))
                                    @foreach($brands as $val_manufactur)
                                        <a href="{{$val_manufactur['href']}}">{{$val_manufactur['name']}}</a>
                                    @endforeach
                                    <a class="all_category_list_item" href="/brands" >Смотреть всех производителей</a>
                                @endif
                            </div>
                        </div>
                        @if($categories)
                            @foreach($categories as $category)
                                @if(!empty($category['children']))
                                    <div class="category_children" data-name_children="{{$category['name']}}">
                                        <div class="unstyled">
                                            @foreach($category['children'] as $children)
                                                @if(!empty($children['children_children']))

                                                            <div class="item_category{{$children['category_id']}}">

                                                                <span class="wrapper_children_category caret_bottom" >
                                                            <a href="{{$children['href']}}">
                                                                {{$children['name']}}
                                                            </a>
                                                        <span class="caret_category_child" onclick="open_category_children('{{$children['category_id']}}');">
                                                            <img src="{{asset('/img/caret2.svg')}}">
                                                        </span>
                                                        </span>
                                                                <div class="block_children_category">
                                                                    <div class="shadow_category">
                                                                    @foreach($children['children_children'] as $children_children)
                                                                            <a href="{{$children_children['href']}}">{{$children_children['name']}}</a>
                                                                        @endforeach
                                                                    </div>
                                                                </div>
                                                            </div>

                                                @else
                                                    <span ><a href="{{$children['href']}}">{{$children['name']}}</a></span>
                                                @endif
                                            @endforeach
                                                <a class="all_category_list_item" href="{{$category['href']}}" >Смотреть все {{$category['name']}}</a>
                                        </div>
                                    </div>
                                            @endif



                                    @endforeach
                                @endif

                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="py-4">
        @yield('content')
    </main>
</div>
</body>
<script type="module">

    jQuery(document).ready(function($) {

        // Set the Options for "Bloodhound" suggestion engine
        var bloodhound = new Bloodhound({
            datumTokenizer: Bloodhound.tokenizers.whitespace,
            queryTokenizer: Bloodhound.tokenizers.whitespace,
            remote: {
                url: '{{route('query','%QUERY%')}}',
                wildcard: '%QUERY%'
            },
        });

        $('#search input').typeahead({
            hint: true,
            highlight: true,
            minLength: 1
        }, {
            name: 'users',
            source: bloodhound,
            display: function(data) {
                return data.name  //Input value to be set when you select a suggestion.
            },
            templates: {
                empty: [
                    '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                ],
                suggestion: function (data) {
                    return '<a href="' + data.product_id + '" class="list-group-item">' + data.name + '</a>'
                }
            }
        });
    });

</script>

<script>
     function openCategory(){
        if ( $(".catalog_list").hasClass("catalog_list_open") ) {
            $('.catalog_list').removeClass('catalog_list_open');
        }else{
            $( ".catalog_list" ).addClass( "catalog_list_open" );
        }
    }
     function open_category_children(a){
         if($('.caret_category_child').hasClass('active')){
             $(' .block_children_category').removeClass('active');
             $(' .caret_category_child').removeClass('active');
             $(' .caret_bottom').removeClass('active');
         }else{
             $('.item_category'+a+' .block_children_category').addClass('active');
             $('.item_category'+a+' .caret_category_child').addClass('active');
             $('.item_category'+a+' .caret_bottom' ).addClass('active');
         }
     }


     $('.category_lef').eq(4).addClass('active_category');
     $('.category_lef').eq(4).addClass('active_category_img');
     $( '.category_children' ).eq(4).css( "display", "block" );

     $('.category_list_left .category_lef').hover(function() {
         var data= $(this).attr('data-name');
         if ( $(".category_lef").hasClass("active_category") ) {
             $('.category_lef').removeClass('active_category');
         }
         if ( $(".category_lef").hasClass("active_category_img") ) {
             $('.category_lef').removeClass('active_category_img');
         }
         $(this) .addClass('active_category');
         $(this) .addClass('active_category_img');
         let children_mass = document.querySelectorAll('[data-name_children]');
         for( let i = 0; i < children_mass.length; i++){ // проходим циклом по всем элементам объекта
             if(children_mass[i].getAttribute('data-name_children') == data){
                 $( children_mass[i] ).css( "display", "block" );

             }
             else{
                 $( children_mass[i] ).css( "display", "none" );
             }
         }

     });

     $(document).mouseup( function(e){ // событие клика по веб-документу
         var click = $( ".catalog_list" );
         if ( !click.is(e.target) && click.has(e.target).length === 0 && !$( ".centre-block-search-category" ).is(e.target) && $( ".centre-block-search-category" ).has(e.target).length === 0) { // если клик был не по блоку и не по его дочерним элементам
             $('.catalog_list').removeClass('catalog_list_open');
         }
     });


</script>

@stack('script')

</html>
