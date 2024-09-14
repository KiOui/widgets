<?php
/**
 * Widgets Collection functions
 *
 * @package widgets
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! function_exists( 'widgets_collection_sanitize_boolean_default_false' ) ) {
	/**
	 * Sanitize boolean value (default to false).
	 *
	 * @param mixed $input Input of the sanitization.
	 * @return bool
	 */
	function widgets_collection_sanitize_boolean_default_false( $input ): bool {
		$filtered = filter_var( $input, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );
		if ( is_null( $filtered ) ) {
			return false;
		} else {
			return $filtered;
		}
	}
}
