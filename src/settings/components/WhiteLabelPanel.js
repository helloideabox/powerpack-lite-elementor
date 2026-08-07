/**
 * The White Label panel, previewed.
 *
 * The settings listed here are the paid edition's, field for field, so that
 * what an agency sees on this screen is what they would get. Nothing on it is
 * operable — see LockedPanel for why the rows are drawn rather than disabled.
 */

import { __ } from '@wordpress/i18n';
import LockedPanel from './LockedPanel';

export default function WhiteLabelPanel() {
	return (
		<LockedPanel
			title={ __( 'White Label', 'powerpack-lite-for-elementor' ) }
			campaign="white-label"
			headline={ __( 'Ship this plugin under your own name', 'powerpack-lite-for-elementor' ) }
			description={ __(
				'White labelling renames the plugin everywhere a client would see it: the plugins list, the admin menu, the editor panel. It also hides the bits that point back to us. Built for agencies handing a site over.',
				'powerpack-lite-for-elementor'
			) }
			points={ [
				__( 'Your name, author and description on the plugins screen', 'powerpack-lite-for-elementor' ),
				__( 'Your own support and documentation links', 'powerpack-lite-for-elementor' ),
				__( 'Hide the plugin from anyone but you', 'powerpack-lite-for-elementor' ),
			] }
			sections={ [
				{
					/*
					 * Paired exactly as the paid edition pairs them: name beside
					 * short name, author beside URL, and the description and menu
					 * label spanning the full width. Someone comparing the two
					 * screens should be looking at the same layout.
					 */
					title: __( 'Branding', 'powerpack-lite-for-elementor' ),
					rows: [
						[
							{
								label: __( 'Plugin Name', 'powerpack-lite-for-elementor' ),
								text: __(
									'Replaces "PowerPack" on the plugins screen.',
									'powerpack-lite-for-elementor'
								),
								type: 'text',
								value: __( 'Acme Elements', 'powerpack-lite-for-elementor' ),
							},
							{
								label: __( 'Plugin Short Name', 'powerpack-lite-for-elementor' ),
								text: __(
									'Used where space is tight, such as the widget panel.',
									'powerpack-lite-for-elementor'
								),
								type: 'text',
								value: __( 'Acme', 'powerpack-lite-for-elementor' ),
							},
						],
						{
							label: __( 'Plugin Description', 'powerpack-lite-for-elementor' ),
							text: __(
								'The line beneath the name on the plugins screen.',
								'powerpack-lite-for-elementor'
							),
							type: 'text',
							value: __( 'Extra widgets for the site builder.', 'powerpack-lite-for-elementor' ),
						},
						[
							{
								label: __( 'Developer / Agency Name', 'powerpack-lite-for-elementor' ),
								type: 'text',
								value: __( 'Acme Studio', 'powerpack-lite-for-elementor' ),
							},
							{
								label: __( 'Developer / Agency URL', 'powerpack-lite-for-elementor' ),
								type: 'text',
								value: 'https://acme.example',
							},
						],
						{
							label: __( 'Admin Menu Label', 'powerpack-lite-for-elementor' ),
							text: __(
								'What this settings screen is called in the menu.',
								'powerpack-lite-for-elementor'
							),
							type: 'text',
							value: __( 'Acme', 'powerpack-lite-for-elementor' ),
						},
					],
				},
				{
					title: __( 'Links', 'powerpack-lite-for-elementor' ),
					rows: [
						[
							{
								label: __( 'Support Link', 'powerpack-lite-for-elementor' ),
								text: __(
									'Sends clients to your help desk instead of ours.',
									'powerpack-lite-for-elementor'
								),
								type: 'text',
								value: 'https://acme.example/support',
							},
							{
								label: __( 'Documentation Link', 'powerpack-lite-for-elementor' ),
								type: 'text',
								value: 'https://acme.example/docs',
							},
						],
					],
				},
				{
					title: __( 'Visibility', 'powerpack-lite-for-elementor' ),
					rows: [
						{
							label: __( 'Hide Logo', 'powerpack-lite-for-elementor' ),
							text: __( 'Removes our mark from this screen.', 'powerpack-lite-for-elementor' ),
						},
						{ label: __( 'Hide License Key Link', 'powerpack-lite-for-elementor' ) },
						{ label: __( 'Hide Demo Links', 'powerpack-lite-for-elementor' ) },
						{ label: __( 'Hide Docs Links', 'powerpack-lite-for-elementor' ) },
						{ label: __( 'Hide Support Link', 'powerpack-lite-for-elementor' ) },
						{ label: __( 'Hide Integration Tab', 'powerpack-lite-for-elementor' ) },
						{
							label: __( 'Hide White Label Settings', 'powerpack-lite-for-elementor' ),
							text: __( 'Locks this panel away once the branding is set.', 'powerpack-lite-for-elementor' ),
						},
						{
							label: __( 'Hide Plugin', 'powerpack-lite-for-elementor' ),
							text: __( 'Keeps the plugin off the list for everyone but you.', 'powerpack-lite-for-elementor' ),
						},
					],
				},
			] }
		/>
	);
}
