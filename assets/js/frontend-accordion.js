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
				const accordionType = this.getElementSettings( 'accordion_type' ),
					speed         = this.getElementSettings( 'toggle_speed' );

				this.eventNamespace = '.ppAdvancedAccordion-' + this.getID();

				// Open default active tab.
				this.elements.$title.each( function() {
					if ( $( this ).hasClass( 'pp-accordion-tab-active-default' ) ) {
						$( this ).addClass( 'pp-accordion-tab-show pp-accordion-tab-active' );
						$( this ).closest( '.pp-accordion-item' ).children( '.pp-accordion-tab-content' ).slideDown( speed );
					}
				} );

				// Detach any previous handlers (handles nested accordions and editor re-renders).
				this.elements.$title.off( this.eventNamespace );

				this.elements.$title.on( 'click' + this.eventNamespace + ' keydown' + this.eventNamespace, function( e ) {
					if ( 'keydown' === e.type && 'Enter' !== e.key && ' ' !== e.key ) {
						return;
					}
					e.preventDefault();

					var $this       = $( this ),
						container   = $this.closest( '.pp-advanced-accordion' ),
						item        = $this.closest( '.pp-accordion-item' ),
						content     = item.children( '.pp-accordion-tab-content' ),
						allTitles   = container.find( '.pp-accordion-tab-title' ),
						allContents = container.find( '.pp-accordion-tab-content' );

					$( document ).trigger( 'ppe-accordion-switched', [ item ] );

					if ( 'accordion' === accordionType ) {
						allTitles.removeClass( 'pp-accordion-tab-active-default' );
						allContents.removeClass( 'pp-accordion-tab-active-default' );

						if ( $this.hasClass( 'pp-accordion-tab-show' ) ) {
							item.removeClass( 'pp-accordion-item-active' );
							$this.removeClass( 'pp-accordion-tab-show pp-accordion-tab-active' );
							$this.attr( 'aria-expanded', 'false' );
							content.slideUp( speed );
						} else {
							container.find( '.pp-accordion-item' ).removeClass( 'pp-accordion-item-active' );
							allTitles.removeClass( 'pp-accordion-tab-show pp-accordion-tab-active' ).attr( 'aria-expanded', 'false' );
							allContents.slideUp( speed );
							$this.addClass( 'pp-accordion-tab-show pp-accordion-tab-active' );
							$this.attr( 'aria-expanded', 'true' );
							item.addClass( 'pp-accordion-item-active' );
							content.slideDown( speed );
						}
					} else {
						// Toggle mode.
						if ( $this.hasClass( 'pp-accordion-tab-show' ) ) {
							$this.removeClass( 'pp-accordion-tab-show pp-accordion-tab-active' );
							$this.attr( 'aria-expanded', 'false' );
							item.removeClass( 'pp-accordion-item-active' );
							content.slideUp( speed );
						} else {
							$this.addClass( 'pp-accordion-tab-show pp-accordion-tab-active' );
							$this.attr( 'aria-expanded', 'true' );
							item.addClass( 'pp-accordion-item-active' );
							content.slideDown( speed );
						}
					}
				} );

				// Trigger on initial hash in URL.
				this.onHashchange();

				// Re-bind hashchange listener (namespaced to this widget instance to avoid accumulation).
				$( window ).off( 'hashchange' + this.eventNamespace ).on( 'hashchange' + this.eventNamespace, this.onHashchange.bind( this ) );
			}

			unbindEvents() {
				if ( ! this.eventNamespace ) {
					return;
				}
				$( window ).off( 'hashchange' + this.eventNamespace );
				if ( this.elements && this.elements.$title ) {
					this.elements.$title.off( this.eventNamespace );
				}
			}

			onHashchange() {
				if ( ! location.hash || ! $( location.hash ).length ) {
					return;
				}

				var element = $( location.hash + '.pp-accordion-tab-title' );

				if ( ! element.length ) {
					return;
				}

				if ( window.history && window.history.replaceState ) {
					window.history.replaceState( null, '', window.location.pathname + window.location.search );
				}

				$( 'html, body' ).animate( {
					scrollTop: ( element.parents( '.pp-accordion-item' ).offset().top - 50 ) + 'px',
				}, 500, function() {
					if ( ! element.parents( '.pp-accordion-item' ).hasClass( 'pp-accordion-item-active' ) ) {
						element.trigger( 'click' );
					}
				} );
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-advanced-accordion', AdvancedAccordionWidget );
	} );
})(jQuery);
