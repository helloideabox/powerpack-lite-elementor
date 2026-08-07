/**
 * The License panel.
 *
 * This edition has no license to enter, and the first thing anyone opening this
 * panel needs is to be told that plainly — otherwise its presence in the nav
 * reads as a step they have missed. The notice says so before anything else on
 * the screen.
 *
 * What is left is the paid edition's key field, previewed the same way the
 * White Label panel previews its settings, so that someone who has bought Pro
 * can see where the key goes and what activating it does.
 */

import { __ } from '@wordpress/i18n';
import { createInterpolateElement } from '@wordpress/element';
import LockedPanel from './LockedPanel';

const ACCOUNT_URL = 'https://powerpackelements.com/my-account/';

export default function LicensePanel() {
	return (
		<LockedPanel
			title={ __( 'License', 'powerpack-lite-for-elementor' ) }
			campaign="license"
			notice={
				<div className="notice notice-success inline pp-notice">
					<p>
						<strong>
							{ __(
								'PowerPack Lite does not need a license key.',
								'powerpack-lite-for-elementor'
							) }
						</strong>{ ' ' }
						{ __(
							'It is free, and updates arrive through WordPress like any other plugin from the directory. Nothing on this panel is required for the widgets you already have.',
							'powerpack-lite-for-elementor'
						) }
					</p>
				</div>
			}
			headline={ __( 'A license key comes with Pro', 'powerpack-lite-for-elementor' ) }
			description={ __(
				'Activating a key on this panel is what connects a Pro install to your account: it turns on automatic updates for the paid widgets and identifies you when you open a support ticket.',
				'powerpack-lite-for-elementor'
			) }
			points={ [
				__( 'Automatic updates for every Pro widget and extension', 'powerpack-lite-for-elementor' ),
				__( 'Direct support from the team that builds it', 'powerpack-lite-for-elementor' ),
				__( 'One key covers the sites your plan allows', 'powerpack-lite-for-elementor' ),
			] }
			sections={ [
				{
					title: __( 'License Key', 'powerpack-lite-for-elementor' ),
					rows: [
						{
							label: __( 'License Key', 'powerpack-lite-for-elementor' ),
							text: __(
								'Found in your account after purchase, and pasted here once per site.',
								'powerpack-lite-for-elementor'
							),
							type: 'text',
							value: '••••••••••••••••••••••••••••3f7a',
						},
						{
							label: __( 'Status', 'powerpack-lite-for-elementor' ),
							text: __(
								'Shows whether the key is active, expired, or has run out of sites.',
								'powerpack-lite-for-elementor'
							),
							type: 'select',
							value: __( 'Active', 'powerpack-lite-for-elementor' ),
						},
					],
				},
			] }
			footnote={ createInterpolateElement(
				__(
					'Already bought Pro? Download it from <account>your account</account> and activate it — the key goes on this panel there.',
					'powerpack-lite-for-elementor'
				),
				{
					account: (
						// eslint-disable-next-line jsx-a11y/anchor-has-content
						<a
							href={ ACCOUNT_URL }
							target="_blank"
							rel="noopener noreferrer"
						/>
					),
				}
			) }
		/>
	);
}
