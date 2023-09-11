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
            <form  @if(!empty($pageGenerator)) action="{{route('admin.pageGenerator.update',$pageGenerator->id)}}"  @else action="{{route('admin.pageGenerator.store')}}" @endif   method="post">
                @csrf
                @if(!empty($pageGenerator)) @method('put')  @endif
                <div class=" mb-3">
                    <div class=" mb-3">
                        <input type="text" name="name" @if(!empty($pageGenerator->name)) value="{{$pageGenerator->name}}"  @endif  class="form-control" placeholder="Наименование">
                    </div>
                    <div class="mb-3">
                        <textarea name="exampleInputNameContent" id="exampleInputNameContent"> @if(!empty($pageGenerator->content)) {{$pageGenerator->content}}  @endif </textarea>
                    </div>

                    <button type="submit" class="btn btn-primary m-lg-2">@if(!empty($pageGenerator->content)) Обновить @else Добавить  @endif</button>
                </div>
            </form>

        </div><!-- /.container-fluid -->

    </section>


    @push('script')
        <script src="{{asset('assets/vendor/ckeditor/ckeditor.js')}}"></script>
        <script>
            CKEDITOR.replace( 'exampleInputNameContent',{height: 800} );
        </script>
    @endpush

@endsection
