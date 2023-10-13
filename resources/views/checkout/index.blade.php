@extends('layouts.app')
@section('content')
    <div class="container">
        <h4 class="mb-3">Способ оплаты и доставки</h4>
<div class="flex_cart_item_input__all">
<div class="checkout" style="min-height: 775px;">



    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
        <li class="nav-item" role="presentation"><button aria-controls="pills-home" aria-selected="true" class="nav-link active" data-bs-target="#pills-1" data-bs-toggle="pill" id="pills-home-tab" role="tab" type="button">Физическое лицо</button></li>
        <li class="nav-item" role="presentation"><button aria-controls="pills-profile" aria-selected="false" class="nav-link" data-bs-target="#pills-2" data-bs-toggle="pill" id="pills-profile-tab" role="tab" type="button" tabindex="-1">Юридическое лицо</button></li>
    </ul>





    <div class="tab-content" id="pills-tabContent">
        <div aria-labelledby="pills-home-tab" class="tab-pane fade show active" id="pills-1" role="tabpanel">
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
                <div class="btn-group w-100" id="btn-group" role="group" aria-label="Basic outlined example">
                    <button type="button" value="1" class="btn btn-outline-primary">Оплата при получении</button>
                    <button type="button" value="2" class="btn btn-outline-primary">Оплата онлайн</button>
                    <button type="button" value="3" class="btn btn-outline-primary">Оплата по счёту</button>
                </div>
            </div>
            <div class="cart_item_input">
                <textarea placeholder="Комментарий" id="comment">@if(!empty($address['comment'])) {{$address['comment']}} @endif</textarea>
            </div>
            <div class="cart_item_input mt-2">
                <div class="itogo">
                    <div class="info_block first"><p class="left">Товары - {{$cart_info['count_all_prod']}} шт.</p><p class="right">{{$cart_info['itogo']}} ₽</p></div>
                    <div style="font-size: 18px;" class="info_block"><strong><p class="left">Итого</p></strong><strong><p class="right">{{$cart_info['itogo']}} ₽</p></strong></div>
                    <span href="#" class="btn btn_order save_order " >Отправить заказ</span>
                </div>
            </div>
        </div>
        <div aria-labelledby="pills-home-tab" class="tab-pane fade" id="pills-2" role="tabpanel">
            <div class="cart_item_input">
                <input @if(!empty($addressUr['inn'])) value="{{$addressUr['inn']}}" @endif placeholder="Инн" type="text" maxlength="12" onkeyup="this.value = this.value.replace (/[^0-9^\d]/, '')" id="inn_ur">
            </div>
            <div class="cart_item_input">
                <input @if(!empty($addressUr['company'])) value="{{$addressUr['company']}}" @endif placeholder="Название компании" type="text" id="company_ur">
            </div>
            <div class="cart_item_input">
                <input @if(!empty($addressUr['name'])) value="{{$addressUr['name']}}" @endif  placeholder="ФИО ответственного лица" type="text" id="name_ur">
            </div>
            <div class="cart_item_input">
                <input @if(!empty($addressUr['Tel'])) value="{{$addressUr['Tel']}}" @endif  placeholder="Контактный телефон" type="text" maxlength="12" onkeyup="this.value = this.value.replace (/[^0-9+^\d]/, '')" id="Tel_ur">
            </div>
            <div class="cart_item_input">
                <input @if(!empty($addressUr['mail'])) value="{{$addressUr['mail']}}" @endif  placeholder="Электронная почта" type="email" id="mail_ur">
            </div>
            <div class="cart_item_input">
                <input @if(!empty($addressUr['address'])) value="{{$addressUr['address']}}" @endif placeholder="Адрес" type="text" id="address_ur">
            </div>
            <div class="cart_item_input">
                <div class="btn-group w-100" id="btn-group_ur" role="group" aria-label="Basic outlined example">
                    <button type="button" value="1" class="btn btn-outline-primary">Оплата при получении</button>
                    <button type="button" value="2" class="btn btn-outline-primary">Оплата онлайн</button>
                    <button type="button" value="3" class="btn btn-outline-primary">Оплата по счёту</button>
                </div>
            </div>
            <div class="cart_item_input">
                <textarea placeholder="Комментарий" id="comment_ur">@if(!empty($addressUr['comment'])) {{$addressUr['comment']}} @endif</textarea>
            </div>
            <div class="cart_item_input mt-2">
                <div class="itogo">
                    <div class="info_block first"><p class="left">Товары - {{$cart_info['count_all_prod']}} шт.</p><p class="right">{{$cart_info['itogo']}} ₽</p></div>
                    <div style="font-size: 18px;" class="info_block"><strong><p class="left">Итого</p></strong><strong><p class="right">{{$cart_info['itogo']}} ₽</p></strong></div>
                    <span href="#" class="btn btn_order save_order_ur " >Отправить заказ</span>
                </div>
            </div>
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

            $('.checkout #pills-1 input').keyup(function(){

                if ($(this).hasClass('error')){
                    $(this).removeClass('error');
                }

                //закидываем данные в сессию в адреса

                var name=$("#name").val();
                var Tel=$("#Tel").val();
                var mail=$("#mail").val();
                var address=$("#address").val();
                var comment=$("#comment").val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                   url:'{{route('save.address')}}',
                    method:'post',
                    dataType:'json',
                    data:{name:name,Tel:Tel,mail:mail,address:address,comment:comment}
                });
            });

            $('.checkout #pills-2 input').keyup(function(){

                if ($(this).hasClass('error')){
                    $(this).removeClass('error');
                }

                //закидываем данные в сессию в адреса
                var inn=$("#inn_ur").val();
                var company=$("#company_ur").val();
                var name=$("#name_ur").val();
                var Tel=$("#Tel_ur").val();
                var mail=$("#mail_ur").val();
                var address=$("#address_ur").val();
                var comment=$("#comment_ur").val();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url:'{{route('save.addressUr')}}',
                    method:'post',
                    dataType:'json',
                    data:{name:name,Tel:Tel,mail:mail,address:address,comment:comment,inn:inn,company:company}
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
                var shipping=$(".cart_item_input #btn-group button.active").val();
                var price='{{$cart_info['itogo']}}';
                var comment=$("#comment").val();

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
                    $(".cart_item_input #btn-group button").addClass('error');
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
                        data: {name: name,Tel:Tel,mail:mail,address:address,shipping:shipping,price:price,comment:comment},
                        success: function(data){
                            if(data){
                                window.location.href = data;
                            }
                        }
                    });
                }
            });
        $(".save_order_ur").on('click',function (){
            var inn=$("#inn_ur").val();
            var company=$("#company_ur").val();
            var name=$("#name_ur").val();
            var Tel=$("#Tel_ur").val();
            var mail=$("#mail_ur").val();
            var address=$("#address_ur").val();
            var shipping=$(".cart_item_input #btn-group_ur button.active").val();
            var price='{{$cart_info['itogo']}}';
            var comment=$("#comment_ur").val();

            if ($(".checkout input").hasClass('error')){
                $(".checkout input").removeClass('error');
            }

            $(".cart_item_input .btn-group button").removeClass('error');

            if(!inn ){
                $("#inn_ur").addClass('error');
            }
            if(!company ){
                $("#company_ur").addClass('error');
            }
            if(!name ){
                $("#name_ur").addClass('error');
            }
            if(!Tel ){
                $("#Tel_ur").addClass('error');
            }
            if(!mail ){
                $("#mail_ur").addClass('error');
            }
            if(!address ){
                $("#address_ur").addClass('error');
            }
            if(!shipping ){
                $(".cart_item_input #btn-group_ur button").addClass('error');
            }
            if(name && Tel && mail && address && shipping){
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url:'{{route('SaveOrderUr')}}',
                    method: 'post',
                    dataType: 'html',
                    data: {name: name,Tel:Tel,mail:mail,address:address,shipping:shipping,price:price,comment:comment,inn:inn,company:company},
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
