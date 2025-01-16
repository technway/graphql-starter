<?php

/**
 * Bootstrap file for Custom Post Type functionality.
 *
 * This file serves as the entry point for custom post type registration 
 * in the GraphQL_Starter package. It ensures the functionality is loaded 
 * properly and provides backward compatibility by making the registration 
 * function globally accessible.
 *
 * @package GraphQL_Starter
 */

// Load required files
require_once get_template_directory() . '/includes/core/classes/CustomPostType.php';

// Expose the registration function globally for backward compatibility
if (!function_exists('register_custom_post_type')) {
    /**
     * This function wraps the `CustomPostType::register` method, allowing
     * custom post types to be registered easily in a backward-compatible manner.
     *
     * @return void
     */
    function register_custom_post_type(array $args)
    {
        return CustomPostType::register($args);
    }
}
