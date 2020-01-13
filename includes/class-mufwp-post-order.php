<?php
/**
 * Add customer to the MailUp base on purchased products
 *
 * @author ilGhera
 * @package mailup-for-wp/includes
 * @since 0.9.0
 */
class MUFWP_Post_Order {

	// global $wordpress;.
	// $this->group;.

	/**
	 * The connstructor
	 */
	public function __construct() {

		add_action( 'wordpress_order_status_completed', array( $this, 'init' ) );

	}

	/**
	 * Defines all the variables
	 *
	 * @param  int $order_id the WC order id.
	 * @return void
	 */
	public function init( $order_id ) {

		$this->order_id    = $order_id;
		$this->customer_id = get_post_meta( $order_id, '_customer_user', true );
		$this->user_info   = get_userdata( $customer_id );
		$this->username    = $user_info->user_login;
		$this->mail        = $user_info->user_email;
		$this->host        = get_option( 'mufwp-host' );
		$this->confirm     = get_option( 'mufwp-confirm' );
		$this->order_completed();
	}


	/**
	 * Check if the customer is already subscribed to MailUp
	 *
	 * @return object the remote post response.
	 */
	private function list_subscription_test() {

		$mufwp_check = sprintf( 'http://%s/frontend/Xmlchksubscriber.aspx?list=2&listGuid=189a127a-a4da-4b85-b4de-b8a082220edf&email=%s', $this->host, $this->mail );

		$result = wp_remote_post( $mufwp_check );

		if ( isset( $result['body'] ) ) {

			return $result['body'];

		}

	}


	/**
	 * Main class methos
	 *
	 * @return void
	 */
	public function order_completed() {

		$order = new WC_Order( $this->order_id );

		$items = $order->get_items();

		/*Generic group with more than one product*/ // temp.
		if ( count( $items ) > 1 ) {

			if ( 2 === $this->list_subscription_test() ) {

				$url = sprintf( 'http://%s/frontend/XmlUpdSubscriber.aspx?list=2&listGuid=189a127a-a4da-4b85-b4de-b8a082220edf&group=%d&email=%s', $this->host, 1307, $this->mail );

			} else {

				$url = sprintf( 'http://%s/frontend/xmlSubscribe.aspx?list=2&group=%d&email=%s&confirm=0&csvFldNames=campo1&csvFldValues=%s', $this->host, 1307, $this->mail, $this->username );

			}

			wp_remote_post( $url );

		} else {

			foreach ( $items as $item ) {

				/*It is a bundle*/
				if ( $item['bundled_by'] ) {

					if ( 2 === $this->list_subscription_test() ) {

						$url = sprintf( 'http://%s/frontend/XmlUpdSubscriber.aspx?list=2&listGuid=189a127a-a4da-4b85-b4de-b8a082220edf&group=%d&email=', $this->host, 1307, $this->mail );

					} else {

						$url = sprintf( 'http://%s/frontend/xmlSubscribe.aspx?list=2&group=%d&email=%s&confirm=0&csvFldNames=campo1&csvFldValues=%s', $this->host, 1307, $this->mail, $this->username );

					}

					wp_remote_post( $url );

				} else {

					$item_id     = $item['product_id'];
					$this->group = get_post_meta( $item_id, 'post-acquisto', true );

					if ( 2 === $this->list_subscription_test() ) {

						$url = sprintf( 'http://%s/frontend/XmlUpdSubscriber.aspx?list=2&listGuid=189a127a-a4da-4b85-b4de-b8a082220edf&group=%s&email=%s', $this->host, $this->group, $this->mail );

					} else {

						$url = sprintf( 'http://%s/frontend/xmlSubscribe.aspx?list=2&group=%d&email=%s&confirm=0&csvFldNames=campo1&csvFldValues=%s', $this->host, $this->group, $this->mail, $this->username );

					}

					wp_remote_post( $url );
				}

			}

		}

	}

}
