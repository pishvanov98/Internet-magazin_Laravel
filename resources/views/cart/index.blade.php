@extends('layouts.app')

@section('content')
<div class="container">
    <div class="wrapper_cart_block">
        <div class="left_block">
            <div class="cart_product_info">
            <div class="cart-itogo-1">
                Корзина<sup>3</sup>
            </div>
            <div class="wrapper_cart_all_click">
                <div class="item_all_click">
                    <input type="checkbox" checked=""  id="all_click_check_cart" class="check_cart "> <label for="all_click_check_cart">Выбрать все</label>
                </div>
                <div class="item_all_click2"><a  href="#">Очистить корзину</a></div>
            </div>



            <table class="table">
                <tbody>

                @if($Products)
                    @foreach($Products as $Product)

                        <tr>
                            <th scope="row"><input  checked="" type="checkbox" class="check_cart "></th>
                            <td><a href="{{route('product.show',$Product->slug)}}"><img width="120" height="120" src="{{asset($Product->image)}}"></a></td>
                            <td>
                                <div class="flex_product-text">
                                    <a href="{{route('product.show',$Product->slug)}}">{{$Product->name}}</a>
                                    <small>Код товара:{{$Product->model}}</small>
                                </div>
                               </td>
                            <td class="flex_input_cart" style="min-width: 140px;zoom:1.3">
                                <span class="flex_input_cart_top">
                                    <img src="{{asset('img/noun-minus.svg')}}" alt="Минус" class="cart-min" style="cursor: pointer;" >
                                    <input type="text" name="quantity[{{$Product->product_id}}]" data-id="{{$Product->product_id}}" value="{{$Product->quantity_cart}}" size="1" class="input-mini input-cart" style="border-radius: 0px;">
                                    <img src="{{asset('img/noun-plus.svg')}}" alt="Плюс" class="cart-plus" style="cursor: pointer;" >
                                </span>
                                <span class="flex_input_cart_bottom">
                                <a class="removetovar hidden" href="#" data-toggle="tooltip" title="Удалить">Удалить</a>
                                <a class="towishlist-cart hidden" onclick="">В избранное</a>
                                </span>
                            </td>
                            <td><strong class="product-pricetotal">{{number_format($Product->product_id, 0, '', ' ')}} ₽</strong></td>
                        </tr>

                    @endforeach


                @endif

                </tbody>
            </table>
        </div>
        </div>
        <div class="right_block">
          информация
        </div>
    </div>
</div>

    @push('script')

        <script>

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
