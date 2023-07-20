@extends('layouts.app2')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">


               @guest

                   Вы гость

                @endguest

        </div>
    </div>
</div>
@endsection
