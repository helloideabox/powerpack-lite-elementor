(function ($) {
	$(window).on( 'elementor/frontend/init', () => {
		class ImageAccordionWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						accordion: '.pp-image-accordion',
						item: '.pp-image-accordion-item',
						trigger: '.pp-image-accordion-trigger',
						content: '.pp-image-accordion-content-wrap',
						containerLink: 'a.pp-image-accordion-overlay',
					},
				};
			}

			getDefaultElements() {
				const selectors  = this.getSettings( 'selectors' ),
					$accordion = this.$element.find( selectors.accordion ).first();

				return {
					$accordion: $accordion,
					$item: $accordion.children( selectors.item ),
				};
			}

			bindEvents() {
				const action    = this.getElementSettings( 'accordion_action' ),
					selectors = this.getSettings( 'selectors' );

				this.unbindEvents();

				const ns = this.eventNamespace;

				// Enter/Space on the trigger, in both modes.
				this.elements.$item.on( 'keydown' + ns, selectors.trigger, ( e ) => {
					if ( 'Enter' !== e.key && ' ' !== e.key && 'Spacebar' !== e.key ) {
						return;
					}

					e.preventDefault(); // Space would scroll the page.
					this.activate( $( e.currentTarget ) );
				} );

				if ( 'on-hover' === action ) {
					this.elements.$item.on( 'mouseenter' + ns, ( e ) => {
						const focused = document.activeElement;

						// Switching would hide the link a keyboard user is on in another item, and the
						// browser then drops focus to <body>. The :hover styles still preview this item.
						if ( focused && focused.closest( selectors.content ) && this.elements.$accordion[0].contains( focused ) && ! e.currentTarget.contains( focused ) ) {
							return;
						}

						this.activate( $( e.currentTarget ) );
					} );

					// Focus is the keyboard's hover.
					this.elements.$item.on( 'focusin' + ns, ( e ) => this.activate( $( e.currentTarget ) ) );

					this.elements.$item.on( 'mouseleave' + ns, () => {
						// Collapsing would hide the link a keyboard user is standing on.
						if ( ! this.hasFocusWithin() ) {
							this.resetAll();
						}
					} );

					this.elements.$accordion.on( 'focusout' + ns, ( e ) => {
						const accordion = this.elements.$accordion[0];

						if ( ! accordion.contains( e.relatedTarget ) && ! accordion.matches( ':hover' ) ) {
							this.resetAll();
						}
					} );
				} else if ( 'on-click' === action ) {
					// currentTarget, not target: a click on the open item's text must not
					// activate the text's parent element.
					this.elements.$item.on( 'click' + ns, ( e ) => this.activate( $( e.currentTarget ) ) );

					// A container link has no trigger to press, so its item opens as it takes focus.
					this.elements.$item.on( 'focusin' + ns, selectors.containerLink, ( e ) => {
						this.activate( $( e.delegateTarget ) );
					} );

					if ( 'yes' !== this.getElementSettings( 'disable_body_click' ) ) {
						// Bound once, and checks containment instead of stopping propagation,
						// so delegated document listeners (lightbox) still see clicks inside.
						$( document.body ).on( 'click' + ns, ( e ) => {
							if ( ! this.elements.$accordion[0].contains( e.target ) ) {
								this.resetAll();
							}
						} );
					}
				}

				this.observeContent();
			}

			unbindEvents() {
				this.eventNamespace = '.ppImageAccordion-' + this.getID();

				this.elements.$item.off( this.eventNamespace );
				this.elements.$accordion.off( this.eventNamespace );
				$( document.body ).off( this.eventNamespace );

				if ( this.resizeObserver ) {
					this.resizeObserver.disconnect();
					this.resizeObserver = null;
				}
			}

			/**
			 * Re-checks the open item whenever its content or the space for it changes size:
			 * text zoom, a viewport change, or the flex transition as the item opens.
			 */
			observeContent() {
				if ( 'undefined' === typeof ResizeObserver || ! this.elements.$accordion.length ) {
					return;
				}

				const selectors = this.getSettings( 'selectors' );

				this.resizeObserver = new ResizeObserver( () => {
					this.updateScroll( this.elements.$item.filter( '.pp-image-accordion-active' ) );
				} );

				this.elements.$item.find( selectors.content ).each( ( i, wrap ) => {
					this.resizeObserver.observe( wrap );

					Array.from( wrap.children ).forEach( ( child ) => this.resizeObserver.observe( child ) );
				} );
			}

			hasFocusWithin() {
				return this.elements.$accordion[0].contains( document.activeElement );
			}

			/**
			 * Lets the open item's content scroll only while it is taller than the item, so
			 * enlarged text is never clipped (WCAG 1.4.4) and the normal case keeps its
			 * overflow visible, where nothing clips the button's focus ring or hover effect.
			 */
			updateScroll( $item ) {
				const wrap = $item.find( this.getSettings( 'selectors' ).content ).get( 0 );

				if ( ! wrap ) {
					return;
				}

				// offsetHeight ignores the slide-in transforms, which scrollHeight would count.
				const contentHeight = Array.from( wrap.children ).reduce( ( total, child ) => {
					const style = window.getComputedStyle( child );

					return total + child.offsetHeight + parseFloat( style.marginTop ) + parseFloat( style.marginBottom );
				}, 0 );

				const $wrap     = $( wrap ),
					overflows = contentHeight > wrap.clientHeight + 1;

				$wrap.toggleClass( 'pp-image-accordion-content-scroll', overflows );

				// A scroll box with nothing focusable inside is otherwise out of keyboard reach.
				if ( overflows ) {
					$wrap.attr( 'tabindex', '0' );
				} else {
					$wrap.removeAttr( 'tabindex' );
				}
			}

			activate( $target ) {
				const selectors = this.getSettings( 'selectors' ),
					$item     = $target.closest( selectors.item );

				if ( $item.hasClass( 'pp-image-accordion-active' ) ) {
					return;
				}

				this.resetAll();

				$item.addClass( 'pp-image-accordion-active' ).css( 'flex', '3' );
				$item.find( selectors.content ).addClass( 'pp-image-accordion-content-active' );
				$item.find( selectors.trigger ).attr( 'aria-expanded', 'true' );

				this.updateScroll( $item );
			}

			resetAll() {
				const selectors = this.getSettings( 'selectors' );

				this.elements.$item.css( 'flex', '1' ).removeClass( 'pp-image-accordion-active' );
				this.elements.$item.find( selectors.content )
					.removeClass( 'pp-image-accordion-content-active pp-image-accordion-content-scroll' )
					.removeAttr( 'tabindex' );
				this.elements.$item.find( selectors.trigger ).attr( 'aria-expanded', 'false' );
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-image-accordion', ImageAccordionWidget );
	} );
})(jQuery);
