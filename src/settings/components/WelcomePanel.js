/**
 * The landing panel.
 *
 * It answers what an admin opens a plugin's home screen to ask — is it working,
 * what did I get, what should I do next — from the state of this site rather
 * than from a brochure. The checklist reads real settings, and the counts come
 * from the same data the Elements panel uses.
 *
 * Where it departs from the paid edition's version is the part a free plugin
 * has to do and a paid one does not: say plainly what the free edition is and
 * what the paid one adds, once, in a place someone can read and move past. That
 * is the "More in Pro" section, and it is deliberately a single honest
 * comparison rather than a lock on every other panel.
 *
 * The review ask is the other difference. This edition lives on WordPress.org,
 * where reviews are how anyone decides whether to install it, so it is worth
 * asking — but quietly, at the bottom, once.
 */

import { __, sprintf, _n } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';
import { Icon, check, chevronRight, seen } from '@wordpress/icons';
import { fetchModules, fetchModuleUsage, fetchWelcome, dismissSetup } from '../api';
import { deriveEnabled, shippedWidgetNames, countEnabled } from '../enabled-modules';
import { PANEL_ICONS } from '../icons';
import PanelHead from './PanelHead';
import Section from './Section';

const boot = window.ppSettingsBootstrap || {};

const UPGRADE_URL = boot.upgradeUrl || 'https://powerpackelements.com/upgrade/';
const COMMUNITY_URL = 'https://www.facebook.com/groups/ppelementor';
const CHANGELOG_URL = 'https://powerpackelements.com/change-log/';
const DEMOS_URL = 'https://powerpackelements.com/elementor-widgets/';
const BLOG_URL = 'https://powerpackelements.com/blog/';
const CHANNEL_URL = 'https://www.youtube.com/@Ideaboxcreations';
const FORUM_URL = 'https://wordpress.org/support/plugin/powerpack-lite-for-elementor/';
const REVIEW_URL = 'https://wordpress.org/support/plugin/powerpack-lite-for-elementor/reviews/#new-post';

/*
 * Links rather than embeds. Four iframes on the default landing tab would load
 * YouTube's player on every visit to the settings screen, for content that
 * never changes and that most people watch once.
 *
 * Titles are the videos' own and are not translated: they name a specific
 * English-language video, and a translated title would not match what the link
 * opens.
 */
const VIDEOS = [
	{
		id: 'pGo6NwqhUbU',
		title: 'How to Install and Activate PowerPack Addon for Elementor',
	},
	{
		id: 'Gc2SFo5h3Jo',
		title: 'How to add a custom cursor to an Elementor Section easily',
	},
	{
		id: 'hru4hlJsBdM',
		title: 'How to customize the WooCommerce Checkout page with Elementor',
	},
	{
		id: 'aOHDRzDkcfY',
		title: 'How to customize the WooCommerce Cart page with Elementor',
	},
];

/*
 * How many lines of each kind the release summary shows. Taken as quotas rather
 * than the first N of the file: the changelog lists every new feature before the
 * first fix, so a flat slice of a large release showed nothing but additions.
 */
const CHANGELOG_QUOTAS = { new: 3, enhancement: 4, fix: 3 };

/**
 * A representative sample of a release, in the file's own order.
 *
 * @param {Array} items Parsed changelog entries.
 * @return {Array} Up to ten entries, mixed across the kinds present.
 */
const summarise = ( items = [] ) => {
	const left = { ...CHANGELOG_QUOTAS };
	const picked = items.filter( ( item ) => {
		if ( ! left[ item.type ] ) {
			return false;
		}

		left[ item.type ] -= 1;

		return true;
	} );

	return picked.length ? picked : items.slice( 0, 3 );
};

/**
 * One line of the setup checklist.
 *
 * @param {Object}   props         Component props.
 * @param {boolean}  props.done    Whether the step is complete.
 * @param {string}   props.title   Step name.
 * @param {string}   props.text    What it means, shown while outstanding.
 * @param {string}   props.action  Link text.
 * @param {Function} props.onClick Navigate to the panel that completes it.
 * @return {JSX.Element} The step.
 */
function Step( { done, title, text, action, onClick } ) {
	return (
		<li className={ `pp-home-step${ done ? ' is-done' : '' }` }>
			<span className="pp-home-step-mark" aria-hidden="true">
				{ done && <Icon icon={ check } size={ 16 } /> }
			</span>

			<span className="pp-home-step-body">
				<span className="pp-home-step-title">{ title }</span>
				{ ! done && <span className="pp-home-step-text">{ text }</span> }
			</span>

			{ done ? (
				<span className="pp-home-step-state">
					{ __( 'Done', 'powerpack-lite-for-elementor' ) }
				</span>
			) : (
				<button type="button" className="pp-home-step-action" onClick={ onClick }>
					{ action }
					<Icon icon={ chevronRight } size={ 18 } />
				</button>
			) }
		</li>
	);
}

/**
 * A figure with a link to the panel that owns it.
 *
 * @param {Object}   props         Component props.
 * @param {Object}   props.icon    Icon to draw. Panel icons come from
 *                                 PANEL_ICONS; a tile that does not stand for a
 *                                 panel passes its own.
 * @param {string}   props.label   What is being counted.
 * @param {Node}     props.value   The headline figure.
 * @param {string}   props.detail  Supporting line.
 * @param {string}   props.cta     Link text.
 * @param {Function} props.onClick Navigate to the owning panel.
 * @return {JSX.Element} The tile.
 */
function Stat( { icon, label, value, detail, cta, onClick } ) {
	return (
		<button type="button" className="pp-home-stat" onClick={ onClick }>
			<span className="pp-home-stat-icon" aria-hidden="true">
				<Icon icon={ icon } size={ 20 } />
			</span>
			<span className="pp-home-stat-label">{ label }</span>
			<span className="pp-home-stat-value">{ value }</span>
			<span className="pp-home-stat-detail">{ detail }</span>
			<span className="pp-home-stat-cta">{ cta }</span>
		</button>
	);
}

export default function WelcomePanel( { settings, changes, groups, onNavigate } ) {
	const [ categories, setCategories ] = useState( null );
	const [ usage, setUsage ] = useState( null );
	const [ release, setRelease ] = useState( null );
	const [ posts, setPosts ] = useState( null );

	/*
	 * Seeded from the page rather than a request, so a checklist that was
	 * dismissed long ago never flashes up before the answer arrives. The write
	 * is fire and forget: the worst case is that it comes back next reload,
	 * which is a smaller failure than blocking the click on a round trip.
	 */
	const [ setupHidden, setSetupHidden ] = useState( !! boot.setupDone );

	const hideSetup = () => {
		setSetupHidden( true );
		dismissSetup().catch( () => {} );
	};

	useEffect( () => {
		let cancelled = false;

		fetchModules()
			.then( ( result ) => ! cancelled && setCategories( result.categories || [] ) )
			.catch( () => ! cancelled && setCategories( [] ) );

		fetchModuleUsage()
			.then( ( result ) => ! cancelled && setUsage( result ) )
			.catch( () => ! cancelled && setUsage( { available: false, used: [], notUsed: [] } ) );

		/*
		 * Two calls to one route, deliberately. The changelog is a local file
		 * and the posts are a remote request that can take seconds on a cold
		 * cache, so asking for them together would hold the release notes
		 * behind the blog.
		 */
		fetchWelcome( false )
			.then( ( result ) => ! cancelled && setRelease( ( result.changelog || [] )[ 0 ] || false ) )
			.catch( () => ! cancelled && setRelease( false ) );

		fetchWelcome( true )
			.then( ( result ) => ! cancelled && setPosts( result.posts || [] ) )
			.catch( () => ! cancelled && setPosts( [] ) );

		return () => {
			cancelled = true;
		};
	}, [] );

	const value = ( key ) =>
		Object.prototype.hasOwnProperty.call( changes, key ) ? changes[ key ] : settings[ key ];

	/*
	 * Widgets, counted the way the Elements panel counts them. The catalogue
	 * lists the paid edition's widgets too, so both figures are taken from the
	 * switchable ones only — "12 of 44" has to mean what this plugin ships.
	 */
	const allNames = categories ? shippedWidgetNames( categories ) : null;
	const enabled = allNames
		? deriveEnabled( {
				isDirty: Object.prototype.hasOwnProperty.call( changes, 'pp_elementor_modules' ),
				edited: changes.pp_elementor_modules,
				saved: settings.pp_elementor_modules,
				categories,
		  } )
		: null;
	const widgetsOn = enabled ? countEnabled( enabled, allNames ) : null;

	/* How many widgets the paid edition adds, counted from the same payload. */
	const proWidgets = categories
		? categories.reduce(
				( total, category ) =>
					total + ( category.widgets || [] ).filter( ( widget ) => widget.isPro ).length,
				0
		  )
		: 0;

	/*
	 * The number worth acting on: widgets that are switched on and appear
	 * nowhere on the site.
	 */
	const usageReady = usage && usage.available;
	const idle = usageReady && enabled ? usage.notUsed.filter( ( n ) => enabled.has( n ) ).length : 0;
	const inUse = usageReady && enabled ? usage.used.filter( ( n ) => enabled.has( n ) ).length : 0;

	const extensionValue = value( 'pp_elementor_extensions' );
	const extensionsOn = Array.isArray( extensionValue ) ? extensionValue.length : 0;

	/* Setup steps, each only listed when the site could act on it. */
	const steps = [];

	if ( usageReady ) {
		steps.push( {
			key: 'build',
			done: inUse > 0,
			title: __( 'Build something with a PowerPack widget', 'powerpack-lite-for-elementor' ),
			text: __(
				'Open a page in Elementor and look for the PowerPack category in the widget panel.',
				'powerpack-lite-for-elementor'
			),
			action: __( 'See the widgets', 'powerpack-lite-for-elementor' ),
			panel: 'elements',
		} );
	}

	if ( groups.includes( 'extensions' ) ) {
		steps.push( {
			key: 'extensions',
			done: extensionsOn > 0,
			title: __( 'Turn on the extensions you want', 'powerpack-lite-for-elementor' ),
			text: __(
				'Extensions add controls to elements you already use, rather than new widgets.',
				'powerpack-lite-for-elementor'
			),
			action: __( 'Choose extensions', 'powerpack-lite-for-elementor' ),
			panel: 'extensions',
		} );
	}

	if ( groups.includes( 'integration' ) ) {
		steps.push( {
			key: 'integration',
			done: !! value( 'pp_instagram_access_token' ),
			title: __( 'Connect Instagram', 'powerpack-lite-for-elementor' ),
			text: __(
				'The Instagram Feed widget needs an access token before it can show anything.',
				'powerpack-lite-for-elementor'
			),
			action: __( 'Add a token', 'powerpack-lite-for-elementor' ),
			panel: 'integration',
		} );
	}

	if ( usageReady ) {
		steps.push( {
			key: 'trim',
			done: 0 === idle,
			title: __( 'Turn off what you are not using', 'powerpack-lite-for-elementor' ),
			text: __(
				'Disabled widgets stop loading in the editor and on the front end.',
				'powerpack-lite-for-elementor'
			),
			action: __( 'Review', 'powerpack-lite-for-elementor' ),
			panel: 'elements',
		} );
	}

	const outstanding = steps.filter( ( step ) => ! step.done ).length;

	/*
	 * What the paid edition adds, in the order someone weighing it up would
	 * ask. The widget count is the live one from the catalogue rather than a
	 * number typed here, so it cannot drift away from what is actually offered.
	 */
	const proPoints = [
		proWidgets > 0 && {
			icon: 'dashicons-screenoptions',
			title: sprintf(
				/* translators: %d: number of additional widgets. */
				_n( '%d more widget', '%d more widgets', proWidgets, 'powerpack-lite-for-elementor' ),
				proWidgets
			),
			text: __(
				'Tabs, timelines, pricing tables, galleries, sliders and the rest — greyed out on the Elements panel so you can see exactly which.',
				'powerpack-lite-for-elementor'
			),
		},
		{
			icon: 'dashicons-editor-kitchensink',
			title: __( 'Header and Footer Builder', 'powerpack-lite-for-elementor' ),
			text: __(
				'Design the header and footer in Elementor and use them in place of the theme’s.',
				'powerpack-lite-for-elementor'
			),
		},
		{
			icon: 'dashicons-cart',
			title: __( 'WooCommerce Builder', 'powerpack-lite-for-elementor' ),
			text: __(
				'Lay out the product page and the shop archive with Elementor, plus cart and checkout widgets.',
				'powerpack-lite-for-elementor'
			),
		},
		{
			icon: 'dashicons-admin-users',
			title: __( 'Login and Register forms', 'powerpack-lite-for-elementor' ),
			text: __(
				'Custom account forms, with Facebook and Google sign-in.',
				'powerpack-lite-for-elementor'
			),
		},
		{
			icon: 'dashicons-art',
			title: __( 'White Label', 'powerpack-lite-for-elementor' ),
			text: __(
				'Ship the plugin under your own name and links when you hand a site over.',
				'powerpack-lite-for-elementor'
			),
		},
		{
			icon: 'dashicons-sos',
			title: __( 'Support from the team', 'powerpack-lite-for-elementor' ),
			text: __(
				'A ticket that reaches the people who wrote the widget, rather than a forum queue.',
				'powerpack-lite-for-elementor'
			),
		},
	].filter( Boolean );

	const resources = [
		boot.docsLink && {
			href: boot.docsLink,
			icon: 'dashicons-book',
			title: __( 'Documentation', 'powerpack-lite-for-elementor' ),
			text: __( 'Set-up guides for every widget and extension.', 'powerpack-lite-for-elementor' ),
		},
		{
			href: DEMOS_URL,
			icon: 'dashicons-visibility',
			title: __( 'Widget demos', 'powerpack-lite-for-elementor' ),
			text: __(
				'See what each widget does before you build with it.',
				'powerpack-lite-for-elementor'
			),
		},
		{
			href: FORUM_URL,
			icon: 'dashicons-sos',
			title: __( 'Support forum', 'powerpack-lite-for-elementor' ),
			text: __(
				'Free support on WordPress.org. Pro licences get a direct ticket instead.',
				'powerpack-lite-for-elementor'
			),
		},
		{
			href: COMMUNITY_URL,
			icon: 'dashicons-groups',
			title: __( 'Community', 'powerpack-lite-for-elementor' ),
			text: __( 'Ask other builders in the Facebook group.', 'powerpack-lite-for-elementor' ),
		},
	].filter( Boolean );

	/*
	 * Held back until the posts have resolved: the video card would otherwise
	 * render full width and halve a moment later when the blog arrived.
	 */
	const learnReady = null !== posts;
	const showPosts = posts && posts.length > 0;

	return (
		<>
			<PanelHead title={ __( 'Welcome', 'powerpack-lite-for-elementor' ) } constrained />

			<div className="pp-panel-body pp-home">
				{ steps.length > 0 && ! setupHidden && (
					<Section
						title={
							outstanding
								? __( 'Finish setting up', 'powerpack-lite-for-elementor' )
								: __( 'Setup', 'powerpack-lite-for-elementor' )
						}
						count={ sprintf(
							/* translators: 1: completed steps, 2: total steps. */
							__( '%1$d of %2$d done', 'powerpack-lite-for-elementor' ),
							steps.length - outstanding,
							steps.length
						) }
						actions={
							<button type="button" className="pp-home-dismiss" onClick={ hideSetup }>
								{ __( 'Dismiss', 'powerpack-lite-for-elementor' ) }
							</button>
						}
					>
						<ul className="pp-home-steps">
							{ steps.map( ( step ) => (
								<Step
									key={ step.key }
									done={ step.done }
									title={ step.title }
									text={ step.text }
									action={ step.action }
									onClick={ () => onNavigate( step.panel ) }
								/>
							) ) }
						</ul>
					</Section>
				) }

				<Section title={ __( 'At a glance', 'powerpack-lite-for-elementor' ) }>
					<div className="pp-home-stats">
						<Stat
							icon={ PANEL_ICONS.elements }
							label={ __( 'Widgets', 'powerpack-lite-for-elementor' ) }
							value={
								null === widgetsOn
									? '—'
									: sprintf(
											/* translators: 1: enabled widgets, 2: total. */
											__( '%1$d of %2$d', 'powerpack-lite-for-elementor' ),
											widgetsOn,
											allNames.length
									  )
							}
							detail={ __( 'available in the editor', 'powerpack-lite-for-elementor' ) }
							cta={ __( 'Manage widgets', 'powerpack-lite-for-elementor' ) }
							onClick={ () => onNavigate( 'elements' ) }
						/>

						{ groups.includes( 'extensions' ) && (
							<Stat
								icon={ PANEL_ICONS.extensions }
								label={ __( 'Extensions', 'powerpack-lite-for-elementor' ) }
								value={ String( extensionsOn ) }
								detail={ __(
									'adding controls to your elements',
									'powerpack-lite-for-elementor'
								) }
								cta={ __( 'Manage extensions', 'powerpack-lite-for-elementor' ) }
								onClick={ () => onNavigate( 'extensions' ) }
							/>
						) }

						{ usageReady && (
							<Stat
								icon={ seen }
								label={ __( 'In use', 'powerpack-lite-for-elementor' ) }
								value={ String( inUse ) }
								detail={ __(
									'widgets appear somewhere on this site',
									'powerpack-lite-for-elementor'
								) }
								cta={ __( 'See which', 'powerpack-lite-for-elementor' ) }
								onClick={ () => onNavigate( 'elements' ) }
							/>
						) }
					</div>

					{ idle > 0 && (
						<div className="pp-home-tip">
							<span className="dashicons dashicons-performance" aria-hidden="true" />
							<div className="pp-home-tip-body">
								<p>
									{ sprintf(
										/* translators: %d: number of widgets. */
										_n(
											'%d enabled widget does not appear anywhere on this site.',
											'%d enabled widgets do not appear anywhere on this site.',
											idle,
											'powerpack-lite-for-elementor'
										),
										idle
									) }{ ' ' }
									{ __(
										'Turning them off shortens the editor panel and stops their assets loading.',
										'powerpack-lite-for-elementor'
									) }
								</p>
								<button
									type="button"
									className="button toggle-all-widgets"
									onClick={ () => onNavigate( 'elements' ) }
								>
									{ __( 'Review unused widgets', 'powerpack-lite-for-elementor' ) }
								</button>
							</div>
						</div>
					) }
				</Section>

				<Section
					title={ __( 'More in Pro', 'powerpack-lite-for-elementor' ) }
					actions={
						<a
							className="pp-home-more"
							href={ `${ UPGRADE_URL }?utm_source=lite-settings&utm_medium=wp-dash&utm_campaign=welcome` }
							target="_blank"
							rel="noopener noreferrer"
						>
							{ __( 'Compare editions', 'powerpack-lite-for-elementor' ) }
						</a>
					}
				>
					<div className="pp-home-resources">
						{ proPoints.map( ( point ) => (
							<div key={ point.title } className="pp-home-resource is-static">
								<span className={ `dashicons ${ point.icon }` } aria-hidden="true" />
								<span className="pp-home-resource-body">
									<span className="pp-home-resource-title">{ point.title }</span>
									<span className="pp-home-resource-text">{ point.text }</span>
								</span>
							</div>
						) ) }
					</div>
				</Section>

				{ release && (
					<Section
						title={ sprintf(
							/* translators: %s: version number. */
							__( 'New in %s', 'powerpack-lite-for-elementor' ),
							release.version
						) }
						description={ release.date || undefined }
						actions={
							<a
								className="pp-home-more"
								href={ CHANGELOG_URL }
								target="_blank"
								rel="noopener noreferrer"
							>
								{ __( 'Full changelog', 'powerpack-lite-for-elementor' ) }
							</a>
						}
						defaultOpen={ false }
					>
						<ul className="pp-home-changes">
							{ summarise( release.items ).map( ( item, index ) => (
								<li key={ index } className={ `pp-home-change is-${ item.type }` }>
									{ item.text }
								</li>
							) ) }
						</ul>
					</Section>
				) }

				{ learnReady && (
					<Section title={ __( 'Learn', 'powerpack-lite-for-elementor' ) }>
						<div className="pp-field-row pp-home-learn">
							<div className="pp-field-group">
								<h4 className="pp-field-group-title">
									{ __( 'Video tutorials', 'powerpack-lite-for-elementor' ) }
									<a
										className="pp-home-more"
										href={ CHANNEL_URL }
										target="_blank"
										rel="noopener noreferrer"
									>
										{ __( 'All videos', 'powerpack-lite-for-elementor' ) }
									</a>
								</h4>
								<ul className="pp-home-list">
									{ VIDEOS.map( ( video ) => (
										<li key={ video.id }>
											<a
												href={ `https://www.youtube.com/watch?v=${ video.id }` }
												target="_blank"
												rel="noopener noreferrer"
											>
												<span
													className="dashicons dashicons-video-alt3"
													aria-hidden="true"
												/>
												<span className="pp-home-list-text">{ video.title }</span>
											</a>
										</li>
									) ) }
								</ul>
							</div>

							{ showPosts && (
								<div className="pp-field-group">
									<h4 className="pp-field-group-title">
										{ __( 'From the blog', 'powerpack-lite-for-elementor' ) }
										<a
											className="pp-home-more"
											href={ BLOG_URL }
											target="_blank"
											rel="noopener noreferrer"
										>
											{ __( 'All posts', 'powerpack-lite-for-elementor' ) }
										</a>
									</h4>
									<ul className="pp-home-list">
										{ posts.map( ( post ) => (
											<li key={ post.link }>
												<a href={ post.link } target="_blank" rel="noopener noreferrer">
													<span
														className="dashicons dashicons-media-document"
														aria-hidden="true"
													/>
													<span className="pp-home-list-text">
														{ post.title }
														{ post.date && (
															<span className="pp-home-list-meta">{ post.date }</span>
														) }
													</span>
												</a>
											</li>
										) ) }
									</ul>
								</div>
							) }
						</div>
					</Section>
				) }

				{ resources.length > 0 && (
					<Section title={ __( 'Help', 'powerpack-lite-for-elementor' ) }>
						<div className="pp-home-resources">
							{ resources.map( ( item ) => (
								<a
									key={ item.href }
									className="pp-home-resource"
									href={ item.href }
									target="_blank"
									rel="noopener noreferrer"
								>
									<span className={ `dashicons ${ item.icon }` } aria-hidden="true" />
									<span className="pp-home-resource-body">
										<span className="pp-home-resource-title">{ item.title }</span>
										<span className="pp-home-resource-text">{ item.text }</span>
									</span>
								</a>
							) ) }
						</div>
					</Section>
				) }

				<p className="pp-home-review">
					{ __( 'Getting some use out of PowerPack?', 'powerpack-lite-for-elementor' ) }{ ' ' }
					<a href={ REVIEW_URL } target="_blank" rel="noopener noreferrer">
						{ __(
							'A review on WordPress.org helps other people find it.',
							'powerpack-lite-for-elementor'
						) }
					</a>
				</p>
			</div>
		</>
	);
}
