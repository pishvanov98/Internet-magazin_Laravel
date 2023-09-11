@extends('layouts.app')

@section('content')

    <div class="container">
        <h4>Избранное</h4>
        <div class="block_account">
            <div class="left_block">
                @include('components.accountMenu')
            </div>
            <div class="right_block">

                <div class="wishlist_category">
                    <div class="wrapper_goods">
                        @if($Products)

                            @foreach($Products as $product)

                                @include('components.product')

                            @endforeach

                        @endif
                    </div>
                    @if($Products)
                        <div class="pagination_wrapper">  {{$Products->links()}} </div>
                    @endif
                </div>

            </div>
        </div>
    </div>

    @push('script')

        <script>

            $('.wishlist').on('click',function (){
                $('.card_item'+$(this).attr('data-id')).remove();
            });

        </script>

    @endpush

@endsection
