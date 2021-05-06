<?php

if (!function_exists("widgets_collection_sanitize_boolean_default_false")) {
    function widgets_collection_sanitize_boolean_default_false($input): bool
    {
        $filtered = filter_var($input, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        if (is_null($filtered)) {
            return False;
        } else {
            return $filtered;
        }
    }
}