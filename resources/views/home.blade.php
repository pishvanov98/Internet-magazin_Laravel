@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

@if($images)
                   <div class="owl-carousel owl-theme" id="slider">
                       <!--Слайд 1-->
                       @foreach($images as $image)
                           <div class="slide" >
                              <img src="{{asset($image['path'])}}">
                           </div>
                       @endforeach
                   </div>
@endif



    @guest

        Вы гость

    @endguest
@push('script')
        <script type="module">

            $(document).ready(function(){
                const slider = $("#slider").owlCarousel({
                    loop:true,
                    margin:5,
                    autoplay: true,
                    autoplayTimeout: 10000,
                    nav:false,
                    responsive:{
                        0:{
                            items:1
                        },
                    }
                });
            });

        </script>
@endpush




</div>
@endsection
