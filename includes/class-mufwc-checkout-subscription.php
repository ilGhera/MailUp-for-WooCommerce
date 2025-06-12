<?php
/**
 * Add customer to the MailUp base on purchased products
 *
 * @author ilGhera
 * @package mailup-for-wc/includes
 *
 * @since 1.0.2
 */

/**
 * MUFWC_Checkout_Subscription
 */
class MUFWC_Checkout_Subscription {

	/**
	 * The constructor
	 *
	 * @return void
	 */
	public function __construct() {

		$this->user_checkout = get_option( 'mufwc-checkout' );

		/* Filters */
		add_filter( 'woocommerce_checkout_fields', array( $this, 'set_checkout_field' ) );

		/* Actions */
		add_action( 'woocommerce_checkout_create_order', array( $this, 'save_field' ), 10, 2 );

	}

	/**
	 * Add the custom field to the WC checkout form
	 *
	 * @param array $fields the current fields.
	 *
	 * @return array the fields updated
	 */
	public function set_checkout_field( $fields ) {

		if ( $this->user_checkout ) {

			$fields['order']['user-newsletter-checkout'] = array(
				'type'     => 'checkbox',
				'class'    => array(
					'mufwc-checkout',
				),
				'label'    => __( 'Subscribe to the newsletter', 'mailup-for-wc' ),
				'required' => false,
			);

            $default_true = get_option( 'mufwc-checkout-true' );

            if ( $default_true ) {

                $fields['order']['user-newsletter-checkout']['default'] = true; 

            }

		}

		return $fields;

	}

	/**
	 * Add user to MailUp if the field was checked
	 *
	 * @param  object $order the WC order.
	 *
	 * @return void
	 */
	public function mailup_registration( $order ) {

		$username = $order->get_billing_first_name() . ' ' . $order->get_billing_last_name();
		$mail     = $order->get_billing_email();
		$host     = get_option( 'mufwc-host' );
		$list     = get_option( 'mufwc-list' );
		$group    = get_option( 'mufwc-group' );
		$confirm  = get_option( 'mufwc-confirm' );

		$url = sprintf(
			'%s/frontend/xmlSubscribe.aspx?list=%d&group=%d&email=%s&confirm=%d&csvFldNames=campo1&csvFldValues=%s',
			$host,
			$list,
			$group,
			$mail,
			$confirm,
			$username
		);

		$response = wp_remote_post( $url );

	}

	/**
	 * Save custom field
	 *
	 * @param  object $order the order.
	 *
	 * @param  array  $data  the order data.
	 */
	public function save_field( $order, $data ) {

		$fields = WC()->checkout()->checkout_fields;

		if ( isset( $fields['order']['user-newsletter-checkout'] ) ) {

			if ( isset( $data['user-newsletter-checkout'] ) && $data['user-newsletter-checkout'] ) {

				$order->update_meta_data( '_user-newsletter-checkout', sanitize_text_field( $data['user-newsletter-checkout'] ) );

				/* MailUp registration */
				$this->mailup_registration( $order );

			}
		}

	}

}

new MUFWC_Checkout_Subscription();

