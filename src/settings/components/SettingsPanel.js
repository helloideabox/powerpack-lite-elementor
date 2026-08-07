/**
 * Generic panel: renders a group's fields in declared sections.
 *
 * Every section lays its fields out the same way — as rows. A row holds either
 * field keys, which sit side by side, or titled groups, which render as
 * bordered cards. One arrangement, one pair of classes, whatever the panel.
 *
 * Any field the server exposes for this group that panels.js does not place in
 * a section is appended in an "Other" section, so a newly registered setting is
 * visible without touching this file.
 */

import { __ } from '@wordpress/i18n';
import Field from './Field';
import PanelHead from './PanelHead';
import Section from './Section';
import { BRAND_ICONS } from '../icons';
import { FIELD_META } from '../panels';

/**
 * Flatten a row definition to the field keys it contains.
 *
 * @param {string|Object|Array} row Row definition.
 * @return {string[]} Field keys.
 */
const rowFields = ( row ) =>
	( Array.isArray( row ) ? row : [ row ] ).flatMap( ( entry ) =>
		'string' === typeof entry ? [ entry ] : entry.fields || []
	);

export default function SettingsPanel( {
	panel,
	fields,
	settings,
	changes,
	templates,
	onChange,
} ) {
	const inGroup = Object.keys( fields ).filter(
		( key ) => fields[ key ].group === panel.group
	);

	const placed = new Set();

	panel.sections.forEach( ( section ) =>
		( section.rows || section.fields || [] ).forEach( ( row ) =>
			rowFields( row ).forEach( ( key ) => placed.add( key ) )
		)
	);

	const orphans = inGroup.filter( ( key ) => ! placed.has( key ) );
	const sections = [ ...panel.sections ];

	if ( orphans.length ) {
		sections.push( {
			title: () => __( 'Other', 'powerpack-lite-for-elementor' ),
			rows: orphans,
		} );
	}

	/*
	 * Conditions read edited values, not stored ones, so a field appears the
	 * moment its prerequisite is switched on rather than after a save.
	 */
	const values = { ...settings, ...changes };

	/**
	 * Activate / Deactivate All for a list field, drawn in the section header.
	 *
	 * Same control the widget library puts beside a category title — same
	 * class, same wording — so the two screens do not offer the same action
	 * under two names in two places.
	 *
	 * @param {string} key Field key.
	 * @return {JSX.Element|null} The button, or null when the field is hidden.
	 */
	const renderToggleAll = ( key ) => {
		const descriptor = fields[ key ];

		if ( ! descriptor ) {
			return null;
		}

		const all = Object.keys( descriptor.choices || {} );
		const current = values[ key ];
		const selected = Array.isArray( current ) ? current : [];
		const allOn = all.length > 0 && all.every( ( name ) => selected.includes( name ) );

		return (
			<button
				type="button"
				className="button toggle-all-widgets"
				onClick={ () => onChange( key, allOn ? [] : all ) }
			>
				{ allOn
					? __( 'Deactivate All', 'powerpack-lite-for-elementor' )
					: __( 'Activate All', 'powerpack-lite-for-elementor' ) }
			</button>
		);
	};

	const renderField = ( key ) => {
		const descriptor = fields[ key ];

		// The server omits fields this user may not touch.
		if ( ! descriptor ) {
			return null;
		}

		const meta = FIELD_META[ key ];

		if ( meta && meta.condition && ! meta.condition( values ) ) {
			return null;
		}

		return (
			<Field
				key={ key }
				fieldKey={ key }
				descriptor={ descriptor }
				value={ settings[ key ] }
				edited={ changes[ key ] }
				isDirty={ Object.prototype.hasOwnProperty.call( changes, key ) }
				templates={ templates }
				onChange={ onChange }
			/>
		);
	};

	/**
	 * Render one entry within a row.
	 *
	 * A string is a field. An object is a titled group of fields, drawn as a
	 * card — which is what the Integration panel uses to pair Facebook with
	 * Google, or reCAPTCHA v2 with v3.
	 *
	 * @param {string|Object} entry Field key or group.
	 * @param {number}        index React key.
	 * @return {JSX.Element|null} Rendered entry, or null when it is empty.
	 */
	const renderEntry = ( entry, index ) => {
		if ( 'string' === typeof entry ) {
			return renderField( entry );
		}

		const grouped = ( entry.fields || [] ).map( renderField ).filter( Boolean );

		if ( ! grouped.length ) {
			return null;
		}

		return (
			<div className="pp-field-group" key={ index }>
				{ entry.title && (
					<h4 className="pp-field-group-title">
						{ entry.icon && BRAND_ICONS[ entry.icon ] && (
							<span className={ `pp-field-group-icon is-${ entry.icon }` }>
								{ BRAND_ICONS[ entry.icon ] }
							</span>
						) }
						{ entry.title() }
					</h4>
				) }
				{ grouped }
			</div>
		);
	};

	return (
		<>
			<PanelHead title={ panel.title() } constrained />

			<div className="pp-panel-body">
				{ sections.map( ( section, index ) => {
					/*
					 * A section may name a component instead of listing fields,
					 * for the few things that are not a stored setting. The
					 * version rollback is the case in point: it posts out of
					 * this app entirely rather than into the settings payload.
					 */
					if ( section.component ) {
						const Custom = section.component;

						return (
							<Section
								key={ index }
								title={ section.title ? section.title() : null }
								description={ section.description ? section.description() : null }
							>
								<Custom />
							</Section>
						);
					}

					const rows = ( section.rows || section.fields || [] )
						.map( ( row, rowIndex ) => {
							// A bare entry takes the full width; an array shares it.
							const entries = Array.isArray( row ) ? row : [ row ];
							const rendered = entries.map( renderEntry ).filter( Boolean );

							if ( ! rendered.length ) {
								return null;
							}

							return (
								<div className="pp-field-row" key={ rowIndex }>
									{ rendered }
								</div>
							);
						} )
						.filter( Boolean );

					if ( ! rows.length ) {
						return null;
					}

					return (
						<Section
							key={ index }
							title={ section.title ? section.title() : null }
							description={ section.description ? section.description() : null }
							actions={
								section.toggleAll
									? renderToggleAll( section.toggleAll )
									: undefined
							}
						>
							{ rows }
						</Section>
					);
				} ) }
			</div>
		</>
	);
}
