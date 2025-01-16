<?php

/**
 * GraphQL Starter: Main Entry File
 * 
 * This file includes all utility modules for developers to extend WordPress 
 * with meta boxes, custom fields, custom post types, and WPGraphQL integration.
 * 
 * @package GraphQL_Starter
 */

// Define the theme version
if (! defined('_GRAPHQL_STARTER_VERSION')) {
    define('_GRAPHQL_STARTER_VERSION', '1.0.0');
}

/**
 * Basic theme setup for GraphQL Starter
 */
function graphql_starter_setup()
{
    // Enable support for Post Thumbnails 
    add_theme_support('post-thumbnails');
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
    // Don't redirect admin or GraphQL requests
    if (function_exists('is_admin') && (is_admin() || defined('GRAPHQL_REQUEST'))) {
        return;
    }

    // Don't redirect WP-CLI requests
    if (defined('WP_CLI')) {
        return;
    }

    // Get the current URL
    $current_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    // Get the site URL without path
    if (function_exists('get_site_url')) {
        $site_url = get_site_url(null, '', 'http');
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
            $site_url = str_replace('http://', 'https://', $site_url);
        }

        // If this is a frontend request, redirect to /graphql
        if (strpos($current_url, '/graphql') === false && function_exists('wp_redirect')) {
            wp_redirect($site_url . '/graphql');
            exit;
        }
    }
}
add_action('template_redirect', 'graphql_starter_redirect_frontend');

/**
 * Include required files
 */
$required_files = [
    '/includes/core/graphql/graphql-setup.php',
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
