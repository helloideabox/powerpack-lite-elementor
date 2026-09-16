(function ($) {
	$( window ).on( 'elementor/frontend/init', () => {
		class ScrollImageWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					config: {
						transformOffset: null
					},
					selectors: {
						container: '.pp-image-scroll-container',
						overlay: '.pp-image-scroll-overlay',
						verticalScroll: '.pp-image-scroll-vertical',
						imageScroll: '.pp-image-scroll-image img',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$container: this.$element.find( selectors.container ),
					$overlay: this.$element.find( selectors.overlay ),
					$verticalScroll: this.$element.find( selectors.verticalScroll ),
					$imageScroll: this.$element.find( selectors.imageScroll ),
				};
			}

			bindEvents() {
				this.init();
			}

			init() {
				const direction = this.getElementSettings('direction_type'),
					reverse = this.getElementSettings('reverse'),
					trigger = this.getElementSettings('trigger_type'),
					scrollElement = this.elements.$container;

				if ( trigger === 'scroll' ) {
					scrollElement.addClass('pp-container-scroll');

					if ( direction === 'vertical' ) {
						this.elements.$verticalScroll.addClass('pp-image-scroll-ver');
					} else {
						scrollElement.imagesLoaded(function() {
							this.elements.$overlay.css( {
								'width': this.elements.$imageScroll.width(),
								'height': this.elements.$imageScroll.height(),
							} );
						}.bind( this ));
					}
				} else {
					if ( reverse === 'yes' ) {
						scrollElement.imagesLoaded(function() {
							scrollElement.addClass('pp-container-scroll-instant');
							this.setTransform();
							this.startTransform();
						}.bind( this ));
					}

					if ( direction === 'vertical' ) {
						this.elements.$verticalScroll.removeClass('pp-image-scroll-ver');
					}

					const enter = function() {
							scrollElement.removeClass('pp-container-scroll-instant');
							this.setTransform();
							reverse === 'yes' ? this.endTransform() : this.startTransform();
						}.bind( this ),
						leave = function() {
							reverse === 'yes' ? this.startTransform() : this.endTransform();
						}.bind( this );

					// Keyboard users get the same reveal as hover: without focusin/focusout
					// the concealed part of the image is unreachable without a pointer.
					scrollElement.on( 'mouseenter focusin', enter );
					scrollElement.on( 'mouseleave focusout', leave );
				}
			}

			startTransform() {
				const settings = this.getSettings();
				const direction = this.getElementSettings('direction_type');

				this.elements.$imageScroll.css( 'transform', ( direction === 'vertical' ? 'translateY' : 'translateX' ) + '( -' +  settings.config.transformOffset + 'px)' );
			}
			
			endTransform() {
				const direction = this.getElementSettings('direction_type');

				this.elements.$imageScroll.css( 'transform', ( direction === 'vertical' ? 'translateY' : 'translateX' ) + '(0px)' );
			}

			setTransform() {
				const settings = this.getSettings();
				const direction = this.getElementSettings('direction_type');

				if ( direction === 'vertical' ) {
					settings.config.transformOffset = this.elements.$imageScroll.height() - this.elements.$container.height();
				} else {
					settings.config.transformOffset = this.elements.$imageScroll.width() - this.elements.$container.width();
				}
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-scroll-image', ScrollImageWidget );
	} );
})(jQuery);