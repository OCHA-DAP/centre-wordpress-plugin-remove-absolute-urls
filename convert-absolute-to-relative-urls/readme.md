# Convert Absolute to Relative URLs
This plugin will replace absolute URLs with relative URLs inside the content (and vice versa in RSS feeds) and creates a page that allows staff members to fix existing bad entries from the database. Allows multiple domains and excluding specific post types.

* Contributors: [The Centre for Humanitarian Data](https://centre.humdata.org)
* Requires at least: 2.9.0

## Installation
- Create a new release or upload the `convert-absolute-to-relative-urls` directory to the `/wp-content/plugins/` directory in your WordPress installation
- Activate the plugin through the \"Plugins\" menu in WordPress
- The plugin will start replacing absolute URLs in the new saved content
- To stop removing absolute URLs, just deactivate the plugin through the \"Plugins\" menu

## Existing absolute URLs
After installation, fix already existing absolute URLs found in the database by visiting `Tools` > `Fix Absolute URLs` and pressing the button.

## Changelog
* 1.1
  * Exclude specific post types
* 1.0
  * Initial Release

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.