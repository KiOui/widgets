<?php

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Testimonials Shortcode Page class
 *
 * @class WidColTestimonialsShortcodePage
 */
if (!class_exists("WidColTestimonialsShortcodePage")) {
    class WidColTestimonialsShortcodePage
    {
        private string $id;
        private ?string $theme_color = null;
        private ?string $secondary_theme_color = null;
        private ?string $text_color = null;
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
         *      @type string    $text_color             The color of the text, if empty the color will not be included
         *                                              in CSS.
         *      @type string    $enable_star_rating     Whether to enable the star rating on the slider, defaults
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
            if (key_exists("text_color", $atts) && gettype($atts['text_color']) == "string") {
                $this->text_color = sanitize_hex_color($atts['text_color']);
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
         * Include all styles and scripts required for this slider to work.
         */
        public function include_styles_and_scripts()
        {
            wp_enqueue_style('widcol-testimonial-shortcode-page', WIDCOL_PLUGIN_URI . 'assets/testimonials/css/widcol-testimonial-shortcode-page.css');
            if ($this->theme_color) {
                wp_add_inline_style("widcol-testimonial-shortcode-page", "
                    #testimonial-page-container-" . $this->get_id() . " .widcol-testimonial-page-wrapper {
                        " . ($this->theme_color ? "background-color: " . $this->theme_color . ';' : "") . "
                        " . ($this->text_color ? "color: " . $this->text_color . ';' : "") . "
                    }
                    
                    #testimonial-page-container-" . $this->get_id() . " .widcol-testimonial-page-reverse {
                        " . ($this->secondary_theme_color ? "background-color: " . $this->secondary_theme_color . ';' : "") . "
                    }
                ");
            }
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
                <div id="testimonial-page-container-<?php echo $this->id; ?>" class="testimonial-container widcol-testimonial-page-container">
                        <?php
                        $even = true;
            foreach ($posts as $post) {
                ?> <div class="widcol-testimonial-page-wrapper">
                                <div class="widcol-testimonial-page-container-individual<?php if (!$even): ?> widcol-testimonial-page-reverse<?php endif; ?>">
                                    <div class="widcol-testimonial-image">
                                        <?php echo get_the_post_thumbnail($post->ID, 'full'); ?>
                                    </div>
                                    <div class="widcol-testimonial-content">
                                        <div class="text-content">
                                            <h3><?php echo get_the_title($post); ?></h3>
                                            <p class="testimonial-text"><?php echo get_post_meta($post->ID, 'widcol_testimonials_content', true); ?></p>
                                            <?php if ($this->enable_star_rating): ?>
                                                <div class="star-rating">
                                                    <?php for ($i = 0; $i < get_post_meta($post->ID, 'widcol_testimonials_rating', true); $i++) : ?>
                                                        <span class="dashicons dashicons-star-filled"></span>
                                                    <?php endfor; ?>
                                                </div>
                                            <?php endif; ?>
                                            <p class="author"><?php echo get_post_meta($post->ID, 'widcol_testimonials_author', true); ?></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php $even = !$even;
            } ?>
                </div>
            <?php
            $ob_content = ob_get_contents();
            ob_end_clean();
            return $ob_content;
        }
    }
}