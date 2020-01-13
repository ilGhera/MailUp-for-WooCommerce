<?php
/**
 * Plugin main options form
 *
 * @author ilGhera
 * @package mailup-for-wp/admin
 * @since 0.9.0
 */

$newsletter   = get_option( 'mufwp-newsletter' );
?>

<form name="mufwp-registration-options" id="mufwp-registration-options" method="post" action="">
	
	<table class="form-table">

		<tr>
			<th scope="row"><?php esc_html_e( 'Registration form', 'mailup-for-wp' ); ?></th>
			<td>
				<input type="checkbox" name="mufwp-newsletter" id="mufwp-newsletter" class="mufwp" value="true"<?php echo true == $newsletter ? ' checked="checked"' : ''; ?>>
				<p class="description"><?php esc_html_e( 'Newsletter option in the registration form.', 'mailup-for-wp' ); ?></p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'Confirm', 'mailup-for-wp' ); ?></th>
				<td>
					<input type="checkbox" name="mufwp-confirm" id="mufwp-confirm" class="mufwp" value="true"<?php echo true == $confirm ? ' checked="checked"' : ''; ?>>
					<p class="description"><?php esc_html_e( 'Send MailUp register confirmation', 'mailup-for-wp' ); ?></p>
				</td>
		</tr>

		<?php wp_nonce_field( 'mufwp-settings-registration', 'mufwp-settings-registration-nonce' ); ?>
	
	</table>

	<p class="submit">
		<input type="hidden" name="mufwp-sent" id="mufwp-sent" value="true">
		<input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save changes', 'mailup-for-wp' ); ?>">
	</p>

</form>


