/**
 * Entry point for the PowerPack settings screen.
 */

import { createRoot } from '@wordpress/element';
import App from './components/App';
import './style.scss';

const mount = () => {
	const root = document.getElementById( 'pp-settings-root' );

	if ( ! root ) {
		return;
	}

	createRoot( root ).render( <App /> );
};

if ( document.readyState === 'loading' ) {
	document.addEventListener( 'DOMContentLoaded', mount );
} else {
	mount();
}
