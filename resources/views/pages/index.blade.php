@extends('layouts.app')

@section('content')
    <div class="jumbotron text-center">
        <h1>{{$title}}</h1>
        <p>This is simple Mapper you can use to find something.</p>
        <p>
            <a class="btn btn-primary btn-lg" href="/mapper" role="button">Mapper</a>
        </p>
    </div>
@endsection
