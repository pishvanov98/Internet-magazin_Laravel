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
                @if(!empty($CategoryTree['Children']))
                    <ul class="CategoryTreeChildren">
                        @foreach($CategoryTree['Children'] as $item)
                                <li> <a href="{{route('category.show',$item->slug)}}">{{$item->name}}</a></li>
                        @endforeach
                    </ul>
                @endif

            @if($AttrCategory && count($AttrCategory) > 1)
                <ul class="CategoryAttr">
                    <li>  <h5 class="mt-3 mb-2">Фильтры</h5></li>


                    <li>
                        <button class="btn btn-primary show_filter_btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExampleFilter" aria-expanded="false" aria-controls="collapseExampleFilter">
                            Показать фильтры
                        </button>
                    </li>
                    <ul class="collapse @notmobile show @endnotmobile" id="collapseExampleFilter">
                        @foreach($AttrCategory as $key_item=>$item)
                        <li>  <strong>{{$item['attribute_name']}}</strong></li>
                        <ul class="CategoryAttrName">
                            @foreach($item['attribute_text'] as $key=> $item_attr_text)
                                <li><input class="form-check-input attr_prod attr_{{$key_item}}_{{$key}}" data-id_item="attr_{{$key_item}}_{{$key}}" data-id="{{$item['attribute_id']}}" data-name="{{$item_attr_text}}" data-category="{{$main_category_id}}" type="checkbox" value=""> {{$item_attr_text}}</li>
                            @endforeach
                        </ul>
                        @endforeach

                    </ul>
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
            <div class="d-flex justify-content-center spinner hide">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            <div class="products_category">
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
    </div>




    @push('script')

        <script type="module">

            $(document).ready(function () {
                var hash = window.location.hash;
                if(hash){

                    var string_art = '';
                    let arr = hash.split('#');
                    var category='';
                    $.each(arr,function(index,item){
                        if(item){
                            $('.'+item).prop('checked', true);
                             var id= $('.'+item).attr('data-id');
                             var value= $('.'+item).attr('data-name');
                              category= $('.'+item).attr('data-category');
                             string_art=string_art+id+'#'+value+'|';
                        }
                    });
                    if(string_art){
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });
                        $.ajax({
                            url: '{{route('query.filter.product')}}',
                            method: 'post',
                            dataType: 'html',
                            data: {string_art:string_art,category:category},
                            success: function(data){
                                $('.products_category').empty();
                                $( ".products_category" ).append(data);
                            }
                        });
                    }

                }
                $('.spinner').css('width',$('.products_category').css('width'));
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

            $('.CategoryAttrName li input').on('click',function (){
            var string_art = '';
            var id= $(this).attr('data-id');
            var value= $(this).attr('data-name');
            var category= $(this).attr('data-category');
            var id_item=$(this).attr('data-id_item');
            var checked=0;
                if ($(this).is(':checked')){
                     checked=1;
                } else {
                     checked=0;
                }
            // console.log(id);
            // console.log(value);
            // console.log(category);

                var hash = window.location.hash;
                var newHash='';
                if(hash){
                    let arr = hash.split('#');
                    $.each(arr,function(index,value){
                      if(value){
                            var statusChecked=0;
                          if ($('.'+value).is(':checked')){
                              statusChecked=1;
                          } else {
                              statusChecked=0;
                          }
                            var status=0;
                            if (value == id_item){
                                status=1;
                            }
                        if(status == 0 && statusChecked == 1){
                            if(newHash){
                                newHash=newHash+'#'+value;
                            }else{
                                newHash=newHash+value;
                            }

                            var id_value= $('.'+value).attr('data-id');
                            var value_value= $('.'+value).attr('data-name');
                            string_art=string_art+id_value+'#'+value_value+'|';

                        }

                      }
                    });
                    if(checked == 1) {
                        newHash = newHash + '#' + id_item;
                        string_art=string_art+id+'#'+value+'|';
                    }
                    console.log(newHash);
                    window.location.hash =  newHash;
                }else{
                    if(checked == 1){
                        window.location.hash =  id_item;
                        string_art=string_art+id+'#'+value+'|';
                    }
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                if(string_art) {
                    $.ajax({
                        url: '{{route('query.filter.product')}}',
                        method: 'post',
                        dataType: 'html',
                        beforeSend:function (){
                            $('.products_category').empty();
                            $('.spinner').removeClass('hide');
                        },
                        data: {string_art: string_art, category: category},
                        success: function (data) {
                            $('.spinner').addClass('hide');
                            $(".products_category").append(data);
                        }
                    });
                }else{
                    $.ajax({
                        url: '{{route('query.filter.product')}}',
                        method: 'post',
                        dataType: 'html',
                        data: {category: category},
                        beforeSend:function (){
                            $('.products_category').empty();
                            $('.spinner').removeClass('hide');
                        },
                        success: function (data) {
                            $('.spinner').addClass('hide');
                            $(".products_category").append(data);
                        }
                    });
                }
            });


        </script>
    @endpush

@endsection
