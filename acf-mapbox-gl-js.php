<?php

/*
Plugin Name: Advanced Custom Fields: Mapbox Polygon Field
Description: Adds a ACF field that allows the input and display of a polygon on a Mapbox map.
Version: 1.1.5
Author: Mindshare Labs, Inc
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// check if class already exists
if ( ! class_exists( 'mind_acf_plugin_mapbox_polygon' ) ) {
	class mind_acf_plugin_mapbox_polygon {
		// vars
		var $settings;

		/**
		 *  __construct
		 *
		 *  This function will setup the class functionality
		 *
		 * @type    function
		 * @date    27/07/2018
		 * @since    1.0.0
		 *
		 * @param    void
		 *
		 * @return    void
		 */
		function __construct() {
			if ( !defined( 'MINDPOLYGON_PLUGIN_FILE' ) ) {
	    	define( 'MINDPOLYGON_PLUGIN_FILE', __FILE__ );
	    }
			//Define all the constants
	    $this->define( 'MINDPOLYGON_ABSPATH', dirname( MINDPOLYGON_PLUGIN_FILE ) . '/' );
	    $this->define( 'MINDPOLYGON_PLUGIN_VERSION', '1.1.1');
	    $this->define( 'MINDPOLYGON_PREPEND', 'mindpolygon_' );

			$this->includes();

			// settings
			// - these will be passed into the field class.
			$this->settings = array(
				'version' => '1.0.0',
				'url'     => plugin_dir_url( __FILE__ ),
				'path'    => plugin_dir_path( __FILE__ )
			);

			// include field depending on ACF version
			add_action( 'acf/include_field_types', array( $this, 'include_field' ) ); // v5
		}

		private function define( $name, $value ) {
	    if ( ! defined( $name ) ) {
	      define( $name, $value );
	    }
	  }
		private function includes() {
	    //General
	    include_once MINDPOLYGON_ABSPATH . 'inc/ajax.class.php';
	  }

		/**
		 *  include_field
		 *
		 *  This function will include the field type class
		 *
		 * @type    function
		 *
		 * @param int $version
		 *
		 * @date    27/07/2018
		 * @since    1.0.0
		 *
		 * @return    void
		 */
		function include_field() {
			include_once( 'fields/class-mind-acf-field-mapbox-gl-js-polygon.php' );
		}
	}

	// initialize
	new mind_acf_plugin_mapbox_polygon();
}
