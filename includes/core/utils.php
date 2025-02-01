<?php
/**
 * This function loads PHP files required by the theme, with optional conditional loading
 * based on defined constants. Files can be loaded unconditionally or only when a specific
 * constant evaluates to true.
 *
 * @param array[] $required_files Array of file configuration arrays. Each array contains:
 *                               - 'file' (string) Required. Relative path to the file from theme root
 *                               - 'constant' (string) Optional. Name of constant to check before loading
 *
 * @example
 * graphql_starter_load_required_files([
 *     // Load file unconditionally
 *     ['file' => '/includes/dir/some-file.php'],
 *     
 *     // Load file only if GRAPHQL_STARTER_CUSTOM_POST_TYPES_ENABLED is true
 *     [
 *         'file' => '/includes/dir/some-other-file.php',
 *         'constant' => 'GRAPHQL_STARTER_SOME_CONSTANT'
 *     ]
 * ]);
 */
function graphql_starter_load_required_files($required_files) {
    foreach ($required_files as $file) {
        // Get absolute file path by combining theme directory with relative path
        $file_path = get_template_directory() . $file['file'];

        // Check if the target file exists in the filesystem
        $file_exists = file_exists($file_path);

        // Check if this file has a conditional constant defined
        $has_constant = isset($file['constant']);

        // For files with constants, check if the constant is explicitly true
        // Files without constants will always load if they exist
        $is_constant_true = $has_constant && constant($file['constant']) === true ? true : false;

        // Only proceed if file exists
        if ($file_exists) {
            // Load file if either:
            // 1. It has no constant requirement, or
            // 2. Its required constant evaluates to true
            if (!$has_constant || $is_constant_true) {
                require_once $file_path;
            }
        }
    }
}

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

    // Disable unnecessary frontend features
    if (function_exists('remove_action')) {
        remove_action('wp_head', 'print_emoji_detection_script', 7);
        remove_action('wp_print_styles', 'print_emoji_styles');
        remove_action('wp_head', 'wp_generator');
        remove_action('wp_head', 'rsd_link');
        remove_action('wp_head', 'wlwmanifest_link');
        remove_action('wp_head', 'wp_shortlink_wp_head');
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