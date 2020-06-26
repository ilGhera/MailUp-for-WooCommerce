<?php
/**
 * Admin class
 *
 * @author ilGhera
 * @package mailup-for-wp/admin
 * @since 0.9.0
 */
class MUFWP_Admin {

	/**
	 * The constructor
	 */
	public function __construct() {

		add_action( 'admin_menu', array( $this, 'register_admin_menu' ) );
		add_action( 'admin_init', array( $this, 'save_options' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'mufwp_admin_scripts' ) );

	}

	/**
	 * Admin menu
	 */
	public function register_admin_menu() {

		add_menu_page( __( 'MailUp for WordPress', 'mailup-for-wp' ), __( 'MailUp for WP', 'mailup-for-wp' ), 'manage_options', 'mailup-for-wp', array( $this, 'mufwp_options' ), 'dashicons-email', 52 );

	}


	/**
	 * Add js and css files
	 */
	public function mufwp_admin_scripts() {

		$pages      = get_option( 'mufwp-post-types' );
		$admin_page = get_current_screen();

		$pages[]    = 'toplevel_page_mailup-for-wp';

		if ( isset( $admin_page->id ) && in_array( $admin_page->id , $pages ) ) {

			/*css*/
			wp_enqueue_style( 'mufwp-admin-style', MUFWP_URI . 'css/mufwp-admin.css' );
			wp_enqueue_style( 'chosen-style', MUFWP_URI . '/vendor/harvesthq/chosen/chosen.min.css' );
			wp_enqueue_style( 'tzcheckbox-style', MUFWP_URI . 'js/tzCheckbox/jquery.tzCheckbox/jquery.tzCheckbox.css' );

			/*js*/
			wp_enqueue_script( 'mufwp-admin-js', MUFWP_URI . 'js/mufwp-admin.js', array( 'jquery' ), '1.0', true );
			wp_enqueue_script( 'chosen', MUFWP_URI . '/vendor/harvesthq/chosen/chosen.jquery.min.js' );
			wp_enqueue_script( 'tzcheckbox', MUFWP_URI . 'js/tzCheckbox/jquery.tzCheckbox/jquery.tzCheckbox.js', array( 'jquery' ) );

			/*Nonce*/
			// $add_hours_nonce       = wp_create_nonce( 'mufwp-add-hours' );
			// $add_last_minute_nonce = wp_create_nonce( 'mufwp-add-last-minute' );

		}

	}


	/**
	 * Plugin options page
	 */
	public function mufwp_options() {

		/*Right of access*/
		if ( ! current_user_can( 'manage_options' ) ) {

			wp_die( esc_html( __( 'It seems like you don\'t have permission to see this page', 'mailup-for-wp' ) ) );

		}

		/*Page template start*/
		echo '<div class="wrap">';
			echo '<div class="wrap-left">';

				/*Header*/
				echo '<h1 class="mufwp main">' . esc_html( __( 'MailUp for WordPress - Premium', 'mailup-for-wp' ) ) . '</h1>';

				/*Plugin premium key*/
				$key = sanitize_text_field( get_option( 'mufwp-premium-key' ) );

				if ( isset( $_POST['mufwp-premium-key'], $_POST['mufwp-premium-key-nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['mufwp-premium-key-nonce'] ), 'mufwp-premium-key' ) ) { // temp.

					$key = sanitize_text_field( wp_unslash( $_POST['mufwp-premium-key'] ) );

					update_option( 'mufwp-premium-key', $key );

				}

				/*Premium Key Form*/
				echo '<form id="mufwp-premium-key" method="post" action="">';
					echo '<label>' . esc_html( __( 'Premium Key', 'mailup-for-wp' ) ) . '</label>';
					echo '<input type="text" class="regular-text code" name="mufwp-premium-key" id="mufwp-premium-key" placeholder="' . esc_html( __( 'Add your Premium Key', 'mailup-for-wp' ) ) . '" value="' . esc_attr( $key ) . '" />';
					echo '<p class="description">' . esc_html( __( 'Add your Premium Key and keep update your copy of MailUp for WordPress - Premium.', 'mailup-for-wp' ) ) . '</p>';
					wp_nonce_field( 'mufwp-premium-key', 'mufwp-premium-key-nonce' );
					echo '<input type="submit" class="button button-primary" value="' . esc_html( __( 'Save settings', 'mailup-for-wp' ) ) . '" />';
				echo '</form>';

				/*Plugin options menu*/
				echo '<div class="icon32 icon32-wordpress-settings" id="icon-wordpress"><br /></div>';
					echo '<h2 id="mufwp-admin-menu" class="nav-tab-wrapper woo-nav-tab-wrapper">';
					echo '<a href="#" data-link="mufwp-general" class="nav-tab nav-tab-active" onclick="return false;">' . esc_html( __( 'General', 'mailup-for-wp' ) ) . '</a>';
					echo '<a href="#" data-link="mufwp-registration" class="nav-tab" onclick="return false;">' . esc_html( __( 'Site Registration', 'mailup-for-wp' ) ) . '</a>';
					echo '<a href="#" data-link="mufwp-subscription" class="nav-tab" onclick="return false;">' . esc_html( __( 'Subscription Button', 'mailup-for-wp' ) ) . '</a>';
				echo '</h2>';

				/*General options*/
				echo '<div id="mufwp-general" class="mufwp-admin" style="display: block;">';

					include( MUFWP_ADMIN . 'mufwp-admin-general-template.php' );

				echo '</div>';

				/*Site registration options*/
				echo '<div id="mufwp-registration" class="mufwp-admin">';

					include( MUFWP_ADMIN . 'mufwp-admin-registration-template.php' );

				echo '</div>';

				/*Subscription button options*/
				echo '<div id="mufwp-subscription" class="mufwp-admin">';

					include( MUFWP_ADMIN . 'mufwp-admin-subscription-template.php' );

				echo '</div>';

			echo '</div>';
			echo '<div class="wrap-right"></div>';
		echo '</div>';

	}


	/**
	 * Save the options in the db
	 */
	public function save_options() {

		if ( isset( $_POST['mufwp-settings-general-nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['mufwp-settings-general-nonce'] ), 'mufwp-settings-general' ) ) {

			$host    = isset( $_POST['mufwp-host'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwp-host'] ) ) : '';
			$list    = isset( $_POST['mufwp-list'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwp-list'] ) ) : '';
			$group   = isset( $_POST['mufwp-group'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwp-group'] ) ) : '';

			update_option( 'mufwp-host', $host );
			update_option( 'mufwp-list', $list );
			update_option( 'mufwp-group', $group );

		}

		if ( isset( $_POST['mufwp-settings-registration-nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['mufwp-settings-registration-nonce'] ), 'mufwp-settings-registration' ) ) {

			$newsletter = isset( $_POST['mufwp-newsletter'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwp-newsletter'] ) ) : '';
			$confirm = isset( $_POST['mufwp-confirm'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwp-confirm'] ) ) : '';

			update_option( 'mufwp-newsletter', $newsletter );
			update_option( 'mufwp-confirm', $confirm );

		}

		if ( isset( $_POST['mufwp-settings-subscription-nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['mufwp-settings-subscription-nonce'] ), 'mufwp-settings-subscription' ) ) {

			$post_types   = isset( $_POST['mufwp-post-types'] ) ? $_POST['mufwp-post-types'] : array();
			$guest_form   = isset( $_POST['mufwp-guest-form'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwp-guest-form'] ) ) : '';
			$position     = isset( $_POST['mufwp-button-position'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwp-button-position'] ) ) : '';
			$privacy_page = isset( $_POST['mufwp-privacy-page'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwp-privacy-page'] ) ) : '';

			update_option( 'mufwp-post-types', $post_types );
			update_option( 'mufwp-guest-form', $guest_form );
			update_option( 'mufwp-button-position', $position );
			update_option( 'mufwp-privacy-page', $privacy_page );

		}

	}

}
new MUFWP_Admin();
