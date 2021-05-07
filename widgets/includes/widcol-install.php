<?php

defined('ABSPATH') || exit;

/**
 * Widgets Collection Install Class
 *
 * @class WidColInstall
 */
if (!class_exists("WidColInstall")) {
    class WidColInstall
    {
        /**
         * Install Widgets Collection
         */
        public static function install()
        {
            if ('yes' === get_transient('widcol_installing')) {
                return;
            }

            set_transient('widcol_installing', 'yes', 10 * MINUTE_IN_SECONDS);

            # Do installation

            flush_rewrite_rules();

            delete_transient('widcol_installing');
        }
    }
}
