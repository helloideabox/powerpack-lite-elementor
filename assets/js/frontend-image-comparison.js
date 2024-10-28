(function ($) {
	$(window).on( 'elementor/frontend/init', () => {
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

			bindEvents() {
				const container = this.elements.$container,
					settings = container.data('settings'),
					handle = this.elements.$handle,
					afterImg = this.elements.$afterImg,
					visibleRatio = settings.visible_ratio,
					startPosition = parseFloat(visibleRatio * 100);

				if ( 'horizontal' === settings.orientation ) {
					handle.css('left', startPosition + '%');
					afterImg.css('left', startPosition + '%');
					afterImg.find('img').css('right', startPosition + '%');

					handle.on('move', (event) => {
						this.handleHorizontalMove(event, container, handle, afterImg);
					});
				} else {
					handle.css('top', startPosition + '%');
					afterImg.css('top', startPosition + '%');
					afterImg.find('img').css('bottom', startPosition + '%');

					handle.on('move', (event) => {
						this.handleVerticalMove(event, container, handle, afterImg);
					});
				}

				if ( settings.slider_on_hover ) {
					container.on('mousemove', (event) => {
						this.handleHover(event, container, handle, afterImg, settings);
					});
				}

				this.hideLabels();
			}

			handleHorizontalMove(event, container, handle, afterImg) {
				let overlayWidth = event.pageX - container.offset().left;

				handle.css({ left: 'auto', right: 'auto' });
				afterImg.css({ left: 'auto', right: 'auto' });

				if ( overlayWidth > 0 && overlayWidth < container.outerWidth() ) {
					handle.css('left', overlayWidth);
					afterImg.css('left', overlayWidth);
					afterImg.find('img').css('right', overlayWidth);
				} else {
					this.handleEdgeCases(overlayWidth, container, handle, afterImg, 'horizontal');
				}

				this.hideLabels();
			}

			handleVerticalMove(event, container, handle, afterImg) {
				let overlayHeight = event.pageY - container.offset().top;

				// Reset
				handle.css({ top: 'auto', bottom: 'auto' });
				afterImg.css({ top: 'auto', bottom: 'auto' });

				if ( overlayHeight > 0 && overlayHeight < container.outerHeight() ) {
					handle.css('top', overlayHeight);
					afterImg.css('top', overlayHeight);
					afterImg.find('img').css('bottom', overlayHeight);
				} else {
					this.handleEdgeCases(overlayHeight, container, handle, afterImg, 'vertical');
				}

				this.hideLabels();
			}

			handleEdgeCases(overlayDimension, container, handle, afterImg, orientation) {
				if ( 'horizontal' === orientation ) {
					if ( overlayDimension <= 0 ) {
						handle.css('left', 0);
						afterImg.css('left', 0);
						afterImg.find('img').css('right', 0);
					} else if ( overlayDimension >= container.outerWidth() ) {
						handle.css('right', -handle.outerWidth() / 2);
						afterImg.css('right', 0);
						afterImg.find('img').css('right', '100%');
					}
				} else {
					if ( overlayDimension <= 0 ) {
						handle.css('top', 0);
						afterImg.css('top', 0);
						afterImg.find('img').css('bottom', 0);
					} else if ( overlayDimension >= container.outerHeight() ) {
						handle.css('bottom', -handle.outerHeight() / 2);
						afterImg.css('bottom', 0);
						afterImg.find('img').css('bottom', '100%');
					}
				}
			}

			handleHover(event, container, handle, afterImg, settings) {
				if ('horizontal' === settings.orientation) {
					let overlayWidth = event.pageX - container.offset().left;
					handle.css('left', overlayWidth);
					afterImg.css('left', overlayWidth);
					afterImg.find('img').css('right', overlayWidth);
				} else {
					let overlayHeight = event.pageY - container.offset().top;
					handle.css('top', overlayHeight);
					afterImg.css('top', overlayHeight);
					afterImg.find('img').css('bottom', overlayHeight);
				}

				this.hideLabels();
			}

			hideLabels() {
				const container = this.elements.$container,
					settings = container.data('settings'),
					handle = this.elements.$handle;

				let labelOne = container.find('.pp-comparison-label-before span'),
					labelTwo = container.find('.pp-comparison-label-after span');

				if ( !labelOne.length && !labelTwo.length ) {
					return;
				}

				if ( 'horizontal' === settings.orientation ) {
					let labelOneOffset = labelOne.position().left + labelOne.outerWidth(),
						labelTwoOffset = labelTwo.position().left + labelTwo.outerWidth();

					if ( labelOneOffset + 15 >= parseInt(handle.css('left'), 10) ) {
						labelOne.stop().css('opacity', 0);
					} else {
						labelOne.stop().css('opacity', 1);
					}

					if ( (container.outerWidth() - (labelTwoOffset + 15)) <= parseInt(handle.css('left'), 10) ) {
						labelTwo.stop().css('opacity', 0);
					} else {
						labelTwo.stop().css('opacity', 1);
					}
				} else {
					let labelOneOffset = labelOne.position().top + labelOne.outerHeight(),
						labelTwoOffset = labelTwo.position().top + labelTwo.outerHeight();

					if ( labelOneOffset + 15 >= parseInt(handle.css('top'), 10) ) {
						labelOne.stop().css('opacity', 0);
					} else {
						labelOne.stop().css('opacity', 1);
					}

					if ( (container.outerHeight() - (labelTwoOffset + 15)) <= parseInt(handle.css('top'), 10) ) {
						labelTwo.stop().css('opacity', 0);
					} else {
						labelTwo.stop().css('opacity', 1);
					}
				}
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-image-comparison', ImageComparisonWidget );
	} );
})(jQuery);