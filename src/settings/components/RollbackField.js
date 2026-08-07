/**
 * Reinstalling an earlier release.
 *
 * The one control on this screen that deliberately leaves it. Replacing plugin
 * files is WordPress's own job: it renders its progress as a page, and on a host
 * whose filesystem is not directly writable it has to ask for FTP credentials
 * first. Neither fits behind an XHR — and the plugin is deleting the very
 * JavaScript running the request, so a real form submit is also the honest
 * shape for what is about to happen.
 *
 * Hence a plain <form> posting to admin-post.php, with the nonce already in the
 * action URL, rather than anything this app manages.
 *
 * The confirm step is in the panel rather than a window.confirm: this is the
 * last screen before something irreversible, and it is worth naming the version
 * and what it will and will not change.
 */

import { __, sprintf } from '@wordpress/i18n';
import { useState } from '@wordpress/element';
import { SelectControl, Button, Notice } from '@wordpress/components';

const boot = window.ppSettingsBootstrap || {};

export default function RollbackField() {
	const rollback = boot.rollback || {};
	const versions = rollback.versions || [];

	const [ version, setVersion ] = useState( versions[ 0 ] || '' );
	const [ confirming, setConfirming ] = useState( false );

	// Nothing to offer: no capability, file changes disabled, or the directory
	// had no earlier release. Saying so is better than an empty select.
	if ( ! versions.length ) {
		return (
			<p className="pp-settings-description">
				{ __(
					'Reinstalling an earlier version is not available on this site.',
					'powerpack-lite-for-elementor'
				) }
			</p>
		);
	}

	return (
		<form className="pp-rollback" method="post" action={ rollback.url }>
			<input type="hidden" name="action" value={ rollback.action } />
			<input type="hidden" name="_wpnonce" value={ rollback.nonce } />
			<input type="hidden" name="version" value={ version } />

			{ /*
			  * The installed version is stated under the row rather than as the
			  * select's help text. A control's help sits inside its box, which
			  * made the box taller than the select and left the button — aligned
			  * to the bottom of the row — hanging below the select it belongs to.
			  */ }
			<div className="pp-rollback-picker">
				<SelectControl
					__nextHasNoMarginBottom
					__next40pxDefaultSize
					label={ __( 'Version', 'powerpack-lite-for-elementor' ) }
					value={ version }
					options={ versions.map( ( item ) => ( { label: item, value: item } ) ) }
					onChange={ ( next ) => {
						setVersion( next );
						setConfirming( false );
					} }
				/>

				{ ! confirming && (
					<Button
						__next40pxDefaultSize
						variant="secondary"
						onClick={ () => setConfirming( true ) }
					>
						{ __( 'Reinstall this version', 'powerpack-lite-for-elementor' ) }
					</Button>
				) }
			</div>

			<p className="pp-settings-description pp-rollback-current">
				{ sprintf(
					/* translators: %s: version number currently installed. */
					__( 'Version %s is installed.', 'powerpack-lite-for-elementor' ),
					rollback.current
				) }
			</p>

			{ confirming && (
				<Notice status="warning" isDismissible={ false }>
					<p>
						{ sprintf(
							/* translators: 1: version to install, 2: version installed now. */
							__(
								'This will replace your files with version %1$s. You can get %2$s back afterwards by installing it again from WordPress.org.',
								'powerpack-lite-for-elementor'
							),
							version,
							rollback.current
						) }
					</p>
					<p>
						{ __(
							'Your settings and content stay where they are, but they were written by the newer version, so an older one might not understand all of them. Back up the site first.',
							'powerpack-lite-for-elementor'
						) }
					</p>

					<div className="pp-rollback-confirm">
						<Button variant="primary" isDestructive type="submit">
							{ sprintf(
								/* translators: %s: version number. */
								__( 'Reinstall version %s', 'powerpack-lite-for-elementor' ),
								version
							) }
						</Button>
						<Button variant="tertiary" onClick={ () => setConfirming( false ) }>
							{ __( 'Cancel', 'powerpack-lite-for-elementor' ) }
						</Button>
					</div>
				</Notice>
			) }
		</form>
	);
}
