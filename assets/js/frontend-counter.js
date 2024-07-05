(function ($) {
	$( window ).on( 'elementor/frontend/init', () => {
		class CounterWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						counter: '.pp-counter',
						counterNumber: '.pp-counter-number'
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$counter: this.$element.find( selectors.counter ),
					$counterNumber: this.$element.find( selectors.counterNumber ),
				};
			}

			bindEvents() {
				this.intersectionObserver = elementorModules.utils.Scroll.scrollObserver({
					callback: event => {
						if (event.isInViewport) {
							this.intersectionObserver.unobserve(this.elements.$counterNumber[0]);
							const data = this.elements.$counterNumber.data(),
							decimalDigits = data.toValue.toString().match(/\.(.*)/);

							if (decimalDigits) {
								data.rounding = decimalDigits[1].length;
							}

							this.elements.$counterNumber.numerator(data);
						}
					}
				});

				this.intersectionObserver.observe(this.elements.$counterNumber[0]);
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-counter', CounterWidget );
	} );
})(jQuery);