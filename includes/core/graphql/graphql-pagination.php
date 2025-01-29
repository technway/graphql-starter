<?php
// Add total posts count to GraphQL schema if enabled
add_action('graphql_register_types', function () {
    // Add to pageInfo
    register_graphql_field('RootQueryToPostConnectionPageInfo', 'total', [
        'type' => 'Int',
        'description' => __('Total number of pages based on posts per page', 'graphql-starter'),
        'resolve' => function ($page_info, $args, $context, $info) {
            // Validate presence of 'first' parameter
            if (!isset($info->variableValues['first'])) {
                throw new \GraphQL\Error\UserError(
                    __('The "first" parameter is required for pagination calculation.', 'graphql-starter')
                );
            }

            // Validate 'first' is numeric
            if (!is_numeric($info->variableValues['first'])) {
                throw new \GraphQL\Error\UserError(
                    __('The "first" parameter must be a numeric value.', 'graphql-starter')
                );
            }

            // Get and validate posts per page
            $posts_per_page = absint($info->variableValues['first']);

            // Validate minimum value
            if ($posts_per_page < 1) {
                throw new \GraphQL\Error\UserError(
                    __('The "first" parameter must be greater than 0.', 'graphql-starter')
                );
            }

            // Validate maximum value to prevent performance issues
            if ($posts_per_page > 100) {
                throw new \GraphQL\Error\UserError(
                    __('The "first" parameter cannot exceed 100 items per page.', 'graphql-starter')
                );
            }

            // Get total published posts
            $total_posts = wp_count_posts('post')->publish;

            // Validate posts existence
            if ($total_posts === 0) {
                // Returns 0 if no posts exist
                return 0;
            }

            // Calculate total pages
            $total_pages = ceil($total_posts / $posts_per_page);

            return $total_pages;
        }
    ]);
});
