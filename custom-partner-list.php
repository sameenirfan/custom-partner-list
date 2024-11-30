<?php
/*
 * Plugin Name: Custom Partner List
 * Plugin URI:   https://oidigitalinstitute.com
 * Description: The Custom Partner List Generator is a powerful WordPress plugin designed to help website administrators and content creators easily generate customized partner lists using a shortcode. This plugin is particularly useful for websites that need to showcase partners, agents, test centers, or preparation centers associated with various products or services.
 * Version:     1.0.0
 * Author:      Sameen Irfan | Oxford International Digital Institute
 * Author URI:   https://github.com/sameenirfan  
 * Organization: Oxford International Digital Institute
 * Organization URI: https://oidigitalinstitute.com/
 * License:     GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: assign-custom-permission
 * Domain Path: /languages
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function clear_custom_partner_list_styles_cache() {
    if (function_exists('wp_cache_clear_cache')) {
        wp_cache_clear_cache();
    }
}
register_activation_hook(__FILE__, 'clear_custom_partner_list_styles_cache');


function custom_partner_list_add_settings_link($links) {
    $settings_link = '<a href="admin.php?page=custom-partner-list-settings">Settings</a>';
    array_unshift($links, $settings_link); // Add the link at the beginning of the array
    return $links;
}

add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'custom_partner_list_add_settings_link');



// Enqueue necessary scripts and styles
function custom_partner_list_enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_style('custom-partner-list-style', plugins_url('/custom-partner-list-style.css', __FILE__));
    wp_enqueue_style('jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js');
    wp_enqueue_script('datatables', 'https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js', array('jquery'), '1.12.1', true);
    wp_enqueue_style('datatables-css', 'https://cdn.datatables.net/1.12.1/css/jquery.dataTables.css');
	
	// Include the Responsive extension
wp_enqueue_script('datatables-responsive', 'https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js', array('datatables'), '2.2.9', true);
wp_enqueue_style('datatables-responsive-css', 'https://cdn.datatables.net/responsive/2.2.9/css/responsive.dataTables.min.css');
}
add_action('wp_enqueue_scripts', 'custom_partner_list_enqueue_scripts', 999); // Use a higher number here

//echo var_dump(plugins_url('/styles.css', __FILE__));
// Include the admin option settings
require_once(plugin_dir_path(__FILE__) . 'admin-options.php');

// Include the partner list shortcode functionality
require_once(plugin_dir_path(__FILE__) . 'partner-list-shortcode.php');

?>