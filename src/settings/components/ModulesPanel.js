/**
 * The widget library.
 *
 * Enabled state is edited here but submitted like any other setting: the value
 * of 'pp_elementor_modules' is always the COMPLETE list of enabled widget
 * names. Searching and filtering narrow what is on screen, never what is
 * submitted — which is what made the old screen's filter views hazardous.
 */

import { __, sprintf } from '@wordpress/i18n';
import { useState, useEffect, useMemo, useRef } from '@wordpress/element';
import { Notice, Button } from '@wordpress/components';
import { Icon } from '@wordpress/icons';
import { fetchModules, fetchModuleUsage } from '../api';
import { LINK_ICONS } from '../icons';
import { deriveEnabled, allWidgetNames } from '../enabled-modules';
import ToggleSwitch from './ToggleSwitch';
import PanelHead from './PanelHead';
import Section from './Section';
import ModulesSkeleton from './ModulesSkeleton';

const boot = window.ppSettingsBootstrap || {};

const KEY = 'pp_elementor_modules';

/**
 * Shorten a category name for the category list.
 *
 * Every category is named "<something> Elements", which is redundant inside a
 * panel already titled Elements. The names are plain array keys in PP_Config
 * rather than translated strings, so matching the English suffix is safe.
 * Anything that does not match, or would be left empty, keeps its full name.
 *
 * @param {string} name Category name.
 * @return {string} Name for the list.
 */
const shortName = ( name ) => name.replace( /\s*Elements$/, '' ).trim() || name;

const FILTERS = [
	{ id: 'all', label: () => __( 'All', 'powerpack-lite-for-elementor' ) },
	{ id: 'used', label: () => __( 'Used on this site', 'powerpack-lite-for-elementor' ) },
	{ id: 'notused', label: () => __( 'Not used', 'powerpack-lite-for-elementor' ) },
];

export default function ModulesPanel( { settings, changes, onChange } ) {
	const [ data, setData ] = useState( null );
	const [ error, setError ] = useState( null );
	const [ search, setSearch ] = useState( '' );
	const [ filter, setFilter ] = useState( 'all' );
	const [ usage, setUsage ] = useState( null );
	const [ activeCategory, setActiveCategory ] = useState( null );
	const sectionRefs = useRef( {} );

	/*
	 * The catalogue is preloaded with the page, so it usually resolves on the
	 * first microtask — a placeholder shown unconditionally is on screen for a
	 * single frame and reads as a flicker. Holding it back means it only
	 * appears when there is actually a wait to cover.
	 */
	const [ showSkeleton, setShowSkeleton ] = useState( false );

	useEffect( () => {
		const timer = setTimeout( () => setShowSkeleton( true ), 150 );

		return () => clearTimeout( timer );
	}, [] );

	useEffect( () => {
		let cancelled = false;

		fetchModules()
			.then( ( result ) => ! cancelled && setData( result ) )
			.catch( ( err ) => ! cancelled && setError( err.message ) );

		// Fired separately and not awaited: classifying usage reads every
		// Elementor document, so the catalogue must not wait for it.
		fetchModuleUsage()
			.then( ( result ) => ! cancelled && setUsage( result ) )
			.catch( () => ! cancelled && setUsage( { available: false, used: [], notUsed: [] } ) );

		return () => {
			cancelled = true;
		};
	}, [] );

	const isDirty = Object.prototype.hasOwnProperty.call( changes, KEY );

	/**
	 * The authoritative enabled set.
	 *
	 * Order matters here — see enabled-modules.js. The catalogue is fetched
	 * once on mount, so after a save its flags are stale; the saved value has
	 * to win or the switches keep showing the pre-save state.
	 */
	const enabled = useMemo(
		() =>
			deriveEnabled( {
				isDirty,
				edited: changes[ KEY ],
				saved: settings[ KEY ],
				categories: data?.categories,
			} ),
		[ isDirty, changes, settings, data ]
	);

	const allNames = useMemo( () => allWidgetNames( data?.categories ), [ data ] );

	/*
	 * The free edition lists the paid widgets so the library reads as one
	 * library. They are searchable and grouped like any other, but every count
	 * and bulk action has to ignore them: "43/46" and "Activate All" are
	 * promises about what can be switched, and the server would drop the rest
	 * on save anyway.
	 */
	const switchable = useMemo(
		() =>
			( data?.categories || [] ).flatMap( ( category ) =>
				( category.widgets || [] )
					.filter( ( widget ) => ! widget.isPro )
					.map( ( widget ) => widget.name )
			),
		[ data ]
	);
	const usedSet = useMemo( () => new Set( usage?.used || [] ), [ usage ] );
	const usageReady = !! usage?.available;
	const term = search.trim().toLowerCase();

	/*
	 * Derived above the loading and error returns, because the scroll spy
	 * effect below has to run on every render — hooks cannot be conditional.
	 */
	const categories = useMemo( () => {
		const matches = ( widget ) => {
			if ( term && ! widget.title.toLowerCase().includes( term ) ) {
				return false;
			}

			if ( 'used' === filter ) {
				return usedSet.has( widget.name );
			}

			if ( 'notused' === filter ) {
				return ! usedSet.has( widget.name );
			}

			return true;
		};

		return ( data?.categories || [] )
			.map( ( category ) => ( {
				...category,
				widgets: category.widgets.filter( matches ),
			} ) )
			.filter( ( category ) => category.widgets.length > 0 );
	}, [ data, term, filter, usedSet ] );

	const slugKey = categories.map( ( category ) => category.slug ).join( '|' );

	/**
	 * Track which category is in view, so the side list can follow the scroll.
	 *
	 * The top margin clears the sticky toolbar and the bottom one keeps the
	 * band near the top of the viewport, so the highlight changes when a
	 * heading reaches the top rather than when it merely enters the screen.
	 */
	useEffect( () => {
		const slugs = slugKey ? slugKey.split( '|' ) : [];

		if ( ! slugs.length || 'undefined' === typeof IntersectionObserver ) {
			return undefined;
		}

		const visible = new Set();

		const observer = new IntersectionObserver(
			( entries ) => {
				entries.forEach( ( entry ) => {
					const slug = entry.target.dataset.ppSlug;

					if ( entry.isIntersecting ) {
						visible.add( slug );
					} else {
						visible.delete( slug );
					}
				} );

				// Topmost wins, so scrolling up highlights the section coming
				// back into view rather than the one leaving.
				const first = slugs.find( ( slug ) => visible.has( slug ) );

				if ( first ) {
					setActiveCategory( first );
				}
			},
			{ rootMargin: '-140px 0px -55% 0px', threshold: 0 }
		);

		slugs.forEach( ( slug ) => {
			const node = sectionRefs.current[ slug ];

			if ( node ) {
				observer.observe( node );
			}
		} );

		return () => observer.disconnect();
	}, [ slugKey ] );

	const commit = ( set ) => onChange( KEY, Array.from( set ) );

	const toggle = ( name, on ) => {
		const next = new Set( enabled );

		if ( on ) {
			next.add( name );
		} else {
			next.delete( name );
		}

		commit( next );
	};

	const toggleMany = ( names, on ) => {
		const next = new Set( enabled );

		names.forEach( ( name ) => ( on ? next.add( name ) : next.delete( name ) ) );
		commit( next );
	};

	/**
	 * Scroll to a category.
	 *
	 * Done with refs rather than href anchors: the shell uses location.hash for
	 * panel routing, so a category anchor would knock the user to another tab.
	 */
	const scrollTo = ( slug ) => {
		sectionRefs.current[ slug ]?.scrollIntoView( { behavior: 'smooth', block: 'start' } );
		setActiveCategory( slug );
	};

	const clearFilters = () => {
		setSearch( '' );
		setFilter( 'all' );
	};

	if ( error ) {
		return (
			<Notice status="error" isDismissible={ false }>
				{ error }
			</Notice>
		);
	}

	/*
	 * A skeleton rather than a spinner: this panel is the landing screen, and
	 * the catalogue is large enough that a centred spinner reads as a stall.
	 * Laying out the shape it is about to take also stops the page jumping
	 * when the data arrives.
	 */
	if ( ! data ) {
		return showSkeleton ? <ModulesSkeleton /> : null;
	}

	const shownCount = categories.reduce( ( total, c ) => total + c.widgets.length, 0 );
	const isFiltered = !! term || 'all' !== filter;

	const filterCount = ( id ) => {
		if ( 'all' === id ) {
			return switchable.length;
		}

		if ( ! usageReady ) {
			return null;
		}

		return 'used' === id ? usedSet.size : switchable.length - usedSet.size;
	};

	/**
	 * A category's widget names, and how many of them are on.
	 *
	 * Counted against what is currently shown, so the numbers agree with the
	 * rows underneath them while a filter is active.
	 *
	 * @param {Object} category Category.
	 * @return {Object} names and on.
	 */
	const tally = ( category ) => {
		const names = category.widgets
			.filter( ( widget ) => ! widget.isPro )
			.map( ( widget ) => widget.name );

		return { names, on: names.filter( ( name ) => enabled.has( name ) ).length };
	};

	return (
		<>
			<PanelHead title={ __( 'Elements', 'powerpack-lite-for-elementor' ) } />

			<div className="pp-panel-body pp-panel-body--split">
				<nav className="pp-category-nav" aria-label={ __( 'Widget categories', 'powerpack-lite-for-elementor' ) }>
					{ categories.map( ( category ) => {
						const { names, on } = tally( category );
						const isActive = activeCategory === category.slug;

						return (
							<button
								type="button"
								key={ category.slug }
								className={ `pp-category-nav-item${ isActive ? ' is-active' : '' }` }
								aria-current={ isActive ? 'true' : undefined }
								onClick={ () => scrollTo( category.slug ) }
							>
								<span className="pp-category-nav-name">
									{ shortName( category.name ) }
								</span>
								{ /*
								  * A group of nothing but paid widgets has no
								  * count to give: "0/0" reads as a category
								  * where everything is switched off.
								  */ }
								{ names.length > 0 && (
									<span className="pp-category-nav-count">
										{ on }/{ names.length }
									</span>
								) }
							</button>
						);
					} ) }

					{ ! categories.length && (
						<p className="pp-category-nav-empty">
							{ __( 'No matching categories.', 'powerpack-lite-for-elementor' ) }
						</p>
					) }
				</nav>

				<div className="pp-panel-main">
					<div className="pp-toolbar">
						<div className="pp-search">
							<span className="dashicons dashicons-search" aria-hidden="true" />
							<input
								type="search"
								className="pp-search-input"
								value={ search }
								placeholder={ sprintf(
									/* translators: %d: total number of widgets. */
									__( 'Search %d widgets', 'powerpack-lite-for-elementor' ),
									allNames.length
								) }
								aria-label={ __( 'Search widgets', 'powerpack-lite-for-elementor' ) }
								autoComplete="off"
								onChange={ ( event ) => setSearch( event.target.value ) }
							/>
							{ search && (
								<button
									type="button"
									className="pp-search-clear"
									aria-label={ __( 'Clear search', 'powerpack-lite-for-elementor' ) }
									onClick={ () => setSearch( '' ) }
								>
									<span className="dashicons dashicons-no-alt" aria-hidden="true" />
								</button>
							) }
						</div>

						<div
							className="pp-seg"
							role="group"
							aria-label={ __( 'Filter widgets by use', 'powerpack-lite-for-elementor' ) }
						>
							{ FILTERS.map( ( item ) => {
								const count = filterCount( item.id );
								const disabled = 'all' !== item.id && ! usageReady;

								return (
									<button
										type="button"
										key={ item.id }
										className={ `pp-seg-btn${ filter === item.id ? ' is-active' : '' }` }
										aria-pressed={ filter === item.id }
										disabled={ disabled }
										title={
											disabled
												? __( 'Working out which widgets are in use…', 'powerpack-lite-for-elementor' )
												: undefined
										}
										onClick={ () => setFilter( item.id ) }
									>
										{ item.label() }
										{ null !== count && <span className="pp-seg-n">{ count }</span> }
									</button>
								);
							} ) }
						</div>

						<div className="pp-toolbar-actions">
							<Button variant="tertiary" onClick={ () => toggleMany( switchable, true ) }>
								{ __( 'Activate All', 'powerpack-lite-for-elementor' ) }
							</Button>
							<Button variant="tertiary" onClick={ () => toggleMany( switchable, false ) }>
								{ __( 'Deactivate All', 'powerpack-lite-for-elementor' ) }
							</Button>
						</div>
					</div>

					{ isFiltered && (
						<p className="pp-filter-summary">
							{ sprintf(
								/* translators: 1: matching widgets, 2: total widgets. */
								__( 'Showing %1$d of %2$d widgets.', 'powerpack-lite-for-elementor' ),
								shownCount,
								allNames.length
							) }{ ' ' }
							<Button variant="link" onClick={ clearFilters }>
								{ __( 'Clear filters', 'powerpack-lite-for-elementor' ) }
							</Button>
						</p>
					) }

					{ ! categories.length && (
						<div className="pp-empty">
							<div className="pp-empty-mark" aria-hidden="true">
								<span className="dashicons dashicons-search" />
							</div>
							<div className="pp-empty-title">
								{ term
									? sprintf(
											/* translators: %s: search term. */
											__( 'No widget matches “%s”', 'powerpack-lite-for-elementor' ),
											search.trim()
									  )
									: __( 'Nothing to show', 'powerpack-lite-for-elementor' ) }
							</div>
							<p className="pp-empty-desc">
								{ 'notused' === filter
									? __( 'Every widget is in use somewhere on this site.', 'powerpack-lite-for-elementor' )
									: __(
											'Try a shorter word, or have a look at the Extensions panel. Some features add controls to elements you already use instead of adding a widget.',
											'powerpack-lite-for-elementor'
									  ) }
							</p>
							<Button variant="secondary" onClick={ clearFilters }>
								{ __( 'Clear filters', 'powerpack-lite-for-elementor' ) }
							</Button>
						</div>
					) }

					{ categories.map( ( category ) => {
						const { names, on } = tally( category );
						const allOn = names.length > 0 && on === names.length;

						return (
							<Section
								key={ category.slug }
								slug={ category.slug }
								sectionRef={ ( node ) => ( sectionRefs.current[ category.slug ] = node ) }
								title={ category.name }
								count={ names.length ? `${ on }/${ names.length }` : null }
								actions={
									names.length ? (
										<button
											type="button"
											className="button toggle-all-widgets"
											onClick={ () => toggleMany( names, ! allOn ) }
										>
											{ allOn
												? __( 'Deactivate All', 'powerpack-lite-for-elementor' )
												: __( 'Activate All', 'powerpack-lite-for-elementor' ) }
										</button>
									) : null
								}
							>
								<div className="pp-modules">
									{ category.widgets.map( ( widget ) => (
										<div
											className={ `pp-modules-table-element${
												widget.isPro ? ' is-pro' : ''
											}` }
											key={ widget.name }
										>
											<div className="pp-modules-table-element-content">
												<div className="pp-modules-table-element-header">
													<span className="pp-module-icon">
														<i className={ widget.icon } aria-hidden="true" />
													</span>
												</div>
												<div className="pp-modules-table-element-name">
													{ widget.title }
												</div>
											</div>

											<div className="pp-modules-table-element-footer">
												<div className="pp-modules-table-element-footer-links">
													{ widget.demo && (
														<a
															href={ widget.demo }
															className="pp-module-link pp-module-demo"
															target="_blank"
															rel="noopener noreferrer"
														>
															<Icon icon={ LINK_ICONS.demo } size={ 16 } />
															<span className="pp-module-link-text">
																{ __( 'Demo', 'powerpack-lite-for-elementor' ) }
															</span>
														</a>
													) }

													{ widget.docs && (
														<a
															href={ widget.docs }
															className="pp-module-link pp-module-docs"
															target="_blank"
															rel="noopener noreferrer"
														>
															<Icon icon={ LINK_ICONS.docs } size={ 16 } />
															<span className="pp-module-link-text">
																{ __( 'Docs', 'powerpack-lite-for-elementor' ) }
															</span>
														</a>
													) }
												</div>

												{ /*
												  * A link, not a badge: it has
												  * somewhere to go, and a label
												  * saying "Pro" beside a switch
												  * you cannot use would only say
												  * why it is disabled.
												  */ }
												{ widget.isPro ? (
													<a
														className="pp-module-pro"
														href={ boot.upgradeUrl || '#' }
														target="_blank"
														rel="noopener noreferrer"
													>
														{ __( 'Pro', 'powerpack-lite-for-elementor' ) }
													</a>
												) : (
													<ToggleSwitch
														checked={ enabled.has( widget.name ) }
														label={ widget.title }
														onChange={ ( checked ) => toggle( widget.name, checked ) }
													/>
												) }
											</div>
										</div>
									) ) }
								</div>
							</Section>
						);
					} ) }
				</div>
			</div>
		</>
	);
}
