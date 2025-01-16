<?php

/**
 * GraphQL Setup and Configurations
 * 
 * This file includes all the necessary configurations for the WPGraphQL plugin.
 * It checks if the WPGraphQL plugin is installed and activated, and if not, it displays an error message.
 * 
 * @package GraphQL_Starter
 */

// Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Display admin notice for missing WPGraphQL plugin.
 *
 * @return void
 */
function graphql_starter_admin_notice()
{
    if (! function_exists('admin_url') || ! function_exists('esc_url')) {
        return;
    }
?>
    <div class="notice notice-error" style="border-left-width: 4px; padding: 12px; margin: 20px 0;">
        <h3 style="margin-top: 0; margin-bottom: 8px; font-size: 14px;">
            <?php esc_html_e('⚠️ Required Plugin Missing: WPGraphQL', 'graphql-starter'); ?>
        </h3>
        <p style="margin: 0; font-size: 13px;">
            <?php
            esc_html_e(
                'The GraphQL Starter theme requires the WPGraphQL plugin to function. Please install and activate WPGraphQL before using this theme.',
                'graphql-starter'
            );
            ?>
        </p>
        <p style="margin-top: 8px; margin-bottom: 0;">
            <a href="<?php echo esc_url(admin_url('plugin-install.php?s=WPGraphQL&tab=search&type=term')); ?>" class="button button-primary">
                <?php esc_html_e('Install WPGraphQL', 'graphql-starter'); ?>
            </a>
        </p>
    </div>
<?php
}

// Check if WPGraphQL is installed and activated
if (! class_exists('\WPGraphQL')) {
    // Check if the add_action function exists
    if (! function_exists('add_action')) {
        return;
    }

    // Add the admin notice
    add_action('admin_notices', 'graphql_starter_admin_notice');

    // Define a constant to track WPGraphQL status
    if (! defined('WPGRAPHQL_IS_MISSING')) {
        define('WPGRAPHQL_IS_MISSING', true);
    }

    return;
}
