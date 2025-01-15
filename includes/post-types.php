<?php

use GraphQL_Starter\Classes\CustomPostType;

/**
 * Register Custom Post Types
 * 
 * This file defines all custom post types used in the theme.
 * Each post type is registered with GraphQL support enabled by default.
 * 
 * @package GraphQL_Starter
 */

/**
 * Below are some examples of how to register custom post types.
 */

// Project post type
CustomPostType::register(['slug' => 'project']);

// Team Member post type
CustomPostType::register([
    'slug' => 'hello',
    'singular_name' => 'Hello',
    'plural_name' => 'Hellos',
    'supports' => ['title', 'editor', 'thumbnail'],
    'menu_icon' => 'dashicons-groups',
    'menu_position' => 6,
]);

// // Event post type
CustomPostType::register([
    'slug' => 'event',
    'singular_name' => 'Event',
    'plural_name' => 'Events',
    'supports' => ['title', 'thumbnail', 'excerpt'],
    'menu_icon' => 'dashicons-calendar',
    'menu_position' => 7,
    'has_archive' => true,
]);