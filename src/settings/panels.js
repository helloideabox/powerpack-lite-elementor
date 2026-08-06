/**
 * Presentation metadata for the registry-driven panels.
 *
 * The REST payload describes how each setting is *stored* (type, secret,
 * choices). This file describes how it is *shown*: which panel, which section,
 * what label and help text. Keeping the two apart means the registry stays a
 * storage contract and UI strings stay translatable on the JavaScript side.
 *
 * Fields the server exposes but this file does not mention are rendered at the
 * end of their panel with a humanised label, so a newly registered setting is
 * never invisible.
 */

import { __ } from '@wordpress/i18n';
import { createInterpolateElement } from '@wordpress/element';
import { ExternalLink } from '@wordpress/components';

/**
 * Build help text containing a link.
 *
 * createInterpolateElement keeps the sentence as one translatable string, so
 * translators are never handed a fragment either side of an anchor.
 *
 * @param {string} text Translated string containing an <a> placeholder.
 * @param {string} href Link target.
 * @return {JSX.Element} Help content.
 */
const withLink = ( text, href ) =>
	createInterpolateElement( text, {
		a: <ExternalLink href={ href } />,
	} );

/**
 * Panels rendered by the generic renderer, in display order.
 *
 * Elements and License have bespoke panels and are not listed here.
 */
export const PANELS = [
	{
		group: 'extensions',
		title: () => __( 'Extensions', 'powerpack-lite-for-elementor' ),
		sections: [
			{
				title: () => __( 'General Extensions', 'powerpack-lite-for-elementor' ),

				// Puts Activate/Deactivate All in the section header, where the
				// widget library keeps the same control.
				toggleAll: 'pp_elementor_extensions',
				fields: [ 'pp_elementor_extensions' ],
			},
		],
	},
	{
		group: 'integration',
		title: () => __( 'Integration', 'powerpack-lite-for-elementor' ),
		sections: [
			{
				title: () => __( 'Instagram Feed', 'powerpack-lite-for-elementor' ),
				description: () =>
					__(
						'Lets the Instagram Feed widget read your posts.',
						'powerpack-lite-for-elementor'
					),
				fields: [ 'pp_instagram_access_token' ],
			},
		],
	},
];

/**
 * What each extension does.
 *
 * Keyed by the slug pp_get_extensions() uses. The list is filterable, so an
 * extension added by a third party simply has no entry and renders as a name
 * on its own.
 */
const EXTENSION_HELP = {
	'pp-display-conditions': () =>
		__(
			'Show or hide any element based on date, login state, post data, browser and more.',
			'powerpack-lite-for-elementor'
		),
	'pp-animated-gradient-background': () =>
		__( 'A gradient background that shifts between colours as it plays.', 'powerpack-lite-for-elementor' ),
	'pp-wrapper-link': () =>
		__( 'Make a whole section, container or widget clickable as one link.', 'powerpack-lite-for-elementor' ),
	'pp-custom-cursor': () =>
		__( 'Replace the mouse cursor with an icon, image or text over chosen elements.', 'powerpack-lite-for-elementor' ),
};

/**
 * Per-field label, help text, and any extra rendering hints.
 *
 * `condition` hides a field until its prerequisite is met, matching what the
 * PHP screen did in jQuery. It only affects display: a hidden field's stored
 * value is left alone, since the payload carries edits rather than the whole
 * form.
 */
export const FIELD_META = {
	pp_elementor_extensions: {
		// The section title already says Extensions.
		hideLabel: true,
		itemHelp: EXTENSION_HELP,
	},
	pp_instagram_access_token: {
		label: () => __( 'Access Token', 'powerpack-lite-for-elementor' ),
		help: () =>
			withLink(
				__(
					'Need help creating an Instagram access token? <a>Read our guide</a>.',
					'powerpack-lite-for-elementor'
				),
				'https://powerpackelements.com/docs/powerpack/widgets/instagram-feed/how-to-get-instagram-access-token/'
			),
	},
};

/**
 * Fall back to a readable label for a setting this file has not been told about.
 *
 * @param {string} key Setting key.
 * @return {string} Humanised label.
 */
export const humanise = ( key ) =>
	key
		.replace( /^pp_(elementor_)?/, '' )
		.replace( /_/g, ' ' )
		.replace( /\b\w/g, ( c ) => c.toUpperCase() );
