![GraphQL Starter Logo](https://i.ibb.co/QMrspvp/graphql-starter-logo.png)
<h1>GraphQL Starter</h1>

The **GraphQL Starter Theme** is a specialized headless WordPress theme designed to function purely as a GraphQL API endpoint. It eliminates traditional frontend rendering and focuses solely on providing a clean and efficient GraphQL API interface.

This lightweight and secure solution is ideal for:
- Public content APIs
- Headless frontend applications
- Scenarios where content management is handled through the WordPress admin interface

The theme is **read-only**, ensuring enhanced security and simplicity by disabling mutations/write operations.

[![WordPress](https://img.shields.io/badge/WordPress-6.7.1-blue.svg)](https://wordpress.org/)
[![PHP](https://img.shields.io/badge/PHP-7.4+-purple.svg)](https://php.net/)
[![License](https://img.shields.io/badge/license-GPL--2.0-green.svg)](https://www.gnu.org/licenses/gpl-2.0.html)

---

- [Features](#features)
  - [Core Purpose](#core-purpose)
  - [Custom Post Type and Custom Field Management](#custom-post-type-and-custom-field-management)
  - [GraphQL Capabilities](#graphql-capabilities)
  - [Additional Features](#additional-features)
- [Requirements](#requirements)
- [Installation](#installation)
  - [Prerequisites](#prerequisites)
  - [Steps](#steps)
- [Directory Structure](#directory-structure)
  - [Core Directory](#core-directory)
  - [Docs Directory](#docs-directory)
- [Testing GraphQL Directory](#testing-graphql-directory)
- [Usage](#usage)
  - [Example Query](#example-query)
- [Theme Renaming](#theme-renaming)
  - [Usage](#usage-1)
- [License](#license)
- [Support](#support)
- [Credits](#credits)

## Features

### Core Purpose
- A headless WordPress theme that acts as a **read-only GraphQL API endpoint**
- Focuses purely on data retrieval without supporting mutations or write operations

### Custom Post Type and Custom Field Management
- Robust `CustomPostType` and `CustomField` classes for registering and managing post types
- Built-in GraphQL support for custom post types and fields
- Flexible configuration options for easy customization

### GraphQL Capabilities
- **Read-Only Operations:**
  - Query post types
  - Retrieve posts and their data
  - Access custom fields
- No mutations (create/update/delete)
- No authentication system required (read-only operations)

### Additional Features
- Built-in logging for debugging and monitoring
- Support for administrative operations like post type deletion
- Fully compatible with [WPGraphQL](https://www.wpgraphql.com/)

---

## Requirements
- PHP 7.4 or higher
- WordPress 6.7.1 or later
- WPGraphQL plugin

---

## Installation

### Prerequisites
Ensure that your environment meets the following requirements:
- PHP 7.4 or higher
- WordPress 6.7.1 or later
- WPGraphQL plugin installed and activated

### Steps
1. Download the theme from the [Technway repository](https://github.com/technway/graphql-starter).
2. Upload the theme folder to your WordPress installation directory under `/wp-content/themes/`.
3. Activate the theme via the WordPress admin panel under **Appearance > Themes**.
4. Install and activate the [WPGraphQL plugin](https://www.wpgraphql.com/).

---

## Directory Structure

```
graphql-starter/
├── testing-graphql/                 # Directory for API-related test files
├── includes/                  # Core includes for the theme
├────── core/                  # Core classes for managing CPTs and CFs
│        ├── classes/            # Utility classes
│        │    ├── CustomPostType.php # Class for registering custom post types
│        │    ├── CustomField.php    # Class for registering custom fields
│        ├── custom-fields-bootstrap.php # Bootstrapper for custom fields
│        ├── custom-post-type-bootstrap.php # Bootstrapper for custom post types
│        ├── graphql-setup.php      # GraphQL-specific configurations
├────── custom-fields.php      # File to register custom fields
├────── post-types.php         # File to register custom post types
├── scripts/                   # Utility scripts (e.g., rename-theme.php)
├── docs/                      # Detailed documentation files
├── functions.php              # Main theme functions
├── index.php                  # Empty index file for security
├── LICENSE                    # License file
├── README.md                  # Documentation
├── style.css                  # Theme information and styles
```

### Core Directory
The `core/` directory contains the reusable classes used to register custom post types (CPTs) and custom fields (CFs). These classes simplify the process of creating and managing CPTs and CFs within the theme.

### Docs Directory
The `docs/` directory contains additional detailed documentation files about the theme, its architecture, and usage examples. Refer to [`docs/usage.md`](docs/usage.md) for more information on registering custom post types and custom fields.

## Testing GraphQL Directory
The `testing-graphql/` directory contains a `.http` file for testing GraphQL queries. Refer to [`testing-graphql.md`](testing-graphql/testing-graphql.md) for more information on testing GraphQL queries.

---

## Usage

This theme is designed to provide a GraphQL API endpoint for your WordPress site. It focuses on read-only operations and does not include any frontend rendering. You can use this API to:

- Query posts, custom fields, and other WordPress data
- Integrate with headless frontend frameworks such as React, Vue, or Angular

### Example Query
Use the following query with your WPGraphQL API:
```graphql
{
  posts {
    nodes {
      id
      title
      content
    }
  }
}
```

---

## Theme Renaming
Use the `rename-theme.php` script to rename the theme. The script updates all references across relevant files while preserving formatting.

### Usage
Run the script from the root of the theme directory:
```bash
php ./scripts/rename-theme.php "Your New Theme Name"
```

Example:
```bash
php ./scripts/rename-theme.php "My Custom GraphQL"
```

---

## License
This theme is licensed under the GNU General Public License v2 or later (GPL-2.0). See the [LICENSE](LICENSE) file for details.

---

## Support
For support and contributions, visit the [Technway GitHub repository](https://github.com/technway/graphql-starter).

---

## Credits
The GraphQL Starter Theme is built with ❤️ and maintained by **[Technway](https://technway.biz)**. Special thanks to the creators of [WPGraphQL](https://www.wpgraphql.com/) for their excellent plugin.

Contributions are welcome! Feel free to fork the repository and submit pull requests.
