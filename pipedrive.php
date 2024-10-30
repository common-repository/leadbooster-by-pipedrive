<?php
/*
 * Plugin Name: LeadBooster Chatbot by Pipedrive
 * Version: 1.1.1
 * Description: LeadBooster Chatbot by Pipedrive is a chatbot plugin that captures visitors to your WordPress website and turns them from qualified leads into deals in your Pipedrive CRM account.
 * Author: Pipedrive
 * Text Domain: leadbooster-by-pipedrive
 * Domain Path: /languages
 * Author URI: https://www.pipedrive.com/?utm_source=leadbooster_wordpress_plugin&utm_medium=growth
 * Plugin URI: https://wordpress.org/plugins/leadbooster-by-pipedrive/
 */

defined('ABSPATH') or die("Restricted access!");


function pipedrive_textdomain() {
    load_plugin_textdomain('leadbooster-by-pipedrive', FALSE, basename(dirname(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'pipedrive_textdomain');

define('PIPEDRIVE_VERSION', '1.0');
define('PIPEDRIVE_DIR', plugin_dir_path(__FILE__));
define('PIPEDRIVE_URL', plugin_dir_url(__FILE__));
defined('PIPEDRIVE_PATH') or define('PIPEDRIVE_PATH', untrailingslashit(plugins_url('', __FILE__)));
define('PIPEDRIVE_ICON_PATH', PIPEDRIVE_PATH . '/images/logo.svg');
define('PIPEDRIVE_ICON_PATH_DIR', PIPEDRIVE_DIR . '/images/logo.svg');

require_once(PIPEDRIVE_DIR . 'includes/core.php');
require_once(PIPEDRIVE_DIR . 'includes/admin.php');
require_once(PIPEDRIVE_DIR . 'includes/embed.php');

//Adds link to the settings page to the plugin entry in plugins list
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'pipedrive_add_action_links');
function pipedrive_add_action_links ($links) {
	$mylinks = array(
		'<a href="' . admin_url('options-general.php?page=pipedrive') . '">' . __('Settings', 'leadbooster-by-pipedrive') . '</a>',
	);
	return array_merge($links, $mylinks);
}

//Enqueue scripts and styles needed for the plugin settings
add_action('admin_enqueue_scripts', 'pipedrive_enqueue_admin_scripts');
function pipedrive_enqueue_admin_scripts() {
	$screen = get_current_screen();
	if ($screen->id == 'settings_page_pipedrive') {
		wp_register_style('pipedrive_admin_css', PIPEDRIVE_URL . '/styles/admin.css', array(), '1.0');
		wp_enqueue_style('pipedrive_admin_css');
		wp_register_script('pipedrive_admin', PIPEDRIVE_URL . 'scripts/admin.js', array(), '1.0');
		wp_enqueue_script('pipedrive_admin');
		wp_register_script('fancyTable', PIPEDRIVE_URL . 'scripts/fancyTable.min.js', array(), '1.0.15');
		wp_enqueue_script('fancyTable');
	}
}
