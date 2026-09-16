( function ( $ ) {
	$( window ).on( 'elementor/frontend/init', () => {
		class MarqueeWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' ),
					id          = this.$element.data( 'id' ),
					marquee     = this.$element.find( '.pp-marquee-' + id );

				return {
					$id:         id,
					$marquee:    marquee,
					$animation:  marquee.find( '.pp-marquee-animation' ),
					$vertical:   'yes' === marquee.data( 'v-direction' ),
					$prevWidth:  $( window ).width(),
					$prevHeight: $( window ).height(),
				};
			}

			bindEvents() {
				var self       = this,
					$marquee   = this.elements.$marquee,
					$animation = this.elements.$animation;

				// Reduced motion: the animation is off in CSS, so the track has to be
				// scrollable instead - otherwise everything past the first screenful is
				// clipped away with no way to reach it.
				this.reducedMotion = window.matchMedia
					? window.matchMedia( '(prefers-reduced-motion: reduce)' )
					: null;

				$marquee.toggleClass( 'pp-marquee-static', this.isReducedMotion() );

				if ( this.reducedMotion ) {
					this.onReducedMotionChange = function () {
						$marquee.toggleClass( 'pp-marquee-static', self.isReducedMotion() );
						self.adjustMarquee();
					};

					if ( this.reducedMotion.addEventListener ) {
						this.reducedMotion.addEventListener( 'change', this.onReducedMotionChange );
					} else if ( this.reducedMotion.addListener ) {
						this.reducedMotion.addListener( this.onReducedMotionChange );
					}
				}

				// Initial setup
				$animation.each( function () {
					$( this ).data( 'original-content', $( this ).html() );
					self.setPauseOnHover( $( this ) );
				} );

				$marquee.addClass( 'showing' );

				// Adjust marquee when in viewport
				this.intersectionObserver = elementorModules.utils.Scroll.scrollObserver( {
					callback: event => {
						if ( event.isInViewport ) {
							this.intersectionObserver.unobserve( $marquee[0] );

							self.waitForFonts().then( () => {
								self.adjustMarquee();
								$animation.css( 'animation-play-state', 'running' );
							} );
						}
					}
				});

				this.intersectionObserver.observe( $marquee[0] );

				// Slow-down on hover via Web Animations API
				var slowFactor = parseFloat( $marquee.attr( 'data-slow-factor' ) ) || 1;
				if ( slowFactor > 1 ) {
					$marquee.on( 'mouseenter.ppMarquee', function () {
						self.setPlaybackRate( 1 / slowFactor );
					} );
					$marquee.on( 'mouseleave.ppMarquee', function () {
						self.setPlaybackRate( 1 );
					} );
				}

				// Adjust on resize (debounced). Watch the axis that matters for
				// this marquee — width for horizontal, height for vertical.
				this.onResize = self.debounce( function () {
					var width  = $( window ).width();
					var height = $( window ).height();
					var changed = self.elements.$vertical
						? ( height !== self.elements.$prevHeight )
						: ( width !== self.elements.$prevWidth );
					if ( ! changed ) {
						return;
					}
					self.elements.$prevWidth  = width;
					self.elements.$prevHeight = height;
					self.adjustMarquee();
				}, 150 );

				$( window ).on( 'resize', this.onResize );
			}

			isReducedMotion() {
				return !! ( this.reducedMotion && this.reducedMotion.matches );
			}

			isStatic() {
				return this.elements.$marquee.hasClass( 'pp-marquee-static' );
			}

			setPlaybackRate( rate ) {
				var $animation = this.elements.$animation;
				$animation.each( function () {
					if ( this.getAnimations ) {
						this.getAnimations().forEach( function ( anim ) {
							anim.updatePlaybackRate( rate );
						} );
					}
				} );
			}

			debounce( fn, wait ) {
				var timeout;
				return function () {
					var context = this, args = arguments;
					clearTimeout( timeout );
					timeout = setTimeout( function () {
						fn.apply( context, args );
					}, wait );
				};
			}

			waitForFonts() {
				if ( document.fonts && document.fonts.ready ) {
					return document.fonts.ready;
				}
				return Promise.resolve();
			}

			setPauseOnHover( el ) {
				var val = el.css( '--pause-on-hover' );
				if ( val && 'true' === val.trim() ) {
					el.css( '--poh', 'paused' );
				} else {
					el.css( '--poh', 'running' );
				}
			}

			adjustMarquee() {
				var self   = this,
				$animation = this.elements.$animation,
				vertical   = this.elements.$vertical;

				var length = self.getInitialLength( $animation, vertical );

				if ( length instanceof Promise ) {
					length.then( handleLength );
				} else {
					handleLength( length );
				}

				function handleLength( length ) {
					if ( length ) {
						self.setValues( $animation, length, vertical );
						self.setDirection( $animation, length, vertical );
					}
					self.setPauseOnHover( $animation );
				}
			}

			checkIfLoaded( $img ) {
				return new Promise(( resolve ) => {
					if ( 0 !== $img[0].complete && $img[0].naturalWidth ) {
						resolve();
					} else {
						$img.on( 'load', function () {
							resolve();
						} );
					}
				});
			}

			getInitialLength( el, direction ) {
				var self = this;
				let $images = el.find( 'img:visible' );

				if ( 0 === $images.length ) {
					// If there are no images, calculate length immediately
					return calculateLength();
				} else {
					// If there are images, wait for them to load
					return new Promise( ( resolve ) => {
						Promise.race(
							$images.map( function () {
								return self.checkIfLoaded( $( this ) );
							} )
						).then( () => {
							resolve( calculateLength() );
						} );
					} );
				}

				function calculateLength() {
					let length = 0;
					let space = parseFloat( el.css( '--items-gap' ) );

					el.find( '.pp-marquee-item' ).each( function ( i, el ) {
						if ( direction ) {
							length += $( this ).height() + space;
						} else {
							length += $( this ).width() + space;
						}
					} );

					return length;
				}
			}

			setValues( el, length, direction ) {
				// Emptying the track throws away whatever the visitor is focused on.
				if ( el.children().length && el[0].contains( document.activeElement ) ) {
					return;
				}

				var total;

				if ( this.isStatic() ) {
					// Nothing is moving, so duplicates would only ever be clipped.
					total = 1;
				} else if ( direction ) {
					total = Math.ceil( el.parent().height() / length ) + 1;
				} else {
					total = Math.ceil( el.parent().width() / length ) + 1;
				}

				// Store original content
				if ( ! el.data( 'original-content' ) ) {
					el.data( 'original-content', el.html() );
				}

				el.empty();
				for ( let i = 0; i < total; i++ ) {
					var $clone = $( '<div>' ).html( el.data( 'original-content' ) ).contents();
					if ( i > 0 ) {
						$clone.attr( 'aria-hidden', 'true' );
						$clone.find( 'a[href], button, input, select, textarea, iframe, area[href], [tabindex], [contenteditable="true"], audio[controls], video[controls], details' ).attr( 'tabindex', '-1' );
					}
					el.append( $clone );
				}

				if ( direction ) {
					el.height( length * total );
				} else {
					el.width( length * total );
				}
				el.css( '--total', total );
				el.css( '--pp-est-speed', length / 100 );
			}

			setDirection( el, length, direction ) {
				if ( this.isStatic() ) {
					el.css( { 'margin-top': '', 'margin-left': '' } );
					return;
				}

				if ( direction ) {
					if ( el.css( '--direction' ) == -1 ) {
						el.css( 'margin-top', -1 * length + 'px' );
					}
				} else {
					if ( el.css('--direction') == -1 ) {
						el.css( 'margin-left', -1 * length + 'px' );
					}
				}
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-marquee', MarqueeWidget );
	} );
} ) ( jQuery );
