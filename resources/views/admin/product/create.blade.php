@extends('layouts.admin')

@section('content')
    <section class="content">
        <div class="container-fluid">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('admin.product.store')}}" enctype="multipart/form-data" method="post">
                @csrf

                <div class=" mb-3">
                    <div class="col-sm-3 mb-3">
                        <span class="mb-1 d-block">Наименование</span>
                        <input type="text" name="name"  class="form-control" placeholder="Наименование">
                    </div>
                    <div class="col-sm-3 mb-3">
                        <span class="mb-1 d-block">Описание</span>
                        <textarea class="form-control" name="description" placeholder="Описание"></textarea>
                    </div>
                    <div class="col-sm-3 mb-3">
{{--                        customer_group_id=0--}}
                        <span class="mb-1 d-block">Цена Default</span>
                        <input type="number" name="price1"  class="form-control" placeholder="Цена Default">
                    </div>
                    <div class="col-sm-3 mb-3">
                        {{--                        customer_group_id=2--}}
                        <span class="mb-1 d-block">Цена Vip</span>
                        <input type="number" name="price2"  class="form-control" placeholder="Цена Vip">
                    </div>
                    <div class="col-sm-3 mb-3">
                        {{--                        customer_group_id=3--}}
                        <span class="mb-1 d-block">Цена Закупочная</span>
                        <input type="number" name="price3"  class="form-control" placeholder="Цена Закупочная">
                    </div>
                    <div class="col-sm-3 mb-3">
                        <span class="mb-1 d-block">Код товара</span>
                        <input type="text" name="model"  class="form-control" placeholder="Код товара">
                    </div>
                    <div class="col-sm-3 mb-3">
                        <span class="mb-1 d-block">Артикул</span>
                        <input type="text" name="sku"  class="form-control" placeholder="Артикул">
                    </div>
                    <div class="col-sm-3 mb-3">
                        <span class="mb-1 d-block">Спец предложение</span>
                    <select class="form-control" name="mpn" aria-label="Спец предложение">
                        <option value="0" selected>Не активно</option>
                        <option value="1" selected>Активно</option>

                    </select>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <span class="mb-1 d-block">Количество</span>
                        <input type="number" name="quantity"  class="form-control" placeholder="Количество">
                    </div>
                    <div class="col-sm-3 mb-3">
                        <span class="mb-1 d-block">Теги через запятую</span>
                        <input type="text" name="tag"  class="form-control" placeholder="Теги через запятую">
                    </div>
                    <div class="col-sm-3 mb-3">
                        <span class="mb-1 d-block">Производитель</span>
                    <select class="form-control" name="manufacturer" aria-label="Производитель">
                        <option value="0" selected></option>
                        @if(!empty($manufacturers))
                            @foreach($manufacturers as $manufacture)

                                <option value="{{$manufacture->manufacturer_id}}">{{$manufacture->name}}</option>

                            @endforeach

                        @endif
                    </select>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <span class="mb-1 d-block">Категория</span>
                    <select class="form-control" name="category" aria-label="Категория">
                        <option value="0" selected></option>
                        @if(!empty($categories))
                            @foreach($categories as $category)

                                <option value="{{$category->category_id}}">{{$category->name}}</option>

                            @endforeach

                        @endif
                    </select>
                    </div>
                    <div class="col-sm-3 mb-3">
                        <input type="file" name="image" class="form-control-file" id="image">
                    </div>
                    <div class="attribute_prod">
                        <span class="mb-1 d-block">Атрибуты</span>

                        <div class="attr_block1 col-sm-3 mb-3 d-flex">
                        <select class="form-control" name="attribute[]" aria-label="Атрибуты">
                            <option value="0" selected></option>
                            @if(!empty($attributes))
                                @foreach($attributes as $attribute)

                                    <option value="{{$attribute->attribute_id}}">{{$attribute->name}}</option>

                                @endforeach

                            @endif
                        </select>
                        <input type="text" name="input_attribute[]" class="form-control"  placeholder="Значение" >
                        </div>

                    </div>
                    <span class="btn btn-primary m-lg-2 attr_add">Добавить атрибут</span>
                    <button type="submit" class="btn btn-primary m-lg-2">Добавить продукт</button>
                </div>
            </form>
        </div>
    </section>
@push('script')
    <script>
        $( document ).ready(function(){
            var count=2;
            $( ".attr_add" ).click(function(){
                $('.attr_block1').clone().appendTo(".attribute_prod");
                var lastBlock=$('.attribute_prod').children().last();
                // var findElement=lastBlock.find('input').attr('name', 'input_attribute'+count);
                // var findElement2=lastBlock.find('select').attr('name', 'attribute'+count);
                var findElement3=lastBlock.attr('class','col-sm-3 mb-3 d-flex attr_block'+count);
                count++;
            });
        });
    </script>
@endpush

@endsection
