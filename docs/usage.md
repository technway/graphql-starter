# Usage Guide

This document provides a detailed overview of how to use the custom fields and custom post types functionality in the **GraphQL Starter** theme.

- [Usage Guide](#usage-guide)
  - [Custom Post Types](#custom-post-types)
    - [Overview](#overview)
    - [Registering Custom Post Types](#registering-custom-post-types)
      - [Example 1: Basic Post Type](#example-1-basic-post-type)
      - [Example 2: Post Type with Custom Settings](#example-2-post-type-with-custom-settings)
      - [Default Behavior](#default-behavior)
      - [Available Configuration Options](#available-configuration-options)
      - [Option Details](#option-details)
        - [Basic Settings](#basic-settings)
        - [Features Support](#features-support)
        - [Admin UI Options](#admin-ui-options)
        - [Visibility Settings](#visibility-settings)
        - [API Integration](#api-integration)
  - [Custom Fields](#custom-fields)
    - [Overview](#overview-1)
    - [Required Parameters](#required-parameters)
    - [Optional Parameters](#optional-parameters)
    - [Input Types](#input-types)
      - [1. Text Input (Default)](#1-text-input-default)
      - [2. Email Input](#2-email-input)
      - [3. URL Input](#3-url-input)
      - [4. Number Input](#4-number-input)
      - [5. Telephone Input](#5-telephone-input)
      - [6. Date Input](#6-date-input)
      - [7. Textarea](#7-textarea)
      - [8. Radio Buttons](#8-radio-buttons)
      - [9. Checkboxes (Multiple Selection)](#9-checkboxes-multiple-selection)
    - [GraphQL Integration](#graphql-integration)
    - [Best Practices](#best-practices)
    - [Error Handling](#error-handling)

---

## Custom Post Types

### Overview
Custom Post Types (CPTs) allow you to extend WordPress by adding new content types beyond the default ones (Posts, Pages, etc.). With the **GraphQL Starter** theme, you can register CPTs easily using the built-in `CustomPostType` class.

### Registering Custom Post Types

#### Example 1: Basic Post Type
```php
CustomPostType::register(['slug' => 'project']);
```
This creates a custom post type with the slug `project`. When only providing the slug:
- The singular name will be "Project" (capitalized slug)
- The plural name will be "Projects" (singular + 's')
- GraphQL names will be lowercase: "project" and "projects"
- Menu name will default to the plural name
- All other settings will use sensible defaults for a typical post type

#### Example 2: Post Type with Custom Settings
```php
CustomPostType::register([
    'slug' => 'event',
    'singular_name' => 'Event',    // Override the auto-generated singular name
    'plural_name' => 'Events',     // Override the auto-generated plural name
    'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
    'menu_icon' => 'dashicons-calendar',
    'menu_position' => 7,
    'has_archive' => true,
]);
```
This registers a post type with custom settings that override the automatic defaults.

#### Default Behavior
The `CustomPostType` class will automatically:
- Format the slug for display (e.g., 'team_member' becomes 'Team Member')
- Generate plural forms (adding 's' to singular name)
- Set up GraphQL integration
- Configure standard WordPress labels
- Enable common features like title and editor support

You only need to specify custom settings when you want to override these defaults.

#### Available Configuration Options
When registering a custom post type, you can use the following options:

```php
CustomPostType::register([
    // Required
    'slug' => 'book',              // String: Unique identifier for the post type
    
    // Basic Settings
    'singular_name' => 'Book',     // String: Singular display name
    'plural_name' => 'Books',      // String: Plural display name
    'public' => true,              // Boolean: Whether the post type is publicly accessible
    'has_archive' => false,        // Boolean: Whether the post type has archive pages
    'hierarchical' => false,       // Boolean: Whether posts can have parent/child relationships
    
    // Features Support
    'supports' => [                // Array: Post type features to enable
        'title',                   // Post title
        'editor',                  // Content editor
        'thumbnail',               // Featured image
        'excerpt',                 // Post excerpt
        'author',                  // Author selection
        'comments',                // Comments
        'revisions',              // Revision history
        'custom-fields',          // Custom fields
        'page-attributes',        // Page attributes (menu order, etc.)
    ],
    
    // Admin UI
    'menu_icon' => 'dashicons-book-alt',  // String: Dashicon or URL for admin menu icon
    'menu_position' => 5,         // Integer: Position in admin menu (5-100)
    
    // Visibility & Access
    'publicly_queryable' => true,  // Boolean: Whether queries can be performed on the front end
    'exclude_from_search' => false, // Boolean: Whether to exclude from search results
    
    // REST API & GraphQL
    'show_in_rest' => true,        // Boolean: Whether to show in REST API. Recommended to be true when editor is enabled.
    'show_in_graphql' => true,     // Boolean: Whether to show in GraphQL API
    'graphql_single_name' => 'book',  // String: GraphQL singular name
    'graphql_plural_name' => 'books',  // String: GraphQL plural name
    
    // Custom Labels
    'labels' => [                  // Array: Custom labels for various UI text
        'menu_name' => 'Books',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Book',
        'edit_item' => 'Edit Book',
        'new_item' => 'New Book',
        'view_item' => 'View Book',
        'view_items' => 'View Books',
        'search_items' => 'Search Books',
        'not_found' => 'No books found',
        'not_found_in_trash' => 'No books found in trash',
        'all_items' => 'All Books',
        'archives' => 'Book Archives',
        'attributes' => 'Book Attributes',
        // ... see WordPress documentation for all available labels
    ],
]);
```

#### Option Details

##### Basic Settings
- `slug`: (Required) The post type identifier. Should be lowercase, no spaces.
- `singular_name`: Human-readable singular name (auto-generated from slug if not provided).
- `plural_name`: Human-readable plural name (auto-generated from singular name if not provided).
- `public`: Whether the post type should be publicly accessible. Affects multiple other settings.
- `has_archive`: Whether there should be post type archive pages.
- `hierarchical`: Enables parent/child relationships (like Pages).

##### Features Support
The `supports` array can include any of these values:
- `title`: Enable title field
- `editor`: Enable content editor
- `thumbnail`: Enable featured image
- `excerpt`: Enable excerpt field
- `author`: Enable author selection
- `comments`: Enable comments
- `revisions`: Enable revision tracking
- `custom-fields`: Enable custom fields
- `page-attributes`: Enable menu order and page parent options

##### Admin UI Options
- `menu_icon`: Dashicons helper class or URL to custom icon
- `menu_position`: Where menu appears in admin sidebar (5-100)

##### Visibility Settings
- `publicly_queryable`: Whether posts are queryable on the front end
- `exclude_from_search`: Whether to exclude from search results

##### API Integration
- `show_in_rest`: Defaults to `true`.
- `show_in_graphql`: Enable in GraphQL API (defaults to `true`)
- `graphql_single_name`: GraphQL singular name (defaults to lowercase singular_name)
- `graphql_plural_name`: GraphQL plural name (defaults to lowercase plural_name)

---

## Custom Fields

### Overview
Custom fields allow you to add metadata to your posts. The `CustomField` class in the theme makes it simple to create and manage custom fields for any post type.

### Required Parameters
When registering a custom field, the following parameters are required:

```php
CustomField::register([
    'id' => 'field_id',         // Unique identifier for the field
    'title' => 'Field Title',   // Display title in admin
    'post_types' => ['post'],   // Array of post types to add this field to
]);
```

### Optional Parameters
```php
CustomField::register([
    // Optional parameters with their defaults
    'context' => 'normal',      // Where the field appears: 'normal', 'side', or 'advanced'
    'priority' => 'default',    // Display priority: 'high', 'core', 'default', or 'low'
    'show_in_graphql' => true,  // Whether to expose in GraphQL
    'description' => '',        // Field description (used in GraphQL docs)
]);
```

### Input Types

#### 1. Text Input (Default)
```php
CustomField::register([
    'id' => 'text_field',
    'title' => 'Text Field',
    'post_types' => ['post'],
    'input_type' => [
        'type' => 'text',
        'attributes' => [
            'placeholder' => 'Enter text...',
            'maxlength' => '100',
            // Any valid HTML input attributes
        ],
    ],
]);
```

#### 2. Email Input
```php
CustomField::register([
    'id' => 'email_field',
    'title' => 'Email Address',
    'post_types' => ['contact'],
    'input_type' => [
        'type' => 'email',
        'attributes' => [
            'placeholder' => 'email@example.com',
        ],
    ],
]);
```

#### 3. URL Input
```php
CustomField::register([
    'id' => 'website',
    'title' => 'Website URL',
    'post_types' => ['profile'],
    'input_type' => [
        'type' => 'url',
        'attributes' => [
            'placeholder' => 'https://',
        ],
    ],
]);
```

#### 4. Number Input
```php
CustomField::register([
    'id' => 'quantity',
    'title' => 'Quantity',
    'post_types' => ['product'],
    'input_type' => [
        'type' => 'number',
        'attributes' => [
            'min' => '0',
            'max' => '100',
            'step' => '1',
        ],
    ],
]);
```

#### 5. Telephone Input
```php
CustomField::register([
    'id' => 'phone',
    'title' => 'Phone Number',
    'post_types' => ['contact'],
    'input_type' => [
        'type' => 'tel',
        'attributes' => [
            'pattern' => '[0-9]{3}-[0-9]{3}-[0-9]{4}',
            'placeholder' => '123-456-7890',
        ],
    ],
]);
```

#### 6. Date Input
```php
CustomField::register([
    'id' => 'event_date',
    'title' => 'Event Date',
    'post_types' => ['event'],
    'input_type' => [
        'type' => 'date',
        'attributes' => [
            'min' => '2024-01-01',
            'max' => '2024-12-31',
        ],
    ],
]);
```

#### 7. Textarea
```php
CustomField::register([
    'id' => 'description',
    'title' => 'Description',
    'post_types' => ['product'],
    'input_type' => [
        'type' => 'textarea',
        'attributes' => [
            'rows' => '5',
            'placeholder' => 'Enter detailed description...',
        ],
    ],
]);
```

#### 8. Radio Buttons
```php
CustomField::register([
    'id' => 'size',
    'title' => 'Size',
    'post_types' => ['product'],
    'input_type' => [
        'type' => 'radio',
        'requires_options' => true,
    ],
    'options' => [
        'small' => 'Small',
        'medium' => 'Medium',
        'large' => 'Large',
    ],
]);
```

#### 9. Checkboxes (Multiple Selection)
```php
CustomField::register([
    'id' => 'features',
    'title' => 'Features',
    'post_types' => ['product'],
    'input_type' => [
        'type' => 'checkbox',
        'requires_options' => true,
        'is_multiple' => true,
    ],
    'options' => [
        'wifi' => 'WiFi',
        'bluetooth' => 'Bluetooth',
        'gps' => 'GPS',
    ],
]);
```

### GraphQL Integration

All custom post types and fields registered using the `CustomPostType` and `CustomField` classes are automatically exposed in the WPGraphQL schema. This allows you to query these custom post types and their associated metadata using GraphQL.

Example Query:
```graphql
{
  products {
    nodes {
      title
      customFieldSize    
      customFieldFeatures
    }
  }
}
```

### Best Practices

1. **Use Meaningful Slugs:** Ensure the slugs for CPTs and custom fields are descriptive and unique to avoid conflicts.
2. **Keep it Organized:** Register all custom post types and fields in the `custom-post-types.php` and `custom-fields.php` files, respectively.
3. **Test Your Queries:** Use the WPGraphQL Explorer to test your queries and ensure the schema is as expected.
4. **Validation**: Use appropriate input types for built-in validation (email, url, tel, etc.)
5. **GraphQL Considerations**: Consider whether fields should be exposed in GraphQL

### Error Handling

The CustomField class will throw an `InvalidArgumentException` if:
- Required parameters are missing
- Invalid input types are specified
- Options are missing for radio/checkbox fields

---

For further details, refer to the main [GraphQL Starter Documentation](README.md).
