(function ($) {
	$( window ).on( 'elementor/frontend/init', () => {
		class ImageComparisonWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						container: '.pp-image-comparison',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$container: this.$element.find( selectors.container ),
				};
			}

			bindEvents() {
				const container = this.elements.$container,
					settings = this.elements.$container.data('settings');

				this.$element.imagesLoaded( function() {
					container.twentytwenty({
						default_offset_pct:    settings.visible_ratio,
						orientation:           settings.orientation,
						before_label:          settings.before_label,
						after_label:           settings.after_label,
						move_slider_on_hover:  settings.slider_on_hover,
						move_with_handle_only: settings.slider_with_handle,
						click_to_move:         settings.slider_with_click,
						no_overlay:            settings.no_overlay
					});
				} );
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-image-comparison', ImageComparisonWidget );
	} );
})(jQuery);