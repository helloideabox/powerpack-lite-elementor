<?php

$current_tab  = isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : 'modules';
$settings     = self::get_settings();

?>

<div class="wrap">
    <h2 class="h2-for-admin-notices"></h2>
    <div class="pp-header-wrap">
            <div class="pp-header-banner banner-image"><img src="<?php echo POWERPACK_ELEMENTS_LITE_URL . 'assets/images/pp-elements-brandmark.svg'; ?>" /></div>
            <div class="nav-tab-wrapper pp-nav-tab-wrapper">
                <a href="<?php echo self::get_form_action( '&tab=modules' ); ?>" class="nav-tab<?php echo ( $current_tab == 'modules' ? ' nav-tab-active' : '' ); ?>"><?php esc_html_e( 'Elements', 'power-pack' ); ?></a>
                <a href="https://powerpackelements.com/" class="pp-button nav-tab<?php echo ( $current_tab == 'get-pro' ? ' nav-tab-active' : '' ); ?>"><?php esc_html_e( 'Get Pro', 'power-pack' ); ?></a>
            </div>
    </div> 

        <?php \PowerpackElementsLite\Classes\PP_Admin_Settings::render_update_message(); ?>

        <form method="post" id="pp-settings-form" action="<?php echo self::get_form_action( '&tab=' . $current_tab ); ?>">
        

            <div class="icon32 icon32-powerpack-settings" id="icon-pp"><br /></div>

            <?php
            // Modules settings.
                if ( 'modules' == $current_tab ) {
                    include POWERPACK_ELEMENTS_LITE_PATH . 'includes/admin/admin-settings-modules.php';
                }
            ?>

        </form>
</div>
