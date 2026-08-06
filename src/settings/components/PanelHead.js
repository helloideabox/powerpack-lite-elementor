/**
 * The title at the top of every panel.
 *
 * Deliberately unstyled beyond the type: it sits directly on the canvas rather
 * than in a banner, so the panels below it carry all the surface.
 */

export default function PanelHead( { title, aside, constrained = false } ) {
	return (
		<div className={ `pp-panel-head${ constrained ? ' is-constrained' : '' }` }>
			<h2 className="pp-panel-title">{ title }</h2>
			{ aside && <div className="pp-panel-head-aside">{ aside }</div> }
		</div>
	);
}
