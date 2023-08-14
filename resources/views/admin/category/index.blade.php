@extends('layouts.admin')

@section('content')

    <section class="content">
        <div class="container-fluid">
            <div class="d-grid gap-2 d-md-flex justify-content-md-end mb-2">
                <div class="d-flex justify-content-between w-100">
                    <div class="search" id="search">
                        <input type="text" name="search" class="form-control" placeholder="Поиск" value="" style="border: 1px solid #00b5d3;margin: 0;"/>
                    </div>

                    <a href="{{route('admin.category.create')}}" class="btn btn-primary" type="button">Добавить Категорию</a>
                </div>
            </div>
            <table class="table">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Наименование</th>
                    <th scope="col">Родитель</th>
                    <th scope="col">Статус</th>
                    <th scope="col">Действие</th>
                </tr>
                </thead>
                <tbody>
                @if($categories)
                    @foreach($categories as $category)

                        <tr>
                            <th scope="row">{{$category->category_id}}</th>
                            <td>{{$category->name}}</td>
                            <td>@if(!empty($category->parent_name)) {{$category->parent_name}} @else {{$category->parent_id}}  @endif</td>
                            <td>@if($category->status == 1) Активный @else Не активный @endif</td>
                            <td><a href="{{route('admin.category.edit',$category->category_id)}}">Редактировать</a>
                                <form method="post" action="{{route('admin.category.destroy',$category->category_id)}}">
                                    @csrf
                                    @method('delete')
                                    <input type="submit" class="btn btn-link ml-0 pl-0" value="Удалить">
                                </form>
                            </td>
                        </tr>

                    @endforeach
                @endif
                </tbody>
            </table>

        </div><!-- /.container-fluid -->
        {{$categories->links()}}


        @push('script')

            <script type="module">

                jQuery(document).ready(function($) {

                    // Set the Options for "Bloodhound" suggestion engine
                    var bloodhound = new Bloodhound({
                        datumTokenizer: Bloodhound.tokenizers.whitespace,
                        queryTokenizer: Bloodhound.tokenizers.whitespace,
                        remote: {
                            url: '{{route('query.admin.category','%QUERY%')}}',
                            wildcard: '%QUERY%'
                        },
                    });

                    $('#search input').typeahead({
                        hint: true,
                        highlight: false,
                        minLength: 3,

                    }, {
                        // name: 'users',
                        source: bloodhound,
                        display: function(data) {
                            return data.name  //Input value to be set when you select a suggestion.
                        },
                        templates: {
                            // empty: [
                            //     '<div class="list-group search-results-dropdown"><div class="list-group-item">Nothing found.</div></div>'
                            // ],
                            suggestion: function (data) {

                                return '<a href="' + data.category_id + '" class="list-group-item">' + data.name + '</a>'

                            }
                        },
                        limit: 5
                    });
                });

            </script>

        @endpush

    </section>

@endsection
