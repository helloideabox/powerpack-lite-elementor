/**
 * Thin wrappers over the powerpack/v1 REST routes.
 *
 * apiFetch handles the X-WP-Nonce header and the rest_route fallback, so
 * nothing here needs to know how the site exposes the REST API.
 */

import apiFetch from '@wordpress/api-fetch';

const NS = '/powerpack/v1';

export const fetchSettings = () => apiFetch( { path: `${ NS }/settings` } );

/**
 * Persist a partial settings payload.
 *
 * Only the keys passed here are touched server side. Credentials the user did
 * not edit must be left out entirely; clearing one is an explicit null.
 *
 * @param {Object} settings Map of setting key to value.
 * @return {Promise<Object>} Fresh settings payload plus written/skipped lists.
 */
export const saveSettings = ( settings ) =>
	apiFetch( {
		path: `${ NS }/settings`,
		method: 'POST',
		data: { settings },
	} );

export const fetchModules = () => apiFetch( { path: `${ NS }/modules` } );

/**
 * Which widgets appear on the site.
 *
 * Deliberately a second request: working this out reads every Elementor
 * document, so it must not hold up the catalogue.
 *
 * @param {boolean} refresh Recompute rather than use the cached answer.
 * @return {Promise<Object>} Used and not-used widget names.
 */
export const fetchModuleUsage = ( refresh = false ) =>
	apiFetch( { path: `${ NS }/modules/usage${ refresh ? '?refresh=1' : '' }` } );

export const fetchLicense = ( refresh = false ) =>
	apiFetch( { path: `${ NS }/license${ refresh ? '?refresh=1' : '' }` } );

export const activateLicense = ( key ) =>
	apiFetch( {
		path: `${ NS }/license/activate`,
		method: 'POST',
		data: { key },
	} );

export const deactivateLicense = () =>
	apiFetch( { path: `${ NS }/license/deactivate`, method: 'POST' } );

export const verifyCredential = ( field, value ) =>
	apiFetch( {
		path: `${ NS }/integration/verify`,
		method: 'POST',
		data: { field, value },
	} );

export const fetchTemplates = ( type ) =>
	apiFetch( { path: `${ NS }/templates?type=${ encodeURIComponent( type ) }` } );

/**
 * Content for the Welcome panel.
 *
 * Server side because neither half can be read from the browser: the
 * changelog is a file on disk, and the blog is another origin.
 *
 * @return {Promise<Object>} Recent releases and recent posts.
 */
export const fetchWelcome = ( posts = true ) =>
	apiFetch( { path: `${ NS }/welcome${ posts ? '' : '?posts=0' }` } );

/**
 * Hide the setup checklist for the current user.
 *
 * @return {Promise<Object>} Confirmation.
 */
export const dismissSetup = () =>
	apiFetch( { path: `${ NS }/welcome/dismiss-setup`, method: 'POST' } );
