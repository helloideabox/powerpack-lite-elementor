(function ($) {
	$( window ).on( 'elementor/frontend/init', () => {
		class PpCarouselWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						swiperContainer: '.pp-swiper-slider',
						swiperSlide: '.swiper-slide',
					},
					slidesPerView: {
						widescreen: 3,
						desktop: 3,
						laptop: 3,
						tablet_extra: 3,
						tablet: 2,
						mobile_extra: 2,
						mobile: 1
					}
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' ),
					$swiperContainer = this.$element.find( selectors.swiperContainer );

				return {
					$swiperContainer: $swiperContainer,
					// Scoped to the slider so slides of any nested carousel are not counted as slides.
					$swiperSlide: $swiperContainer.find( selectors.swiperSlide ),
				};
			}

			getSliderSettings(prop) {
				const sliderSettings = ( undefined !== this.elements.$swiperContainer.data('slider-settings') ) ? this.elements.$swiperContainer.data('slider-settings') : '';

				if ( 'undefined' !== typeof prop && 'undefined' !== sliderSettings[prop] ) {
					return sliderSettings[prop];
				}

				return sliderSettings;
			}

			getSlidesCount() {
				return this.elements.$swiperSlide.length;
			}

			getEffect() {
				return this.getSliderSettings('effect');
			}

			getDeviceSlidesPerView(device) {
				const slidesPerViewKey = 'slides_per_view' + ('desktop' === device ? '' : '_' + device);
				return Math.min(this.getSlidesCount(), +this.getSliderSettings(slidesPerViewKey) || this.getSettings('slidesPerView')[device]);
			}

			getSlidesPerView(device) {
				if ('slide' === this.getEffect() || 'coverflow' === this.getEffect()) {
					return this.getDeviceSlidesPerView(device);
				}
				return 1;
			}

			getDeviceSlidesToScroll(device) {
				const slidesToScrollKey = 'slides_to_scroll' + ('desktop' === device ? '' : '_' + device);
				return Math.min(this.getSlidesCount(), +this.getElementSettings(slidesToScrollKey) || 1);
			}

			getSlidesToScroll(device) {
				if ('slide' === this.getEffect()) {
					return this.getDeviceSlidesToScroll(device);
				}
				return 1;
			}

			getSpaceBetween(device) {
				let propertyName = 'space_between';
				if (device && 'desktop' !== device) {
					propertyName += '_' + device;
				}
				// return this.getSliderSettings(propertyName) || '';
				return elementorFrontend.utils.controls.getResponsiveControlValue(this.getSliderSettings(), 'space_between', 'size', device) || 0;
			}

			getSwiperOptions() {
				const sliderSettings = this.getSliderSettings();
				// const swiperOptions = ( undefined !== this.elements.$swiperContainer.data('slider-settings') ) ? this.elements.$swiperContainer.data('slider-settings') : '';

				const swiperOptions = {
					grabCursor:                'yes' === sliderSettings.grab_cursor,
					// initialSlide:               this.getInitialSlide(),
					slidesPerView:              this.getSlidesPerView('desktop'),
					slidesPerGroup:             this.getSlidesToScroll('desktop'),
					spaceBetween:               this.getSpaceBetween(),
					loop:                       'yes' === sliderSettings.loop,
					centeredSlides:             'yes' === sliderSettings.centered_slides,
					speed:                      sliderSettings.speed,
					autoHeight:                 sliderSettings.auto_height,
					effect:                     this.getEffect(),
					watchSlidesVisibility:      true,
					watchSlidesProgress:        true,
					preventClicksPropagation:   false,
					slideToClickedSlide:        true,
					handleElementorBreakpoints: true,
				};

				if ( 'fade' === this.getEffect() ) {
					swiperOptions.fadeEffect = {
						crossFade: true,
					};
				}

				if ( sliderSettings.show_arrows ) {
					var prevEle = ( this.isEdit ) ? '.elementor-swiper-button-prev' : '.swiper-button-prev-' + this.getID();
					var nextEle = ( this.isEdit ) ? '.elementor-swiper-button-next' : '.swiper-button-next-' + this.getID();

					swiperOptions.navigation = {
						prevEl: prevEle,
						nextEl: nextEle,
					};
				}

				if ( sliderSettings.pagination ) {
					var paginationEle = ( this.isEdit ) ? '.swiper-pagination' : '.swiper-pagination-' + this.getID();

					swiperOptions.pagination = {
						el: paginationEle,
						type: sliderSettings.pagination,
						clickable: true
					};
				}

				/*
				 * Opt-in per widget, via `a11y` in data-slider-settings. The a11y module is what makes the
				 * div[role=button] arrows answer Enter and Space — a div does not synthesise
				 * a click from those the way a native button does — and what gives the
				 * pagination bullets a role and a tab stop. It also sets aria-disabled at
				 * either end.
				 *
				 * Swiper's keyboard module rides the same opt-in: it listens on the document,
				 * so enabling it everywhere would let an arrow key pressed anywhere on the
				 * page move every carousel currently on screen.
				 */
				if ( 'yes' === sliderSettings.a11y ) {
					const i18n = this.getI18n();

					swiperOptions.a11y = {
						enabled:                    true,
						prevSlideMessage:           i18n.prevSlide  || 'Previous slide',
						nextSlideMessage:           i18n.nextSlide  || 'Next slide',
						firstSlideMessage:          i18n.firstSlide || 'This is the first slide',
						lastSlideMessage:           i18n.lastSlide  || 'This is the last slide',
						paginationBulletMessage:    i18n.paginationBullet || 'Go to slide {{index}}',
						slideLabelMessage:          i18n.slideLabel || 'Slide {{index}} of {{slidesLength}}',
						slideRole:                  'group',
						itemRoleDescriptionMessage: i18n.slideRoleDescription || 'slide',
					};

					/*
					 * A widget can keep the strings and still turn this off with `keyboard: 'no'`.
					 * The module ignores preventDefault(), so a widget with its own arrow-key
					 * handling (the Image Slider's thumbnails) would move twice per key.
					 */
					if ( 'no' !== sliderSettings.keyboard ) {
						swiperOptions.keyboard = {
							enabled:        true,
							onlyInViewport: true,
						};
					}
				}

				if ('cube' !== this.getEffect()) {
					const breakpointsSettings = {},
					breakpoints = elementorFrontend.config.responsive.activeBreakpoints;

					Object.keys(breakpoints).forEach(breakpointName => {
						breakpointsSettings[breakpoints[breakpointName].value] = {
							slidesPerView: this.getSlidesPerView(breakpointName),
							slidesPerGroup: this.getSlidesToScroll(breakpointName),
						};

						if ( this.getSpaceBetween(breakpointName) ) {
							breakpointsSettings[breakpoints[breakpointName].value].spaceBetween = this.getSpaceBetween(breakpointName);
						}
					});

					swiperOptions.breakpoints = breakpointsSettings;
				}

				if ( !this.isEdit && sliderSettings.autoplay && ! this.isMotionReduced() ) {
					swiperOptions.autoplay = {
						delay: sliderSettings.autoplay_speed,
						disableOnInteraction: !!sliderSettings.pause_on_interaction
					};
				}

				return swiperOptions;
			}

			getI18n() {
				return ( 'undefined' !== typeof ppCarouselScript && ppCarouselScript.i18n )
					? ppCarouselScript.i18n
					: {};
			}

			prefersReducedMotion() {
				return !! ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches );
			}

			/**
			 * Autoplay and slide animation are decorative, so an explicit system-level
			 * request for reduced motion is always honoured — there is no author opt-out.
			 */
			isMotionReduced() {
				return this.prefersReducedMotion();
			}

			bindEvents() {
				this.initSlider();
			}

			/**
			 * Slides that are off screen have to leave the accessibility tree and the tab order
			 * together. Keying off the active index alone hid slides that were plainly visible
			 * whenever slidesPerView was above one — with the default of three, two of the three
			 * members on screen were erased for screen readers while their links stayed tabbable.
			 * watchSlidesProgress already maintains the class this reads.
			 */
			syncSlideVisibility() {
				const $slides = this.elements.$swiperContainer.find( '.swiper-slide' );

				// Effects such as cube never set the visibility class. Hiding every slide
				// because none is marked visible would be far worse than hiding none.
				const hasVisibilityClass = $slides.filter( '.swiper-slide-visible' ).length > 0;

				$slides.each( function() {
					// A slide is never itself a control, so it should hold no tabindex at all.
					this.removeAttribute( 'tabindex' );

					if ( ! hasVisibilityClass || this.classList.contains( 'swiper-slide-visible' ) ) {
						this.removeAttribute( 'aria-hidden' );
						this.removeAttribute( 'inert' );
					} else {
						this.setAttribute( 'aria-hidden', 'true' );
						// inert is what actually takes the links inside out of the tab order;
						// aria-hidden on its own is what created the mismatch.
						this.setAttribute( 'inert', '' );
					}
				} );
			}

			/**
			 * Announces the slide position, but only where the user asked for the move.
			 */
			announceSlidePosition() {
				if ( ! this.userNavigated ) {
					return;
				}

				this.userNavigated = false;

				const statusFormat = this.getI18n().slideStatus || 'Showing Slide %1$s of %2$s';

				let statusText = statusFormat
					.replace( '%1$s', this.swiper.realIndex + 1 )
					.replace( '%2$s', this.getSlidesCount() );

				/*
				 * Widgets that name their slides say which one is showing: the Content
				 * Ticker prints data-image-label from the item title and renders the first
				 * slide's status with that label already appended — without this the label
				 * would disappear on the first move. Carousels with nothing meaningful to
				 * name announce the position alone.
				 */
				const slideLabel = this.elements.$swiperContainer
					.find( '.swiper-slide-active' )
					.attr( 'data-image-label' );

				if ( slideLabel ) {
					statusText += ': ' + slideLabel;
				}

				/*
				 * [aria-live] and not the class alone: .pp-screen-only is a plain visually
				 * hidden marker, and slides carry their own — the "(opens in a new tab)"
				 * note on external links — which a bare descendant match overwrites with
				 * the slide count.
				 */
				this.elements.$swiperContainer.find( '.pp-screen-only[aria-live]' ).text( statusText );
			}

			async initSlider() {
				const elementSettings = this.getElementSettings();

				const Swiper = elementorFrontend.utils.swiper;
    			this.swiper = await new Swiper(this.elements.$swiperContainer, this.getSwiperOptions());

				this.thumbsNav();

				/*
				 * params.autoplay.enabled, not the widget setting: it is true only where the
				 * autoplay actually started, so never in the editor and never once reduced
				 * motion has held it back. Swiper's autoplay.start() has no such guard, so a
				 * resume bound on a slider that is not autoplaying would start one.
				 */
				const autoplayStarted = !! ( this.swiper.params.autoplay && this.swiper.params.autoplay.enabled );

				if ( autoplayStarted && 'yes' === elementSettings.pause_on_hover ) {
					this.togglePauseOnHover(true);
				}

				if ( autoplayStarted ) {
					this.togglePauseOnFocus(true);
				}

				if ( 'yes' === elementSettings.equal_height_boxes ) {
					this.setEqualHeight();
				}

				this.syncSlideVisibility();

				// Autoplay must not speak. A position announcement answers the user having
				// navigated; on a timer it just interrupts whatever they are reading, every
				// few seconds, for as long as the page is open.
				this.userNavigated = false;

				/*
				 * Swiper emits navigationNext/navigationPrev only after slideNext() has run, by
				 * which time slideChange has already come and gone, so a flag set from those
				 * events was always one move behind and the first arrow press announced
				 * nothing. paginationUpdate was worse: it fires on autoplay moves too. Marking
				 * the intent on the way down, in the capture phase, gets there first.
				 */
				const controls = '.pp-slider-arrow, .swiper-pagination-bullet';

				const markNavigated = ( e ) => {
					const onControl = e.target.closest && e.target.closest( controls );

					if ( 'keydown' === e.type ) {
						const isSliderArrowKey = ( 'ArrowLeft' === e.key || 'ArrowRight' === e.key ) && e.target === this.elements.$swiperContainer[0];
						const isActivation     = onControl && ( 'Enter' === e.key || ' ' === e.key );

						if ( ! isSliderArrowKey && ! isActivation ) {
							return;
						}
					} else if ( ! onControl ) {
						return;
					}

					this.userNavigated = true;
				};

				this.$element[0].addEventListener( 'pointerdown', markNavigated, true );
				this.$element[0].addEventListener( 'keydown', markNavigated, true );

				// A swipe is deliberate too, and touchEnd lands before the transition.
				this.swiper.on( 'touchEnd', () => {
					this.userNavigated = true;
				} );

				/*
				 * The status region belongs to the widget, so take its id from the DOM rather
				 * than rebuilding it here: a widget that renders no status then leaves its
				 * arrows alone instead of pointing them at an element that does not exist.
				 */
				const statusId = this.elements.$swiperContainer.find( '.pp-screen-only[aria-live]' ).attr( 'id' );

				if ( statusId ) {
					/*
					 * Swiper points the arrows' aria-controls at the slides wrapper, which is
					 * correct and stays; the status region only describes the result. Swiper
					 * also makes that wrapper a polite live region, which would read the new
					 * slide a second time on top of the status announcement.
					 */
					this.$element.find(
						'.elementor-swiper-button-prev, .elementor-swiper-button-next, ' +
						'.swiper-button-prev-' + this.getID() + ', .swiper-button-next-' + this.getID()
					).attr( 'aria-describedby', statusId );

					this.swiper.$wrapperEl.attr( 'aria-live', 'off' );
				}

				this.swiper.on( 'slideChange', function () {
					if ( 'yes' === elementSettings.equal_height_boxes ) {
						this.setEqualHeight();
					}

					this.announceSlidePosition();
				}.bind( this ) );

				// Swiper settles which slides are on screen only once the movement is over.
				this.swiper.on( 'slideChangeTransitionEnd resize breakpoint', () => {
					this.syncSlideVisibility();
				} );

				// Keyboard navigation. Scoped to the container itself: while it was bound to
				// every descendant, arrow keys pressed inside a slide's own links and text
				// moved the whole carousel out from under the user.
				this.elements.$swiperContainer.on( 'keydown', function ( e ) {
					if ( e.target !== e.currentTarget ) { return; }
					if ( 'ArrowRight' === e.key ) { this.swiper.slideNext(); }
					if ( 'ArrowLeft'  === e.key ) { this.swiper.slidePrev(); }
				}.bind( this ) );

				// No Enter/Space handler on the arrows here: Swiper's a11y module, on by default
				// in Elementor's Swiper 8, already moves the slider from those keys. Firing a
				// click as well moved it twice per press wherever the loop was off.

				this.initFancybox();
			}

			thumbsNav() {
				const elementSettings = this.getElementSettings(),
					thumbsNav         = this.$element.find( '.pp-image-slider-thumb-item-wrap' ),
					swiper            = this.swiper;

				if ( 'slideshow' === elementSettings.skin ) {
					thumbsNav.removeClass('pp-active-slide');
					thumbsNav.eq(0).addClass('pp-active-slide');

					swiper.on( 'slideChange', function () {
						const activeSlide = ( 'yes' === elementSettings.infinite_loop ) ? swiper.realIndex : swiper.activeIndex;

						thumbsNav.removeClass('pp-active-slide');
						thumbsNav.eq( activeSlide ).addClass('pp-active-slide');
					});

					const offset = elementSettings.infinite_loop ? 1 : 0;

					$(thumbsNav).on( 'click', function() {
						swiper.slideTo( $(this).index() + offset, 500 );
					});
				}
			}

			togglePauseOnHover(toggleOn) {
				if (toggleOn) {
					this.$element.on({
						mouseenter: () => {
							if (this.swiper && this.swiper.autoplay && this.swiper.autoplay.running) {
								this.swiper.autoplay.stop();
							}
						},
						mouseleave: () => {
							// Focus inside still holds the pause. Resuming here would move the slide
							// on and make it inert, dropping that focus to the body.
							if ( this.$element[0].contains( document.activeElement ) ) {
								return;
							}

							if (this.swiper && this.swiper.autoplay && !this.swiper.autoplay.running) {
								this.swiper.autoplay.start();
							}
						}
					});
				} else {
					this.$element.off('mouseenter mouseleave');
				}
			}

			togglePauseOnFocus(toggleOn) {
				if (toggleOn) {
					this.$element.on({
						focusin: () => {
							if (this.swiper && this.swiper.autoplay && this.swiper.autoplay.running) {
								this.swiper.autoplay.stop();
							}
						},
						focusout: ( e ) => {
							// Moving between two controls inside the widget is not leaving it.
							if ( e.relatedTarget && this.$element[0].contains( e.relatedTarget ) ) {
								return;
							}

							// A pointer still resting on the widget holds the hover pause.
							if ( 'yes' === this.getElementSettings( 'pause_on_hover' ) && this.$element[0].matches( ':hover' ) ) {
								return;
							}

							if (this.swiper && this.swiper.autoplay && !this.swiper.autoplay.running) {
								this.swiper.autoplay.start();
							}
						}
					});
				} else {
					this.$element.off('focusin focusout');
				}
			}

			setEqualHeight() {
				const swiperOptions = this.getSwiperOptions(),
					effect          = swiperOptions.effect,
					activeSlide     = this.elements.$swiperContainer.find( '.swiper-slide-visible' );

				let maxHeight = -1;

				activeSlide.each( function() {
					let containerHeight = $(this).outerHeight();

					if ( maxHeight < containerHeight ) {
						maxHeight = containerHeight;
					}
				});

				activeSlide.each( function() {
					if ( 'coverflow' === effect ) {
						$(this).css({ height: maxHeight } );
					} else {
						$(this).animate({ height: maxHeight }, { duration: 200, easing: 'linear' });
					}
				});
			}

			initFancybox() {
				const sliderId       = this.elements.$swiperContainer.attr( 'id' ),
					fancyboxSettings = this.elements.$swiperContainer.data('fancybox-settings'),
					lightboxSelector = '.pp-swiper-slide:not(.swiper-slide-duplicate) .pp-image-slider-slide-link[data-fancybox="' + sliderId + '"]';
	
				if ( $(lightboxSelector).length > 0 ) {
					$(lightboxSelector).fancybox( fancyboxSettings );
				}
			}
		}

		const widgets = {
			'business-reviews':     [ 'default', 'classic', 'card' ],
			'content-ticker':       '',
			'image-slider':         '',
			'info-box-carousel':    '',
			'logo-carousel':        '',
			'magazine-slider':      '',
			'team-member-carousel': '',
		}

		$.each( widgets, function( widget, skin ) {
			if ( 'object' ===  typeof skin ) {
				$.each( skin, function( index, wSkin ) {
					elementorFrontend.elementsHandler.attachHandler( 'pp-' + widget, PpCarouselWidget, wSkin );
				});
			} else {
				elementorFrontend.elementsHandler.attachHandler( 'pp-' + widget, PpCarouselWidget );
			}
		});
	} );
})(jQuery);