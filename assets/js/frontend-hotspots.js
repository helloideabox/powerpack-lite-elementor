(function ($) {
	$( window ).on( 'elementor/frontend/init', () => {
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
			}

			initTooltips() {
				const $id          = this.getID(),
					tooltipElm     = this.elements.$tooltipElm,
					tooltipOptions = JSON.parse( this.$element.find('.pp-image-hotspots').attr('data-tooltip-options') ),
					ttArrow        = tooltipOptions.arrow,
					ttAlwaysOpen   = tooltipOptions.always_open,
					ttTrigger      = tooltipOptions.trigger,
					ttDistance     = tooltipOptions.distance,
					animation      = tooltipOptions.animation,
					tooltipWidth   = tooltipOptions.width,
					tooltipSize    = tooltipOptions.size,
					tooltipZindex  = tooltipOptions.zindex;

				let ppclass = 'pp-tooltip' + ' pp-tooltip-' + $id;

				if ( '' !== tooltipSize && undefined !== tooltipSize ) {
					ppclass += ' pp-tooltip-size-' + tooltipSize;
				}

				tooltipElm.each(function () {
					let ttPosition = $(this).data('tooltip-position');

					$(this).pptooltipster({
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
					});

					if ( 'yes' === ttAlwaysOpen ) {
						$(this).pptooltipster();
						$(this).pptooltipster('show');
					}
				});
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-image-hotspots', ImageHotspotsWidget );
	} );
})(jQuery);