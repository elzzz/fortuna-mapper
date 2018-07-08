@extends('layouts.app')

@section('content')
    <div class="jumbotron center">
        <div style="height: 700px;">
            {!! Mapper::render() !!}
        </div>
    </div>

    @if(count($markers) > 0)
        <ul class="list-group">
            @foreach($markers as $marker)
                <li class="list-group-item">
                    <h3><a href="/mapper/{{$marker->id}}">{{$marker->description}}</a></h3>
                    <small>Created at {{$marker->created_at}} by {{$marker->user->name}}</small>
                </li>
            @endforeach
            {{$markers->links()}}
        </ul>
    @else
        <p>No markers found</p>
    @endif
@endsection
