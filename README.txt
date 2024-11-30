=== Custom Partner List ===
Contributors: Sameen Irfan
Tags: partner list, custom table, DataTables, shortcode
Requires at least: 4.0
Tested up to: 5.8
Stable tag: 1.0.0
License: GPL v2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

== Description ==
Custom Partner List is a WordPress plugin that generates a custom partner list table with DataTables for your WordPress site. It allows you to display a list of partners based on specific product IDs and stakeholder types.

== Installation ==
1. Upload the `custom-partner-list` folder to the `/wp-content/plugins/` directory.
2. Activate the plugin through the 'Plugins' menu in WordPress.

== Usage ==
To display the custom partner list table, create a new page or post and add the following shortcode:

[custom_partner_list]

The plugin will generate a table listing partners based on product IDs and stakeholder types.

== Shortcode Attributes ==
The `[custom_partner_list]` shortcode supports the following attributes:

1. `product`: Comma-separated list of product IDs to filter the partner list (optional).
2. `stakeholder`: Comma-separated list of stakeholder types to filter the partner list (optional).

Example shortcode usage:
[custom_partner_list product=1,2,3 stakeholder=1,2]



== Styles ==
The plugin enqueues the necessary styles and scripts for DataTables to enhance the table display. It also utilizes Google Fonts to apply font styles.

== Database Tables ==
The plugin requires a custom database table `wp_custom_partners_agents` for storing partner information and a relationship table `wp_custom_partners_products_relationship` for linking partners with products.

== Known Issues ==
- None.

== Frequently Asked Questions ==
- Q: How can I customize the table appearance?
  A: You can modify the `styles.css` file in the `custom-partner-list` plugin folder to adjust the table's appearance according to your preferences.

== Changelog ==
= 1.0.0 =
* Initial release.

== Upgrade Notice ==
= 1.0.0 =
Initial release of the Custom Partner List plugin.

== Author ==
Custom Partner List plugin is developed by Sameen Irfan.

== Support ==
For any support or inquiries, you can contact the author at jsansari@oxfordinternational.com.
