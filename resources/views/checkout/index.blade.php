@extends('layouts.app')
@section('content')
    <div class="container">
        <h4 class="mb-3">Способ оплаты и доставки</h4>
<div class="flex_cart_item_input__all">
<div class="checkout">
@if( !empty($Profile))
            <select class="form-select profile" >
                <option selected>Выбрать профиль</option>
                @foreach($Profile as $key=> $item)
                    <option value="{{$item->id}}">{{$item->name}}: {{$item->address}}</option>
                @endforeach
            </select>
    @endif
            <div class="cart_item_input">
                <input @if(!empty($address['name'])) value="{{$address['name']}}" @endif placeholder="ФИО" type="text" id="name">
            </div>
            <div class="cart_item_input">
                <input @if(!empty($address['Tel'])) value="{{$address['Tel']}}" @endif placeholder="Контактный телефон" type="text" maxlength="12" onkeyup="this.value = this.value.replace (/[^0-9+^\d]/, '')" id="Tel">
            </div>
            <div class="cart_item_input">
                <input @if(!empty($address['mail'])) value="{{$address['mail']}}" @endif placeholder="Электронная почта" type="email" id="mail">
            </div>
            <div class="cart_item_input">
                <input @if(!empty($address['address'])) value="{{$address['address']}}" @endif placeholder="Адрес" type="text" id="address">
            </div>
            <div class="cart_item_input">
                <div class="btn-group w-100" role="group" aria-label="Basic outlined example">
                    <button type="button" value="1" class="btn btn-outline-primary">Оплата при получении</button>
                    <button type="button" value="2" class="btn btn-outline-primary">Оплата онлайн</button>
                    <button type="button" value="3" class="btn btn-outline-primary">Оплата по счёту</button>
                </div>
            </div>
    <div class="cart_item_input mt-2">
        <div class="itogo">
            <div class="info_block first"><p class="left">Товары - {{$cart_info['count_all_prod']}} шт.</p><p class="right">{{$cart_info['itogo']}} ₽</p></div>
            <div style="font-size: 18px;" class="info_block"><strong><p class="left">Итого</p></strong><strong><p class="right">{{$cart_info['itogo']}} ₽</p></strong></div>
            <span href="#" class="btn btn_order save_order " >Отправить заказ</span>
        </div>
    </div>
</div>
</div>


    </div>

    @push('script')
        <script>



$('.profile').change(function(){
    var value = $(this).val();
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url:'{{route('select.address')}}',
        method:'post',
        dataType:'json',
        data:{id:value},
        success:function (data){

            if(data){
                $("#name").val(data['name']);
                $("#Tel").val(data['Tel']);
                $("#mail").val(data['mail']);
                $("#address").val(data['address']);
            }
        }
    });
});

            $('.checkout input').keyup(function(){

                if ($(this).hasClass('error')){
                    $(this).removeClass('error');
                }

                //закидываем данные в сессию в адреса

                var name=$("#name").val();
                var Tel=$("#Tel").val();
                var mail=$("#mail").val();
                var address=$("#address").val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                   url:'{{route('save.address')}}',
                    method:'post',
                    dataType:'json',
                    data:{name:name,Tel:Tel,mail:mail,address:address}
                });



            });

            $('.cart_item_input .btn-group button').on('click',function (){
                if ($(".cart_item_input .btn-group button").hasClass('error')){
                    $(".cart_item_input .btn-group button").removeClass('error');
                }
            });

            $(".save_order").on('click',function (){
                var name=$("#name").val();
                var Tel=$("#Tel").val();
                var mail=$("#mail").val();
                var address=$("#address").val();
                var shipping=$(".cart_item_input .btn-group button.active").val();
                var price='{{$cart_info['itogo']}}';


                if ($(".checkout input").hasClass('error')){
                    $(".checkout input").removeClass('error');
                }

                $(".cart_item_input .btn-group button").removeClass('error');


                if(!name ){
                    $("#name").addClass('error');
                }
                if(!Tel ){
                    $("#Tel").addClass('error');
                }
                if(!mail ){
                    $("#mail").addClass('error');
                }
                if(!address ){
                    $("#address").addClass('error');
                }
                if(!shipping ){
                    $(".cart_item_input .btn-group button").addClass('error');
                }
                if(name && Tel && mail && address && shipping){
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $.ajax({
                        url:'{{route('SaveOrder')}}',
                        method: 'post',
                        dataType: 'html',
                        data: {name: name,Tel:Tel,mail:mail,address:address,shipping:shipping,price:price},
                        success: function(data){
                            if(data){
                                window.location.href = data;
                            }
                        }
                    });
                }
            });

            $(".cart_item_input .btn-group button").on('click',function (){

                if($(".cart_item_input .btn-group button").hasClass('active')){
                    $(".cart_item_input .btn-group button").removeClass('active');
                }
                $(this).addClass('active');
            });

        </script>
    @endpush

@endsection
