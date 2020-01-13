<?php
/**
 * Plugin main options form
 *
 * @author ilGhera
 * @package mailup-for-wp/admin
 * @since 0.9.0
 */

$types        = get_option( 'mufwp-post-types' );
$guest_form   = get_option( 'mufwp-guest-form' );
$position     = get_option( 'mufwp-button-position' );
$privacy_page = get_option( 'mufwp-privacy-page' );
?>

<form name="mufwp-subscription-options" id="mufwp-subscription-options" method="post" action="">
	
	<table class="form-table">

		<tr>
			<th scope="row"><?php esc_html_e( 'Post types', 'mailup-for-wp' ); ?></th>
			<td>
				<select name="mufwp-post-types[]" class="mufwp-select" multiple>
					<?php
					$post_types = get_post_types(
						array(
							'public' => true,
						),
						'objects'
					);

					if ( is_array( $post_types ) ) {

						foreach ( $post_types as $p_type ) {

							echo '<option value="' . esc_attr( $p_type->name ) . '"' . ( in_array( $p_type->name, $types ) ? ' selected="selected"' : '' ) . '>' . esc_html( $p_type->label ) . '</option>';

						}

					}
					?>
				</select>
			</td>
		<tr>
			<th scope="row"><?php esc_html_e( 'Guest form', 'mailup-for-wp' ); ?></th>
			<td>
				<select name="mufwp-guest-form" id="mufwp-guest-form" class="mufwp-select">
					<option value="login-form"<?php echo 'login-form' === $guest_form ? ' selected="selected"' : ''; ?>><?php esc_html_e( 'Login/ Register', 'mailup-for-wp' ); ?></option>
					<option value="email"<?php echo 'email' === $guest_form ? ' selected="selected"' : ''; ?>><?php esc_html_e( 'Email only', 'mailup-for-wp' ); ?></option>
				</select>
				<p class="description"><?php esc_html_e( 'Ask to access to not logged-in users or just the email for newsletter subscription.', 'mailup-for-wp' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Button position', 'mailup-for-wp' ); ?></th>
			<td>
				<select name="mufwp-button-position" class="mufwp-select">
					<option value="before"<?php echo 'before' === $position ? ' selected="selected"' : ''; ?>><?php esc_html_e( 'Before the content', 'mailup-for-wp' ); ?></option>
					<option value="after"<?php echo 'after' === $position ? ' selected="selected"' : ''; ?>><?php esc_html_e( 'After the content', 'mailup-for-wp' ); ?></option>
					<option value="custom"<?php echo 'custom' === $position ? ' selected="selected"' : ''; ?>><?php esc_html_e( 'Custom', 'mailup-for-wp' ); ?></option>
				</select>
				<p class="description"><?php esc_html_e( 'Custom position requires use of shortcode [mailup-subscribe] provided by the plugin.', 'mailup-for-wp' ); ?></p>
			</td>
		</tr>
		<tr class="privacy-field">
			<th scope="row"><?php esc_html_e( 'Privacy', 'mailup-for-wp' ); ?></th>
			<td>
				<select name="mufwp-privacy-page" class="mufwp-select">
					<option value=""><?php esc_html_e( 'Select a page', 'mailup-for-wp' ); ?></option>
					<?php
					$p_pages = get_pages();

					if ( $p_pages ) {

						foreach ( $p_pages as $p_page ) {

							echo '<option value="' . esc_attr( $p_page->ID ) . '"' . ( $p_page->ID == $privacy_page ? ' selected="selected"' : '' ) . '>' . esc_html( $p_page->post_title ) . '</option>';

						}
					}
					?>
				</select>
				<p class="description"><?php esc_html_e( 'Select the page with the privacy conditions', 'mailup-for-wp' ); ?></p>
			</td>
		</tr>

		<?php wp_nonce_field( 'mufwp-settings-subscription', 'mufwp-settings-subscription-nonce' ); ?>
	
	</table>

	<p class="submit">
		<input type="hidden" name="mufwp-sent" id="mufwp-sent" value="true">
		<input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save changes', 'mailup-for-wp' ); ?>">
	</p>

</form>


