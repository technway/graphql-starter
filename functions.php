<?php

/**
 * GraphQL Starter: Main Entry File
 * 
 * This file includes all utility modules for developers to extend WordPress 
 * with meta boxes, custom fields, custom post types, and WPGraphQL integration.
 * 
 * @package GraphQL_Starter
 */

// Load WPGraphQL setup
require_once get_template_directory() . '/includes/core/graphql/graphql-setup.php';

// Stop loading if WPGraphQL Plugin is missing
if (defined('WPGRAPHQL_IS_MISSING')) {
    return;
}

// Load Theme Config
require_once get_template_directory() . '/theme.config.php';

// Load Utils
require_once get_template_directory() . '/includes/core/utils.php';

// Load required files based on configuration
graphql_starter_load_required_files([
    ['file' => '/includes/core/bootstrap/cpt-bootstrap.php'],
    ['file' => '/includes/core/bootstrap/cf-bootstrap.php'],
    [
        'file' => '/includes/post-types.php',
        'constant' => 'GRAPHQL_STARTER_CUSTOM_POST_TYPES_ENABLED'
    ],
    [
        'file' => '/includes/custom-fields.php',
        'constant' => 'GRAPHQL_STARTER_CUSTOM_FIELDS_ENABLED'
    ],
    [
        'file' => '/includes/core/graphql/social-share-graphql.php',
        'constant' => 'GRAPHQL_STARTER_SOCIAL_SHARE_ENABLED'
    ]
]);

/**
 * Basic theme setup for GraphQL Starter.
 * 
 * This function sets up core theme features like support for post thumbnails 
 * and registering navigation menus. These features enable essential functionality 
 * for the theme and its integration with WordPress.
 */
function graphql_starter_setup()
{
    // Enable support for Post Thumbnails (featured images).
    add_theme_support('post-thumbnails');

    /**
     * Register navigation menus.
     * 
     * This function declares menu locations, enabling assigning menus 
     * in the WordPress admin under Appearance > Menus. The registered locations 
     * are 'primary-menu' and 'footer-menu', which are intended for the 
     * primary navigation and footer navigation, respectively.
     */
    register_nav_menus(array(
        'primary-menu' => __('Primary Menu', 'graphql-starter'), // Main navigation menu.
        'footer-menu'  => __('Footer Menu', 'graphql-starter')  // Footer navigation menu.
    ));
}
add_action('after_setup_theme', 'graphql_starter_setup');

// Redirect frontend requests to wp-admin. Only if enabled in `theme.config.php`
if (defined('GRAPHQL_STARTER_REDIRECT_FRONTEND_ENABLED') && GRAPHQL_STARTER_REDIRECT_FRONTEND_ENABLED) {
    add_action('template_redirect', 'graphql_starter_redirect_frontend');
    add_action('rest_api_init', 'graphql_starter_redirect_frontend');
}
