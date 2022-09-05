<?php
/*
 * Plugin Name: Disable Things
 * Description: This plugin disables:
 *     - Gutenberg Block Editor, enabled by default, and restores the Classic Editor.
 *     - XML-RPC Protocol, enabled by default.
 * Version: 1.0
 * Author: The Centre for Humanitarian Data
 * Author URI: https://centre.humdata.org
**/

// No direct access to this file
if(!defined('ABSPATH')) {
    exit;
}

add_filter('use_block_editor_for_post', '__return_false');

add_filter('xmlrpc_enabled', '__return_false');
