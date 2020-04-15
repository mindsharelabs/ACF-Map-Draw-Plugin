<?php
/**
 * Template Name: Mapbox GL JS
 * Template Post Type: post, page
 */
?>

<?php get_header(); ?>

<?php $api = apply_filters( 'acf/fields/mapbox/api', array() ); // Apply filter from functions.php ?>
<?php $fields = get_fields(); // Get all ACF fields of the post/page ?>
<?php if ( isset( $api['key'] ) && count( $fields ) > 0 ): ?>
	<?php foreach ( $fields as $name => $location ): ?>
		<?php // Skip if it's not a map field ?>
		<?php if ( ! isset( $location['lat'] ) && ! isset( $location['lng'] ) ): ?>
			<?php continue; ?>
		<?php endif; ?>

		<?php // Set each map's width and height attributes if specified ?>
        <style type="text/css">
            #map_<?php echo $name; ?> {
                <?php if (isset($location['width']) && !empty($location['width'])): ?> width: <?php echo $location['width']; ?>px<?php endif; ?>;
                <?php if (isset($location['height']) && !empty($location['height'])): ?> height: <?php echo $location['height']; ?>px<?php endif; ?>;
            }
        </style>

        <!-- Map container with unique ID -->
        <div id="map_<?php echo $name; ?>"></div>

        <script type="text/javascript">
            if (mapboxgl) {
                // Set the access token
                mapboxgl.accessToken = '<?php echo $api['key']; ?>';

                if (typeof create_map !== 'undefined') {
                    create_map(
                        '<?php echo $name; ?>',
						<?php echo $location['lat']; ?>,
						<?php echo $location['lng']; ?>,
                        '<?php echo $location['address']; ?>',
						<?php echo $location['zoom']; ?>,
                        '<?php echo $location['styles']; ?>',
						<?php echo ( $location['enable_nav_control'] ) ? 'true' : 'false'; ?>,
						<?php echo ( $location['enable_marker'] ) ? 'true' : 'false'; ?>,
						<?php echo ( $location['enable_marker_popup'] ) ? 'true' : 'false'; ?>,
                    );
                }
            }
        </script>
	<?php endforeach; ?>
<?php else: ?>
    <div style="color: #FF0000; margin: 0 auto; width: 50%; text-align: center;"><?php echo __( 'Please set the Mapbox access token and make sure to change the ACF field name. For more info, please read the readme.txt file inside the plugin folder.' ); ?></div>
<?php endif; ?>

<?php get_footer(); ?>