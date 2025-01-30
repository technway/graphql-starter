
<h1>Custom Fields</h1>

Custom fields allow you to add metadata to your posts. The `CustomField` class in the theme makes it simple to create and manage custom fields for any post type.
This feature is disabled by default. To enable it, set the `GRAPHQL_STARTER_ENABLE_CUSTOM_FIELDS` constant to true in the `config.php` file.

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

> Note: Please enable this feature by setting the `GRAPHQL_STARTER_ENABLE_CUSTOM_FIELDS` constant to true in the `config.php` file.

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
