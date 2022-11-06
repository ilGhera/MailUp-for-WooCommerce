<?php
/**
 * Plugin main options form
 *
 * @author ilGhera
 * @package mailup-for-wc/admin
 * @since 0.9.2
 */

$types    = get_option( 'mufwc-post-types' );
$position = get_option( 'mufwc-button-position' );
?>

<form name="mufwc-subscription-options" id="mufwc-subscription-options" method="post" action="">

	<table class="form-table">

		<tr>
			<th scope="row"><?php esc_html_e( 'Post types', 'wc-mailup' ); ?></th>
			<td>
				<select name="mufwc-post-types[]" class="mufwc-select regular-text" multiple>
					<?php
					$post_types = get_post_types(
						array(
							'public' => true,
						),
						'objects'
					);

					if ( is_array( $post_types ) ) {

						foreach ( $post_types as $p_type ) {

							echo '<option value="' . esc_attr( $p_type->name ) . '"' . ( is_array( $types ) && in_array( $p_type->name, $types, true ) ? ' selected="selected"' : '' ) . '>' . esc_html( $p_type->label ) . '</option>';

						}
					}
					?>
				</select>
			</td>
		<tr>
			<th scope="row"><?php esc_html_e( 'Guest form', 'wc-mailup' ); ?></th>
			<td>
				<select name="mufwc-guest-form" id="mufwc-guest-form" class="mufwc-select">
					<option value="login-form" selected="selected"><?php esc_html_e( 'Login/ Register', 'wc-mailup' ); ?></option>
					<option value="email"><?php esc_html_e( 'Email only', 'wc-mailup' ); ?></option>
				</select>
				<p class="description"><?php esc_html_e( 'Ask to access to not logged-in users or just the email for newsletter subscription.', 'wc-mailup' ); ?></p>
				<?php MUFWC_admin::go_premium(); ?>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Button position', 'wc-mailup' ); ?></th>
			<td>
				<select name="mufwc-button-position" class="mufwc-select">
					<option value="before"<?php echo 'before' === $position ? ' selected="selected"' : ''; ?>><?php esc_html_e( 'Before the content', 'wc-mailup' ); ?></option>
					<option value="after"<?php echo 'after' === $position ? ' selected="selected"' : ''; ?>><?php esc_html_e( 'After the content', 'wc-mailup' ); ?></option>
					<option value="custom"<?php echo 'custom' === $position ? ' selected="selected"' : ''; ?>><?php esc_html_e( 'Custom', 'wc-mailup' ); ?></option>
				</select>
				<p class="description"><?php esc_html_e( 'Custom position requires use of shortcode [mailup-subscribe] provided by the plugin.', 'wc-mailup' ); ?></p>
			</td>
		</tr>
		<tr class="privacy-field">
			<th scope="row"><?php esc_html_e( 'Privacy', 'wc-mailup' ); ?></th>
			<td>
				<select name="mufwc-privacy-page" class="mufwc-select" disabled>
					<option value=""><?php esc_html_e( 'Select a page', 'wc-mailup' ); ?></option>
					<?php
					$p_pages = get_pages();

					if ( $p_pages ) {

						foreach ( $p_pages as $p_page ) {

							echo '<option value="' . esc_attr( $p_page->ID ) . '">' . esc_html( $p_page->post_title ) . '</option>';

						}
					}
					?>
				</select>
				<p class="description"><?php esc_html_e( 'Select the page with the privacy conditions', 'wc-mailup' ); ?></p>
				<?php MUFWC_admin::go_premium(); ?>
			</td>
		</tr>

		<?php wp_nonce_field( 'mufwc-settings-subscription', 'mufwc-settings-subscription-nonce' ); ?>

	</table>

	<p class="submit">
		<input type="hidden" name="mufwc-sent" id="mufwc-sent" value="true">
		<input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save changes', 'wc-mailup' ); ?>">
	</p>

</form>


