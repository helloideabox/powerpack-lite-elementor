/**
 * Renders one setting from its server-side descriptor.
 *
 * The descriptor says how the value is stored; FIELD_META says how to label it.
 * Nothing here knows about individual settings, so a newly registered field
 * renders without any change to this file.
 */

import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import {
	TextControl,
	TextareaControl,
	SelectControl,
	ComboboxControl,
	Button,
	ExternalLink,
	Notice,
	Spinner,
} from '@wordpress/components';
import { Icon } from '@wordpress/icons';
import { FIELD_META, humanise } from '../panels';
import { LINK_ICONS } from '../icons';
import { verifyCredential } from '../api';
import ToggleSwitch from './ToggleSwitch';

/**
 * A standing caveat attached to a field.
 *
 * Distinct from help text: help explains how to fill the field in, a notice
 * warns about something that will bite later. Google's billing requirement is
 * the case in point — the key saves fine and then fails at request time.
 *
 * Uses the editor's own Notice so this reads like every other warning in
 * wp-admin, rather than a shape invented for this screen. It is not
 * dismissible: the caveat holds for as long as the key does.
 *
 * @param {Object} props          Component props.
 * @param {Node}   props.children Notice content.
 * @return {JSX.Element} The notice.
 */
function FieldNotice( { children } ) {
	return (
		<Notice status="warning" isDismissible={ false }>
			{ children }
		</Notice>
	);
}

/**
 * Is this value "on"? Mirrors the server's is_on().
 *
 * @param {*} value Stored or edited value.
 * @return {boolean} Whether the value counts as enabled.
 */
const isOn = ( value ) => {
	if ( typeof value === 'string' ) {
		return ! [ '', '0', 'off', 'false', 'no', 'disabled' ].includes( value.toLowerCase() );
	}
	return !! value;
};

/**
 * Credential input.
 *
 * The stored value only ever reaches the browser as a mask, so the input starts
 * empty and shows the mask as its placeholder. An untouched field is never
 * included in the payload, which is what stops a save from wiping a key the
 * user never looked at. Clearing is deliberate and explicit.
 */
function SecretField( { fieldKey, meta, stored, edited, isDirty, onChange } ) {
	const [ verifying, setVerifying ] = useState( false );
	const [ verdict, setVerdict ] = useState( null );

	const cleared = edited === null;
	const hasStored = !! stored && ! cleared;

	const verify = async () => {
		setVerifying( true );
		setVerdict( null );
		try {
			const result = await verifyCredential( fieldKey, isDirty ? edited || '' : '' );
			setVerdict( result );
		} catch ( error ) {
			setVerdict( { valid: null, message: error.message } );
		}
		setVerifying( false );
	};

	return (
		<div className="pp-field pp-field--secret">
			<TextControl
				__nextHasNoMarginBottom
				__next40pxDefaultSize
				label={ meta.label ? meta.label() : humanise( fieldKey ) }
				help={ meta.help ? meta.help() : undefined }
				type="password"
				autoComplete="off"
				value={ isDirty && ! cleared ? edited : '' }
				placeholder={
					hasStored ? stored : __( 'Not set', 'powerpack-lite-for-elementor' )
				}
				onChange={ ( value ) => onChange( fieldKey, value ) }
			/>

			<div className="pp-field__actions">
				{ hasStored && (
					<Button
						variant="tertiary"
						isDestructive
						onClick={ () => onChange( fieldKey, null ) }
					>
						{ __( 'Clear', 'powerpack-lite-for-elementor' ) }
					</Button>
				) }
				{ cleared && (
					<span className="pp-field__note">
						{ __( 'Will be cleared on save.', 'powerpack-lite-for-elementor' ) }
					</span>
				) }
				{ meta.verifiable && ( hasStored || isDirty ) && (
					<Button variant="secondary" onClick={ verify } disabled={ verifying }>
						{ verifying ? <Spinner /> : __( 'Verify', 'powerpack-lite-for-elementor' ) }
					</Button>
				) }
				{ verdict && (
					<span
						className={ `pp-verdict pp-verdict--${
							verdict.valid === true
								? 'ok'
								: verdict.valid === false
								? 'bad'
								: 'unknown'
						}` }
					>
						{ verdict.message }
					</span>
				) }
			</div>

			{ meta.notice && <FieldNotice>{ meta.notice() }</FieldNotice> }
		</div>
	);
}

/**
 * Multi-select rendered as switch rows, matching the taxonomy list on the old
 * screen.
 *
 * An empty selection is submitted as an empty array; the server turns that into
 * the 'disabled' sentinel it has always stored.
 */
function ListField( { fieldKey, meta, choices, docs, value, onChange } ) {
	const selected = Array.isArray( value ) ? value : [];
	const all = Object.keys( choices );
	const allOn = all.length > 0 && all.every( ( key ) => selected.includes( key ) );

	const toggle = ( key, checked ) => {
		const next = checked
			? [ ...selected, key ]
			: selected.filter( ( item ) => item !== key );
		onChange( fieldKey, next );
	};

	/*
	 * Choices come from a filterable PHP list, so a third party can add one
	 * this file has never heard of. Descriptions are looked up per key and
	 * simply absent when there is none — which also decides the layout: a list
	 * of names stays a compact row of switches, a list of features gets the
	 * same card the widget library uses.
	 */
	const itemHelp = meta.itemHelp || {};
	const links = docs || {};
	const asCards = all.some( ( key ) => itemHelp[ key ] );

	/*
	 * A section that declares toggleAll puts the button in its own header, next
	 * to the title, the way the widget library does. Nothing is left to draw
	 * here in that case — the label went with it, since the section title was
	 * already saying the same thing.
	 */
	const header = meta.hideLabel ? null : (
		<div className="pp-field__header">
			<span className="pp-field__label">
				{ meta.label ? meta.label() : humanise( fieldKey ) }
			</span>

			{ /*
			  * The same control the widget library and the section headers
			  * use, down to the wording — one action should not be called
			  * three things in three places.
			  */ }
			<button
				type="button"
				className="button toggle-all-widgets"
				onClick={ () => onChange( fieldKey, allOn ? [] : all ) }
			>
				{ allOn
					? __( 'Deactivate All', 'powerpack-lite-for-elementor' )
					: __( 'Activate All', 'powerpack-lite-for-elementor' ) }
			</button>
		</div>
	);

	/*
	 * The widget library's card, reused rather than re-styled: name and
	 * description in the body, docs link and switch in the footer. Extensions
	 * and widgets are the same kind of thing to a user — something the plugin
	 * provides that can be turned off — so they should not look like two
	 * different screens.
	 */
	if ( asCards ) {
		return (
			<div className="pp-field pp-field--list">
				{ header }

				<div className="pp-modules">
					{ all.map( ( key ) => {
						const help = itemHelp[ key ];
						const href = links[ key ];

						return (
							<div className="pp-modules-table-element" key={ key }>
								<div className="pp-modules-table-element-content">
									<div className="pp-modules-table-element-name">
										{ choices[ key ] }
									</div>
									{ help && (
										<p className="pp-settings-description">{ help() }</p>
									) }
								</div>

								<div className="pp-modules-table-element-footer">
									<div className="pp-modules-table-element-footer-links">
										{ href && (
											<a
												href={ href }
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

									<ToggleSwitch
										checked={ selected.includes( key ) }
										label={ choices[ key ] }
										onChange={ ( checked ) => toggle( key, checked ) }
									/>
								</div>
							</div>
						);
					} ) }
				</div>
			</div>
		);
	}

	return (
		<div className="pp-field pp-field--list">
			{ header }

			<div className="pp-toggle-list">
				{ all.map( ( key ) => {
					const id = `pp-field-${ fieldKey }-${ key }`;

					return (
						<div className="pp-toggle-item" key={ key }>
							<ToggleSwitch
								id={ id }
								checked={ selected.includes( key ) }
								label={ choices[ key ] }
								onChange={ ( checked ) => toggle( key, checked ) }
							/>
							<label className="pp-toggle-label" htmlFor={ id }>
								{ choices[ key ] }
							</label>
						</div>
					);
				} ) }
			</div>
		</div>
	);
}

export default function Field( {
	fieldKey,
	descriptor,
	value,
	edited,
	isDirty,
	templates,
	onChange,
} ) {
	const meta = FIELD_META[ fieldKey ] || {};
	const label = meta.label ? meta.label() : humanise( fieldKey );
	const help = meta.help ? meta.help() : undefined;
	const current = isDirty ? edited : value;

	if ( descriptor.secret ) {
		return (
			<SecretField
				fieldKey={ fieldKey }
				meta={ meta }
				stored={ value }
				edited={ edited }
				isDirty={ isDirty }
				onChange={ onChange }
			/>
		);
	}

	/*
	 * A template picker is a searchable select whose options are fetched, not
	 * declared. A plain dropdown is unusable on a site with a few hundred
	 * pages, which is exactly where these are chosen from.
	 */
	if ( meta.templates ) {
		const groups = templates[ meta.templates ];

		if ( ! groups ) {
			return (
				<div className="pp-field">
					<span className="pp-field__label">{ label }</span>
					<Spinner />
				</div>
			);
		}

		const options = [ { label: __( 'Default', 'powerpack-lite-for-elementor' ), value: '' } ];
		const selected = current ? String( current ) : '';
		let picked = null;

		groups.forEach( ( group ) => {
			group.items.forEach( ( item ) => {
				options.push( {
					label: `${ group.label } — ${ item.title }`,
					value: String( item.id ),
				} );

				if ( String( item.id ) === selected ) {
					picked = item;
				}
			} );
		} );

		return (
			<div className="pp-field pp-field--combobox">
				<ComboboxControl
					__nextHasNoMarginBottom
					__next40pxDefaultSize
					label={ label }
					help={ help }
					placeholder={ meta.placeholder ? meta.placeholder() : undefined }
					value={ selected }
					options={ options }
					// Clearing resets to null; the stored shape is an empty
					// string, and null would write the wrong thing.
					onChange={ ( next ) => onChange( fieldKey, next ?? '' ) }
				/>

				{ /*
				 * Straight into the editor for the template that is actually
				 * selected. The URL comes from the server with the template
				 * rather than being assembled here, so it stays right whatever
				 * the site's admin path is.
				 */ }
				{ picked && picked.editUrl && (
					<p className="pp-field__link">
						<ExternalLink href={ picked.editUrl }>
							{ __( 'Edit Template', 'powerpack-lite-for-elementor' ) }
						</ExternalLink>
					</p>
				) }
			</div>
		);
	}

	if ( descriptor.type === 'list' ) {
		return (
			<ListField
				fieldKey={ fieldKey }
				meta={ meta }
				choices={ descriptor.choices || {} }
				docs={ descriptor.docs }
				value={ current }
				onChange={ onChange }
			/>
		);
	}

	/*
	 * A stored enum can still be presented as a switch when it only has two
	 * meaningful values. asSwitch names the strings to write, so the option
	 * keeps the shape its readers expect.
	 */
	if ( descriptor.type === 'on_off' || descriptor.type === 'boolean' || meta.asSwitch ) {
		const id = `pp-field-${ fieldKey }`;

		const writeToggle = ( checked ) => {
			if ( meta.asSwitch ) {
				return checked ? meta.asSwitch.on : meta.asSwitch.off;
			}

			return descriptor.type === 'on_off' ? ( checked ? 'on' : 'off' ) : checked;
		};

		return (
			<div className="pp-field pp-field--toggle">
				<div className="pp-toggle-item">
					<ToggleSwitch
						id={ id }
						checked={ isOn( current ) }
						label={ label }
						onChange={ ( checked ) => onChange( fieldKey, writeToggle( checked ) ) }
					/>
					<div className="pp-toggle-item-content">
						<label className="pp-toggle-label" htmlFor={ id }>
							{ label }
						</label>
						{ help && <p className="pp-settings-description">{ help }</p> }
						{ meta.notice && <FieldNotice>{ meta.notice() }</FieldNotice> }
					</div>
				</div>
			</div>
		);
	}

	/*
	 * Choices declared in FIELD_META imply a select, whatever the stored type.
	 * The responsive breakpoint is registered as a string because that is what
	 * it stores, but it is a fixed set of options to the user.
	 */
	if ( descriptor.type === 'enum' || meta.choices ) {
		const source = meta.choices ? meta.choices() : descriptor.choices || {};
		const options = Object.keys( source ).map( ( key ) => ( {
			label: source[ key ],
			value: key,
		} ) );

		const value = current === undefined || current === null ? '' : String( current );

		// Long lists get a filter. The map languages run to well over a
		// hundred entries, which is not a dropdown anyone wants to scroll.
		if ( meta.searchable ) {
			return (
				<div className="pp-field pp-field--combobox">
					<ComboboxControl
						__nextHasNoMarginBottom
						__next40pxDefaultSize
						label={ label }
						help={ help }
						placeholder={ meta.placeholder ? meta.placeholder() : undefined }
						value={ value }
						options={ options }
						onChange={ ( next ) => onChange( fieldKey, next ?? '' ) }
					/>

					{ meta.notice && <FieldNotice>{ meta.notice() }</FieldNotice> }
				</div>
			);
		}

		return (
			<div className="pp-field">
				<SelectControl
					__nextHasNoMarginBottom
					__next40pxDefaultSize
					label={ label }
					help={ help }
					value={ value }
					options={ options }
					onChange={ ( next ) => onChange( fieldKey, next ) }
				/>

				{ meta.notice && <FieldNotice>{ meta.notice() }</FieldNotice> }
			</div>
		);
	}

	if ( descriptor.type === 'textarea' ) {
		return (
			<div className="pp-field">
				<TextareaControl
					__nextHasNoMarginBottom
					label={ label }
					help={ help }
					// Two, against the control's default of four: these hold a
					// sentence or so, and the field is full width now.
					rows={ meta.rows || 2 }
					value={ current || '' }
					onChange={ ( next ) => onChange( fieldKey, next ) }
				/>
			</div>
		);
	}

	return (
		<div className="pp-field">
			<TextControl
				__nextHasNoMarginBottom
				__next40pxDefaultSize
				label={ label }
				help={ help }
				placeholder={ meta.placeholder ? meta.placeholder() : undefined }
				type={ descriptor.type === 'url' ? 'url' : 'text' }
				value={ current || '' }
				onChange={ ( next ) => onChange( fieldKey, next ) }
			/>

			{ meta.notice && <FieldNotice>{ meta.notice() }</FieldNotice> }
		</div>
	);
}
