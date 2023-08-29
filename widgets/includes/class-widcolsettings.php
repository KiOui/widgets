<?php
/**
 * Settings class
 *
 * @package widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WidColSettings' ) ) {
	/**
	 * WidCol Settings class
	 *
	 * @class WidColSettings
	 */
	class WidColSettings {

		/**
		 * The single instance of the class
		 *
		 * @var WidColSettings|null
		 */
		protected static ?WidColSettings $_instance = null;

		/**
		 * The instance of the settings class.
		 *
		 * @var Settings
		 */
		private Settings $settings;

		/**
		 * The instance of the settings group class.
		 *
		 * @var SettingsGroup
		 */
		private SettingsGroup $settings_group;

		/**
		 * Widgets Collection Settings instance
		 *
		 * Uses the Singleton pattern to load 1 instance of this class at maximum
		 *
		 * @static
		 * @return WidColSettings
		 */
		public static function instance(): WidColSettings {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * SUCSettings constructor.
		 */
		public function __construct() {
			include_once WIDCOL_ABSPATH . 'includes/settings/settings-init.php';
			include_once WIDCOL_ABSPATH . 'includes/widcol-settings-config.php';
			initialize_settings_fields();

			$this->settings = SettingsFactory::create_settings( widcol_get_settings_config() );
			$this->settings->initialize_settings();
			$this->settings_group = SettingsFactory::create_settings_group( widcol_get_settings_screen_config() );

			$this->actions_and_filters();
		}

		/**
		 * Execute custom actions.
		 */
		public function do_custom_actions() {
			if ( get_current_screen()->id === 'toplevel_page_widcol_admin_menu' ) {
				if ( isset( $_POST['option_page'] ) && isset( $_POST['action'] ) && 'update' == $_POST['action'] && 'widcol_settings' === $_POST['option_page'] && isset( $_POST['_wpnonce'] ) && wp_verify_nonce( wp_unslash( $_POST['_wpnonce'] ), 'widcol_settings-options' ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
					$this->settings->update_settings( $_POST );
					$this->settings->save_settings();
					wp_redirect( '/wp-admin/admin.php?page=widcol_admin_menu' );
					exit;
				}
			}
		}

		/**
		 * Register the settings group settings.
		 *
		 * @return void
		 */
		public function register_settings(): void {
			$this->settings_group->register( $this->settings );
		}

		/**
		 * Add actions and filters.
		 */
		public function actions_and_filters(): void {
			add_action( 'admin_init', array( $this->settings, 'register' ) );
			add_action( 'admin_menu', array( $this, 'register_settings' ) );
			add_action( 'current_screen', array( $this, 'do_custom_actions' ), 99 );

			if ( $this->settings->get_value( 'widcol_testimonials_enabled' ) ) {
				include_once WIDCOL_ABSPATH . '/includes/testimonials/class-widcoltestimonialscore.php';
				WidColTestimonialsCore::instance();
			}
			if ( $this->settings->get_value( 'widcol_galleries_enabled' ) ) {
				include_once WIDCOL_ABSPATH . '/includes/gallery/class-widcolgallerycore.php';
				WidColGalleryCore::instance();
			}
			if ( $this->settings->get_value( 'widcol_text_sliders_enabled' ) ) {
				include_once WIDCOL_ABSPATH . '/includes/textslider/class-widcoltextslidercore.php';
				WidColTextSliderCore::instance();
			}
			if ( $this->settings->get_value( 'widcol_badges_enabled' ) ) {
				include_once WIDCOL_ABSPATH . '/includes/badges/class-widcolbadgescore.php';
				WidColBadgesCore::instance();
			}
		}
	}
}
