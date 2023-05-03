<?php
/**
 * Plugin main options form
 *
 * @author ilGhera
 * @package mailup-for-wc/admin
 * @since 0.9.0
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
			<th scope="row"><?php esc_html_e( 'Endpoint/ Console url', 'mailup-for-wc' ); ?></th>
			<td>
				<input type="text" name="mufwc-host" id="mufwc-host" class="regular-text" value="<?php echo esc_attr( $host ); ?>">
				<p class="description">
					<?php esc_html_e( 'Do you need help? Click ', 'mailup-for-wc' ); ?>			
					<?php echo sprintf( '<a href="%s" target="_blank">' . esc_html__( 'here', 'mailup-for-wc' ) . '</a>', esc_url( 'http://help.mailup.com/display/mailupapi/MailUp+API+Credentials ' ) ); ?>			
				</p>
			</td>
		</tr>

		<?php wp_nonce_field( 'mufwc-settings-general', 'mufwc-settings-general-nonce' ); ?>

	</table>

	<p class="submit">
		<input type="hidden" name="mufwc-sent" id="mufwc-sent" value="true">
		<input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save changes', 'mailup-for-wc' ); ?>">
	</p>

</form>
<form name="mufwc-registration-options" id="mufwc-registration-options" method="post" action="">

	<table class="form-table">

		<tr class="mufwc-newsletter-field">
			<th scope="row"><?php esc_html_e( 'Registration form', 'mailup-for-wc' ); ?></th>
			<td>
				<input type="checkbox" name="mufwc-newsletter" id="mufwc-newsletter" class="mufwc" value="true"<?php echo true == $newsletter ? ' checked="checked"' : ''; ?>>
				<p class="description"><?php esc_html_e( 'Newsletter option in the registration form.', 'mailup-for-wc' ); ?></p>
			</td>
		</tr>
		<tr class="mufwc-newsletter-option">
			<th scope="row"><?php esc_html_e( 'Confirm', 'mailup-for-wc' ); ?></th>
				<td>
					<input type="checkbox" name="mufwc-confirm" id="mufwc-confirm" class="mufwc" value="true"<?php echo true == $confirm ? ' checked="checked"' : ''; ?>>
					<p class="description"><?php esc_html_e( 'Send MailUp register confirmation', 'mailup-for-wc' ); ?></p>
				</td>
		</tr>
		<tr class="mufwc-newsletter-option">
			<th scope="row"><?php esc_html_e( 'MailUp List ID', 'mailup-for-wc' ); ?></th>
			<td>
				<input type="text" name="mufwc-list" id="mufwc-list" value="<?php echo esc_attr( $list ); ?>">
				<p class="description">
					<?php esc_html_e( 'Enter the ID of the list which you want to register your users.', 'mailup-for-wc' ); ?></strong>
				</p>
			</td>
		</tr>
		<tr class="mufwc-newsletter-option">	
			<th scope="row"><?php esc_html_e( 'MailUp Group ID', 'mailup-for-wc' ); ?></th>
			<td>
				<input type="text" name="mufwc-group" id="mufwc-group" value="<?php echo esc_attr( $group ); ?>">
				<p class="description">
					<?php esc_html_e( 'If you want to add users to a specific MailUp group, enter here the correct ID.', 'mailup-for-wc' ); ?></strong>
				</p>
			</td>
		</tr>

		<?php wp_nonce_field( 'mufwc-settings-registration', 'mufwc-settings-registration-nonce' ); ?>

	</table>

	<p class="submit">
		<input type="hidden" name="mufwc-sent" id="mufwc-sent" value="true">
		<input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save changes', 'mailup-for-wc' ); ?>">
	</p>

</form>
