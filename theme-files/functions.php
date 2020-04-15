<?php

/**
 * Mapbox API
 *
 * Copy this to your theme's functions.php preferably at the end
 *
 * @param $api
 *
 * @return mixed
 */
function acf_mapbox_api( $api ) {
	$api['key'] = 'XXXX'; // Please obtain an access token from your Mapbox account and replace the dummy value

	return $api;
}

add_filter( 'acf/fields/mapbox/api', 'acf_mapbox_api' );

/**
 * Enqueue Mapbox JS and CSS including our custom JS and CSS
 *
 * Copy this to your theme's functions.php preferably at the end
 */
function acf_mapbox_assets() {
	// Enqueue CSS and JS only on the Mapbox template
	if ( is_page_template( 'mapbox.php' ) ) {
		// Mapbox assets
		wp_enqueue_script( 'acf-mapbox-gl-js', '//api.mapbox.com/mapbox-gl-js/v0.47.0/mapbox-gl.js' );
		wp_enqueue_style( 'acf-mapbox-gl-css', '//api.mapbox.com/mapbox-gl-js/v0.47.0/mapbox-gl.css' );

		// Our custom assets
		wp_enqueue_script( 'acf-mapbox-gl-js-custom', get_template_directory_uri() . '/assets/js/custom-mapbox-gl-js.js' );
		wp_enqueue_style( 'acf-mapbox-gl-css-custom', get_template_directory_uri() . '/assets/css/custom-mapbox-gl-js.css' );
	}
}

add_action( 'wp_enqueue_scripts', 'acf_mapbox_assets' );