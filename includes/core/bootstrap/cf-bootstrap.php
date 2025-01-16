<?php

/**
 * Bootstrap file for Custom Fields functionality.
 *
 * This file initializes the custom fields system for the GraphQL_Starter package. 
 * It ensures the required functionality is loaded and provides backward compatibility 
 * by exposing a globally accessible function to register custom fields.
 * 
 * @package GraphQL_Starter
 * @requires WordPress
 */

// Load required files
require_once get_template_directory() . '/includes/core/classes/CustomField.php';
require_once get_template_directory() . '/includes/core/classes/CustomFieldRenderer.php';

// Expose the registration function globally for backward compatibility
if (!function_exists('register_custom_field')) {
    /**
     * This function wraps the `CustomField::register` method, allowing custom 
     * fields to be registered easily in a backward-compatible manner.
     *
     * @return void
     */
    function register_custom_field($args)
    {
        return CustomField::register($args);
    }
}
