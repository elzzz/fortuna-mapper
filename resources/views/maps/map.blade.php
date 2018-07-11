@extends('layouts.app')

@section('content')
    <div class="center">
        <div style="height: 800px;">
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

    <script>
        let markers_info = [];
        let markers = [];

        var timeout;

        function displayMarkerListener(map) {
            var markerCluster = new MarkerClusterer(map, [], {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
            map.addListener('bounds_changed', function () {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    clearMarkers();
                    getMarkers(map);
                    console.log(markers_info);
                    createMarkers();
                    console.log(markers);
                    showMarkers(map, markerCluster);
                }, 500);
            });

        }

        function getMarkers(map) {
            var latFrom = map.getBounds().toJSON()['south'];
            var latTo = map.getBounds().toJSON()['north'];
            var longFrom = map.getBounds().toJSON()['west'];
            var longTo = map.getBounds().toJSON()['east'];
            var zoom = map.getZoom();

            $.ajax({
                type: 'GET',
                url: `http://localhost:8080/api/markers?latFrom=${latFrom}&latTo=${latTo}&longFrom=${longFrom}&longTo=${longTo}&zoom=${zoom}`,
                async: false,
                success: function (data) {
                    markers_info = [];
                    for (var i = 0; i < data.length; i++) {
                        markers_info.push(data[i]);
                    }
                }
            });
        }

        function createMarkers() {
            markers = [];
            var mark, LatLng, marker;
            for (var i = 0; i < markers_info.length; i++) {
                if (Array.isArray(markers_info[i])) {
                    var cluster = [];
                    for (var j = 0; j < markers_info[i].length; j++) {
                        mark = markers_info[i][j];
                        LatLng = new google.maps.LatLng(mark['lat'], mark['long']);
                        marker = new google.maps.Marker({
                            position: LatLng,
                            title: mark['description'],
                        });
                        cluster.push(marker);
                    }

                    markers.push(cluster);
                } else {
                    mark = markers_info[i];
                    LatLng = new google.maps.LatLng(mark['lat'], mark['long']);
                    marker = new google.maps.Marker({
                        position: LatLng,
                        title: mark['description'],
                    });

                    markers.push(marker);
                }
            }
        }

        function showMarkers(map, markerCluster) {
            var infowindow = new google.maps.InfoWindow();
            var marker;
            markerCluster.clearMarkers();
            for (var i = 0; i < markers.length; i++) {
                if (Array.isArray(markers[i])) {
                    for (var j = 0; j < markers[i].length; j++) {
                        marker = markers[i][j];
                    }
                    markerCluster.addMarkers(markers[i]);

                } else {
                    marker = markers[i];

                    google.maps.event.addListener(marker, 'click', (function(marker) {
                        return function() {
                            infowindow.setContent(marker.title);
                            infowindow.open(map, marker);
                        }
                    })(marker));

                    marker.setMap(map);
                }
            }
        }

        function clearMarkers() {
            for (var i = 0; i < markers.length; i++) {
                if (!Array.isArray(markers[i])) {
                    markers[i].setMap(null);
                }
            }
        }

    </script>

@endsection
