<?php
/**
 * Widgets Counters Core
 *
 * @package widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WidColCountersCore' ) ) {
	/**
	 * Counters Core class.
	 *
	 * @class WidColCountersCore
	 */
	class WidColCountersCore {

		/**
		 * The single instance of the class.
		 *
		 * @var WidColCountersCore|null
		 */
		protected static ?WidColCountersCore $_instance = null;

		/**
		 * Widgets Counters core.
		 *
		 * Uses the Singleton pattern to load 1 instance of this class at maximum
		 *
		 * @static
		 * @return WidColCountersCore
		 */
		public static function instance(): WidColCountersCore {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * WidColCountersCore constructor.
		 */
		private function __construct() {
			$this->actions_and_filters();
			$this->add_shortcodes();
		}

		/**
		 * Add actions and filters.
		 */
		public function actions_and_filters(): void {
		}

		/**
		 * Add the Text slider shortcode.
		 */
		public function add_shortcodes(): void {
			add_shortcode( 'widcol_counter', array( $this, 'do_shortcode_counter' ) );
		}

		/**
		 * Do the shortcode of counter.
		 *
		 * @param $atts
		 * @return string
		 */
		public function do_shortcode_counter( $atts ): string {
			if ( gettype( $atts ) != 'array' ) {
				$atts = array();
			}

			include_once WIDCOL_ABSPATH . 'includes/counters/WidColCountersShortcodeCounter.php';
			$shortcode = new WidColCountersShortcodeCounter( $atts );
			$return = $shortcode->do_shortcode();
			return $return ? $return : '';
		}
	}
}
