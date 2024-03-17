<?php
/**
 * Widgets Counters Counter
 *
 * @package widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WidColCountersShortcodeCounter' ) ) {
	/**
	 * Counters Shortcode Counter class
	 *
	 * @class WidColCountersShortcodeCounter
	 */
	class WidColCountersShortcodeCounter {

		/**
		 * Identifier of counter.
		 *
		 * @var string
		 */
		private string $id;

		/**
		 * To which number to count up to.
		 *
		 * @var int
		 */
		private int $count_to;

		/**
		 * Counter text.
		 *
		 * @var ?string
		 */
		private ?string $counter_text;

		/**
		 * Icon css class.
		 *
		 * @var ?string
		 */
		private ?string $icon;

		/**
		 * Counter color.
		 *
		 * @var ?string
		 */
		private ?string $counter_color;

		/**
		 * Text color.
		 *
		 * @var ?string
		 */
		private ?string $text_color;

		/**
		 * Widgets Collection Counters Shortcode Counter constructor.
		 *
		 * @param array $atts {
		 *      Optional. Array of Widget parameters.
		 *
		 *      @type string    $id                     CSS ID of the widget, if empty a random ID will be assigned.
		 *      @type string    $counter_text           The text to display on the counter.
		 *      @type string    $icon                   The fontawesome icon to use on the icon.
		 *      @type string    $popup_text             The text to display on popup.
		 *      @type string    $counter_color          The background color to use on the counter.
		 *      @type string    $text_color             The color of the counter text.
		 *
		 * }
		 */
		public function __construct( array $atts = array() ) {
			if ( key_exists( 'id', $atts ) && 'string' === gettype( $atts['id'] ) ) {
				$this->id = $atts['id'];
			} else {
				$this->id = uniqid();
			}
			if ( key_exists( 'count_to', $atts ) && 'string' === gettype( $atts['count_to'] ) ) {
				$this->count_to = intval( $atts['count_to'] );
			} else {
				$this->count_to = 100;
			}
			if ( key_exists( 'counter_text', $atts ) && 'string' === gettype( $atts['counter_text'] ) ) {
				$this->counter_text = $atts['counter_text'];
			} else {
				$this->counter_text = null;
			}
			if ( key_exists( 'icon', $atts ) && 'string' === gettype( $atts['icon'] ) ) {
				$this->icon = $atts['icon'];
			} else {
				$this->icon = null;
			}
			if ( key_exists( 'counter_color', $atts ) && gettype( $atts['counter_color'] ) == 'string' ) {
				$this->counter_color = sanitize_hex_color( $atts['counter_color'] );
			} else {
				$this->counter_color = null;
			}
			if ( key_exists( 'text_color', $atts ) && gettype( $atts['text_color'] ) == 'string' ) {
				$this->text_color = sanitize_hex_color( $atts['text_color'] );
			} else {
				$this->text_color = null;
			}
			$this->include_styles_and_scripts();
		}

		/**
		 * Get the ID of this counter.
		 *
		 * @return string
		 */
		public function get_id(): string {
			return $this->id;
		}

		/**
		 * Include all styles and scripts required for this counter to work.
		 */
		public function include_styles_and_scripts(): void {
			wp_enqueue_style( 'widcol-badges', WIDCOL_PLUGIN_URI . 'assets/counters/css/widcol-counters.css', array(), '1.0' );
			wp_enqueue_script( 'widcol-start-counters', WIDCOL_PLUGIN_URI . 'assets/counters/js/start-counters.js', array(), '1.0' );
		}

		/**
		 * Get the contents of the shortcode.
		 *
		 * @return false|string
		 */
		public function do_shortcode(): bool|string {
			ob_start(); ?>
			<div class="widcol-counter" id="<?php echo esc_attr( $this->get_id() ); ?>" style="
			<?php if ( isset( $this->counter_color ) ) : ?>
				background-color: <?php echo esc_attr( $this->counter_color ); ?>;
					<?php endif ?>
				"
			>
				<?php if ( isset( $this->icon ) ) : ?>
					<div class="widcol-counter-icon"
							<?php if ( isset( $this->text_color ) ) : ?>
								style="color: <?php echo esc_attr( $this->text_color ); ?>;"
							<?php endif ?>
						>
							<i class="<?php echo esc_attr( $this->icon ); ?>"></i>
						</div>
				<?php endif; ?>
				<div class="widcol-counter-value" data-count="<?php echo esc_attr( $this->count_to ); ?>">0</div>
				<?php if ( isset( $this->counter_text ) ) : ?>
					<div class="widcol-counter-text"
							<?php if ( isset( $this->text_color ) ) : ?>
								style="color: <?php echo esc_attr( $this->text_color ); ?>;"
							<?php endif ?>
						><?php echo esc_html( $this->counter_text ); ?></div>
				<?php endif; ?>
			</div>
			<?php
			$ob_content = ob_get_contents();
			ob_end_clean();
			return $ob_content;
		}
	}
}
