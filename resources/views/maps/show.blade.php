@extends('layouts.app')

@section('content')
    <a href="/mapper" class="btn btn-default">Go Back</a>
    <div style="height: 700px;">
        {!! Mapper::render() !!}
    </div>
    <div>
        {!! $marker->description !!}
    </div>
    <hr>
    <small>Created at {{$marker->created_at}} by {{$marker->user->name}}</small>
    <hr>
    @auth
        @if(Auth::user()->id == $marker->user_id)
            <a href="/mapper/{{$marker->id}}/edit" class="btn btn-warning">Edit</a>

            {!! Form::open(['action' => ['MapsController@destroy', $marker->id], 'method' => 'POST', 'class' => 'pull-right']) !!}
            {{ Form::hidden('_method', 'DELETE') }}
            {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
            {!! Form::close() !!}
        @endif
    @endauth
@endsection