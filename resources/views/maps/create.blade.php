@extends('layouts.app')

@section('content')
    <div class="jumbotron center">
        <h1>Create Mark</h1>
        <i>Click on map and write description</i>

        <div style="height: 700px;">
            {!! Mapper::render() !!}
        </div>

        <hr>

        {!! Form::open(['action' => "MapsController@store", 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}

            <div class="form-group">
                {{ Form::label('description', 'Description') }}
                {{ Form::textarea('description', '', ['class' => 'form-control', 'placeholder' => 'Description']) }}
            </div>

            <div class="form-group">
                {{ Form::label('lat', 'Latitude') }}
                {{ Form::text('lat', '', ['class' => 'form-control', 'placeholder' => 'Latitude']) }}
            </div>

            <div class="form-group">
                {{ Form::label('long', 'Longitude') }}
                {{ Form::text('long', '', ['class' => 'form-control', 'placeholder' => 'Longitude']) }}
            </div>

            {{ Form::submit('Submit', ['class' => 'btn btn-success']) }}
        {!! Form::close() !!}
    </div>

    <script>
        var marker;

        function addMarkerListener(map) {
            map.addListener('click', function (e) {
                placeMarker(map, e.latLng);
                document.getElementById('lat').value = e.latLng.lat();
                document.getElementById('long').value = e.latLng.lng();
            });
        }

        function placeMarker(map, location) {
            if (!marker || !marker.setPosition) {
                marker = new google.maps.Marker({
                    position: location,
                    map: map
                });
            } else {
                marker.setPosition(location);
            }
        }
    </script>
@endsection
