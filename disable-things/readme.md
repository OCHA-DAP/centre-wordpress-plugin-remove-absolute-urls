# Disable Gutenberg Editor & XML-RPC Protocol
This plugin uses the built-in WordPress filters [use_block_editor_for_post](https://developer.wordpress.org/reference/hooks/use_block_editor_for_post/) (to disable the Gutenberg Block Editor, enabled by default, and to restore the Classic Editor) and [xmlrpc_enabled](https://developer.wordpress.org/reference/hooks/xmlrpc_enabled/) (to disable the XML-RPC API on a WordPress site).

* Contributors: [The Centre for Humanitarian Data](https://centre.humdata.org)
* Requires at least: 5.0.0

## Installation
- Create a new release or upload the `disable-things` directory to the `/wp-content/plugins/` directory in your WordPress installation
- Activate the plugin through the \"Plugins\" menu in WordPress
- The Gutenberg Editor and WordPress XML-RPC methods are now disabled
- To re-enable the Gutenberg Editor and XML-RPC Protocol, just deactivate the plugin through the \"Plugins\" menu

## Changelog
* 1.0
    * Initial Release

## Contributing
Pull requests are welcome. For major changes, please open an issue first to discuss what you would like to change.
