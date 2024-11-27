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
				const elementSettings = this.getElementSettings(),
					elementID         = this.getID(),
					$tooltipElements  = this.$element.find('[data-tooltip]'),
					tooltipArrow      = elementSettings.tooltip_arrow === 'yes',
					tooltipTrigger    = elementSettings.tooltip_trigger,
					tooltipAnimation  = elementSettings.tooltip_animation,
					tooltipSize       = elementSettings.tooltip_size,
					tooltipZIndex     = elementSettings.tooltip_zindex,
					breakpoints       = elementorFrontend.config.responsive.activeBreakpoints;

				let tooltipClass = `pp-tooltip pp-tooltip-${elementID}`;
				if (tooltipSize) {
					tooltipClass += ` pp-tooltip-size-${tooltipSize}`;
				}

				const getTooltipPosition = (tooltipOptions) => {
					const windowWidth = window.innerWidth;

					if (windowWidth >= breakpoints.widescreen.value) return tooltipOptions.position_widescreen || 'top';
					if (windowWidth > breakpoints.laptop.value) return tooltipOptions.position || 'top';
					if (windowWidth > breakpoints.tablet_extra.value) return tooltipOptions.position_laptop || 'top';
					if (windowWidth > breakpoints.tablet.value) return tooltipOptions.position_tablet_extra || 'top';
					if (windowWidth > breakpoints.mobile_extra.value) return tooltipOptions.position_tablet || 'top';
					if (windowWidth > breakpoints.mobile.value) return tooltipOptions.position_mobile_extra || 'top';

					return tooltipOptions.position_mobile || 'top';
				};

				$tooltipElements.each(function () {
					const $this         = $(this),
						tooltipOptions  = $this.data('tooltip') || {},
						tooltipPosition = getTooltipPosition(tooltipOptions),
						minWidth        = tooltipOptions.width || 'auto',
						tooltipDistance = tooltipOptions.distance || 10;

					$this.pptooltipster({
						trigger:         tooltipTrigger,
						animation:       tooltipAnimation,
						minWidth:        minWidth,
						ppclass:         tooltipClass,
						side:            tooltipPosition,
						arrow:           tooltipArrow,
						distance:        tooltipDistance,
						interactive:     true,
						positionTracker: true,
						zIndex:          tooltipZIndex,
					});
				});
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-pricing-table', TableWidget );
	} );
})(jQuery);