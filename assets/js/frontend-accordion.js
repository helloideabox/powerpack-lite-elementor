(function ($) {
	$(window).on( 'elementor/frontend/init', () => {
		class AdvancedAccordionWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						title: '.pp-accordion-tab-title',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$title: this.$element.find( selectors.title ),
				};
			}

			bindEvents() {
				const $type = this.getElementSettings('accordion_type'),
					$speed  = this.getElementSettings('toggle_speed');

				// Open default actived tab
				this.elements.$title.each(function() {
					if ( $(this).hasClass('pp-accordion-tab-active-default') ) {
						$(this).addClass('pp-accordion-tab-show pp-accordion-tab-active');
						$(this).next().slideDown($speed);
					}
				});

				// Remove multiple click event for nested accordion
				this.elements.$title.unbind('click');

				this.elements.$title.on( 'click keypress', function(e) {
					e.preventDefault();

					var validClick = ( e.which == 1 || e.which == 13 || e.which == 32 || e.which == undefined ) ? true : false;

					if ( ! validClick ) {
						return;
					}

					var $this     = $(this),
						$item     = $this.parent(),
						container = $this.closest('.pp-advanced-accordion'),
						item      = $this.closest('.pp-accordion-item'),
						title     = container.find('.pp-accordion-tab-title'),
						content   = container.find('.pp-accordion-tab-content');

					$(document).trigger('ppe-accordion-switched', [ $item ]);

					if ( 'accordion' === $type ) {
						title.removeClass('pp-accordion-tab-active-default');
						content.removeClass('pp-accordion-tab-active-default');

						if ( $this.hasClass('pp-accordion-tab-show') ) {
							item.removeClass('pp-accordion-item-active');
							$this.removeClass('pp-accordion-tab-show pp-accordion-tab-active');
							$this.attr('aria-expanded', 'false');
							$this.next().slideUp($speed);
						} else {
							container.find('.pp-accordion-item').removeClass('pp-accordion-item-active');
							title.removeClass('pp-accordion-tab-show pp-accordion-tab-active');
							content.slideUp($speed);
							$this.toggleClass('pp-accordion-tab-show pp-accordion-tab-active');
							title.attr('aria-expanded', 'false');
							item.toggleClass('pp-accordion-item-active');

							if ( $this.hasClass('pp-accordion-tab-title') ) {
								$this.attr('aria-expanded', 'true');
							}

							$this.next().slideToggle($speed);
						}
					} else {
						// For acccordion type 'toggle'
						if ( $this.hasClass('pp-accordion-tab-show') ) {
							$this.removeClass('pp-accordion-tab-show pp-accordion-tab-active');
							$this.next().slideUp($speed);
						} else {
							$this.addClass('pp-accordion-tab-show pp-accordion-tab-active');
							$this.next().slideDown($speed);
						}
					}
				});

				// Trigger filter by hash parameter in URL.
				this.onHashchange();

				// Trigger filter on hash change in URL.
				$(window).on( 'hashchange', function() {
					this.onHashchange();
				}.bind(this) );
			}

			onHashchange() {
				if ( location.hash && $(location.hash).length > 0 ) {
					var element = $(location.hash + '.pp-accordion-tab-title');
		
					if ( element && element.length > 0 ) {
						location.href = '#';
						$('html, body').animate({
							scrollTop: ( element.parents('.pp-accordion-item').offset().top - 50 ) + 'px'
						}, 500, function() {
							if ( ! element.parents('.pp-accordion-item').hasClass('pp-accordion-item-active') ) {
								element.trigger('click');
							}
						});
					}
				}
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-advanced-accordion', AdvancedAccordionWidget );
	} );
})(jQuery);