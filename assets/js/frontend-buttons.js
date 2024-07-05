(function ($) {
	$( window ).on( 'elementor/frontend/init', () => {
		class ButtonsWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						tooltipButton: '.pp-button[data-tooltip]',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$tooltipButton: this.$element.find( selectors.tooltipButton ),
				};
			}

			bindEvents() {
				this.initTooltips();
			}

			initTooltips() {
				const $id   = this.getID(),
					ppclass = 'pp-tooltip' + ' pp-tooltip-' + $id,
					button  = this.elements.$tooltipButton;

				button.each( function() {
					const ttOptions  = $(this).data('tooltip');
					let ttipPosition = ttOptions.position;

					const breakpoints = elementorFrontend.config.responsive.activeBreakpoints;

					let shouldSkip = false;
					Object.keys(breakpoints).forEach(breakpointName => {
						if ( shouldSkip ) {
							return;
						}

						if ( window.innerWidth <= breakpoints[breakpointName].value ) {
							if ( undefined != ttOptions['position_' + breakpointName] ) {
								ttipPosition = ttOptions['position_' + breakpointName];
							}

							shouldSkip = true;
    						return;
						}
					});

					button.pptooltipster({
						trigger : 'hover',
						animation : 'fade',
						ppclass : ppclass,
						side : ttipPosition,
						interactive : true,
						positionTracker : true,
						contentCloning : true,
					});
				});
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-buttons', ButtonsWidget );
	} );
})(jQuery);