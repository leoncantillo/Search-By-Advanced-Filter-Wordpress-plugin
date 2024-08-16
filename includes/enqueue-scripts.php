<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

function search_advanced_filter_enqueue_assets() {
    wp_enqueue_style('plugin-styles', plugin_dir_url(__FILE__) . '../public/css/styles.css');
}
add_action('wp_enqueue_scripts', 'search_advanced_filter_enqueue_assets');
