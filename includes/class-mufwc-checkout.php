<?php
/**
 * Add customer to the MailUp base on purchased products
 *
 * @author ilGhera
 * @package mailup-for-wc/includes
 * @since 0.9.0
 */
class MUFWC_Checkout {

	/**
	 * The constructor
     *
     * @return void
	 */
	public function __construct() {

		$this->user_checkout   = get_option( 'mufwc-checkout' );

        /* Filters */
        add_filter( 'woocommerce_checkout_fields', array( $this, 'set_checkout_field' ) );

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
                'type'        => 'checkbox',
                'class'       => array(
                    /* 'field-school-class form-row-first', */
                    'mufwc-checkout',
                ),
                'label'       => __( 'Iscriviti alla newsletter', 'mailup-for-wc' ),
                /* 'placeholder' => __( 'Classe scolastica', MS_DOMAIN ), */
                'required'    => false, 
            );

        }

        return $fields;

    }

}
new MUCWC_Checkout();

