<?php
/**
 * Product meta box
 *
 * @author ilGhera
 * @package mailup-for-wc/includes
 * @since 0.9.2
 */

?>

<div class="wrap">
	<p><?php esc_html_e( 'Users who purchase this product will be added to this MailUp list and group.', 'wc-mailup' ); ?></p>
	<table class="form-table">
		<tr class="mufwc-product-field">	
			<th scope="row"><?php esc_html_e( 'MailUp List ID', 'wc-mailup' ); ?></th>
			<td>
				<input type="number" name="mufwc-product-list" id="mufwc-product-list" class="regular-text" disabled>
				<p class="description">
					<?php esc_html_e( 'Enter the ID of the list which you want to register your users.', 'wc-mailup' ); ?>
				</p>
				<?php MUFWC_admin::go_premium(); ?>
			</td>
		</tr>
		<tr class="mufwc-product-field">	
			<th scope="row"><?php esc_html_e( 'MailUp Group ID', 'wc-mailup' ); ?></th>
			<td>
				<input type="number" name="mufwc-product-group" id="mufwc-product-group" class="regular-text" disabled>
				<p class="description">
					<?php esc_html_e( 'Add users to a specific MailUp group.', 'wc-mailup' ); ?>
				</p>
				<?php MUFWC_admin::go_premium(); ?>
			</td>
		</tr>

		<?php wp_nonce_field( 'mufwc-product-metas', 'mufwc-product-metas-nonce' ); ?>

	</table>
</div>

