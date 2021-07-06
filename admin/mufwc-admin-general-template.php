<?php
/**
 * Plugin main options form
 *
 * @author ilGhera
 * @package mailup-for-wc/admin
 * @since 0.9.0
 */

$host    = get_option( 'mufwc-host' );
$list    = get_option( 'mufwc-list' );
$group   = get_option( 'mufwc-group' );
$confirm = get_option( 'mufwc-confirm' );
?>

<form name="mufwc-general-options" id="mufwc-general-options" method="post" action="">
	
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
