<h1>Custom Post Types</h1>

Custom Post Types (CPTs) allow you to extend WordPress by adding new content types beyond the default ones (Posts, Pages, etc.). With the **GraphQL Starter** theme, you can register CPTs easily using the built-in `CustomPostType` class.

This feature is disabled by default. To enable it, set the `GRAPHQL_STARTER_CUSTOM_POST_TYPES_ENABLED` constant to true in the `theme.config.php` file.


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

> Note: Please enable this feature by setting the `GRAPHQL_STARTER_CUSTOM_POST_TYPES_ENABLED` constant to true in the `theme.config.php` file.

## Registering Custom Post Types

### Example 1: Basic Post Type
```php
CustomPostType::register(['slug' => 'project']);
```
This creates a custom post type with the slug `project`. When only providing the slug:
- The singular name will be "Project" (capitalized slug)
- The plural name will be "Projects" (singular + 's')
- GraphQL names will be lowercase: "project" and "projects"
- Menu name will default to the plural name
- All other settings will use sensible defaults for a typical post type

### Example 2: Post Type with Custom Settings
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

### Default Behavior
The `CustomPostType` class will automatically:
- Format the slug for display (e.g., 'team_member' becomes 'Team Member')
- Generate plural forms (adding 's' to singular name)
- Set up GraphQL integration
- Configure standard WordPress labels
- Enable common features like title and editor support

You only need to specify custom settings when you want to override these defaults.

### Available Configuration Options
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

### Option Details

#### Basic Settings
- `slug`: (Required) The post type identifier. Should be lowercase, no spaces.
- `singular_name`: Human-readable singular name (auto-generated from slug if not provided).
- `plural_name`: Human-readable plural name (auto-generated from singular name if not provided).
- `public`: Whether the post type should be publicly accessible. Affects multiple other settings.
- `has_archive`: Whether there should be post type archive pages.
- `hierarchical`: Enables parent/child relationships (like Pages).

#### Features Support
The `supports` array can include any of these values:
- `title`: Enable title field
- `editor`: Enable content editor
- `thumbnail`: Enable featured image
- `excerpt`: Enable excerpt field
- `author`: Enable author selection
- `custom-fields`: Enable custom fields
- `page-attributes`: Enable menu order and page parent options

#### Admin UI Options
- `menu_icon`: Dashicons helper class or URL to custom icon
- `menu_position`: Where menu appears in admin sidebar (5-100)

#### Visibility Settings
- `publicly_queryable`: Whether posts are queryable on the front end
- `exclude_from_search`: Whether to exclude from search results

#### API Integration
- `show_in_rest`: Defaults to `true`.
- `show_in_graphql`: Enable in GraphQL API (defaults to `true`)
- `graphql_single_name`: GraphQL singular name (defaults to lowercase singular_name)
- `graphql_plural_name`: GraphQL plural name (defaults to lowercase plural_name)