<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * WidCol Core class
 *
 * @class WidColCore
 */
if (!class_exists("WidColCore")) {
    class WidColCore
    {
        /**
         * Plugin version
         *
         * @var string
         */
        public string $version = '0.0.2';

        /**
         * The single instance of the class
         *
         * @var WidColCore|null
         */
        protected static ?WidColCore $_instance = null;

        /**
         * Widgets Collection Core
         *
         * Uses the Singleton pattern to load 1 instance of this class at maximum
         *
         * @static
         * @return WidColCore
         */
        public static function instance(): WidColCore
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * Constructor
         */
        private function __construct()
        {
            $this->define_constants();
            $this->init_hooks();
            $this->actions_and_filters();
        }

        /**
         * Initialise Widgets Collection
         */
        public function init()
        {
            include_once WIDCOL_ABSPATH . 'includes/widcol-install.php';

            $this->initialise_localisation();
            do_action('widgets_collection_before_init');

            WidColInstall::install();

            do_action('widgets_collection_init');
        }

        /**
         * Initialise the localisation of the plugin.
         */
        private function initialise_localisation()
        {
            load_plugin_textdomain('widgets-collection', false, plugin_basename(dirname(WIDCOL_PLUGIN_FILE)) . '/languages/');
        }

        /**
         * Define constants of the plugin.
         */
        private function define_constants()
        {
            $this->define('WIDCOL_ABSPATH', dirname(WIDCOL_PLUGIN_FILE) . '/');
            $this->define('WIDCOL_VERSION', $this->version);
            $this->define('WIDCOL_FULLNAME', 'widgets-collection');
        }

        /**
         * Define if not already set
         *
         * @param string $name
         * @param string $value
         */
        private static function define(string $name, string $value)
        {
            if (! defined($name)) {
                define($name, $value);
            }
        }

        /**
         * Initialise activation and deactivation hooks.
         */
        private function init_hooks()
        {
            register_activation_hook(WIDCOL_PLUGIN_FILE, array( $this, 'activation' ));
            register_deactivation_hook(WIDCOL_PLUGIN_FILE, array( $this, 'deactivation' ));
        }

        /**
         * Activation hook call.
         */
        public function activation()
        {
        }

        /**
         * Deactivation hook call.
         */
        public function deactivation()
        {
        }

        /**
         * Add pluggable support to functions
         */
        public function pluggable()
        {
            include_once WIDCOL_ABSPATH . 'includes/widcol-functions.php';
        }

        /**
         * Add actions and filters.
         */
        private function actions_and_filters()
        {
            add_action('after_setup_theme', array( $this, 'pluggable' ));
            add_action('init', array( $this, 'init' ));
            include_once WIDCOL_ABSPATH . '/includes/widcol-settings.php';
            WidColSettings::instance();
        }
    }
}
