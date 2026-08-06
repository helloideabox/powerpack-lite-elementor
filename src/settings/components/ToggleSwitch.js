/**
 * A bare switch.
 *
 * FormToggle rather than ToggleControl: this sits in the widget card footer
 * where the card title is already the visible label, so the control must not
 * render one of its own. The accessible name is supplied via aria-label.
 */

import { FormToggle } from '@wordpress/components';

export default function ToggleSwitch( { checked, onChange, label, id, disabled = false } ) {
	return (
		<FormToggle
			id={ id }
			checked={ checked }
			disabled={ disabled }
			aria-label={ id ? undefined : label }
			onChange={ ( event ) => onChange( event.target.checked ) }
		/>
	);
}
