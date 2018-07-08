@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Dashboard</div>

                    <div class="card-body">
                        <a href="/mapper/create" class="btn btn-primary">Create Mark</a>
                        <h3>Your Marks</h3>
                        <div style="height: 700px;">
                            {!! Mapper::render() !!}
                        </div>
                        @if(count($markers) > 0)
                            <table class="table table-striped">
                                <tr>
                                    <th>Description</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                                @foreach($markers as $marker)
                                    <tr>
                                        <td>{{$marker->description}}</td>
                                        <td><a href="/mapper/{{$marker->id}}/edit" class="btn btn-warning">Edit</a></td>
                                        <td>
                                            {!! Form::open(['action' => ['MapsController@destroy', $marker->id], 'method' => 'POST', 'class' => 'pull-right']) !!}
                                            {{ Form::hidden('_method', 'DELETE') }}
                                            {{ Form::submit('Delete', ['class' => 'btn btn-danger']) }}
                                            {!! Form::close() !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        @else
                            <p>You have no marks</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
