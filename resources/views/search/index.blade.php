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

Тут фильтр будет
{{--            @if($AttrCategory && count($AttrCategory) > 1)--}}
{{--                <ul class="CategoryAttr">--}}
{{--                    <li>  <h5 class="mt-3 mb-2">Фильтры</h5></li>--}}
{{--                    @foreach($AttrCategory as $key_item=>$item)--}}
{{--                        <li>  <strong>{{$item['attribute_name']}}</strong></li>--}}
{{--                        <ul class="CategoryAttrName">--}}
{{--                            @foreach($item['attribute_text'] as $key=> $item_attr_text)--}}
{{--                                <li><input class="form-check-input attr_prod attr_{{$key_item}}_{{$key}}" data-id_item="attr_{{$key_item}}_{{$key}}" data-id="{{$item['attribute_id']}}" data-name="{{$item_attr_text}}" data-category="{{$main_category_id}}" type="checkbox" value=""> {{$item_attr_text}}</li>--}}
{{--                            @endforeach--}}
{{--                        </ul>--}}


{{--                    @endforeach--}}
{{--                </ul>--}}

{{--            @endif--}}
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

@endsection
