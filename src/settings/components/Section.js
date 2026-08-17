/**
 * A settings section, collapsible by its header.
 *
 * The heading wraps the disclosure button rather than the other way round: a
 * button may only contain phrasing content, so putting an h3 inside one would
 * be invalid, while a heading containing a button is the standard disclosure
 * shape and keeps the section in the document outline.
 *
 * Any actions belong beside the button, not inside it — nesting a button in a
 * button is invalid and would swallow the click.
 */

import { useState } from '@wordpress/element';

/*
 * Ids for the disclosure relationship. A counter rather than a hook so this
 * stays independent of which @wordpress packages the site has: the value only
 * has to be unique within the page, and one settings screen renders once.
 */
let sequence = 0;

export default function Section( {
	title,
	count,
	description,
	actions,
	children,
	sectionRef,
	slug,
	isMarked = false,
	defaultOpen = true,
} ) {
	const [ open, setOpen ] = useState( defaultOpen );
	const [ regionId ] = useState( () => `pp-section-${ ++sequence }` );

	// Set when another screen has just sent the reader here, so the section
	// they were promised is the one they see rather than whatever the scroll
	// happened to land on.
	const mark = isMarked ? ' is-marked' : '';

	// Sections without a title have nothing to click, so they never collapse.
	if ( ! title ) {
		return (
			<div
				className={ `pp-settings-section${ mark }` }
				ref={ sectionRef }
				data-pp-slug={ slug }
			>
				<div className="pp-settings-section-content">{ children }</div>
			</div>
		);
	}

	return (
		<div
			className={ `pp-settings-section${ open ? '' : ' is-collapsed' }${ mark }` }
			ref={ sectionRef }
			data-pp-slug={ slug }
		>
			<div className="pp-settings-section-header">
				<h3 className="pp-settings-section-title">
					<button
						type="button"
						className="pp-section-toggle"
						aria-expanded={ open }

						// Names the region the button opens, so a screen reader
						// can be taken to it rather than left to hunt.
						aria-controls={ regionId }
						onClick={ () => setOpen( ( previous ) => ! previous ) }
					>
						<span
							className="dashicons dashicons-arrow-down-alt2 pp-section-caret"
							aria-hidden="true"
						/>
						<span className="pp-section-title-text">{ title }</span>
						{ count && <span className="pp-category-count">{ count }</span> }
					</button>
				</h3>

				{ actions && <div className="pp-section-actions">{ actions }</div> }
				{ description && <p className="pp-section-description">{ description }</p> }
			</div>

			{ open && (
				<div id={ regionId } className="pp-settings-section-content">
					{ children }
				</div>
			) }
		</div>
	);
}
