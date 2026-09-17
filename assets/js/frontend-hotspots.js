(function ($) {
	$( window ).on( 'elementor/frontend/init', () => {
		const focusableSelector = 'a[href], area[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), iframe, [tabindex]:not([tabindex="-1"])';

		class ImageHotspotsWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						tooltipElm: '[data-tooltip]',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$tooltipElm: this.$element.find( selectors.tooltipElm ),
				};
			}

			bindEvents() {
				this.initTooltips();
				this.initTooltipEscape();
			}

			unbindEvents() {
				$( document ).off( 'keydown.ppHotspots' + this.getID() );
			}

			/**
			 * Parsed tooltip options from the widget container.
			 *
			 * @since x.x.x
			 */
			getTooltipOptions() {
				return JSON.parse( this.$element.find( '.pp-image-hotspots' ).attr( 'data-tooltip-options' ) );
			}

			initTooltips() {
				const $id          = this.getID(),
					tooltipElm     = this.elements.$tooltipElm,
					tooltipOptions = this.getTooltipOptions(),
					ttArrow        = tooltipOptions.arrow,
					ttAlwaysOpen   = tooltipOptions.always_open,
					ttTrigger      = tooltipOptions.trigger,
					ttDistance     = tooltipOptions.distance,
					animation      = tooltipOptions.animation,
					tooltipWidth   = tooltipOptions.width,
					tooltipSize    = tooltipOptions.size,
					tooltipZindex  = tooltipOptions.zindex,
					alwaysOpen     = 'yes' === ttAlwaysOpen;

				let ppclass = 'pp-tooltip' + ' pp-tooltip-' + $id;

				if ( '' !== tooltipSize && undefined !== tooltipSize ) {
					ppclass += ' pp-tooltip-size-' + tooltipSize;
				}

				tooltipElm.each( ( index, element ) => {
					const $hotspot   = $( element ),
						ttPosition = $hotspot.data( 'tooltip-position' );

					$hotspot.pptooltipster( {
						trigger:         ttTrigger,
						animation:       animation,
						minWidth:        0,
						maxWidth:        tooltipWidth,
						ppclass:         ppclass,
						position:        ttPosition,
						arrow:           ( 'yes' === ttArrow ),
						distance:        ttDistance,
						interactive:     true,
						positionTracker: true,
						zIndex:          tooltipZindex,
						// Clone so the source stays in place for aria-describedby and the Always Open screen-reader copy.
						contentCloning:  true,
						functionReady:   ( instance, helper ) => this.onTooltipReady( $hotspot, $( helper.tooltip ), alwaysOpen ),
						functionAfter:   () => this.setExpanded( $hotspot, false ),
					} );

					if ( alwaysOpen ) {
						$hotspot.pptooltipster( 'show' );
					} else {
						this.bindHotspotKeyboard( $hotspot, 'click' === ttTrigger );
					}
				} );
			}

			/**
			 * Wire an opened tooltip: unique ids, visibility to AT, and a Tab path in and out of its content.
			 *
			 * @since x.x.x
			 */
			onTooltipReady( $hotspot, $tooltip, alwaysOpen ) {
				// The clone keeps the source id; drop it so aria-describedby resolves to one node.
				$tooltip.find( '[id]' ).removeAttr( 'id' );

				if ( alwaysOpen ) {
					this.proxyFocus( $hotspot, $tooltip );
					return;
				}

				this.setExpanded( $hotspot, true );

				$tooltip.off( '.ppHotspots' )
					.on( 'keydown.ppHotspots', ( event ) => {
						if ( 'Tab' !== event.key ) {
							return;
						}

						const $items = $tooltip.find( focusableSelector );

						if ( event.shiftKey && event.target === $items.get( 0 ) ) {
							event.preventDefault();
							$hotspot.trigger( 'focus' );
						} else if ( ! event.shiftKey && event.target === $items.get( -1 ) ) {
							// No preventDefault: the browser's Tab then moves on from the hotspot.
							$hotspot.data( 'ppSkipFocusOpen', true ).trigger( 'focus' );
						}
					} )
					.on( 'focusout.ppHotspots', ( event ) => {
						if ( ! this.isWithin( $hotspot, $tooltip, event.relatedTarget ) ) {
							$hotspot.pptooltipster( 'close' );
						}
					} );
			}

			/**
			 * Keyboard support the bundled tooltipster lacks: open on focus, close on blur,
			 * Enter/Space toggle, and Tab into interactive tooltip content.
			 *
			 * @since x.x.x
			 */
			bindHotspotKeyboard( $hotspot, isClick ) {
				const isButton = 'button' === $hotspot.attr( 'role' );

				if ( ! isClick ) {
					$hotspot.on( 'focus', () => {
						if ( ! $hotspot.data( 'ppSkipFocusOpen' ) ) {
							$hotspot.pptooltipster( 'open' );
						}

						$hotspot.removeData( 'ppSkipFocusOpen' );
					} );
				}

				$hotspot
					.on( 'focusout', ( event ) => {
						if ( ! this.isWithin( $hotspot, $( $hotspot.pptooltipster( 'elementTooltip' ) ), event.relatedTarget ) ) {
							$hotspot.pptooltipster( 'close' );
						}
					} )
					.on( 'keydown', ( event ) => {
						if ( event.altKey || event.ctrlKey || event.metaKey ) {
							return;
						}

						if ( 'Tab' === event.key && ! event.shiftKey ) {
							if ( $hotspot.pptooltipster( 'status' ).open ) {
								const $first = $( $hotspot.pptooltipster( 'elementTooltip' ) ).find( focusableSelector ).first();

								if ( $first.length ) {
									event.preventDefault();
									$first.trigger( 'focus' );
								}
							}

							return;
						}

						if ( ! isButton || event.repeat ) {
							return;
						}

						if ( 'Enter' === event.key ) {
							event.preventDefault();
							this.toggleTooltip( $hotspot );
						} else if ( ' ' === event.key || 'Spacebar' === event.key ) {
							// Stop page scroll; activate on keyup like a native button.
							event.preventDefault();
						}
					} )
					.on( 'keyup', ( event ) => {
						if ( isButton && ( ' ' === event.key || 'Spacebar' === event.key ) ) {
							event.preventDefault();
							this.toggleTooltip( $hotspot );
						}
					} );
			}

			/**
			 * Close any open tooltip in this widget on Escape, returning focus to its hotspot if it was inside.
			 *
			 * @since x.x.x
			 */
			initTooltipEscape() {
				if ( ! this.elements.$tooltipElm.length || 'yes' === this.getTooltipOptions().always_open ) {
					return;
				}

				$( document ).on( 'keydown.ppHotspots' + this.getID(), ( event ) => {
					if ( 'Escape' !== event.key ) {
						return;
					}

					this.elements.$tooltipElm.filter( '.tooltipstered' ).each( ( index, element ) => {
						const $hotspot = $( element );

						if ( ! $hotspot.pptooltipster( 'status' ).open ) {
							return;
						}

						const tooltip   = $hotspot.pptooltipster( 'elementTooltip' ),
							focusInside = tooltip && $.contains( tooltip, document.activeElement );

						$hotspot.pptooltipster( 'close' );

						if ( focusInside ) {
							$hotspot.data( 'ppSkipFocusOpen', true ).trigger( 'focus' );
						}
					} );
				} );
			}

			/**
			 * Always Open: hide the floating clone from AT and show focus on it while
			 * the in-order screen-reader copy holds keyboard focus.
			 *
			 * @since x.x.x
			 */
			proxyFocus( $hotspot, $tooltip ) {
				if ( $tooltip.data( 'ppFocusProxy' ) ) {
					return;
				}

				const $cloneItems = $tooltip.find( focusableSelector ),
					$sourceItems  = $hotspot.nextAll( '.pp-tooltip-container' ).first().find( focusableSelector );

				$tooltip.data( 'ppFocusProxy', true ).attr( 'aria-hidden', 'true' );
				$cloneItems.attr( 'tabindex', '-1' );

				$sourceItems.each( ( index, item ) => {
					$( item )
						.on( 'focus', () => $cloneItems.eq( index ).addClass( 'pp-hotspot-focus-proxy' ) )
						.on( 'blur', () => $cloneItems.eq( index ).removeClass( 'pp-hotspot-focus-proxy' ) );
				} );
			}

			/**
			 * @since x.x.x
			 */
			toggleTooltip( $hotspot ) {
				$hotspot.pptooltipster( $hotspot.pptooltipster( 'status' ).open ? 'close' : 'open' );
			}

			/**
			 * @since x.x.x
			 */
			setExpanded( $hotspot, open ) {
				if ( 'button' === $hotspot.attr( 'role' ) ) {
					$hotspot.attr( 'aria-expanded', open ? 'true' : 'false' );
				}
			}

			/**
			 * Whether a node is the hotspot, inside it, or inside its tooltip.
			 *
			 * @since x.x.x
			 */
			isWithin( $hotspot, $tooltip, node ) {
				if ( ! node ) {
					return false;
				}

				return $hotspot.is( node ) || $.contains( $hotspot[0], node ) || ( !! $tooltip.length && $.contains( $tooltip[0], node ) );
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-image-hotspots', ImageHotspotsWidget );
	} );
})(jQuery);
