(function ($) {
	$(window).on( 'elementor/frontend/init', () => {
		class ImageAccordionWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						accordion: '.pp-image-accordion',
						item: '.pp-image-accordion-item',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$accordion: this.$element.find( selectors.accordion ),
					$item: this.$element.find( selectors.item ),
				};
			}

			bindEvents() {
				const action = this.getElementSettings('accordion_action');

				if ( 'on-hover' === action ) {
					this.elements.$item.on({
						mouseenter: this.onEnter.bind( this, action ),
						mouseleave: this.onOut.bind( this )
					});
				}
				else if ( 'on-click' === action ) {
					this.elements.$item.on( 'click', this.onEnter.bind( this, action ) );
				}
			}

			onEnter(action, e) {
				const $id      = this.elements.$accordion.attr( 'id' ),
					$item    = $(e.target).parent();

				if ( 'on-click' === action ) {
					e.stopPropagation(); // when you click the button, it stops the page from seeing it as clicking the body too
				}

				this.resetAll();

				this.activeItem($item);

				if ( 'on-click' === action ) {
					$('#'+ $id).click( function(e) {
						e.stopPropagation(); // when you click within the content area, it stops the page from seeing it as clicking the body too
					});

					this.disableBodyClick();
				}
			}

			onOut() {
				this.elements.$item.css('flex', '1');
				this.elements.$item.find('.pp-image-accordion-content-wrap').removeClass('pp-image-accordion-content-active');
				this.elements.$item.removeClass('pp-image-accordion-active');
			}

			activeItem($item) {
				$item.addClass('pp-image-accordion-active');
				$item.find('.pp-image-accordion-content-wrap').addClass('pp-image-accordion-content-active');
				$item.css('flex', '3');
			}

			disableBodyClick() {
				const disableBodyClick = this.getElementSettings('disable_body_click');

				if ( 'yes' !== disableBodyClick ) {
					$('body').on( 'click', function() {
						this.elements.$item.css('flex', '1');
						this.elements.$item.find('.pp-image-accordion-content-wrap').removeClass('pp-image-accordion-content-active');
						this.elements.$item.removeClass('pp-image-accordion-active');
					}.bind(this));
				}
			}

			resetAll() {
				this.elements.$item.css('flex', '1');
				this.elements.$item.removeClass('pp-image-accordion-active');
				this.elements.$item.find('.pp-image-accordion-content-wrap').removeClass('pp-image-accordion-content-active');
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-image-accordion', ImageAccordionWidget );
	} );
})(jQuery);