<?php
/**
 * Handles the post meta-boxes
 *
 * @author ilGhera
 * @package mailup-for-wp/includes
 * @since 0.9.0
 */
class MUFWP_Edit_Post {

	/**
	 * The constructor
	 */
	public function __construct() {

		add_action( 'add_meta_boxes', array( $this, 'mufwp_add_meta_box' ) );
		add_action( 'save_post', array( $this, 'mufwp_save_post_metas' ) );
		// add_action( 'admin_enqueue_scripts', array( $this, 'mufwp_edit_scripts' ) );

		$this->main_list  = get_option( 'mufwp-list' );

	}


	/**
	 * Add meta box
	 *
	 * @param  string $post_type reservations.
	 */
	public function mufwp_add_meta_box( $post_type ) {

		$post_types = get_option( 'mufwp-post-types' );
		$admin_page = get_current_screen();

		if ( isset( $admin_page->id ) && in_array( $admin_page->id, $post_types ) ) {

			add_meta_box( 'mufwp-box', __( 'MailUp for WordPress', 'mailup-for-wp' ), array( $this, 'mufwp_add_meta_box_callback' ) );

		}

	}


	/**
	 * The meta box content for reservation
	 *
	 * @return void
	 */
	public function mufwp_add_meta_box_callback() {

		include( MUFWP_INCLUDES . 'mufwp-meta-box-template.php' );

	}


	/**
	 * Save the post metas
	 *
	 * @param  int $post_id the post id.
	 * @return void
	 */
	public function mufwp_save_post_metas( $post_id ) {

		if ( isset( $_POST['mufwp-post-list'], $_POST['mufwp-post-metas-nonce'] ) && wp_verify_nonce( wp_unslash( $_POST['mufwp-post-metas-nonce'] ), 'mufwp-post-metas' ) ) {

			$activate      = isset( $_POST['mufwp-post-activate'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwp-post-activate'] ) ) : 0;
			$list          = isset( $_POST['mufwp-post-list'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwp-post-list'] ) ) : '';
			$post_list     = $list ? $list : $this->main_list;
			$group         = isset( $_POST['mufwp-post-group'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwp-post-group'] ) ) : '';
			$before_text   = isset( $_POST['mufwp-before-text'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwp-before-text'] ) ) : '';
			$button_text   = isset( $_POST['mufwp-button-text'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwp-button-text'] ) ) : '';
			$response_text = isset( $_POST['mufwp-response-text'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwp-response-text'] ) ) : '';
			$redirect      = isset( $_POST['mufwp-redirect'] ) ? sanitize_text_field( wp_unslash( $_POST['mufwp-redirect'] ) ) : '';


			update_post_meta( $post_id, 'mufwp-post-activate', $activate );
			update_post_meta( $post_id, 'mufwp-post-list', $post_list );
			update_post_meta( $post_id, 'mufwp-post-group', $group );
			update_post_meta( $post_id, 'mufwp-before-text', $before_text );
			update_post_meta( $post_id, 'mufwp-button-text', $button_text );
			update_post_meta( $post_id, 'mufwp-response-text', $response_text );
			update_post_meta( $post_id, 'mufwp-redirect', $redirect );


		}

	}
}

new MUFWP_Edit_Post();
