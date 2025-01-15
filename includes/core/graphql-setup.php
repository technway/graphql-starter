<?php
/**
 * GraphQL Setup and Configurations
 */

/**
 * Register GraphQL settings and configurations
 */
function graphql_starter_init() {
    // Check if WPGraphQL is active
    if (!class_exists('WPGraphQL')) {
        add_action('admin_notices', function() {
            ?>
            <div class="notice notice-error">
                <p><?php _e('This theme requires WPGraphQL plugin to function properly. Please install and activate WPGraphQL.', 'graphql-starter'); ?></p>
            </div>
            <?php
        });
        return;
    }
}
add_action('init', 'graphql_starter_init');
