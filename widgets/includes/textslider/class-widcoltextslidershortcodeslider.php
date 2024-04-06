<?php
/**
 * Widgets Text Slider Slider
 *
 * @package widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WidColTextSliderShortcodeSlider' ) ) {
	/**
	 * Text Slider Shortcode Slider class
	 *
	 * @class WidColTextSliderShortcodeSlider
	 */
	class WidColTextSliderShortcodeSlider {

		/**
		 * Identifier of slider.
		 *
		 * @var string
		 */
		private string $id;

		/**
		 * Number of slides per view.
		 *
		 * @var int
		 */
		private int $slides_per_view = 3;

		/**
		 * Categories to include in slider.
		 *
		 * @var array
		 */
		private array $category = array();

		/**
		 * Widgets Collection Text Slider Shortcode Slider constructor.
		 *
		 * @param array $atts {
		 *      Optional. Array of Widget parameters.
		 *
		 *      @type string    $id                     CSS ID of the widget, if empty a random ID will be assigned.
		 *      @type string    $slides_per_view        How many slides per view to show. Defaults to 1.
		 *      @type string    $category               List of comma-separated categories of testimonials to include,
		 *                                              defaults to all categories (array(0)).
		 * }
		 */
		public function __construct( array $atts = array() ) {
			if ( key_exists( 'id', $atts ) && 'string' === gettype( $atts['id'] ) ) {
				$this->id = $atts['id'];
			} else {
				$this->id = uniqid();
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
			if ( key_exists( 'category', $atts ) && 'string' === gettype( $atts['category'] ) ) {
				include_once WIDCOL_ABSPATH . 'includes/textslider/widcol-textslider-functions.php';
				$this->category = widcol_text_sliders_string_to_array_ints( $atts['category'] );
			}
			$this->include_styles_and_scripts();
		}

		/**
		 * Get Testimonials corresponding to this slider.
		 *
		 * @return WP_POST[]
		 */
		public function get_posts(): array {
			$atts = array(
				'post_type' => 'widcol_text_sliders',
				'post_status' => 'publish',
				'numberposts' => '-1',
			);
			if ( count( $this->category ) > 0 ) {
				$atts['tax_query'] = array(
					array(
						'taxonomy' => 'widcol_text_sliders_category',
						'field' => 'term_id',
						'terms' => $this->category,
					),
				);
			}
			return get_posts( $atts );
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
		 * Get the amount of slides per view of this slider.
		 *
		 * @return string
		 */
		public function get_slides_per_view(): string {
			return $this->slides_per_view;
		}

		/**
		 * Include all styles and scripts required for this slider to work.
		 */
		public function include_styles_and_scripts() {
            wp_enqueue_style( 'swiper', WIDCOL_PLUGIN_URI . 'assets/global/css/swiper/swiper-bundle.min.css', array(), '11.1.0' );
            wp_enqueue_script( 'swiper', WIDCOL_PLUGIN_URI . 'assets/global/js/swiper/swiper-bundle.min.js', array(), '11.1.0' );
			wp_enqueue_script( 'text-swiper-activation', WIDCOL_PLUGIN_URI . 'assets/textslider/js/text-swiper-activation.js', array( 'swiper' ), '1.0', true );
			wp_enqueue_style( 'text-swiper-overrides', WIDCOL_PLUGIN_URI . 'assets/textslider/css/text-swiper-overrides.css', array(), '1.0' );
		}

		/**
		 * Localize the slider activation javascript file to activate all activated sliders.
		 *
		 * @param WidColTextSliderShortcodeSlider[] $sliders sliders to localize the activation script for.
		 */
		public static function localize_swiper_activation( array $sliders ) {
			$configs = array();
			foreach ( $sliders as $slider ) {
				$configs[] = array(
					'id' => 'swiper-container-' . $slider->get_id(),
					'slides_per_view' => $slider->get_slides_per_view(),
				);
			}
			wp_localize_script( 'text-swiper-activation', 'swiper_configs', $configs );
		}

		/**
		 * Get the contents of the shortcode.
		 *
		 * @return false|string
		 */
		public function do_shortcode() {
			ob_start();
			$posts = $this->get_posts(); ?>
				<div id="swiper-container-<?php echo esc_attr( $this->id ); ?>" class="swiper-container widcol-text-slider-swiper-container">
					<div class="swiper-wrapper">
						<?php
						foreach ( $posts as $post ) {
							?>
							<div class="swiper-slide">
								<div class="swiper-slide-body">
									<div class="text-content">
										<?php if ( get_post_meta( $post->ID, 'widcol_text_sliders_icon', true ) ) : ?>
											<i class="textslider-icon <?php echo esc_attr( get_post_meta( $post->ID, 'widcol_text_sliders_icon', true ) ); ?>"></i>
										<?php endif; ?>
										<p class="textslider-text"><?php echo esc_html( get_post_meta( $post->ID, 'widcol_text_sliders_content', true ) ); ?></p>
									</div>
								</div>
							</div>
							<?php
						}
						?>
					</div>
				</div>
			<?php
			$ob_content = ob_get_contents();
			ob_end_clean();
			return $ob_content;
		}
	}
}
