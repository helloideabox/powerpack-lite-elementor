( function ( $ ) {
	$( window ).on( 'elementor/frontend/init', () => {
		class InteractiveCircleWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						circleWrap: '.pp-circle-wrapper',
						tab: '.pp-circle-tab',
						tabContent: '.pp-circle-tab-content',
						circleContent: '.pp-circle-content',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$circleWrap: this.$element.find( selectors.circleWrap ),
					$tabs: this.$element.find( selectors.tab ),
					$tabContents: this.$element.find( selectors.tabContent ),
					$circleContent: this.$element.find( selectors.circleContent ),
				};
			}

			prefersReducedMotion() {
				return !! ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches );
			}

			bindEvents() {
				const elementSettings = this.getElementSettings(),
					$circleWrap       = this.elements.$circleWrap,
					$circleContent    = this.elements.$circleContent,
					animation         = elementSettings.circle_animation,
					reduceMotion      = this.prefersReducedMotion(),
					eventType         = 'click' === elementSettings.open_on ? 'click' : 'mouseenter';

				this.syncActiveItem();

				if ( 'none' !== animation && ! reduceMotion && $circleContent.length ) {
					let _$activeItem = $circleContent;
					_$activeItem.siblings( '.pp-circle-tab-content' ).removeClass( 'active' );
					$( 'body' ).scroll(function () {
						if ( $circleWrap.isInViewport() ) {
							$( window ).trigger( 'resize' );
						}
					});

					this.intersectionObserver = elementorModules.utils.Scroll.scrollObserver( {
						callback: event => {
							if ( event.isInViewport ) {
								this.intersectionObserver.unobserve( $circleContent[0] );

								let $animationClass = 'pp-circle-animation-' + animation;

								$circleWrap.addClass( $animationClass );

								setTimeout( function () {
									_$activeItem.siblings( '.pp-circle-tab-content' ).addClass( 'active' );
								}, 1700 );
							}
						}
					});

					this.intersectionObserver.observe( $circleContent[0] );
				}

				this.elements.$tabs.on( eventType + ' ppInteractiveCicle', ( event ) => {
					this.activateTab( $( event.currentTarget ) );
				} );

				this.elements.$tabs.on( 'keydown', ( event ) => this.onTabKeydown( event ) );

				// Autoplay pauses while the pointer or keyboard focus is inside the widget (WCAG 2.2.2).
				if ( 'yes' === elementSettings.autoplay_tabs && ! reduceMotion ) {
					this.autoplayInterval = parseInt( elementSettings.autoplay_tabs_interval, 10 ) || 2000;

					this.$element
						.on( 'mouseenter.ppCircle', () => {
							this.isHovered = true;
							this.stopAutoplay();
						} )
						.on( 'mouseleave.ppCircle', () => {
							this.isHovered = false;
							this.startAutoplay();
						} )
						.on( 'focusin.ppCircle', () => {
							this.hasFocus = true;
							this.stopAutoplay();
						} )
						.on( 'focusout.ppCircle', ( event ) => {
							if ( ! this.$element[0].contains( event.relatedTarget ) ) {
								this.hasFocus = false;
								this.startAutoplay();
							}
						} );

					this.startAutoplay();
				}

				this.stackOn = elementSettings.stack_on;

				if ( this.stackOn !== 'none' ) {
					this.onResize = this.stackIt.bind( this );
					this.stackIt();

					elementorFrontend.elements.$window.on( 'resize', this.onResize );
				}
			}

			unbindEvents() {
				this.stopAutoplay();
				this.$element.off( '.ppCircle' );
				this.elements.$tabs.off( 'click mouseenter keydown ppInteractiveCicle' );

				if ( this.onResize ) {
					elementorFrontend.elements.$window.off( 'resize', this.onResize );
				}
			}

			stackIt() {
				const breakpoint = elementorFrontend.config.responsive.activeBreakpoints[ this.stackOn ];

				if ( ! breakpoint ) {
					return;
				}

				this.isStacked = window.innerWidth < breakpoint.value;
				this.$element.toggleClass( 'pp-circle-stacked', this.isStacked );
				this.updateExpandedState();
			}

			startAutoplay() {
				if ( this.isHovered || this.hasFocus ) {
					return;
				}

				this.stopAutoplay();
				this.autoplayTimer = setInterval( () => this.autoplayInteractiveCircle(), this.autoplayInterval );
			}

			stopAutoplay() {
				if ( this.autoplayTimer ) {
					clearInterval( this.autoplayTimer );
					this.autoplayTimer = null;
				}
			}

			autoplayInteractiveCircle() {
				const $tabs     = this.elements.$tabs,
					activeIndex = $tabs.index( $tabs.filter( '.active' ).first() );

				$tabs.eq( ( activeIndex + 1 ) % $tabs.length ).trigger( 'ppInteractiveCicle' );
			}

			onTabKeydown( event ) {
				if ( event.altKey || event.ctrlKey || event.metaKey ) {
					return;
				}

				const $tabs = this.elements.$tabs,
					index   = $tabs.index( event.currentTarget ),
					isRtl   = !! elementorFrontend.config.is_rtl;
				let next;

				switch ( event.key ) {
					case 'Enter':
					case ' ':
					case 'Spacebar':
						event.preventDefault();
						$( event.currentTarget ).trigger( 'ppInteractiveCicle' );
						return;
					case 'ArrowDown':
						next = index + 1;
						break;
					case 'ArrowUp':
						next = index - 1;
						break;
					case 'ArrowRight':
						next = isRtl ? index - 1 : index + 1;
						break;
					case 'ArrowLeft':
						next = isRtl ? index + 1 : index - 1;
						break;
					case 'Home':
						next = 0;
						break;
					case 'End':
						next = $tabs.length - 1;
						break;
					default:
						return;
				}

				event.preventDefault();
				$tabs.eq( ( next + $tabs.length ) % $tabs.length ).trigger( 'focus' );
			}

			// Keeps a single open item when the markup arrives with several.
			syncActiveItem() {
				const $active = this.elements.$tabs.filter( '.active' );

				if ( $active.length > 1 ) {
					this.activateTab( $active.last() );
				} else {
					this.updateExpandedState();
				}
			}

			activateTab( $tab ) {
				this.elements.$tabs.not( $tab ).removeClass( 'active' );
				$tab.addClass( 'active' );

				this.elements.$tabContents
					.removeClass( 'active' )
					.filter( '[data-index="' + $tab.attr( 'data-index' ) + '"]' )
					.addClass( 'active' );

				this.updateExpandedState();
			}

			// A stacked layout shows every panel, so every tab reports expanded.
			updateExpandedState() {
				const isStacked = !! this.isStacked;

				this.elements.$tabs.each( function () {
					const $tab = $( this );
					$tab.attr( 'aria-expanded', isStacked || $tab.hasClass( 'active' ) ? 'true' : 'false' );
				} );
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-interactive-circle', InteractiveCircleWidget );
	} );
} ) ( jQuery );
