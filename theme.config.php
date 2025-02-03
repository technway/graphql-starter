<?php
/**
 * Define constants for theme configuration
 * 
 * This file is used to define constants for the theme configuration.
 * 
 * @package GraphQL Starter
 */

/**
 * Define if frontend redirect is enabled. Default is true.
 * 
 * This constant controls whether the frontend is redirected to the admin dashboard.
 * When enabled (true), the frontend is redirected to the admin dashboard.
 * 
 * read docs/frontend-redirect.md for more information.
 */
define('GRAPHQL_STARTER_REDIRECT_FRONTEND_ENABLED', true);

/**
 * Define if post count functionality is enabled. Default is true.
 * 
 * This constant controls whether the total pages count is available in GraphQL queries.
 * When enabled (true), the 'total' field is added to pageInfo in post queries.
 * This is useful for pagination UI and displaying total pages.
 * 
 * read docs/blog-posts.md for more information.
 */
define('GRAPHQL_STARTER_POST_PAGES_COUNT_ENABLED', true);

/**
 * Define if posts likes functionality is enabled. Default is false.
 * 
 * This constant controls whether the post likes system is active.
 * When enabled (true), like/unlike queries and mutations are enabled.
 * 
 * read docs/blog-posts.md for more information.
 */
define('GRAPHQL_STARTER_LIKE_POSTS_ENABLED', false);

/**
 * Define if custom post types are enabled. Default is false.
 * 
 * This constant controls whether the custom post types are enabled.
 * When enabled (true), the custom post types are registered.
 * 
 * read docs/custom-post-types.md for more information.
 */
define('GRAPHQL_STARTER_CUSTOM_POST_TYPES_ENABLED', false);


/**
 * Define if custom fields are enabled. Default is false.
 * 
 * This constant controls whether the custom fields are enabled.
 * When enabled (true), the custom fields are registered.
 * 
 * read docs/custom-fields.md for more information.
 */
define('GRAPHQL_STARTER_CUSTOM_FIELDS_ENABLED', false);

/**
 * Define if posts social sharing functionality is enabled. Default is false.
 * 
 * This constant controls whether the posts social sharing functionality is active.
 * When enabled (true), sharing URLs are available in post queries.
 * 
 * read docs/blog-posts.md for more information.
 */
define('GRAPHQL_STARTER_SOCIAL_SHARE_ENABLED', false);