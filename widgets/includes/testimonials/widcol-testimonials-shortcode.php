<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Testimonials Shortcode class
 *
 * @class WidColTestimonialsShortcode
 */
if (!class_exists("WidColTestimonialsShortcode")) {
    class WidColTestimonialsShortcode
    {
        private string $id;
        private ?string $theme_color = null;
        private ?string $secondary_theme_color = null;
        private bool $arrow_enabled = true;
        private bool $pagination_enabled = true;
        private int $slides_per_view = 1;
        private bool $enable_star_rating = true;
        private array $category = array();

        /**
         * WidgetsCollectionTestimonialsShortcode constructor.
         * @param array $atts {
         *      Optional. Array of Widget parameters.
         *
         *      @type string    $id                     CSS ID of the widget, if empty a random ID will be assigned.
         *      @type string    $theme_color            Primary theme color, if empty the color will not be included in
         *                                              CSS.
         *      @type string    $secondary_theme_color  Secondary theme color, if empty the color will not be included
         *                                              in CSS.
         *      @type string    $arrow_enabled          Whether or not to enable the arrows on the slider, defaults to
         *                                              true.
         *      @type string    $pagination_enabled     Whether or not to enable the pagination on the slider, defaults
         *                                              to true.
         *      @type string    $slides_per_view        How many slides per view to show. Defaults to 1.
         *      @type string    $enable_star_rating     Whether or not to enable the star rating on the slider, defaults
         *                                              to true.
         *      @type string    $category               List of comma-separated categories of testimonials to include,
         *                                              defaults to all categories (array(0)).
         * }
         *
         */
        public function __construct(array $atts = [])
        {
            if (key_exists("id", $atts) && gettype($atts['id']) == "string") {
                $this->id = $atts['id'];
            } else {
                $this->id = uniqid();
            }
            if (key_exists("theme_color", $atts) && gettype($atts['theme_color']) == "string") {
                $this->theme_color = sanitize_hex_color($atts['theme_color']);
            }
            if (key_exists("secondary_theme_color", $atts) && gettype($atts['secondary_theme_color']) == "string") {
                $this->secondary_theme_color = sanitize_hex_color($atts['secondary_theme_color']);
            }
            if (key_exists("arrow_enabled", $atts) && gettype($atts['arrow_enabled']) == "string") {
                $arrow_enabled = filter_var($atts["arrow_enabled"], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                $this->arrow_enabled = $arrow_enabled == null ? false : $arrow_enabled;
            }
            if (key_exists("pagination_enabled", $atts) && gettype($atts['pagination_enabled']) == "string") {
                $pagination_enabled = filter_var($atts["pagination_enabled"], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                $this->pagination_enabled = $pagination_enabled == null ? false : $pagination_enabled;
            }
            if (key_exists("slides_per_view", $atts) && gettype($atts['slides_per_view']) == "string") {
                $this->slides_per_view = filter_var($atts['slides_per_view'], FILTER_VALIDATE_INT, array(
                        "default" => $this->slides_per_view,
                        "min_range" => 1,
                ));
            }
            if (key_exists("enable_star_rating", $atts) & gettype($atts["enable_star_rating"]) == "string") {
                $star_rating_enabled = filter_var($atts["enable_star_rating"], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
                $this->enable_star_rating = $star_rating_enabled == null ? false : $star_rating_enabled;
            }
            if (key_exists("category", $atts) & gettype($atts["category"]) == "string") {
                include_once WIDCOL_ABSPATH . 'includes/testimonials/widcol-testimonials-functions.php';
                $this->category = widcol_testimonials_string_to_array_ints($atts["category"]);
            }
            $this->include_styles_and_scripts();
        }

        /**
         * Get Testimonials corresponding to this slider.
         *
         * @return WP_POST[]
         */
        public function get_posts(): array
        {
            $atts = array(
                'post_type' => 'widcol_testimonials',
                'post_status' => 'publish',
                'numberposts' => '-1',
            );
            if (sizeof($this->category) > 0) {
                $atts['tax_query'] = array(
                    array(
                        'taxonomy' => 'widcol_testimonials_category',
                        'field' => 'term_id',
                        'terms' => $this->category
                    )
                );
            }
            return get_posts($atts);
        }

        /**
         * Get the ID of this slider.
         *
         * @return string
         */
        public function get_id(): string
        {
            return $this->id;
        }

        /**
         * Get the amount of slides per view of this slider.
         *
         * @return string
         */
        public function get_slides_per_view(): string
        {
            return $this->slides_per_view;
        }

        /**
         * Include all styles and scripts required for this slider to work.
         */
        public function include_styles_and_scripts()
        {
            wp_enqueue_style('swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css');
            wp_enqueue_script('swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js');
            wp_enqueue_script('swiper-activation', WIDCOL_PLUGIN_URI . 'assets/testimonials/js/swiper-activation.js', array( 'swiper-js' ), false, true);
            wp_enqueue_style('swiper-overrides', WIDCOL_PLUGIN_URI . 'assets/testimonials/css/swiper-overrides.css');
            if ($this->theme_color) {
                wp_add_inline_style("swiper-overrides", "
                    #swiper-container-" . $this->get_id() . " .swiper-pagination .swiper-pagination-bullet-active {
                        " . ($this->theme_color ? "background: " . $this->theme_color : "") . "
                    }
                    
                    #swiper-container-" . $this->get_id() . " .swiper-slide .text-content {
                        " . ($this->theme_color ? "color: " . $this->theme_color : "") . "
                    }
                    
                    #swiper-container-" . $this->get_id() . " .swiper-slide .text-content h3 {
                        " . ($this->theme_color ? "color: " . $this->theme_color : "") . "
                    }
                    
                    #swiper-container-" . $this->get_id() . " .dashicons-star-filled {
                        " . ($this->secondary_theme_color ? "color: " . $this->secondary_theme_color : "") . "
                    }
                ");
            }
        }

        /**
         * Localize the slider activation javascript file to activate all activated sliders.
         *
         * @param WidColTestimonialsShortcode[] $sliders sliders to localize the activation script for
         */
        public static function localize_swiper_activation(array $sliders)
        {
            $configs = array();
            foreach ($sliders as $slider) {
                $configs[] = array('id' => 'swiper-container-' . $slider->get_id(), 'slides_per_view' => $slider->get_slides_per_view());
            }
            wp_localize_script('swiper-activation', 'swiper_configs', $configs);
        }

        /**
         * Get the contents of the shortcode.
         *
         * @return false|string
         */
        public function do_shortcode()
        {
            ob_start();
            $posts = $this->get_posts(); ?>
                <div id="swiper-container-<?php echo $this->id; ?>" class="swiper-container widcol-swiper-container">
                    <div class="swiper-wrapper">
                        <?php
                        foreach ($posts as $post) {
                            ?>
                            <div class="swiper-slide">
                                <div class="swiper-slide-header">
                                    <?php if ($this->enable_star_rating): ?>
                                        <div class="star-rating">
                                            <?php for ($i = 0; $i < get_post_meta($post->ID, 'widcol_testimonials_rating', true); $i++) : ?>
                                                <span class="dashicons dashicons-star-filled"></span>
                                            <?php endfor; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <div class="swiper-slide-body">
                                    <div class="text-content">
                                        <h3><?php echo get_the_title($post); ?></h3>
                                        <p class="testimonial-text"><?php echo get_post_meta($post->ID, 'widcol_testimonials_content', true); ?></p>
                                    </div>
                                </div>
                                <div class="swiper-slide-footer">
                                    <p class="author" style="<?php if ($this->theme_color) : echo "color: " . $this->theme_color;
                            endif ?>"><?php echo get_post_meta($post->ID, 'widcol_testimonials_author', true); ?></p>
                                </div>
                            </div>
                            <?php
                        } ?>
                    </div>
                    <?php if ($this->pagination_enabled): ?>
                        <div class="swiper-pagination"></div>
                    <?php endif; ?>
                    <?php if ($this->arrow_enabled): ?>
                        <div class="swiper-button-prev" style="<?php if ($this->theme_color) : echo "color: " . $this->theme_color;
            endif ?>"></div>
                        <div class="swiper-button-next" style="<?php if ($this->theme_color) : echo "color: " . $this->theme_color;
            endif ?>"></div>
                    <?php endif; ?>
                </div>
            <?php
            $ob_content = ob_get_contents();
            ob_end_clean();
            return $ob_content;
        }
    }
}
