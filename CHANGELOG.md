All notable changes to this project should be documented both here and in the main Readme files.

#### [3.0.0] - 2026-10-03
#### Changed
- Rewritten as one small class (`includes/Plugin.php`) that runs only on the Plugins screen, for people who can manage plugins. No settings, nothing stored, no outside requests, no scripts or styles.
- Explains WordPress's "Plugin file does not exist" message right below it: which plugins WordPress could not find, that it has switched them off, and what to do next.
- Requires WordPress 6.2 and PHP 7.4.

#### Added
- Removes leftover entries for deleted plugins from `recently_activated` and `uninstall_plugins`, which WordPress never clears.

#### Removed
- The "Remove Notice" link and the "(File Missing)" rows. WordPress switches missing plugins off on the same page load (`validate_active_plugins()` runs after the plugin list is built), so the link always reported "Failed to remove plugin reference". The rows also caused an "Undefined index: DomainPath" notice.
- The update source chooser (`fpden_update_source` option, deleted on the first visit to the Plugins screen), the bundled updater and the `Update URI` and Gitea headers. Updates come from the `GitHub Plugin URI` header (Git Updater or SEO Pro Stack's Updates from GitHub).
- Code that deleted the update caches of every plugin, theme and core and flushed the whole object cache on each visit to the Plugins screen.
- The plugin details override, which showed made-up ratings and install counts.
- Admin scripts and styles, Composer runtime dependencies, AI tool config files, the unused wiki and AI workflow docs, and the WordPress.org deploy job (the plugin is not on WordPress.org).

#### [2.4.0] - 2025-04-17
#### Added
- Comprehensive documentation for working in multi-repository workspaces
- Guidelines to prevent feature hallucination in multi-repo environments
- New .ai-workflows/multi-repo-workspace.md file with detailed best practices

#### Improved
- AI assistant documentation with repository context verification
- Wiki documentation to accurately reflect plugin functionality

#### [2.3.1] - 2025-04-16
#### Updated
- Tested compatibility with WordPress 6.8
- Documentation to reflect WordPress 6.8 compatibility

#### [2.3.0] - 2025-04-15
#### Added
- Improved time-efficient development workflow documentation
- New git workflow guidelines for better branch management
- Comprehensive incremental development approach

#### Improved
- Documentation for local vs. remote testing
- Version management process for more efficient development

#### [2.2.5] - 2025-04-14
#### Added
- Documentation for the developer preferences memory file in .ai-workflows/
- Improved AI assistant instructions for maintaining developer preferences
- Updated version numbers across all files for consistency

#### [2.2.4] - 2025-04-14
#### Added
- Developer preferences memory file for AI assistants
- Improved AI assistant documentation with instructions for maintaining developer preferences
- Updated WordPress.org documentation with correct plugin slug and version numbers

#### [2.2.3] - 2025-04-14
#### Changed
- Moved admin-specific files to admin/lib directory for better organization
- Updated namespaces to reflect the new file locations
- Added comprehensive folder structure documentation
- Fixed file references in the main plugin file

#### [2.2.2-stable] - 2025-04-14
#### Changed
- Renamed includes files to lowercase for consistency with the rest of the codebase
- Removed redundant Git Updater patches and version-fix.js as they're no longer needed
- Improved documentation for Git Updater integration and release process
- Fixed token-efficient documentation with references to .ai-workflows files
- Added comprehensive release process documentation with emphasis on merging to main branch

#### [2.2.1] - 2025-04-14
#### Changed
- Commented out version-fix.js script as it's no longer needed after refactoring
- Fixed version consistency across all files

#### [2.2.0] - 2025-04-14
#### Added
- Completely refactored plugin to use OOP best practices
- New class structure with proper namespaces
- Improved code organization and maintainability
- Better separation of concerns with dedicated classes

#### Changed
- Changed "Choose Update Source" link to just "Update Source"
- Fixed close button in the update source modal
- Added links to the main page for each update source in the modal
- Replaced all instances of "WP ALLSTARS" with "WPALLSTARS"

#### [2.1.1] - 2025-04-13
#### Added
- New "Choose Update Source" feature allowing users to select their preferred update source (WordPress.org, GitHub, or Gitea)
- Modal dialog with detailed information about each update source option
- Visual indicator showing the currently selected update source

#### Fixed
- Updated heading styles in CHANGELOG.md for better readability
- Corrected dates in changelog to use 2025 instead of 2024

#### Improved
- Documentation improvements for better clarity
- Enhanced Git Updater integration with user-selectable update sources

#### [2.1.0] - 2025-04-13
#### Changed
- Minor version bump for Git Updater compatibility

#### Improved
- Error handling for Git Updater integration
- Type checking in branch fix functions
- Documentation for Git Updater installation and cache refreshing

#### [2.0.13] - 2025-04-12
#### Fixed
- Critical error when Git Updater passes an object instead of a string to API URL filter
- Type checking in branch fix functions to handle both string and object inputs

#### Improved
- Error handling for Git Updater integration
- Documentation for Git Updater installation and cache refreshing

#### [2.0.12] - 2025-04-11
#### Fixed
- Integrated Git Updater branch fix directly into main plugin
- Removed separate "GU Branch Fix" plugin (no longer needed)

#### Added
- Documentation explaining branch fix integration

#### Improved
- Compatibility with Git Updater plugin
- Deploy script to remove separate branch fix plugin

#### [2.0.11] - 2025-04-10
#### Added
- Created separate "GU Branch Fix" plugin to fix Git Updater branch issues
- Added deploy script for local testing

#### Fixed
- Git Updater branch issues with 'main' vs 'master' branch names
- Improved compatibility with Git Updater plugin

#### [2.0.10] - 2025-04-09
#### Fixed
- Plugin details popup version display issue with Git Updater integration
- Added JavaScript-based solution to ensure correct version display in plugin details

#### Improved
- Version consistency across all plugin views
- Enhanced cache busting for plugin information API

#### [2.0.9] - 2025-04-08
#### Fixed
- Plugin details popup now correctly shows version and author information
- Added support for both old and new plugin slugs to fix caching issues

#### Improved
- Cache clearing mechanism to ensure plugin details are always up-to-date
- Enhanced version display in plugin details popup

#### [2.0.8] - 2025-04-07
#### Fixed
- Plugin details popup now correctly shows version and author information
- Added cache-busting mechanism to ensure plugin details are always up-to-date

#### Improved
- Author and contributor information display in plugin details
- Added WordPress 6.5 compatibility indicator

#### [2.0.7] - 2025-04-06
#### Changed
- Additional text improvements and minor fixes

#### [2.0.6] - 2025-04-05
#### Changed
- Text improvements and minor fixes

#### [2.0.5] - 2025-04-04
#### Fixed
- Display correct version in plugin details popup

#### [2.0.4] - 2025-04-03
#### Fixed
- Display actual plugin version instead of 'N/A' for missing plugins in plugin details view

#### [2.0.2] - 2025-04-02
#### Changed
- Consolidated WordPress.org assets into .wordpress-org directory
- Improved organization of assets for WordPress.org submission
- Updated .wordpress-org/README.md with comprehensive information

#### [2.0.1] - 2025-04-01
#### Added
- Contributing section to readme.txt
- reference-plugins directory for plugin development inspiration

#### Changed
- Updated "tested up to" version to WordPress 6.7.2
- Improved .gitattributes with comprehensive file handling
- Enhanced documentation organization

#### [2.0.0] - 2025-03-31
#### Added
- Note clarifying that the plugin has no settings page
- Clarification that functionality is limited to the admin plugins page only

#### Changed
- Major version release for WordPress.org submission
- Finalized all assets and documentation for public release

#### [1.6.28] - 2025-03-30
#### Changed
- Consolidated asset documentation into a single comprehensive guide
- Clarified that `-rtl` suffix is for Right-to-Left languages, not dark mode
- Updated all asset README files to point to the main documentation
- Improved file organization and documentation structure

#### [1.6.27] - 2025-03-29
#### Changed
- Clarified RTL support in WordPress.org asset documentation
- Corrected information about `-rtl` suffix for assets (for right-to-left languages, not dark themes)
- Consolidated asset files and improved documentation

#### [1.6.26] - 2025-03-28
#### Changed
- Updated screenshot references to use a single comprehensive screenshot
- Fixed asset directory paths in documentation
- Improved ImageMagick conversion commands for icon generation
- Clarified WordPress.org SVN repository structure

#### [1.6.25] - 2025-03-27
#### Added
- Comprehensive WordPress.org submission guide with SVN structure explanation

#### Changed
- Consolidated asset files into their respective directories
- Clarified asset requirements and submission process
- Improved organization of asset files

#### [1.6.24] - 2025-03-26
#### Added
- Properly named icon PNG files (icon-256x256.png and icon-128x128.png)

#### Changed
- Updated asset preparation documentation
- Improved WordPress.org submission readiness

#### [1.6.23] - 2025-03-25
#### Added
- Properly named icon, banner, and screenshot files for WordPress.org submission
- Comprehensive guide for WordPress.org asset requirements

#### Changed
- Fixed file naming to comply with WordPress.org standards
- Organized assets in separate directories for better management

#### [1.6.22] - 2025-03-24
#### Changed
- Enhanced support section with multiple support channel options
- Added encouragement for users to leave reviews on WordPress.org
- Improved documentation with clearer support instructions

#### [1.6.21] - 2025-03-23
#### Added
- Support for more AI-powered development tools (Roo, Gemini, Loveable, Bolt, Cody, Continue)
- Links to supported AI IDEs in documentation
- Additional FAQs for better SEO

#### Changed
- Updated AI configuration files with modern models (gpt-4o)
- Made documentation more generic for boilerplate use
- Enhanced explanations in configuration files
- Added more keywords for better SEO

#### [1.6.20] - 2025-03-22
#### Changed
- Added explanations about the ! prefix in ignore files
- Moved WordPress-specific patterns to .gitignore
- Further improved organization of ignore patterns
- Added examples of how to include files excluded by .gitignore

#### [1.6.19] - 2025-03-21
#### Changed
- Consolidated common ignore patterns into .gitignore
- Simplified AI IDE configuration files to only include tool-specific patterns
- Improved organization of ignore patterns for better maintainability
- Added more file types to .gitignore for comprehensive coverage

#### [1.6.18] - 2025-03-20
#### Changed
- Optimized AI IDE configuration files to only include patterns not in .gitignore
- Improved efficiency of ignore files for better AI context management
- Enhanced compatibility with various AI-powered development tools

#### [1.6.17] - 2025-03-19
#### Added
- .augmentignore file with best practices
- Configuration files for Cursor, Windsurf, v0, and Cline IDEs
- More design file formats to .gitignore
- .aiconfig file for general AI IDE compatibility

#### [1.6.16] - 2025-03-18
#### Fixed
- Updated CI configuration files with correct plugin slugs
- Updated CHANGELOG.md with all recent versions
- Fixed outdated references to plugin-reference-cleaner

#### [1.6.15] - 2025-03-17
#### Changed
- Moved AI workflow documentation to root directory for better visibility
- Consolidated duplicate workflow files
- Improved organization of development documentation
- Updated CI configuration files with correct plugin slugs

#### [1.6.14] - 2025-03-16
#### Changed
- Updated documentation to consistently use "Remove Notice" instead of "Remove Reference"
- Added design file extensions to .gitignore (.pxd, .afdesign, .afphoto, .afpub)
- Improved explanation of how the plugin works
- Ensured consistent terminology across all documentation

#### [1.6.13] - 2025-03-15
#### Changed
- Code cleanup and optimization
- Improved Git Updater integration
- Fixed author display in plugin information
- Ensured compatibility with WordPress 6.4

#### [1.6.12] - 2025-03-14
#### Added
- Added WPALLSTARS as a co-author
- Updated author information and links
- Added author websites to plugin description
- Fixed issue with multiple author URLs

#### [1.6.11] - 2025-03-13
#### Fixed
- Improved Git Updater integration
- Fixed plugin header information
- Updated author information

#### [1.6.10] - 2025-03-12
#### Fixed
- Corrected plugin header information
- Improved Git Updater compatibility
- Updated documentation

#### [1.6.9] - 2025-03-11
#### Fixed
- Fixed Git Updater integration
- Updated plugin header information
- Improved documentation

#### [1.6.8] - 2025-03-10
#### Fixed
- Fixed Git Updater integration
- Updated plugin header information

#### [1.6.7] - 2025-03-09
#### Fixed
- Fixed Git Updater integration
- Updated plugin header information

#### [1.6.6] - 2025-03-08
#### Fixed
- Fixed Git Updater integration
- Updated plugin header information

#### [1.6.5] - 2025-03-07
#### Fixed
- Fixed Git Updater integration
- Updated plugin header information

#### [1.6.4] - 2025-03-06
#### Improved
- Version management to ensure consistent patch version increments
- Documentation for version update process
- AI workflow files with detailed version increment instructions

#### [1.6.3] - 2025-03-05
#### Fixed
- Git Updater repository URLs to use full repository paths
- Update URI configuration for proper update detection
- Version management following semantic versioning

#### Changed
- Updated organization name from 'WP All Stars' to 'WPALLSTARS'
- Updated namespace from 'WPAllStars' to 'WPALLSTARS'

#### [1.6.2] - 2025-03-04
#### Changed
- Updated POT file version for consistency
- Improved JavaScript localization with proper fallbacks
- Enhanced code quality for WordPress.org submission
#### Added
- Git Updater configuration with Update URI
- Update server URL configuration

#### [1.6.1] - 2025-03-03
#### Added
- AI assistant guide and workflow documentation
- Detailed release process documentation
- Feature development guidelines
- Bug fixing procedures
- Code review standards

#### [1.6.0] - 2025-03-02
#### Added
- Full translation support with POT file
- JavaScript localization for better multilingual support
- Plugin constants for improved code organization
- Git Updater support for updates from GitHub and Gitea

#### Changed
- Updated code to follow WordPress internationalization best practices
- Improved asset loading with version constants
- Smart update detection based on installation source

#### [1.5.0] - 2025-03-01
#### Added
- Improved compatibility with WordPress 6.4
- Enhanced error detection for plugin references

#### Fixed
- Minor UI improvements for better visibility
- Accessibility enhancements for screen readers

#### [1.4.1] - 2025-02-28
#### Added
- FAQ about keeping the plugin installed after notices are cleared

#### [1.4.0] - 2025-02-27
#### Changed
- Updated plugin name and text domain
- Repository rename from plugin-reference-cleaner to wp-fix-plugin-does-not-exist-notices

#### [1.3.3] - 2025-02-26
#### Added
- "Click here to scroll" button to automatically find missing plugins
- Visual arrow pointing from notification to error message
- Smooth scrolling with highlighting of missing plugin rows

#### Improved
- Notification reliability using multiple injection methods
- Earlier placement in page load cycle for better visibility
- Enhanced error detection for all WordPress error message formats

#### [1.3.2] - 2025-02-25
#### Added
- Prominent notification that appears directly below WordPress error messages
- Visual styling to help users connect error message with solution
- Direction arrows and highlighted text to improve user guidance

#### [1.3.1] - 2025-02-24
#### Added
- Instructional notification explaining how to use the plugin
- Step-by-step guidance for removing plugin references
- Clear visual indicators for missing plugins

#### [1.3.0] - 2025-02-23
#### Changed
- Complete redesign for maximum compatibility with all WordPress themes
- Now uses the plugins list table for missing plugin references
- Uses standard WordPress admin UI patterns and hooks

#### Added
- Missing plugins now appear directly in the plugins list
- "Remove Reference" action link in the plugins list
- Success/error notices after removing references

#### Fixed
- Compatibility issues with various WordPress admin themes
- Reliability issues with notification detection

#### [1.2.4] - 2025-02-22
#### Fixed
- Compatibility with more WordPress admin UI variations
- Specific targeting for admin notices in various themes

#### Added
- Advanced DOM traversal using TreeWalker API
- Multiple fallback approaches to ensure button appears
- Enhanced console logging for troubleshooting

#### [1.2.3] - 2025-02-21
#### Fixed
- Button not appearing in some WordPress admin themes
- Error message detection for greater theme compatibility

#### Improved
- DOM traversal to find notification elements in various themes
- Added console logging for troubleshooting

#### [1.2.2] - 2025-02-20
#### Fixed
- Timeout issue during plugin activation
- Potential infinite recursion in admin notices handling

#### Improved
- Hook management to prevent performance issues
- Optimized by only loading on plugins page

#### [1.2.1] - 2025-04-07
#### Improved
- Fixed typos in documentation
- Improved text clarity
- Added question mark to first sentence for better readability

#### [1.2] - 2025-04-07
#### Added
- Improved documentation with detailed explanation
- Added SQL reference for technical users
- Suggested potential inclusion in WordPress core

#### [1.1] - 2025-04-07
#### Added
- Support for multisite WordPress installations
- Network admin page integration
- Woodpecker CI integration for automated releases

#### Improved
- Error handling
- User experience with better confirmation messages
- Security by adding proper capability checks

#### [1.0] - 2025-03-15
#### Added
- Initial release
- "Remove Reference" button for plugin deactivation error notices
- AJAX processing for reference removal