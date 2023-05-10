<?php
/**
 * Add customer to the MailUp base on purchased products
 *
 * @author ilGhera
 * @package mailup-for-wc/includes
 * @since 1.0.0
 */

/**
 * MUFWC_Post_Order
 */
class MUFWC_Post_Order {

	/**
	 * The connstructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->host = get_option( 'mufwc-host' );

		add_action( 'woocommerce_order_status_completed', array( $this, 'order_completed' ), 10, 1 );

	}


	/**
	 * Check if the customer is already subscribed to MailUp
	 *
	 * @param int    $list the MailUp list number.
	 * @param string $mail the user email.
	 *
	 * @return object the remote post response.
	 */
	private function is_user_subscried( $list, $mail ) {

		$output      = false;
		$mufwc_check = sprintf( '%s/frontend/Xmlchksubscriber.aspx?list=%d&email=%s', $this->host, $list, $mail );

		$result = wp_remote_post( $mufwc_check );

		if ( ! is_wp_error( $result ) && isset( $result['body'] ) ) {

			$output = 2 === intval( $result['body'] ) ? true : false;

		}

		return $output;

	}


	/**
	 * Get customer info
	 *
	 * @param object $order   the WC order.
	 * @param boll   $wp_user get the WP user data with true.
	 *
	 * @return array
	 */
	public function get_customer_info( $order, $wp_user = false ) {

		$order_id    = $order->get_id();
		$customer_id = get_post_meta( $order_id, '_customer_user', true );

		if ( $wp_user && $customer_id ) {

			$user_info = get_userdata( $customer_id );
			$mail      = $user_info->user_email;
			$firstname = $user_info->first_name;
			$lastname  = $user_info->last_name;
			$username  = $user_info->user_login;

			if ( $firstname && $lastname ) {

				$username = $firstname . ' ' . $lastname;

			} elseif ( $firstname ) {

				$username = $firstname;

			} elseif ( $lastname ) {

				$username = $lastname;

			}
		} else {

			$mail     = $order->get_billing_email();
			$username = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();

		}

		return array(
			'username' => $username,
			'mail'     => $mail,
		);

	}


	/**
	 * Add order items to the MailUp groups set
	 *
	 * @param  int $order_id the WC order id.
	 *
	 * @return void
	 */
	public function order_completed( $order_id ) {

		$order         = new WC_Order( $order_id );
		$customer_info = $this->get_customer_info( $order );
		$items         = $order->get_items();

		foreach ( $items as $item ) {

			$item_id = $item['product_id'];
			$list    = get_post_meta( $item_id, 'mufwc-product-list', true );
			$group   = get_post_meta( $item_id, 'mufwc-product-group', true );

			if ( $list ) {

				if ( $this->is_user_subscried( $list, $customer_info['mail'] ) ) {

					$url = sprintf( '%s/frontend/XmlUpdSubscriber.aspx?list=%d&group=%d&email=%s', $this->host, $list, $group, $customer_info['mail'] );

				} else {

					$url = sprintf( '%s/frontend/xmlSubscribe.aspx?list=%d&group=%d&email=%s&confirm=0&csvFldNames=campo1&csvFldValues=%s', $this->host, $list, $group, $customer_info['mail'], $customer_info['username'] );

				}

				$response = wp_remote_post( $url );

			}
		}

	}

}

new MUFWC_Post_Order();

