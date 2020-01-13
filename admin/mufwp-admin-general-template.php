<?php
/**
 * Plugin main options form
 *
 * @author ilGhera
 * @package mailup-for-wp/admin
 * @since 0.9.0
 */

$host         = get_option( 'mufwp-host' );
$list         = get_option( 'mufwp-list' );
$group        = get_option( 'mufwp-group' );
$confirm      = get_option( 'mufwp-confirm' );
?>

<form name="mufwp-general-options" id="mufwp-general-options" method="post" action="">
	
	<table class="form-table">

		<tr>
			<th scope="row"><?php esc_html_e( 'Endpoint/ Console url', 'mailup-for-wp' ); ?></th>
			<td>
				<input type="text" name="mufwp-host" id="mufwp-host" value="<?php echo esc_attr( $host ); ?>">
				<p class="description">
					<?php esc_html_e( 'Do you need help? Click ', 'mailup-for-wp' ); ?>			
					<?php echo sprintf( '<a href="%s" target="_blank">here</a>', esc_url( 'http://help.mailup.com/display/mailupapi/MailUp+API+Credentials ' ) ); ?>			
				</p>
			</td>
		</tr>
		<tr>
			<th scope="row"><?php esc_html_e( 'MailUp List ID', 'mailup-for-wp' ); ?></th>
			<td>
				<input type="text" name="mufwp-list" id="mufwp-list" value="<?php echo esc_attr( $list ); ?>">
				<p class="description">
					<?php esc_html_e( 'Enter the ID of the list which you want to register your users.', 'mailup-for-wp' ); ?></strong>
				</p>
			</td>
		</tr>

		<tr>	
			<th scope="row"><?php esc_html_e( 'MailUp Group ID', 'mailup-for-wp' ); ?></th>
			<td>
				<input type="text" name="mufwp-group" id="mufwp-group" value="<?php echo esc_attr( $group ); ?>">
				<p class="description">
					<?php esc_html_e( 'If you want to add users to a specific MailUp group, enter here the correct ID.', 'mailup-for-wp' ); ?></strong>
				</p>
			</td>
		</tr>

		<?php wp_nonce_field( 'mufwp-settings-general', 'mufwp-settings-general-nonce' ); ?>
	
	</table>

	<p class="submit">
		<input type="hidden" name="mufwp-sent" id="mufwp-sent" value="true">
		<input class="button button-primary" type="submit" value="<?php esc_html_e( 'Save changes', 'mailup-for-wp' ); ?>">
	</p>

</form>
