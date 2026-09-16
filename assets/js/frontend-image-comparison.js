(function ($) {
	$(window).on( 'elementor/frontend/init', () => {
		const STEP = 1,
			STEP_LARGE = 10;

		class ImageComparisonWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						container: '.pp-image-comparison',
						beforeImg: '.pp-before-image',
						afterImg: '.pp-after-image',
						handle: '.pp-comparison-handle',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings('selectors');
				return {
					$container: this.$element.find(selectors.container),
					$beforeImg: this.$element.find(selectors.beforeImg),
					$afterImg: this.$element.find(selectors.afterImg),
					$handle: this.$element.find(selectors.handle),
				};
			}

			unbindEvents() {
				this.eventNamespace = '.ppImageComparison-' + this.getID();

				this.elements.$handle.off( this.eventNamespace );
				this.elements.$container.off( this.eventNamespace );
			}

			bindEvents() {
				this.unbindEvents();

				const container = this.elements.$container,
					settings = container.data('settings'),
					handle = this.elements.$handle,
					ns = this.eventNamespace;

				this.isVertical = 'vertical' === settings.orientation;
				this.valueText = handle.attr('data-value-text') || '';
				this.suppressClick = false;

				this.setRatio( parseFloat( settings.visible_ratio ) * 100 );

				handle.on( 'move' + ns, ( event ) => {
					this.setRatio( this.pointToRatio( event.pageX, event.pageY ) );
				} );

				// A drag finishes with a click on the container; it must not re-run the click path.
				handle.on( 'moveend' + ns, () => {
					this.suppressClick = true;
					setTimeout( () => {
						this.suppressClick = false;
					}, 0 );
				} );

				handle.on( 'keydown' + ns, ( event ) => this.onKeydown( event ) );

				if ( settings.slider_on_hover ) {
					container.on( 'mousemove' + ns, ( event ) => {
						this.setRatio( this.pointToRatio( event.pageX, event.pageY ) );
					} );
				} else {
					// WCAG 2.5.7: the divider has to be positionable with a single pointer
					// without dragging, so a click or tap on the image moves it there.
					container.on( 'click' + ns, ( event ) => {
						if ( this.suppressClick ) {
							return;
						}

						this.setRatio( this.pointToRatio( event.pageX, event.pageY ) );

						// So the arrow keys carry on from wherever the tap landed.
						if ( ! handle[0].contains( event.target ) ) {
							handle.trigger( 'focus' );
						}
					} );
				}
			}

			pointToRatio( pageX, pageY ) {
				const container = this.elements.$container,
					offset = container.offset();

				if ( this.isVertical ) {
					return ( ( pageY - offset.top ) / container.outerHeight() ) * 100;
				}

				return ( ( pageX - offset.left ) / container.outerWidth() ) * 100;
			}

			setRatio( ratio ) {
				const handle = this.elements.$handle,
					afterImg = this.elements.$afterImg;

				ratio = Math.max( 0, Math.min( 100, ratio ) );
				this.ratio = ratio;

				// Percentages rather than pixels: the same value is what aria-valuenow
				// publishes and what the arrow keys step. At 0 and 100 the handle's own
				// negative margin still centres it on the edge, as the old pixel
				// edge-case branch did, and the container's overflow clips the rest.
				if ( this.isVertical ) {
					handle.css( { top: ratio + '%', bottom: 'auto' } );
					afterImg.css( { top: ratio + '%', bottom: 'auto' } );
					afterImg.find('img').css( 'bottom', ratio + '%' );
				} else {
					handle.css( { left: ratio + '%', right: 'auto' } );
					afterImg.css( { left: ratio + '%', right: 'auto' } );
					afterImg.find('img').css( 'right', ratio + '%' );
				}

				const rounded = Math.round( ratio );

				handle.attr( 'aria-valuenow', rounded );

				if ( this.valueText ) {
					handle.attr( 'aria-valuetext', this.valueText.replace( '{percent}', rounded ) );
				}

				this.hideLabels();
			}

			onKeydown( event ) {
				// Legacy key names from older browsers.
				const key = event.key.replace( /^(Left|Right|Up|Down)$/, 'Arrow$1' ),
					// Mapped to what the user sees move, not to a fixed axis.
					less = this.isVertical ? 'ArrowUp' : 'ArrowLeft',
					more = this.isVertical ? 'ArrowDown' : 'ArrowRight';

				let ratio = this.ratio;

				switch ( key ) {
					case less:
						ratio -= STEP;
						break;
					case more:
						ratio += STEP;
						break;
					case 'PageDown':
						ratio -= STEP_LARGE;
						break;
					case 'PageUp':
						ratio += STEP_LARGE;
						break;
					case 'Home':
						ratio = 0;
						break;
					case 'End':
						ratio = 100;
						break;
					default:
						return; // The perpendicular arrows still scroll the page.
				}

				event.preventDefault();
				this.setRatio( ratio );
			}

			hideLabels() {
				const container = this.elements.$container,
					handle = this.elements.$handle,
					vertical = this.isVertical,
					labelBefore = container.find('.pp-comparison-label-before span'),
					labelAfter = container.find('.pp-comparison-label-after span');

				if ( ! labelBefore.length && ! labelAfter.length ) {
					return;
				}

				// .css() reports the computed pixel value even though a percentage was set.
				const handlePos = parseInt( handle.css( vertical ? 'top' : 'left' ), 10 ),
					containerSize = vertical ? container.outerHeight() : container.outerWidth();

				// Each label is tested on its own: one can be cleared while the other is
				// set, and position() on an empty set throws.
				if ( labelBefore.length ) {
					const end = vertical
						? labelBefore.position().top + labelBefore.outerHeight()
						: labelBefore.position().left + labelBefore.outerWidth();

					labelBefore.stop().css( 'opacity', end + 15 >= handlePos ? 0 : 1 );
				}

				if ( labelAfter.length ) {
					const end = vertical
						? labelAfter.position().top + labelAfter.outerHeight()
						: labelAfter.position().left + labelAfter.outerWidth();

					labelAfter.stop().css( 'opacity', ( containerSize - ( end + 15 ) ) <= handlePos ? 0 : 1 );
				}
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-image-comparison', ImageComparisonWidget );
	} );
})(jQuery);
