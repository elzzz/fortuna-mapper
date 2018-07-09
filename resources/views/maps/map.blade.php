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
            // Add clusterer for displayed markers
            var markerCluster = new MarkerClusterer(map, [], {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
            // Listener for map border changing
            map.addListener('bounds_changed', function () {
                clearTimeout(timeout);
                timeout = setTimeout(function() {
                    getMarkers(map);
                    createMarkers();
                    showMarkers(map, markerCluster);
                }, 500);
            });

        }

        function getMarkers(map) {
            var latFrom = map.getBounds().toJSON()['south'];
            var latTo = map.getBounds().toJSON()['north'];
            var longFrom = map.getBounds().toJSON()['west'];
            var longTo = map.getBounds().toJSON()['east'];

            $.ajax({
                type: 'GET',
                url: `http://localhost:8080/api/markers?latFrom=${latFrom}&latTo=${latTo}&longFrom=${longFrom}&longTo=${longTo}`,
                dataType: 'json',
                async: false,
                success: function (data) {
                    markers_info = [];
                    for (var i = 0; i < data.length; i++) {
                        markers_info.pushIfNotExist(data[i], function (e) {
                           return e.description === data[i]['description']  && e.lat === data[i]['lat'] && e.long === data[i]['long'];
                        });
                    }
                }
            });
        }

        function createMarkers() {
            markers = [];
            for (var i = 0; i < markers_info.length; i++) {
                var mark = markers_info[i],
                    LatLng = new google.maps.LatLng(mark['lat'], mark['long']),
                    marker = new google.maps.Marker({
                        position: LatLng,
                        title: mark['description'],
                    });


                markers.push(marker);
            }
        }

        function showMarkers(map, markerCluster) {
            var infowindow = new google.maps.InfoWindow();
            markerCluster.clearMarkers();
            for (var i = 0; i < markers.length; i++) {
                var marker = markers[i];

                google.maps.event.addListener(marker, 'click', (function(marker) {
                    return function() {
                        infowindow.setContent(marker.title);
                        infowindow.open(map, marker);
                    }
                })(marker));

                marker.setMap(map);
                markerCluster.addMarker(markers[i]);
            }
        }


        // Comparer for marker arrays

        Array.prototype.inArray = function(comparer) {
            for(var i=0; i < this.length; i++) {
                if(comparer(this[i])) return true;
            }
            return false;
        };

        Array.prototype.pushIfNotExist = function(element, comparer) {
            if (!this.inArray(comparer)) {
                this.push(element);
            }
        };

    </script>

@endsection
