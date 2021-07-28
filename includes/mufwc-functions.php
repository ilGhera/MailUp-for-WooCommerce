<?php
/**
 * Functions
 *
 * @author ilGhera
 * @package mailup-for-wc/includes
 * @since 0.9.0
 */

/**
 * Update checker
 */
require MUFWC_DIR . 'plugin-update-checker/plugin-update-checker.php';

$mufwc_update_checker = Puc_v4_Factory::buildUpdateChecker(
	'https://www.ilghera.com/wp-update-server-2/?action=get_metadata&slug=mailup-for-wc-premium',
	MUFWC_FILE,
	'mailup-for-wc-premium'
);


/**
 * Secure update check with the Premium Key
 *
 * @param  array $query_args the default args.
 * @return array            the updated args
 */
function mufwc_secure_update_check( $query_args ) {

	$key = base64_encode( get_option( 'mufwc-premium-key' ) );

	if ( $key ) {

		$query_args['premium-key'] = $key;

	}

	return $query_args;

}
$mufwc_update_checker->addQueryArgFilter( 'mufwc_secure_update_check' );


/**
 * Plugin update message
 *
 * @param  array $plugin_data plugin information.
 * @param  array $response    available plugin update information.
 */
function mufwc_update_message( $plugin_data, $response ) {

	$key = get_option( 'mufwc-premium-key' );

	$message = null;

	if ( ! $key ) {

		$message = 'A <b>Premium Key</b> is required for keeping this plugin up to date. Please, add yours in the <a href="' . MUFWC_SETTINGS . '">options page</a> or click <a href="https://www.ilghera.com/product/mailup-for-woocommerce-premium/" target="_blank">here</a> for prices and details.';

	} else {

		$decoded_key = explode( '|', base64_decode( $key ) );
		$bought_date = date( 'd-m-Y', strtotime( $decoded_key[1] ) );
		$limit       = strtotime( $bought_date . ' + 365 day' );
		$now         = strtotime( 'today' );

		if ( $limit < $now ) {

			$message = 'It seems like your <strong>Premium Key</strong> is expired. Please, click <a href="https://www.ilghera.com/product/mailup-for-woocommerce-premium/" target="_blank">here</a> for prices and details.';

		} elseif ( '9128' !== $decoded_key[2] ) {

			$message = 'It seems like your <strong>Premium Key</strong> is not valid. Please, click <a href="https://www.ilghera.com/product/mailup-for-woocommerce-premium/" target="_blank">here</a> for prices and details.';

		}
	}

	$allowed_tags = array(
		'strong' => array(),
		'a'      => array(
			'href'   => array(),
			'target' => array(),
		),
	);

	echo ( $message ) ? '<br><span class="mufwc-alert">' . wp_kses( $message, $allowed_tags ) . '</span>' : '';

}
add_action( 'in_plugin_update_message-' . MUFWC_DIR_NAME . '/mailup-for-wc.php', 'mufwc_update_message', 10, 2 );
