<?php

use GraphQL_Starter\Classes\CustomField;

/**
 * Register Custom Fields with GraphQL support
 * 
 * This file defines all custom fields used in the theme.
 * Each field is registered with GraphQL support enabled by default.
 * 
 * Read the `../docs/custom-fields.md` file for more information on registering custom fields.
 * 
 * @package GraphQL_Starter
 */

/**
 * Below is an example of how to register a custom field.
 * This assumes that you have already registered the `hello` CPT.
 * 
 * For more examples with different field types,
 * see the `../docs/custom-fields.md` file.
 * 
 * Note: Please enable this feature by setting:
 * `GRAPHQL_STARTER_CUSTOM_FIELDS_ENABLED` constant to true in the `theme.config.php` file.
 */


// Text field with placeholder
CustomField::register([
    'id' => 'greeting',
    'title' => 'Greeting',
    'post_types' => ['hello']
]);
