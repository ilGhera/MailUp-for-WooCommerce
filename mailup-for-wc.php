<?php
/**
 * Plugin Name: MailUp for WooCommerce
 * Plugin URI: https://www.ilghera.com/product/mailup-for-woocommerce-premium
 * Description: Lead marketing and clients follow-up for WooCommerce with MailUp lists, groups and workflows
 * Author: ilGhera
 * Version: 0.9.0
 * Author URI: https://ilghera.com
 * Requires at least: 4.0
 * Tested up to: 5
 * WP tested up to: 3
 * Text Domain: mailup-for-wc
 */

/**
 * Handles the plugin activation
 *
 * @return void
 */
function load_mailup_for_wc() {

	/*Function check */
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . '/wp-admin/includes/plugin.php';
	}

	/*Deactivate free plugins*/
	if ( is_plugin_active( 'mailup-auto-subscription/mailup-auto-subscription.php' ) || function_exists( 'mas_load_textdomain' ) ) {
		deactivate_plugins( 'mailup-auto-subscription/mailup-auto-subscription.php' );
		remove_action( 'plugins_loaded', 'mas_load_textdomain' );
		wp_safe_redirect( admin_url( 'plugins.php?plugin_status=all&paged=1&s' ) );
		exit;
	}

	/*Constants declaration*/
	define( 'MUFWC_DIR', plugin_dir_path( __FILE__ ) );
	define( 'MUFWC_DIR_NAME', basename( dirname( __FILE__ ) ) );
	define( 'MUFWC_FILE', __FILE__ );
	define( 'MUFWC_URI', plugin_dir_url( __FILE__ ) );
	define( 'MUFWC_INCLUDES', MUFWC_DIR . 'includes/' );
	define( 'MUFWC_ADMIN', MUFWC_DIR . 'admin/' );
	define( 'MUFWC_SETTINGS', admin_url( 'admin.php?page=mailup-for-wc' ) );

	/*Internationalization*/
	$locale = apply_filters( 'plugin_locale', get_locale(), 'wp-restaurant-booking' );
	load_plugin_textdomain( 'mailup-for-wc', false, basename( dirname( __FILE__ ) ) . '/languages' );
	load_textdomain( 'mailup-for-wc', trailingslashit( WP_LANG_DIR ) . basename( MUFWC_DIR ) . '/mailup-for-wc-' . $locale . '.mo' );

	/*Files required*/
	require MUFWC_ADMIN . 'class-mufwc-admin.php';
	require MUFWC_INCLUDES . 'class-mufwc-auto-subscription.php';
	require MUFWC_INCLUDES . 'class-mufwc-button.php';
	require MUFWC_INCLUDES . 'class-mufwc-edit-post.php';

}
add_action( 'plugins_loaded', 'load_mailup_for_wc', 100 );

