/**
 * A panel for settings this edition does not have.
 *
 * The point of showing them at all is that someone deciding whether to upgrade
 * should be able to see what they would get, laid out the way they would
 * actually meet it, rather than reading a feature list on a sales page.
 *
 * Two rules follow from that, and they pull in opposite directions.
 *
 * Nothing here may be operable. The rows are drawn from plain elements — no
 * input, no button, no href — so there is nothing to focus, nothing to click
 * and nothing to submit. A disabled <input> would have been the quicker way to
 * get the look, but a disabled control is still a control: it lands in the
 * accessibility tree as a form field, and a screen reader announces a Plugin
 * Name text box that does not exist.
 *
 * But the reader still has to learn what is on offer. So the labels and the
 * descriptions are ordinary readable text, and only the decorative part — the
 * drawing of a switch or a text box — is hidden from assistive technology. The
 * upgrade call sits at the top, before the preview, because it is the only
 * thing on the panel anyone can act on.
 */

import { __ } from '@wordpress/i18n';
import { Icon, lock } from '@wordpress/icons';
import PanelHead from './PanelHead';
import Section from './Section';

const boot = window.ppSettingsBootstrap || {};

const UPGRADE_URL = boot.upgradeUrl || 'https://powerpackelements.com/upgrade/';

/**
 * The drawing of a control. Decorative, and hidden from assistive technology.
 *
 * @param {Object} props      Component props.
 * @param {string} props.type 'toggle', 'text' or 'select'.
 * @param {string} props.text Sample value shown inside a text or select.
 * @return {JSX.Element} The preview.
 */
function Ghost( { type, text } ) {
	if ( 'toggle' === type ) {
		return (
			<span className="pp-locked-ghost is-toggle" aria-hidden="true">
				<span className="pp-locked-ghost-knob" />
			</span>
		);
	}

	return (
		<span className={ `pp-locked-ghost is-${ type }` } aria-hidden="true">
			{ text ? <span className="pp-locked-ghost-text">{ text }</span> : null }
		</span>
	);
}

/**
 * One previewed setting.
 *
 * Text and select rows stack their control under the label, the way the real
 * fields do, so that two of them sit side by side in a row without the labels
 * and boxes drifting apart. Toggles keep the label and switch on one line.
 *
 * @param {Object} props       Component props.
 * @param {string} props.label What the setting is called in the paid edition.
 * @param {string} props.text  What it does.
 * @param {string} props.type  Which control it uses.
 * @param {string} props.value Sample value.
 * @return {JSX.Element} The row.
 */
function LockedRow( { label, text, type = 'toggle', value } ) {
	return (
		<div className={ `pp-locked-row is-${ type }` }>
			<span className="pp-locked-row-body">
				<span className="pp-locked-row-label">{ label }</span>
				{ text && <span className="pp-locked-row-text">{ text }</span> }
			</span>
			<Ghost type={ type } text={ value } />
		</div>
	);
}

/**
 * A section's rows, laid out the way the paid edition lays out the real ones.
 *
 * A section may describe its rows as a flat list, or as an array where a nested
 * array means "these sit side by side" — the same shape PANELS uses for the
 * fields it renders for real. Pairing is what makes Plugin Name and Plugin
 * Short Name read as two halves of one decision rather than two settings.
 *
 * @param {Object} props      Component props.
 * @param {Array}  props.rows Rows, or arrays of rows to pair.
 * @return {JSX.Element} The rows.
 */
function LockedRows( { rows } ) {
	return (
		<div className="pp-locked-rows">
			{ rows.map( ( row ) =>
				Array.isArray( row ) ? (
					<div className="pp-locked-pair" key={ row.map( ( item ) => item.label ).join( '|' ) }>
						{ row.map( ( item ) => (
							<LockedRow key={ item.label } { ...item } />
						) ) }
					</div>
				) : (
					<LockedRow key={ row.label } { ...row } />
				)
			) }
		</div>
	);
}

/**
 * @param {Object} props             Component props.
 * @param {string} props.title       Panel title.
 * @param {Node}   props.notice      Optional block shown above the offer.
 * @param {string} props.headline    What the feature is, in one line.
 * @param {string} props.description Why it is worth having.
 * @param {Array}  props.points      Short list of what the upgrade includes.
 * @param {Array}  props.sections    [ { title, rows: [ LockedRow props ] } ].
 * @param {string} props.campaign    UTM campaign for the upgrade link.
 * @param {string} props.footnote    Line closing the panel.
 * @return {JSX.Element} The panel.
 */
export default function LockedPanel( {
	title,
	notice,
	headline,
	description,
	points = [],
	sections = [],
	campaign = 'settings',
	footnote,
} ) {
	const href = `${ UPGRADE_URL }?utm_source=lite-settings&utm_medium=wp-dash&utm_campaign=${ campaign }`;

	return (
		<>
			<PanelHead
				title={ title }
				constrained
				aside={
					<span className="pp-locked-badge">
						<Icon icon={ lock } size={ 14 } />
						{ __( 'Pro', 'powerpack-lite-for-elementor' ) }
					</span>
				}
			/>

			<div className="pp-panel-body pp-locked">
				{ notice }

				<div className="pp-locked-offer">
					<div className="pp-locked-offer-body">
						<h3 className="pp-locked-offer-title">{ headline }</h3>
						<p className="pp-locked-offer-text">{ description }</p>

						{ points.length > 0 && (
							<ul className="pp-locked-offer-points">
								{ points.map( ( point ) => (
									<li key={ point }>{ point }</li>
								) ) }
							</ul>
						) }
					</div>

					<a
						className="pp-locked-offer-button"
						href={ href }
						target="_blank"
						rel="noopener noreferrer"
					>
						{ __( 'Upgrade to Pro', 'powerpack-lite-for-elementor' ) }
					</a>
				</div>

				{ sections.map( ( section ) => (
					<Section
						key={ section.title }
						title={ section.title }
						description={ section.description }
					>
						<LockedRows rows={ section.rows } />
					</Section>
				) ) }

				<p className="pp-locked-footnote">
					{ footnote ||
						__(
							'This is a preview. These settings are part of PowerPack Pro and are not editable here.',
							'powerpack-lite-for-elementor'
						) }
				</p>
			</div>
		</>
	);
}
