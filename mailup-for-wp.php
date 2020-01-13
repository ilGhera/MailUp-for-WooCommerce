<?php
/**
 * Plugin Name: MailUp for WordPress - Premium
 * Plugin URI: https://www.ilghera.com/product/mailup-for-wordpress-premium
 * Description: xxxxxxx
 * Author: ilGhera
 * Version: 0.9.0
 * Author URI: https://ilghera.com
 * Requires at least: 4.0
 * Tested up to: 5
 * WC tested up to: 3
 * Text Domain: mailup-for-wp
 */


/**
 * Handles the plugin activation
 *
 * @return void
 */
function load_mailup_for_wp() {

	/*Function check */
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	}


	/*Constants declaration*/
	define( 'MUFWP_DIR', plugin_dir_path( __FILE__ ) );
	define( 'MUFWP_URI', plugin_dir_url( __FILE__ ) );
	define( 'MUFWP_INCLUDES', MUFWP_DIR . 'includes/' );
	define( 'MUFWP_ADMIN', MUFWP_DIR . 'admin/' );
	// define( 'MUFWP_SETTINGS', admin_url( 'admin.php?page=mailup-for-wp' ) );

	/*Internationalization*/
	$locale = apply_filters( 'plugin_locale', get_locale(), 'wp-restaurant-booking' );
	load_plugin_textdomain( 'mailup-for-wp', false, basename( dirname( __FILE__ ) ) . '/languages' );
	load_textdomain( 'mailup-for-wp', trailingslashit( WP_LANG_DIR ) . basename( MUFWP_DIR ) . '/mailup-for-wp-' . $locale . '.mo' );

	/*Files required*/
	require( MUFWP_ADMIN . 'class-mufwp-admin.php' );
	require( MUFWP_INCLUDES . 'class-mufwp-auto-subscription.php' );
	require( MUFWP_INCLUDES . 'class-mufwp-button.php' );
	require( MUFWP_INCLUDES . 'class-mufwp-edit-post.php' );

}
add_action( 'plugins_loaded', 'load_mailup_for_wp', 10 );
