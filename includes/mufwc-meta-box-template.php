<?php
/**
 * Meta box
 *
 * @author ilGhera
 * @package mailup-for-wp/includes
 * @since 0.9.0
 */

$activate      = get_post_meta( get_the_ID(), 'mufwp-post-activate', true );
$list          = get_post_meta( get_the_ID(), 'mufwp-post-list', true );
$group         = get_post_meta( get_the_ID(), 'mufwp-post-group', true );
$before_text   = get_post_meta( get_the_ID(), 'mufwp-before-text', true );
$button_text   = get_post_meta( get_the_ID(), 'mufwp-button-text', true );
$response_text = get_post_meta( get_the_ID(), 'mufwp-response-text', true );
$redirect      = get_post_meta( get_the_ID(), 'mufwp-redirect', true );
?>

<div class="wrap">
	<table class="form-table">
		<tr class="mufwp-post-activate">
			<th scope="row"><?php esc_html_e( 'Activate', 'mailup-for-wp' ); ?></th>
			<td>
				<input type="checkbox" name="mufwp-post-activate" id="mufwp-post-activate" class="mufwp" value="1"<?php echo 1 == $activate ? ' checked="checked"' : ''; ?>>
				<p class="description"><?php esc_html_e( 'Add a MailUp subscription button.', 'mailup-for-wp' ); ?></p>
			</td>
		</tr>
		<tr class="mufwp-post-field">	
			<th scope="row"><?php esc_html_e( 'MailUp List ID', 'mailup-for-wp' ); ?></th>
			<td>
				<input type="number" name="mufwp-post-list" id="mufwp-post-list" class="regular-text" value="<?php echo esc_attr( $list ); ?>">
				<p class="description">
					<?php esc_html_e( 'Enter the ID of the list which you want to register your users.', 'mailup-for-wp' ); ?>
				</p>
			</td>
		</tr>
		<tr class="mufwp-post-field">	
			<th scope="row"><?php esc_html_e( 'MailUp Group ID', 'mailup-for-wp' ); ?></th>
			<td>
				<input type="number" name="mufwp-post-group" id="mufwp-post-group" class="regular-text" value="<?php echo esc_attr( $group ); ?>">
				<p class="description">
					<?php esc_html_e( 'Add users to a specific MailUp group.', 'mailup-for-wp' ); ?>
				</p>
			</td>
		</tr>
		<tr class="mufwp-post-field">
			<th scope="row"><?php esc_html_e( 'Description text', 'mailup-for-wp' ); ?></th>
			<td>
				<textarea name="mufwp-before-text" class="regular-text" placeholder="<?php esc_html_e( 'Click on the button below to receive the first chapter for free!', 'mailup-for-wp' ); ?>"><?php echo esc_attr( $before_text ); ?></textarea>
				<p class="desctiption"><?php esc_html_e( 'Add a description text before the button.', 'mailup-for-wp' ); ?></p>
			</td>
		</tr>
		<tr class="mufwp-post-field required">
			<th scope="row"><?php esc_html_e( 'Button text *', 'mailup-for-wp' ); ?></th>
			<td>
				<textarea name="mufwp-button-text" class="regular-text" placeholder="<?php esc_html_e( 'Subscribe', 'mailup-for-wp' ); ?>" required><?php echo esc_attr( $button_text ); ?></textarea>
				<p class="desctiption"><?php esc_html_e( 'Add the button text.', 'mailup-for-wp' ); ?></p>
			</td>
		</tr>
		<tr class="mufwp-post-field required">
			<th scope="row"><?php esc_html_e( 'Response text *', 'mailup-for-wp' ); ?></th>
			<td>
				<textarea name="mufwp-response-text" class="regular-text" placeholder="<?php esc_html_e( 'Thanks for subscribing', 'mailup-for-wp' ); ?>" required><?php echo esc_attr( $response_text ); ?></textarea>
				<p class="desctiption"><?php esc_html_e( 'Add the button response text.', 'mailup-for-wp' ); ?></p>
			</td>
		</tr>
		<tr class="mufwp-post-field">
			<th scope="row"><?php esc_html_e( 'Redirect', 'mailup-for-wp' ); ?></th>
			<td>
				<select name="mufwp-redirect" class="mufwp-select">
					<option><?php esc_html_e( 'Select a page', 'mailup-for-wp' ); ?></option>
					<?php
					$red_pages = get_pages();

					if ( $red_pages ) {

						foreach ( $red_pages as $red_page ) {

							echo '<option value="' . esc_attr( $red_page->ID ) . '"' . ( $red_page->ID == $redirect ? ' selected="selected"' : '' ) . '>' . esc_html( $red_page->post_title ) . '</option>';

						}
					}
					?>
				</select>
				<p class="desctiption"><?php esc_html_e( 'Redirect the user to a specific page.', 'mailup-for-wp' ); ?></p>
			</td>
		</tr>
		<?php wp_nonce_field( 'mufwp-post-metas', 'mufwp-post-metas-nonce' ); ?>
	</table>
</div>
