=== Advanced Custom Fields: Mapbox GL JS Field ===
Contributor: WP Bees
Tags: ACF Mapbox
Requires at least: 4.4.12
Tested up to: 5.5.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Mapbox GL JS is a JavaScript library that uses WebGL to render interactive maps from vector tiles and Mapbox styles.

== Description ==

Mapbox GL JS is a JavaScript library that uses WebGL to render interactive maps from vector tiles and Mapbox styles.

Mapbox is the location data platform for mobile and web applications. It provides building blocks to add location features like maps, search, and navigation into any experience you create.

The following are some features of this plugin:
- A search functionality in the map for places which uses Geocoding API.
- A marker (including a marker popup) which is a visual representation of a specific coordinate on a map. The marker icon can be customised to your liking. Both the marker and the marker popup can be enabled/disabled in the ACF form.
- 10 Mapbox styles were included namely: streets-v10, outdoors-v10, light-v9, dark-v9, satellite-v9, satellite-streets-v10, navigation-preview-day-v2, navigation-preview-night-v2, navigation-guidance-day-v2, and navigation-guidance-night-v2.
- Navigation control - zoom in, zoom out, and Compass.

For more information about Mapbox, please visit https://www.mapbox.com.


= Compatibility =

This ACF field type is compatible with:
* ACF 4 (from 4.4.12)
* ACF 5 (to 5.5.0)


== Installation ==

1. Copy the `acf-mapbox-gl-js` folder into your `wp-content/plugins` folder.
2. Activate the `Advanced Custom Fields: Mapbox GL JS` plugin via the plugins admin page.
3. Create a new field via ACF and select the Mapbox type which is under jQuery category.
4. In the theme-files folder, you will find three files/folder namely: functions.php, mapbox.php, and assets folder.
    4.1. Copy the content of the functions.php to your theme's functions.php. Preferably put it at the end.
    4.2. Copy the mapbox.php file to your theme. This is a template you will use to display the Mapbox on the frontend. When you create a post/page, assign it to the template called Mapbox GLS JS. You can customise this to your liking.
    4.3. Copy/Merge the assets folder to your theme. This folder has the CSS, JS, and marker image that will be used in the frontend.
5. Read the description above for usage instructions

== Changelog ==

= 1.0.0 =
* Initial Release.
