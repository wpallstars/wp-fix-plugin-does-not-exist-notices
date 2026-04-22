# Folder Structure

This document outlines the folder structure of the plugin and explains the purpose of each directory.

## Root Directories

- **admin/** - Contains admin-specific functionality and assets
- **includes/** - Contains core plugin functionality and classes
- **languages/** - Contains translation files
- **scripts/** - Contains build and deployment scripts
- **.ai-workflows/** - Contains documentation for AI assistants
- **.github/** - Contains GitHub-specific files like workflows
- **.wordpress-org/** - Contains WordPress.org assets like banners and screenshots

## Admin Directory Structure

- **admin/css/** - Admin-specific CSS files
- **admin/js/** - Admin-specific JavaScript files
- **admin/lib/** - Admin-specific library files and helper functions
  - **admin/lib/admin.php** - Admin class for handling admin-specific functionality
  - **admin/lib/modal.php** - Modal class for handling the update source selector modal

## Includes Directory

The `includes/` directory contains the core plugin functionality:

- **includes/Core.php** - Core class for handling the main plugin functionality
- **includes/Plugin.php** - Main plugin class that initializes all components
- **includes/Updater.php** - Updater class for handling plugin updates

## File Naming Conventions

- PHP class files in the `includes/` directory use PascalCase filenames matching the class name (e.g., `Core.php`, `Plugin.php`, `Updater.php`)
- PHP files in `admin/lib/` use lowercase filenames (e.g., `admin.php`, `modal.php`)
- All directories use lowercase names
- JavaScript and CSS files use kebab-case (e.g., `update-source-selector.js`)

## Best Practices

1. **Unique Directory Names**: Each directory should have a unique name to avoid confusion
2. **Logical Organization**: Files should be organized logically by function
3. **Consistent Naming**: File and directory names should follow consistent naming conventions
4. **Clear Separation**: Admin functionality should be separate from core functionality
5. **Minimal Dependencies**: Files should have minimal dependencies on other files

## @mentions for AI Assistants

When referring to files or directories in AI conversations, use the following format:

- **@includes/Plugin.php** - Main plugin class
- **@includes/Core.php** - Core functionality
- **@admin/lib/admin.php** - Admin functionality
- **@admin/lib/modal.php** - Modal functionality
- **@includes/Updater.php** - Updater functionality
- **@admin/js/update-source-selector.js** - Update source selector JavaScript
- **@admin/css/update-source-selector.css** - Update source selector CSS
