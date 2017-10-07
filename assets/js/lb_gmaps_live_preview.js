function initMap() {
    var markers = [];
    var mapAttributes = {
        post_id: post.ID
    };

    if( null !== data.map && 'object' === typeof data.map ) {
        var map = new google.maps.Map( document.getElementById( 'lb-gmaps-live-preview' ), parseMapData( data.map ) );
        mapAttributes = data.map;
    } else {
        var map = new google.maps.Map( document.getElementById( 'lb-gmaps-live-preview' ), {
            center: {lat: 45.40338, lng: 10.17403},
            zoom: 3,
            disableDefaultUI: true,
            gestureHandling: 'none'
        });
    }

    for ( var i = 0; i < data.markers.length; i++ ) {
        if( 'object' === typeof data.markers[ i ] ) {
            var marker = new google.maps.Marker( parseMarkerData( data.markers[ i ] ) );
            marker.setPosition( new google.maps.LatLng( marker.lat , marker.lng ) );
            marker.setMap( map );
            displayInfoWindow( map, marker );
        }
    }

    ( function ( map, mapAttributes ) {
        postFormHandler( map, mapAttributes );
    } )( map, mapAttributes );

    map.addListener( 'dblclick', function ( event ) {
        createMarker( map, event.latLng );
    } );

    $( '#publish' ).on( 'click', function ( e ) {
        mapAttributes.lat = map.getCenter().lat();
        mapAttributes.lng = map.getCenter().lng();
        mapAttributes.zoom = map.getZoom();
        if( mapAttributes.hasOwnProperty( 'map_types' ) && Array.isArray( mapAttributes.map_types) ) {
            mapAttributes.map_types = mapAttributes.map_types.join( ', ' );
        }
        $.ajax( {
            type: "POST",
            url: admin.ajaxURL,
            data: {
                action: 'save_map_data',
                map: mapAttributes,
                markers: markers
            }
        } );
    } );

    function createMarker( map, location ) {
        var marker = new google.maps.Marker({
            map: map
        });

        marker.setPosition( location );

        var markerObject = {
            post_id: post.ID,
            lat: marker.position.lat(),
            lng: marker.position.lng()
        };
        showMarkerForm( map, marker );
        markers.push( markerObject );
    }
}

//TODO: Add Comments
//TODO: Use API Getters and Setters
//TODO: Work on the Rotate Option