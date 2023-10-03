@extends('layouts.app')

@section('content')
<div class="container">
    <div class="wrapper_cart_block">
        <div class="left_block">
            <div class="cart_product_info">
                @if(!empty($statusCoupon))
                    <div class="alert_block_success">
                    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                        <symbol id="check-circle-fill" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                        </symbol>
                    </svg>
                    <div class="alert alert-success d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Success:"><use xlink:href="#check-circle-fill"/></svg>
                        <div>
                            {{$statusCoupon}}
                        </div>
                    </div>
                    </div>
                @endif
                <div class="alert_block hide">
                    <svg xmlns="http://www.w3.org/2000/svg" style="display: none;">
                        <symbol id="exclamation-triangle-fill" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M8.982 1.566a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767L8.982 1.566zM8 5c.535 0 .954.462.9.995l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 5.995A.905.905 0 0 1 8 5zm.002 6a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                        </symbol>
                    </svg>
                    <div class="alert alert-danger d-flex align-items-center" role="alert">
                        <svg class="bi flex-shrink-0 me-2" width="24" height="24" role="img" aria-label="Danger:"><use xlink:href="#exclamation-triangle-fill"/></svg>
                        <div>
                            Не верный купон либо вы имеете максимальную скидку
                        </div>
                    </div>
                </div>

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
                                 <span data-id="{{$Product->product_id}}" onclick="addToWishlistCart({{$Product->product_id}})" class="wishlist towishlist-cart hidden">
                                     @if(!empty($Product->wishlist))
                                         <span>В избранном</span>
                                    @else
                                         <span>Добавить в избранное</span>
                                    @endif
                                 </span>
                                </span>
                            </td>
                            <td>
                                @if(!empty($Product->old_price))
                                    <strong style="color: red;" class="product-pricetotal">{{number_format($Product->price, 0, '', ' ')}} ₽ <s style="opacity: 0.5;font-size: 14px">{{number_format($Product->old_price, 0, '', ' ')}} ₽ </s></strong>
                                @else
                                    <strong class="product-pricetotal">{{number_format($Product->price, 0, '', ' ')}} ₽</strong>
                                @endif

                            </td>
                        </tr>

                    @endforeach


                @endif

                </tbody>
            </table>
        </div>
        </div>
        <div class="right_block">
            <h4>Ваш заказ</h4>
            <div class="info_block first"><p class="left">Товары - {{$cart_info['count_all_prod']}} шт.</p><p class="right">@if(!empty($cart_info['itogoAddDiscount'])) {{$cart_info['itogoAddDiscount']}} @else {{$cart_info['itogo']}} @endif ₽</p></div>
            @if(!empty($coupon))  <div class="info_block_coupon"><p class="left">Акция: {{$coupon['name']}}</p><p class="right">-{{number_format($coupon['discount'], 0, '', ' ')}} ₽</p></div> @endif
            <div style="font-size: 18px;" class="info_block"><strong><p class="left">Итого</p></strong><strong><p class="right">{{$cart_info['itogo']}} ₽</p></strong></div>
            <div class="input-append">
                <input type="text" name="coupon" value="" placeholder="Введите промокод:" id="input-coupon">
                <button type="button" id="button-coupon" class="btn btn_order" data-loading-text="Loading...">Применить</button>
            </div>
            <a href="{{route('checkout')}}" class="btn btn_order " >Оформить заказ</a>
        </div>
    </div>
</div>

    @push('script')

        <script>

            updateCount(0);
            setTimeout(function(){
                $('.alert_block_success').remove();
            }, 10000);
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

            $('#button-coupon').on('click',function (){
                var value = $('#input-coupon').val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url:'{{route('CheckCoupon')}}',
                    method: 'post',
                    dataType: 'json',
                    data: {value: value},
                    success: function(data){
                        if(data == 0){
                            if($('.right_block  .info_block_coupon').length){
                                $('.right_block  .info_block_coupon').addClass('hide');
                            }
                            $('.alert_block').removeClass('hide');
                            updateCount(0);
                            setTimeout(function(){
                                $('.alert_block').addClass('hide');
                            }, 5000);
                        }else{
                            location.reload();
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

                        if(data['discount']){
                            $('.right_block .info_block_coupon .right').text("-"+data['discount']+" ₽");
                        }
                        if(data['itogoAddDiscount']){
                            $('.right_block .info_block.first .right').text(data['itogoAddDiscount']+" ₽");
                        }

                        $('#cart-total').text(data['count_all_prod']+" Товаров - "+data['itogo']+" руб.");
                    }
                });

            }

            function activeProduct(id){
                var count = $('.check_cart:checkbox:checked').length;
                var countProd=$("table tbody tr").length;

                if(count == countProd){
                    $("#all_click_check_cart").prop("checked", true)
                }

                if ($('.cartItem_'+id+' .check_cart').is(':checked')){}else{
                    if ($('#all_click_check_cart').is(':checked')){
                        $("#all_click_check_cart").prop("checked", false)
                    }
                }

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


            function addToWishlistCart(id){

                $.ajax({
                    url: '{{route('addToWishlist')}}',
                    method: 'get',
                    dataType: 'json',
                    data: {id: id},
                    success: function(data){

                        if(data == 1){
                            $('.cartItem_'+id+ ' .wishlist').html('<span>В избранном</span>');
                        }else{
                            $('.cartItem_'+id+ ' .wishlist').html('<span>Добавить в избранное</span>');

                        }

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
