<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

function powerpack_elements_lite_render_modules_settings() {
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended -- Reading filter parameter, not processing form submission.
	$current_filter = isset( $_GET['show'] ) ? sanitize_key( wp_unslash( $_GET['show'] ) ) : ''; 

	if ( 'notused' === $current_filter || 'used' === $current_filter ) {
		$modules = powerpack_elements_lite_get_filter_modules( $current_filter );
	} else {
		$modules = powerpack_elements_lite_get_modules();
	}

	$enabled_modules = powerpack_elements_lite_get_enabled_modules();
	?>

	<div class="pp-settings-section">
		<div class="pp-settings-section-header">
			<h3 class="pp-settings-section-title"><?php esc_html_e( 'Widgets', 'powerpack-lite-for-elementor' ); ?></h3>
		</div>
		<div class="pp-settings-section-content">
			<button type="button" class="button toggle-all-widgets"><?php esc_html_e( 'Toggle All', 'powerpack-lite-for-elementor' ); ?></button>
			<div class="pp-modules-manager-filters">
				<select class="pp-modules-manager-filter">
					<option value=""><?php esc_html_e( 'Filter: All Widgets', 'powerpack-lite-for-elementor' ); ?></option>
					<option value="used"<?php echo 'used' == $current_filter ? ' selected' : ''; ?>><?php esc_html_e( 'Filter: Used Widgets', 'powerpack-lite-for-elementor' ); ?></option>
					<option value="notused"<?php echo 'notused' == $current_filter ? ' selected' : ''; ?>><?php esc_html_e( 'Filter: Not Used Widgets', 'powerpack-lite-for-elementor' ); ?></option>
				</select>
			</div>
			<table class="form-table pp-settings-elements-grid">
				<?php
				foreach ( $modules as $module_name => $module_title ) :
					if ( ! is_array( $enabled_modules ) && 'disabled' !== $enabled_modules ) {
						$module_enabled = true;
					} elseif ( ! is_array( $enabled_modules ) && 'disabled' === $enabled_modules ) {
						$module_enabled = false;
					} else {
						$module_enabled = in_array( $module_name, $enabled_modules ) || isset( $enabled_modules[ $module_name ] );
					}
					?>
				<tr valign="top">
					<th scope="row" valign="top">
						<label for="<?php echo esc_attr( $module_name ); ?>">
							<?php echo esc_html( $module_title ); ?>
						</label>
					</th>
					<td>
						<label class="pp-admin-field-toggle">
							<input
								id="<?php echo esc_attr( $module_name ); ?>"
								name="pp_enabled_modules[]"
								type="checkbox"
								value="<?php echo esc_attr( $module_name ); ?>"
								<?php checked( $module_enabled ); ?>
							/>
							<span class="pp-admin-field-toggle-slider" aria-hidden="true"></span>
						</label>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
	<?php
	wp_nonce_field( 'pp-modules-settings', 'pp-modules-settings-nonce' );
}

powerpack_elements_lite_render_modules_settings();
?>

<script>
(function($) {
	if ( $('input[name="pp_enabled_modules[]"]:checked').length > 0 ) {
		$('.toggle-all-widgets').addClass('checked');
	}
	$('.toggle-all-widgets').on('click', function() {
		if ( $(this).hasClass('checked') ) {
			$('input[name="pp_enabled_modules[]"]').prop('checked', false);
			$(this).removeClass('checked');
		} else {
			$('input[name="pp_enabled_modules[]"]').prop('checked', true);
			$(this).addClass('checked');
		}
	});

	// Filter.
	$('.pp-modules-manager-filter').on('change', function() {
		var currentUrl = location.href;
		currentUrl = currentUrl.replace( /&show=.*/g, '' );
		if ( $(this).val() !== '' ) {
			currentUrl = currentUrl + '&show=' + $(this).val();
		}
		location.href = currentUrl;
	});
})(jQuery);
</script>
