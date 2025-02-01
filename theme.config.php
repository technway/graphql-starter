<?php
/**
 * Define constants for theme configuration
 * 
 * This file is used to define constants for the theme configuration.
 * 
 * @package GraphQL Starter
 */

/**
 * Define if custom post types are enabled. Default is false.
 * 
 * This constant controls whether the custom post types are enabled.
 * When enabled (true), the custom post types are registered.
 * 
 * read docs/custom-post-types.md for more information.
 */
define('GRAPHQL_STARTER_ENABLE_CUSTOM_POST_TYPES', false);

/**
 * Define if custom fields are enabled. Default is false.
 * 
 * This constant controls whether the custom fields are enabled.
 * When enabled (true), the custom fields are registered.
 * 
 * read docs/custom-fields.md for more information.
 */
define('GRAPHQL_STARTER_ENABLE_CUSTOM_FIELDS', false);

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
 * Define if post count functionality is enabled. Default is true.
 * 
 * This constant controls whether the total pages count is available in GraphQL queries.
 * When enabled (true), the 'total' field is added to pageInfo in post queries.
 * This is useful for pagination UI and displaying total pages.
 * 
 * Example query when enabled:
 * query GetPostsCounts($first: Int!) {
 *   posts(first: $first) {
 *     pageInfo {
 *       total
 *     }
 *   }
 * }
 * 
 * read docs/blog-posts.md for more information.
 */
define('GRAPHQL_STARTER_POST_PAGES_COUNT_ENABLED', true);