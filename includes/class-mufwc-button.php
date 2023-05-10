<?php
/**
 * Add logged in user to MailUp
 *
 * @author ilGhera
 * @package mailup-for-wc/includes
 * @since 1.0.0
 */

/**
 * MUFWC_Button
 */
class MUFWC_Button {

	/**
	 * The constructor
	 */
	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this, 'mufwc_scripts' ) );

		add_shortcode( 'mailup-subscribe', array( $this, 'mufwc_button' ) );
		add_shortcode( 'mailup-subscribe-ancor', array( $this, 'ancor' ) );

		add_action( 'wp_ajax_mufwc-add-to', array( $this, 'add_to_callback' ) );
		add_action( 'wp_ajax_nopriv_mufwc-add-to', array( $this, 'add_to_callback' ) );

		add_action( 'wp_ajax_mufwc-form', array( $this, 'subscription_form_response' ) );
		add_action( 'wp_ajax_nopriv_mufwc-form', array( $this, 'subscription_form_response' ) );

		add_filter( 'the_content', array( $this, 'place_button' ) );

	}


	/**
	 * Add css and js files
	 *
	 * @return void
	 */
	public function mufwc_scripts() {

		/*css*/
		wp_enqueue_style( 'mufwc-style', MUFWC_URI . 'css/mufwc.css', array(), filemtime( MUFWC_DIR . 'css/mufwc.css' ) );

		/*js*/
		wp_enqueue_script( 'mufwc-js', MUFWC_URI . 'js/mufwc.js', array( 'jquery' ), '0.9.0', true );

			$product_id = get_the_ID(); // temp.
			$user_id    = get_current_user_id();
			$user_name  = null;
			$email      = null;

		if ( $user_id ) {

			$user_info = get_userdata( $user_id );
			$user_name = $user_info->user_login;
			$email     = $user_info->user_email;

		}

			$text  = get_post_meta( get_the_ID(), 'button-text', true );
			$nonce = wp_create_nonce( 'mufwc-subscribe' );

			/*Pass data to the script file*/
			wp_localize_script(
				'mufwc-js',
				'mufwcSettings',
				array(
					'ajaxURL'  => admin_url( 'admin-ajax.php' ),
					'nonce'    => $nonce,
					'userId'   => $user_id,
					'userName' => $user_name,
					'email'    => $email,
					'text'     => $text,
				)
			);

	}


	/**
	 * Guest form for not logged-in users
	 *
	 * @param int $list          the list.
	 * @param int $response_text the response text.
	 *
	 * @return mixed the form html
	 */
	public function guest_form( $list, $response_text ) {

		echo '<form id="mufwc-subscription-form" method="post" action="">';
			echo '<div class="form-group">';
				echo '<label for="mufwc-name">' . esc_html__( 'Your name', 'wc-mailup' ) . '</label>';
				echo '<input type="text" class="form-control" name="mufwc-name" id="mufwc-name" required="required">';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<label for="mufwc-mail">' . esc_html__( 'Your email', 'wc-mailup' ) . '</label>';
				echo '<input type="email" class="form-control" name="mufwc-mail" id="mufwc-mail" required="required">';
			echo '</div>';

			$url = get_option( 'mufwc-privacy-page' );

		if ( $url ) {

			echo '<div class="form-group privacy">';
				printf(
					wp_kses_post(
						/* Translators: the Privacy Policy URL */
						__( 'I consent to the processing of personal data according to the new general data protection regulation of the European Union (GDPR) and subsequent amendments and according to the <a href="%s" target="_blank">Privacy Policy</a> of the site.', 'wc-mailup' )
					),
					esc_url( get_permalink( $url ) )
				);
				echo '<p>';
					echo '<input type="checkbox" name="privacy" checked value="1" required>';
					esc_html_e( ' Accept', 'wc-mailup' );
				echo '</p>';
			echo '</div>';

		}

			wp_nonce_field( 'mufwc-guest-form', 'mufwc-guest-form-nonce' );

			echo '<input type="hidden" name="mufwc-subscription-request" id="mufwc-subscription-request" value="1">';
			echo '<input type="hidden" name="mufwc-list" id="mufwc-list" value="' . esc_attr( $list ) . '">';
			echo '<input type="hidden" name="response-text" id="response-text" value="' . esc_attr( $response_text ) . '">';
			echo '<input type="submit" class="newsletter-form-button" value="' . esc_html__( 'Subscribe', 'wc-mailup' ) . '">';
		echo '</form>';

	}


	/**
	 * The subscription button displayed to logged-in users
	 */
	public function mufwc_button() {

		$activate      = get_post_meta( get_the_ID(), 'mufwc-post-activate', true );
		$list          = get_post_meta( get_the_ID(), 'mufwc-post-list', true );
		$button_text   = get_post_meta( get_the_ID(), 'mufwc-button-text', true );
		$response_text = get_post_meta( get_the_ID(), 'mufwc-response-text', true );

		ob_start();

		if ( $activate ) {

			if ( is_user_logged_in() ) {

				echo '<div id="mufwc-button-container">';

					echo '<div class="mufwc-add-button" ';
						echo 'data-product-id="' . esc_attr( $list ) . '" ';
						echo 'data-list="' . esc_attr( $list ) . '" ';
						echo 'data-response-text="' . esc_attr( $response_text ) . '" ';
						echo '>';

						echo esc_attr( $button_text );

					echo '</div>';

				echo '</div>';

			} elseif ( 'login-form' === get_option( 'mufwc-guest-form' ) ) {

				$text = get_post_meta( get_the_ID(), 'form-text', true );

				echo '<div id="mufwc-wordpress-form">';
					echo '<div class="mufwc-access">';

						echo '<div class="mufwc-buttons">';
							echo '<div class="mufwc-login active">' . esc_html__( 'Login', 'wc-mailup' ) . '</div>';

				if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) {

					echo '<div class="mufwc-register">' . esc_html__( 'Register', 'wc-mailup' ) . '</div>';

				}

							echo '<div class="clear"></div>';

						echo '</div>';

						echo do_shortcode( '[woocommerce_my_account]' );

						echo '<div class="clear"></div>';

					echo '</div>';
				echo '</div>';

			} else {

				$this->guest_form( $list, $response_text );

			}

			$output = ob_get_clean();

			return $output;

		}

	}


	/**
	 * The position of the button based on the admin set
	 *
	 * @param  mixed $content the post/ page content.
	 * @return mixed          the content plus button
	 */
	public function place_button( $content ) {

		$position = get_option( 'mufwc-button-position' );

		if ( 'before' === $position ) {

			$output = $this->mufwc_button() . $content;

		} elseif ( 'after' === $position ) {

			$output = $content . $this->mufwc_button();

		} else {

			$output = $content;

		}

		return $output;

	}


	/**
	 * Shortcode action callback
	 */
	public function add_to_callback() {

		if ( isset( $_POST['mail'], $_POST['mufwc-subscribe-noce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mufwc-subscribe-noce'] ) ), 'mufwc-subscribe' ) ) {

			$host          = get_option( 'mufwc-host' );
			$username      = isset( $_POST['username'] ) ? sanitize_text_field( wp_unslash( $_POST['username'] ) ) : '';
			$mail          = isset( $_POST['mail'] ) ? sanitize_text_field( wp_unslash( $_POST['mail'] ) ) : '';
			$list          = isset( $_POST['list'] ) ? sanitize_text_field( wp_unslash( $_POST['list'] ) ) : '';
			$product_id    = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';
			$response_text = isset( $_POST['response-text'] ) ? sanitize_text_field( wp_unslash( $_POST['response-text'] ) ) : '';
			$error_message = __( 'Sorry but something went wrong, please try again later.', 'wc-mailup' );

			/*Check if the user is already subscribed*/
			$check  = sprintf( '%s/frontend/Xmlchksubscriber.aspx?list=%d&email=%s', $host, $list, $mail );
			$result = wp_remote_post( $check );

			if ( ! is_wp_error( $result ) && isset( $result['body'] ) && 2 !== intval( $result['body'] ) ) {

				/*If not, add him to the list*/
				$url = sprintf( '%s/frontend/xmlSubscribe.aspx?list=%d&email=%s&confirm=0&csvFldNames=campo1&csvFldValues=%s&retCode=1', $host, $list, $mail, $username );

			}

			$subscribe = wp_remote_post( $url );

			if ( ! is_wp_error( $subscribe ) && isset( $subscribe['body'] ) ) {

				if ( 1 !== intval( $subscribe['body'] ) ) {

					echo esc_html( $response_text );

				} else {

					echo esc_html( $error_message );

				}
			} else {

				echo esc_html( $error_message );

			}
		}

		exit;

	}


	/**
	 * The anchor button
	 *
	 * @param  array $vars the options.
	 */
	public function ancor( $vars ) {

		ob_start();

		$details = shortcode_atts(
			array(
				'text' => '',
			),
			$vars,
			'mufwc-subscribe-ancor'
		);

		$logged = is_user_logged_in() ? ' logged' : '';

		echo '<div class="mufwc-add-button-ancor' . esc_attr( $logged ) . '">' . esc_attr( $details['text'] ) . '</div>';

		$output = ob_get_clean();

		return $output;

	}


	/**
	 * Guest form response
	 *
	 * @return void
	 */
	public function subscription_form_response() {

		if ( isset( $_POST['mufwc-guest-form-nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['mufwc-guest-form-nonce'] ) ), 'mufwc-guest-form' ) ) {
			$host          = get_option( 'mufwc-host' );
			$name          = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
			$mail          = isset( $_POST['mail'] ) ? sanitize_text_field( wp_unslash( $_POST['mail'] ) ) : '';
			$list          = isset( $_POST['list'] ) ? sanitize_text_field( wp_unslash( $_POST['list'] ) ) : get_option( 'mufwc-list' );
			$response_text = isset( $_POST['response-text'] ) ? sanitize_text_field( wp_unslash( $_POST['response-text'] ) ) : '';
			$error_message = __( 'Something did\'t work, please try again later.', 'wc-mailup' );
			$product_id    = null;

			/*Check if the user is already subscribed*/
			$check  = sprintf( '%s/frontend/Xmlchksubscriber.aspx?list=%d&email=%s', $host, $list, $mail );
			$result = wp_remote_post( $check );

			if ( ! is_wp_error( $result ) && isset( $result['body'] ) && 2 !== intval( $result['body'] ) ) {

				/*If not, add him to the list*/
				$url = sprintf( '%s/frontend/xmlSubscribe.aspx?list=%d&email=%s&confirm=0&csvFldNames=campo1&csvFldValues=%s&retCode=1', $host, $list, $mail, $name );

			}

			$subscribe = wp_remote_post( $url );

			if ( ! is_wp_error( $subscribe ) && isset( $subscribe['body'] ) ) {

				if ( 1 !== intval( $subscribe['body'] ) ) {

					echo '<div class="mufwc-add-button">' . esc_html( $response_text ) . '</div>';

				} else {

					echo esc_html( $error_message );

				}
			} else {

				echo esc_html( $error_message );

			}
		}

		exit;

	}

}

new MUFWC_Button();

