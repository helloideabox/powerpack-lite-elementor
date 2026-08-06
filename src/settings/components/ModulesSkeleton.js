/**
 * Placeholder for the widget library while the catalogue is fetched.
 *
 * It reuses the real layout classes rather than approximating them, so the
 * page does not reflow when the data lands — the category list, the toolbar
 * and the card grid all occupy the space they are about to occupy for real.
 *
 * The bones are decorative; the status message is what assistive technology
 * gets, so the whole tree is hidden from it apart from that.
 */

import { __ } from '@wordpress/i18n';
import PanelHead from './PanelHead';

/*
 * Uneven widths, so the list reads as text waiting to arrive rather than a
 * bar chart. Fixed rather than random: a placeholder that reshuffles on every
 * render draws the eye to the wrong thing.
 */
const NAV_WIDTHS = [ 64, 82, 58, 74, 90, 66, 78, 60 ];
const CARD_WIDTHS = [ 72, 88, 60, 80, 66, 92, 74, 58, 84, 68, 90, 62 ];

/**
 * One placeholder card, shaped like a widget tile.
 *
 * @param {Object} props       Component props.
 * @param {number} props.width Name width, in pixels.
 * @return {JSX.Element} The card.
 */
function CardBone( { width } ) {
	return (
		<div className="pp-modules-table-element">
			<div className="pp-modules-table-element-content">
				<div className="pp-modules-table-element-header">
					<span className="pp-skeleton pp-skeleton--icon" />
				</div>
				<div className="pp-modules-table-element-name">
					<span className="pp-skeleton pp-skeleton--text" style={ { width } } />
				</div>
			</div>

			<div className="pp-modules-table-element-footer">
				<div className="pp-modules-table-element-footer-links">
					<span className="pp-skeleton pp-skeleton--link" />
					<span className="pp-skeleton pp-skeleton--link" />
				</div>
				<span className="pp-skeleton pp-skeleton--switch" />
			</div>
		</div>
	);
}

/**
 * One placeholder section, shaped like a widget category.
 *
 * @param {Object} props       Component props.
 * @param {number} props.cards How many cards to draw.
 * @param {number} props.seed  Offset into the width table.
 * @return {JSX.Element} The section.
 */
function SectionBone( { cards, seed } ) {
	return (
		<div className="pp-settings-section">
			<div className="pp-settings-section-header">
				<span className="pp-skeleton pp-skeleton--heading" />
				<span className="pp-skeleton pp-skeleton--button" />
			</div>

			<div className="pp-settings-section-content">
				<div className="pp-modules">
					{ Array.from( { length: cards } , ( ignored, index ) => (
						<CardBone
							key={ index }
							width={ CARD_WIDTHS[ ( index + seed ) % CARD_WIDTHS.length ] }
						/>
					) ) }
				</div>
			</div>
		</div>
	);
}

export default function ModulesSkeleton() {
	return (
		<>
			<PanelHead title={ __( 'Elements', 'powerpack-lite-for-elementor' ) } />

			<div className="pp-panel-body pp-panel-body--split pp-is-loading" aria-busy="true">
				<p className="screen-reader-text" role="status">
					{ __( 'Loading widgets…', 'powerpack-lite-for-elementor' ) }
				</p>

				<nav className="pp-category-nav" aria-hidden="true">
					{ NAV_WIDTHS.map( ( width, index ) => (
						<div className="pp-category-nav-item" key={ index }>
							<span className="pp-skeleton pp-skeleton--text" style={ { width } } />
							<span className="pp-skeleton pp-skeleton--count" />
						</div>
					) ) }
				</nav>

				<div className="pp-panel-main" aria-hidden="true">
					<div className="pp-toolbar">
						<span className="pp-skeleton pp-skeleton--search" />
						<span className="pp-skeleton pp-skeleton--seg" />
						<span className="pp-skeleton pp-skeleton--button" />
						<span className="pp-skeleton pp-skeleton--button" />
					</div>

					<SectionBone cards={ 6 } seed={ 0 } />
					<SectionBone cards={ 3 } seed={ 5 } />
				</div>
			</div>
		</>
	);
}
