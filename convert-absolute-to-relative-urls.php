<?php
/*
 * Plugin Name: Convert Absolute URLs to Relative URLs
 * Description: This plugin will replace absolute URLs with relative URLs inside the content (and vice versa in RSS feeds) and creates a page that allows staff members to fix existing bad entries from the database. Allows multiple domains and excluding specific post types.
 * Version: 1.1
 * Author: The Centre for Humanitarian Data
 * Author URI: https://centre.humdata.org
**/

// No direct access to this file
if(!defined('ABSPATH')) {
    exit;
}
 
class FixAbsoluteURLs {

	protected $_domains;
	protected $_attributes;
	protected $_excepted_post_types;

	public function __construct($file) {
		if(!$file) {
			throw new Error('Missing 1 argument!');
		}

		// get current domain
		$url = get_bloginfo('wpurl');
		$parse = parse_url($url);
		$current_domain = $parse['scheme'].'://'.$parse['host'];
		$current_domain_with_www = $parse['scheme'].'://www.'.$parse['host'];

		// set domains and replacement attributes
		$this->_domains = [$current_domain, $current_domain_with_www, 'https://centre.humdata.org', 'https://hdx-centre.site.strattic.io', 'https://hdx-centre.preview.strattic.io'];
		$this->_attributes = ['href', 'src', 'srcset'];

		// excepted post types
		$this->_excepted_post_types = ['ufaq']; // FAQ

		// init action
		add_action('wp_insert_post_data', [$this, 'remove_absolute_urls'], 99, 1);
		add_action('admin_menu', [$this, 'register_fix_absolute_urls_page']);
		add_action('the_content_feed', [$this, 'feed_relative_to_absolute_urls']);
	}

	/**
	 * Show "Fix Absolute URLs" submenu in admin panel.
	 * @action add_submenu_page
	 */
	public function register_fix_absolute_urls_page() {
		add_submenu_page(
			'tools.php',
			'Fix Absolute URLs',
			'Fix Absolute URLs',
			'manage_options',
			'fix-absolute-urls',
			[$this, 'fix_absolute_urls_submenu_page_content']
		);
	}

	/**
	 * Page content for submenu page in admin panel.
	 */
	public function fix_absolute_urls_submenu_page_content() {
		require_once plugin_dir_path( __FILE__ ).'/admin/views/update.php';
	}

	/**
	 * Remove root URL upon saving a post or page.
	 * @action wp_insert_post_data
	 * @param array $data
	 * @return array
	 */
	public function remove_absolute_urls($data) {
		// avoid replacement in excepted post types
		if(!in_array($data['post_type'], $this->_excepted_post_types)) {

			// received content
			$content = htmlentities($data['post_content']);

			// do the magic :-)
			$new_content = $content;
			foreach ($this->_domains as $domain) {
				foreach ($this->_attributes as $attribute) {
					$new_content = str_ireplace(htmlentities($attribute . '=\\"' . $domain), $attribute . '=\\"', $new_content);
				}
			}

			// set the new content
			$data['post_content'] = html_entity_decode($new_content);

		}

		// return the updated data
		return $data;
	}

	/**
	 * Check number of entries with root URL from "posts" table.
	 * @global $wpdb
	 */
	public function check_database_no_absolute_urls() {
		global $wpdb;

		$post_table = $wpdb->prefix.'posts';

		// set the matches
		$matches = '';
		foreach($this->_domains as $domain) {
			foreach($this->_attributes as $attribute) {
				$matches .= 'OR post_content LIKE \'%'.$attribute.'="'.$domain.'%\'';
			}
		}
		$matches = ltrim($matches, 'OR '); // fix sql query

		// avoid replacement in excepted post types
		$excepted = '1 = 1';
		if(count($this->_excepted_post_types) > 0) {
			$excepted = 'post_type NOT IN ("'.implode('","', $this->_excepted_post_types).'")';
		}

		// count rows
		$wpdb->get_results('SELECT * FROM '.$post_table.' WHERE '.$excepted.' AND ('.$matches.')');
		return $wpdb->num_rows;
	}

	/**
	 * Remove root URL from "posts" table.
	 * @global $wpdb
	 */
	public function remove_database_absolute_urls() {
		global $wpdb;

		$post_table = $wpdb->prefix.'posts';

		// set the replacements
		$replacements = [];
		foreach($this->_domains as $domain) {
			foreach($this->_attributes as $attribute) {
				$replacements[] =                 [
					'old' => $attribute.'="' . $domain,
					'new' => $attribute.'="',
				];
			}
		}

		// avoid replacement in excepted post types
		$excepted = '1 = 1';
		if(count($this->_excepted_post_types) > 0) {
			$excepted = 'post_type NOT IN ("'.implode('","', $this->_excepted_post_types).'")';
		}

		// update queries
		foreach($replacements as $replacement) {
			$query = 'UPDATE '.$post_table.' SET post_content = REPLACE(post_content, \''.$replacement['old'].'\', \''.$replacement['new'].'\') WHERE '.$excepted;
			$wpdb->query($query);
		}
	}

	/**
	 * Convert relative URLs to absolute URLs in feed content.
	 * @action the_content_feed
	 */
	public function feed_relative_to_absolute_urls($content) {
		// do the magic :-)
		$new_content = $content;
		foreach($this->_domains as $domain) {
			foreach($this->_attributes as $attribute) {
				$new_content = str_ireplace($attribute.'="/', $attribute.'="'.$domain.'/', $new_content);
			}
		}

		// return the new content
		return $new_content;
	}

}

new FixAbsoluteURLs(__FILE__);
