<?php
/**
 * Product meta box
 *
 * @author ilGhera
 * @package mailup-for-wc/includes
 * @since 0.9.0
 */

$list  = get_post_meta( get_the_ID(), 'mufwc-product-list', true );
$group = get_post_meta( get_the_ID(), 'mufwc-product-group', true );
?>

<div class="wrap">
	<p><?php esc_html_e( 'Users who purchase this product will be added to this MailUp list and group.', 'mailup-for-wc' ); ?></p>
	<table class="form-table">
		<tr class="mufwc-post-field">	
			<th scope="row"><?php esc_html_e( 'MailUp List ID', 'mailup-for-wc' ); ?></th>
			<td>
				<input type="number" name="mufwc-product-list" id="mufwc-product-list" class="regular-text" value="<?php echo esc_attr( $list ); ?>">
				<p class="description">
					<?php esc_html_e( 'Enter the ID of the list which you want to register your users.', 'mailup-for-wc' ); ?>
				</p>
			</td>
		</tr>
		<tr class="mufwc-post-field">	
			<th scope="row"><?php esc_html_e( 'MailUp Group ID', 'mailup-for-wc' ); ?></th>
			<td>
				<input type="number" name="mufwc-product-group" id="mufwc-product-group" class="regular-text" value="<?php echo esc_attr( $group ); ?>">
				<p class="description">
					<?php esc_html_e( 'Add users to a specific MailUp group.', 'mailup-for-wc' ); ?>
				</p>
			</td>
		</tr>

		<?php wp_nonce_field( 'mufwc-product-metas', 'mufwc-product-metas-nonce' ); ?>

	</table>
</div>

