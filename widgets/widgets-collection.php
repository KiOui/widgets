<?php
/**
 * Plugin Name: Widgets Collection
 * Description: A collection of custom-made widgets for Wordpress websites
 * Plugin URI: https://github.com/KiOui/widgets
 * Version: 0.0.8
 * Author: Lars van Rhijn
 * Author URI: https://larsvanrhijn.nl/
 * Text Domain: widgets-collection
 * Domain Path: /languages/
 *
 * @package widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'WIDCOL_PLUGIN_FILE' ) ) {
	define( 'WIDCOL_PLUGIN_FILE', __FILE__ );
}
if ( ! defined( 'WIDCOL_PLUGIN_URI' ) ) {
	define( 'WIDCOL_PLUGIN_URI', plugin_dir_url( __FILE__ ) );
}

include_once __DIR__ . '/includes/class-widcolcore.php';

WidColCore::instance();
