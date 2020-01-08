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
	    return 'PowerPack';
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
				echo '<div class="error"><p>' . esc_html__( $message ) . '</p></div>';
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
				$icon  = "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHhtbG5zOnhsaW5rPSJodHRwOi8vd3d3LnczLm9yZy8xOTk5L3hsaW5rIiB2aWV3Qm94PSIwIDAgMTUwIDE1MCI+PHRpdGxlPkFzc2V0IDE8L3RpdGxlPjxnIGlkPSJMYXllcl8yIiBkYXRhLW5hbWU9IkxheWVyIDIiPjxnIGlkPSJMYXllcl8xLTIiIGRhdGEtbmFtZT0iTGF5ZXIgMSI+PGltYWdlIHdpZHRoPSIxNTAiIGhlaWdodD0iMTUwIiB4bGluazpocmVmPSJkYXRhOmltYWdlL3BuZztiYXNlNjQsaVZCT1J3MEtHZ29BQUFBTlNVaEVVZ0FBQUpZQUFBQ1dDQVlBQUFBOEFYSGlBQUFBQ1hCSVdYTUFBQXNTQUFBTEVnSFMzWDc4QUFBZ0FFbEVRVlI0WHUyZGVYeFVWYmJ2MTk3bm5KcFRVeXFWeWh4Q0VnaERDQWdvR0ZCUW16UmlPMFhiZ1c2eHUyMmZUNTR0bjZjWHVuM2UxNStydHk5MGV6L2EzZmk4dHRndE51MklDZ2dxRjVsRUJCS1FDZ2tReUR4WGtxcFVrcHJQc1BmN0k4RVdCUTVENVZRbDFQZnp5VC81ckZWSm5mclZIdFplYTIxRUtZV3h5cG8xYmV5dVhmM2M5T21HeHc0Zjl0OHVTVlFOQUxNQkFNbjVqZ0FTUWxCaE5MS3VXMjR4cjl1OWUyRFhiYmRaaFljZVNwWGtIRWNqYUN3S2E4MmFOdlhldlFQcW1UTU45eHc4Nkh0QUVLZ0JBR1lBQUN2bnF3QThRdkMxMmN5MmxaWWEvM2Jva0cvZjRzWFd5RU1QcFFweWpxT0pNU1dzbDEvdVZILzJtVmMvWllydVIwNW40UDV3bUZnQW9CZ0ExSEsrTVNDRU1Sd3ptN25HbVRNTmJ6WTBoTCs2NXg1YmNORWlpeWpuT0JvWUU4SmF2NzVidldOSHZ6RW5SMzJEMCtsL0tCZ2thUUJRQkFBNk9kODR3TTl4NkpqZHJqb3llYkp1dzhDQVZIdmZmVFovU1ltQnlEbkdNNk5hV051M2U3bE5tenhtcTVXZGRmcDA2SDZ2VjV3Z1NYUWlBQ1RKK2NZaEF6b2RQcHFSb2Q1dU1qRWYybXhjeDhxVldRRTVwM2hsVkFyTDZmVGo5OTkzbXdDZ3FLVWxjazl2TDM4ZHo5TWlBRERKK1k0Q3ZFWWo4MFZXbHZyZFVJaDhNWCsreWZQUVE2bGhPYWQ0WTlRSmE4MmFOa05uSjU4N09DamQydHNybEFVQ1VqRUFXT1g4UmhsOURJT2FiVGJXNlhDb1BnaUZTT1ZqajZWNVJ0UDBPR3FFOWNFSGJ2WE9uZjBPbmlkelBSNnh2TDlmbkE4QU5qbS9VWTVicmNhTlNVbk1OcnVkMjJLMXNvM1BQWmM3S09jVUQ4UzlzSnhPUDE2M3pwVWlDSFQ2d0lDNHhPTVJTMFdSWnNEWUY5VzM2VFdiMmE4TUJtYWoxY3J1bnovZjFIbjMzYmFJbkZNc2lXdGhQZnRzYzFKZm4xamtjdkUvQ0FiSkhlRXd5UUlBdTV6ZkdLV0haVkYzY2pLM3gySmh0eVVuczErWGw5dmlkbnFNUzJHdFg5K3RPWGt5bU5QVUZKNHRpdlRPdmo1eExnQ2t5dmxkSlhUcjlVeXpWb3Mvek14VTdYSTRWQ2RXcnN3S3lqa3BUVndKYTNpM2w5eldGaWtWQkhxTDJ5M2NJSW8wR1JLaU9oY3VtNDNialJCc25UUkpWekYxcXI3bDdydHRjUk85anh0aFBmdHNzOWJuazJZMk4wZXVqMFRJZmVFd1NRVUFoNXpmVlk2TFpWRy96Y1o5d2pCb1gxR1JkdCt0dDFyN1Nrb01NZjlRWXk2czlldTcyZmIyeUlSOSt3Ym5HQXo0Qng2UFdBb0FhWEorQ2M2aVM2UEJicFVLYmNqSVVIK3RWdU45TDc2WUY5UEZmY3lFdFgyN0YvWDJDcG1mZk5JM2h4QTZ2N3RidUEwQVZKQVlwYTZFTG91RlBlVHpTWi9kZEpPNUlpbUpxWHI4OGZTWUxPNWpJcXd2dnh6UXYvRkd6eTJEZytKTW4wKzZjL2l3T0RGS1JZY3VBQkJUVTdsUFF5SHkxZTIzSngvNjJjOGN0WEpPMFVaeFlUMzdiUE1OVG1kZ0pxWHdrMEJBc2tOQ1VDTkZGOHVpa00zR2JjWVlIZnpwVCsxZkxGcGtjY2s1UlF2RmhQWHl5NTNUTm0vMmxDWWxNZGQ2dmVKQ1NpRkR6aWRCVk9qU2FMREhZR0ErdEZxNUk0ODk1dmk4cE1RdzR1R0pFUmZXK3ZYZGhSOS8zRGNmQUdaNHZjSmlRa0FEUTBIT1dHUnhYcTBRaEtCYnE4VWRMSXMreU12VFZyNzRZdDVPT2FjclljU0U5ZVdYZytiWFhuTXRDZ1NrT1lPRFlwa2dVQXNNSGNOZ09kOEVJNGFFTWJUcmRNeCtTdUhRRDM5bzJmWDQ0K2sxY2s2WHc0Z0lhOFdLeG9XdHJlRWJBd0d5aE9kSkpxVmdCUUJHemkrQklrZ0EwTTh3eUdzd01EdTFXbHhSWG03YmNmZmR0alk1eDBzaHFzSmF2NzQ3ZjlNbXo2M2hNTG1PNSttMWhOQXNpSTg4OHdUZlIwUUlCbGdXZFJxTjdBNkhRN1h6Rjc5STNWMVNZZ2pKT1Y0TVVSR1cwK25YL09sUG5XVmVyM2lMenljdGxDVHFBQUFESkVRMUdoQXdSb01zQy9VV0MvZmh2SG5HTFk4L25uN0Y0WWtyRnRhYU5XM0ZSNDhHYnZkNGhCOUpFczJuRlBRQXdNbjVKWWc3QklaQjlRWURzeTh0VGJYOXdRZnRuNWVXR2k4NzkrdXloZVYwK2pVdnY5eDFjMit2Y0tmZkwxMHZTVFFQRW9JYTdRZ0lRWkJoVUwzRG9kb3laWXB1NDhxVldTZmtuTTdGWlFsci9mcnU3RjI3K3NzN092Z0hDS0VUS0FVTkpLYTlzY1NaMGV1UTBjaTgrYS8vbXIwM1AxOTdTVWREbHl5c0ZTc2FaM1IzOHcvMzlBZ0xKSWxPZ0xFbnFBRUFxQWNBVUt0eHlHcGx1L1I2UEhBdVE3K2ZtUHY2aERTZXA1cmhYMDJBb2JYbFdFQkVDSGlFNEhodXJ1YjF4WXV0SDk1OXQ2MVh6dWtNRnkycyt2b1FmdTY1MXJuOS9kTC85UHZGR1lSQVBvenVFQUlQQUZVY2g4S0ZoZHJLOUhUMU1RRG85WGlFeHBhV1NML0hJMGdhRGFZT2gwb3ltWmh6Zmx2Nyt5WHNjdkZNSkVMUTdObEpScU9SVFFhQS9MNCtvYWlqZ3kvcTdlVXpDUUVNQU5mQTZJM2ZpUmlqaytQR3FUY1ZGR2pYcjF5WjFTRG5BSENSbzAxOWZRaXZXdFU4eisrWFZ2TThtVUVwTURBNlJYVllyMmU4UlVYYWd4a1o2cStxcWdLVm5aMFJVYWRqaE14TXRXZzBNbVRjT0xVMGJkcWw1ek01blg1M01FaWFHaHZEUndXQnNDeUxXQUNFNzczWGx1M3hDS1ZWVllFZnVOMUNOZ3lWK284bVdFTG9wS2FtTUVNSTZKOTl0dm5WNTU3TFBTM25KRHRpYmQvdTFXL2QydmVEdXJyUXJ5TVJNcHErZVhUNDU2aFdpejBsSllaZFJVVzZUelp2OXRSUG1xU1Rpb3YxVW5tNWJjUWJjbno1NVNBNmV0VFBIRHpvWThlUDF4Z1lCdDEzN0ZoZ1FWK2ZNQTRBcHNIUTBkWm9PTjRpQ01IcGxCUlZaVklTOHdlRW9PYTExd3JPSzU0TEN1dmYvNzJWM2I5LzhJWlFpS3lGb2ZWRHZEOEFBZ0FVSVpBQXdKbVhwemt3WjQ3eFE3K2ZWUFQzQy96MTE1dm96VGViTDNrMGlpWnIxclJocTVYakpJa1dWMWI2bGpZMWhlY0NRREdsd0VMOGYya3BBSnkyMjFWZm0wek03OGFQMXh4ZnVUTHJuTS96dk1KNjhza0d0cjJkdjk3dmwxNktSRWpKT1kzaUN3bGpPRTRwZEUyZHF0OTk3YlZKR3lkTjBqV1hsQmhHZkZTNlhKeE9QMXRkSGNnN2RNaFhmdUpFOEVjQU1JMVM0Q0QrbHhtbkN3dTFtOVBUVmYrbDBlRG1sU3V6dnJjR1BhZXdWcXhvWk5yYUl0ZjQvZExhU0lUTStwNUJmQ0ZpREFJQVZHVm1xdmRiTE96TEw3MDB2a25PS1o2b3J3K2hkZXRjRXpvNitFYzZPeU9sbEg0emdsM1VHamhHbkpvd1Fic2xNMVA5eHh0dk5IZVdsaHJQRWhMejI5Lys5aXpyalJ2ZHVLdUxIeDhPay8vdDlZb0ZBSkFNOFltQUVQQU1nNnB0TnU3ajRtTDk3My8rYzhlR1pjc2NIam5IZU1OcTVlRG1teTN1dkR6Tm52WjJ2allTb1l3b1VwWlNTSWI0SGIxc0hvOW8wK2x3blNSQjArelpTV2UxWC9xZXNENysySlBVMXllV056U0V5Z0dnQU9JVGdXSFFLYlVhN3kwbzBLMzc4WTlUMWozNmFGcUR3Nkc2cENCZXZPRndxS1RGaTYwdDRUQ3BIQmdRZ3dNRFVqSkNZS0lVTU1TbndHeVJDRWtPaGNnQnQxczRxenJvTEdHdFh0M0duRG9WbXRUU0Vsa3BTWFRhdVY0cDFpQUVFWlpGMWVucHFxMFRKK3IrM3dzdjVPM056OWZHdENJbDJwU1VHSHlUSit1T25ENGRQRUVwQXA2blNZU0FCZUpRWEpFSWxkTFNWQUdlcDhkdXZOSDB6ZWR3bHJEZWY5OXRFUVQ2djl4dVlUckVYd2NYSG1NMG9GYmppdHhjelY4V0w3YSt0WHg1ZXJPYzAyakZhdVhva2lYSjdZMk40Y1p3bUpCQWdGampkR3BNSGhpUTlCeUhkckFzOHVibmF3SGdXOXZidFdzN21XQ1E1RFEyaG04QWdQenp2VXFNNEZrVzFWc3N6TnM1T2VyZlB2MTA1b2J5Y2x1UG5OTllZTldxck5vSEhyQy9rcHpNL2hsamRBUWhpTHZST1JJaGRvT0JLYTJwQ1h6VGt2T2JYVWRYRjYvVGFQQWRFSC9OeTNpV1JhZnRkbTdydUhHYWQ1OS9QdGNwNXpEV0tDdXplUEx6Tlc4KzlWU1RKeHdtS3dTQlRDRUVUREJVaHhrUDVEYzFoZTlxYTR0c0FZQXd3TGRHckthbXNLYTVPVHdYQVBMTzV4MERlSVpCMWFtcHFnMVRwK3IvZmpXSzZnejUrVnJ5d2d2anRsbXQzR3E5bnZtWVlWQUxESjEzeGdVZWo1Q2VrYUd5Yjl6b3hnRER3bnJyclI2Y25hM084ZnVsZUpvQ2gwWEZ2YlowcWYzMVZhc3VMeTlvTEpHZnJ5VnZ2VFhodisxMjdzMmtKR1ozbklrck9TbUpLZTNxNGptQVlXRzF0RVJVaE1CTkFKQjdJVThGNFJrR1ZUc2MzR3MvK1VucUIyVmxGcmVjdzlYRXVuV0ZlNU9UMmJlU2twZzlHS1BMenZLTU1ybE5UZUc1eDQ0RldJQmhZZFhXQnRuMjlraThITnZ3TEl0T3BhZXIzbG02TkhWalFsVG5adDI2d3IxMk8vZVdTb1dPeGN1QzN1WGlDL3grU1FVd0xDeVhTMkRjYmlIendtNktJTEFzT3AyV3B2cG85dXlrVFdWbGxsRVhSVmVTcDUvTy9FS2p3Uyt5TEtxSUIzSHhQTTBwTHRZN1B2KzhIMkVBZ0duVDlGcEJvTEcrdlVIQUdEV2F6ZXllU1pOMDd5eGZubDR2NTNDMWs1K3ZKWC80dzdoUDB0TFVhekZHMVFBUTY4WnJXUm9OTHV6ckV6QlRWUFE0bGlSNlRWMWQ2R0dJWWVOOWhDQ3NWdU05S1NuYzY3Ly9mZDVST2ZzRVExaXRIRTFKNFZycjYwTTZ2NTlrREJjSHh5ejl4bWhrancwTWlBZllVSWhnQU1pQzJIWjlFUkJDeDYxVzdoL3IxaFVla0ROT2NEYWxwVWFmMCtuZkdBZ001UGIxQ1J3aE1CNWlsQm5oY3ZGRkxJc3dibXdNSTVlTHo1VnpHRUZFaGtFbmMzUFY3ejM5ZE1ibmNzWUp6czN5NWVtTkpoUHpONVVLVnc4bk9zYUUzbDRodytYaU1SNGNGSEV3U0dMV1BCWWhFS3hXOXF2cDB3M3ZsNVFZWXI0QUhjMnNXMWRZV1ZDZzNZb3hPZ2tRRzNHRnc4UVFDaEhBTGhlUGVudUZXRTJERWtKUW5aZW4rZnZ5NWVtdGNzWUo1SmsvMzdRdE9abXRRQWhpZGozZFhYY2xHN0RISXlLdlY0ekZpRVV3aGhPNXVab2Q5OTJYY2xqT09NSEZVVjV1YzZla2NLOEFRRFVNMVFBb1RiNVd5Mml4S0ZLUUpCcUwwbmlxVXVHR3REVFY2eVVsaG5nNWxoZ1RQUGxrUnRXRUNick5DTUVKR0NxQVVCSXJJWkFUcTIwcFJRaXFwMDgzZlB6ODg3bWpLajk5TkpDZnI2VTVPZXEvQVVCTVVvc0VnZGp4bENuNm1HeExOUnJjWGxpby9Vak9Mc0hsc1dwVlZzZVVLZm85QUhCY3pqYmFFQUlzTzNHaTFsUlRFNWdzWnh4bHFrdEtETnVYTFV2MXlobkdrb2FHTUc1dERadjcrc1JzdjE4eVVqcFVWNmxXNDRqUnlMU01INi9wbVRoUkY1UGQxOFZnc2JDdkFjQWlPYnVSZ0dWWnhBQ0FYczR3eXZnTUJ1WXRPU09sT1g0OHlOVFVCREliR3NKemFtdURrMTB1UHBNUXlNUVl4aU4wVmtvd2tTU29KNFM2MHRMVXJjWEZ1cU81dVpwOU0yWVllZ29LdEVxdmFjNUxNQ2oxVEp1bS83eXFLbkM5bkcwMGFXZ0lsY1JrR3B3OFdmZFZUdy9mTDJlbkZCczI5Q1MxdDBkdVBuTEV2OURyRlNjaEJGTXBCZlh3cG9hVnBIUDIvY29BQU1ubDRvV2VIajVFQ0ZTbHBuSTFjK1lZTjA2YXBEdHkwMDNtbU1mazVzd3gwczVPL3AycXFzQWRNRlRPcndpaVNMV3hFSmF6dU5qd3JzM0d4ZnliL2M0N3ZkcW1wdkFQOXUwYnZFOFFTTFlrMGVuRGhhSVhzMHZtQUlBamhHb0lnU1FBdUxHN203OSs4MmJQTlFjT0RCNXJhQWk5WGxTa3E1azN6eFREZUpLTlB2RkVRNnZWeXJYMjlRbUtDUXNnQnVkSldpMzJIam5pcjMzMTFmeVlDZXZZc1FEYXZicy9lLy8rd2YvcjhZZ1RDS0d6WUtndnhaVThENDRRNEFEb1hKZUx2L2JkZDN1TGk0cDBlemR2OXF4KzRZVzhFVy9ZZno1bXpVcUs1T1FJYTdkdTlXUUJnR0k1ZDFmeUlDOEhPbTJhL25POW5vbFozR3JEaGg1MllFQXMzYnpaOHh3QVhEZGNEQnJOc0F0TEtiQ1VRdW1KRTBGMWVyb3FiZW5TMmljMmJKZ1lsVzdFbDhwUGZtS1g3cmpqUkNVb25GSVR6UWQ2TVJ6Snp0WnNtemZQRkpPZDFGLys0dUphV2lJLzM3alJ2WVpTdUg2RU83eGdTbUYyUndkZkVvbVExOHZMVCtya0hFYUtsQlJXeU01V241U3ppeVlqOVZEUENjWkkrT0FEZC9NTk41Z1VQMnJZczJlQThmbkVPM2Z1OVA0TUFHYURNaTJaRUFETWRMdkZBa0xvWHg5NG9EWW1iU1FYTGJKR2lvcDBId0VvZDM2b3FMQW1UTkFlbmpaTnIraVFmSWFhbXNEMGJkdjZma1VweEtKN3pqWDkvV0tlSk5FL3JsclZwSGd5WlhtNVRhaXVEaHpDR0JSTG9GUlNXSkdNRE5YQmFkUDBpbjFyenJCOGViMTF5eGJQLzZFVXJnVmxScXJ2Z2lpRmE5eHVjVkl3U0g3MTZhZDlpaGVhSW9RaXljbGNoNXhkdEZCU1dEWEJJS2tvTGxaMnhOcSszYXNPaGNoS1NhTHBFQnRSblFFVFFtZWVPQkc4K2VUSjRCdzU0MmlUbjY4UjB0TlZwK1Rzb29WaXdtSVlKRlJVK0FhVXZnajc2RkgvM0phV3lCeENZRG9vK0g3UEEwc0l2ZmFMTHdZZTNMelpvK2hpZnZ4NExXODBzalVBb0Vqb1E3RUhuWnpNZFRrY0trVjNnM3YyOUd1KyttcHc2WENjU3VuUXlqbWhGSmhna0JRMk5ZVVZIYldtVHRVSkF3UGlFUUJRWk5SU1VGaHNjMGFHU3RGcHNLNHVOQ1VVSW5uRDdjUGpCVTZTWUVaRmhlOW1PY05vVWxKaUlNZU9CWHh5ZHRGQ01XR2xwSENuSjA3VUticHdyNmp3MzBJcFRJZUxPNkpSREVJbzE5MHQ1THo5ZHE5RnpqYWFhTFdZcU5WNGJFMkZDRUZZcFVLS3JhKysrR0pBMTlRVW1pUkpWUEVkMkVXQVdSWmxkblh4aWs2SDJkbHF3Vzdub25yaDVmbFFURmlTUkR2dGRwVmlJNWJYSytaZ2pOSWhUdFpXMzBGRkNKMVNYUjJZTG1jWVRVd21WdEpxc1NJNWNJbzk5TUZCeVd1enNZcEYzRnRhSXJrSVFTSEUyVFI0QmttaXFvNk9pS0w5TXRMU1ZLSWswVTQ1dTJpQVF5RVNBWUJ1T2NQUlJrdExPSmRTaUhVL2l2TkNLU0JKb3VxalIvMksvWTltTXl1cFZPZSt5U3phNE9ycWdCOEFXdVFNcjVBZWxrV0tUWU1BQUQwOVF1cFFHa3Zjd21LTTBpSVJFbSt0T2FNQ2Jtd01LeEZiNmhnWUVIMmdZQ21TM3k4WmhsTmk0aFVWUXBBVkRCSkZBNlZLb2RpRGIyZ0loNVdNdWtjaVJFMHBWZXo5WFE0SUFlSjVFbys3MWlzbXJoLzhsWUF4VW15amNBV2c0WXN5eHh4ajhrMEJBT2oxVEFCanBNUTBmeVhRVWZJRnVDVDBlcVovekFyTGFtWDdZdGtZNHlMZ0NZRW1uUTczeVJsR2kvNStrZUg1a2Q4czJHeGM2NWdWVmthR3FoM2p1R2xWZlM1RVNhTGRLU21jSWdGTEFJQ3VMcDcxK2FSME9ic3JSYVBCZ1RFcnJISGpOQTBBVUF2eDB3ZjlMREJHb3NYQ3VvdUtsS3VramtRSUZrV3FsYk9MQm9vSmE4b1VuY0hwOUN2Mjl5d1c5cVFvMGk2SVRTc2ZXUmdHd3ZuNTJqbzV1MmpTMmNtemZYMmlRODR1R2lqMVFlZXpMRkswakgveFlxdmZidWZhTUVhS3B1cGNKRHlsOEhWUmtXNlBuR0UwNmVzVG1jRkJ5U1puRncyVUVsWlNMTGJWczJjYmR6QU1mQTBLMTlUSmdURUlTVW5NNmVKaWZiT2NiYlJ3T3YxNDhtU2RrUkNxU0c2YTRoKzJrdVRsYVE2b1ZMZzVsczFlejRIQU1Panc3TmxKbjgrWVlWQnMxOXJVRkdHdFZuWWlBRXlRczQwR1kxcFl0OStlSEp3M3ovZ2V3NkFLVUxDbTdrSWdCSkpPaCtzek05Vzc1R3lqaWN2RnN6eFBjd0JBa1NPa01TMHNBSUJ4NHpTN3pHYjJGRUp4c1lnWE1VYjdGeTQwdjd4MHFUMGdaeHhOR2hwQ25Oc3RGTXJaUllzeEw2eDc3MDBKMzNTVCtmY0FzQjlpdTBNa0RJTXFwazdWNzVnKzNYQk16amphVkZVRlZBME5ZY1VTQzhlOHNBQUFaczVNYWl3dE5YMkVNUndDQlRNc3ZnVkZDQ3J0ZHJaR3E4Vi9VcnAzaGRQcFIzUG5HaTFLTmpHK1NvUmxJRm90L3ErcFUvV2ZJNlM0dUNnQVVMMmVhY2NZUGZHNzMrVXEzblZtMzc1QkZReTFqRXlNV05IbTE3L09FbGdXL1Z0eHNXRUh4bkFRbEprV0NVSlFvZEhnclE4OFlQL0ZoZzBUWTlMbHora01jRTFONGV0QXdjOWJzVDhVRDd6d1FwNllrc0krTjNldThWMk0wVDRZaW0rTjFHNVJ4QmdkU2tuaGFzeG05dDc3NzArSldXdk1jRmpTdHJWRkN1VHNvc2xWSlN3QWdHZWV5UmJtekRHK2ZOZGR5YytvMWZnamhrRkhJTG9CVkFFaENETU0ybGRZcU5ubGNLaWVlUHZ0Mkl4VUFBQi8vR01uVzFTa1d3SUszM2l2V0pWT1BMRjRzVlVFZ1AyMXRhRVRvUkI1cXFVbEhDQ0V6aVlFVkhENUh3Q1BFRWdzaXc3cmRNenBoUXZONi9QeU5BZVhMTEZHVTdTWFRGdGJXTlhVRkZhMHVTM0FWU3FzTS96NXorTzluMzNtL2Jmanh3T3pEeDcwbFE4TWlFV1V3aldpU0RVdzlHemtSTWJEMExWNEVZemhhNFpCcmJObUplM0l5bEovL01nakRyK003NGp6M251OUtEdGJQZTdJRWY4NE9kdG9jMVVMQ3dDZ3JNd1NLU3V6N051OXU3K3lyaTQwNWRBaDMwM056ZUVKTElzY0NLRUpBS0NpbEg3M09WR0VFRThwUFNtS3RDc2pROTA0ZTdaaFoyR2gxbm5MTFJiRmQzM240K0JCSHhJRWVoOEFUSld6alRaWHZiRE9zR0NCT2J4Z2dmbndMMytaZHJpeTBxZnU2dUl6T2pvaWVkM2RndDNuazR5UzlNL0NESVpCVW1xcXFyT2dRRnVibHNhMVhYZWRNWHloMTQ0VlJpT1R0bmZ2d0FJNXU1R0E1VGdFQUJBUkJDVkRPL0hOckZsSkVRQm9IUDRadGZUMkNyOEFBTE9jM1VpQUhRNFZTVWxScm9WZ0FtVll2Ym90KytUSjRId0FtQ1JuRzIwUWduNXN0YkpnTXJFZU9lTUVvd2VuMDY5cWFnby9EQUN4dU9BVU9BN1ZYblZ4ckt1QmQ5OTF6NjZyQzkxT0tSUkJqUHF1NHFRa2htaTFPQ1lYSmlhSVBoczN1bE1hR2tJL2hhRXBNQllEUjI4NFRDTHMrUEZhNm5ZTGlWdE94d2g3OWd3czZlc1RyeDIrZFNNV05OWFVCUDJzUm9NcHc2Q1lCL01TWERtUFBGSTNwNzA5c3BnUU9nRWdObjFYV1JieExTMWhDYWVtY3BSaFVBc0FkTWs1SlloZjFxN3R6Ty92RngvaWVWSWN3OUVLa3BPNXpwUVVqdUlGQzh5a3ZUM1NDUUR0Y2s0SjRwTXZ2eHcwVmxUNHlyMWVjUjRoa0FjeEdxMEFBT3gycmowalEwVllBSUNxcWtDSTR4Q2ZDSktPUHVyclEvZ3ZmK202dGJPVHYwZVNhQUhFK0RURmJHYmJqRVptNkF3c05aV1RCSUcydTF4eFdZMmU0QUk4OVZUVGttQlFlcHdRT2dWaTMyKzExV1JpS25OeU5CSUdBQ2dxMG9sWldXckZib1pLRUIwZWVhUnVJYytUZTBXUlRxRlVOaE5EQ2JwYld5UDE1ZVUyOG8yd2JEYnVhd0JvdnJCZmduamhrVWZxRnJyZHdnT1JDTDJPVW1WcUJlV3cyN2xtdDFzSUFBd0gwR3cyVHVycTRrK3JWRmlSVnMwSnJvd3pvaG9jbEc0a2hHWkQ3S2RBQUlERzhlTzErODljRzRnQkFFcExqVFFTSWYxcGFhcmFDL3NtaURWeEtpb0FBRzh3S08ydzIxVTh3TGRDL2pObUdDTFoyZXFkQUZCL1h0Y0VNYU8rUG9TWExqMVYxdDNOL3pRT1JRVlpXZXJxVUloMExsdVdTZ0MrdFRYTnpGUkhEaC8yVjZoVXVKZm5TZjc1WHlLQjBqaWRmczJhTmUyM2VyM2lyd1NCVENFRURCQkhvZ0tBdXBRVTdoK0ZoZHB2MmdaOEk2eXlNZ3M5ZE1qWG8xS2h6ZFhWQVJzQUtGb3VsT0RjZlBDQk8yM1RKczlkUFQzQ3c1VFNxWEd5K3pzTG00MnJEWWZKaVVjZlRmdW1jT1NzWUZwT2p0cmYxQlRlcWxiak95SVJraEJXakZteG9uR215OFUvMk5zcjNFUUluUWp4TlVxZDRWUm1wdnB2QlFYYXM1cjBuaVdzWmN0U1NYZTMwR0V5TVI4Y094YXdnRUs5bEJLY3pmYnRYc3VXTFo0Zk5qYUc3K0I1V2t3SUhROHhqcWlmaDFONWVaclBEUVpjVVZ5c1A2dDI4bnYvYkdvcU45RGFHdjdZWnVNV3VOMUNRbGdLczJKRjQ1VGVYdUdYWFYzOGZFcnB4T0VENVppZC9WMEl0Um9QK0h6U09rcWhxN1RVZU5aNTRQZUV0V3haS3Uzb2lOUjNkRVQrQXdEeUFHRGlkMjBTUkovMTY3dnRsWlcrZTArY0NONEdBTm1VUWdIRXFhQ0dxYzNMMC94KzFxeWtVdzgvblBxOVBoam5IRjZmZVNaYitzYy9lcW9PSHZUOXJxWW04R3NZRWxkTVVseXZoTzNidldqUklrdmNucXl2WE5tRXM3UFYxdmIyeU4xVlZZRWw0VERKL1ZZNmNTeXlQeThHQ2dDMUV5ZnFObVZrcUE0Ky9IRHFPZXNvenp0dlAvaWczWWN4Mm9veGFLdXJBMDhNditGNGZiUGZ3K24wSTQ5SEtIN3FxY1lXU2lHNFlJRlpYTExFcWtTSG1Rdnk1cHM5eklFRGc1ek54cVlLQW4xNDI3YStPYUVReVlCL1Z0UEU4eGVZSUFTMUV5Wm9QOHJLVXIvNm05OWtuYmU2NjRJTHd2dnZUL0cydElRL2kwUkllbDFkNkU1Q1lETEU5L0Q4YlV4Ly9XdjNNNUpFY3laUDFuM1oxQlIrOTdiYmp0ZVZsT2dqcGFVbWZ0RWl5MGgxbWZrZTc3M1h5MjNmM3E4T0JDVDFyRm1HV1lPRDBrTzF0Y0hKRUlNSzVTdEF3aGhxYzNNMW55WW5jMy85elcreUxuaTN0T3hPWTlXcXJOYU5HOTEveGhoMW5Eb1YvQitVd2xSSzQzTGIrejBvaGFrQU1QSDQ4ZURzNDhlREN4RUMzdStYZHA4OEdmemtEMzlvUDFsUW9CWHk4elhDbENuNlNMU0V0bi8vSU5QVUZPYXFxZ0txdXJvUXA5ZGoxZFNwK3JtQ1FPN3E3dVlMdG03dFU0SENEVHFpZ0lneE9wbVRvLzQwTFUzMTJ2UFA1OG9XOHNvS0N3Q2d2TnptY1RoVWIyM1kwTlBiM0J4ZUlRaGtCaUZ3cG5IR2FLR0VVZ0NuTXpEVDZRemN6REJJRElYSTZaNGU0WGhGaGUvQTZ0VnRqUm9OSmprNUdzRnNaaVNIUXlWYUxPd0ZXenA2dlNMamN2RnNWNWZBdWx3Umx1Y3BuamZQNU1BWXB2aDgwa3hKb2hPNnVuaHpaeWMvQTBiUk11STdDQmlqbXZIak5lOFVGK3ZmWHI0OC9ZSWoxUmt1V2hpbHBjYUF3OEZ0K2MvL2JIZTUzZUpQdlY1eG5pVFJDUkNmUWJzTGdRSGdHa21pME5JU3ZyYWxKUndHZ0pNQUFJUkFLQmlVV2dGb3Z5alN6cDRlNFlMTjBuaWVtUDErS1QwU0lRNUNJQTBBVlB2MkRRQUFGQUdBNWtLK293QUJJUWhqakk1blo2dGZ1ZXN1MjVheU1zdEZONCs3YUdFQkFPVG5hOGtycnhRY1hMdTJzNmV5MHRmWTBjR1hFMEluRHVjRGpUYUJuVUVEdzcwNWVaNUFXMXRrcm96OTFZREFzdWkwWHM4Y1NFMVZ2ZmZZWTQ0dlNrb01sOVE4N3BLRWRZYmx5OU1iblU3L245ZXU3VHJoZGd0TEFnRnBuaWpTYkFEUXd1Z1ZXSUtoVVNySU1PaTB3NkhhTkhteTdzTlZxN0l1SzVYcXNvUUZBRkJTWW9pc1cxZnd5UnR2ZEovNDlOTytFLzM5NGx4SmdwbVNSSE1oSWE3UkNNK3lxRTZqd1YrWlRPeU9SeDlOKzZ5MDFPaVRjem9mbHkyc015eGJsdHE4YkZucTJpZWZiSEMydGtadTh2dWx4YUpJOHlnRkV5UUVOaG9RTUVaZWprTjFaalA3UVdtcGNkdnk1ZW1uNVp6a3VHSmhuZUdsbDhaLzZYVDZqN3o0WW1lVnp5ZmU2UGRMTjRzaVRhVVV6QkRGdjVNZ2FnZ0l3UURMb2c2VGlmM01idWQyUC9LSVkyOUppU0VxVGVTaStvR1hsQmhDNjljWGJucmpqZTZhVHovMTF2QThtZTN6U1FzbGllYkkrU1pRRkpGaFVITlNFck5McmNZSHlzdHRPOHZMYlZFdFdJNnFzTTZ3YkZscS9iSmxxZldyVjdjZFBYVElkNHJueVQwR0E1TW9Xb3c5RXNiUXJkSGdaclVhYjV3OU8yblBxbFZaSTFMMk55TENPc09xVlZsSG5FNy9pVmRlNmFwTlMxTjFnYkpYalNUNEp3UUF1amtPOVpuTjdQc1dDMXZ4MkdOcGUwcEtEQ1BXaUhkRWhRVXdORDIrK21yQk5qbTdCQ01HUlFoYTdIWnVTeVJDSzM3Mk04Zk9zakpMdDV6VGxUTGl3a29RVTlwTkpyWTVIQ1p2bEphYURpNWZubjVjemlGYUpJUTFOdW5RYXJIWFpHTGZUVTNsOWk5Zm5yNDNQMStyYU1wUVFsaGppdzRBRU5MVFZSOFJBbC9jZWFmdDBMMzMybUxTOXl3aHJMRkRSMG9LOThYQWdQVHB0R21HQ3J1ZHE3LzNYcHVpRjI1K200U3dSaitkU1VsTXEwcUYzczdJVUIwcUxHU2MvL0l2bVpkMFlEd1NKSVExZXVsaVdlU3gyYmhOQmdQZW5aK3ZQYnBva2FXL3BNUVFGeUdkaExCR0h5NEE2RTlONWZaampMYm01bW9PejU5dmNwV1ZSU2NETmxva2hEVzZjSm5OYkErOG5mQUFBQUVpU1VSQlZDWEc4TDdWeWgyYU5Fblh1bng1ZWxUTzlxSk5RbGlqZ3g2TkJqY1pETXhIeWNuc2YyczB1UDZsbDhaZmRrcUxFaVNFRmQrNFdSYTEybXhjcGMzR2ZTaUs5TWlqanpxOEpTVUdSV05TbDBOQ1dQR0wyMnhtOTZTbHFkNFJSWHJnbW1zTW5tWExVbU8rMjd0WUVzS0tQL3IwZXVab1dwcHFtOFhDZm15MXNwMnJWbVVGNVp6aWpZU3c0b2NCbFFyVjJPMnFRMFZGdXJkOFB2SFVqMytjRWh3TjA5NjVTQWdyOXZneFJqVldLM3U2cE1Udzk1YVdjTVdNR1laQVdaa2xabEh6YURDbWhZVVFWQU5BTHNSbmpWOElBS3ExV3V5Ni9ucmpYMnRxZ2pzek1sVGhaNTdKaXF0NDFPVXlsb1UxVUZabWVXZkhEcStKNTZrUkFHWkNmTHhmSGdDK1Zxdnh3Snc1U1JzcUsvMGJUU1pXZVB2dGlhTjZoUG91L3g5YTdjYkhtMXFBZHdBQUFBQkpSVTVFcmtKZ2dnPT0iLz48L2c+PC9nPjwvc3ZnPg==";

				//add_submenu_page( 'elementor', $title, $title, $cap, $slug, $func );
				add_menu_page( $title, $title, $cap, $slug, $func, $icon );
			}
		}
	}

    static public function render()
    {
		include POWERPACK_ELEMENTS_LITE_PATH . 'includes/admin/admin-settings.php';
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

    static public function save()
    {
		// Only admins can save settings.
		if ( ! current_user_can('manage_options') ) {
			return;
		}

		self::save_modules();
    }

	static public function save_modules()
	{
		if ( ! isset( $_POST['pp-modules-settings-nonce'] ) || ! wp_verify_nonce( $_POST['pp-modules-settings-nonce'], 'pp-modules-settings' ) ) {
            return;
        }

		if ( isset( $_POST['pp_enabled_modules'] ) ) {
			$enabled_modules = array_map( 'sanitize_text_field', wp_unslash( $_POST['pp_enabled_modules'] ) );
			update_site_option( 'pp_elementor_modules', $enabled_modules );
		}
	}

	static public function reset_settings()
	{
		if ( isset( $_GET['reset_modules'] ) ) {
			delete_site_option( 'pp_elementor_modules' );
			self::$errors[] = esc_html__('Modules settings updated!', 'power-pack');
		}
	}
}

PP_Admin_Settings::init();
