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
					$accordion = this.$element.find( selectors.accordion ).first(),
					$items     = $accordion.children( selectors.item );

				return {
					$accordion: $accordion,
					$items: $items,
					// Only this accordion's own titles. An accordion nested inside a tab's
					// content (e.g. via a saved template) is driven by its own handler, so
					// binding to its titles here would toggle them twice per click.
					// The title sits directly under the item (FAQ) or inside the heading
					// wrapper (Advanced Accordion); .add() collects both in document order.
					$title: this.collectTitles( $items ),
				};
			}

			/**
			 * Titles one or two levels below the given items, in document order.
			 *
			 * A nested accordion's titles sit three levels down, so they are not picked up.
			 */
			collectTitles( $items ) {
				const title = this.getSettings( 'selectors' ).title;

				return $items.children( title ).add( $items.children().children( title ) );
			}

			prefersReducedMotion() {
				return window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches;
			}

			/**
			 * The panel slide is decorative, so an explicit system-level request for
			 * reduced motion is always honoured — there is no author opt-out.
			 *
			 * Resolved per interaction rather than once at bind time, so a mid-session
			 * change to the system setting is picked up.
			 */
			getToggleSpeed() {
				return this.prefersReducedMotion() ? 0 : this.getElementSettings( 'toggle_speed' );
			}

			bindEvents() {
				const selectors = this.getSettings( 'selectors' ),
					accordionType = this.getElementSettings( 'accordion_type' ),
					getSpeed      = () => this.getToggleSpeed();

				this.eventNamespace = '.ppAdvancedAccordion-' + this.getID();

				// Open default active tab.
				this.elements.$title.each( function() {
					const $title   = $( this ),
						$content = $title.closest( selectors.item ).children( selectors.content );

					if ( $title.hasClass( 'pp-accordion-tab-active-default' ) ) {
						$title.addClass( 'pp-accordion-tab-show pp-accordion-tab-active' );
						$title.attr( 'aria-expanded', 'true' );
						$content.removeAttr( 'hidden' );
						$content.slideDown( getSpeed() );
					} else {
						$title.attr( 'aria-expanded', 'false' );
						$content.attr( 'hidden', '' );
					}
				} );

				// Detach any previous handlers (handles nested accordions and editor re-renders).
				this.elements.$title.off( this.eventNamespace );

				this.elements.$title.on( 'click' + this.eventNamespace + ' keydown' + this.eventNamespace, ( e ) => {
					if ( 'keydown' === e.type ) {
						const isActivationKey = 'Enter' === e.key || ' ' === e.key || 'Spacebar' === e.key || 'Space' === e.code;
						if ( ! isActivationKey ) {
							return;
						}
					}

					e.preventDefault();

					var speed       = getSpeed(),
						$this       = $( e.currentTarget ),
						container   = $this.closest( selectors.accordion ),
						item        = $this.closest( selectors.item ),
						content     = item.children( selectors.content ),
						// Direct children only, so a nested accordion's tabs are left alone.
						allItems    = container.children( selectors.item ),
						allTitles   = this.collectTitles( allItems ),
						allContents = allItems.children( selectors.content );

					$( document ).trigger( 'ppe-accordion-switched', [ item ] );

					if ( 'accordion' === accordionType ) {
						allTitles.removeClass( 'pp-accordion-tab-active-default' );
						allContents.removeClass( 'pp-accordion-tab-active-default' );

						if ( $this.hasClass( 'pp-accordion-tab-show' ) ) {
							// Collapse the open panel.
							item.removeClass( 'pp-accordion-item-active' );
							$this.removeClass( 'pp-accordion-tab-show pp-accordion-tab-active' );
							$this.attr( 'aria-expanded', 'false' );
							content.slideUp( speed, function() {
								$( this ).attr( 'hidden', '' );
							} );
						} else {
							// Collapse all panels first.
							allItems.removeClass( 'pp-accordion-item-active' );
							allTitles.removeClass( 'pp-accordion-tab-show pp-accordion-tab-active' ).attr( 'aria-expanded', 'false' );
							allContents.slideUp( speed, function() {
								$( this ).attr( 'hidden', '' );
							} );

							// Expand the clicked panel.
							$this.addClass( 'pp-accordion-tab-show pp-accordion-tab-active' );
							$this.attr( 'aria-expanded', 'true' );
							item.addClass( 'pp-accordion-item-active' );
							content.stop( true, false ).removeAttr( 'hidden' ).slideDown( speed );
						}
					} else {
						// Toggle mode — each panel opens/closes independently.
						if ( $this.hasClass( 'pp-accordion-tab-show' ) ) {
							$this.removeClass( 'pp-accordion-tab-show pp-accordion-tab-active' );
							$this.attr( 'aria-expanded', 'false' );
							item.removeClass( 'pp-accordion-item-active' );
							content.slideUp( speed, function() {
								$( this ).attr( 'hidden', '' );
							} );
						} else {
							$this.addClass( 'pp-accordion-tab-show pp-accordion-tab-active' );
							$this.attr( 'aria-expanded', 'true' );
							item.addClass( 'pp-accordion-item-active' );
							content.removeAttr( 'hidden' ).slideDown( speed );
						}
					}
				} );

				// Arrow-key navigation between accordion headers (WAI-ARIA accordion pattern).
				this.elements.$title.on( 'keydown' + this.eventNamespace, ( e ) => {
					const titles = this.elements.$title.toArray();
					const idx    = titles.indexOf( e.currentTarget );

					if ( e.key === 'ArrowDown' || e.key === 'Down' ) {
						e.preventDefault();
						const next = ( idx + 1 ) % titles.length;
						$( titles[ next ] ).focus();
					} else if ( e.key === 'ArrowUp' || e.key === 'Up' ) {
						e.preventDefault();
						const prev = ( idx - 1 + titles.length ) % titles.length;
						$( titles[ prev ] ).focus();
					} else if ( e.key === 'Home' ) {
						e.preventDefault();
						$( titles[ 0 ] ).focus();
					} else if ( e.key === 'End' ) {
						e.preventDefault();
						$( titles[ titles.length - 1 ] ).focus();
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
				const selectors = this.getSettings( 'selectors' );
				let element;

				if ( ! location.hash ) {
					return;
				}

				try {
					element = $( location.hash + selectors.title );
				} catch ( err ) {
					return;
				}

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

				const scrollSpeed = this.prefersReducedMotion() ? 0 : 500;

				$( 'html, body' ).animate( {
					scrollTop: ( item.offset().top - 50 ) + 'px',
				}, scrollSpeed, function() {
					if ( ! item.hasClass( 'pp-accordion-item-active' ) ) {
						element.trigger( 'click' );
					}

					// The hash is stripped via replaceState above, so the browser never
					// moves focus to the fragment target. Do it here, or the next Tab
					// resumes from the top of the document.
					element.trigger( 'focus' );
				} );
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-advanced-accordion', AdvancedAccordionWidget );
	} );
} )( jQuery );
