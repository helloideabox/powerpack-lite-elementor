<?php
namespace PowerpackElementsLite\Classes;

/**
 * Handles logic for the admin settings page.
 *
 * @since 1.0.0
 */
final class PP_Admin_Settings {
    /**
	 * Holds any errors that may arise from
	 * saving admin settings.
	 *
	 * @since 1.0.0
	 * @var array $errors
	 */
	static public $errors = array();

	static public $settings = array();

	/**
	 * Initializes the admin settings.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	static public function init()
	{
		add_action( 'plugins_loaded', __CLASS__ . '::init_hooks' );
	}

    /**
	 * Adds the admin menu and enqueues CSS/JS if we are on
	 * the plugin's admin settings page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	static public function init_hooks()
	{
		if ( ! is_admin() ) {
			return;
		}

        add_action( 'admin_menu',           __CLASS__ . '::menu', 601 );
        add_filter( 'all_plugins',          __CLASS__ . '::update_branding' );

		if ( isset( $_REQUEST['page'] ) && 'powerpack-settings' == $_REQUEST['page'] ) {
            //add_action( 'admin_enqueue_scripts', __CLASS__ . '::styles_scripts' );
			self::save();
			self::reset_settings();
		}
	}

    /**
	 * Enqueues the needed CSS/JS for the builder's admin settings page.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	static public function styles_scripts()
	{
		// Styles
		//wp_enqueue_style( 'pp-admin-settings', POWERPACK_ELEMENTS_LITE_URL . 'assets/css/admin-settings.css', array(), POWERPACK_ELEMENTS_LITE_VER );
	}

	/**
	 * Get settings.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	static public function get_settings()
	{
		$default_settings = array(
			'plugin_name'       => '',
            'plugin_desc'       => '',
            'plugin_author'     => '',
            'plugin_uri'        => '',
            'admin_label'       => '',
            'support_link'      => '',
            'hide_support'      => 'off',
            'hide_wl_settings'  => 'off',
			'hide_plugin'       => 'off',
			'google_map_api'	=> '',
		);

		$settings = self::get_option( 'pp_elementor_settings', true );

		if ( ! is_array( $settings ) || empty( $settings ) ) {
			return $default_settings;
		}

		if ( is_array( $settings ) && ! empty( $settings ) ) {
			return array_merge( $default_settings, $settings );
		}
	}

	/**
	 * Get admin label from settings.
	 *
	 * @since 1.0.0
	 * @return string
	 */
	static public function get_admin_label()
	{
		$settings = self::get_settings();

	    $admin_label = $settings['admin_label'];

	    return trim( $admin_label ) == '' ? 'PowerPack' : trim( $admin_label );
	}

	/**
	 * Renders the update message.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	static public function render_update_message()
	{
		if ( ! empty( self::$errors ) ) {
			foreach ( self::$errors as $message ) {
				echo '<div class="error"><p>' . $message . '</p></div>';
			}
		}
		else if ( ! empty( $_POST ) && ! isset( $_POST['email'] ) ) {
			echo '<div class="updated"><p>' . esc_html__( 'Settings updated!', 'power-pack' ) . '</p></div>';
		}
	}

	/**
	 * Adds an error message to be rendered.
	 *
	 * @since 1.0.0
	 * @param string $message The error message to add.
	 * @return void
	 */
	static public function add_error( $message )
	{
		self::$errors[] = $message;
	}

    /**
	 * Renders the admin settings menu.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	static public function menu()
	{
		if ( is_main_site() || ! is_multisite() ) {

			$admin_label = self::get_admin_label();

			if ( current_user_can( 'delete_users' ) ) {

				$title = $admin_label;
				$cap   = 'delete_users';
				$slug  = 'powerpack-settings';
				$func  = __CLASS__ . '::render';

				add_submenu_page( 'elementor', $title, $title, $cap, $slug, $func );
			}
		}
	}

    static public function render()
    {
		include POWERPACK_ELEMENTS_LITE_PATH . 'includes/admin/admin-settings.php';
		//include POWERPACK_ELEMENTS_LITE_PATH . 'includes/modules-manager.php';
    }

	/**
	 * Renders the action for a form.
	 *
	 * @since 1.0.0
	 * @param string $type The type of form being rendered.
	 * @return void
	 */
	static public function get_form_action( $type = '' )
	{
		return admin_url( '/admin.php?page=powerpack-settings' . $type );
	}

	/**
	 * Returns an option from the database for
	 * the admin settings page.
	 *
	 * @since 1.0.0
	 * @param string $key The option key.
	 * @return mixed
	 */
    static public function get_option( $key, $network_override = true )
    {
    	if ( is_network_admin() ) {
    		$value = get_site_option( $key );
    	}
        elseif ( ! $network_override && is_multisite() ) {
            $value = get_site_option( $key );
        }
        elseif ( $network_override && is_multisite() ) {
            $value = get_option( $key );
            $value = ( false === $value || (is_array($value) && in_array('disabled', $value) && get_option('pp_override_ms') != 1) ) ? get_site_option( $key ) : $value;
        }
        else {
    		$value = get_option( $key );
    	}

        return $value;
    }

    /**
	 * Updates an option from the admin settings page.
	 *
	 * @since 1.0.0
	 * @param string $key The option key.
	 * @param mixed $value The value to update.
	 * @return mixed
	 */
    static public function update_option( $key, $value, $network_override = true )
    {
    	if ( is_network_admin() ) {
    		update_site_option( $key, $value );
    	}
        // Delete the option if network overrides are allowed and the override checkbox isn't checked.
		else if ( $network_override && is_multisite() && ! isset( $_POST['pp_override_ms'] ) ) {
			delete_option( $key );
		}
        else {
    		update_option( $key, $value );
    	}
    }

    /**
	 * Delete an option from the admin settings page.
	 *
	 * @since 1.0.0
	 * @param string $key The option key.
	 * @param mixed $value The value to delete.
	 * @return mixed
	 */
    static public function delete_option( $key )
    {
    	if ( is_network_admin() ) {
    		delete_site_option( $key );
    	} else {
    		delete_option( $key );
    	}
	}
	
	/**
	 * Set the branding data to plugin.
	 *
	 * @since 1.0.0
	 * @return array
	 */
	static public function update_branding( $all_plugins )
	{
		$settings = self::get_settings();

    	$all_plugins[POWERPACK_ELEMENTS_LITE_BASE]['Name']           = ! empty( $settings['plugin_name'] )     ? $settings['plugin_name']      : $all_plugins[POWERPACK_ELEMENTS_LITE_BASE]['Name'];
    	$all_plugins[POWERPACK_ELEMENTS_LITE_BASE]['PluginURI']      = ! empty( $settings['plugin_uri'] )      ? $settings['plugin_uri']       : $all_plugins[POWERPACK_ELEMENTS_LITE_BASE]['PluginURI'];
    	$all_plugins[POWERPACK_ELEMENTS_LITE_BASE]['Description']    = ! empty( $settings['plugin_desc'] )     ? $settings['plugin_desc']      : $all_plugins[POWERPACK_ELEMENTS_LITE_BASE]['Description'];
    	$all_plugins[POWERPACK_ELEMENTS_LITE_BASE]['Author']         = ! empty( $settings['plugin_author'] )   ? $settings['plugin_author']    : $all_plugins[POWERPACK_ELEMENTS_LITE_BASE]['Author'];
    	$all_plugins[POWERPACK_ELEMENTS_LITE_BASE]['AuthorURI']      = ! empty( $settings['plugin_uri'] )      ? $settings['plugin_uri']       : $all_plugins[POWERPACK_ELEMENTS_LITE_BASE]['AuthorURI'];
    	$all_plugins[POWERPACK_ELEMENTS_LITE_BASE]['Title']          = ! empty( $settings['plugin_name'] )     ? $settings['plugin_name']      : $all_plugins[POWERPACK_ELEMENTS_LITE_BASE]['Title'];
    	$all_plugins[POWERPACK_ELEMENTS_LITE_BASE]['AuthorName']     = ! empty( $settings['plugin_author'] )   ? $settings['plugin_author']    : $all_plugins[POWERPACK_ELEMENTS_LITE_BASE]['AuthorName'];

    	if ( $settings['hide_plugin'] == 'on' ) {
    		unset( $all_plugins[POWERPACK_ELEMENTS_LITE_BASE] );
    	}

    	return $all_plugins;
	}

    static public function save()
    {
		// Only admins can save settings.
		if ( ! current_user_can('manage_options') ) {
			return;
		}

		self::save_license();
		self::save_google_map_api();
        self::save_white_label();
		self::save_modules();
    }

	/**
	 * Saves the license.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return void
	 */
    static private function save_license()
    {
        if ( isset( $_POST['pp_license_key'] ) ) {

        	$old = get_site_option( 'pp_license_key' );
            $new = $_POST['pp_license_key'];

        	if( $old && $old != $new ) {
        		delete_site_option( 'pp_license_status' ); // new license has been entered, so must reactivate
        	}

            update_site_option( 'pp_license_key', $new );
        }
	}
	
	/**
	 * Saves Google Map API key.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return void
	 */
    static private function save_google_map_api()
    {
        if ( isset( $_POST['pp_google_map_api'] ) ) {

			$settings = self::get_settings();
			$settings['google_map_api'] = $_POST['pp_google_map_api'];

            update_site_option( 'pp_elementor_settings', $settings );
        }
    }

	/**
	 * Saves the white label settings.
	 *
	 * @since 1.0.0
	 * @access private
	 * @return void
	 */
    static private function save_white_label()
    {
        if ( ! isset( $_POST['pp-wl-settings-nonce'] ) || ! wp_verify_nonce( $_POST['pp-wl-settings-nonce'], 'pp-wl-settings' ) ) {
            return;
        }

		$settings = self::get_settings();

		$settings['plugin_name'] 		= isset( $_POST['pp_plugin_name'] ) ? sanitize_text_field( $_POST['pp_plugin_name'] ) : '';
		$settings['plugin_desc'] 		= isset( $_POST['pp_plugin_desc'] ) ? esc_textarea( $_POST['pp_plugin_desc'] ) : '';
		$settings['plugin_author'] 		= isset( $_POST['pp_plugin_author'] ) ? sanitize_text_field( $_POST['pp_plugin_author'] ) : '';
		$settings['plugin_uri'] 		= isset( $_POST['pp_plugin_uri'] ) ? esc_url( $_POST['pp_plugin_uri'] ) : '';
        $settings['admin_label']       	= isset( $_POST['pp_admin_label'] ) ? sanitize_text_field( $_POST['pp_admin_label'] ) : 'PowerPack';
        $settings['support_link']       = isset( $_POST['pp_support_link'] ) ? esc_url_raw( $_POST['pp_support_link'] ) : 'httsp://powerpackelements.com/contact/';
        $settings['hide_support']       = isset( $_POST['pp_hide_support_msg'] ) ? 'on' : 'off';
        $settings['hide_wl_settings']	= isset( $_POST['pp_hide_wl_settings'] ) ? 'on' : 'off';
        $settings['hide_plugin']        = isset( $_POST['pp_hide_plugin'] ) ? 'on' : 'off';

		update_site_option( 'pp_elementor_settings', $settings );
    }

	static public function save_modules()
	{
		if ( ! isset( $_POST['pp-modules-settings-nonce'] ) || ! wp_verify_nonce( $_POST['pp-modules-settings-nonce'], 'pp-modules-settings' ) ) {
            return;
        }

		if ( isset( $_POST['pp_enabled_modules'] ) ) {
			update_site_option( 'pp_elementor_modules', $_POST['pp_enabled_modules'] );
		}
	}

	static public function reset_settings()
	{
		if ( isset( $_GET['reset_modules'] ) ) {
			delete_site_option( 'pp_elementor_modules' );
			self::$errors[] = __('Modules settings updated!', 'power-pack');
		}
	}
}

PP_Admin_Settings::init();
