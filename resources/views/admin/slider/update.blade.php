@extends('layouts.admin')
@section('content')
    <section class="content">
        <div class="container-fluid">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{route('admin.slider.update',$id)}}" enctype="multipart/form-data" method="post">
                @csrf
                @method('put')
                <div class=" mb-3">
                    <div class="col-sm-3 mb-3">
                        <input type="text" name="name" value="{{$slider->name}}" class="form-control" placeholder="Наименование">
                    </div>
                    <div class="col-sm-3 mb-3">
                        <input type="text" name="location" value="{{$slider->location}}" class="form-control" placeholder="Расположение">
                    </div>
                    <div class="col-sm-2 mb-2">
                        <select name="status" class="form-control" >
                            @if($slider->status == 1)
                                <option selected value="1">Активный слайдер</option>
                                <option value="0">Не активный слайдер</option>
                            @else
                                <option value="1">Активный слайдер</option>
                                <option selected value="0">Не активный слайдер</option>
                            @endif
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary m-lg-2">Редактировать</button>
                </div>
            </form>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
                    <form id="image" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3 ">
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <input type="hidden" name="id" class="form-control" value="{{$id}}">
                                    <input type="number" name="width" id="width" class="form-control" placeholder="Ширина">
                                </div>
                                <div class="col-sm-3">
                                    <input type="number" name="height" id="height" class="form-control" placeholder="Высота">
                                </div>
                            </div>
                            <div class="row ">
                                <div class="col-sm-3">
                                    <input type="number" name="order" id="order" class="form-control" placeholder="Порядок">
                                </div>
                                <div class="col-sm-3">
                                    <input type="file" name="image" class="form-control-file" id="image">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Добавить изображение</button>
                    </form>

    </div>
    </section>

    <section class="content mt-4">
        <div class="container-fluid gallery_img">

            @if($images)

                @foreach($images as $image)
                    <a rel="gallery" data-fancybox class="photo m-1" href="{{asset($image['path'])}}" title=""><img src="{{asset($image['path'])}}" width="200" height="130" alt="" />
                        <span class="remove_image" data-id="{{$image['id']}}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle" viewBox="0 0 16 16">
                              <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                              <path d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                            </svg>
                        </span>
                    </a>
                @endforeach

            @endif
        </div>
    </section>

    @push('css')
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
    @endpush
    @push('script')
        <script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
        <script>

            $('#image').on('submit',function (event){
                event.preventDefault();
                var data = new FormData(this);
                $.ajax({
                    url: "/admin/slider/image",
                    headers: { 'X-CSRF-Token': $('meta[name="csrf-token"]').attr('content') },
                    type:"POST",
                    contentType: false,
                    data: data,
                    cache: false,
                    processData: false,
                    success:function(response){
                        $('.gallery_img').empty();
                        $(".gallery_img").append(response);
                        document.getElementById("image").reset();
                    },
                });
            });

            $('.remove_image').on('click',function (event){
                event.preventDefault();
                console.log($(this).attr('data-id'));
                var id=$(this).attr('data-id');
                $.ajax({
                    url: "/admin/slider/{{$id}}/image/"+id,
                    type:"POST",
                    data: {
                        "_token": "{{ csrf_token() }}",
                    },
                    success:function(response){
                        $('.gallery_img').empty();
                        $(".gallery_img").append(response);
                    },
                });


            })

            $(document).ready(function(){
                $("a.photo").fancybox({
                    transitionIn: 'elastic',
                    transitionOut: 'elastic',
                    speedIn: 500,
                    speedOut: 500,
                    hideOnOverlayClick: false,
                    titlePosition: 'over'
                });
            });

        </script>

    @endpush

@endsection
