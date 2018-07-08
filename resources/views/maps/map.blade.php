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

    <script>
        // Got from PHP (formalized)
        var markers = JSON.parse('<?php echo json_encode($markArr); ?>');

        // Basic marker with location and title info
        var google_markers = [];

        // Visible markers
        var displayed_markers = [];

        function displayMarkerListener(map) {

            //formalized info about marker to marker obj
            addMarkers();

            // Listener for map border changing
            map.addListener('bounds_changed', function () {
                showVisibleMarkers(map);

                // Add clusterer for displayed markers
                var markerCluster = new MarkerClusterer(map, displayed_markers, {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
            });

        }

        function showVisibleMarkers(map) {
            for (var i = 0; i < google_markers.length; i++)
            {
                if (map.getBounds().contains(google_markers[i].getPosition()))
                {
                    // Marker
                    var marker = google_markers[i];

                    // Window
                    var infowindow = new google.maps.InfoWindow({
                        content: marker.title
                    });

                    // Added listener to marker with InfoWindow
                    marker.addListener('click', function() {
                        infowindow.open(map, marker);
                    });

                    // Visible element added to map
                    google_markers[i].setMap(map);

                    // Pushed to displayed markers (for Clusterizing)
                    displayed_markers.push(google_markers[i]);

                    // Deleted from all markers (in case to not redraw inf times)
                    google_markers.splice(i, 1);
                }
            }
        }

        function addMarkers() {
            for (var i = 0; i < markers.length; i++) {
                var mark = markers[i],
                    LatLng = new google.maps.LatLng(mark[0], mark[1]),
                    marker = new google.maps.Marker({
                        position: LatLng,
                        title: mark[2]
                    });
                google_markers.push(marker);
            }
        }
    </script>

@endsection
