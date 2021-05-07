<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Testimonials Core class
 *
 * @class WidColTestimonialsCore
 */
if (!class_exists("WidColTestimonialsCore")) {
    class WidColTestimonialsCore
    {
        /**
         * The single instance of the class
         *
         * @var WidColTestimonialsCore|null
         */
        protected static ?WidColTestimonialsCore $_instance = null;

        private array $sliders = array();

        /**
         * Widgets Collection Core
         *
         * Uses the Singleton pattern to load 1 instance of this class at maximum
         *
         * @static
         * @return WidColTestimonialsCore
         */
        public static function instance(): WidColTestimonialsCore
        {
            if (is_null(self::$_instance)) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         * WidColTestimonialsCore constructor.
         */
        private function __construct()
        {
            $this->actions_and_filters();
            $this->add_meta_box_support();
            $this->add_shortcodes();
        }

        /**
         * Add actions and filters.
         */
        public function actions_and_filters()
        {
            add_action('init', array($this, 'add_post_type'));
            add_action('wp_footer', array($this, 'localize_slider_script'));
        }

        /**
         * Localize the slider script so that the sliders activate.
         */
        public function localize_slider_script()
        {
            if (sizeof($this->sliders) > 0) {
                include_once WIDCOL_ABSPATH . 'includes/testimonials/widcol-testimonials-shortcode.php';
                WidColTestimonialsShortcode::localize_swiper_activation($this->sliders);
            }
        }

        /**
         * Add the Testimonials shortcode.
         */
        public function add_shortcodes()
        {
            add_shortcode('widcol_testimonials', array($this, 'do_shortcode'));
        }

        /**
         * Add the Testimonials post type and Testimonials Category type.
         */
        public function add_post_type()
        {
            register_post_type('widcol_testimonials', array(
                'label' => __('Testimonials', 'widgets-collection'),
                'labels' => array(
                    'name' => __('Testimonials', 'widgets-collection'),
                    'singular_name' => __('Testimonial', 'widgets-collection'),
                    'add_new' => __('Add New', 'widgets-collection'),
                    'add_new_item' => __('Add New Testimonial', 'widgets-collection'),
                    'edit_item' => __('Edit Testimonial', 'widgets-collection'),
                    'new_item' => __('New Testimonial', 'widgets-collection'),
                    'view_item' => __('View Testimonial', 'widgets-collection'),
                    'view_items' => __('View Testimonials', 'widgets-collection'),
                    'search_items' => __('Search Testimonials', 'widgets-collection'),
                    'not_found' => __('No testimonials found', 'widgets-collection'),
                    'not_found_in_trash' => __('No testimonials found in trash', 'widgets-collection'),
                    'parent_item_colon' => __('Parent Testimonial', 'widgets-collection'),
                    'all_items' => __('All Testimonials', 'widgets-collection'),
                    'archives' => __('Testimonial Archives', 'widgets-collection'),
                    'attributes' => __('Testimonial Attributes', 'widgets-collection'),
                    'insert_into_item' => __('Insert into testimonial', 'widgets-collection'),
                    'uploaded_to_this_item' => __('Uploaded to this testimonial', 'widgets-collection'),
                    'featured_image' => __('Featured image', 'widgets-collection'),
                    'set_featured_image' => __('Set featured image', 'widgets-collection'),
                    'remove_featured_image' => __('Remove featured image', 'widgets-collection'),
                    'use_featured_image' => __('Use as featured image', 'widgets-collection'),
                    'menu_name' => __('Testimonials', 'widgets-collection'),
                    'filter_items_list' => __('Filter testimonials list', 'widgets-collection'),
                    'filter_by_date' => __('Filter by date', 'widgets-collection'),
                    'items_list_navigation' => __('Testimonials list navigation', 'widgets-collection'),
                    'items_list' => __('Testimonials list', 'widgets-collection'),
                    'item_published' => __('Testimonial published', 'widgets-collection'),
                    'item_published_privately' => __('Testimonial published privately', 'widgets-collection'),
                    'item_reverted_to_draft' => __('Testimonial reverted to draft', 'widgets-collection'),
                    'item_scheduled' => __('Testimonial scheduled', 'widgets-collection'),
                    'item_updated' => __('Testimonial updated', 'widgets-collection'),
                ),
                'description' => __('Testimonial post type', 'widgets-collection'),
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
                'menu_icon' => 'dashicons-format-quote',
                'taxonomies' => array(),
                'has_archive' => false,
                'can_export' => true,
                'delete_with_user' => false
            ));
            remove_post_type_support('widcol_testimonials', 'editor');
            register_taxonomy('widcol_testimonials_category', 'widcol_testimonials', array(
                'labels' => array(
                    'name' => __('Testimonial Categories'),
                    'singular_name' => __('Testimonial Category'),
                    'search_items' => __('Search Testimonial Categories'),
                    'popular_items' => __('Popular Testimonial Categories'),
                    'all_items' => __('All Testimonial Categories'),
                    'parent_item' => __('Parent Testimonial Category'),
                    'parent_item_colon' => __('Parent Testimonial Category:'),
                    'edit_item' => __('Edit Testimonial Category'),
                    'view_item' => __('View Testimonial Category'),
                    'update_item' => __('Update Testimonial Category'),
                    'add_new_item' => __('Add New Testimonial Category'),
                    'new_item_name' => __('New Testimonial Category Name'),
                    'separate_items_with_commas' => __('Separate Testimonial Categories with commas'),
                    'add_or_remove_items' => __('Add or remove testimonial categories'),
                    'choose_from_most_used' => __('Choose from the most used testimonial categories'),
                    'not_found' => __('No testimonial categories found'),
                    'no_terms' => __('No testimonial categories'),
                    'filter_by_item' => __('Filter by testimonial category'),
                ),
                'description' => __('Testimonial Categories', 'widgets-collection'),
                'public' => false,
                'publicly_queryable' => false,
                'hierarchical' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => false,
                'show_in_rest' => true,
                'show_tagcloud' => false,
                'show_in_quick_edit' => true,
                'show_admin_column' => true
            ));
        }

        /**
         * Add meta box support to Testimonials.
         */
        private function add_meta_box_support()
        {
            include_once WIDCOL_ABSPATH . '/includes/widcol-metaboxes.php';
            new WidColMetabox('widcol_testimonials_metabox', array(
                array(
                    'label' => __('Testimonial content', 'widgets-collection'),
                    'desc'  => __('Content of the testimonial', 'widgets-collection'),
                    'id'    => 'widcol_testimonials_content',
                    'type'  => 'textarea'
                ),
                array(
                    'label' => __('Testimonial author', 'widgets-collection'),
                    'desc'  => __('Author of the testimonial', 'widgets-collection'),
                    'id'    => 'widcol_testimonials_author',
                    'type'  => 'text'
                ),
                array(
                    'label' => __('Testimonial rating', 'widgets-collection'),
                    'desc'  => __('Rating of the testimonial (1-5)', 'widgets-collection'),
                    'id'    => 'widcol_testimonials_rating',
                    'type'  => 'number',
                    'min'   => 1,
                    'max'   => 5,
                ),
            ), 'widcol_testimonials', 'Testimonial settings');
        }

        /**
         * Do the shortcode of a slider.
         *
         * @param $atts
         * @return false|string
         */
        public function do_shortcode($atts)
        {
            if (gettype($atts) != "array") {
                $atts = array();
            }

            include_once WIDCOL_ABSPATH . 'includes/testimonials/widcol-testimonials-shortcode.php';
            $shortcode = new WidColTestimonialsShortcode($atts);
            $this->sliders[] = $shortcode;
            return $shortcode->do_shortcode();
        }
    }
}
