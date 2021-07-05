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

		$pages[]    = 'toplevel_page_mailup-for-wc';

		if ( isset( $admin_page->id ) && in_array( $admin_page->id , $pages ) ) {

			/*css*/
			wp_enqueue_style( 'mufwc-admin-style', MUFWC_URI . 'css/mufwc-admin.css' );
			wp_enqueue_style( 'chosen-style', MUFWC_URI . 'vendor/harvesthq/chosen/chosen.min.css' );
			wp_enqueue_style( 'tzcheckbox-style', MUFWC_URI . 'js/tzCheckbox/jquery.tzCheckbox/jquery.tzCheckbox.css' );

			/*js*/
			wp_enqueue_script( 'mufwc-admin-js', MUFWC_URI . 'js/mufwc-admin.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'chosen', MUFWC_URI . 'vendor/harvesthq/chosen/chosen.jquery.min.js' );
			wp_enqueue_script( 'tzcheckbox', MUFWC_URI . 'js/tzCheckbox/jquery.tzCheckbox/jquery.tzCheckbox.js', array( 'jquery' ) );

			/*Nonce*/
			// $add_hours_nonce       = wp_create_nonce( 'mufwc-add-hours' );
			// $add_last_minute_nonce = wp_create_nonce( 'mufwc-add-last-minute' );

		}

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
				echo '<h1 class="mufwc main">' . esc_html( __( 'MailUp for WooCommerce - Premium', 'mailup-for-wc' ) ) . '</h1>';

				/*Plugin premium key*/
				$key = sanitize_text_field( get_option( 'mufwc-premium-key' ) );

				if ( isset( $_POST['mufwc-premium-key'], $_POST['mufwc-premium-key-nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['mufwc-premium-key-nonce'] ), 'mufwc-premium-key' ) ) { // temp.

					$key = sanitize_text_field( wp_unslash( $_POST['mufwc-premium-key'] ) );

					update_option( 'mufwc-premium-key', $key );

				}

				/*Premium Key Form*/
				echo '<form id="mufwc-premium-key" method="post" action="">';
					echo '<label>' . esc_html( __( 'Premium Key', 'mailup-for-wc' ) ) . '</label>';
					echo '<input type="text" class="regular-text code" name="mufwc-premium-key" id="mufwc-premium-key" placeholder="' . esc_html( __( 'Add your Premium Key', 'mailup-for-wc' ) ) . '" value="' . esc_attr( $key ) . '" />';
					echo '<p class="description">' . esc_html( __( 'Add your Premium Key and keep update your copy of MailUp for WooCommerce - Premium.', 'mailup-for-wc' ) ) . '</p>';
					wp_nonce_field( 'mufwc-premium-key', 'mufwc-premium-key-nonce' );
					echo '<input type="submit" class="button button-primary" value="' . esc_html( __( 'Save settings', 'mailup-for-wc' ) ) . '" />';
				echo '</form>';

				/*Plugin options menu*/
				echo '<div class="icon32 icon32-wordpress-settings" id="icon-wordpress"><br /></div>';
					echo '<h2 id="mufwc-admin-menu" class="nav-tab-wrapper woo-nav-tab-wrapper">';
					echo '<a href="#" data-link="mufwc-general" class="nav-tab nav-tab-active" onclick="return false;">' . esc_html( __( 'General', 'mailup-for-wc' ) ) . '</a>';
					echo '<a href="#" data-link="mufwc-registration" class="nav-tab" onclick="return false;">' . esc_html( __( 'Site Registration', 'mailup-for-wc' ) ) . '</a>';
					echo '<a href="#" data-link="mufwc-subscription" class="nav-tab" onclick="return false;">' . esc_html( __( 'Subscription Button', 'mailup-for-wc' ) ) . '</a>';
				echo '</h2>';

				/*General options*/
				echo '<div id="mufwc-general" class="mufwc-admin" style="display: block;">';

					include( MUFWC_ADMIN . 'mufwc-admin-general-template.php' );

				echo '</div>';

				/*Site registration options*/
				echo '<div id="mufwc-registration" class="mufwc-admin">';

					include( MUFWC_ADMIN . 'mufwc-admin-registration-template.php' );

				echo '</div>';

				/*Subscription button options*/
				echo '<div id="mufwc-subscription" class="mufwc-admin">';

					include( MUFWC_ADMIN . 'mufwc-admin-subscription-template.php' );

				echo '</div>';

			echo '</div>';
			echo '<div class="wrap-right"></div>';
		echo '</div>';

	}


	/**
	 * Save the options in the db
	 */
	public function save_options() {

		if ( isset( $_POST['mufwc-settings-general-nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['mufwc-settings-general-nonce'] ), 'mufwc-settings-general' ) ) {

			$host  = isset( $_POST['mufwc-host'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-host'] ) ) : '';
			$list  = isset( $_POST['mufwc-list'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-list'] ) ) : '';
			$group = isset( $_POST['mufwc-group'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-group'] ) ) : '';

			update_option( 'mufwc-host', $host );
			update_option( 'mufwc-list', $list );
			update_option( 'mufwc-group', $group );

		}

		if ( isset( $_POST['mufwc-settings-registration-nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['mufwc-settings-registration-nonce'] ), 'mufwc-settings-registration' ) ) {

			$newsletter = isset( $_POST['mufwc-newsletter'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-newsletter'] ) ) : '';
			$confirm    = isset( $_POST['mufwc-confirm'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-confirm'] ) ) : '';

			update_option( 'mufwc-newsletter', $newsletter );
			update_option( 'mufwc-confirm', $confirm );

		}

		if ( isset( $_POST['mufwc-settings-subscription-nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['mufwc-settings-subscription-nonce'] ), 'mufwc-settings-subscription' ) ) {

			$post_types   = isset( $_POST['mufwc-post-types'] ) ? $_POST['mufwc-post-types'] : array();
			$guest_form   = isset( $_POST['mufwc-guest-form'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-guest-form'] ) ) : '';
			$position     = isset( $_POST['mufwc-button-position'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-button-position'] ) ) : '';
			$privacy_page = isset( $_POST['mufwc-privacy-page'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-privacy-page'] ) ) : '';

			update_option( 'mufwc-post-types', $post_types );
			update_option( 'mufwc-guest-form', $guest_form );
			update_option( 'mufwc-button-position', $position );
			update_option( 'mufwc-privacy-page', $privacy_page );

		}

	}

}
new MUFWC_Admin();

