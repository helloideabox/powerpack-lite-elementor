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
				if ( this.$element.hasClass('elementor-widget-pp-table') ) {
					this.initTableSaw();
				}

				this.initTooltips();
			}

			initTableSaw() {
				const elementSettings = this.getElementSettings();

				if ( 'responsive' === elementSettings.table_type ) {

					if ( 'yes' === elementSettings.scrollable && 0 < elementSettings.breakpoint ) {
						if ( $(window).width() >= elementSettings.breakpoint ) {
							this.elements.$table.removeAttr('data-tablesaw-mode');
						}
					}

					$(document).trigger( 'enhance.tablesaw' );
				}
			}

			initTooltips() {
				const elementSettings = this.getElementSettings(),
					elementID         = this.getID(),
					$tooltipElements  = this.$element.find('[data-tooltip]'),
					tooltipArrow      = elementSettings.tooltip_arrow === 'yes',
					tooltipTrigger    = elementSettings.tooltip_trigger || 'hover',
					tooltipAnimation  = elementSettings.tooltip_animation || 'fade',
					tooltipSize       = elementSettings.tooltip_size,
					tooltipZIndex     = elementSettings.tooltip_zindex || 99,
					breakpoints       = elementorFrontend.config.responsive.activeBreakpoints;

				let tooltipClass = `pp-tooltip pp-tooltip-${elementID}`;
				if (tooltipSize) {
					tooltipClass += ` pp-tooltip-size-${tooltipSize}`;
				}

				const getTooltipPosition = (tooltipOptions) => {
					const windowWidth = window.innerWidth;

					const breakpointPositions = [
						{ bp: breakpoints.widescreen, position: tooltipOptions.position_widescreen },
						{ bp: breakpoints.laptop, position: tooltipOptions.position },
						{ bp: breakpoints.tablet_extra, position: tooltipOptions.position_laptop },
						{ bp: breakpoints.tablet, position: tooltipOptions.position_tablet_extra },
						{ bp: breakpoints.mobile_extra, position: tooltipOptions.position_tablet },
						{ bp: breakpoints.mobile, position: tooltipOptions.position_mobile_extra },
					];

					for (const { bp, position } of breakpointPositions) {
						if (bp && windowWidth > bp.value && position) {
							return position;
						}
					}

					return tooltipOptions.position_mobile || 'top';
				};

				$tooltipElements.each(function () {
					const $this         = $(this),
						tooltipOptions  = $this.data('tooltip') || {},
						tooltipPosition = getTooltipPosition(tooltipOptions),
						tooltipConfig   = {
							trigger:         tooltipTrigger,
							animation:       tooltipAnimation,
							minWidth:        tooltipOptions.width || 'auto',
							ppclass:         tooltipClass,
							side:            tooltipPosition,
							arrow:           tooltipArrow,
							distance:        tooltipOptions.distance || 10,
							interactive:     true,
							positionTracker: true,
							zIndex:          tooltipZIndex,
						};

					$this.pptooltipster(tooltipConfig);
				});
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-table', TableWidget );
		elementorFrontend.elementsHandler.attachHandler( 'pp-pricing-table', TableWidget );
	} );
})(jQuery);