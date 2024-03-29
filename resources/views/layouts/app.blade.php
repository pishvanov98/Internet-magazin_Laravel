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
    @stack('css')

    <!-- Scripts -->
    <script src="{{asset('js/jquery-3.4.1.min.js')}}"></script>
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <script src="{{asset('js/typeahead.bundle.min.js')}}"></script>
</head>
<body>
<div id="app">
    @notmobile
    <nav>
        <div class="container d-flex flex-wrap top_header pt-1 pb-1">
            <ul class="nav me-auto">
                <li class="nav-item menu_Info menu-hover">
                        <a href="tel:+74995020830" class="menu_Info">О компании <img style="width: 20px" src="{{asset('/img/caret.svg')}}"></a>
                        <ul class="submenu">
                            <li><a href="{{route('page','about_avel')}}">О нас</a></li>
                            <li><a href="{{route('page','otzyvi')}}">Отзывы</a></li>
{{--                            <li><a href="#">Новости</a></li>--}}
{{--                            <li><a href="#">Семинары</a></li>--}}
                            <li><a href="{{route('page','region')}}">Региональным клиентам</a></li>
                            <li><a href="{{route('page','viezd-predstavitelya')}}">Выезд нашего представителя</a></li>
                            <li><a href="{{route('page','vakansii')}}">Вакансии</a></li>
                            <li><a href="{{route('page','contact')}}">Контакты</a></li>
                            <li><a href="{{route('page','faq')}}">Вопрос-ответ</a></li>
                        </ul>

                </li>
                <li class="nav-item"><a href="{{route('page','dostavka')}}" class="nav-link link-dark px-2">Доставка</a></li>
                <li class="nav-item"><a href="{{route('page','oplata')}}" class="nav-link link-dark px-2">Оплата</a></li>
                <li class="nav-item"><a href="{{route('page','vozvrat')}}" class="nav-link link-dark px-2">Возврат</a></li>
                <li class="nav-item"><a href="{{route('page','samovivoz')}}" class="nav-link link-dark px-2">Самовывоз</a></li>
                <li class="nav-item"><a href="{{route('page','predzakaz')}}" class="nav-link link-dark px-2">Предзаказ</a></li>
{{--                <li class="nav-item"><a href="#" class="nav-link link-dark px-2">Семинары</a></li>--}}
{{--                <li class="nav-item" style="color: #e43535;"><a href="#" class="nav-link link-dark px-2">Акции</a></li>--}}
            </ul>
            <ul class="nav right-menu">
                @guest
                    <a href="{{route('login')}}"><img style="width: 25px;height: 25px;" src="{{asset('/img/account2.0.svg')}}">Войти</a>
                @else
                    <a href="{{route('account')}}"><img style="width: 25px;height: 25px;" src="{{asset('/img/account2.0.svg')}}">{{ Auth::user()->name }}</a>
                @endguest

                    <a href="{{route('cart')}}"><div style="line-height: 0.6;" id="cart"><img style="width: 25px;height: 25px;" src="{{asset('/img/cart.svg')}}"> <span class="text-items" id="cart-total"> {{$infoCart[0].$infoCart[1]}} </span> </div></a>

            </ul>
        </div>
    </nav>
    <header class="py-3 pb-0 pt-0">
        <div style="margin-top: 30px" class="container d-flex flex-wrap justify-content-center ">
            <div class="d-flex align-items-center mb-3 mb-lg-0 me-lg-auto ">
            <a href="/" class="text-decoration-none">
                <img width="300" height="50" src="{{asset('/img/home_sd.png')}}">
            </a>
            <div class="centre-block-search">
                  <div onclick="openCategory()" class="centre-block-search-category  desktop_centre-block">
                      <img style="width: 20px;height: 17.5px;margin-right: 5px;" src="{{asset('/img/menu.svg')}}">
                      <span class="centre-block-search-search">Каталог</span>
                  </div>
                  <div class="search" id="search">
                      <form method="get" action="{{route('search')}}">
                          <button class="button-search"><img width="25" src="{{asset('/img/search.svg')}}"></button>
                          <input type="text" name="search" placeholder="Введите название или код товара" value="" style="border: 1px solid #00b5d3;margin: 0;"/>
                          <img class="input__clear"  src="{{asset('/img/cross.svg')}}">
                      </form>
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
                        <li><a href="{{route('page','samovivoz')}}"><img style="width: 22px" src="{{asset('/img/ok.svg')}}"> Пн–Пт: 09:00 – 18:00</a></li>
                        <li><span>Склад:</span></li>
                        <li><p><img style="width: 22px" src="{{asset('/img/marker.svg')}}"> г. Долгопрудный, Московская область, улица Жуковского, 3</p></li>
                        <li><a href="{{route('page','samovivoz')}}"><img style="width: 22px" src="{{asset('/img/ok.svg')}}"> Пн–Пт: 09:00 – 18:00</a></li>
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
        </div>
    </header>
    @elsenotmobile
    <header class="py-3 pb-0 pt-0" >
    <nav class="navbar navbar-expand-lg mobile-header" style="padding: 0 10px;">
        <div class="container-fluid px-0">
            <a class="navbar-brand" href="/" ><img width="180" height="26" src="{{asset('/img/home_sd.png')}}" alt="" /></a>
            <!-- Mobile view nav wrap -->
            <div class="ms-auto d-flex align-items-center order-lg-3">
                <ul class="navbar-nav navbar-right-wrap mx-2 flex-row">
                    <li>
                        <a style="display: contents;" href="{{route('cart')}}">
                            <svg style="color: #00aeef;" xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                            </svg>
                            <span style="background-color:#00aeef !important;" class="badge text-bg-secondary" id="cart_itogo_mobile">{{$infoCart[0]}}</span>
                        </a>
                    </li>
                    <li class="dropdown ms-2 d-inline-block position-static">
                        <a class="rounded-circle" href="#" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false">
                            <div class="avatar avatar-md avatar-indicators avatar-online">
                                <svg style="color:#00aeef;" xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                    <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                    <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                </svg>
                            </div>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end position-absolute items_account">
                            <div class="dropdown-item">
                                <div class="d-flex">
                                    <div class="avatar avatar-md avatar-indicators avatar-online">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-circle" viewBox="0 0 16 16">
                                            <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/>
                                            <path fill-rule="evenodd" d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1z"/>
                                        </svg>
                                    </div>
                                    <div class="ms-3 lh-1">
                                        @guest
                                            <a href="{{route('login')}}">Войти</a>
                                        @else
                                            <h5 class="mb-1"><a href="{{route('account')}}">{{ Auth::user()->name }}</a></h5>
                                            <p class="mb-0"><a href="{{route('account')}}">{{ Auth::user()->email }}</a></p>
                                        @endguest

                                    </div>
                                </div>
                            </div>
                            <div class="dropdown-divider"></div>
                            <ul class="list-unstyled">
                                <li>
                                    <a class="dropdown-item" href="{{route('account')}}">
                                        <i class="fe fe-user me-2"></i>
                                        Профиль
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{route('account.order')}}">
                                        <i class="fe fe-star me-2"></i>
                                        Мои заказы
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{route('account.wishlist')}}">
                                        <i class="fe fe-settings me-2"></i>
                                        Избранное
                                    </a>
                                </li>
                            </ul>
                            <div class="dropdown-divider"></div>
                            <ul class="list-unstyled">
                                <li>
                                    <a class="dropdown-item" href="{{route('exit')}}">
                                        <i class="fe fe-power me-2"></i>
                                        Выйти
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>
                </ul>
            </div>
            <div>
                <!-- Button -->
                <button
                    class="navbar-toggler collapsed"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navbar-default-header"
                    aria-controls="navbar-default-header"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                    style="background-color: #00aeef;padding: 4px 10px;"
                    >
                    <img style="width: 20px;height: 17.5px;" src="{{asset('/img/menu.svg')}}">
                </button>
            </div>
            <!-- Collapse -->
            <div class="collapse navbar-collapse" id="navbar-default-header">
                <ul class="navbar-nav">

                    <li class="nav-item dropdown">
                        <a  style="background-color: #00aeef;padding: 6px 10px;border-radius: 5px;"
                            class="nav-link "
                            href="#"
                            id="navbarDropdown"
                            data-bs-toggle="dropdown"
                            aria-haspopup="true"
                            aria-expanded="false"
                            data-bs-display="static"
                            onclick="showCategoryListMobile();">
                            <img style="width: 20px;height: 17.5px;margin-right: 5px;" src="{{asset('/img/menu.svg')}}">
                            <span style="color: #fff">Каталог</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-arrow" aria-labelledby="navbarDropdown">
                        </ul>
                    </li>
                </ul>

                <form style="width: 225px" class="mt-3 mt-lg-0 ms-lg-3 d-flex align-items-center " id="search_mobile">
                                                                 <span class="position-absolute ps-3 search-icon">
                                                                 <i class="fe fe-search"></i>
                                                                 </span>
                    <input type="search" class="form-control ps-6" placeholder="Введите название товара..." />
                </form>
            </div>
        </div>
    </nav>
    </header>
    @endnotmobile

    <main class="py-4">
        @yield('content')
    </main>
</div>
@notmobile
<footer>
<div class="container">
    <div class="wrapper_footer">
        <div>
            <ul>
                <li>
                    <b><a href="{{route('page','about_avel')}}">О компании</a></b>
                </li>
                <li><a href="{{route('page','otzyvi')}}">Отзывы клиентов</a></li>
                <li><a href="{{route('page','dostavka')}}">Доставка </a></li>
                <li><a href="{{route('page','oplata')}}">Оплата</a></li>
                <li><a href="{{route('page','vozvrat')}}">Условия возврата</a></li>
                <li><a href="{{route('page','region')}}">Региональным клиентам</a></li>
                <li><a href="{{route('page','viezd-predstavitelya')}}">Выезд нашего представителя</a></li>
            </ul>
        </div>
        <div>
            <ul>
                <li>
                    <b>Сервисы</b>
                </li>
                <li><a href="{{route('page','contact')}}">Контакты</a></li>
{{--                <li><a href="#">Новости</a></li>--}}
                <li><a href="{{route('manufacturer')}}">Производители</a></li>
{{--                <li><a href="#">Вебинары</a></li>--}}
                <li><a href="{{route('page','vakansii')}}">Вакансии</a></li>
{{--                <li><a href="#">Семинары</a></li>--}}
                <li><a href="{{route('page','faq')}}">Вопрос-ответ</a></li>
            </ul>
        </div>
        <div>
            <ul>
                <li>
                    <b><a href="#">Каталог</a></b>
                </li>
                <li><a href="{{route('category.show','apteka')}}">Аптека</a></li>
                <li><a href="{{route('category.show','dezinfekciya')}}">Дезинфекция</a></li>
                <li><a href="{{route('category.show','zubotehnicheskie-materialy-i-oborudovanie')}}">Зуботехнические материалы и <br>оборудование</a></li>
                <li><a href="{{route('category.show','instrumenty')}}">Инструменты</a></li>
                <li><a href="{{route('category.show','materialy')}}">Материалы</a></li>
                <li><a href="{{route('category.show','oborudovanie')}}">Оборудование</a></li>
                <li><a href="{{route('category.show','rashodnye-materialy')}}">Расходные материалы</a></li>
                <li><a href="{{route('category.show','suveniry')}}">Сувениры</a></li>
                <li><a href="{{route('category.show','endodonticheskie-instrumenty')}}">Эндодонтические инструменты</a></li>
            </ul>
        </div>
        <div>
            <div>
                <p><b>Подпишитесь на наши <br>новости и акции</b></p>
                <div class="d-flex"><input type="email" name="mailingList"><button class="button_mailList"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16"><path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/></svg></button></div>
            </div>
            <div>
                <p>Мы в социальных сетях</p>
                <div>
                    <a href="https://t.me/Aveldentru"><img style="height: 35px;width: 35px;cursor: pointer; padding: 0;" src="{{asset('/img/telegram.svg')}}"></a>
                    <a href="https://vk.com/aveldentru"><img  style="height: 35px;width: 35px;cursor: pointer; padding: 0;margin-left: 5px" src="{{asset('/img/vk.svg')}}"></a>
                </div>
            </div>
        </div>
        <div>
            <ul>
                <li><b>Остались вопросы?</b></li>
                <li><a href="tel:+74995020830">+7 499 502-08-30 </a></li>
                <li><a href="mailto:zakaz@aveldent.ru">zakaz@aveldent.ru</a></li>
            </ul>
        </div>
    </div>
</div>
</footer>
@elsenotmobile
<footer>
    <div style="height: 390px;" class="container">
        <div class="wrapper_footer footer_mobile">


                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuFooter1" data-bs-toggle="dropdown" aria-expanded="false">
                        О компании
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuFooter1">
                        <li><a href="{{route('page','otzyvi')}}">Отзывы клиентов</a></li>
                        <li><a href="{{route('page','dostavka')}}">Доставка </a></li>
                        <li><a href="{{route('page','oplata')}}">Оплата</a></li>
                        <li><a href="{{route('page','vozvrat')}}">Условия возврата</a></li>
                        <li><a href="{{route('page','region')}}">Региональным клиентам</a></li>
                        <li><a href="{{route('page','viezd-predstavitelya')}}">Выезд нашего представителя</a></li>
                    </ul>
                </div>



                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuFooter2" data-bs-toggle="dropdown" aria-expanded="false">
                        Сервисы
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuFooter2">
                        <li><a href="{{route('page','contact')}}">Контакты</a></li>
                        <li><a href="{{route('manufacturer')}}">Производители</a></li>
                        <li><a href="{{route('page','vakansii')}}">Вакансии</a></li>
                        <li><a href="{{route('page','faq')}}">Вопрос-ответ</a></li>
                    </ul>
                </div>



                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuFooter3" data-bs-toggle="dropdown" aria-expanded="false">
                        Каталог
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuFooter3">
                        <li><a href="{{route('category.show','apteka')}}">Аптека</a></li>
                        <li><a href="{{route('category.show','dezinfekciya')}}">Дезинфекция</a></li>
                        <li><a href="{{route('category.show','zubotehnicheskie-materialy-i-oborudovanie')}}">Зуботехнические материалы и <br>оборудование</a></li>
                        <li><a href="{{route('category.show','instrumenty')}}">Инструменты</a></li>
                        <li><a href="{{route('category.show','materialy')}}">Материалы</a></li>
                        <li><a href="{{route('category.show','oborudovanie')}}">Оборудование</a></li>
                        <li><a href="{{route('category.show','rashodnye-materialy')}}">Расходные материалы</a></li>
                        <li><a href="{{route('category.show','suveniry')}}">Сувениры</a></li>
                        <li><a href="{{route('category.show','endodonticheskie-instrumenty')}}">Эндодонтические инструменты</a></li>
                    </ul>
                </div>


        </div>
        <div class="wrapper_footer footer_mobile">

            <div>
                <div>
                    <p><b>Подпишитесь на наши <br>новости и акции</b></p>
                    <div class="d-flex"><input type="email" name="mailingList"><button class="button_mailList"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-right-fill" viewBox="0 0 16 16"><path d="m12.14 8.753-5.482 4.796c-.646.566-1.658.106-1.658-.753V3.204a1 1 0 0 1 1.659-.753l5.48 4.796a1 1 0 0 1 0 1.506z"/></svg></button></div>
                </div>
            </div>
            <div>
                <ul style="padding: 0; padding-top: 5px">
                    <li><b>Остались вопросы?</b></li>
                    <li><a href="tel:+74995020830">+7 499 502-08-30 </a></li>
                    <li><a href="mailto:zakaz@aveldent.ru">zakaz@aveldent.ru</a></li>
                </ul>
            </div>
        </div>
        <div>
            <p>Мы в социальных сетях</p>
            <div>
                <a href="https://t.me/Aveldentru"><img style="height: 35px;width: 35px;cursor: pointer; padding: 0;" src="{{asset('/img/telegram.svg')}}"></a>
                <a href="https://vk.com/aveldentru"><img  style="height: 35px;width: 35px;cursor: pointer; padding: 0;margin-left: 5px" src="{{asset('/img/vk.svg')}}"></a>
            </div>
        </div>

    </div>
</footer>
@endnotmobile
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
            highlight: false,
            minLength: 3,

        }, {
            // name: 'users',
            source: bloodhound,
            display: function(data) {

                return data.name  //Input value to be set when you select a suggestion.
            },
            templates: {
                // empty: [
                //     '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                // ],
                suggestion: function (data) {

                    if (data.slug_category){
                        return '<a href="' + data.slug_category + '" class="list-group-item">' + data.name + '</a>'
                    }else{
                        return '<a href="' + data.slug + '" class="list-group-item">' + data.name + '</a>'
                    }
                }
            },
            limit: 25
        });
    });

    $('#search input').on('keypress',function(e) {
        if(e.which == 13) {
            $('#search .button-search').trigger('click');
        }
    });

</script>


@mobile
<script>
    jQuery(function($){
        $(document).mouseup(function (e){ // событие клика по веб-документу
            var div = $("#navbar-default-header"); // тут указываем ID элемента
            if (!div.is(e.target) // если клик был не по нашему блоку
                && div.has(e.target).length === 0) { // и не по его дочерним элементам
                $('#navbar-default-header').removeClass('active');
                $('#navbar-default-header .dropdown-menu:first').css('display','none');
            }
        });
    });

    function showCategoryListMobile(){

        if($('#navbar-default-header').hasClass('active')){
            $('#navbar-default-header').removeClass('active');
            $('#navbar-default-header .dropdown-menu:first').css('display','none');
        }else{
            $('#navbar-default-header').addClass('active');
            $('#navbar-default-header .dropdown-menu:first').css('display','block');
        }

        if($('#navbar-default-header .dropdown-menu-arrow li').length == 0){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                url:'{{route('header.category')}}',
                method:'post',
                dataType:'html',
                success: function (data){
                    $(data).appendTo('#navbar-default-header .dropdown-menu-arrow');
                }
            });
        }
    }

    function addToCart(id,count){

        $.ajax({
            url: '{{route('addCart')}}',
            method: 'get',
            dataType: 'json',
            data: {id: id,count:count},
            success: function(data){
                $('.mobile-header #cart_itogo_mobile').text(data[0]);
            }
        });

    }




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

        $('#search_mobile input').typeahead({
            hint: true,
            highlight: false,
            minLength: 3,

        }, {
            // name: 'users',
            source: bloodhound,
            display: function(data) {

                return data.name  //Input value to be set when you select a suggestion.
            },
            templates: {
                // empty: [
                //     '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                // ],
                suggestion: function (data) {

                    if (data.slug_category){
                        return '<a href="' + data.slug_category + '" class="list-group-item">' + data.name + '</a>'
                    }else{
                        return '<a href="' + data.slug + '" class="list-group-item">' + data.name + '</a>'
                    }
                }
            },
            limit: 25
        });
    });


</script>

@elsemobile

<script>
    $('body').addClass('desctop');
    function addToCart(id,count){

        $.ajax({
            url: '{{route('addCart')}}',
            method: 'get',
            dataType: 'json',
            data: {id: id,count:count},
            success: function(data){
                $('#cart-total').text(data[0]+data[1]);
            }
        });

    }

</script>

@endmobile



<script>
     function openCategory(){

         showCategoryList();

        if ( $(".catalog_list").hasClass("catalog_list_open") ) {
            $('.catalog_list').removeClass('catalog_list_open');
        }else{
            $( ".catalog_list" ).addClass( "catalog_list_open" );
        }
    }

     function showCategoryList(){

         if($('.catalog_list').length == 0){
             $.ajaxSetup({
                 headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                 }
             });
             $.ajax({
                url:'{{route('header.category')}}',
                method:'post',
                dataType:'html',
                success: function (data){
                    $(data).appendTo('.wrapper_category_list');
                    $( ".catalog_list" ).addClass( "catalog_list_open" );
                }
             });

         }

     }

     function addToWishlist(id){

         $.ajax({
             url: '{{route('addToWishlist')}}',
             method: 'get',
             dataType: 'json',
             data: {id: id},
             success: function(data){

               if(data == 1){
                   $('.card_item'+id+ ' .wishlist').html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/> </svg>');
               }else{
                   $('.card_item'+id+ ' .wishlist').html('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16"> <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/> </svg>');

               }

             }
         });

     }

     $('.input__clear').on('click touch',function () {
         $('.search input[name="search"]').val('');
         $(".input__clear").attr('style', 'opacity: 0;');
     });
     $('.search').keyup(function () {
         $(".input__clear").attr('style', 'opacity: 1;');

     });


</script>

@stack('script')

</html>
