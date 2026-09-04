(function ($) {
	$(window).on( 'elementor/frontend/init', () => {
		class AdvancedAccordionWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						accordion: '.pp-advanced-accordion',
						item: '.pp-accordion-item',
						title: '.pp-accordion-tab-title',
						content: '.pp-accordion-tab-content',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' ),
					$accordion = this.$element.find( selectors.accordion ).first();

				return {
					$accordion: $accordion,
					// Only this accordion's own titles. An accordion nested inside a tab's
					// content (e.g. via a saved template) is driven by its own handler, so
					// binding to its titles here would toggle them twice per click.
					$title: $accordion.children( selectors.item ).children( selectors.title ),
				};
			}

			bindEvents() {
				const selectors = this.getSettings( 'selectors' ),
					accordionType = this.getElementSettings( 'accordion_type' ),
					speed         = this.getElementSettings( 'toggle_speed' );

				this.eventNamespace = '.ppAdvancedAccordion-' + this.getID();

				// Open default active tab.
				this.elements.$title.each( function() {
					if ( $( this ).hasClass( 'pp-accordion-tab-active-default' ) ) {
						$( this ).addClass( 'pp-accordion-tab-show pp-accordion-tab-active' );
						$( this ).closest( selectors.item ).children( selectors.content ).slideDown( speed );
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
						container   = $this.closest( selectors.accordion ),
						item        = $this.closest( selectors.item ),
						content     = item.children( selectors.content ),
						// Direct children only, so a nested accordion's tabs are left alone.
						allItems    = container.children( selectors.item ),
						allTitles   = allItems.children( selectors.title ),
						allContents = allItems.children( selectors.content );

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
							allItems.removeClass( 'pp-accordion-item-active' );
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

				var selectors = this.getSettings( 'selectors' ),
					element   = $( location.hash + selectors.title );

				if ( ! element.length ) {
					return;
				}

				var item = element.closest( selectors.item );

				if ( ! item.length ) {
					return;
				}

				if ( window.history && window.history.replaceState ) {
					window.history.replaceState( null, '', window.location.pathname + window.location.search );
				}

				$( 'html, body' ).animate( {
					scrollTop: ( item.offset().top - 50 ) + 'px',
				}, 500, function() {
					if ( ! item.hasClass( 'pp-accordion-item-active' ) ) {
						element.trigger( 'click' );
					}
				} );
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-advanced-accordion', AdvancedAccordionWidget );
	} );
})(jQuery);
