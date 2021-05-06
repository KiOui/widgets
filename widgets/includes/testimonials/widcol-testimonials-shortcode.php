<?php

if (!class_exists("WidgetsCollectionTestimonialsShortcode")) {

    class WidgetsCollectionTestimonialsShortcode {

        private string $id;

        function __construct() {
            $this->id = uniqid();
            $this->include_styles_and_scripts();
        }

        function get_posts(): array {
            return get_posts(array(
                'post_type' => 'widcol_testimonials',
                'post_status' => 'publish',
                'numberposts' => '-1',
            ));
        }

        function get_id(): string
        {
            return $this->id;
        }

        function include_styles_and_scripts() {
            wp_enqueue_style( 'swiper-css', 'https://unpkg.com/swiper/swiper-bundle.min.css' );
            wp_enqueue_script( 'swiper-js', 'https://unpkg.com/swiper/swiper-bundle.min.js' );
            wp_enqueue_script( 'swiper-activation', WIDGETS_COLLECTION_PLUGIN_URI . 'assets/testimonials/js/swiper-activation.js', array( 'swiper-js' ), false, true);
        }

        public static function localize_swiper_activation(array $sliders) {
            $ids = array();
            foreach ($sliders as $slider) {
                $ids[] = array('id' => 'swiper-container-' . $slider->get_id());
            }
            wp_localize_script('swiper-activation', 'swiper_configs', $ids);
        }

        function do_shortcode() {
            $posts = $this->get_posts();
            ?>
                <div id="swiper-container-<?php echo $this->id; ?>" class="swiper-container">
                    <div class="swiper-wrapper">
                        <?php
                        foreach ($posts as $post) {
                            $post_meta = get_post_meta($post->ID);
                            ?>
                            <div class="swiper-slide">
                                <div class="text-content">
                                    <h3><?php echo get_the_title($post); ?></h3>
                                    <p><?php echo get_post_meta($post->ID, 'widcol_testimonials_content', true); ?></p>
                                    <span class="author"><?php echo get_post_meta($post->ID, 'widcol_testimonials_author', true); ?></span>
                                </div>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="swiper-pagination"></div>

                    <div class="swiper-button-prev"></div>
                    <div class="swiper-button-next"></div>
                </div>
            <?php
        }
    }
}