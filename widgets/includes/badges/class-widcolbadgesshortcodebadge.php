<?php
/**
 * Widgets Badges Badge
 *
 * @package widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WidColBadgesShortcodeBadge' ) ) {
	/**
	 * Badges Shortcode Badge class
	 *
	 * @class WidColBadgesShortcodeBadge
	 */
	class WidColBadgesShortcodeBadge {

		/**
		 * Identifier of slider.
		 *
		 * @var string
		 */
		private string $id;

		/**
		 * Badge text.
		 *
		 * @var ?string
		 */
		private ?string $badge_text;

		/**
		 * Icon css class.
		 *
		 * @var ?string
		 */
		private ?string $icon;

		/**
		 * Popup text.
		 *
		 * @var ?string
		 */
		private ?string $popup_text;

		/**
		 * Badge color.
		 *
		 * @var ?string
		 */
		private ?string $badge_color;

		/**
		 * Text color.
		 *
		 * @var ?string
		 */
		private ?string $text_color;

		/**
		 * Widgets Collection Badges Shortcode Badge constructor.
		 *
		 * @param array $atts {
		 *      Optional. Array of Widget parameters.
		 *
		 *      @type string    $id                     CSS ID of the widget, if empty a random ID will be assigned.
		 *      @type string    $badge_text             The text to display on the badge.
		 *      @type string    $icon                   The fontawesome icon to use on the icon.
		 *      @type string    $popup_text             The text to display on popup.
		 *      @type string    $badge_color            The background color to use on the badge.
		 *      @type string    $text_color             The color of the badge text.
		 *
		 * }
		 */
		public function __construct( array $atts = array() ) {
			if ( key_exists( 'id', $atts ) && 'string' === gettype( $atts['id'] ) ) {
				$this->id = $atts['id'];
			} else {
				$this->id = uniqid();
			}
			if ( key_exists( 'badge_text', $atts ) && 'string' === gettype( $atts['badge_text'] ) ) {
				$this->badge_text = $atts['badge_text'];
			} else {
				$this->badge_text = null;
			}
			if ( key_exists( 'icon', $atts ) && 'string' === gettype( $atts['icon'] ) ) {
				$this->icon = $atts['icon'];
			} else {
				$this->icon = null;
			}
			if ( key_exists( 'popup_text', $atts ) && 'string' === gettype( $atts['popup_text'] ) ) {
				$this->popup_text = $atts['popup_text'];
			} else {
				$this->popup_text = null;
			}
			if ( key_exists( 'badge_color', $atts ) && gettype( $atts['badge_color'] ) == 'string' ) {
				$this->badge_color = sanitize_hex_color( $atts['badge_color'] );
			} else {
				$this->badge_color = null;
			}
			if ( key_exists( 'text_color', $atts ) && gettype( $atts['text_color'] ) == 'string' ) {
				$this->text_color = sanitize_hex_color( $atts['text_color'] );
			} else {
				$this->text_color = null;
			}
			$this->include_styles_and_scripts();
		}

		/**
		 * Get the ID of this slider.
		 *
		 * @return string
		 */
		public function get_id(): string {
			return $this->id;
		}

		/**
		 * Include all styles and scripts required for this slider to work.
		 */
		public function include_styles_and_scripts(): void {
			wp_enqueue_style( 'widcol-badges', WIDCOL_PLUGIN_URI . 'assets/badges/css/widcol-badges.css', array(), '1.0' );
			if ( isset( $this->popup_text ) ) {
				wp_enqueue_style( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css', array(), '5.1.3' );
				wp_enqueue_script( 'bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', array(), '5.1.3' );
			}
		}

		/**
		 * Get the contents of the shortcode.
		 *
		 * @return false|string
		 */
		public function do_shortcode(): bool|string {
			ob_start(); ?>
				<div class="widcol-badge" id="<?php echo esc_attr( $this->get_id() ); ?>" style="
					<?php if ( isset( $this->badge_color ) ) : ?>
						background-color: <?php echo esc_attr( $this->badge_color ); ?>;
					<?php endif ?>
					<?php if ( isset( $this->popup_text ) ) : ?>
						cursor: pointer;
					<?php endif; ?>
					"
					<?php if ( isset( $this->popup_text ) ) : ?>
						data-bs-toggle="modal" data-bs-target="#widcol-badge-modal-<?php echo esc_attr( $this->id ); ?>"
					<?php endif; ?>
				>
					<?php if ( isset( $this->icon ) ) : ?>
						<span class="widcol-badge-icon"
							<?php if ( isset( $this->text_color ) ) : ?>
								style="color: <?php echo esc_attr( $this->text_color ); ?>;"
							<?php endif ?>
						>
							<i class="<?php echo esc_attr( $this->icon ); ?>"></i>
						</span>
					<?php endif; ?>
					<?php if ( isset( $this->badge_text ) ) : ?>
						<span class="widcol-badge-text"
							<?php if ( isset( $this->text_color ) ) : ?>
								style="color: <?php echo esc_attr( $this->text_color ); ?>;"
							<?php endif ?>
						><?php echo esc_html( $this->badge_text ); ?></span>
					<?php endif; ?>
				</div>
				<?php if ( isset( $this->popup_text ) ) : ?>
					<div class="modal fade" id="widcol-badge-modal-<?php echo esc_attr( $this->id ); ?>" tabindex="-1" aria-hidden="true">
						<div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
							<div class="modal-content">
								<div class="modal-header">
									<?php if ( isset( $this->badge_text ) ) : ?>
										<h5 class="modal-title"><?php echo esc_html( $this->badge_text ); ?></h5>
									<?php endif; ?>
									<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
								</div>
								<div class="modal-body">
									<?php echo esc_html( $this->popup_text ); ?>
								</div>
							</div>
						</div>
					</div>
				<?php endif; ?>
			<?php
			$ob_content = ob_get_contents();
			ob_end_clean();
			return $ob_content;
		}
	}
}
