<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'widcol_gallery_sanitize_image_id_array' ) ) {
	/**
	 * Convert a string of comma separated integers to an array of ints.
	 *
	 * @param string $input string of comma separated integers
	 * @return int[]
	 */
	function widcol_gallery_sanitize_image_id_array( string $input ): array {
		$splitted = explode( ',', $input );
		$array_ids = array();
		foreach ( $splitted as $element ) {
			$converted_int = filter_var(
				$element,
				FILTER_VALIDATE_INT,
				array(
					'flags' => FILTER_NULL_ON_FAILURE,
					'options' => array(
						'min_range' => 0,
					),
				)
			);
			if ( $converted_int != null ) {
				$image = wp_get_attachment_image( $converted_int );
				if ( $image !== '' ) {
					$array_ids[] = $converted_int;
				}
			}
		}
		return $array_ids;
	}
}
