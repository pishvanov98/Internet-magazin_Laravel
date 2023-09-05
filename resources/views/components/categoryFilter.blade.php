
    <div class="wrapper_goods">
        @if($Products)

            @foreach($Products as $product)

                @include('components.product')

            @endforeach

        @endif
    </div>
    <div class="pagination_wrapper ajax">  {{$Products->links()}} </div>

    <script>

        @if(!empty($string_art))
        $(document).ready(function() {
            $('.ajax .pagination a').on('click', function (event) {
                event.preventDefault();
                var string_art = '{{$string_art}}';
                var category= '{{$category}}';
                var page= $(this).text();
                if(page == "›"){
                    page= $('.ajax .pagination .page-item.active .page-link').text();
                    page=Number(page)+1;
                    if(page > $('.ajax .pagination a').length){
                        page=$('.ajax .pagination a').length;
                    }
                }
               if(page == "‹"){
                   page= $('.ajax .pagination .page-item.active .page-link').text();
                   page=Number(page)-1;
                   if(page < 1){
                       page=1;
                   }
               }
                var xhr = $.ajax({
                    url: '{{route('query.filter.product')}}',
                    method: 'get',
                    dataType: 'html',
                    data: {string_art: string_art,category:category,page:page},
                    success: function(data){
                        $('.products_category').empty();
                        $( ".products_category" ).append(data);
                        //kill the request
                        xhr.abort()
                    }
                });
            });
        });
            @else

            $(document).ready(function() {
                $('.ajax .pagination a').on('click', function (event) {
                    console.log('click')
                    event.preventDefault();
                    var category= '{{$category}}';
                    var page= $(this).text();
                    if(page == "›"){
                        page= $('.ajax .pagination .page-item.active .page-link').text();
                        page=Number(page)+1;
                        if(page > $('.ajax .pagination a').length){
                            page=$('.ajax .pagination a').length;
                        }
                    }
                    if(page == "‹"){
                        page= $('.ajax .pagination .page-item.active .page-link').text();
                        page=Number(page)-1;
                        if(page < 1){
                            page=1;
                        }
                    }
                    var xhr = $.ajax({
                        url: '{{route('query.filter.product')}}',
                        method: 'get',
                        dataType: 'html',
                        data: {category:category,page:page},
                        success: function(data){
                            $('.products_category').empty();
                            $( ".products_category" ).append(data);
                            //kill the request
                            xhr.abort()
                        }
                    });


                });
            });
            @endif

    </script>

