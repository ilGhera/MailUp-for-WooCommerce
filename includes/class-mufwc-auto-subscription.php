<?php
/**
 * Auto subscription to MailUp on WC registration
 *
 * @author ilGhera
 * @package mailup-for-wc/includes
 * @since 0.9.2
 */
class MUFWC_Auto_Subscription {


	/**
	 * The constructor
	 */
	public function __construct() {

		$this->user_newsletter = get_option( 'mufwc-newsletter' );

		add_action( 'register_form', array( $this, 'add_check_field' ) );
		add_action( 'woocommerce_register_form', array( $this, 'add_check_field' ) );
		add_action( 'user_register', array( $this, 'mailup_registration' ) );

	}


	/**
	 * Add the newsletter check field to the WC registration form
	 */
	public function add_check_field() {

		if ( $this->user_newsletter ) {

			echo '<p style="margin-bottom:10px;">';
				echo '<label style="text-align: left;">';
					echo '<input style="margin-top: 0;" type="checkbox" name="user-newsletter" id="user-newsletter" value="1" checked="checked"/>';
					echo esc_html__( 'Newsletter subscriptions', 'wc-mailup' );
				echo '</label>';
			echo '</p>';

		}

	}


	/**
	 * Add user to MailUp if the field was checked
	 *
	 * @param  int $user_id the user.
	 * @return void
	 */
	public function mailup_registration( $user_id ) {

		$user_info = get_userdata( $user_id );
		$username  = $user_info->user_login;
		$mail      = $user_info->user_email;
		$host      = get_option( 'mufwc-host' );
		$list      = get_option( 'mufwc-list' );
		$group     = get_option( 'mufwc-group' );
		$confirm   = get_option( 'mufwc-confirm' );

		if ( $this->user_newsletter ) {

			$user_newsletter = ( isset( $_POST['user-newsletter'] ) && 1 === intval( $_POST['user-newsletter'] ) ) ? 1 : 0;

			if ( $user_newsletter ) {

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
		}

	}

}

new MUFWC_Auto_Subscription();

