<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WidCol Settings class
 *
 * @class WidColSettings
 */
if (!class_exists("WidColSettings")) {
    class WidColSettings
    {
        /**
         * The single instance of the class
         *
         * @var WidColSettings|null
         */
        protected static ?WidColSettings $_instance = null;

        /**
         * Widgets Collection Core
         *
         * Uses the Singleton pattern to load 1 instance of this class at maximum
         *
         * @static
         * @return WidColSettings
         */
        public static function instance(): WidColSettings
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * WidColSettings constructor.
         */
        public function __construct()
        {
            $this->actions_and_filters();
        }

        /**
         * Add actions and filters.
         */
        public function actions_and_filters()
        {
            add_action('admin_menu', array( $this, 'add_menu_page' ), 99);
            add_action('admin_init', array( $this, 'register_settings'));
            if (get_option('widgets_collection_settings')['widgets_collection_testimonials_enabled']) {
                include_once WIDCOL_ABSPATH . '/includes/testimonials/widcol-testimonials-core.php';
                WidColTestimonialsCore::instance();
            }
        }

        /**
         * Add Widget Collection menu page.
         */
        public function add_menu_page()
        {
            add_menu_page(
                esc_html__('Widgets', 'widgets-collection'),
                esc_html__('Widgets', 'widgets-collection'),
                'edit_plugins',
                'widgets_collection_admin_menu',
                null,
                'dashicons-awards',
                56
            );
            add_submenu_page(
                'widgets_collection_admin_menu',
                esc_html__('Widgets Dashboard', 'widgets-collection'),
                esc_html__('Dashboard', 'widgets-collection'),
                'edit_plugins',
                'widgets_collection_admin_menu',
                array( $this, 'widgets_admin_menu_dashboard_callback' )
            );
        }

        /**
         * Register Widget Collection settings.
         */
        public function register_settings()
        {
            register_setting(
                'widgets_collection_settings',
                'widgets_collection_settings',
                array($this, 'widgets_collection_settings_validate')
            );

            add_settings_section(
                'enabled_widgets_section',
                __('Enabled widgets', 'widgets-collection'),
                'widgets_collection_enabled_widgets_callback',
                'widgets_collection_settings'
            );

            add_settings_field(
                'widgets_collection_testimonials_enabled',
                __('Enable Testimonials', 'widgets-collection'),
                array($this, 'widgets_collection_testimonials_enabled_renderer'),
                'widgets_collection_settings',
                'enabled_widgets_section'
            );
        }

        /**
         * Validate Widget Collection settings.
         *
         * @param $input
         * @return array
         */
        public function widgets_collection_settings_validate($input): array
        {
            $output['widgets_collection_testimonials_enabled'] = widgets_collection_sanitize_boolean_default_false($input['widgets_collection_testimonials_enabled']);
            return $output;
        }

        /**
         * Render widget collection testimonials enabled setting.
         */
        public function widgets_collection_testimonials_enabled_renderer()
        {
            $options = get_option('widgets_collection_settings'); ?>
            <input type='checkbox' name='widgets_collection_settings[widgets_collection_testimonials_enabled]' <?php checked($options['widgets_collection_testimonials_enabled'], 1); ?> value='1'>
            <?php
        }

        /**
         * Render the section title of enabled widgets.
         */
        public function widgets_collection_enabled_widgets_callback()
        {
            echo __('Enabled widgets', 'widgets-collection');
        }

        /**
         * Admin menu dashboard callback.
         */
        public function widgets_admin_menu_dashboard_callback()
        {
            include_once WIDCOL_ABSPATH . 'views/widcol-admin-dashboard-view.php';
        }
    }
}
