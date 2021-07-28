<?php
/**
 * Meta box
 *
 * @author ilGhera
 * @package mailup-for-wc/includes
 * @since 0.9.0
 */

$activate      = get_post_meta( get_the_ID(), 'mufwc-post-activate', true );
$list          = get_post_meta( get_the_ID(), 'mufwc-post-list', true );
$button_text   = get_post_meta( get_the_ID(), 'mufwc-button-text', true );
$response_text = get_post_meta( get_the_ID(), 'mufwc-response-text', true );
?>

<div class="wrap">
	<table class="form-table">
		<tr class="mufwc-post-activate">
			<th scope="row"><?php esc_html_e( 'Activate', 'mailup-for-wc' ); ?></th>
			<td>
				<input type="checkbox" name="mufwc-post-activate" id="mufwc-post-activate" class="mufwc" value="1"<?php echo 1 === intval( $activate ) ? ' checked="checked"' : ''; ?>>
				<p class="description"><?php esc_html_e( 'Add a MailUp subscription button.', 'mailup-for-wc' ); ?></p>
			</td>
		</tr>
		<tr class="mufwc-post-field">	
			<th scope="row"><?php esc_html_e( 'MailUp List ID', 'mailup-for-wc' ); ?></th>
			<td>
				<input type="number" name="mufwc-post-list" id="mufwc-post-list" class="regular-text" value="<?php echo esc_attr( $list ); ?>">
				<p class="description">
					<?php esc_html_e( 'Enter the ID of the list which you want to register your users.', 'mailup-for-wc' ); ?>
				</p>
			</td>
		</tr>
		<tr class="mufwc-post-field">	
			<th scope="row"><?php esc_html_e( 'MailUp Group ID', 'mailup-for-wc' ); ?></th>
			<td>
				<input type="number" name="mufwc-post-group" id="mufwc-post-group" class="regular-text" disabled>
				<p class="description">
					<?php esc_html_e( 'Add users to a specific MailUp group.', 'mailup-for-wc' ); ?>
					<?php MUFWC_admin::go_premium(); ?>
				</p>
			</td>
		</tr>
		<tr class="mufwc-post-field">
			<th scope="row"><?php esc_html_e( 'Description text', 'mailup-for-wc' ); ?></th>
			<td>
				<textarea name="mufwc-before-text" class="regular-text" placeholder="<?php esc_html_e( 'Click on the button below to receive the first chapter for free!', 'mailup-for-wc' ); ?>" disabled></textarea>
				<p class="desctiption"><?php esc_html_e( 'Add a description text before the button.', 'mailup-for-wc' ); ?></p>
				<?php MUFWC_admin::go_premium(); ?>
			</td>
		</tr>
		<tr class="mufwc-post-field required">
			<th scope="row"><?php esc_html_e( 'Button text *', 'mailup-for-wc' ); ?></th>
			<td>
				<textarea name="mufwc-button-text" class="regular-text" placeholder="<?php esc_html_e( 'Subscribe', 'mailup-for-wc' ); ?>" required><?php echo esc_attr( $button_text ); ?></textarea>
				<p class="desctiption"><?php esc_html_e( 'Add the button text.', 'mailup-for-wc' ); ?></p>
			</td>
		</tr>
		<tr class="mufwc-post-field required">
			<th scope="row"><?php esc_html_e( 'Response text *', 'mailup-for-wc' ); ?></th>
			<td>
				<textarea name="mufwc-response-text" class="regular-text" placeholder="<?php esc_html_e( 'Thanks for subscribing', 'mailup-for-wc' ); ?>" required><?php echo esc_attr( $response_text ); ?></textarea>
				<p class="desctiption"><?php esc_html_e( 'Add the button response text.', 'mailup-for-wc' ); ?></p>
			</td>
		</tr>
		<tr class="mufwc-post-field">
			<th scope="row"><?php esc_html_e( 'Redirect', 'mailup-for-wc' ); ?></th>
			<td>
				<select name="mufwc-redirect" class="mufwc-select" disabled>
					<option><?php esc_html_e( 'Select a page', 'mailup-for-wc' ); ?></option>
					<?php
					$red_pages = get_pages();

					if ( $red_pages ) {

						foreach ( $red_pages as $red_page ) {

							echo '<option value="' . esc_attr( $red_page->ID ) . '">' . esc_html( $red_page->post_title ) . '</option>';

						}
					}
					?>
				</select>
				<p class="desctiption"><?php esc_html_e( 'Redirect the user to a specific page.', 'mailup-for-wc' ); ?></p>
				<?php MUFWC_admin::go_premium(); ?>
			</td>
		</tr>

		<?php wp_nonce_field( 'mufwc-post-metas', 'mufwc-post-metas-nonce' ); ?>

	</table>
</div>
