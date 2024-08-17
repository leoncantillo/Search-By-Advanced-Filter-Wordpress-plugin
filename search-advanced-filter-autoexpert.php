<?php
/**
 * Plugin Name:  Search By Advanced Filter
 * Plugin URI:   https://github.com/LeonCantillo/Search-By-Advanced-Filter-Wordpress-plugin/tree/master
 * Description:  Plugin created for the company Autoexpert "https://autoxpert.expressactionllc.com/" to implement an advanced search based on filters. **Requires WooCommerce to be active**.
 * Version:      1.0
 * Author:       León Cantillo
 * Author URI:   https://github.com/LeonCantillo
 * License:      GPL v2 or later
 * License URI:  https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:  search_advanced_filter 
 * Domain Path:  /languages
 * Charset:      UTF-8
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Enqueue scripts and styles
require_once plugin_dir_path(__FILE__) . 'includes/enqueue-scripts.php';

// Shortcode
require_once plugin_dir_path(__FILE__) . 'public/shortcodes/search-advanced-filter-shortcode.php';

// Admin functions (if any)
require_once plugin_dir_path(__FILE__) . 'admin/admin-functions.php';