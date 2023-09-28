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
{{--                <a class="category_lef_2 item_img_seminar" href="/seminar"><img src="{{asset('/img/seminar2.jpg')}}"></a>--}}
            <?php } ?>
        </div>
        <div class="category_list_right">
            <div class="category_children" data-name_children="Производители">
                <div class="unstyled">
                    @if(!empty($brands))
                        @foreach($brands as $val_manufactur)
                            <a href="{{route('manufacturer.show',$val_manufactur['slug'])}}">{{$val_manufactur['name']}}</a>
                        @endforeach
                        <a class="all_category_list_item" href="{{route('manufacturer')}}" >Смотреть всех производителей</a>
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


    <script>
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

