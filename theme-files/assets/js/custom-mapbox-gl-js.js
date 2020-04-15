/**
 * Custom Mapbox JS
 */

/**
 * Create the custom map
 *
 * @param id
 * @param center_lat
 * @param center_lng
 * @param address
 * @param zoom
 * @param styles
 * @param enable_nav_control
 * @param enable_marker
 * @param enable_marker_popup
 */
function create_map(id, center_lat, center_lng, address, zoom, styles, enable_nav_control, enable_marker, enable_marker_popup) {
    // bail early if Mapbox required JS is not available
    if (typeof mapboxgl === 'undefined') {
        return;
    }

    try {
        // Create the map coordinates using the values from the form or from the user's selected location
        let map = new mapboxgl.Map({
            container: 'map_' + id,
            zoom: (zoom) ? zoom : '16',
            center: [center_lat, center_lng],
            style: 'mapbox://styles/mapbox/' + styles
        });

        // Add the navigation control if it is set to be enabled ?>
        if (enable_nav_control) {
            let navControl = new mapboxgl.NavigationControl();
            map.addControl(navControl, 'top-left');
        }

        // Create the map marker
        create_marker(map, center_lat, center_lng, address, enable_marker, enable_marker_popup);
    } catch (error) {
        // Log important error message
        console.log(error.message);
    }
}

/**
 * Create the map marker
 *
 * @param map
 * @param center_lat
 * @param center_lng
 * @param address
 * @param enable_marker
 * @param enable_marker_popup
 */
function create_marker(map, center_lat, center_lng, address, enable_marker, enable_marker_popup) {
  console.log(address);
    // Add the marker if it is set to be enabled
    if (enable_marker) {
        // This GeoJSON will be used to determine where the marker will appear on the map
        let geoJSON = {
            type: 'FeatureCollection',
            features: [{
                type: 'Feature',
                geometry: {
                    type: 'Point',
                    coordinates: [center_lat, center_lng]
                },
                properties: {
                    title: 'Mapbox',
                    description: address
                }
            }]
        };

        // Add marker to the location
        geoJSON.features.forEach(function (marker) {
            // Create an HTML element for each feature
            let el = document.createElement('div');
            el.className = 'marker';

            // Make a marker for the feature and add to the map
            let map_marker = new mapboxgl.Marker(el).setLngLat(marker.geometry.coordinates);

            // Set the popup if the marker popup is set to be enabled
            if (enable_marker_popup) {
                map_marker.setPopup(new mapboxgl.Popup({offset: 25}) // adds popup
                    .setHTML('<h3>' + marker.properties.title + '</h3><p>' + marker.properties.description + '</p>'));
            }

            // Add the marker to the map
            map_marker.addTo(map);
        });
    }
}
