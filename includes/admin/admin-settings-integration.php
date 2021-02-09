<?php
use PowerpackElementsLite\Classes\PP_Helper;
use PowerpackElementsLite\Classes\PP_Admin_Settings;

$settings   = PP_Admin_Settings::get_settings();

function refresh_instagram_access_token() {
	$access_token         = trim( \PowerpackElementsLite\Classes\PP_Admin_Settings::get_option( 'instagram_access_token' ) );
	$updated_access_token = 'ppe_updated_instagram_access_token';

	if ( empty( $access_token ) ) {
		return;
	}

	$updated = get_transient( $updated_access_token );

	if ( ! empty( $updated ) ) {
		return;
	}

	$endpoint_url = add_query_arg(
		[
			'access_token' => $access_token,
			'grant_type'   => 'ig_refresh_token',
		],
		'https://graph.instagram.com/refresh_access_token'
	);

	$response = wp_remote_get( $endpoint_url );

	if ( ! $response || 200 !== wp_remote_retrieve_response_code( $response ) || is_wp_error( $response ) ) {
		set_transient( $updated_access_token, 'error', DAY_IN_SECONDS );
		return;
	}

	$body = wp_remote_retrieve_body( $response );

	if ( ! $body ) {
		set_transient( $updated_access_token, 'error', DAY_IN_SECONDS );
		return;
	}

	$body = json_decode( $body, true );

	if ( empty( $body['access_token'] ) || empty( $body['expires_in'] ) ) {
		set_transient( $updated_access_token, 'error', DAY_IN_SECONDS );
		return;
	}

	set_transient( $updated_access_token, 'updated', 30 * DAY_IN_SECONDS );
}
add_action( 'admin_init', 'refresh_instagram_access_token' );
?>
<h3><?php _e( 'Integration', 'powerpack' ); ?></h3>

<table class="form-table">
	<tr valign="top">
		<th scope="row" valign="top">
			<?php esc_html_e( 'Instagram Access Token', 'powerpack' ); ?>
		</th>
		<td>
			<input id="pp_instagram_access_token" name="pp_instagram_access_token" type="text" class="regular-text" value="<?php echo PP_Admin_Settings::get_option( 'pp_instagram_access_token', true ); ?>" />
		<p class="description">
			<?php // translators: %s: Google API document ?>
			<?php echo sprintf( __( 'To get your Instagram Access Token, read <a href="%s" target="_blank">this document</a>', 'powerpack' ), 'https://powerpackelements.com/docs/create-instagram-access-token-for-instagram-feed-widget/' ); ?>
		</p>
		</td>
	</tr>
</table>
