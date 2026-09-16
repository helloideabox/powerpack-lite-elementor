(function ($) {
	$( window ).on( 'elementor/frontend/init', () => {
		class ButtonsWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						tooltipButton: '.pp-button[data-tooltip]',
						fauxButton: '.pp-button[role="button"]',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$tooltipButton: this.$element.find( selectors.tooltipButton ),
					$fauxButton: this.$element.find( selectors.fauxButton ),
				};
			}

			bindEvents() {
				this.initTooltips();
				this.initKeyboardActivation();
				this.initTooltipEscape();
			}

			unbindEvents() {
				$( document ).off( 'keydown.ppButtons' + this.getID() );
			}

			initTooltips() {
				const $id   = this.getID(),
					ppclass = 'pp-tooltip' + ' pp-tooltip-' + $id;

				this.elements.$tooltipButton.each( function() {
					const $button   = $( this );
					const ttOptions = $button.data( 'tooltip' );

					if ( ! ttOptions ) {
						return;
					}

					let ttipPosition = ttOptions.position;

					const breakpoints = elementorFrontend.config.responsive.activeBreakpoints;

					let shouldSkip = false;
					Object.keys( breakpoints ).forEach( breakpointName => {
						if ( shouldSkip ) {
							return;
						}

						if ( window.innerWidth <= breakpoints[ breakpointName ].value ) {
							if ( undefined !== ttOptions[ 'position_' + breakpointName ] ) {
								ttipPosition = ttOptions[ 'position_' + breakpointName ];
							}

							shouldSkip = true;
							return;
						}
					} );

					$button.pptooltipster( {
						trigger: 'custom',
						triggerOpen: {
							mouseenter: true,
							touchstart: true,
						},
						triggerClose: {
							mouseleave: true,
							touchleave: true,
						},
						animation: 'fade',
						ppclass: ppclass,
						side: ttipPosition,
						interactive: true,
						positionTracker: true,
						contentCloning: true,
						functionReady: ( instance, helper ) => {
							// The clone keeps the source id; drop it so aria-describedby resolves to one node.
							$( helper.tooltip ).find( '[id]' ).removeAttr( 'id' );
						},
					} );

					// The bundled tooltipster has no focus triggers, so open and close on focus here.
					$button
						.on( 'focus', () => $button.pptooltipster( 'open' ) )
						.on( 'blur', () => $button.pptooltipster( 'close' ) );
				} );
			}

			/**
			 * Close any open tooltip in this widget on Escape, whether opened by hover or focus.
			 *
			 * @since x.x.x
			 */
			initTooltipEscape() {
				if ( ! this.elements.$tooltipButton.length ) {
					return;
				}

				$( document ).on( 'keydown.ppButtons' + this.getID(), ( event ) => {
					if ( 'Escape' !== event.key ) {
						return;
					}

					this.elements.$tooltipButton.filter( '.tooltipstered' ).each( function() {
						const $button = $( this );

						if ( $button.pptooltipster( 'status' ).open ) {
							$button.pptooltipster( 'close' );
						}
					} );
				} );
			}

			initKeyboardActivation() {
				this.elements.$fauxButton
					.on( 'keydown', function( event ) {
						if ( event.altKey || event.ctrlKey || event.metaKey || event.repeat ) {
							return;
						}

						if ( 'Enter' === event.key ) {
							event.preventDefault();
							this.click();
						} else if ( ' ' === event.key || 'Spacebar' === event.key ) {
							// Stop page scroll; activate on keyup like a native button.
							event.preventDefault();
						}
					} )
					.on( 'keyup', function( event ) {
						if ( ' ' === event.key || 'Spacebar' === event.key ) {
							event.preventDefault();
							this.click();
						}
					} );
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-buttons', ButtonsWidget );
	} );
})(jQuery);
