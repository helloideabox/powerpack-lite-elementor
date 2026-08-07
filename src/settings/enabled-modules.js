/**
 * Works out which widgets are enabled, from whichever source is authoritative
 * at the moment.
 *
 * Pulled out of the panel because the precedence here is the whole bug surface:
 * the widget catalogue is fetched once on mount, so after a save its per-widget
 * flags describe the state *before* the save. Falling back to it while the saved
 * value is available leaves the UI showing the old state until a reload.
 *
 * Precedence:
 *   1. Unsaved edits.
 *   2. The saved value, which the save response refreshes.
 *   3. The catalogue's flags, only when the saved value is an unrecognised
 *      shape.
 *
 * The stored value is tri-state — a list of names, or the string 'disabled'
 * meaning none. An absent option would mean "all enabled", but PowerPack seeds
 * the option on first admin load, so it is never absent by the time this runs.
 */

/** Sentinel the server stores when nothing is selected. */
export const NONE_SELECTED = 'disabled';

/**
 * @param {Object}  args
 * @param {boolean} args.isDirty    Whether the user has unsaved edits.
 * @param {*}       args.edited     The unsaved value, when dirty.
 * @param {*}       args.saved      The stored value from the settings payload.
 * @param {Array}   args.categories Catalogue from GET /modules.
 * @return {Set<string>} Enabled widget names.
 */
export function deriveEnabled( { isDirty, edited, saved, categories = [] } ) {
	if ( isDirty ) {
		return new Set( Array.isArray( edited ) ? edited : [] );
	}

	if ( Array.isArray( saved ) ) {
		return new Set( saved );
	}

	if ( saved === NONE_SELECTED ) {
		return new Set();
	}

	const set = new Set();

	categories.forEach( ( category ) =>
		( category.widgets || [] ).forEach( ( widget ) => {
			if ( widget.enabled ) {
				set.add( widget.name );
			}
		} )
	);

	return set;
}

/**
 * Every widget name in the catalogue, including any this build cannot switch.
 *
 * The free edition lists the paid edition's widgets so the library reads as one
 * library. They are searchable and grouped like any other, so counts about what
 * is *on screen* — "Search 76 widgets" — want this one.
 *
 * @param {Array} categories Catalogue from GET /modules.
 * @return {string[]} Widget names.
 */
export function allWidgetNames( categories = [] ) {
	return categories.flatMap( ( category ) =>
		( category.widgets || [] ).map( ( widget ) => widget.name )
	);
}

/**
 * The widget names this build actually ships.
 *
 * Counts about what is *switched on* want this one instead. "71 of 76 available
 * in the editor" was wrong in both halves: a paid widget is neither enabled nor
 * enableable, so it belongs in neither the figure nor the total.
 *
 * @param {Array} categories Catalogue from GET /modules.
 * @return {string[]} Widget names, minus anything marked as paid.
 */
export function shippedWidgetNames( categories = [] ) {
	return categories.flatMap( ( category ) =>
		( category.widgets || [] )
			.filter( ( widget ) => ! widget.isPro )
			.map( ( widget ) => widget.name )
	);
}

/**
 * How many *shipping* widgets are enabled.
 *
 * The stored list can still name widgets that have since been removed, so
 * counting it directly would overstate the total. The server drops unknown
 * names on save; this keeps the on-screen count honest before that happens.
 *
 * @param {Set<string>} enabled Enabled names.
 * @param {string[]}    all     Names in the catalogue.
 * @return {number} Count of enabled widgets that still exist.
 */
export function countEnabled( enabled, all ) {
	return all.filter( ( name ) => enabled.has( name ) ).length;
}
