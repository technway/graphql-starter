<?php

namespace GraphQL_Starter\Classes;

use InvalidArgumentException;
use function add_action;
use function did_action;
use function register_post_type;
use function ucfirst;
use function strtolower;
use function str_replace;

/**
 * Handles registration of custom post types with GraphQL support.
 * 
 * This class provides a simplified interface for registering custom post types
 * with sensible defaults and built-in GraphQL integration. Features:
 * - Automatic label generation
 * - GraphQL integration
 * - WordPress admin UI customization
 * 
 * Usage example:
 * ```php
 * CustomPostType::register([
 *     'slug' => 'book',
 *     'singular_name' => 'Book',
 *     'plural_name' => 'Books',
 *     'supports' => ['title', 'editor', 'thumbnail'],
 *     'menu_icon' => 'dashicons-book-alt'
 * ]);
 * ```
 * 
 * @package GraphQL_Starter
 * @see https://developer.wordpress.org/reference/functions/register_post_type/
 * @see https://www.wpgraphql.com/docs/custom-post-types
 */
class CustomPostType
{
    /**
     * Registers a custom post type with GraphQL support.
     *
     * @param array $args {
     *     Configuration array for the post type.
     *
     *     @type string $slug            Required. Post type identifier (e.g., 'book')
     *     @type string $singular_name   Optional. Singular name (defaults to formatted slug)
     *     @type string $plural_name     Optional. Plural name (defaults to singular + 's')
     *     @type array  $labels          Optional. Override default labels
     *     @type bool   $public          Optional. Whether post type is public (default: true)
     *     @type array  $supports        Optional. Features supported (default: ['title', 'editor'])
     *     @type bool   $show_in_rest    Optional. Show in REST API (default: true)
     *     @type bool   $show_in_graphql Optional. Show in GraphQL API (default: true)
     *     @type bool   $hierarchical    Optional. Supports parent/child relationships (default: false)
     *     @type bool   $has_archive     Optional. Enables post type archives (default: false)
     *     @type string $menu_icon       Optional. Admin menu icon (default: 'dashicons-admin-post')
     *     @type int    $menu_position   Optional. Admin menu position (default: null)
     * }
     * @throws InvalidArgumentException If required slug parameter is missing
     */
    public static function register(array $args = []): void
    {
        if (empty($args['slug'])) {
            throw new InvalidArgumentException('Post type slug is required');
        }

        $config = self::prepareConfiguration($args);
        self::registerPostType($args['slug'], $config);
    }

    /**
     * Prepares the post type configuration with defaults.
     *
     * Handles:
     * - Label generation
     * - Default settings
     * - GraphQL configuration
     * - WordPress admin settings
     *
     * @param array $args Raw configuration array
     * @return array Complete WordPress post type configuration
     */
    private static function prepareConfiguration(array $args): array
    {
        $slug = $args['slug'];
        $singular_name = $args['singular_name'] ?? ucfirst(str_replace(['-', '_'], ' ', $slug));
        $plural_name = $args['plural_name'] ?? $singular_name . 's';

        $menu_name = $args['labels']['menu_name'] ?? $plural_name;

        $labels = self::generateLabels($singular_name, $plural_name, $menu_name, $args['labels'] ?? []);

        return array_merge([
            'public' => $args['public'] ?? true,
            'labels' => $labels,
            'supports' => $args['supports'] ?? ['title', 'editor'],
            'show_in_rest' => $args['show_in_rest'] ?? true,
            'show_in_graphql' => $args['show_in_graphql'] ?? true,
            'graphql_single_name' => strtolower($singular_name),
            'graphql_plural_name' => strtolower($plural_name),
            'hierarchical' => $args['hierarchical'] ?? false,
            'has_archive' => $args['has_archive'] ?? false,
            'publicly_queryable' => $args['publicly_queryable'] ?? true,
            'menu_icon' => $args['menu_icon'] ?? 'dashicons-admin-post',
            'menu_position' => $args['menu_position'] ?? null,
            'exclude_from_search' => $args['exclude_from_search'] ?? false,
        ], $args);
    }

    /**
     * Generates standardized labels for the post type.
     *
     * Creates a complete set of labels for WordPress admin UI.
     * Custom labels can override any default labels.
     *
     * @param string $singular     Singular name (e.g., 'Book')
     * @param string $plural       Plural name (e.g., 'Books')
     * @param string $menu_name    Admin menu label
     * @param array  $custom_labels Optional custom labels to override defaults
     * @return array Complete labels array for post type registration
     */
    private static function generateLabels(
        string $singular,
        string $plural,
        string $menu_name,
        array $custom_labels = []
    ): array {
        $default_labels = [
            'name' => $plural,
            'singular_name' => $singular,
            'menu_name' => $menu_name,
            'add_new' => "Add New",
            'add_new_item' => "Add New {$singular}",
            'edit_item' => "Edit {$singular}",
            'new_item' => "New {$singular}",
            'view_item' => "View {$singular}",
            'view_items' => "View {$plural}",
            'search_items' => "Search {$plural}",
            'not_found' => "No {$plural} found",
            'not_found_in_trash' => "No {$plural} found in trash",
            'all_items' => "All {$plural}",
            'archives' => "{$singular} Archives",
            'attributes' => "{$singular} Attributes",
        ];

        return array_merge($default_labels, $custom_labels);
    }

    /**
     * Registers the post type with WordPress.
     *
     * Handles proper timing of registration in WordPress lifecycle.
     * If called after 'init', registers immediately; otherwise hooks to 'init'.
     *
     * @param string $slug   Post type identifier
     * @param array  $config Complete post type configuration
     */
    private static function registerPostType(string $slug, array $config): void
    {
        if (did_action('init')) {
            register_post_type($slug, $config);
        } else {
            add_action('init', function () use ($slug, $config) {
                register_post_type($slug, $config);
            });
        }
    }
}
