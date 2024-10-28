( function ( $ ) {
	$( window ).on( 'elementor/frontend/init', () => {
		class InteractiveCircleWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						circleWrap: '.pp-circle-wrapper',
						activeItem: '.pp-circle-tab.active',
						circleContent: '.pp-circle-content',
						circleInfo: '.pp-circle-info',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$circleWrap: this.$element.find( selectors.circleWrap ),
					$activeItem: this.$element.find( selectors.activeItem ),
					$circleContent: this.$element.find( selectors.circleContent ),
					$circleInfo: this.$element.find( selectors.circleInfo ),
					$autoplayPause: 0,
				};
			}

			bindEvents() {
				const elementSettings = this.getElementSettings();
				let self = this,
					$circleWrap       = this.elements.$circleWrap,
					$activeItem       = this.elements.$activeItem,
					$circleContent    = this.elements.$circleContent,
					$animation        = elementSettings.circle_animation,
					$autoplay         = elementSettings.autoplay_tabs,
					$openOn           = elementSettings.open_on,
					$autoplayInterval = parseInt( elementSettings.autoplay_tabs_interval || 2000 ),
					$autoplayPause    = this.elements.$autoplayPause,
					$eventType        = 'mouseenter';

				if ( $activeItem.length > 1 ) {
					$activeItem.not( ':last' ).removeClass( 'active' );
					$activeItem.siblings( '.pp-circle-tab-content' ).removeClass( 'active' );
				}

				if ( 'none' !== $animation ) {
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

								let $animationClass = 'pp-circle-animation-' + $animation;

								$circleWrap.addClass( $animationClass );

								setTimeout( function () {
									_$activeItem.siblings( '.pp-circle-tab-content' ).addClass( 'active' );
								}, 1700 );
							}
						}
					});

					this.intersectionObserver.observe( $circleContent[0] );
				}

				if ( 'click' === $openOn ) {
					$eventType = 'click';
				}

				let $tabLinks = $circleWrap.find( '.pp-circle-tab' );

				// Keyboard accessibility
				this.$element.on( 'keyup', '.pp-circle-tab', function ( e ) {
					if ( e.which === 9 || e.which === 32 ) {
						$(this).trigger( $eventType );
					}
				} );

				$tabLinks.each( function ( element ) {
					$(this).on( $eventType, self.handleEvent( element, self ) );
					$(this).on( 'ppInteractiveCicle', self.handleEvent( element, self ) );
				} );

				if ( 'yes' === $autoplay ) {
					setInterval( function () {
						if ( $autoplayPause ) {
							setTimeout( function () {
								self.autoplayInteractiveCircle();
							}, 5000 );
						} else {
							self.autoplayInteractiveCircle();
						}
					}, $autoplayInterval );
				}

				this.stackOn = elementSettings.stack_on;

				if ( this.stackOn !== 'none' ) {
					this.stackIt();

					elementorFrontend.elements.$window.on('resize', this.stackIt.bind(this));
				}
			}

			stackIt() {
				const breakpoints = elementorFrontend.config.responsive.activeBreakpoints;
				let stackOn = breakpoints[this.stackOn].value;

				if ( window.innerWidth < stackOn ) {
					this.$element.addClass( 'pp-circle-stacked' );
				} else {
					this.$element.removeClass( 'pp-circle-stacked' );
				}
			}

			autoplayInteractiveCircle() {
				let $tabLinks   = this.elements.$circleWrap.find( '.pp-circle-tab' );
				let activeIndex = 0;

				$tabLinks.each(function ( index ) {
					if ( $(this).hasClass( 'active' ) ) {
						activeIndex = index + 1;
						activeIndex = activeIndex >= $tabLinks.length ? 0 : activeIndex;
					}
				});

				setTimeout( function () {
					$( $tabLinks[activeIndex] ).trigger( 'ppInteractiveCicle' );
				}, 300 );
			}

			handleEvent( element, self ) {
				let $tabLinks    = this.elements.$circleWrap.find( '.pp-circle-tab' ),
					$tabContents = this.elements.$circleWrap.find( '.pp-circle-tab-content' );

				return function ( event ) {
					let $element   = $(this),
						$activeTab = $(this).hasClass( 'active' );

					if ( $activeTab == false ) {
						$tabLinks.each(function ( tabLink ) {
							$(this).removeClass( 'active' );
						});

						$element.addClass( 'active' );

						$tabContents.each( function ( tabContent ) {
							$(this).removeClass( 'active' );
							if ( $(this).hasClass( $element.attr( 'id' ) ) ) {
								$(this).addClass( 'active' );
							}
						});
					}

					self.elements.$autoplayPause = event.originalEvent ? 1 : 0;
				};
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-interactive-circle', InteractiveCircleWidget );
	} );
} ) ( jQuery );