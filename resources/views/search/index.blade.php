@extends('layouts.app')
@section('content')
    <div class="container block_category">
        <div>

            @if(!empty($category_mass))
                <ul class="block_category_search">
                    <li style="font-weight: 600;"><a href="{{route('search',"search=".$search)}}">Категории</a></li>
                    <ul>
                        @foreach($category_mass as $item_category)
                                <li> <a @if(!empty($category) && $category == $item_category[1]) style="font-weight: 600;"  @endif href="{{route('search',"search=".$search."&category=".$item_category[0])}}" class="category_search_all ">{{$item_category[1]}}</a></li>
                        @endforeach
                    </ul>
                </ul>
            @endif


                @if($AttrCategory && count($AttrCategory) > 1)
                    <ul class="CategoryAttr">
                        <li>  <h5 class="mt-3 mb-2">Фильтры</h5></li>
                        @foreach($AttrCategory as $key_item=>$item)
                            <li>  <strong>{{$item['attribute_name']}}</strong></li>
                            <ul class="CategoryAttrName">
                                @foreach($item['attribute_text'] as $key=> $item_attr_text)
                                    <li><input class="form-check-input attr_prod attr_{{$key_item}}_{{$key}}" data-id_item="attr_{{$key_item}}_{{$key}}" data-id="{{$item['attribute_id']}}" data-name="{{$item_attr_text}}" type="checkbox" value=""> {{$item_attr_text}}</li>
                                @endforeach
                            </ul>


                        @endforeach
                    </ul>

                @endif
        </div>
        <div>
           @if(!empty($search)) <h4>Поиск: {{$search}} @if(!empty($category))  : {{$category}}  @endif </h4> @endif
            <div class="d-flex justify-content-center spinner hide">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
               @if(!empty($Products->all()))
            <div class="products_category">
                <div class="wrapper_goods">

                        @foreach($Products as $product)

                            @include('components.product')

                        @endforeach

                </div>
                <div class="pagination_wrapper">  {{$Products->links()}} </div>
            </div>
               @else
                  Пустой результат поиска
               @endif
        </div>
    </div>

    @push('script')
    <script>


        $(document).ready(function () {
            var hash = window.location.hash;
            if(hash){

                var string_art = '';
                var search="{{$search}}";
                var category="";
                @if(!empty($category_id))
                    category= "{{$category_id}}";
                @endif
                let arr = hash.split('#');
                $.each(arr,function(index,item){
                    if(item){
                        $('.'+item).prop('checked', true);
                        var id= $('.'+item).attr('data-id');
                        var value= $('.'+item).attr('data-name');
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
                        url: '{{route('query.filter.product.search')}}',
                        method: 'post',
                        dataType: 'html',
                        data: {string_art:string_art,search:search,category:category},
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
            var id_item=$(this).attr('data-id_item');
            var search="{{$search}}";
            var category="";
            @if(!empty($category_id))
                 category= "{{$category_id}}";
            @endif
            var checked=0;
            if ($(this).is(':checked')){
                checked=1;
            } else {
                checked=0;
            }
            // console.log(id);
            // console.log(value);

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
                    url: '{{route('query.filter.product.search')}}',
                    method: 'post',
                    dataType: 'html',
                    beforeSend:function (){
                        $('.products_category').empty();
                        $('.spinner').removeClass('hide');
                    },
                    data: {string_art: string_art,search:search,category:category},
                    success: function (data) {
                        $('.spinner').addClass('hide');
                        $(".products_category").append(data);
                    }
                });
            }else{
                $.ajax({
                    url: '{{route('query.filter.product.search')}}',
                    method: 'post',
                    dataType: 'html',
                    data: {search:search,category:category},
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
