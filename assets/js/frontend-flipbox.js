(function ($) {
	$( window ).on( 'elementor/frontend/init', () => {
		let instanceCount = 0;

		class FlipboxWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						container: '.pp-flipbox-container',
						card: '.pp-flipbox-flip-card',
						front: '.pp-flipbox-front',
						back: '.pp-flipbox-back',
						trigger: '.pp-flipbox-flip-trigger',
						openTrigger: '.pp-flipbox-flip-trigger--open',
						closeTrigger: '.pp-flipbox-flip-trigger--close',
						interactive: 'a[href], button, input, select, textarea, [role="button"]',
					},
					classes: {
						flipped: 'pp-flipbox--flipped',
						dismissed: 'pp-flipbox--dismissed',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' ),
					$container = this.$element.find( selectors.container ).first(),
					$card = $container.children( selectors.card ),
					$front = $card.children( selectors.front ),
					$back = $card.children( selectors.back );

				return {
					$container: $container,
					$front: $front,
					$back: $back,
					$openTrigger: $front.children( selectors.openTrigger ),
					$closeTrigger: $back.children( selectors.closeTrigger ),
				};
			}

			bindEvents() {
				const selectors = this.getSettings( 'selectors' ),
					classes = this.getSettings( 'classes' ),
					$container = this.elements.$container;

				if ( ! $container.length ) {
					return;
				}

				this.eventNamespace = '.ppFlipbox' + ( ++instanceCount );

				$container.on( 'click', selectors.trigger, ( e ) => {
					e.preventDefault();
					this.toggle();
				} );

				$container.on( 'keydown', ( e ) => {
					if ( $( e.target ).is( selectors.trigger ) && ( 'Enter' === e.key || ' ' === e.key || 'Spacebar' === e.key ) ) {
						e.preventDefault();
						this.toggle();
						return;
					}

					if ( 'Escape' !== e.key && 'Esc' !== e.key ) {
						return;
					}

					if ( this.isOpen() ) {
						this.close( true );
					} else if ( $container[0].matches( ':hover' ) ) {
						this.close( false );
					}
				} );

				// Touch: there is no hover, so a tap on the card itself flips it.
				$container.on( 'click', ( e ) => {
					if ( this.canHover() || $( e.target ).closest( selectors.interactive ).length ) {
						return;
					}

					this.setState( ! this.isOpen() );
				} );

				// Focus reaching a link on a hover-revealed back face keeps the card open once the pointer leaves.
				$container.on( 'focusin', selectors.back, () => {
					if ( ! this.isOpen() ) {
						this.setState( true );
					}
				} );

				$container.on( 'focusout', () => {
					if ( ! this.isOpen() ) {
						return;
					}

					setTimeout( () => {
						if ( this.isOpen() && ! $container[0].contains( document.activeElement ) && ! $container[0].matches( ':hover' ) ) {
							this.setState( false );
						}
					}, 0 );
				} );

				$container.on( 'mouseleave', () => {
					$container.removeClass( classes.dismissed );
				} );

				$( document ).on( 'click' + this.eventNamespace, ( e ) => {
					if ( this.isOpen() && ! $container[0].contains( e.target ) ) {
						this.setState( false );
					}
				} );

				// WCAG 1.4.13: content revealed by hover must be dismissable without moving the pointer.
				$( document ).on( 'keydown' + this.eventNamespace, ( e ) => {
					if ( ( 'Escape' === e.key || 'Esc' === e.key ) && ! $container[0].contains( e.target ) && $container[0].matches( ':hover' ) ) {
						this.close( false );
					}
				} );
			}

			unbindEvents() {
				this.elements.$container.off();

				if ( this.eventNamespace ) {
					$( document ).off( this.eventNamespace );
				}
			}

			canHover() {
				return ! window.matchMedia || window.matchMedia( '(hover: hover)' ).matches;
			}

			isOpen() {
				return this.elements.$container.hasClass( this.getSettings( 'classes.flipped' ) );
			}

			setState( open ) {
				this.elements.$container.toggleClass( this.getSettings( 'classes.flipped' ), open );

				// The front stays in the DOM while the card is open (rotated, slid or faded away),
				// so take it out of the tab order and the accessibility tree.
				if ( this.elements.$front.length ) {
					this.elements.$front[0].inert = open;
				}

				this.elements.$openTrigger.add( this.elements.$closeTrigger ).attr( 'aria-expanded', open ? 'true' : 'false' );
			}

			open( moveFocus ) {
				const classes = this.getSettings( 'classes' );

				this.elements.$container.removeClass( classes.dismissed );

				// Reveal the back before moving focus into it, and only then make the front inert:
				// inerting the front while its trigger still holds focus would drop focus to <body>.
				this.elements.$container.addClass( classes.flipped );

				if ( moveFocus ) {
					this.elements.$closeTrigger.trigger( 'focus' );
				}

				this.setState( true );
			}

			close( restoreFocus ) {
				const $container = this.elements.$container,
					hadFocus = $container[0].contains( document.activeElement );

				this.setState( false );

				// A pointer still resting on the card would otherwise keep it open through :hover.
				if ( $container[0].matches( ':hover' ) ) {
					$container.addClass( this.getSettings( 'classes.dismissed' ) );
				}

				if ( restoreFocus && hadFocus ) {
					this.elements.$openTrigger.trigger( 'focus' );
				}
			}

			toggle() {
				if ( this.isOpen() ) {
					this.close( true );
				} else {
					this.open( true );
				}
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-flipbox', FlipboxWidget );
	} );
})(jQuery);
