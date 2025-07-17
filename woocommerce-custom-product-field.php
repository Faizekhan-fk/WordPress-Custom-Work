<?php
/**
 * Plugin Name: Custom Feild
 * Plugin URI: #
 * Description: Adds a custom field to WooCommerce products.
 * Version: 1.0
 * Author: Faizan 
 * Author URI: #
 * License: GPL2
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

// Add custom field to product edit page
add_action('woocommerce_product_options_general_product_data', 'custom_woocommerce_product_field');
function custom_woocommerce_product_field() {
    woocommerce_wp_text_input([
        'id' => '_custom_product_field',
        'label' => __('Custom Field', 'woocommerce'),
        'desc_tip' => 'true',
        'description' => __('Enter a custom value for this product.', 'woocommerce'),
    ]);
}

// Save custom field value
add_action('woocommerce_process_product_meta', 'save_custom_woocommerce_product_field');
function save_custom_woocommerce_product_field($post_id) {
    $custom_field_value = isset($_POST['_custom_product_field']) ? sanitize_text_field($_POST['_custom_product_field']) : '';
    update_post_meta($post_id, '_custom_product_field', $custom_field_value);
}

// Display custom field on product page
add_action('woocommerce_single_product_summary', 'display_custom_product_field', 25);
function display_custom_product_field() {
    global $post;
    $custom_field_value = get_post_meta($post->ID, '_custom_product_field', true);
    if (!empty($custom_field_value)) {
        echo '<p class="custom-field">Custom Info: ' . esc_html($custom_field_value) . '</p>';
    }
}

// Add to cart item data
add_filter('woocommerce_add_cart_item_data', 'add_custom_field_to_cart', 10, 2);
function add_custom_field_to_cart($cart_item_data, $product_id) {
    if (isset($_POST['_custom_product_field'])) {
        $cart_item_data['custom_product_field'] = sanitize_text_field($_POST['_custom_product_field']);
    }
    return $cart_item_data;
}

// Display in cart and checkout
add_filter('woocommerce_get_item_data', 'display_custom_field_cart_checkout', 10, 2);
function display_custom_field_cart_checkout($item_data, $cart_item) {
    if (isset($cart_item['custom_product_field'])) {
        $item_data[] = [
            'name' => __('Custom Field', 'woocommerce'),
            'value' => $cart_item['custom_product_field'],
        ];
    }
    return $item_data;
}
