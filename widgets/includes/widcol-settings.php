<?php

if (!class_exists("WidgetsCollectionSettings")) {
    class WidgetsCollectionSettings
    {
        /**
         * The single instance of the class
         *
         * @var WidgetsCollectionSettings|null
         */
        protected static ?WidgetsCollectionSettings $_instance = null;

        /**
         * Widgets Collection Core
         *
         * Uses the Singleton pattern to load 1 instance of this class at maximum
         *
         * @static
         * @return WidgetsCollectionSettings
         */
        public static function instance(): WidgetsCollectionSettings
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function __construct()
        {
            $this->actions_and_filters();
        }

        public function actions_and_filters()
        {
            add_action('admin_menu', array( $this, 'add_menu_page' ), 99);
            add_action('admin_init', array( $this, 'register_settings'));
            if (get_option('widgets_collection_settings')['widgets_collection_testimonials_enabled']) {
                include_once WIDGETS_COLLECTION_ABSPATH . '/includes/testimonials/widcol-testimonials-core.php';
                WidgetsCollectionTestimonialsCore::instance();
            }
        }

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

        public function register_settings()
        {
            register_setting(
                'widgets_collection_settings',
                'widgets_collection_settings',
                array($this, 'widgets_collection_settings_validate')
            );

            add_settings_section(
                'enabled_widgets_section',
                __('Enabled widgets', 'widgets_collection'),
                'widgets_collection_enabled_widgets_callback',
                'widgets_collection_settings'
            );

            add_settings_field(
                'widgets_collection_testimonials_enabled',
                __('Enable Testimonials', 'widgets_collection'),
                array($this, 'widgets_collection_testimonials_enabled_renderer'),
                'widgets_collection_settings',
                'enabled_widgets_section'
            );
        }

        public function widgets_collection_settings_validate($input): array
        {
            $output['widgets_collection_testimonials_enabled'] = widgets_collection_sanitize_boolean_default_false($input['widgets_collection_testimonials_enabled']);
            return $output;
        }

        public function widgets_collection_testimonials_enabled_renderer()
        {
            $options = get_option('widgets_collection_settings'); ?>
            <input type='checkbox' name='widgets_collection_settings[widgets_collection_testimonials_enabled]' <?php checked($options['widgets_collection_testimonials_enabled'], 1); ?> value='1'>
            <?php
        }

        public function widgets_collection_enabled_widgets_callback()
        {
            echo __('Enabled widgets', 'widgets-collection');
        }

        public function widgets_admin_menu_dashboard_callback()
        {
            include_once WIDGETS_COLLECTION_ABSPATH . 'views/widcol-admin-dashboard-view.php';
        }
    }
}
