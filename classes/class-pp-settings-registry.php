<?php
namespace PowerpackElementsLite\Classes;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Declarative registry of every persisted admin setting.
 *
 * This is the single source of truth for how each setting is stored, who may
 * read or write it, and — critically — what happens when a field is *absent*
 * from an incoming payload. The legacy form handlers encoded that last part
 * ad hoc, inconsistently, which is how saving the WooCommerce Builder tab came
 * to blank template options it rendered no fields for.
 *
 * Value shapes here are load-bearing: they are read at render time by widgets
 * across the plugin, so they must not drift. See the notes on each field.
 *
 * @since x.x.x
 */
final class PP_Settings_Registry {

	/**
	 * Option holding the serialized white label / maps settings array.
	 */
	const SETTINGS_OPTION = 'pp_elementor_settings';

	/**
	 * Sentinel written when a list field has nothing selected.
	 *
	 * An empty array is NOT equivalent: powerpack_elements_lite_get_enabled_extensions() and friends
	 * treat a missing or non-'disabled' value as "everything enabled".
	 */
	const NONE_SELECTED = 'disabled';

	/**
	 * Cached field map.
	 *
	 * @var array|null
	 */
	private static $fields = null;

	/**
	 * Empty-value strategies.
	 *
	 * THE INVARIANT: a field absent from the payload is never touched, whatever
	 * its strategy. Only values actually submitted are acted on. This is what
	 * makes partial updates safe, and it is what the legacy handlers got wrong —
	 * they inferred "unchecked" from "missing from $_POST", so any option whose
	 * form field had been dropped got blanked on every save.
	 *
	 * These strategies therefore describe how a *submitted but empty* value is
	 * stored:
	 *
	 * store    Store it as-is; an empty string stays an empty string.
	 * delete   Truthy stores 1, falsy deletes the option outright. Readers test
	 *          the truthiness of get_option(), so "off" must be an absent
	 *          option rather than a stored 0.
	 * sentinel An empty list stores the string 'disabled', never [].
	 */
	const ON_EMPTY_STORE    = 'store';
	const ON_EMPTY_DELETE   = 'delete';
	const ON_EMPTY_SENTINEL = 'sentinel';

	/**
	 * Get the full field map.
	 *
	 * @since x.x.x
	 * @return array Map of field key => descriptor.
	 */
	public static function get_fields() {
		if ( null !== self::$fields ) {
			return self::$fields;
		}

		self::$fields = apply_filters( 'pp_elements_settings_fields', self::define_fields() );

		return self::$fields;
	}

	/**
	 * Field definitions.
	 *
	 * Descriptor keys:
	 *   group     Logical tab the field belongs to.
	 *   store     'option' for a standalone option, 'settings' for a sub-key of
	 *             pp_elementor_settings.
	 *   type      string|url|textarea|on_off|boolean|enum|list
	 *   strategy  See the STRATEGY_* constants.
	 *   cap       Capability required to read and write the field.
	 *   network_override  Passed through to the multisite read/write helpers.
	 *   secret    Value is a credential: masked on read, never blanked by an
	 *             empty string on write.
	 *   choices   Allowed values for 'enum', or a callable returning the allowed
	 *             members for 'list'.
	 *   default   Value returned when nothing is stored.
	 *
	 * @since x.x.x
	 * @return array
	 */
	private static function define_fields() {
		$fields = [];

		/*
		 * The widget library. Stored as a plain list of widget names, or the
		 * string 'disabled' when nothing is selected — never an empty array,
		 * because consumers read a non-'disabled' non-array as "all enabled".
		 */
		$fields['pp_elementor_modules'] = [
			'group'    => 'modules',
			'store'    => 'option',
			'type'     => 'list',
			'strategy' => self::ON_EMPTY_SENTINEL,
			'cap'      => 'edit_posts',
			'choices'  => 'powerpack_elements_lite_get_modules',
			'default'  => self::NONE_SELECTED,
		];

		$fields['pp_elementor_extensions'] = [
			'group'    => 'extensions',
			'store'    => 'option',
			'type'     => 'list',
			'strategy' => self::ON_EMPTY_SENTINEL,
			'cap'      => 'edit_posts',
			'choices'  => 'powerpack_elements_lite_get_extensions',

			// Per-choice documentation links, suppressed by white label like
			// every other docs link on the screen.
			'docs'     => [ __NAMESPACE__ . '\PP_Config', 'get_extension_docs' ],

			/*
			 * Shown on the screen but absent from 'choices', so the sanitiser
			 * drops them on save whatever the browser sends. The listing is a
			 * preview; there is nothing to store.
			 */
			'pro'      => [ __NAMESPACE__ . '\PP_Config', 'get_pro_extensions' ],
			'default'  => self::NONE_SELECTED,
		];

		/*
		 * Integration. A standalone option rather than a sub-key, and a secret:
		 * it reaches the browser masked and is only written when the user types
		 * a new one or clears it outright.
		 */
		$fields['pp_instagram_access_token'] = [
			'group'            => 'integration',
			'store'            => 'option',
			'type'             => 'string',
			'strategy'         => self::ON_EMPTY_STORE,
			'cap'              => 'manage_options',
			'sanitize'         => 'sanitize_text_field',
			'secret'           => true,
			'network_override' => false,
			'default'          => '',
		];

		/*
		 * Advanced.
		 *
		 * Read by uninstall.php, which runs with the plugin unloaded and so
		 * keeps its own copy of the key list. Keep the two in step.
		 */
		$fields['pp_delete_data_on_uninstall'] = [
			'group'    => 'advanced',
			'store'    => 'option',
			'type'     => 'boolean',
			'strategy' => self::ON_EMPTY_DELETE,
			'cap'      => 'manage_options',
			'default'  => false,
		];

		return $fields;
	}

	/**
	 * Get a single field descriptor.
	 *
	 * @since x.x.x
	 * @param string $key Field key.
	 * @return array|null
	 */
	public static function get_field( $key ) {
		$fields = self::get_fields();

		return isset( $fields[ $key ] ) ? $fields[ $key ] : null;
	}

	/**
	 * Get all field keys belonging to a group.
	 *
	 * @since x.x.x
	 * @param string $group Group name.
	 * @return array
	 */
	public static function get_group_fields( $group ) {
		$fields = [];

		foreach ( self::get_fields() as $key => $field ) {
			if ( $group === $field['group'] ) {
				$fields[ $key ] = $field;
			}
		}

		return $fields;
	}

	/**
	 * Get the list of registered groups.
	 *
	 * @since x.x.x
	 * @return array
	 */
	public static function get_groups() {
		$groups = [];

		foreach ( self::get_fields() as $field ) {
			$groups[ $field['group'] ] = true;
		}

		return array_keys( $groups );
	}

	/**
	 * Whether the current user may read and write a field.
	 *
	 * @since x.x.x
	 * @param string $key     Field key.
	 * @param bool   $network Whether the request is in a network admin context.
	 * @return bool
	 */
	public static function current_user_can( $key, $network = false ) {
		$field = self::get_field( $key );

		if ( ! $field ) {
			return false;
		}

		$cap = $network ? 'manage_network_plugins' : $field['cap'];

		return current_user_can( $cap );
	}

	/**
	 * Read a field value.
	 *
	 * @since x.x.x
	 * @param string $key     Field key.
	 * @param bool   $network Whether the request is in a network admin context.
	 * @return mixed
	 */
	public static function read( $key, $network = false ) {
		$field = self::get_field( $key );

		if ( ! $field ) {
			return null;
		}

		if ( 'settings' === $field['store'] ) {
			$settings = self::get_settings_array();
			$value    = isset( $settings[ $key ] ) ? $settings[ $key ] : $field['default'];
		} else {
			$network_override = isset( $field['network_override'] ) ? $field['network_override'] : true;
			$value            = self::read_option( $key, $network_override, $network );

			if ( false === $value ) {
				$value = $field['default'];
			}
		}

		if ( 'boolean' === $field['type'] ) {
			return (bool) $value;
		}

		return $value;
	}

	/**
	 * Read a field for output, masking credentials.
	 *
	 * Stored secrets never reach the browser in plaintext. The client shows the
	 * mask, omits the field unless the user edits it, and clears a key by
	 * sending an explicit null.
	 *
	 * @since x.x.x
	 * @param string $key     Field key.
	 * @param bool   $network Whether the request is in a network admin context.
	 * @return mixed
	 */
	public static function read_for_output( $key, $network = false ) {
		$field = self::get_field( $key );
		$value = self::read( $key, $network );

		if ( $field && ! empty( $field['secret'] ) ) {
			return self::mask( $value );
		}

		return $value;
	}

	/**
	 * Mask a credential down to its last four characters.
	 *
	 * @since x.x.x
	 * @param string $value Raw value.
	 * @return string
	 */
	public static function mask( $value ) {
		$value = (string) $value;

		if ( '' === $value ) {
			return '';
		}

		if ( strlen( $value ) <= 4 ) {
			return str_repeat( '*', strlen( $value ) );
		}

		return str_repeat( '*', 8 ) . substr( $value, -4 );
	}

	/**
	 * Apply a payload of field values.
	 *
	 * Only keys present in the payload are considered; everything else keeps
	 * whatever it already has. Sub-keys of pp_elementor_settings are collected
	 * and written in a single read-modify-write so that one group's save cannot
	 * drop another's values.
	 *
	 * @since x.x.x
	 * @param array $payload Map of field key => submitted value.
	 * @param array $args    {
	 *     @type bool $network Whether the request is in a network admin context.
	 * }
	 * @return array {
	 *     @type array $written Field keys that were written.
	 *     @type array $skipped Field keys ignored, mapped to a reason.
	 * }
	 */
	public static function apply( array $payload, $args = [] ) {
		$network = ! empty( $args['network'] );
		$written = [];
		$skipped = [];
		$batched = [];

		foreach ( self::get_fields() as $key => $field ) {
			if ( ! array_key_exists( $key, $payload ) ) {
				continue;
			}

			if ( ! self::current_user_can( $key, $network ) ) {
				$skipped[ $key ] = 'forbidden';
				continue;
			}

			$value = $payload[ $key ];

			// Rule 3: a credential is cleared only by an explicit null. An empty
			// string is treated as an untouched masked field and ignored, so a
			// round-tripped placeholder can never wipe a stored key.
			if ( ! empty( $field['secret'] ) ) {
				if ( null === $value ) {
					$value = '';
				} elseif ( '' === $value ) {
					$skipped[ $key ] = 'empty_secret_ignored';
					continue;
				}
			}

			$value = self::normalize( $value, $field, self::read( $key, $network ) );

			if ( 'settings' === $field['store'] ) {
				$batched[ $key ] = $value;
				$written[]       = $key;
				continue;
			}

			$network_override = isset( $field['network_override'] ) ? $field['network_override'] : true;

			if ( self::ON_EMPTY_DELETE === $field['strategy'] && ! self::is_on( $payload[ $key ] ) ) {
				self::delete_option( $key, $network );
			} else {
				self::write_option( $key, $value, $network_override, $network );
			}

			$written[] = $key;
		}

		if ( ! empty( $batched ) ) {
			self::write_settings_array( $batched );
		}

		return [
			'written' => $written,
			'skipped' => $skipped,
		];
	}

	/**
	 * Coerce a submitted value into its stored shape.
	 *
	 * @since x.x.x
	 * @param mixed $value   Submitted value.
	 * @param array $field   Field descriptor.
	 * @param mixed $stored  Currently stored value, for rules that must not
	 *                       discard what the browser could not have seen.
	 * @return mixed
	 */
	private static function normalize( $value, $field, $stored = null ) {
		switch ( $field['type'] ) {
			case 'on_off':
				return self::is_on( $value ) ? 'on' : 'off';

			case 'boolean':
				return self::is_on( $value ) ? 1 : 0;

			case 'enum':
				$choices = isset( $field['choices'] ) ? $field['choices'] : [];

				if ( is_callable( $choices ) ) {
					// A callable returns a value => label map, so the allowed
					// set is its keys rather than the labels shown to the user.
					$allowed = array_keys( (array) call_user_func( $choices ) );
				} elseif ( is_array( $choices ) ) {
					$allowed = $choices;
				} else {
					$allowed = [];
				}

				// An empty value means "no choice made" and is always allowed;
				// it is what the default is for these fields.
				if ( ! empty( $allowed ) && '' !== $value && ! in_array( $value, $allowed, true ) ) {
					return $field['default'];
				}

				return $value;

			case 'list':
				if ( ! is_array( $value ) ) {
					return self::NONE_SELECTED;
				}

				$value = array_values( array_filter( array_map( 'sanitize_text_field', $value ) ) );

				if ( ! empty( $field['choices'] ) && is_callable( $field['choices'] ) ) {
					$allowed = array_keys( (array) call_user_func( $field['choices'] ) );

					/*
					 * A choice list can shrink for reasons that have nothing to
					 * do with this save, and a name the browser was never
					 * offered cannot have been deselected in it. The thirteen
					 * WooCommerce builder widgets leave the catalogue whenever
					 * the builder is switched off; intersecting them away here
					 * would silently disable every one of them the next time
					 * anything on the Elements panel was saved, and switching
					 * the builder back on would find them all off.
					 *
					 * So: honour the submission for everything currently on
					 * offer, and carry the rest of the stored selection over
					 * untouched.
					 */
					$carried = is_array( $stored ) ? array_diff( $stored, $allowed ) : [];
					$value   = array_values(
						array_unique( array_merge( array_intersect( $value, $allowed ), $carried ) )
					);
				}

				// An empty selection is the string 'disabled', never an empty
				// array — consumers read a non-'disabled' non-array as "all on".
				return empty( $value ) ? self::NONE_SELECTED : $value;
		}

		if ( isset( $field['sanitize'] ) && is_callable( $field['sanitize'] ) ) {
			return call_user_func( $field['sanitize'], $value );
		}

		return $value;
	}

	/**
	 * Whether a submitted value means "on".
	 *
	 * @since x.x.x
	 * @param mixed $value Submitted value.
	 * @return bool
	 */
	private static function is_on( $value ) {
		if ( is_string( $value ) ) {
			return ! in_array( strtolower( $value ), [ '', '0', 'off', 'false', 'no', 'disabled' ], true );
		}

		return (bool) $value;
	}

	/**
	 * Read the raw pp_elementor_settings array.
	 *
	 * Deliberately NOT PP_Admin_Settings::get_settings(): that method returns
	 * the array after the pp_elements_admin_settings filter has run, so seeding
	 * a save from it persists whatever a filter injected.
	 *
	 * @since x.x.x
	 * @return array
	 */
	private static function get_settings_array() {
		$settings = get_option( self::SETTINGS_OPTION );

		return is_array( $settings ) ? $settings : [];
	}

	/**
	 * Merge values into pp_elementor_settings.
	 *
	 * @since x.x.x
	 * @param array $values Map of sub-key => value.
	 * @return void
	 */
	private static function write_settings_array( array $values ) {
		$settings = array_merge( self::get_settings_array(), $values );

		update_option( self::SETTINGS_OPTION, $settings );
	}

	/**
	 * Read an option, preserving the legacy multisite fallback.
	 *
	 * Mirrors PP_Admin_Settings::get_option(), but takes the network context
	 * explicitly rather than calling is_network_admin() — which is always false
	 * during a REST request.
	 *
	 * @since x.x.x
	 * @param string $key              Option key.
	 * @param bool   $network_override Whether a site value may override the network one.
	 * @param bool   $network          Whether the request is in a network admin context.
	 * @return mixed
	 */
	private static function read_option( $key, $network_override, $network ) {
		if ( $network ) {
			return get_site_option( $key );
		}

		if ( ! $network_override && is_multisite() ) {
			return get_site_option( $key );
		}

		if ( $network_override && is_multisite() ) {
			$value = get_option( $key );

			if ( false === $value || ( is_array( $value ) && in_array( self::NONE_SELECTED, $value, true ) && 1 != get_option( 'pp_override_ms' ) ) ) {
				return get_site_option( $key );
			}

			return $value;
		}

		return get_option( $key );
	}

	/**
	 * Write an option.
	 *
	 * The legacy PP_Admin_Settings::update_option() inspected
	 * $_POST['pp_override_ms'] inside the storage helper and deleted the option
	 * when it was absent. No template ever rendered that checkbox and nothing
	 * writes the flag, so the branch is dropped here; the corresponding read
	 * fallback is preserved in read_option().
	 *
	 * @since x.x.x
	 * @param string $key              Option key.
	 * @param mixed  $value            Value to store.
	 * @param bool   $network_override Whether a site value may override the network one.
	 * @param bool   $network          Whether the request is in a network admin context.
	 * @return void
	 */
	private static function write_option( $key, $value, $network_override, $network ) {
		if ( $network ) {
			update_site_option( $key, $value );

			return;
		}

		if ( ! $network_override && is_multisite() ) {
			update_site_option( $key, $value );

			return;
		}

		update_option( $key, $value );
	}

	/**
	 * Delete an option.
	 *
	 * @since x.x.x
	 * @param string $key     Option key.
	 * @param bool   $network Whether the request is in a network admin context.
	 * @return void
	 */
	private static function delete_option( $key, $network ) {
		if ( $network ) {
			delete_site_option( $key );

			return;
		}

		delete_option( $key );
	}
}
