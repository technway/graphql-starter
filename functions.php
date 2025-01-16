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

// Stop loading if WPGraphQL is missing
if (defined('WPGRAPHQL_IS_MISSING')) {
    return;
}

// Define the theme version
if (! defined('_GRAPHQL_STARTER_VERSION')) {
    define('_GRAPHQL_STARTER_VERSION', '1.0.0');
}

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

// Disable unnecessary frontend features
if (function_exists('remove_action')) {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('wp_head', 'wp_generator');
    remove_action('wp_head', 'rsd_link');
    remove_action('wp_head', 'wlwmanifest_link');
    remove_action('wp_head', 'wp_shortlink_wp_head');
}

// Disable the admin bar for non-logged-in users
add_action('after_setup_theme', function () {
    if (function_exists('show_admin_bar')) {
        show_admin_bar(false);
    }
});

/**
 * Redirect all frontend requests to /graphql
 */
function graphql_starter_redirect_frontend()
{
    // Add debug logging
    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('GraphQL Redirect Check - Starting');
    }

    // Don't redirect REST API requests
    if (defined('REST_REQUEST') && REST_REQUEST) {
        return;
    }

    // Don't redirect admin, AJAX, or GraphQL requests
    if (is_admin() || wp_doing_ajax() || defined('GRAPHQL_REQUEST')) {
        return;
    }

    // Don't redirect WP-CLI requests
    if (defined('WP_CLI')) {
        return;
    }

    // Don't redirect login/register pages
    $no_redirect_pages = [
        'wp-login.php',
        'wp-register.php'
    ];

    if (str_replace($no_redirect_pages, '', $_SERVER['PHP_SELF']) != $_SERVER['PHP_SELF']) {
        return;
    }

    // Get the current URL path
    $current_path = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');

    // Don't redirect if already on graphql endpoint
    if ($current_path === 'graphql') {
        return;
    }

    // Get the site URL
    $graphql_url = home_url('/graphql');

    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('GraphQL Redirect - Redirecting to: ' . $graphql_url);
    }

    wp_redirect($graphql_url);
    exit;
}
add_action('template_redirect', 'graphql_starter_redirect_frontend');

/**
 * Include required files
 */
$required_files = [
    '/includes/core/bootstrap/cpt-bootstrap.php',
    '/includes/core/bootstrap/cf-bootstrap.php',
    '/includes/post-types.php',
    '/includes/custom-fields.php',
];
foreach ($required_files as $file) {
    $file_path = get_template_directory() . $file;
    if (file_exists($file_path)) {
        require_once $file_path;
    }
}
