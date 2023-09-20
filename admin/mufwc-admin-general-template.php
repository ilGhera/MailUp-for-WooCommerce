<?php
/**
 * Plugin main options form
 *
 * @author ilGhera
 * @package mailup-for-wc/admin
 *
 * @since 1.0.2
 */

$host       = get_option( 'mufwc-host' );
$list       = get_option( 'mufwc-list' );
$group      = get_option( 'mufwc-group' );
$confirm    = get_option( 'mufwc-confirm' );
$newsletter = get_option( 'mufwc-newsletter' );
?>

<form name="mufwc-general-options" id="mufwc-general-options" class="one-of" method="post" action="">

	<table class="form-table">

		<tr>
			<th scope="row"><?php esc_html_e( 'Endpoint/ Console url', 'wc-mailup' ); ?></th>
			<td>
				<input type="text" name="mufwc-host" id="mufwc-host" class="regular-text" value="<?php echo esc_attr( $host ); ?>">
				<p class="description">
					<?php esc_html_e( 'Do you need help? Click ', 'wc-mailup' ); ?>			
					<?php echo sprintf( '<a href="%s" target="_blank">' . esc_html__( 'here', 'wc-mailup' ) . '</a>', esc_url( 'http://help.mailup.com/display/mailupapi/MailUp+API+Credentials' ) ); ?>
				</p>
			</td>
		</tr>

		<?php wp_nonce_field( 'mufwc-settings-general', 'mufwc-settings-general-nonce' ); ?>

	</table>

	<p class="submit">
		<input type="hidden" name="mufwc-sent" id="mufwc-sent" value="true">
		<input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save changes', 'wc-mailup' ); ?>">
	</p>

</form>
<form name="mufwc-registration-options" id="mufwc-registration-options" method="post" action="">

	<table class="form-table">

		<tr class="mufwc-newsletter-field">
			<th scope="row"><?php esc_html_e( 'Registration form', 'wc-mailup' ); ?></th>
			<td>
				<input type="checkbox" name="mufwc-newsletter" id="mufwc-newsletter" class="mufwc" value=1<?php echo 1 === intval( $newsletter ) ? ' checked="checked"' : ''; ?>>
				<p class="description"><?php esc_html_e( 'Newsletter option in the registration form.', 'wc-mailup' ); ?></p>
			</td>
		</tr>
		<tr class="mufwc-checkout-field">
			<th scope="row"><?php esc_html_e( 'Checkout form', 'wc-mailup' ); ?></th>
			<td>
				<input type="checkbox" name="mufwc-checkout" id="mufwc-checkout" class="mufwc" disabled>
				<p class="description"><?php esc_html_e( 'Newsletter option in the checkout form.', 'wc-mailup' ); ?></p>
                <div class="mufwc-checkout-true-container">
                    <input type="checkbox" name="mufwc-checkout-true" id="mufwc-checkout-true" class="mufwc" disabled>
                    <p class="description"><?php esc_html_e( 'Make the option enabled by default.', 'mailup-for-wc' ); ?></p>
                </div>
				<?php MUFWC_admin::go_premium(); ?>
			</td>
		</tr>
		<tr class="mufwc-newsletter-option">
			<th scope="row"><?php esc_html_e( 'Confirm', 'wc-mailup' ); ?></th>
				<td>
					<input type="checkbox" name="mufwc-confirm" id="mufwc-confirm" class="mufwc" value=1<?php echo 1 === intval( $confirm ) ? ' checked="checked"' : ''; ?>>
					<p class="description"><?php esc_html_e( 'Send MailUp register confirmation.', 'wc-mailup' ); ?></p>
				</td>
		</tr>
		<tr class="mufwc-newsletter-option">
			<th scope="row"><?php esc_html_e( 'MailUp List ID', 'wc-mailup' ); ?></th>
			<td>
				<input type="text" name="mufwc-list" id="mufwc-list" value="<?php echo esc_attr( $list ); ?>">
				<p class="description">
					<?php esc_html_e( 'Enter the ID of the list which you want to register your users.', 'wc-mailup' ); ?></strong>
				</p>
			</td>
		</tr>
		<tr class="mufwc-newsletter-option">	
			<th scope="row"><?php esc_html_e( 'MailUp Group ID', 'wc-mailup' ); ?></th>
			<td>
				<input type="text" name="mufwc-group" id="mufwc-group" value="<?php echo esc_attr( $group ); ?>">
				<p class="description">
					<?php esc_html_e( 'If you want to add users to a specific MailUp group, enter here the correct ID.', 'wc-mailup' ); ?></strong>
				</p>
			</td>
		</tr>

		<?php wp_nonce_field( 'mufwc-settings-registration', 'mufwc-settings-registration-nonce' ); ?>

	</table>

	<p class="submit">
		<input type="hidden" name="mufwc-sent" id="mufwc-sent" value="true">
		<input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save changes', 'wc-mailup' ); ?>">
	</p>

</form>
