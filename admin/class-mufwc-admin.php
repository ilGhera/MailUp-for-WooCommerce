<?php
/**
 * Admin class
 *
 * @author ilGhera
 * @package mailup-for-wc/admin
 * @since 0.9.0
 */
class MUFWC_Admin {

	/**
	 * The constructor
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'save_options' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'mufwc_admin_scripts' ) );

	}

	/**
	 * Admin menu
	 */
	public function register_admin_menu() {

		add_menu_page( __( 'MailUp for WooCommerce', 'mailup-for-wc' ), __( 'MailUp for WC', 'mailup-for-wc' ), 'manage_options', 'mailup-for-wc', array( $this, 'mufwc_options' ), 'dashicons-email', 52 );

	}


	/**
	 * Add js and css files
	 */
	public function mufwc_admin_scripts() {

		$pages      = get_option( 'mufwc-post-types' );
		$admin_page = get_current_screen();

		$pages[] = 'toplevel_page_mailup-for-wc';

		wp_enqueue_style( 'mufwc-admin-style', MUFWC_URI . 'css/mufwc-admin.css', array(), filemtime( MUFWC_DIR . 'css/mufwc-admin.css' ) );

		if ( isset( $admin_page->id ) && in_array( $admin_page->id, $pages, true ) || 'product' === $admin_page->id ) {

			/*css*/
			wp_enqueue_style( 'chosen-style', MUFWC_URI . 'vendor/harvesthq/chosen/chosen.min.css', array(), filemtime( MUFWC_DIR . 'vendor/harvesthq/chosen/chosen.min.css' ) );
			wp_enqueue_style( 'tzcheckbox-style', MUFWC_URI . 'js/tzCheckbox/jquery.tzCheckbox/jquery.tzCheckbox.css', array(), filemtime( MUFWC_DIR . 'js/tzCheckbox/jquery.tzCheckbox/jquery.tzCheckbox.css' ) );
			wp_enqueue_style( 'bootstrap-iso', MUFWC_URI . 'css/bootstrap-iso.css' );

			/*js*/
			wp_enqueue_script( 'mufwc-admin-js', MUFWC_URI . 'js/mufwc-admin.js', array( 'jquery' ), filemtime( MUFWC_DIR . 'js/mufwc-admin.js' ), true );
			wp_enqueue_script( 'chosen', MUFWC_URI . 'vendor/harvesthq/chosen/chosen.jquery.min.js', array(), filemtime( MUFWC_DIR . 'vendor/harvesthq/chosen/chosen.jquery.min.js' ), false );
			wp_enqueue_script( 'tzcheckbox', MUFWC_URI . 'js/tzCheckbox/jquery.tzCheckbox/jquery.tzCheckbox.js', array( 'jquery' ), filemtime( MUFWC_DIR . 'js/tzCheckbox/jquery.tzCheckbox/jquery.tzCheckbox.js' ), false );

		}

	}


	/**
	 * Button premium call to action
	 *
	 * @return void
	 */
	public static function go_premium() {

		echo '<div class="bootstrap-iso">';
			echo '<span class="label label-warning premium">';
				echo '<a href="https://www.ilghera.com/product/mailup-for-woocommerce-premium" target="_blank" ';
					echo 'title="' . esc_html__( 'This is a premium functionality, click here for details', 'mailup-for-wc' ) . '"';
				echo '>Premium</a>';
			echo '</span>';
		echo '</div>';

	}


	/**
	 * Plugin options page
	 */
	public function mufwc_options() {

		/*Right of access*/
		if ( ! current_user_can( 'manage_options' ) ) {

			wp_die( esc_html( __( 'It seems like you don\'t have permission to see this page', 'mailup-for-wc' ) ) );

		}

		/*Page template start*/
		echo '<div class="wrap">';
			echo '<div class="wrap-left">';

				/*Header*/
				echo '<h1 class="mufwc main">' . esc_html( __( 'MailUp for WooCommerce', 'mailup-for-wc' ) ) . '</h1>';

				/*Plugin options menu*/
				echo '<div class="icon32 icon32-wordpress-settings" id="icon-wordpress"><br /></div>';
					echo '<h2 id="mufwc-admin-menu" class="nav-tab-wrapper woo-nav-tab-wrapper">';
					echo '<a href="#" data-link="mufwc-general" class="nav-tab nav-tab-active" onclick="return false;">' . esc_html( __( 'General', 'mailup-for-wc' ) ) . '</a>';
					echo '<a href="#" data-link="mufwc-registration" class="nav-tab" onclick="return false;">' . esc_html( __( 'Site Registration', 'mailup-for-wc' ) ) . '</a>';
					echo '<a href="#" data-link="mufwc-subscription" class="nav-tab" onclick="return false;">' . esc_html( __( 'Subscription Button', 'mailup-for-wc' ) ) . '</a>';
				echo '</h2>';

				/*General options*/
				echo '<div id="mufwc-general" class="mufwc-admin" style="display: block;">';

					include MUFWC_ADMIN . 'mufwc-admin-general-template.php';

				echo '</div>';

				/*Site registration options*/
				echo '<div id="mufwc-registration" class="mufwc-admin">';

					include MUFWC_ADMIN . 'mufwc-admin-registration-template.php';

				echo '</div>';

				/*Subscription button options*/
				echo '<div id="mufwc-subscription" class="mufwc-admin">';

					include MUFWC_ADMIN . 'mufwc-admin-subscription-template.php';

				echo '</div>';

			echo '</div>';
            echo '<div class="wrap-right" style="float:left; width:30%; text-align:center; padding-top:3rem;">';
                echo '<iframe width="300" height="800" scrolling="no" src="https://www.ilghera.com/images/mufwc-iframe.html"></iframe>';
            echo '</div>';
		echo '</div>';

	}


    /**
	 * Sanitize every single array element
	 *
	 * @param  array $array the array to sanitize.
	 * @return array        the sanitized array.
	 */
	public function sanitize_array( $array ) {

		$output = array();

		if ( is_array( $array ) && ! empty( $array ) ) {

			foreach ( $array as $key => $value ) {

				$output[ $key ] = sanitize_text_field( wp_unslash( $value ) );

			}

		}

		return $output;

	}


	/**
	 * Save the options in the db
	 */
	public function save_options() {

		if ( isset( $_POST['mufwc-settings-general-nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['mufwc-settings-general-nonce'] ), 'mufwc-settings-general' ) ) {

			$host = isset( $_POST['mufwc-host'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-host'] ) ) : '';

			update_option( 'mufwc-host', $host );

		}

		if ( isset( $_POST['mufwc-settings-registration-nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['mufwc-settings-registration-nonce'] ), 'mufwc-settings-registration' ) ) {

			$newsletter = isset( $_POST['mufwc-newsletter'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-newsletter'] ) ) : '';
			$confirm    = isset( $_POST['mufwc-confirm'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-confirm'] ) ) : '';
			$list       = isset( $_POST['mufwc-list'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-list'] ) ) : '';
			$group      = isset( $_POST['mufwc-group'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-group'] ) ) : '';

			update_option( 'mufwc-newsletter', $newsletter );
			update_option( 'mufwc-confirm', $confirm );
			update_option( 'mufwc-list', $list );
			update_option( 'mufwc-group', $group );

		}

		if ( isset( $_POST['mufwc-settings-subscription-nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['mufwc-settings-subscription-nonce'] ), 'mufwc-settings-subscription' ) ) {

			$post_types   = isset( $_POST['mufwc-post-types'] ) ? $this->sanitize_array( $_POST['mufwc-post-types'] ) : array();
			$guest_form   = isset( $_POST['mufwc-guest-form'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-guest-form'] ) ) : '';
			$position     = isset( $_POST['mufwc-button-position'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-button-position'] ) ) : '';
			$privacy_page = isset( $_POST['mufwc-privacy-page'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-privacy-page'] ) ) : '';

			if ( 'login-form' === $guest_form ) {

				/* This option must be enabled */
				update_option( 'woocommerce_enable_myaccount_registration', 'yes' );

			}

			update_option( 'mufwc-post-types', $post_types );
			update_option( 'mufwc-guest-form', $guest_form );
			update_option( 'mufwc-button-position', $position );
			update_option( 'mufwc-privacy-page', $privacy_page );

		}

	}

}
new MUFWC_Admin();

