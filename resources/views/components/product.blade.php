<div class="card card_item{{$product->product_id}}" style="width: 290px;min-height: 400px;">

    <a class="wrapper_img_card" href="{{route('product.show',$product->slug)}}">@if($product->image) <img src="{{asset($product->image)}}"  class="card-img-top" alt=""> @else <img src="{{asset('img/zag_258x258.svg')}}"  class="card-img-top" alt=""> @endif </a>

    <div class="card-body">
        <a class="card-title" href="{{route('product.show',$product->slug)}}"><h6>{{$product->name}}</h6></a>
        <div style="flex-direction: column" class="d-flex">
            <div>

                @if(!empty($product->old_price))
                    <p class="card-text">Стоимость <span style="color: red; font-size: 16px">{{number_format($product->price, 0, '', ' ')}} ₽</span> <s style="opacity: 0.5;">{{number_format($product->old_price, 0, '', ' ')}} ₽ </s></p>
                @else
                    <p class="card-text">Стоимость {{number_format($product->price, 0, '', ' ')}} ₽ </p>
                @endif
                <div class="d-flex justify-content-between align-items-center">
                <span onclick="addToCart({{$product->product_id}},1)" class="btn btn-primary ">Купить</span>
                    <span data-id="{{$product->product_id}}" onclick="addToWishlist({{$product->product_id}})" class="wishlist">
                        @if(!empty($product->wishlist))
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart-fill" viewBox="0 0 16 16">
                              <path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/>
                            </svg>
                        @else
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-heart" viewBox="0 0 16 16">
                                <path d="m8 2.748-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/>
                            </svg>
                        @endif
                    </span>
                </div>
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
