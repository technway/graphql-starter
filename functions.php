<?php

/**
 * GraphQL Starter: Main Entry File
 * 
 * This file includes all utility modules for developers to extend WordPress 
 * with meta boxes, custom fields, custom post types, and WPGraphQL integration.
 * 
 * @package GraphQL_Starter
 */

// Load config
require_once get_template_directory() . '/theme.config.php';

// Then load WPGraphQL setup
require_once get_template_directory() . '/includes/core/graphql/graphql-setup.php';

// Stop loading if WPGraphQL Plugin is missing
if (defined('WPGRAPHQL_IS_MISSING')) {
    return;
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
 * Redirect all frontend requests and REST API requests to wp-admin
 * 
 * @return void
 */
function graphql_starter_redirect_frontend() 
{
    // Don't redirect rest api requests
    if (defined('REST_REQUEST')) {
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

    // Don't redirect login/register pages and other essential WP pages
    $no_redirect_pages = [
        'wp-login.php',
        'wp-register.php', 
        'wp-admin/',
    ];

    // Use a more secure server variable comparison
    $script_name = isset($_SERVER['SCRIPT_NAME']) ? $_SERVER['SCRIPT_NAME'] : $_SERVER['PHP_SELF'];
    $script_name = filter_var($script_name, FILTER_SANITIZE_URL);

    foreach ($no_redirect_pages as $page) {
        if (strpos($script_name, $page) !== false) {
            return;
        }
    }

    // Get and sanitize the current URL path
    $request_uri = isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : '';
    $current_path = trim(parse_url(
        filter_var($request_uri, FILTER_SANITIZE_URL),
        PHP_URL_PATH
    ), '/');

    // Don't redirect if already on admin
    if ($current_path === 'wp-admin') {
        return;
    }

    // Perform safe redirect with nonce
    $admin_url = wp_nonce_url(admin_url(), 'graphql_starter_redirect');

    if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log('Admin Redirect - Redirecting to: ' . $admin_url);
    }

    wp_safe_redirect($admin_url, 302);
    exit;
}
add_action('template_redirect', 'graphql_starter_redirect_frontend');
add_action('rest_api_init', 'graphql_starter_redirect_frontend');

/**
 * Include required files
 * 
 * {file: required constant to import the file}
 */
$required_files = [
    ['file' => '/includes/core/bootstrap/cpt-bootstrap.php'],
    ['file' => '/includes/core/bootstrap/cf-bootstrap.php'],
    [
        'file' => '/includes/post-types.php',
        'constant' => 'GRAPHQL_STARTER_ENABLE_CUSTOM_POST_TYPES'
    ],
    [
        'file' => '/includes/custom-fields.php',
        'constant' => 'GRAPHQL_STARTER_ENABLE_CUSTOM_FIELDS'
    ],
];
foreach ($required_files as $file) {
    $file_path = get_template_directory() . $file['file'];
    $has_constant = isset($file['constant']) && defined($file['constant']);
    $is_constant_true = $has_constant && $file['constant'];

    error_log(print_r([$file_path, $has_constant, $is_constant_true], true));

    if (file_exists($file_path)) {
        if (!$has_constant || $is_constant_true) {
            require_once $file_path;
        }
    }
}
