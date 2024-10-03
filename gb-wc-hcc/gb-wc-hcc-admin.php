<?php 
	if( !defined( 'ABSPATH' ) ) die();

	$options = get_option('gb_wc_hcc_options');
?>
<style type="text/css">
.wrap input[type="text"], 
.wrap select, 
.wrap textarea {
	max-width: 400px;
	width: 100%;
}

.wrap textarea {
	min-height: 200px;
}

.gb-hcc-input-key {
	min-width: 288px;
}
.gb-hcc-admin-response {
	padding: 10px;
	background: #76F1A7;
	max-width: 600px;
}

.radio_wrap {
	margin-bottom: 5px;
}
</style>
<div class="wrap">
	<h2>
		<span><?php echo GB_HCC_NAME; ?></span>
	</h2>

	<?php if( !defined( 'GB_BRM_REMOVE' ) ): ?>

	<h3><?php _e( 'Your license', 'gb-wc-hcc' ); ?></h3>

	<?php
		$response = $type = '';

		// check for updates

		if( !empty( $_SERVER['SERVER_NAME'] ) )
		{
			$update = get_option('gb_wc_hcc_update_data');

			// set the key to check or clear it

			if( !empty( $_POST['gb_key'] ) )
			{
				if( $_POST['gb_key'] == 'deactivate' )
				{
					// send remove domain request, if status == ok

					if( is_array( $update ) && !empty( $update['status'] ) && $update['status'] == 'ok' )
					{
						$remove = wp_remote_get( 'https://bogdanfix.com/cp/api/index.php?r=domain_remove&k=' . $options['key'] . '&p=' . GB_HCC_ID . '&d=' . $_SERVER['SERVER_NAME'] );

						if( is_array( $remove ) )
						{
							$remove = $remove['body']; // use the content

							$remove = json_decode( $remove, TRUE );
						}
					}

					// erase the key

					$options['key'] = '';

					update_option( 'gb_wc_hcc_options', $options );
				}
				else
				{
					$options['key'] = trim( $_POST['gb_key'] );

					update_option( 'gb_wc_hcc_options', $options );
				}

				delete_site_transient('gb_wc_hcc_update_data');
			}

			// display license & update data

			if( is_array( $update ) && !empty( $update['status'] ) )
			{
				if(
					$update['status'] == 'ok' && 
					!empty( $update['result'] ) && 
					!empty( $update['result']['update_version'] ) &&
					!empty( $update['result']['update_description'] )
				)
				{
					$current_version = intval( str_replace( '.', '', GB_HCC_VER ) );

					// compare versions

					$update_version = intval( str_replace( '.', '', $update['result']['update_version'] ) );
					$update_description = $update['result']['update_description'];
					$update_url = '';

					if( !empty( $update['result']['update_url'] ) )
					{
						$update_url = $update['result']['update_url'];
					}

					if( $current_version < $update_version )
					{
						$response = '
							<div>
								<b>' . sprintf( __( 'Update to %s is available!', 'gb-wc-hcc' ), $update['result']['update_version'] ) . '</b>
							</div>
							<br />
							<div><b>' . __( 'Description:', 'gb-wc-hcc' ) . '</b><br />' . $update_description . '</div>';
							
						if( !empty( $update_url ) )
						{
							$response .= '<br />
								<div>
									<a href="' . admin_url( 'plugins.php?s=bogdanfix' ) . '" class="button button-primary">' . __( 'Go to Plugins menu to update', 'gb-wc-hcc' ) . '</a>
								</div>';
						}
						else
						{
							$response .= '<br />
								<div>
									<a href="https://bogdanfix.com/handsome-checkout-pages-for-woocommerce/?utm_source=hcc-admin" class="button button-primary" target="_blank">' . __( 'Get the license key now', 'gb-wc-hcc' ) . '</a>
								</div>';
						}
					}
					elseif( $current_version >= $update_version )
					{
						$response = __( 'You have the latest version', 'gb-wc-hcc' ) . ' (' . GB_HCC_VER . ')';
					}

					// license type

					if( !empty( $update['result']['type'] ) && mb_strpos( $update['result']['type'], '99' ) > 0 )
					{
						$type = 'pro';
					}
				}
				elseif( 
					$update['status'] == 'error' && 
					!empty( $update['error'] ) && 
					!empty( $update['error_text'] ) 
				)
				{
					$response = $update['error_text'];
				}
				else
				{
					$response = __( 'Undefined error. Please, contact plugin support.', 'gb-wc-hcc' );
				}
			}
			else
			{
				$response = __( 'Undefined server response. Please, contact plugin support.', 'gb-wc-hcc' );
			}
		}
		else
		{
			$response = __( 'Server variable SERVER_NAME is not defined. Please, contact your hosting support.', 'gb-wc-hcc' );
		}

		if( !empty( $response ) )
		{
			echo '<div class="gb-hcc-admin-response">' . $response . '</div>';
		}

		// check if default offer page exists

		$pages_available = get_posts(array(
			'posts_per_page' => -1,
			'post_type' => 'handsome-checkout',
			'post_status' => 'publish',
			'orderby' => 'ID',
			'order' => 'ASC',
		));

		// prepare data: pages for checkout

		$pages_available_html = '';

		foreach( $pages_available as $page )
		{
			if( !empty( $options['replace_page'] ) && $options['replace_page'] == $page->ID )
			{
				$pages_available_html .= '<option value="' . $page->ID . '" selected="selected">' . $page->post_title . ' (#' . $page->ID . ')</option>' . PHP_EOL;
			}
			else
			{
				$pages_available_html .= '<option value="' . $page->ID . '">' . $page->post_title . ' (#' . $page->ID . ')</option>' . PHP_EOL;
			}
		}

		// prepare data: pages for order review

		$pages_available_or_html = '';

		foreach( $pages_available as $page )
		{
			if( !empty( $options['replace_page_order_review'] ) && $options['replace_page_order_review'] == $page->ID )
			{
				$pages_available_or_html .= '<option value="' . $page->ID . '" selected="selected">' . $page->post_title . ' (#' . $page->ID . ')</option>' . PHP_EOL;
			}
			else
			{
				$pages_available_or_html .= '<option value="' . $page->ID . '">' . $page->post_title . ' (#' . $page->ID . ')</option>' . PHP_EOL;
			}
		}
	?>

	<div>
		<table class="form-table">
			
			<tr>
				<th><?php _e( 'License key', 'gb-wc-hcc' ); ?>:</th>
				<td>
					<form method="POST">
					<?php
						if( empty( $options['key'] ) )
						{
					?>
						<input type="text" name="gb_key" value="" class="gb-hcc-input-key" placeholder="<?php _e( 'Paste your key here...', 'gb-wc-hcc' ); ?>" />&nbsp;
						<input type="submit" name="submit" class="button button-primary" value="<?php _e( 'Activate', 'gb-wc-hcc' ); ?>">
					<?php
						}
						else
						{
					?>
						<input type="hidden" name="gb_key" value="deactivate" />
						<input type="submit" name="submit" class="button" value="<?php _e( 'Remove key', 'gb-wc-hcc' ); ?>">
					<?php
						}
					?>
					</form>
				</td>
			</tr>

		</table>
	</div>

	<?php endif; ?>

	<h3><?php _e( 'Settings', 'gb-wc-hcc' ); ?></h3>

	<div>
		<form method="POST" action="options.php">
			<?php settings_fields('gb_wc_hcc_options'); ?>
			<input type="hidden" name="gb_wc_hcc_options[key]" value="<?php if( !empty( $options['key'] ) ) echo $options['key']; ?>" />
			<table class="form-table">
				
				<tr>
					<th><?php _e( 'Support email', 'gb-wc-hcc' ); ?>:</th>
					<td>
						<div>
							<input type="text" name="gb_wc_hcc_options[support_email]" value="<?php if( !empty( $options['support_email'] ) ) echo $options['support_email']; ?>" />
						</div>
					</td>
				</tr>

				<tr>
					<th><?php _e( 'Support phone', 'gb-wc-hcc' ); ?>:</th>
					<td>
						<div>
							<input type="text" name="gb_wc_hcc_options[support_phone]" value="<?php if( !empty( $options['support_phone'] ) ) echo $options['support_phone']; ?>" />
						</div>
					</td>
				</tr>

				<tr>
					<th><?php _e( 'Replace default checkout page with', 'gb-wc-hcc' ); ?>:</th>
					<td>
						<div>
							<select name="gb_wc_hcc_options[replace_page]">
								<option value=""><?php _e( 'do not replace', 'gb-wc-hcc' ); ?></option>
								<?php
									echo $pages_available_html;
								?>
							</select>
						</div>
					</td>
				</tr>

				<tr>
					<th><?php _e( 'Replace default order review page with', 'gb-wc-hcc' ); ?>:</th>
					<td>
						<div>
							<select name="gb_wc_hcc_options[replace_page_order_review]">
								<option value=""><?php _e( 'do not replace', 'gb-wc-hcc' ); ?></option>
								<?php
									echo $pages_available_or_html;
								?>
							</select>
						</div>
					</td>
				</tr>

				<tr>
					<th><?php _e( 'Custom CSS', 'gb-wc-hcc' ); ?>:</th>
					<td>
						<div>
							<textarea name="gb_wc_hcc_options[custom_css]"><?php if( !empty( $options['custom_css'] ) ) echo $options['custom_css']; ?></textarea>
						</div>
						<p class="description"><?php _e( 'Custom CSS from this field will be applied to <u>all</u> the plugin\'s pages.', 'gb-wc-hcc' ); ?></p>
					</td>
				</tr>

				<tr>
					<th><?php _e( 'Custom JS', 'gb-wc-hcc' ); ?>:</th>
					<td>
						<div>
							<textarea name="gb_wc_hcc_options[custom_js]"><?php if( !empty( $options['custom_js'] ) ) echo $options['custom_js']; ?></textarea>
						</div>
						<p class="description"><?php _e( 'Custom JS from this field will be applied to <u>all</u> the plugin\'s pages. &#60;script&#62; tags are not required.', 'gb-wc-hcc' ); ?></p>
					</td>
				</tr>

				<tr>
					<th><?php _e( 'Alternative URLs', 'gb-wc-hcc' ); ?>:</th>
					<td>
						<div class="radio_wrap">
							<input type="radio" name="gb_wc_hcc_options[alternative_url]" value="0" <?php if( isset( $options['alternative_url'] ) ) checked( $options['alternative_url'], '0' ); ?> /> <?php _e( 'no, leave default "/handsome-checkout/"', 'gb-wc-hcc' ); ?>
						</div>
						<div class="radio_wrap">
							<input type="radio" name="gb_wc_hcc_options[alternative_url]" value="1" <?php if( !empty( $options['alternative_url'] ) ) checked( $options['alternative_url'], '1' ); ?> /> <?php _e( 'yes, switch to  "/checkout-handsome/"', 'gb-wc-hcc' ); ?>
						</div>
						<div class="radio_wrap">
							<input type="radio" name="gb_wc_hcc_options[alternative_url]" value="2" <?php if( !empty( $options['alternative_url'] ) ) checked( $options['alternative_url'], '2' ); ?> /> <?php _e( 'yes, switch to  "/checkout-hs/"', 'gb-wc-hcc' ); ?>
						</div>
						<div class="radio_wrap" style="margin-bottom: 10px;">
							<input type="radio" name="gb_wc_hcc_options[alternative_url]" value="3" <?php if( !empty( $options['alternative_url'] ) ) checked( $options['alternative_url'], '3' ); ?> /> <?php _e( 'yes, switch to  "/checkouts/"', 'gb-wc-hcc' ); ?>
						</div>
						<p class="description"><?php _e( 'Switches links from "/handsome-checkout/" to "/checkout-handsome/" or to shorter versions to bypass the caching restrictions.', 'gb-wc-hcc' ); ?></p>
						<p class="description"><?php _e( '<b>Note:</b> You need to reset the rewrite rules manually. Go to the menu > Settings > Permalinks and hit "Save Changes".', 'gb-wc-hcc' ); ?></p>
					</td>
				</tr>

				<tr>
					<th><?php _e( 'Disable Abandoned Carts', 'gb-wc-hcc' ); ?>:</th>
					<td>
						<div>
							<input type="checkbox" name="gb_wc_hcc_options[disable_ac]" value="1" <?php if( !empty( $options['disable_ac'] ) ) checked( $options['disable_ac'], '1' ); ?> /> <?php _e( 'yes', 'gb-wc-hcc' ); ?>
						</div>
					</td>
				</tr>

				<tr>
					<th><?php _e( 'Enable beta updates', 'gb-wc-hcc' ); ?>:</th>
					<td>
						<div>
							<input type="checkbox" name="gb_wc_hcc_options[beta_subscription]" value="1" <?php if( !empty( $options['beta_subscription'] ) ) checked( $options['beta_subscription'], '1' ); ?> /> <?php _e( 'yes', 'gb-wc-hcc' ); ?>
						</div>
					</td>
				</tr>
				
			</table>

			<div>
				<?php submit_button(); ?>
			</div>
		</form>
	</div>

</div>