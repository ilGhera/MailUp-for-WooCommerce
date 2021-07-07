<?php
/**
 * Handles the post meta-boxes
 *
 * @author ilGhera
 * @package mailup-for-wc/includes
 * @since 0.9.0
 */
class MUFWC_Edit_Post {

	/**
	 * The constructor
	 */
	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'mufwc_add_meta_box' ) );
		add_action( 'save_post', array( $this, 'mufwc_save_post_metas' ) );
		// add_action( 'admin_enqueue_scripts', array( $this, 'mufwc_edit_scripts' ) );

		$this->main_list  = get_option( 'mufwc-list' );

	}


	/**
	 * Add meta box
	 *
	 * @param  string $post_type reservations.
	 */
	public function mufwc_add_meta_box( $post_type ) {

		$post_types = get_option( 'mufwc-post-types' );
		$admin_page = get_current_screen();

        if ( is_array( $post_types ) ) {

            if ( isset( $admin_page->id ) && in_array( $admin_page->id, $post_types ) ) {

                add_meta_box( 'mufwc-box', __( 'MailUp for WooCommerce', 'mailup-for-wc' ), array( $this, 'mufwc_add_meta_box_callback' ) );

            }

        }

	}


	/**
	 * The meta box content for reservation
	 *
	 * @return void
	 */
	public function mufwc_add_meta_box_callback() {

		include( MUFWC_INCLUDES . 'mufwc-meta-box-template.php' );

	}


	/**
	 * Save the post metas
	 *
	 * @param  int $post_id the post id.
	 * @return void
	 */
	public function mufwc_save_post_metas( $post_id ) {

		if ( isset( $_POST['mufwc-post-list'], $_POST['mufwc-post-metas-nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['mufwc-post-metas-nonce'] ), 'mufwc-post-metas' ) ) {

			$activate      = isset( $_POST['mufwc-post-activate'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-post-activate'] ) ) : 0;
			$list          = isset( $_POST['mufwc-post-list'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-post-list'] ) ) : '';
			$post_list     = $list ? $list : $this->main_list;
			$group         = isset( $_POST['mufwc-post-group'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-post-group'] ) ) : '';
			$before_text   = isset( $_POST['mufwc-before-text'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-before-text'] ) ) : '';
			$button_text   = isset( $_POST['mufwc-button-text'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-button-text'] ) ) : '';
			$response_text = isset( $_POST['mufwc-response-text'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-response-text'] ) ) : '';
			$redirect      = isset( $_POST['mufwc-redirect'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwc-redirect'] ) ) : '';


			update_post_meta( $post_id, 'mufwc-post-activate', $activate );
			update_post_meta( $post_id, 'mufwc-post-list', $post_list );
			update_post_meta( $post_id, 'mufwc-post-group', $group );
			update_post_meta( $post_id, 'mufwc-before-text', $before_text );
			update_post_meta( $post_id, 'mufwc-button-text', $button_text );
			update_post_meta( $post_id, 'mufwc-response-text', $response_text );
			update_post_meta( $post_id, 'mufwc-redirect', $redirect );


		}

	}
}

new MUFWC_Edit_Post();
