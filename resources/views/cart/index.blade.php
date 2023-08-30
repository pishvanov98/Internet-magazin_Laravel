@extends('layouts.app')

@section('content')
<div class="container">
    <div class="wrapper_cart_block">
        <div class="left_block">
            <div class="cart_product_info">
            <div class="cart-itogo-1">
                Корзина<sup>{{$cart_info['count_all_prod']}}</sup>
            </div>
            <div class="wrapper_cart_all_click">
                <div class="item_all_click">
                    <input type="checkbox" checked=""  id="all_click_check_cart" class="check_cart "> <label for="all_click_check_cart">Выбрать все</label>
                </div>
                <div class="item_all_click2"><span onclick="delAllCart(0)" >Очистить корзину</span></div>
            </div>



            <table class="table">
                <tbody>

                @if($Products)
                    @foreach($Products as $Product)

                        <tr class="cartItem_{{$Product->product_id}}">
                            <th scope="row"><input @if($Product->active == 1) checked  @endif    onclick="activeProduct({{$Product->product_id}})" type="checkbox" class="check_cart "></th>
                            <td><a href="{{route('product.show',$Product->slug)}}"><img width="120" height="120" src="{{asset($Product->image)}}"></a></td>
                            <td>
                                <div class="flex_product-text">
                                    <a href="{{route('product.show',$Product->slug)}}">{{$Product->name}}</a>
                                    <small>Код товара:{{$Product->model}}</small>
                                </div>
                               </td>
                            <td class="flex_input_cart" style="min-width: 140px;zoom:1.3">
                                <span class="flex_input_cart_top">
                                    <img src="{{asset('img/noun-minus.svg')}}" onclick="delToCartCart('{{$Product->product_id}}');" alt="Минус" class="cart-min" style="cursor: pointer;" >
                                    <input type="text" name="quantity[{{$Product->product_id}}]" data-id="{{$Product->product_id}}" data-id="{{$Product->product_id}}" value="{{$Product->quantity_cart}}" size="1" class=" input-cart" style="border-radius: 0px;">
                                    <img src="{{asset('img/noun-plus.svg')}}" onclick="addToCartCart({{$Product->product_id}},1);" alt="Плюс" class="cart-plus" style="cursor: pointer;" >
                                </span>
                                <span class="flex_input_cart_bottom">
                                <a class="removetovar hidden" href="#" onclick="delAllCart({{$Product->product_id}})" data-toggle="tooltip" title="Удалить">Удалить</a>
                                <a class="towishlist-cart hidden" onclick="">В избранное</a>
                                </span>
                            </td>
                            <td><strong class="product-pricetotal">{{number_format($Product->price, 0, '', ' ')}} ₽</strong></td>
                        </tr>

                    @endforeach


                @endif

                </tbody>
            </table>
        </div>
        </div>
        <div class="right_block">
            <h4>Ваш заказ</h4>
            <div class="info_block first"><p class="left">Товары - {{$cart_info['count_all_prod']}} шт.</p><p class="right">{{$cart_info['itogo']}} ₽</p></div>
            <div style="font-size: 18px;" class="info_block"><strong><p class="left">Итого</p></strong><strong><p class="right">{{$cart_info['itogo']}} ₽</p></strong></div>
            <a href="{{route('checkout')}}" class="btn btn_order " >Оформить заказ</a>
        </div>
    </div>
</div>

    @push('script')

        <script>


            $("#all_click_check_cart").on('click',function (){
                if ($(this).is(':checked')){
                    $('table input:checkbox').prop('checked', true);
                    var result= 1;
                } else {
                    $('table input:checkbox').prop('checked', false);
                    var result= 0;
                }
                $.ajax({
                    url:'{{route('ActiveAllProduct')}}',
                    method: 'get',
                    dataType: 'json',
                    data: {result: result},
                    success: function(data){
                        if(data){
                            updateCount(0);
                        }
                    }
                });
            });

            $('.flex_input_cart_top .input-cart').keyup(function(){
                var Count = $(this).val();
                var id=$(this).attr('data-id');
                $.ajax({
                   url:'{{route('UpdateCountProduct')}}',
                    method: 'get',
                    dataType: 'json',
                    data: {id: id,Count:Count},
                    success: function(data){
                       if(data){
                           updateCount(id);
                       }
                    }
                });
            });

            function delAllCart(id){

                $.ajax({
                    url:'{{route('delAllCart')}}',
                    method: 'get',
                    dataType: 'json',
                    data: {id: id},
                    success: function(data){
                        if(data){
                            updateCount(id);
                            if(id == 0){
                                $("table tbody").remove();
                            }
                        }
                    }
                });
            }

            function updateCount(id){
                $.ajax({
                   url:'{{route('CheckCountProduct')}}',
                    method: 'get',
                    dataType: 'json',
                    data: {id: id},
                    success: function(data){

                        if(data['count_prod'] == 0){
                            $('.cartItem_'+id).remove();
                        }

                       $('.cartItem_'+id).find('.flex_input_cart_top input').val(data['count_prod']);
                        $('.cart-itogo-1 sup').text(data['count_all_prod']);
                        $('.total_price_cart').text(data['itogo']);
                        $('.right_block .info_block.first .left').text("Товары - "+data['count_all_prod']+" шт.");
                        $('.right_block .info_block .right').text(data['itogo']+" ₽");
                        $('#cart-total').text(data['count_all_prod']+" Товаров - "+data['itogo']+" руб.");
                    }
                });

            }

            function activeProduct(id){

                $.ajax({
                    url:'{{route('ActiveProduct')}}',
                    method: 'get',
                    dataType: 'json',
                    data: {id: id},
                    success: function(data){
                        if(data){
                            updateCount(id);
                        }
                    }
                });
            }

            function addToCartCart(id,count){

                $.ajax({
                    url: '{{route('addCart')}}',
                    method: 'get',
                    dataType: 'json',
                    data: {id: id,count:count},
                    success: function(data){
                        updateCount(id);
                    }
                });

            }

            function delToCartCart(id){

                $.ajax({
                    url: '{{route('delCart')}}',
                    method: 'get',
                    dataType: 'json',
                    data: {id: id},
                    success: function(data){
                        $('#cart-total').text(data);
                        updateCount(id);
                    }
                });

            }

            $(document).on('mouseenter', 'tbody tr', function (e) {
                $(this).find(".towishlist-cart").removeClass('hidden');
                $(this).find(".removetovar").removeClass('hidden');
            }).on('mouseleave', 'tbody tr', function (e) {
                $(this).find(".towishlist-cart").addClass('hidden');
                $(this).find(".removetovar").addClass('hidden');
            });

        </script>

    @endpush

@endsection
