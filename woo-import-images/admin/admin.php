<?php
/**
 * Register a custom menu page.
 */
function woo_import_images_menu() {
    add_menu_page(
        'WooCommerce Import Images',
        'WooCommerce Import Images',
        'manage_options',
        'woo-import-images/woo-import-images-do.php'
    );
}

add_action( 'admin_menu', 'woo_import_images_menu' );
