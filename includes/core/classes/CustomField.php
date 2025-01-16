<?php

namespace GraphQL_Starter\Classes;

use InvalidArgumentException;
use function add_action;
use function add_meta_box;
use function sanitize_text_field;
use function update_post_meta;
use function get_post_meta;
use function register_graphql_field;

/**
 * Custom Field Class
 * 
 * Handles registration and management of custom fields in WordPress.
 * 
 * This class provides a standardized way to:
 * - Register custom fields for post types
 * - Setup meta boxes in the WordPress admin
 * - Handle data saving and validation
 * - Enable GraphQL integration for fields
 * 
 * Usage example:
 * ```php
 * CustomField::register([
 *     'id' => 'my_field',
 *     'title' => 'My Field',
 *     'post_types' => ['post', 'page'],
 *     'input_type' => [
 *         'type' => 'text',
 *         'attributes' => ['placeholder' => 'Enter value...']
 *     ]
 * ]);
 * ```
 * 
 * @see CustomFieldRenderer For field rendering implementation
 * @package GraphQL_Starter
 */
class CustomField
{
    /**
     * Defines configuration for all supported input types.
     * 
     * input types have:
     * - type: Base input type for rendering
     * - attributes: Default HTML attributes
     * - requires_options: Whether the field needs options array
     * - is_multiple: Whether field supports multiple values
     * 
     * @var array<string, array>
     */
    public static $input_configs = [
        'text' => [
            'type' => 'text',
            'attributes' => ['type' => 'text'],
        ],
        'email' => [
            'type' => 'text',
            'attributes' => ['type' => 'email'],
        ],
        'url' => [
            'type' => 'text',
            'attributes' => ['type' => 'url'],
        ],
        'number' => [
            'type' => 'text',
            'attributes' => ['type' => 'number'],
        ],
        'tel' => [
            'type' => 'text',
            'attributes' => ['type' => 'tel'],
        ],
        'date' => [
            'type' => 'text',
            'attributes' => ['type' => 'date'],
        ],
        'textarea' => [
            'type' => 'textarea',
            'attributes' => ['rows' => '5'],
        ],
        'radio' => [
            'type' => 'radio',
            'requires_options' => true,
        ],
        'checkbox' => [
            'type' => 'checkbox',
            'requires_options' => true,
            'is_multiple' => true,
        ],
    ];

    /**
     * Registers a new custom field with WordPress and GraphQL.
     * 
     * @param array $args {
     *     Field configuration arguments.
     * 
     *     @type string       $id              Unique identifier for the field
     *     @type string       $title           Display title in admin
     *     @type array        $post_types      Post types to add field to
     *     @type string       $context         Meta box context (normal|side|advanced)
     *     @type string       $priority        Meta box priority (high|core|default|low)
     *     @type bool         $show_in_graphql Whether to expose in GraphQL API
     *     @type string       $description     Field description for GraphQL
     *     @type array        $input_type {
     *         Input configuration
     *         @type string $type       Input type from $input_configs
     *         @type array  $attributes Additional HTML attributes
     *     }
     *     @type array        $options         Options for radio/checkbox fields
     * }
     * 
     * @param array $args Field configuration array
     * @throws InvalidArgumentException If required args are missing
     */
    public static function register($args)
    {
        $validated_args = self::validateArgs($args);
        self::setupMetaBox($validated_args);
        self::setupSaveHandler($validated_args);
        self::setupGraphQL($validated_args);
    }

    /**
     * Validates and sets defaults for field arguments.
     * 
     * @param array $args Field configuration array
     * @return array Validated args with defaults
     * @throws InvalidArgumentException If required args are missing
     */
    private static function validateArgs($args)
    {
        if (empty($args['id']) || empty($args['title']) || empty($args['post_types'])) {
            throw new InvalidArgumentException('The "id", "title", and "post_types" parameters are required.');
        }

        return array_merge([
            'context' => 'normal',
            'priority' => 'default',
            'show_in_graphql' => true,
            'description' => '',
            'input_type' => [
                'type' => 'text',
                'attributes' => [],
            ],
        ], $args);
    }

    /**
     * Sets up the meta box in WordPress admin.
     * 
     * @param array $args Field configuration
     */
    private static function setupMetaBox($args)
    {
        add_action('add_meta_boxes', function () use ($args) {
            foreach ($args['post_types'] as $post_type) {
                add_meta_box(
                    $args['id'],
                    $args['title'],
                    [self::class, 'renderField'],
                    $post_type,
                    $args['context'],
                    $args['priority'],
                    $args
                );
            }
        });
    }

    /**
     * Sets up the save handler for the field.
     * 
     * Handles:
     * - Data sanitization
     * - Multiple value fields (checkboxes)
     * - Meta data storage
     * 
     * @param array $args Field configuration
     */
    private static function setupSaveHandler($args)
    {
        add_action('save_post', function ($post_id) use ($args) {
            if (!isset($_POST[$args['id']])) {
                return;
            }

            $value = $_POST[$args['id']];
            $is_multiple = $args['input_type']['is_multiple'] ?? false;

            if ($is_multiple) {
                $value = array_map('sanitize_text_field', (array)$value);
            } else {
                $value = sanitize_text_field($value);
            }

            update_post_meta($post_id, $args['id'], $value);
        });
    }

    /**
     * Sets up GraphQL integration for the field.
     * 
     * Makes the field available via GraphQL API when enabled.
     * 
     * @param array $args Field configuration
     */
    private static function setupGraphQL($args)
    {

        if (empty($args['show_in_graphql'])) {
            // error_log('CustomField::setupGraphQL: show_in_graphql is false');
            return;
        }

        add_action('graphql_register_types', function () use ($args) {
            foreach ($args['post_types'] as $post_type) {
                register_graphql_field($post_type, $args['id'], [
                    'type' => 'String',
                    'description' => $args['description'] ?: "A custom field for {$post_type}",
                    'resolve' => fn($post) => get_post_meta($post->ID, $args['id'], true),
                ]);
            }
        });
    }

    /**
     * Renders the field in WordPress admin.
     * 
     * Delegates actual rendering to CustomFieldRenderer.
     * 
     * @param WP_Post  $post    Current post object
     * @param array    $meta_box Meta box arguments
     */
    public static function renderField($post, $meta_box)
    {
        $args = $meta_box['args'];
        $value = get_post_meta($post->ID, $args['id'], true);

        $renderer = new CustomFieldRenderer($args, $value);
        $renderer->render();
    }
}
