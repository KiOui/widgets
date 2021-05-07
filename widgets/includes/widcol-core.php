<?php

if (!class_exists("WidgetsCollectionCore")) {
    class WidgetsCollectionCore
    {
        /**
         * Plugin version
         *
         * @var string
         */
        public string $version = '0.0.1';

        /**
         * The single instance of the class
         *
         * @var WidgetsCollectionCore|null
         */
        protected static ?WidgetsCollectionCore $_instance = null;

        /**
         * Widgets Collection Core
         *
         * Uses the Singleton pattern to load 1 instance of this class at maximum
         *
         * @static
         * @return WidgetsCollectionCore
         */
        public static function instance(): WidgetsCollectionCore
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
         * Initialise WooCommerce Sales Agents
         */
        public function init()
        {
            include_once WIDGETS_COLLECTION_ABSPATH . 'includes/widcol-install.php';

            $this->initialise_localisation();
            do_action('widgets_collection_before_init');

            WidgetsCollectionInstall::install();

            do_action('widgets_collection_init');
        }

        private function initialise_localisation()
        {
            load_plugin_textdomain('widgets-collection', false, plugin_basename(dirname(WIDGETS_COLLECTION_PLUGIN_FILE)) . '/i18n/');
        }

        private function define_constants()
        {
            $this->define('WIDGETS_COLLECTION_ABSPATH', dirname(WIDGETS_COLLECTION_PLUGIN_FILE) . '/');
            $this->define('WIDGETS_COLLECTION_VERSION', $this->version);
            $this->define('WIDGETS_COLLECTION_FULLNAME', 'widgets-collection');
        }

        /**
         * Define if not already set
         *
         * @param string $name name
         * @param string $value value
         */
        private static function define(string $name, string $value)
        {
            if (! defined($name)) {
                define($name, $value);
            }
        }

        private function init_hooks()
        {
            register_activation_hook(WIDGETS_COLLECTION_PLUGIN_FILE, array( $this, 'activation' ));
            register_deactivation_hook(WIDGETS_COLLECTION_PLUGIN_FILE, array( $this, 'deactivation' ));
        }

        public function activation()
        {
            # Activation
        }

        public function deactivation()
        {
            # Deactivation
        }

        /**
         * Add pluggable support to functions
         */
        public function pluggable()
        {
            include_once WIDGETS_COLLECTION_ABSPATH . 'includes/widcol-functions.php';
        }

        private function actions_and_filters()
        {
            include_once WIDGETS_COLLECTION_ABSPATH . '/includes/widcol-settings.php';
            add_action('after_setup_theme', array( $this, 'pluggable' ));
            WidgetsCollectionSettings::instance();
        }
    }
}
