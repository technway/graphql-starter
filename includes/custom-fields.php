<?php

use GraphQL_Starter\Classes\CustomField;

/**
 * Register Custom Fields with GraphQL support
 * 
 * This file defines all custom fields used in the theme.
 * Each field is registered with GraphQL support enabled by default.
 * 
 * @package GraphQL_Starter
 */

/**
 * Below are some examples of how to register custom fields.
 * 
 * This assumes that you have already registered these post types: hello, event, project
 */

// Text field with placeholder
CustomField::register([
    'id' => 'greeting',
    'title' => 'Greeting',
    'post_types' => ['hello']
]);

// Email input with validation
CustomField::register([
    'id' => 'my_email',
    'title' => 'Email Field',
    'post_types' => ['post', 'event'],
    'input_type' => [
        'type' => 'email',
        'attributes' => [
            'placeholder' => 'email@example.com',
        ],
    ],
]);

// Number input with constraints
CustomField::register([
    'id' => 'my_number',
    'title' => 'Number Field',
    'post_types' => ['post', 'event'],
    'input_type' => [
        'type' => 'number',
        'attributes' => [
            'min' => '0',
            'max' => '100',
            'step' => '5',
            'placeholder' => 'Enter a number',
        ],
    ],
]);

// Rating field
CustomField::register([
    'id' => 'rating',
    'title' => 'Rating',
    'post_types' => ['event', 'project'],
]);

// Textarea example
CustomField::register([
    'id' => 'description',
    'title' => 'Long Description',
    'post_types' => ['event'],
    'input_type' => [
        'type' => 'textarea',
        'attributes' => [
            'rows' => '6',
            'placeholder' => 'Enter a detailed description...',
        ],
    ],
]);

// Checkbox field example
CustomField::register([
    'id' => 'event_type',
    'title' => 'Event Type',
    'post_types' => ['event'],
    'input_type' => [
        'type' => 'checkbox',
        'requires_options' => true,
        'is_multiple' => true,
    ],
    'options' => [
        'online' => 'Online',
        'in-person' => 'In-Person',
        'hybrid' => 'Hybrid',
    ],
]);

// Radio field example
CustomField::register([
    'id' => 'difficulty',
    'title' => 'Event Difficulty',
    'post_types' => ['event'],
    'input_type' => [
        'type' => 'radio',
        'requires_options' => true,
    ],
    'options' => [
        'easy' => 'Easy',
        'medium' => 'Medium',
        'hard' => 'Hard',
    ],
]);

// URL input example
CustomField::register([
    'id' => 'website',
    'title' => 'Website URL',
    'post_types' => ['event'],
    'input_type' => [
        'type' => 'url',
        'attributes' => [
            'placeholder' => 'https://example.com',
        ],
    ],
]);

// Date input example
CustomField::register([
    'id' => 'event_date',
    'title' => 'Event Date',
    'post_types' => ['event'],
    'input_type' => [
        'type' => 'date',
    ],
]);
