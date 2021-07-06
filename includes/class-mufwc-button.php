<?php
/**
 * Add logged in user to MailUp
 *
 * @author ilGhera
 * @package mailup-for-wc/includes
 * @since 0.9.0
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
		wp_enqueue_style( 'mufwc-style', MUFWC_URI . 'css/mufwc.css' );

		/*js*/
		wp_enqueue_script( 'mufwc-js', MUFWC_URI . 'js/mufwc.js', array( 'jquery' ), '0.9.0', true );

			$product_id = get_the_ID(); // temp.
			$user_id    = get_current_user_id();
			$user_name  = null;
			$email      = null;

			if ( $user_id ) {

				$user_info  = get_userdata( $user_id );
				$user_name  = $user_info->user_login;
				$email      = $user_info->user_email;

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
	 * @param int $group         the group.
	 * @param int $response_text the response text.
	 * @param int $redirect the redirect page id.
	 * @return mixed the form html
	 */
	public function guest_form( $list, $group, $response_text, $redirect ) {

		echo '<form id="mufwc-subscription-form" method="post" action="">';
			echo '<div class="form-group">';
				echo '<label for="mufwc-name">' . esc_html__( 'Your name', 'mailup-for-wc' ) . '</label>';
				echo '<input type="text" class="form-control" name="mufwc-name" id="mufwc-name" required="required">';
			echo '</div>';
			echo '<div class="form-group">';
				echo '<label for="mufwc-mail">' . esc_html__( 'Your email', 'mailup-for-wc' ) . '</label>';
				echo '<input type="email" class="form-control" name="mufwc-mail" id="mufwc-mail" required="required">';
			echo '</div>';

			$url = get_option( 'mufwc-privacy-page' );

			if ( $url ) {

				echo '<div class="form-group privacy">';
					printf( __( 'I consent to the processing of personal data according to the new general data protection regulation of the European Union (GDPR) and subsequent amendments and according to the <a href="%s" target="_blank">Privacy Policy</a> of the site.', 'mailup-for-wc' ), get_permalink( $url ) );
					echo '<p>';
						echo '<input type="checkbox" name="privacy" checked value="1" required>';
						esc_html_e( ' Accept', 'mailup-for-wc' );
					echo '</p>';
				echo '</div>';

			}

			wp_nonce_field( 'mufwc-guest-form', 'mufwc-guest-form-nonce' );

			echo '<input type="hidden" name="mufwc-subscription-request" id="mufwc-subscription-request" value="1">';
			echo '<input type="hidden" name="mufwc-list" id="mufwc-list" value="' . esc_attr( $list ) . '">';
			echo '<input type="hidden" name="mufwc-group" id="mufwc-group" value="' . esc_attr( $group ) . '">';
			echo '<input type="hidden" name="response-text" id="response-text" value="' . esc_attr( $response_text ) . '">';
			echo '<input type="hidden" name="mufwc-redirect" id="mufwc-redirect" value="' . esc_attr( $redirect ) . '">';
			echo '<input type="submit" class="newsletter-form-button" value="' . esc_html__( 'Subscribe', 'mailup-for-wc' ) . '">';
		echo '</form>';

	}


	/**
	 * The subscription button displayed to logged-in users
	 */
	public function mufwc_button() {

		$activate      = get_post_meta( get_the_ID(), 'mufwc-post-activate', true );
		$list          = get_post_meta( get_the_ID(), 'mufwc-post-list', true );
		$group         = get_post_meta( get_the_ID(), 'mufwc-post-group', true );
		$before_text   = get_post_meta( get_the_ID(), 'mufwc-before-text', true );
		$button_text   = get_post_meta( get_the_ID(), 'mufwc-button-text', true );
		$response_text = get_post_meta( get_the_ID(), 'mufwc-response-text', true );
		$redirect      = get_post_meta( get_the_ID(), 'mufwc-redirect', true );

		ob_start();

		if ( $activate ) {

			if ( $before_text ) {

				echo '<div class="mufwc-before-text">' . esc_html( $before_text ) . '</div>';

			}

			if ( is_user_logged_in() ) {

				echo '<div id="mufwc-button-container">';


					echo '<div class="mufwc-add-button" ';
						echo 'data-product-id="' . esc_attr( $list ) . '" ';
						echo 'data-list="' . esc_attr( $list ) . '" ';
						echo 'data-group="' . esc_attr( $group ) . '" ';
						echo 'data-response-text="' . esc_attr( $response_text ) . '" ';
						echo 'data-redirect="' . esc_attr( $redirect ) . '"';
						echo '>';

						echo esc_attr( $button_text );

					echo '</div>';

				echo '</div>';

			} elseif ( 'login-form' === get_option( 'mufwc-guest-form' ) ) {

				$text = get_post_meta( get_the_ID(), 'form-text', true );

				echo '<div id="mufwc-wordpress-form">';
					/* echo '<div class="mufwc-before-text">' . esc_attr( $text ) . '</div>'; */
					echo '<div class="mufwc-access">';

						echo '<div class="mufwc-buttons">';
							echo '<div class="mufwc-login active">' . esc_html__( 'Login', 'mailup-for-wc' ) . '</div>';

                            if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) {

                                echo '<div class="mufwc-register">' . esc_html__( 'Register', 'mailup-for-wc' ) . '</div>';

                            }

							echo '<div class="clear"></div>';

						echo '</div>';

						echo do_shortcode( '[woocommerce_my_account]' );

						echo '<div class="clear"></div>';

					echo '</div>';
				echo '</div>';

			} else {

				$this->guest_form( $list, $group, $response_text, $redirect );

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

		if ( isset( $_POST['mail'], $_POST['mufwc-subscribe-noce'] ) && wp_verify_nonce( wp_unslash( $_POST['mufwc-subscribe-noce'] ), 'mufwc-subscribe' ) ) {

			$host          = get_option( 'mufwc-host' );
			$username      = isset( $_POST['username'] ) ? sanitize_text_field( wp_unslash( $_POST['username'] ) ) : '';
			$mail          = isset( $_POST['mail'] ) ? sanitize_text_field( wp_unslash( $_POST['mail'] ) ) : '';
			$list          = isset( $_POST['list'] ) ? sanitize_text_field( wp_unslash( $_POST['list'] ) ) : '';
			$group         = isset( $_POST['group'] ) ? sanitize_text_field( wp_unslash( $_POST['group'] ) ) : get_option( 'mufwc-group' ); // temp.
			$product_id    = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : '';
			$response_text = isset( $_POST['response-text'] ) ? sanitize_text_field( wp_unslash( $_POST['response-text'] ) ) : '';
			$get_redirect  = isset( $_POST['redirect'] ) ? sanitize_text_field( wp_unslash( $_POST['redirect'] ) ) : '';
			$error_message = __( 'Sorry but something went wrong, please try again later.', 'mailup-for-wc' );
			$redirect      = null;

			if ( $get_redirect ) {

				$redirect = get_permalink( $get_redirect ); // . '?product_id=' . $product_id;

			}

			/*Check if the user is already subscribed*/
			$check = sprintf( '%s/frontend/Xmlchksubscriber.aspx?list=%d&email=%s', $host, $list, $mail );
			$result = wp_remote_post( $check );

			if ( ! is_wp_error( $result ) && isset( $result['body'] ) && 2 == $result['body'] ) {

				/*If yes, just update his profile by adding him to the specified group*/

				$url = sprintf( '%s/frontend/XmlUpdSubscriber.aspx?list=%d&email=%s&group=%d', $host, $list, $mail, $group );

			} else {

				/*If not, add him to the list and the group*/
				$url = sprintf( '%s/frontend/xmlSubscribe.aspx?list=%d&group=%d&email=%s&confirm=0&csvFldNames=campo1&csvFldValues=%s&retCode=1', $host, $list, $group, $mail, $username );

			}

			$subscribe = wp_remote_post( $url );

			if ( ! is_wp_error( $subscribe ) && isset( $subscribe['body'] ) ) {

				if ( 1 != $subscribe['body'] ) {

					echo esc_html( $response_text );

					if ( $redirect ) { ?>
						<script>
						setTimeout(function(){ 
							window.location = '<?php echo esc_url( $redirect ); ?>';
						}, 3000);
						</script>
						<?php
					}

				} else {

					error_log( 'TEST 100' );
					echo esc_html( $error_message );

				}

			} else {

				error_log( 'TEST 200' );
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

		if ( isset( $_POST['mufwc-guest-form-nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['mufwc-guest-form-nonce'] ), 'mufwc-guest-form' ) ) {
			$host          = get_option( 'mufwc-host' );
			$name          = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
			$mail          = isset( $_POST['mail'] ) ? sanitize_text_field( wp_unslash( $_POST['mail'] ) ) : '';
			$list          = isset( $_POST['list'] ) ? sanitize_text_field( wp_unslash( $_POST['list'] ) ) : get_option( 'mufwc-list' );
			$group         = isset( $_POST['group'] ) ? sanitize_text_field( wp_unslash( $_POST['group'] ) ) : get_option( 'mufwc-group' );
			$response_text = isset( $_POST['response-text'] ) ? sanitize_text_field( wp_unslash( $_POST['response-text'] ) ) : '';
			$get_redirect  = isset( $_POST['redirect'] ) ? sanitize_text_field( wp_unslash( $_POST['redirect'] ) ) : '';
			$error_message = __( 'Something did\'t work, please try again later.', 'mailup-for-wc' );
			$product_id    = null;
			$redirect      = null;

			if ( $get_redirect && 'Select a page' !== $get_redirect ) {

				$redirect = get_permalink( $get_redirect ); // . '?product_id=' . $product_id;

			}

			/*Check if the user is already subscribed*/
			$check = sprintf( '%s/frontend/Xmlchksubscriber.aspx?list=%d&email=%s', $host, $list, $mail );
			$result = wp_remote_post( $check );

			if ( ! is_wp_error( $result ) && isset( $result['body'] ) && 2 == $result['body'] ) {

				/*If yes, just update his profile by adding him to the specified group*/				
				$url = sprintf( '%s/frontend/XmlUpdSubscriber.aspx?list=%d&email=%s&group=%d', $host, $list, $mail, $group );

			} else {

				/*If not, add him to the list and the group*/
				$url = sprintf( '%s/frontend/xmlSubscribe.aspx?list=%d&group=%d&email=%s&confirm=0&csvFldNames=campo1&csvFldValues=%s&retCode=1', $host, $list, $group, $mail, $name );

			}

			$subscribe = wp_remote_post( $url );

			if ( ! is_wp_error( $subscribe ) && isset( $subscribe['body'] ) ) {

				if ( 1 != $subscribe['body'] ) {

					echo '<div class="mufwc-add-button">' . esc_html( $response_text ) . '</div>';

					if ( $redirect ) {
						?>
						<script>
						setTimeout(function(){ 
							window.location = '<?php echo esc_url( $redirect ); ?>';
						}, 3000);
						</script>
						<?php
					}

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
