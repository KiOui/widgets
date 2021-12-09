<?php
/**
 * Widgets Text Slider Core
 *
 * @package widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WidColTextSliderCore' ) ) {
	/**
	 * Text Slider Core class
	 *
	 * @class WidColTextSliderCore
	 */
	class WidColTextSliderCore {

		/**
		 * The single instance of the class
		 *
		 * @var WidColTextSliderCore|null
		 */
		protected static ?WidColTextSliderCore $_instance = null;

		/**
		 * Registered sliders.
		 *
		 * @var array
		 */
		private array $sliders = array();

		/**
		 * Widgets Text Slider Core
		 *
		 * Uses the Singleton pattern to load 1 instance of this class at maximum
		 *
		 * @static
		 * @return WidColTextSliderCore
		 */
		public static function instance(): WidColTextSliderCore {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		/**
		 * WidColTextSliderCore constructor.
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
			add_action( 'widgets_collection_init', array( $this, 'add_meta_box_support' ) );
			add_action( 'wp_footer', array( $this, 'localize_slider_script' ) );
		}

		/**
		 * Localize the slider script so that the sliders activate.
		 */
		public function localize_slider_script() {
			if ( count( $this->sliders ) > 0 ) {
				include_once WIDCOL_ABSPATH . 'includes/textslider/class-widcoltextslidershortcodeslider.php';
				WidColTextSliderShortcodeSlider::localize_swiper_activation( $this->sliders );
			}
		}

		/**
		 * Add the Text slider shortcode.
		 */
		public function add_shortcodes() {
			add_shortcode( 'widcol_textslider', array( $this, 'do_shortcode_slider' ) );
		}

		/**
		 * Add the Text slider post type and Text slider Category type.
		 */
		public function add_post_type() {
			register_post_type(
				'widcol_text_sliders',
				array(
					'label' => __( 'Text slider', 'widgets-collection' ),
					'labels' => array(
						'name' => __( 'Text slider', 'widgets-collection' ),
						'singular_name' => __( 'Text slider', 'widgets-collection' ),
						'add_new' => __( 'Add New', 'widgets-collection' ),
						'add_new_item' => __( 'Add New Text slider', 'widgets-collection' ),
						'edit_item' => __( 'Edit Text slider', 'widgets-collection' ),
						'new_item' => __( 'New Text slider', 'widgets-collection' ),
						'view_item' => __( 'View Text slider', 'widgets-collection' ),
						'view_items' => __( 'View Text sliders', 'widgets-collection' ),
						'search_items' => __( 'Search Text sliders', 'widgets-collection' ),
						'not_found' => __( 'No text sliders found', 'widgets-collection' ),
						'not_found_in_trash' => __( 'No text sliders found in trash', 'widgets-collection' ),
						'parent_item_colon' => __( 'Parent Text slider', 'widgets-collection' ),
						'all_items' => __( 'All Text sliders', 'widgets-collection' ),
						'archives' => __( 'Text slider Archives', 'widgets-collection' ),
						'attributes' => __( 'Text slider Attributes', 'widgets-collection' ),
						'insert_into_item' => __( 'Insert into text slider', 'widgets-collection' ),
						'uploaded_to_this_item' => __( 'Uploaded to this text slider', 'widgets-collection' ),
						'featured_image' => __( 'Text slider image', 'widgets-collection' ),
						'set_featured_image' => __( 'Set featured image', 'widgets-collection' ),
						'remove_featured_image' => __( 'Remove featured image', 'widgets-collection' ),
						'use_featured_image' => __( 'Use as featured image', 'widgets-collection' ),
						'menu_name' => __( 'Text sliders', 'widgets-collection' ),
						'filter_items_list' => __( 'Filter text slider list', 'widgets-collection' ),
						'filter_by_date' => __( 'Filter by date', 'widgets-collection' ),
						'items_list_navigation' => __( 'Text slider list navigation', 'widgets-collection' ),
						'items_list' => __( 'Text sliders list', 'widgets-collection' ),
						'item_published' => __( 'Text slider published', 'widgets-collection' ),
						'item_published_privately' => __( 'Text slider published privately', 'widgets-collection' ),
						'item_reverted_to_draft' => __( 'Text slider reverted to draft', 'widgets-collection' ),
						'item_scheduled' => __( 'Text slider scheduled', 'widgets-collection' ),
						'item_updated' => __( 'Text slider updated', 'widgets-collection' ),
					),
					'description' => __( 'Text slider post type', 'widgets-collection' ),
					'public' => true,
					'hierarchical' => false,
					'exclude_from_search' => true,
					'publicly_queryable' => false,
					'show_ui' => true,
					'show_in_menu' => true,
					'show_in_nav_menus' => false,
					'show_in_admin_bar' => true,
					'show_in_rest' => true,
					'menu_position' => 56,
					'menu_icon' => 'dashicons-slides',
					'taxonomies' => array(),
					'has_archive' => false,
					'can_export' => true,
					'delete_with_user' => false,
				)
			);
			remove_post_type_support( 'widcol_text_sliders', 'editor' );
			add_post_type_support( 'widcol_text_sliders', 'thumbnail' );
			register_taxonomy(
				'widcol_text_sliders_category',
				'widcol_text_sliders',
				array(
					'labels' => array(
						'name' => __( 'Text slider Categories', 'widgets-collection' ),
						'singular_name' => __( 'Text slider Category', 'widgets-collection' ),
						'search_items' => __( 'Search Text slider Categories', 'widgets-collection' ),
						'popular_items' => __( 'Popular Text slider Categories', 'widgets-collection' ),
						'all_items' => __( 'All Text slider Categories', 'widgets-collection' ),
						'parent_item' => __( 'Parent Text slider Category', 'widgets-collection' ),
						'parent_item_colon' => __( 'Parent Text slider Category:', 'widgets-collection' ),
						'edit_item' => __( 'Edit Text slider Category', 'widgets-collection' ),
						'view_item' => __( 'View Text slider Category', 'widgets-collection' ),
						'update_item' => __( 'Update Text slider Category', 'widgets-collection' ),
						'add_new_item' => __( 'Add New Text slider Category', 'widgets-collection' ),
						'new_item_name' => __( 'New Text slider Category Name', 'widgets-collection' ),
						'separate_items_with_commas' => __( 'Separate Text slider Categories with commas', 'widgets-collection' ),
						'add_or_remove_items' => __( 'Add or remove text slider categories', 'widgets-collection' ),
						'choose_from_most_used' => __( 'Choose from the most used text slider categories', 'widgets-collection' ),
						'not_found' => __( 'No text slider categories found', 'widgets-collection' ),
						'no_terms' => __( 'No text slider categories', 'widgets-collection' ),
						'filter_by_item' => __( 'Filter by text slider category', 'widgets-collection' ),
					),
					'description' => __( 'Text slider Categories', 'widgets-collection' ),
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
		 * Add meta box support to Text sliders.
		 */
		public function add_meta_box_support() {
			include_once WIDCOL_ABSPATH . '/includes/class-widcolmetabox.php';
			new WidColMetabox(
				'widcol_text_sliders_metabox',
				array(
					array(
						'label' => __( 'Text slider content', 'widgets-collection' ),
						'desc'  => __( 'Content of the text slider', 'widgets-collection' ),
						'id'    => 'widcol_text_sliders_content',
						'type'  => 'text',
					),
					array(
						'label' => __( 'Testimonial icon', 'widgets-collection' ),
						'desc'  => __( 'Icon to be displayed with the text slider (enter as fontawesome classes)', 'widgets-collection' ),
						'id'    => 'widcol_text_sliders_icon',
						'type'  => 'text',
					),
				),
				'widcol_text_sliders',
				__( 'Text slider settings' )
			);
		}

		/**
		 * Do the shortcode of a text slider.
		 *
		 * @param $atts
		 * @return false|string
		 */
		public function do_shortcode_slider( $atts ) {
			if ( gettype( $atts ) != 'array' ) {
				$atts = array();
			}

			include_once WIDCOL_ABSPATH . 'includes/textslider/class-widcoltextslidershortcodeslider.php';
			$shortcode = new WidColTextSliderShortcodeSlider( $atts );
			$this->sliders[] = $shortcode;
			return $shortcode->do_shortcode();
		}
	}
}
