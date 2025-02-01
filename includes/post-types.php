<?php

use GraphQL_Starter\Classes\CustomPostType;

/**
 * Register Custom Post Types
 * 
 * This file defines all custom post types used in the theme.
 * Each post type is registered with GraphQL support enabled by default.
 * 
 * Read the `../docs/custom-post-types.md` file for more information on registering custom post types.
 * 
 * @package GraphQL_Starter
 */

/**
 * Below is an example of how to register a custom post type.
 * 
 * For more examples with different post types options,
 * see the `../docs/custom-post-types.md` file.
 * 
 * Note: Please enable this feature by setting:
 * `GRAPHQL_STARTER_CUSTOM_POST_TYPES_ENABLED` constant to true in the `theme.config.php` file.
 */

// Project post type
CustomPostType::register(['slug' => 'hello']);