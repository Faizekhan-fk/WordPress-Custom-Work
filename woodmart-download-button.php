<?php
/**
 * Plugin Name: WoodMart Download Button
 * Plugin URI:  https://yourwebsite.com
 * Description: Adds a custom download button for downloadable products in WooCommerce.
 * Version:     1.0
 * Author:      Your Name
 * Author URI:  https://yourwebsite.com
 * License:     GPL2
 * Text Domain: woodmart-download-button
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Hook into WooCommerce single product page
add_action('woocommerce_single_product_summary', 'woodmart_display_download_button', 25);

function woodmart_display_download_button() {
    global $product;

    if ($product->is_downloadable()) {
        $downloads = $product->get_downloads();

        if (!empty($downloads)) {
            echo '<div class="woodmart-download-section">';
            echo '<h4>' . __('Downloadable Files', 'woocommerce') . '</h4>';
            foreach ($downloads as $download) {
                echo '<p><a href="' . esc_url($download['file']) . '" class="woodmart-download-btn button" download>
                        <i class="fas fa-download"></i> ' . esc_html($download['name']) . '
                      </a></p>';
            }
            echo '</div>';
        }
    }
}

// Enqueue styles for the download button
add_action('wp_enqueue_scripts', 'woodmart_download_button_styles');

function woodmart_download_button_styles() {
    wp_add_inline_style('woodmart-style', '
        .woodmart-download-section {
            margin-top: 15px;
            padding: 10px;
            background: #f7f7f7;
            border-radius: 5px;
        }
        .woodmart-download-btn {
            display: inline-block;
            padding: 10px 20px;
            background: #ff6600;
            color: #fff;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            transition: 0.3s;
        }
        .woodmart-download-btn:hover {
            background: #cc5200;
        }
    ');
}
