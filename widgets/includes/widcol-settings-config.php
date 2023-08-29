<?php
/**
 * Settings configuration
 *
 * @package widgets-collection
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'widcol_get_settings_config' ) ) {
	/**
	 * Get the settings config.
	 *
	 * @return array The settings config.
	 */
	function widcol_get_settings_config(): array {
		return array(
			'group_name' => 'widcol_settings',
			'name' => 'widcol_settings',
			'settings' => array(
				array(
					'type'    => 'bool',
					'id'      => 'widcol_testimonials_enabled',
					'name'    => __( 'Testimonials Enabled', 'widgets-collection' ),
					'default' => false,
					'hint'    => __( 'Whether testimonials are enabled.', 'widgets-collection' ),
				),
				array(
					'type'    => 'bool',
					'id'      => 'widcol_galleries_enabled',
					'name'    => __( 'Galleries Enabled', 'widgets-collection' ),
					'default' => false,
					'hint'    => __( 'Whether galleries are enabled.', 'widgets-collection' ),
				),
				array(
					'type'    => 'bool',
					'id'      => 'widcol_text_sliders_enabled',
					'name'    => __( 'Text Sliders Enabled', 'widgets-collection' ),
					'default' => false,
					'hint'    => __( 'Whether text sliders are enabled.', 'widgets-collection' ),
				),
				array(
					'type'    => 'bool',
					'id'      => 'widcol_badges_enabled',
					'name'    => __( 'Badges Enabled', 'widgets-collection' ),
					'default' => false,
					'hint'    => __( 'Whether badges are enabled.', 'widgets-collection' ),
				),
			),
		);
	}
}

if ( ! function_exists( 'widcol_get_settings_screen_config' ) ) {
	/**
	 * Get the settings screen config.
	 *
	 * @return array The settings screen config.
	 */
	function widcol_get_settings_screen_config(): array {
		return array(
			'page_title'        => esc_html__( 'Widgets Collection', 'widgets-collection' ),
			'menu_title'        => esc_html__( 'Widgets Collection', 'widgets-collection' ),
			'capability_needed' => 'manage_options',
			'menu_slug'         => 'widcol_admin_menu',
			'icon'              => 'dashicons-awards',
			'position'          => 56,
			'settings_pages' => array(
				array(
					'page_title'        => esc_html__( 'Widgets Collection Dashboard', 'widgets-collection' ),
					'menu_title'        => esc_html__( 'Dashboard', 'widgets-collection' ),
					'capability_needed' => 'manage_options',
					'menu_slug'         => 'widcol_admin_menu',
					'renderer'          => function() {
						include_once WIDCOL_ABSPATH . 'views/widcol-admin-dashboard-view.php';
					},
					'settings_sections' => array(
						array(
							'id'       => 'global_settings',
							'name'     => __( 'Global settings', 'widgets-collection' ),
							'settings' => array(
								'widcol_testimonials_enabled',
								'widcol_galleries_enabled',
								'widcol_text_sliders_enabled',
								'widcol_badges_enabled',
							),
						),
					),
				),
			),
		);
	}
}
