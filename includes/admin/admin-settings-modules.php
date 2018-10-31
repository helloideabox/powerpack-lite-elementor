<?php

$modules = pp_get_modules();
$enabled_modules = pp_get_enabled_modules();

?>

<table class="form-table">
    <tr valign="top">
        <th scope="row" valign="top">
            <?php esc_html_e('Enable/Disable Widgets', 'power-pack'); ?>
        </th>
        <td>
            <?php
            foreach ( $modules as $module_name => $module_title ) :
                $module_enabled = in_array( $module_name, $enabled_modules ) || isset( $enabled_modules[$module_name] );
            ?>
            <p>
                <label for="<?php echo $module_name; ?>">
                    <input
                        id="<?php echo $module_name; ?>"
                        name="pp_enabled_modules[]"
                        type="checkbox"
                        value="<?php echo $module_name; ?>"
                        <?php echo $module_enabled ? ' checked="checked"' : '' ?>
                    />
                        <?php echo $module_title; ?>
                </label>
            </p>
            <?php endforeach; ?>
        </td>
    </tr>
</table>

<?php wp_nonce_field('pp-modules-settings', 'pp-modules-settings-nonce'); ?>
<?php submit_button(); ?>
