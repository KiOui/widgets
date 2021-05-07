<?php

/**
 * Widgets Collection Install Class
 *
 * @version 0.0.1
 */

defined('ABSPATH') || exit;

/**
 * WidgetsCollectionInstall Class
 */
class WidgetsCollectionInstall
{
    /**
     * Install WCB2BSA
     */
    public static function install()
    {
        if ('yes' === get_transient('widgets_collection_installing')) {
            return;
        }

        set_transient('widgets_collection_installing', 'yes', 10 * MINUTE_IN_SECONDS);

        # Do installation

        flush_rewrite_rules();

        delete_transient('widgets_collection_installing');
    }
}
