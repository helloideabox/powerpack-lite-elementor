<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use PowerpackElementsLite\Classes\PP_Admin_Settings;

function powerpack_elements_lite_render_integration_settings() {
	$settings     = PP_Admin_Settings::get_settings();
	$access_token = PP_Admin_Settings::get_option( 'pp_instagram_access_token', true );
	?>
	<h3><?php esc_html_e( 'Integration', 'powerpack-lite-for-elementor' ); ?></h3>

	<table class="form-table">
		<tr valign="top">
			<th scope="row" valign="top">
				<?php esc_html_e( 'Instagram Access Token', 'powerpack-lite-for-elementor' ); ?>
			</th>
			<td>
				<input 
					id="pp_instagram_access_token" 
					name="pp_instagram_access_token" 
					type="text" 
					class="regular-text" 
					value="<?php echo esc_attr( $access_token ); ?>" 
				/>
				<p class="description">
					<?php
					$url = 'https://powerpackelements.com/docs/create-instagram-access-token-for-instagram-feed-widget/';

					printf(
						wp_kses_post(
							/* translators: %s: Instagram access token documentation link */
							__( 'To get your Instagram Access Token, read %s.', 'powerpack-lite-for-elementor' )
						),
						'<a href="' . esc_url( $url ) . '" target="_blank" rel="noopener noreferrer">' . esc_html__( 'this document', 'powerpack-lite-for-elementor' ) . '</a>'
					);
					?>
				</p>
			</td>
		</tr>
	</table>

	<?php wp_nonce_field( 'pp-integration-settings', 'pp-integration-settings-nonce' );
}

powerpack_elements_lite_render_integration_settings();
