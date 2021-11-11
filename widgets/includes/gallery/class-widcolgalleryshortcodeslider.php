<?php
/**
 * Gallery shortcode slider
 *
 * @package widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WidColGalleryShortcodeSlider' ) ) {
	/**
	 * Gallery Shortcode class
	 *
	 * @class WidColGalleryShortcodeSlider
	 */
	class WidColGalleryShortcodeSlider {

		/**
		 * Identifier of the gallery.
		 *
		 * @var int|null
		 */
		private ?int $id;

		/**
		 * Theme color for slider.
		 *
		 * @var string|null
		 */
		private ?string $theme_color = null;

		/**
		 * Whether arrows are enabled on slider.
		 *
		 * @var bool
		 */
		private bool $arrow_enabled = true;

		/**
		 * Wheter pagination is enabled on slider.
		 *
		 * @var bool
		 */
		private bool $pagination_enabled = true;

		/**
		 * Number of slides per view.
		 *
		 * @var int
		 */
		private int $slides_per_view = 1;

		/**
		 * Whether slider must auto adjust height.
		 *
		 * @var bool
		 */
		private bool $auto_height = true;

		/**
		 * WidColGalleryShortcodeSlider constructor.
		 *
		 * @param array $atts {
		 *      Optional. Array of Widget parameters.
		 *
		 *      @type int    $id                        ID of the gallery to show.
		 *      @type string    $theme_color            Primary theme color, if empty the color will not be included in
		 *                                              CSS.
		 *      @type string    $arrow_enabled          Whether or not to enable the arrows on the slider, defaults to
		 *                                              true.
		 *      @type string    $pagination_enabled     Whether or not to enable the pagination on the slider, defaults
		 *                                              to true.
		 *      @type string    $slides_per_view        How many slides per view to show. Defaults to 1.
		 * }
		 */
		public function __construct( array $atts = array() ) {
			if ( key_exists( 'id', $atts ) && gettype( $atts['id'] ) == 'string' ) {
				$this->id = absint( $atts['id'] );
			} else {
				$this->id = null;
			}
			if ( key_exists( 'theme_color', $atts ) && gettype( $atts['theme_color'] ) == 'string' ) {
				$this->theme_color = sanitize_hex_color( $atts['theme_color'] );
			}
			if ( key_exists( 'auto_height', $atts ) && gettype( $atts['auto_height'] ) == 'string' ) {
				$auto_height = filter_var( $atts['auto_height'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );
				$this->arrow_enabled = $auto_height ?? true;
			}
			if ( key_exists( 'arrow_enabled', $atts ) && gettype( $atts['arrow_enabled'] ) == 'string' ) {
				$arrow_enabled = filter_var( $atts['arrow_enabled'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );
				$this->arrow_enabled = $arrow_enabled ?? false;
			}
			if ( key_exists( 'pagination_enabled', $atts ) && gettype( $atts['pagination_enabled'] ) == 'string' ) {
				$pagination_enabled = filter_var( $atts['pagination_enabled'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );
				$this->pagination_enabled = $pagination_enabled ?? false;
			}
			if ( key_exists( 'slides_per_view', $atts ) && gettype( $atts['slides_per_view'] ) == 'string' ) {
				$this->slides_per_view = filter_var(
					$atts['slides_per_view'],
					FILTER_VALIDATE_INT,
					array(
						'default' => $this->slides_per_view,
						'min_range' => 1,
					)
				);
			}
			$this->include_styles_and_scripts();
		}

		/**
		 * Get corresponding gallery.
		 *
		 * @return ?WP_POST
		 */
		public function get_gallery_post(): ?WP_Post {
			if ( isset( $this->id ) ) {
				return get_post( $this->get_id() );
			} else {
				return null;
			}
		}

		/**
		 * Get the ID of this gallery.
		 *
		 * @return ?int
		 */
		public function get_id(): ?int {
			return $this->id;
		}

		/**
		 * Get the amount of slides per view of this slider.
		 *
		 * @return string
		 */
		public function get_slides_per_view(): string {
			return $this->slides_per_view;
		}

		/**
		 * Get whether to display auto height on slider.
		 *
		 * @return string
		 */
		public function get_auto_height(): bool {
			return $this->auto_height;
		}

		/**
		 * Include all styles and scripts required for the gallery to work.
		 */
		public function include_styles_and_scripts() {
			wp_enqueue_style( 'swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css', array(), '1.0' );
			wp_enqueue_script( 'swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js', array(), '1.0' );
			wp_enqueue_script( 'gallery-swiper-activation', WIDCOL_PLUGIN_URI . 'assets/gallery/js/gallery-swiper-activation.js', array( 'swiper-js' ), '1.0', true );
			wp_enqueue_style( 'gallery-swiper-overrides', WIDCOL_PLUGIN_URI . 'assets/gallery/css/gallery-swiper-overrides.css', array(), '1.0' );
		}

		/**
		 * Localize the slider activation javascript file to activate all activated sliders.
		 *
		 * @param WidColGalleryShortcodeSlider[] $sliders sliders to localize the activation script for.
		 */
		public static function localize_gallery_activation( array $sliders ) {
			$configs = array();
			foreach ( $sliders as $slider ) {
				if ( $slider->get_id() !== null ) {
					$configs[] = array(
						'id'              => 'widcol-gallery-swiper-container-' . $slider->get_id(),
						'slides_per_view' => $slider->get_slides_per_view(),
						'auto_height'     => $slider->get_auto_height(),
					);
				}
			}
			wp_localize_script( 'gallery-swiper-activation', 'swiper_configs', $configs );
		}

		/**
		 * Get the contents of the shortcode.
		 *
		 * @return false|string
		 */
		public function do_shortcode() {
			ob_start();
			$gallery_post = $this->get_gallery_post();
			if ( isset( $gallery_post ) ) {
				$attachement_ids = get_post_meta( $gallery_post->ID, 'widcol_gallery_images', true );
				?>
				<div id="widcol-gallery-swiper-container-<?php echo esc_attr( $this->id ); ?>" class="swiper widcol-gallery-swiper-container">
					<div class="swiper-wrapper">
						<?php
						foreach ( $attachement_ids as $attachement_id ) {
							?>
							<div class="swiper-slide">
								<div class="swiper-slide-body">
									<div class="img-content">
										<?php echo wp_get_attachment_image( $attachement_id, 'full' ); ?>
									</div>
								</div>
							</div>
							<?php
						}
						?>
					</div>
					<?php if ( $this->pagination_enabled ) : ?>
						<div class="swiper-pagination"></div>
					<?php endif; ?>
					<?php if ( $this->arrow_enabled ) : ?>
						<div class="swiper-button-prev" style="
						<?php
						if ( $this->theme_color ) :
							echo 'color: ' . esc_attr( $this->theme_color );
						endif
						?>
								"></div>
						<div class="swiper-button-next" style="
						<?php
						if ( $this->theme_color ) :
							echo 'color: ' . esc_attr( $this->theme_color );
						endif
						?>
								"></div>
					<?php endif; ?>
				</div>
			<?php } else { ?>
				<div class="notice notice-error"><?php echo esc_html( __( 'Please set an ID for this gallery slider to work.', 'widgets-collection' ) ); ?></div>
				<?php
			}
			$ob_content = ob_get_contents();
			ob_end_clean();
			return $ob_content;
		}
	}
}
