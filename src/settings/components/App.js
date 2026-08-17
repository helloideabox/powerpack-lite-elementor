/**
 * Settings screen shell: navigation, dirty tracking, and saving.
 *
 * Saving submits only what changed. That is not an optimisation — it is what
 * makes it impossible for one panel to blank another panel's options, which is
 * the failure mode the old self-posting form had.
 */

import { __, sprintf, _n } from '@wordpress/i18n';
import { useState, useEffect, useCallback, useRef } from '@wordpress/element';
import { Button, Notice, Spinner, Snackbar } from '@wordpress/components';
import { Icon } from '@wordpress/icons';
import { fetchSettings, saveSettings, fetchTemplates } from '../api';
import { PANELS, FIELD_META } from '../panels';
import { PANEL_ICONS } from '../icons';
import SettingsPanel from './SettingsPanel';
import ModulesPanel from './ModulesPanel';
import WelcomePanel from './WelcomePanel';
import LicensePanel from './LicensePanel';
import WhiteLabelPanel from './WhiteLabelPanel';

const boot = window.ppSettingsBootstrap || {};

/**
 * Panels that are not driven by the generic renderer.
 *
 * 'group' is the registry group the panel's settings live in, where that
 * differs from the panel key. The key is what the URL shows, so it follows the
 * tab's name rather than the internal one: the widget library stores its list
 * in an option called 'modules', but nothing on screen has ever used that word.
 */
const SPECIAL = {
	welcome: { title: () => __( 'Welcome', 'powerpack-lite-for-elementor' ) },
	elements: { title: () => __( 'Elements', 'powerpack-lite-for-elementor' ), group: 'modules' },
	license: { title: () => __( 'License', 'powerpack-lite-for-elementor' ) },
	white_label: { title: () => __( 'White Label', 'powerpack-lite-for-elementor' ) },
};

/** Navigation order, by group key. */
const ORDER = [
	'welcome',
	'elements',
	'extensions',
	'integration',
	'advanced',
	'license',
	'white_label',
];

/*
 * Panels with no settings behind them. Welcome reads the other panels; License
 * and White Label show what the paid edition offers and are not editable here.
 * They are always in the nav, because there is no group to gate them on.
 */
const PREVIEW_ONLY = [ 'welcome', 'license', 'white_label' ];

/**
 * Which panel the URL is asking for, and what it wants shown once there.
 *
 * The second segment of '#panel/view' is an instruction for the panel rather
 * than a panel of its own — the section to scroll to, in the one case that
 * uses it today.
 *
 * @return {Object} panel, a key from ORDER falling back to Welcome, and view.
 */
const routeFromHash = () => {
	const [ panel, view ] = window.location.hash.replace( /^#/, '' ).split( '/' );

	return ORDER.includes( panel ) ? { panel, view: view || '' } : { panel: 'welcome', view: '' };
};

export default function App() {
	const [ payload, setPayload ] = useState( null );
	const [ changes, setChanges ] = useState( {} );
	const [ templates, setTemplates ] = useState( {} );
	const [ saving, setSaving ] = useState( false );
	const [ error, setError ] = useState( null );
	const [ toast, setToast ] = useState( null );
	const [ route, setRoute ] = useState( routeFromHash );

	// The panel half of the route, which is all most of this component cares
	// about.
	const active = route.panel;

	// The save bar animates both ways, so it has to outlive the state that
	// summons it: on the way out it stays mounted until the slide finishes.
	const [ saveBar, setSaveBar ] = useState( { mounted: false, leaving: false } );
	const lastDirtyCount = useRef( 0 );

	const noticesSlot = useRef( null );

	useEffect( () => {
		fetchSettings()
			.then( setPayload )
			.catch( ( err ) => setError( err.message ) );
	}, [] );

	/*
	 * WordPress fires admin_notices above the whole screen, which on this page
	 * puts our own notices over the header rather than under it. PHP renders
	 * them hidden at the top of the wrap and this moves the node into place.
	 *
	 * Moved rather than re-serialised through the bootstrap: a notice is markup
	 * this plugin already generated, and passing it through JSON only to inject
	 * it back with dangerouslySetInnerHTML would add an escaping decision with
	 * nothing to gain. React does not render children into the slot, so nothing
	 * here contends with it.
	 *
	 * Runs on every render because the slot only exists once the settings have
	 * loaded, and the first render is the loading spinner.
	 */
	useEffect( () => {
		const source = document.querySelector( '.pp-settings-wrap > .pp-settings-notices' );

		if ( source && noticesSlot.current && source.parentNode !== noticesSlot.current ) {
			noticesSlot.current.appendChild( source );
			source.removeAttribute( 'hidden' );
		}
	} );

	/*
	 * Follow the URL, not just read it once on mount. Without this the browser
	 * Back button moves the hash but leaves the previous panel on screen, and
	 * pasting a link with a hash into an already-open tab does nothing.
	 *
	 * Unsaved edits survive the switch: they live in `changes`, which spans
	 * panels, so navigating away and back loses nothing.
	 */
	useEffect( () => {
		const onHashChange = () => setRoute( routeFromHash() );

		window.addEventListener( 'hashchange', onHashChange );

		return () => window.removeEventListener( 'hashchange', onHashChange );
	}, [] );

	/*
	 * A view is an instruction, not a location. Once the panel has been handed
	 * it the address bar goes back to naming the panel alone, so reloading or
	 * coming back later does not silently re-apply it. replaceState rather than
	 * assigning the hash: this must not fire hashchange and start the round
	 * again.
	 */
	useEffect( () => {
		if ( ! route.view ) {
			return;
		}

		const { pathname, search } = window.location;

		window.history.replaceState( null, '', `${ pathname }${ search }#${ route.panel }` );
	}, [ route ] );

	// Template pickers declare which collection they need; fetch each once.
	useEffect( () => {
		if ( ! payload ) {
			return;
		}

		const needed = new Set();

		Object.keys( payload.fields ).forEach( ( key ) => {
			const meta = FIELD_META[ key ];
			if ( meta?.templates ) {
				needed.add( meta.templates );
			}
		} );

		needed.forEach( ( type ) => {
			if ( templates[ type ] ) {
				return;
			}

			fetchTemplates( type )
				.then( ( result ) =>
					setTemplates( ( previous ) => ( { ...previous, [ type ]: result.groups } ) )
				)
				.catch( () =>
					setTemplates( ( previous ) => ( { ...previous, [ type ]: [] } ) )
				);
		} );
	}, [ payload ] ); // eslint-disable-line react-hooks/exhaustive-deps

	const dirtyCount = Object.keys( changes ).length;

	// Computed here rather than beside the markup so the animation effect below
	// runs on every render, ahead of the loading and error returns.
	const wantSaveBar = dirtyCount > 0 && 'license' !== active;

	useEffect( () => {
		if ( wantSaveBar ) {
			setSaveBar( { mounted: true, leaving: false } );
			return undefined;
		}

		let timer;

		setSaveBar( ( previous ) => {
			if ( ! previous.mounted ) {
				return previous;
			}

			// Keep it on screen until the slide-out finishes.
			timer = setTimeout( () => setSaveBar( { mounted: false, leaving: false } ), 200 );

			return { mounted: true, leaving: true };
		} );

		return () => clearTimeout( timer );
	}, [ wantSaveBar ] );

	// Guard against losing edits to a stray navigation.
	useEffect( () => {
		if ( ! dirtyCount ) {
			return undefined;
		}

		const warn = ( event ) => {
			event.preventDefault();
			event.returnValue = '';
		};

		window.addEventListener( 'beforeunload', warn );

		return () => window.removeEventListener( 'beforeunload', warn );
	}, [ dirtyCount ] );

	const onChange = useCallback( ( key, value ) => {
		setChanges( ( previous ) => ( { ...previous, [ key ]: value } ) );
	}, [] );

	const navigate = ( key, view = '' ) => {
		setRoute( { panel: key, view } );
		window.location.hash = view ? `${ key }/${ view }` : key;
	};

	const save = async () => {
		if ( ! dirtyCount ) {
			return;
		}

		setSaving( true );
		setError( null );

		try {
			const result = await saveSettings( changes );

			setPayload( result );
			setChanges( {} );

			const ignored = Object.keys( result.skipped || {} ).length;

			setToast(
				ignored
					? sprintf(
							/* translators: %d: number of fields that were not saved. */
							_n(
								'Settings saved. %d field was left unchanged.',
								'Settings saved. %d fields were left unchanged.',
								ignored,
								'powerpack-lite-for-elementor'
							),
							ignored
					  )
					: __( 'Settings saved.', 'powerpack-lite-for-elementor' )
			);
		} catch ( err ) {
			setError( err.message );
		}

		setSaving( false );
	};

	if ( error && ! payload ) {
		return (
			<div className="pp-settings">
				<Notice status="error" isDismissible={ false }>
					{ error }
				</Notice>
			</div>
		);
	}

	if ( ! payload ) {
		return (
			<div className="pp-settings pp-settings--loading">
				<Spinner />
			</div>
		);
	}

	const settings = payload.settings || {};
	const fields = payload.fields || {};

	// White label can hide whole panels, so visibility is derived from the
	// current values rather than baked in at page load.
	const value = ( key ) =>
		Object.prototype.hasOwnProperty.call( changes, key ) ? changes[ key ] : settings[ key ];

	const hidden = new Set();

	if ( value( 'hide_integration_tab' ) === 'on' ) {
		hidden.add( 'integration' );
	}

	if ( settings.hide_wl_settings === 'on' ) {
		hidden.add( 'white_label' );
	}

	const available = ORDER.filter( ( key ) => {
		if ( hidden.has( key ) ) {
			return false;
		}

		/*
		 * Welcome summarises the other panels rather than owning settings of its
		 * own, and License and White Label preview settings this edition does
		 * not have. None of the three has a group to check for.
		 */
		if ( PREVIEW_ONLY.includes( key ) ) {
			return true;
		}

		if ( SPECIAL[ key ] ) {
			return payload.groups.includes( SPECIAL[ key ].group || key );
		}

		return payload.groups.includes( key );
	} );

	const current = available.includes( active ) ? active : available[ 0 ];
	const panel = PANELS.find( ( item ) => item.group === current );

	// Hold the last real count so the label does not blink to zero mid-exit.
	if ( dirtyCount > 0 ) {
		lastDirtyCount.current = dirtyCount;
	}

	const shownDirtyCount = dirtyCount > 0 ? dirtyCount : lastDirtyCount.current;

	return (
		<div className={ `pp-settings${ saveBar.mounted ? ' has-save-bar' : '' }` }>
			<div className="pp-admin-settings-header">
				<div className="pp-admin-settings-head">
					<h1>
						{ ! boot.hideLogo && (
							<span className="ppicon-powerpack-small" aria-hidden="true" />
						) }
						<span>{ boot.adminLabel || 'PowerPack' }</span>

						{ /*
						  * The gaps between these are drawn by flexbox, which
						  * leaves the heading's text as one run — "PowerPackPro
						  * v2.13.5" to anything reading it rather than looking
						  * at it. The spaces are explicit for that reason.
						  */ }
						{ boot.isPro && (
							<>
								{ ' ' }
								<span className="pp-edition">{ __( 'Pro', 'powerpack-lite-for-elementor' ) }</span>
							</>
						) }
						{ boot.version && (
							<>
								{ ' ' }
								<span className="version">v{ boot.version }</span>
							</>
						) }
					</h1>

					<ul className="pp-admin-settings-topbar-nav">
						{ boot.docsLink && (
							<li className="pp-admin-settings-topbar-nav-item">
								<a href={ boot.docsLink } target="_blank" rel="noopener noreferrer">
									<span
										className="dashicons dashicons-editor-help"
										aria-hidden="true"
									/>
									{ __( 'Documentation', 'powerpack-lite-for-elementor' ) }
								</a>
							</li>
						) }
						{ boot.showSupport && boot.supportLink && (
							<li className="pp-admin-settings-topbar-nav-item">
								<a href={ boot.supportLink } target="_blank" rel="noopener noreferrer">
									<span className="dashicons dashicons-email" aria-hidden="true" />
									{ __( 'Support', 'powerpack-lite-for-elementor' ) }
								</a>
							</li>
						) }
					</ul>
				</div>
			</div>

			<div className="pp-admin-settings-body">
				<div className="pp-admin-settings-tabs-container">
					<nav
						className="pp-admin-settings-tabs"
						aria-label={ __( 'Settings sections', 'powerpack-lite-for-elementor' ) }
					>
						{ available.map( ( key ) => {
							const item = SPECIAL[ key ] || PANELS.find( ( p ) => p.group === key );

							return (
								<button
									type="button"
									key={ key }
									className={ `pp-settings-tab${
										key === current ? ' is-active' : ''
									}` }
									aria-current={ key === current ? 'page' : undefined }
									onClick={ () => navigate( key ) }
								>
									{ PANEL_ICONS[ key ] && (
										<Icon icon={ PANEL_ICONS[ key ] } size={ 20 } />
									) }
									<span>{ item ? item.title() : key }</span>
								</button>
							);
						} ) }
					</nav>

					{ /*
					  * Says what the upgrade is, not just that there is one. A
					  * bare "Go Pro" button asks for a decision without giving
					  * anything to decide on; the widget count comes from the
					  * catalogue, so it stays true as widgets ship.
					  */ }
					<div className="pp-sidebar-upgrade">
						<span className="pp-sidebar-upgrade-badge">
							{ __( 'Pro', 'powerpack-lite-for-elementor' ) }
						</span>

						<p className="pp-sidebar-upgrade-title">
							{ boot.proWidgets
								? sprintf(
										/* translators: %d: number of additional widgets. */
										_n(
											'%d more widget',
											'%d more widgets',
											boot.proWidgets,
											'powerpack-lite-for-elementor'
										),
										boot.proWidgets
								  )
								: __( 'More widgets in Pro', 'powerpack-lite-for-elementor' ) }
						</p>

						<p className="pp-sidebar-upgrade-text">
							{ __(
								'Plus the header, footer and WooCommerce builders, white labelling, and support straight from the team.',
								'powerpack-lite-for-elementor'
							) }
						</p>

						<a
							className="pp-sidebar-upgrade-button"
							href={ `${ boot.upgradeUrl ||
								'https://powerpackelements.com/upgrade/' }?utm_source=lite-settings&utm_medium=wp-dash&utm_campaign=sidebar` }
							target="_blank"
							rel="noopener noreferrer"
						>
							{ __( 'See what’s in Pro', 'powerpack-lite-for-elementor' ) }
						</a>
					</div>
				</div>

				<main className="pp-admin-settings-content">
					{ /*
					  * Where this plugin's own admin notices end up. They are
					  * rendered by PHP above the screen, where WordPress fires
					  * admin_notices, and moved here on mount — see the effect
					  * above. React never renders children into this node, so
					  * owning it from outside is safe.
					  *
					  * In the content column rather than across the whole screen:
					  * a notice belongs with what it is about, and every panel
					  * opens with its title, so this is the one place that reads
					  * as "before the page" on all of them.
					  */ }
					<div className="pp-admin-settings-notices" ref={ noticesSlot } />

					{ error && (
						<Notice status="error" onRemove={ () => setError( null ) }>
							{ error }
						</Notice>
					) }

					{ current === 'welcome' && (
						<WelcomePanel
							settings={ settings }
							changes={ changes }
							groups={ payload.groups }
							onNavigate={ navigate }
						/>
					) }

					{ current === 'elements' && (
						<ModulesPanel
							settings={ settings }
							changes={ changes }
							onChange={ onChange }
							panels={ available }
							onNavigate={ navigate }
						/>
					) }

					{ current === 'license' && <LicensePanel /> }

					{ current === 'white_label' && <WhiteLabelPanel /> }

					{ panel && (
						<SettingsPanel
							panel={ panel }
							fields={ fields }
							settings={ settings }
							changes={ changes }
							templates={ templates }
							onChange={ onChange }
							view={ route.view }
						/>
					) }
				</main>
			</div>

			{ saveBar.mounted && (
				<footer
					className={ `pp-settings__save is-dirty${ saveBar.leaving ? ' is-leaving' : '' }` }
					aria-hidden={ saveBar.leaving }
				>
					<span className="pp-settings__dirty">
						{ sprintf(
							/* translators: %d: number of unsaved changes. */
							_n( '%d unsaved change', '%d unsaved changes', shownDirtyCount, 'powerpack-lite-for-elementor' ),
							shownDirtyCount
						) }
					</span>

					<Button
						variant="tertiary"
						disabled={ saving || saveBar.leaving }
						onClick={ () => setChanges( {} ) }
					>
						{ __( 'Discard', 'powerpack-lite-for-elementor' ) }
					</Button>

					<Button
						variant="primary"
						disabled={ saving || saveBar.leaving }
						onClick={ save }
					>
						{ saving ? <Spinner /> : __( 'Save Changes', 'powerpack-lite-for-elementor' ) }
					</Button>
				</footer>
			) }

			{ /*
			  * Snackbar carries no positioning of its own — in the editor a
			  * SnackbarList places it. Left in the flow it rendered at the foot
			  * of the document, well below the fold.
			  */ }
			{ toast && (
				<div className="pp-settings__toast">
					<Snackbar onRemove={ () => setToast( null ) }>{ toast }</Snackbar>
				</div>
			) }
		</div>
	);
}
