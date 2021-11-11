<?php
/**
 * Gallery shortcode masonry
 *
 * @package widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WidColGalleryShortcodeMasonry' ) ) {
	/**
	 * Gallery Shortcode class
	 *
	 * @class WidColGalleryShortcodeMasonry
	 */
	class WidColGalleryShortcodeMasonry {

		/**
		 * Identifier of the gallery.
		 *
		 * @var int|null
		 */
		private ?int $id;

		/**
		 * WidColGalleryShortcodeMasonry constructor.
		 *
		 * @param array $atts {
		 *      Optional. Array of Widget parameters.
		 *
		 *      @type int    $id                     ID of the gallery to show.
		 * }
		 */
		public function __construct( array $atts = array() ) {
			if ( key_exists( 'id', $atts ) && gettype( $atts['id'] ) == 'string' ) {
				$this->id = absint( $atts['id'] );
			} else {
				$this->id = null;
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
		 * Include all styles and scripts required for the gallery to work.
		 */
		public function include_styles_and_scripts() {
			wp_enqueue_script( 'macy-js', 'https://cdn.jsdelivr.net/npm/macy@2', array(), true, false );
			wp_enqueue_script( 'gallery-masonry-activation', WIDCOL_PLUGIN_URI . 'assets/gallery/js/gallery-masonry-activation.js', array( 'macy-js' ), '1.0', true );
		}

		/**
		 * Localize the gallery activation javascript file to activate all activated galleries.
		 *
		 * @param WidColGalleryShortcodeMasonry[] $galleries galleries to localize the activation script for.
		 */
		public static function localize_gallery_activation( array $galleries ) {
			$configs = array();
			foreach ( $galleries as $gallery ) {
				$configs[] = array( 'id' => 'widcol-gallery-masonry-' . $gallery->get_id() );
			}
			wp_localize_script( 'gallery-masonry-activation', 'gallery_configs', $configs );
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
				<div id="widcol-gallery-masonry-<?php echo esc_attr( $this->get_id() ); ?>">
					<?php foreach ( $attachement_ids as $attachement_id ) : ?>
						<div>
							<?php echo wp_get_attachment_image( $attachement_id, 'full' ); ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php } else { ?>
				<div class="notice notice-error"><?php echo esc_html( __( 'Please set an ID for this gallery to work.', 'widgets-collection' ) ); ?></div>
				<?php
			}
			$ob_content = ob_get_contents();
			ob_end_clean();
			return $ob_content;
		}
	}
}
