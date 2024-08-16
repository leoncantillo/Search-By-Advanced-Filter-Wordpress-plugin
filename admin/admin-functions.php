<?php

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

// Admin notice if WooCommerce is not active.
function safp_admin_notice_woocommerce_required() {
    if ( ! class_exists( 'WooCommerce' ) ) {
        ?>
        <div class="notice notice-error">
            <p><?php _e( 'The Search By Advanced Filter plugin requires WooCommerce to be installed and active.', 'search_advanced_filter' ); ?></p>
        </div>
        <?php
    }
}
add_action( 'admin_notices', 'safp_admin_notice_woocommerce_required' );