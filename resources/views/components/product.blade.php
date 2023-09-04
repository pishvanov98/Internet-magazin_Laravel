<div class="card" style="width: 290px;min-height: 400px;">

    <a class="wrapper_img_card" href="{{route('product.show',$product->slug)}}">@if($product->image) <img src="{{asset($product->image)}}"  class="card-img-top" alt=""> @else <img src="{{asset('img/zag_258x258.svg')}}"  class="card-img-top" alt=""> @endif </a>

    <div class="card-body">
        <a class="card-title" href="{{route('product.show',$product->slug)}}"><h6>{{$product->name}}</h6></a>
        <div style="flex-direction: column" class="d-flex">
            <div>
                <p class="card-text">Стоимость {{number_format($product->price, 0, '', ' ')}} ₽</p>
                <span onclick="addToCart({{$product->product_id}},1)" class="btn btn-primary ">Купить</span>
            </div>
            <div class="d-flex justify-content-between pt-2">
                <p class="mb-0">Код: {{$product->model}}</p>
                @if($product->quantity < 1)
                    <p class="mb-0"><span class="marker red"></span>Нет в наличии</p>
                @else
                    <p class="mb-0"><span class="marker yellow"></span>В наличии</p>
                @endif
            </div>
            </div>
    </div>
</div>
