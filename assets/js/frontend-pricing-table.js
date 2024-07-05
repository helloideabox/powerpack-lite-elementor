(function ($) {
	$( window ).on( 'elementor/frontend/init', () => {
		class TableWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						table: '.pp-table',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$table: this.$element.find( selectors.table ),
				};
			}

			bindEvents() {
				this.initTooltips();
			}

			initTooltips() {
				const elementSettings    = this.getElementSettings(),
					$id                  = this.getID(),
					tooltipElm           = this.$element.find('[data-tooltip]'),
					ttArrow              = elementSettings.tooltip_arrow,
					ttTrigger            = elementSettings.tooltip_trigger,
					animation            = elementSettings.tooltip_animation,
					tooltipSize          = elementSettings.tooltip_size,
					tooltipZindex        = elementSettings.tooltip_zindex,
					elementorBreakpoints = elementorFrontend.config.breakpoints;

				let ppclass = 'pp-tooltip' + ' pp-tooltip-' + $id;

				if ( '' !== tooltipSize && undefined !== tooltipSize ) {
					ppclass += ' pp-tooltip-size-' + tooltipSize;
				}

				tooltipElm.each(function () {
					let ttPosition = $(this).data('tooltip-position'),
						minWidth   = $(this).data('tooltip-width'),
						ttDistance = $(this).data('tooltip-distance');

					// Tablet
					if ( window.innerWidth <= elementorBreakpoints.lg && window.innerWidth >= elementorBreakpoints.md ) {
						ttPosition = $scope.find('.pp-pricing-table-tooptip[data-tooltip]').data('tooltip-position-tablet');
					}

					// Mobile
					if ( window.innerWidth < elementorBreakpoints.md ) {
						ttPosition = $scope.find('.pp-pricing-table-tooptip[data-tooltip]').data('tooltip-position-mobile');
					}

					$(this).pptooltipster({
						trigger : ttTrigger,
						animation : animation,
						minWidth: minWidth,
						ppclass : ppclass,
						side : ttPosition,
						arrow : ( 'yes' === ttArrow ),
						distance : ttDistance,
						interactive : true,
						positionTracker : true,
						zIndex : tooltipZindex,
					});
				});
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-pricing-table', TableWidget );
	} );
})(jQuery);