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
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
</head>
<body>
<div id="app">
    <div class="wrapper_header">
        <div class="wrapper-block-header">
            <a class="logo_avel"  href="#" title="Авельдент - Стоматологические материалы"><img src="/image/new_header/aveldent_logo.svg"></a>
            <div class="top-block-header">
                <ul class="top-block-header-left" style="margin: 0">
                    <li class="menu_callback display_none_small3 menu-hover display_none_small2">
                        <a href="tel:+74995020830">+7 499 502-0830 <img style="width: 20px" src="/image/new_header/caret.svg"></a>
                        <ul class="submenu">
                            <li><span>Пункт выдачи:</span></li>
                            <li><p><img style="width: 20px" src="/image/new_header/marker.svg"> г. Москва, Шереметьевская улица, 47</p></li>
                            <li><a href="mailto:zakaz@aveldent.ru"><img style="width: 20px" src="/image/new_header/mail.svg"> zakaz@aveldent.ru</a></li>
                            <li><a href="https://aveldent.ru/samovivoz"><img style="width: 19px" src="/image/new_header/ok.svg"> Пн–Пт: 09:00 – 18:00</a></li>
                            <li><span>Склад:</span></li>
                            <li><p><img style="width: 20px" src="/image/new_header/marker.svg"> г. Долгопрудный, Московская область, улица Жуковского, 3</p></li>
                            <li><a href="https://aveldent.ru/samovivoz"><img style="width: 19px" src="/image/new_header/ok.svg"> Пн–Пт: 09:00 – 18:00</a></li>
                            <li style="text-align: center">Мы в социальных сетях</li>
                            <li style="display: grid;grid-template-columns: 30px 30px;justify-content: center;"><a href="https://t.me/Aveldentru"><img style="width: 25px;height:25px; cursor: pointer; padding: 0 5px 0 5px;" src="/image/new_header/telegram.svg"></a><a href="https://vk.com/aveldentru"><img style="width: 25px;cursor: pointer; padding: 0 5px 0 5px;" src="/image/new_header/vk.svg"></a></li>
                        </ul>
                    </li>
                    <li class="menu_Info menu-hover display_none_small2">
                        <a href="https://aveldent.ru/dostavka">О компании<img style="width: 20px; height: 20px;" src="/image/new_header/caret.svg"></a>
                        <ul class="submenu">
                            <li><a href="/about_avel">О нас</a></li>
                            <li><a href="/otzyvi">Отзывы</a></li>
                            <li><a href="/news">Новости</a></li>
                            <li class="display_block_small"><a href="https://aveldent.ru/predzakaz">Предзаказ</a></li>
                            <li class="display_block_small"><a href="/promo">Акции</a></li>
                            <li><a href="/seminar">Семинары</a></li>
                            <li><a href="/team">Наша команда</a></li>
                            <li><a href="/region">Региональным клиентам</a></li>
                            <li><a href="/viezd-predstavitelya">Выезд нашего представителя</a></li>
                            <li><a href="/vakansii">Вакансии</a></li>
                            <li><a href="/contact">Контакты</a></li>
                            <li><a href="/faq">Вопрос-ответ</a></li>
                        </ul>
                    </li>
                    <li class="menu_Info display_none_small2"><a href="https://aveldent.ru/dostavka" >Доставка</a></li>
                    <li class="menu_Info display_none_small2"><a href="https://aveldent.ru/oplata" >Оплата</a></li>
                    <li class="menu_Info display_none_small2"><a href="/vozvrat">Возврат</a></li>
                    <li class="menu_Info display_none_small2"><a href="https://aveldent.ru/samovivoz" >Самовывоз</a></li>
                    <li class="menu_Info display_none_small"><a href="https://aveldent.ru/predzakaz" >Предзаказ</a></li>
                    <li class="menu_Info display_none_small"><a href="/seminar" >Семинары</a></li>
                    <li class="menu_Info display_none_small"><a style="color:#e43535;" href="/promo">Акции</a></li>
                </ul>
                <div class="top-block-header-right">
                    <a href="#"><img style="width: 25px;height: 25px;" src="/image/new_header/account2.0.svg">Войти</a>
                    Корзина
                </div>
            </div>
            <a class="logo_avel_small"  href="#" title="Авельдент - Стоматологические материалы "><img src="/image/new_header/aveldent_logo.svg"></a>
            <div  class="centre-block-header">
                <div class="centre-block-left">
                    <div class="centre-block-search">
                        <div onclick="openCategory()" class="centre-block-search-category  desktop_centre-block"><img style="width: 20px;height: 17.5px;" src="/image/new_header/menu.svg"><span class="centre-block-search-search">Каталог</span></div>
                        <div class="menu_Info menu-hover  mobile_centre-block">
                            <div class="centre-block-search-category"><img style="width: 20px;height: 17.5px;" src="/image/new_header/menu.svg"><span class="centre-block-search-search">Каталог</span></div>
                            <ul class="submenu">
                                <?php if ($categories) {   ?>
                                <?php foreach ($categories as $category) {   ?>
                                <li><a href="<?php echo $category['href']; ?>" data-name="<?php echo $category['description'][0]['name']; ?>"><?php echo $category['description'][0]['name']; ?> </a></li>
                                <?php  } ?>
                                <li><a  href="/promo">Акции</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                        <div class="search" id="search"><img class="button-search" src="/image/new_header/search.svg"><input type="text" name="search" placeholder="Введите название, код товара или артикул" value="" style="border: 1px solid #00b5d3;margin: 0;"/><img class="input__clear"  src="/image/new_header/cross.svg"></div>
                    </div>
                </div>
                <div class="centre-block-info display_none_small2">
                    <div class="menu_callback menu-hover display_none_small2">
                        <a href="tel:+74995020830" class=" phone_callback">+7 499 502-08-30 <img style="width: 20px" src="/image/new_header/caret.svg"></a>
                        <ul class="submenu">
                            <li><span>Пункт выдачи:</span></li>
                            <li><p><img style="width: 20px;" src="/image/new_header/marker.svg"> г. Москва, Шереметьевская улица, 47</p></li>
                            <li><a href="mailto:zakaz@aveldent.ru"><img style="width: 20px" src="/image/new_header/mail.svg"> zakaz@aveldent.ru</a></li>
                            <li><a href="https://aveldent.ru/samovivoz"><img style="width: 19px" src="/image/new_header/ok.svg"> Пн–Пт: 09:00 – 18:00</a></li>
                            <li><span>Склад:</span></li>
                            <li><p><img style="width: 20px" src="/image/new_header/marker.svg"> г. Долгопрудный, Московская область, улица Жуковского, 3</p></li>
                            <li><a href="https://aveldent.ru/samovivoz"><img style="width: 19px" src="/image/new_header/ok.svg"> Пн–Пт: 09:00 – 18:00</a></li>
                        </ul>
                    </div>
                    <div class="button_callback popup-open-orderCall">Заказать звонок</div>
                </div>
                <div  class="icon_header">
                    <a  class=" display_none_small" href="https://t.me/Aveldentru"><img style="height: 25px;width: 25px;cursor: pointer; padding: 0 5px 0 5px;" src="/image/new_header/telegram.svg"></a>
                    <a  class=" display_none_small" href="https://vk.com/aveldentru"><img  style="height: 25px;width: 25px;cursor: pointer; padding: 0 5px 0 5px;" src="/image/new_header/vk.svg"></a>
                </div>
            </div>

            <div class="wrapper_category_list">
                <div class="catalog_list">
                    <div class="catefory_list">
                        <div class="category_list_left">
                            <?php if ($categories) { ?>
                            <a class="category_lef_2" style="color:#e43535;" href="/promo">Акции</a>
                            <a class="category_lef_2" style="color:#e43535;" href="/exclusive">Эксклюзивные предложения</a>
                            <a class="category_lef" href="https://aveldent.ru/brands" data-name="Производители">Производители <img src="/image/new_header/caret2.svg"></a>
                            <?php foreach ($categories as $category) { ?>

                            <?php if (!empty($category['children']) && count($category['children']) != 0){ ?>
                            <a class="category_lef"  href="<?php echo $category['href']; ?>" data-name="<?php echo $category['description'][0]['name']; ?>"><?php echo $category['description'][0]['name']; ?>
                                <img src="/image/new_header/caret2.svg">
                                <?php  } else {?>
                                <a class="category_lef_2" style=""  href="<?php echo $category['href']; ?>" data-name="<?php echo $category['description'][0]['name']; ?>"><?php echo $category['description'][0]['name']; ?>

                                    <? } ?>
                                </a>
                                <?php  } ?>
                                <a class="category_lef_2 item_img_seminar" href="/seminar"><img src="/image/seminar2.jpg"></a>
                            <?php } ?>
                        </div>
                        <div class="category_list_right">
                            <div class="category_children" data-name_children="Производители">
                                <div class="unstyled">
                                    <?php if(!empty($manufacturer)){ foreach($manufacturer as $val_manufactur){
                                    ?>  <a href="<?php echo $val_manufactur['href']; ?>"><?php echo $val_manufactur['name']; ?></a> <?php
                                    } } ?>
                                    <a class="all_category_list_item" href="/brands" >Смотреть всех производителей</a>
                                </div>
                            </div>
                            <?php if ($categories) { ?>

                            <?php foreach ($categories as $category) { ?>
                            <?php if ( !empty($category['children'])  ) { ?>
                            <div class="category_children" data-name_children="<?php echo $category['name']; ?>">

                                <?php for ($i = 0; $i < count($category['children']);) { ?>
                                <div class="unstyled">
                                    <?php $j = $i + ceil(count($category['children']) / $category['column']); ?>
                                    <?php for (; $i < $j; $i++) { ?>
                                    <?php if (isset($category['children'][$i])) { ?>

                                    <?php if(!empty($category['children'][$i]['children_children'])){ ?> <div class="item_category<?php echo $category['children'][$i]['category_id']; ?>"><span class="wrapper_children_category caret_bottom" ><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a> <span class="caret_category_child" onclick="open_category_children('<?php echo $category['children'][$i]['category_id']; ?>');"><img src="/image/new_header/caret2.svg"></span></span><div class="block_children_category"></div></div><?php }else{
                                    ?>
                                    <span ><a href="<?php echo $category['children'][$i]['href']; ?>"><?php echo $category['children'][$i]['name']; ?></a></span>
                                    <?php
                                    } ?>
                                    <?php } ?>
                                    <?php } ?>
                                    <a class="all_category_list_item" href="<?php echo $category['href']; ?>" ><?php echo $text_all; ?> <?php echo $category['name']; ?></a>
                                </div>
                                <?php } ?>
                            </div>
                            <?php } } ?>
                            <?php } ?>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <main class="py-4">


        @yield('content')
    </main>
</div>
</body>
</html>
