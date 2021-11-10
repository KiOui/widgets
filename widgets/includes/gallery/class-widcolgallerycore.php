<?php
/**
 * Gallery core class
 *
 * @package widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WidColGalleryCore' ) ) {
	/**
	 * Gallery Core class
	 *
	 * @class WidColTestimonialsCore
	 */
	class WidColGalleryCore {

		/**
		 * The single instance of the class
		 *
		 * @var WidColGalleryCore|null
		 */
		protected static ?WidColGalleryCore $_instance = null;

		/**
		 * Registered galleries
		 *
		 * @var array
		 */
		private array $galleries = array();

		/**
		 * Widgets Collection Gallery Core
		 *
		 * Uses the Singleton pattern to load 1 instance of this class at maximum
		 *
		 * @static
		 * @return WidColGalleryCore
		 */
		public static function instance(): WidColGalleryCore {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * WidColGalleryCore constructor.
		 */
		private function __construct() {
			$this->actions_and_filters();
			$this->add_shortcodes();
		}

		/**
		 * Add actions and filters.
		 */
		public function actions_and_filters() {
			add_action( 'init', array( $this, 'add_post_type' ) );
			add_action( 'add_meta_boxes_widcol_galleries', array( $this, 'add_meta_box_support' ) );
			add_action( 'wp_footer', array( $this, 'localize_gallery_script' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'save_post', array( $this, 'save_meta_box_gallery' ) );
		}

		/**
		 * Enqueue scripts for the gallery metabox.
		 */
		public function admin_scripts() {
			wp_enqueue_media();
			wp_enqueue_script( 'widcol-gallery-metabox', WIDCOL_PLUGIN_URI . 'assets/gallery/js/gallery-metabox.js', array( 'jquery' ), '1.0', true );
		}

		/**
		 * Localize the gallery script so that the sliders activate.
		 */
		public function localize_gallery_script() {
			if ( count( $this->galleries ) > 0 ) {
				include_once WIDCOL_ABSPATH . 'includes/gallery/class-widcolgalleryshortcode.php';
				WidColGalleryShortcode::localize_gallery_activation( $this->galleries );
			}
		}

		/**
		 * Add the Gallery shortcode.
		 */
		public function add_shortcodes() {
			add_shortcode( 'widcol_gallery', array( $this, 'do_shortcode' ) );
		}

		/**
		 * Add the Gallery post type and Gallery Category type.
		 */
		public function add_post_type() {
			register_post_type(
				'widcol_galleries',
				array(
					'label' => __( 'Galleries', 'widgets-collection' ),
					'labels' => array(
						'name' => __( 'Galleries', 'widgets-collection' ),
						'singular_name' => __( 'Gallery', 'widgets-collection' ),
						'add_new' => __( 'Add New', 'widgets-collection' ),
						'add_new_item' => __( 'Add New Gallery', 'widgets-collection' ),
						'edit_item' => __( 'Edit Gallery', 'widgets-collection' ),
						'new_item' => __( 'New Gallery', 'widgets-collection' ),
						'view_item' => __( 'View Gallery', 'widgets-collection' ),
						'view_items' => __( 'View Galleries', 'widgets-collection' ),
						'search_items' => __( 'Search Galleries', 'widgets-collection' ),
						'not_found' => __( 'No galleries found', 'widgets-collection' ),
						'not_found_in_trash' => __( 'No galleries found in trash', 'widgets-collection' ),
						'parent_item_colon' => __( 'Parent Gallery', 'widgets-collection' ),
						'all_items' => __( 'All Galleries', 'widgets-collection' ),
						'archives' => __( 'Gallery Archives', 'widgets-collection' ),
						'attributes' => __( 'Gallery Attributes', 'widgets-collection' ),
						'insert_into_item' => __( 'Insert into gallery', 'widgets-collection' ),
						'uploaded_to_this_item' => __( 'Uploaded to this gallery', 'widgets-collection' ),
						'featured_image' => __( 'Featured image', 'widgets-collection' ),
						'set_featured_image' => __( 'Set featured image', 'widgets-collection' ),
						'remove_featured_image' => __( 'Remove featured image', 'widgets-collection' ),
						'use_featured_image' => __( 'Use as featured image', 'widgets-collection' ),
						'menu_name' => __( 'Galleries', 'widgets-collection' ),
						'filter_items_list' => __( 'Filter galleries list', 'widgets-collection' ),
						'filter_by_date' => __( 'Filter by date', 'widgets-collection' ),
						'items_list_navigation' => __( 'Galleries list navigation', 'widgets-collection' ),
						'items_list' => __( 'Galleries list', 'widgets-collection' ),
						'item_published' => __( 'Gallery published', 'widgets-collection' ),
						'item_published_privately' => __( 'Gallery published privately', 'widgets-collection' ),
						'item_reverted_to_draft' => __( 'Gallery reverted to draft', 'widgets-collection' ),
						'item_scheduled' => __( 'Gallery scheduled', 'widgets-collection' ),
						'item_updated' => __( 'Gallery updated', 'widgets-collection' ),
					),
					'description' => __( 'Gallery post type', 'widgets-collection' ),
					'public' => true,
					'hierarchical' => false,
					'exclude_from_search' => true,
					'publicly_queryable' => false,
					'show_ui' => true,
					'show_in_menu' => true,
					'show_in_nav_menus' => false,
					'show_in_admin_bar' => true,
					'show_in_rest' => true,
					'menu_position' => 54,
					'menu_icon' => 'dashicons-images-alt',
					'taxonomies' => array(),
					'has_archive' => false,
					'can_export' => true,
					'delete_with_user' => false,
				)
			);
			remove_post_type_support( 'widcol_galleries', 'editor' );
			register_taxonomy(
				'widcol_galleries_category',
				'widcol_galleries',
				array(
					'labels' => array(
						'name' => __( 'Gallery Categories', 'widgets-collection' ),
						'singular_name' => __( 'Gallery Category', 'widgets-collection' ),
						'search_items' => __( 'Search Gallery Categories', 'widgets-collection' ),
						'popular_items' => __( 'Popular Gallery Categories', 'widgets-collection' ),
						'all_items' => __( 'All Gallery Categories', 'widgets-collection' ),
						'parent_item' => __( 'Parent Gallery Category', 'widgets-collection' ),
						'parent_item_colon' => __( 'Parent Gallery Category:', 'widgets-collection' ),
						'edit_item' => __( 'Edit Gallery Category', 'widgets-collection' ),
						'view_item' => __( 'View Gallery Category', 'widgets-collection' ),
						'update_item' => __( 'Update Gallery Category', 'widgets-collection' ),
						'add_new_item' => __( 'Add New Gallery Category', 'widgets-collection' ),
						'new_item_name' => __( 'New Gallery Category Name', 'widgets-collection' ),
						'separate_items_with_commas' => __( 'Separate Gallery Categories with commas', 'widgets-collection' ),
						'add_or_remove_items' => __( 'Add or remove gallery categories', 'widgets-collection' ),
						'choose_from_most_used' => __( 'Choose from the most used gallery categories', 'widgets-collection' ),
						'not_found' => __( 'No gallery categories found', 'widgets-collection' ),
						'no_terms' => __( 'No gallery categories', 'widgets-collection' ),
						'filter_by_item' => __( 'Filter by gallery category', 'widgets-collection' ),
					),
					'description' => __( 'Gallery Categories', 'widgets-collection' ),
					'public' => false,
					'publicly_queryable' => false,
					'hierarchical' => false,
					'show_ui' => true,
					'show_in_menu' => true,
					'show_in_nav_menus' => false,
					'show_in_rest' => true,
					'show_tagcloud' => false,
					'show_in_quick_edit' => true,
					'show_admin_column' => true,
				)
			);
		}

		/**
		 * Add meta box support to Galleries.
		 */
		public function add_meta_box_support() {
			add_meta_box( 'widcol_gallery_images', __( 'Gallery images', 'widgets-collection' ), array( $this, 'render_meta_box_gallery' ), 'widcol_galleries', 'normal', 'low' );
		}

		/**
		 * Render the custom meta box for gallery images.
		 *
		 * @param $post WP_Post post to render the metabox for
		 */
		public function render_meta_box_gallery( WP_Post $post ) {
			wp_nonce_field( basename( __FILE__ ), 'widcol-gallery_nonce' );
			?>
			<div id="gallery-images-container">
				<ul class="gallery-images">
					<?php
					$product_image_gallery_ids = get_post_meta( $post->ID, 'widcol_gallery_images', true );

					$update_meta         = false;
					$updated_gallery_ids = array();

					if ( ! empty( $product_image_gallery_ids ) ) {
						foreach ( $product_image_gallery_ids as $attachment_id ) {
							$attachment = wp_get_attachment_image( $attachment_id );

							// if attachment is empty skip.
							if ( empty( $attachment ) ) {
								$update_meta = true;
								continue;
							}
							?>
							<li class="image" data-attachment_id="<?php echo esc_attr( $attachment_id ); ?>">
								<?php echo $attachment; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
								<ul class="actions">
									<li><a href="#" class="delete tips" data-tip="<?php esc_attr_e( 'Delete image', 'widgets-collection' ); ?>"><?php esc_html_e( 'Delete', 'widgets-collection' ); ?></a></li>
								</ul>
							</li>
							<?php
							$updated_gallery_ids[] = $attachment_id;
						}
						if ( $update_meta ) {
							update_post_meta( $post->ID, 'widcol_gallery_images', $updated_gallery_ids );
						}
					}
					?>
				</ul>

				<input type="hidden" id="gallery-images" name="gallery-images" value="<?php echo esc_attr( implode( ',', $updated_gallery_ids ) ); ?>" />

			</div>
			<p class="add-gallery-images hide-if-no-js">
				<a href="#" data-choose="<?php esc_attr_e( 'Add images to gallery', 'widgets-collection' ); ?>" data-update="<?php esc_attr_e( 'Add to gallery', 'widgets-collection' ); ?>" data-delete="<?php esc_attr_e( 'Delete image', 'widgets-collection' ); ?>" data-text="<?php esc_attr_e( 'Delete', 'widgets-collection' ); ?>"><?php esc_html_e( 'Add gallery images', 'widgets-collection' ); ?></a>
			</p>
			<?php
		}

		/**
		 * Save the custom meta box gallery images.
		 *
		 * @param $post_id int the post id to save the gallery images for.
		 *
		 * @return int the post ID.
		 */
		public function save_meta_box_gallery( int $post_id ): int {
			include_once WIDCOL_ABSPATH . 'includes/gallery/widcol-gallery-functions.php';
			if ( empty( $_POST['widcol-gallery_nonce'] ) || ! wp_verify_nonce( wp_unslash( $_POST['widcol-gallery_nonce'] ), basename( __FILE__ ) ) ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
				return $post_id;
			}

			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}

			if ( ! current_user_can( 'edit_posts', $post_id ) ) {
				return $post_id;
			}
			$attachment_ids = isset( $_POST['gallery-images'] ) ? widcol_gallery_sanitize_image_id_array( wp_unslash( $_POST['gallery-images'] ) ) : array(); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			update_post_meta( $post_id, 'widcol_gallery_images', $attachment_ids );
			return $post_id;
		}

		/**
		 * Do the shortcode of a gallery.
		 *
		 * @param $atts string|array shortcode attributes
		 * @return false|string a string with the shortcode or false on failure
		 */
		public function do_shortcode( string|array $atts ): string|false {
			if ( gettype( $atts ) != 'array' ) {
				$atts = array();
			}

			include_once WIDCOL_ABSPATH . 'includes/gallery/class-widcolgalleryshortcode.php';
			$shortcode = new WidColGalleryShortcode( $atts );
			$this->galleries[] = $shortcode;
			return $shortcode->do_shortcode();
		}
	}
}
