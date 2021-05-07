<?php

/*
Plugin Name: Lars van Rhijn Widgets
Description: A collection of custom-made widgets for Wordpress websites
Plugin URI: https://github.com/KiOui/widgets
Version: 0.0.1
Author: Lars van Rhijn
Author URI: https://larsvanrhijn.nl/
*/

if (!defined('ABSPATH')) {
    exit;
}

if (! defined('WIDGETS_COLLECTION_PLUGIN_FILE')) {
    define('WIDGETS_COLLECTION_PLUGIN_FILE', __FILE__);
}
if (! defined('WIDGETS_COLLECTION_PLUGIN_URI')) {
    define('WIDGETS_COLLECTION_PLUGIN_URI', plugin_dir_url(__FILE__));
}

include_once dirname(__FILE__) . '/includes/widcol-core.php';

WidgetsCollectionCore::instance();
