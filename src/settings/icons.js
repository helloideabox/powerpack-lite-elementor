/**
 * Icons for the panel navigation.
 *
 * Drawn from @wordpress/icons so they sit naturally beside the block
 * components already on this screen. The package is bundled rather than
 * externalised, so only the icons imported here ship.
 *
 * Keyed by panel group, which means panels.js stays about layout and this file
 * is the only place that knows what anything looks like.
 */

import {
	home,
	grid,
	plugins,
	globe,
	layout,
	store,
	login,
	key,
	cog,
	tool,
	brush,
	external,
	page,
} from '@wordpress/icons';

/**
 * Icons used on the per-widget links.
 *
 * Docs uses a page rather than the question mark: a "?" reads as contextual
 * help, while these link out to written documentation — which is also what the
 * document-file glyph they replace was saying.
 *
 * Setup is a cog rather than the globe the Integration panel wears in the
 * navigation. Beside Demo and Docs it has to say "there is something to do
 * here", and the globe next to two outbound links would read as a third one.
 */
export const LINK_ICONS = {
	demo: external,
	docs: page,
	setup: cog,
};

export const PANEL_ICONS = {
	welcome: home,
	elements: grid,
	extensions: plugins,

	// Not 'connection', which is also a plug and sat directly under the plug
	// used for Extensions — two near-identical glyphs in adjacent rows. A globe
	// also says "third-party service" better than a plug does.
	integration: globe,

	header_footer: layout,
	woo: store,
	login_register: login,
	license: key,
	advanced: tool,
	white_label: brush,
};

/*
 * Brand marks, carried over from the previous settings page. @wordpress/icons
 * carries no company logos, and these are the only two the screen needs, so
 * they stay as inline paths rather than pulling in a logo set for a pair.
 * Both fill with currentColor, so they take their colour from context.
 */
export const BRAND_ICONS = {
	facebook: (
		<svg viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
			<path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z" />
		</svg>
	),
	/*
	 * Google's mark is four colours by definition, so its fills are baked in
	 * rather than taken from currentColor like the other two. A path's own
	 * fill attribute beats an inherited one, so the rule on the svg leaves
	 * these alone.
	 */
	google: (
		<svg viewBox="0 0 256 262" aria-hidden="true">
			<path
				fill="#4285F4"
				d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027"
			/>
			<path
				fill="#34A853"
				d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1"
			/>
			<path
				fill="#FBBC05"
				d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782"
			/>
			<path
				fill="#EB4335"
				d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251"
			/>
		</svg>
	),
	/*
	 * Taller than it is wide, unlike the other two. Its own viewBox keeps the
	 * burst in proportion — the shared 16px box scales it to fit rather than
	 * squashing it.
	 */
	yelp: (
		<svg viewBox="0 0 384 512" fill="currentColor" aria-hidden="true">
			<path d="M42.9 240.32l99.62 48.61c19.2 9.4 16.2 37.51-4.5 42.71L30.5 358.45a22.79 22.79 0 0 1-28.21-19.6 197.16 197.16 0 0 1 9-85.32 22.8 22.8 0 0 1 31.61-13.21zm44 239.25a199.45 199.45 0 0 0 79.42 32.11A22.78 22.78 0 0 0 192.94 490l3.9-110.82c.7-21.3-25.5-31.91-39.81-16.1l-74.21 82.4a22.82 22.82 0 0 0 4.09 34.09zm145.34-109.92l58.81 94a22.93 22.93 0 0 0 34 5.5 198.36 198.36 0 0 0 52.71-67.61A23 23 0 0 0 364.17 370l-105.42-34.26c-20.31-6.5-37.81 15.8-26.51 33.91zm148.33-132.23a197.44 197.44 0 0 0-50.41-69.31 22.85 22.85 0 0 0-34 4.4l-62 91.92c-11.9 17.7 4.7 40.61 25.2 34.71L366 268.63a23 23 0 0 0 14.61-31.21zM62.11 30.18a22.86 22.86 0 0 0-9.9 32l104.12 180.44c11.7 20.2 42.61 11.9 42.61-11.4V22.88a22.67 22.67 0 0 0-24.5-22.8 320.37 320.37 0 0 0-112.33 30.1z" />
		</svg>
	),
};
