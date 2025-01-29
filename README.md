<div align="center">

<img src="https://i.ibb.co/QMrspvp/graphql-starter-logo.png" alt="GraphQL Starter Logo">
<h1>GraphQL Starter</h1>

<p>The <strong>GraphQL Starter Theme</strong> is a specialized headless WordPress theme designed to function purely as a GraphQL API endpoint. It eliminates traditional frontend rendering and focuses solely on providing a clean and efficient GraphQL API interface.</p>

<a href="https://wordpress.org/"><img src="https://img.shields.io/badge/WordPress-6.7-blue.svg" alt="WordPress"></a>
<a href="https://php.net/"><img src="https://img.shields.io/badge/PHP-7.2+-purple.svg" alt="PHP"></a>
<a href="https://www.gnu.org/licenses/gpl-2.0.html"><img src="https://img.shields.io/badge/license-GPL--2.0-green.svg" alt="License"></a>

</div>

---

- [Features](#features)
  - [Core Capabilities](#core-capabilities)
  - [Custom Post Types and Fields](#custom-post-types-and-fields)
  - [GraphQL Capabilities](#graphql-capabilities)
  - [Additional Features](#additional-features)
- [Installation](#installation)
  - [Prerequisites](#prerequisites)
  - [Steps](#steps)
- [Directory Structure](#directory-structure)
  - [`includes/` Directory](#includes-directory)
    - [`core/` Directory](#core-directory)
  - [`docs/` Directory](#docs-directory)
  - [`testing-graphql/` Directory](#testing-graphql-directory)
- [Usage](#usage)
- [Theme Renaming](#theme-renaming)
  - [Script Usage](#script-usage)
- [License](#license)
- [Support](#support)
- [Credits](#credits)


**This lightweight and secure solution is ideal for:**
- Public content APIs
- Headless frontend applications
- Scenarios where content management is handled through the WordPress admin interface

The theme is **read-only**, ensuring enhanced security and simplicity by disabling mutations/write operations.

## Features

### Core Capabilities
- **Headless WordPress API:** Designed to serve as a **read-only GraphQL endpoint** for seamless integration with modern frontend frameworks like React, Vue, or Angular.
- **Focus on Data Retrieval:** Excludes mutations (write operations) to enhance security and simplify implementation.

### Custom Post Types and Fields
- Easy-to-use `CustomPostType` and `CustomField` classes for registering and managing post types and custom fields.
- Automatically integrates custom post types and fields into the GraphQL schema.
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
- Fully compatible with [WPGraphQL](https://wordpress.org/plugins/wp-graphql/)

---

## Installation

### Prerequisites
Ensure that your environment meets the following requirements:
- PHP 7.2 or higher
- WordPress 6.7 or later
- [WPGraphQL](https://wordpress.org/plugins/wp-graphql/) plugin installed and activated

### Steps
1. Download the theme from the [Technway repository](https://github.com/technway/graphql-starter).
2. Upload the theme folder to your WordPress installation directory under `/wp-content/themes/`.
3. Activate the theme via the WordPress admin panel under **Appearance > Themes**.
4. Install and activate the [WPGraphQL plugin](https://wordpress.org/plugins/wp-graphql/).

---

## Directory Structure

```
graphql-starter/                   # Root directory of the theme
├── includes/                      # Core functionalities of the theme
│   ├── core/                      # Essential reusable components and logic
│   │   ├── classes/               # PHP classes for managing CPTs and CFs
│   │   │   ├── CustomField.php          # Class for defining custom fields
│   │   │   ├── CustomFieldRenderer.php  # Class for rendering custom fields
│   │   │   ├── CustomPostType.php       # Class for defining custom post types
│   │   ├── bootstrap/             # Initialization files for CPTs and fields
│   │   │   ├── cf-bootstrap.php   # Initializes custom fields
│   │   │   ├── cpt-bootstrap.php  # Initializes custom post types
│   │   ├── graphql/               # Files specific to GraphQL setup
│   │   │   ├── graphql-setup.php  # Configures GraphQL endpoints and settings
│   ├── custom-fields.php          # Registers and manages custom fields
│   ├── post-types.php             # Registers and manages custom post types
├── scripts/                       # Utility scripts for automation
│   ├── rename-theme.php           # Script to rename theme references
├── testing-graphql/               # GraphQL query test files for debugging
│   ├── example.http               # Example HTTP requests for GraphQL queries
│   ├── testing-graphql.md         # Documentation for testing GraphQL
├── docs/                          # Documentation files for the theme
├── functions.php                  # Main WordPress functions file
├── index.php                      # Empty index file
```

### `includes/` Directory
The `includes/` directory contains the core classes used to register custom post types (CPTs) and custom fields (CFs). These classes simplify the process of creating and managing CPTs and CFs within the theme. It also contains the `custom-fields.php` and `post-types.php` files, which are used to register custom fields and post types respectively.

#### `core/` Directory
The `core/` directory contains the reusable classes used to register custom post types (CPTs) and custom fields (CFs). These classes simplify the process of creating and managing CPTs and CFs within the theme.

### `docs/` Directory
The `docs/` directory contains additional detailed documentation files about the theme, its architecture, and usage examples. Refer to [`docs/usage.md`](docs/usage.md) for more information on registering custom post types and custom fields.

### `testing-graphql/` Directory
The `testing-graphql/` directory contains a `.http` file for testing GraphQL queries. Refer to [`testing-graphql.md`](testing-graphql/testing-graphql.md) for more information on testing GraphQL queries.

---

## Usage

- Read the [`custom-post-types.md`](docs/custom-post-types.md) file for more information on registering custom post types.
- Read the [`custom-fields.md`](docs/custom-fields.md) file for more information on registering custom fields.

---

## Theme Renaming
Use the `rename-theme.php` script to rename the theme. The script updates all references across relevant files while preserving formatting.

### Script Usage
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
