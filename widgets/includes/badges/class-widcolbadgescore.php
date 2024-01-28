<?php
/**
 * Widgets Badges Core
 *
 * @package widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WidColBadgesCore' ) ) {
	/**
	 * Badges Core class.
	 *
	 * @class WidColBadgesCore
	 */
	class WidColBadgesCore {

		/**
		 * The single instance of the class.
		 *
		 * @var WidColBadgesCore|null
		 */
		protected static ?WidColBadgesCore $_instance = null;

		/**
		 * Widgets Badges core.
		 *
		 * Uses the Singleton pattern to load 1 instance of this class at maximum
		 *
		 * @static
		 * @return WidColBadgesCore
		 */
		public static function instance(): WidColBadgesCore {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * WidColBadgesCore constructor.
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
			add_shortcode( 'widcol_badge', array( $this, 'do_shortcode_badge' ) );
		}

		/**
		 * Do the shortcode of badge.
		 *
		 * @param $atts
		 * @return string
		 */
		public function do_shortcode_badge( $atts ): string {
			if ( gettype( $atts ) != 'array' ) {
				$atts = array();
			}

			include_once WIDCOL_ABSPATH . 'includes/badges/class-widcolbadgesshortcodebadge.php';
			$shortcode = new WidColBadgesShortcodeBadge( $atts );
			$return = $shortcode->do_shortcode();
			return $return ? $return : '';
		}
	}
}
