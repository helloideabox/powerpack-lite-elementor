<p><?php echo sprintf(__('Enter your <a href="%s" target="_blank">license key</a> to enable remote updates and support.', 'power-pack'), 'https://powerpackelements.com/my-account/'); ?>
<table class="form-table">
    <tbody>
        <tr valign="top">
            <th scope="row" valign="top">
                <?php esc_html_e('License Key', 'power-pack'); ?>
            </th>
            <td>
                <input id="pp_license_key" name="pp_license_key" type="password" class="regular-text" value="<?php esc_attr_e( $license ); ?>" />
            </td>
        </tr>
        <?php if( false !== $license && ! empty($license) ) { ?>
            <tr valign="top">
                <th scope="row" valign="top">
                    <?php esc_html_e( 'License Status', 'power-pack' ); ?>
                </th>
                <td>
                    <?php if ( $status == 'valid' ) { ?>
                        <span style="color: #267329; background: #caf1cb; padding: 5px 10px; text-shadow: none; border-radius: 3px; display: inline-block; text-transform: uppercase;"><?php esc_html_e('active'); ?></span>
                        <?php wp_nonce_field( 'pp_license_deactivate_nonce', 'pp_license_deactivate_nonce' ); ?>
                        <input type="submit" class="button-secondary" name="pp_license_deactivate" value="<?php esc_html_e('Deactivate License', 'power-pack'); ?>" />
                    <?php } else { ?>
                        <?php if ( $status == '' ) { $status = 'inactive'; } ?>
                        <span style="<?php echo $status == 'inactive' ? 'color: #fff; background: #b1b1b1;' : 'color: red; background: #ffcdcd;'; ?> padding: 5px 10px; text-shadow: none; border-radius: 3px; display: inline-block; text-transform: uppercase;"><?php echo $status; ?></span>
                        <?php
                        wp_nonce_field( 'pp_license_activate_nonce', 'pp_license_activate_nonce' ); ?>
                        <input type="submit" class="button-secondary" name="pp_license_activate" value="<?php esc_html_e( 'Activate License', 'power-pack' ); ?>"/>
                        <p class="description"><?php esc_html_e( 'Please click the Activate License button to activate your license.', 'power-pack' ); ?>
                    <?php } ?>
                </td>
            </tr>
        <?php } ?>
        <tr valign="top">
            <th scope="row" valign="top">
                <?php esc_html_e('Google Map API Key', 'power-pack'); ?>
            </th>
            <td>
                <input id="pp_google_map_api" name="pp_google_map_api" type="text" class="regular-text" value="<?php echo $settings['google_map_api']; ?>" />
            </td>
        </tr>
    </tbody>
</table>
<?php submit_button(); ?>