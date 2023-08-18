<div class="card" style="width: 290px;min-height: 400px;">

    <a class="wrapper_img_card" href="{{route('product.show',$product->slug)}}">@if($product->image) <img src="{{asset($product->image)}}"  class="card-img-top" alt=""> @else <img src="{{asset('img/zag_258x258.svg')}}"  class="card-img-top" alt=""> @endif </a>

    <div class="card-body">
        <a class="card-title" href="{{route('product.show',$product->slug)}}"><h6>{{$product->name}}</h6></a>
        <div>
        <p class="card-text">Стоимость {{number_format($product->price, 0, '', ' ')}} ₽</p>
        <a href="#" onclick="addToCart({{$product->product_id}})" class="btn btn-primary ">Купить</a>
        </div>
    </div>
</div>
