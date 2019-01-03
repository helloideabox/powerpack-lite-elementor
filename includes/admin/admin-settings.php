<?php

$current_tab  = isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : 'modules';
$settings     = self::get_settings();

?>

<div class="wrap">

    <h2>
        <?php
            echo sprintf( esc_html__( '%s Settings', 'power-pack' ), 'PowerPack' );
        ?>
    </h2>

    <?php \PowerpackElementsLite\Classes\PP_Admin_Settings::render_update_message(); ?>

    <form method="post" id="pp-settings-form" action="<?php echo self::get_form_action( '&tab=' . $current_tab ); ?>">

        <div class="icon32 icon32-powerpack-settings" id="icon-pp"><br /></div>

        <h2 class="nav-tab-wrapper pp-nav-tab-wrapper">
            <a href="<?php echo self::get_form_action( '&tab=modules' ); ?>" class="nav-tab<?php echo ( $current_tab == 'modules' ? ' nav-tab-active' : '' ); ?>"><?php esc_html_e( 'Elements', 'power-pack' ); ?></a>
        </h2>

        <?php
        // Modules settings.
        if ( 'modules' == $current_tab ) {
            include POWERPACK_ELEMENTS_LITE_PATH . 'includes/admin/admin-settings-modules.php';
        }
        ?>

    </form>

    <?php if ( 'on' != $settings['hide_support'] ) { ?>
    <hr />

    <h2><?php esc_html_e('Support', 'powerpack'); ?></h2>
    <p>
        <?php
            $support_link = $settings['support_link'];
            $support_link = !empty( $support_link ) ? $support_link : 'https://powerpackelements.com/contact/';
            esc_html_e('For submitting any support queries, feedback, bug reports or feature requests, please visit', 'power-pack'); ?> <a href="<?php echo $support_link; ?>" target="_blank"><?php esc_html_e('this link', 'power-pack'); ?></a>
    </p>
    <?php } ?>

</div>
