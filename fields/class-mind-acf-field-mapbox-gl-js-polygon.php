<?php

// exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


// check if class already exists
if ( ! class_exists( 'mind_acf_field_mapbox_gl_js_polygon' ) ) {
	class mind_acf_field_mapbox_gl_js_polygon extends acf_field {

		/**
		 *  __construct
		 *
		 *  This function will setup the field type data
		 *
		 * @type    function
		 * @date    5/03/2014
		 * @since    5.0.0
		 *
		 * @param $settings
		 */
		function __construct( $settings ) {

			/**
			 *  name (string) Single word, no spaces. Underscores allowed
			 */
			$this->name = 'mapbox_polygon';

			/**
			 * label (string) Multiple words, can include spaces, visible when selecting a field type
			 */
			$this->label = __( 'Mapbox Polygon', 'acf-mapbox' );

			/**
			 * category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
			 */
			$this->category = 'jquery';

			/**
			 * defaults (array) Array of default settings which are merged into the field object. These are used later in settings
			 */
			$this->defaults = array(
				'center_lat'          => '',
				'center_lng'          => '',
				'zoom'                => '',
				'styles'              => '',
				'features'       => '',
				'enable_marker_popup' => '',
			);

			/**
			 * default map values
			 */
			$this->default_values = array(
				'height'     => '400',
				'center_lat' => '-77.01866',
				'center_lng' => '38.888',
				'zoom'       => '12',
				'styles'     => 'streets-v10',
			);

			/**
			 * Default search input box place holder
			 */
			$this->default_search_placeholder = 'Search place...';

			/**
			 * settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
			 */
			$this->settings = $settings;

			// do not delete!
			parent::__construct();
		}


		/**
		 *  render_field_settings()
		 *
		 *  Create extra settings for your field. These are visible when editing a field
		 *
		 * @type    action
		 * @since    3.6
		 * @date    27/07/18
		 *
		 * @param    $field (array) the $field being edited
		 *
		 * @return    n/a
		 */
		function render_field_settings( $field ) {

			// Center - latitude
			acf_render_field_setting( $field, array(
				'label'        => __( 'Center', 'acf' ),
				'instructions' => __( 'Center the initial map. lng value must be between -90 and 90', 'acf' ),
				'type'         => 'text',
				'name'         => 'center_lat',
				'prepend'      => 'lat',
				'placeholder'  => $this->default_values['center_lat']
			) );

			// Center - longitude
			acf_render_field_setting( $field, array(
				'label'        => __( 'Center', 'acf' ),
				'instructions' => __( 'Center the initial map', 'acf' ),
				'type'         => 'text',
				'name'         => 'center_lng',
				'prepend'      => 'lng',
				'placeholder'  => $this->default_values['center_lng'],
				'_append'      => 'center_lat'
			) );

			// Zoom level
			acf_render_field_setting( $field, array(
				'label'        => __( 'Zoom', 'acf' ),
				'instructions' => __( 'Set the initial zoom level', 'acf' ),
				'type'         => 'text',
				'name'         => 'zoom',
				'placeholder'  => $this->default_values['zoom']
			) );

			// Map container's width
			acf_render_field_setting( $field, array(
				'label'        => __( 'Width', 'acf' ),
				'instructions' => __( 'Customise the map width. If left empty, default width will be 100%', 'acf' ),
				'type'         => 'text',
				'name'         => 'width',
				'append'       => 'px',
			) );

			// Map container's height
			acf_render_field_setting( $field, array(
				'label'        => __( 'Height', 'acf' ),
				'instructions' => __( 'Customise the map height', 'acf' ),
				'type'         => 'text',
				'name'         => 'height',
				'append'       => 'px',
				'placeholder'  => $this->default_values['height']
			) );

			// Mapbox styles selection
			acf_render_field_setting( $field, array(
				'label'        => __( 'Styles', 'acf' ),
				'instructions' => __( 'Select a Mapbox Styles to use', 'acf' ),
				'type'         => 'select',
				'name'         => 'styles',
				'append'       => 'px',
				'placeholder'  => $this->default_values['styles'],
				'choices'      => array(
					'streets-v10'                  => 'streets-v10',
					'outdoors-v10'                 => 'outdoors-v10',
					'light-v9'                     => 'light-v9',
					'dark-v9'                      => 'dark-v9',
					'satellite-v9'                 => 'satellite-v9',
					'satellite-streets-v10'        => 'satellite-streets-v10',
					'navigation-preview-day-v2'    => 'navigation-preview-day-v2',
					'navigation-preview-night-v2'  => 'navigation-preview-night-v2',
					'navigation-guidance-day-v2'   => 'navigation-guidance-day-v2',
					'navigation-guidance-night-v2' => 'navigation-guidance-night-v2'
				)
			) );


			// Enable/Disable the map marker popup
			acf_render_field_setting( $field, array(
				'label'        => __( 'Map Marker Popup', 'acf' ),
				'instructions' => __( 'Enable the marker popup on the map', 'acf' ),
				'type'         => 'true_false',
				'name'         => 'enable_marker_popup',
				'ui'           => 1,
				'class'        => 'conditional-toggle',
			) );


		}

		/**
		 *  render_field()
		 *
		 *  Create the HTML interface for your field
		 *
		 * @param    $field (array) the $field being rendered
		 *
		 * @type    action
		 * @since    3.6
		 * @date    27/07/18
		 *
		 * @param    $field (array) the $field being edited
		 *
		 * @return    n/a
		 */
		function render_field( $field ) {

			// Apply filter from functions.php to use the Mapbox access token
			$api = apply_filters( 'acf/fields/mapbox/api', array() );

			// Get the field ID because this will serve as the wrapper element of the map and the hidden fields
			$field_id = $field['id'];

			// validate value
			if ( empty( $field['value'] ) ) {
				$field['value'] = array();
			}

			$field['features'] = get_post_meta(get_the_id(), $field['key'], true);

			// Populate fields with default values if they're empty yet
			foreach ( $this->default_values as $k => $v ) {
				if ( empty( $field[ $k ] ) ) {
					$field[ $k ] = $v;
				}
			}


      echo '<div id="' . $field['id'] . '" class="acf-mapbox-polygon">';
				if ( isset( $api['key'] ) ):
          echo '<div class="polymap"><div id="mapPolygon_' . $field['key'] . '" style="width:100%; height: 500px;"></div></div>';
        endif;

      echo '</div>';
		}

		/**
		 *  input_admin_enqueue_scripts()
		 *
		 *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
		 *  Use this action to add CSS + JavaScript to assist your render_field() action.
		 *
		 * @type    action (admin_enqueue_scripts)
		 * @since    3.6
		 * @date    23/01/13
		 *
		 * @param    n/a
		 *
		 * @return    n/a
		 */
		function input_admin_enqueue_scripts() {
			$api = apply_filters( 'acf/fields/mapbox/api', array() );
			acf_localize_data(array(
				'mapbox_api' => 'pk.eyJ1IjoidGhlamFtZXN3aWxsaWFtIiwiYSI6ImNrOHZ3N2NzdTBoODEzbnBreXBxaWprMGYifQ.e4DQGcfCXLf22Q7eG_apYw',
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'post_id' => get_the_id(),
				'post_meta' => json_encode(get_post_meta(get_the_id()))
			));
			// vars
			$url     = $this->settings['url'];
			$version = $this->settings['version'];
			// Register & include JS
			wp_enqueue_script( 'acf-mind-mapbox-gl-js', '//api.mapbox.com/mapbox-gl-js/v1.4.1/mapbox-gl.js', array( 'acf-input', 'jquery' ), $version, true );
			wp_enqueue_script( 'acf-mind-mapbox-gl-draw-js', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.0.9/mapbox-gl-draw.js', array( 'acf-input', 'acf-mind-mapbox-gl-js'), $version, true );
			wp_enqueue_script( 'acf-mind-mind-mapbox-custom-js', "{$url}assets/js/mind-admin.js", array( 'acf-input', 'acf-mind-mapbox-gl-js', 'acf-mind-mapbox-gl-draw-js' ), $version, true );


			// Register & include CSS
			wp_enqueue_style( 'acf-mind-mapbox-gl-css', '//api.mapbox.com/mapbox-gl-js/v1.4.1/mapbox-gl.css', array( 'acf-input' ), $version );
			wp_enqueue_style( 'acf-mind-mapbox-gl-draw-css', 'https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-draw/v1.0.9/mapbox-gl-draw.css', array( 'acf-input' ), $version );
			wp_enqueue_style( 'acf-mind-mapbox-custom-css', "{$url}assets/css/mapbox-gl-js.css", array( 'acf-input' ), $version );

		}

		/**
		 *  update_value()
		 *
		 *  This filter is applied to the $value before it is saved in the db
		 *
		 * @type    filter
		 * @since    3.6
		 * @date    27/07/18
		 *
		 * @param    $value (mixed) the value found in the database
		 * @param    $post_id (mixed) the $post_id from which the value was loaded
		 * @param    $field (array) the field array holding all the field options
		 *
		 * @return    $value
		 */
		function update_value( $value, $post_id, $field ) {
			if ( empty( $value ) ) {
				return false;
			}

			return $value;
		}

		/**
		 *  validate_value()
		 *
		 *  This filter is used to perform validation on the value prior to saving.
		 *  All values are validated regardless of the field's required setting. This allows you to validate and return
		 *  messages to the user if the value is not correct
		 *
		 * @type    filter
		 * @date    11/02/2014
		 * @since    5.0.0
		 *
		 * @param    $valid (boolean) validation status based on the value and the field's required setting
		 * @param    $value (mixed) the $_POST value
		 * @param    $field (array) the field array holding all the field options
		 * @param    $input (string) the corresponding input name for $_POST value
		 *
		 * @return    $valid
		 */
		function validate_value( $valid, $value, $field, $input ) {
			// bail early if not required
			if ( ! $field['required'] ) {
				return $valid;
			}

			if ( empty( $value )  ) {
				return false;
			}

			return $valid;
		}
	}

	// initialize
	new mind_acf_field_mapbox_gl_js_polygon( $this->settings );
}
