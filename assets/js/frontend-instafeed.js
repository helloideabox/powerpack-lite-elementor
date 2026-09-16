(function ($) {
	$( window ).on( 'elementor/frontend/init', () => {
		class InstafeedWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						swiperContainer: '.pp-swiper-slider',
						swiperSlide: '.swiper-slide',
						feed: '.pp-instafeed-grid',
					},
					slidesPerView: {
						widescreen: 3,
						desktop: 3,
						laptop: 3,
						tablet_extra: 3,
						tablet: 2,
						mobile_extra: 2,
						mobile: 1
					},
					effect: 'slide'
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$swiperContainer: this.$element.find( selectors.swiperContainer ),
					$swiperSlide: this.$element.find( selectors.swiperSlide ),
					$feed: this.$element.find( selectors.feed ),
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
				return ( this.getSliderSettings('effect') || this.getSettings('effect') );
			}

			getDeviceSlidesPerView(device) {
				const slidesPerViewKey = 'slides_per_view' + ('desktop' === device ? '' : '_' + device);
				return Math.min(this.getSlidesCount(), +this.getSliderSettings(slidesPerViewKey) || this.getSettings('slidesPerView')[device]);
			}

			getSlidesPerView(device) {
				if ('slide' === this.getEffect()) {
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
				return elementorFrontend.utils.controls.getResponsiveControlValue(this.getSliderSettings(), 'space_between', 'size', device) || 0;
			}

			getSwiperOptions() {
				const sliderSettings = this.getSliderSettings();

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
					handleElementorBreakpoints: true
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

				/*
				 * Swiper 8 runs its a11y module whether or not it is asked to, in its own
				 * hardcoded English. The widget opts in through `a11y` in data-slider-settings
				 * so the arrows, bullets and slides are named from the translated strings.
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

					swiperOptions.keyboard = {
						enabled:        true,
						onlyInViewport: true,
					};
				}

				// Autoplay is decorative, so a system-level request for reduced motion always wins.
				if ( !this.isEdit && sliderSettings.autoplay && ! this.isMotionReduced() ) {
					swiperOptions.autoplay = {
						delay: sliderSettings.autoplay_speed,
						disableOnInteraction: !!sliderSettings.pause_on_interaction
					};
				}

				return swiperOptions;
			}

			getI18n() {
				return ( 'undefined' !== typeof ppInstafeedScript && ppInstafeedScript.i18n )
					? ppInstafeedScript.i18n
					: {};
			}

			isMotionReduced() {
				return !! ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches );
			}

			bindEvents() {
				const elementSettings = this.getElementSettings();
				const layout = elementSettings.feed_layout;

				if ( 'carousel' === layout ) {
					this.initSlider();
				}

				if ( 'masonry' === layout ) {
					this.initMasonryLayout();
				}
			}

			initMasonryLayout() {
				const self = this;

				this.masonry = new PPMasonry( {
					container: this.elements.$feed[0],
					itemSelector: '.pp-feed-item',
				} );

				this.masonry.observeResize();

				// Measure once images are loaded, then keep it in sync as (lazy) images resolve.
				this.elements.$feed.imagesLoaded( function () {
					self.masonry.setReady();
					self.masonry.layout();
				} );

				this.elements.$feed.find('img').on('load', function () {
					self.masonry.layoutIfReady();
				} );
			}

			onElementChange() {
				if ( this.masonry && 'masonry' === this.getElementSettings('feed_layout') ) {
					setTimeout( () => this.masonry.layout() );
				}
			}

			onDestroy() {
				if ( this.masonry ) {
					this.masonry.destroy();
				}

				if ( this.onNavigationIntent ) {
					this.$element[0].removeEventListener( 'pointerdown', this.onNavigationIntent, true );
					this.$element[0].removeEventListener( 'keydown', this.onNavigationIntent, true );
				}

				if ( this.swiper ) {
					this.togglePauseOnHover( false );
					this.togglePauseOnFocus( false );
				}

				if ( super.onDestroy ) {
					super.onDestroy();
				}
			}

			async initSlider() {
				const elementSettings = this.getElementSettings();

				const Swiper = elementorFrontend.utils.swiper;
    			this.swiper = await new Swiper(this.elements.$swiperContainer, this.getSwiperOptions());

				/*
				 * params.autoplay.enabled, not the widget setting: it is true only where the
				 * autoplay actually started, so never in the editor and never once reduced
				 * motion has held it back. autoplay.start() has no such guard, so a resume
				 * bound on a carousel that is not autoplaying would start one.
				 */
				const autoplayStarted = !! ( this.swiper.params.autoplay && this.swiper.params.autoplay.enabled );

				if ( autoplayStarted && 'yes' === elementSettings.pause_on_hover ) {
					this.togglePauseOnHover(true);
				}

				if ( autoplayStarted ) {
					this.togglePauseOnFocus( true );
				}

				this.syncSlideVisibility();

				// Autoplay must not speak. An announcement answers the user having moved the carousel.
				this.userNavigated = false;

				/*
				 * Swiper emits navigationNext/navigationPrev only after the slide has changed,
				 * so a flag set from those is one press behind. Marking the intent on the way
				 * down, in the capture phase, gets there before slideChange.
				 */
				const controls = '.pp-slider-arrow, .swiper-pagination-bullet';

				this.onNavigationIntent = ( e ) => {
					const onControl = e.target.closest && e.target.closest( controls );

					if ( 'keydown' === e.type ) {
						const isArrowKey   = ( 'ArrowLeft' === e.key || 'ArrowRight' === e.key );
						const isActivation = onControl && ( 'Enter' === e.key || ' ' === e.key );

						if ( ! isArrowKey && ! isActivation ) {
							return;
						}
					} else if ( ! onControl ) {
						return;
					}

					this.userNavigated = true;
				};

				this.$element[0].addEventListener( 'pointerdown', this.onNavigationIntent, true );
				this.$element[0].addEventListener( 'keydown', this.onNavigationIntent, true );

				// A swipe is deliberate too, and touchEnd lands before the transition.
				this.swiper.on( 'touchEnd', () => {
					this.userNavigated = true;
				} );

				const statusId = this.$element.find( '.pp-screen-only[aria-live]' ).attr( 'id' );

				if ( statusId ) {
					/*
					 * Swiper points the arrows' aria-controls at the slides wrapper, which is
					 * correct and stays; the status region only describes the result. Swiper
					 * also makes that wrapper a polite live region, which would read the new
					 * slide a second time on top of the status announcement.
					 */
					this.$element.find( '.pp-slider-arrow' ).attr( 'aria-describedby', statusId );

					if ( this.swiper.$wrapperEl ) {
						this.swiper.$wrapperEl.attr( 'aria-live', 'off' );
					}
				}

				this.swiper.on( 'slideChange', () => {
					this.announceSlidePosition();
				} );

				this.swiper.on( 'slideChangeTransitionEnd resize breakpoint', () => {
					this.syncSlideVisibility();
				} );
			}

			/**
			 * Slides that are off screen have to leave the accessibility tree and the tab order
			 * together, or tabbing walks into links that are scrolled out of view.
			 * watchSlidesProgress already maintains the class this reads.
			 */
			syncSlideVisibility() {
				const $slides = this.elements.$swiperContainer.find( '.swiper-slide' );

				// Effects that never set the visibility class must not hide every slide.
				const hasVisibilityClass = $slides.filter( '.swiper-slide-visible' ).length > 0;

				$slides.each( function () {
					// A slide is never itself a control, so it should hold no tabindex at all.
					this.removeAttribute( 'tabindex' );

					if ( ! hasVisibilityClass || this.classList.contains( 'swiper-slide-visible' ) ) {
						this.removeAttribute( 'aria-hidden' );
						this.removeAttribute( 'inert' );
					} else {
						this.setAttribute( 'aria-hidden', 'true' );
						// inert is what takes the link inside out of the tab order.
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

				this.$element.find( '.pp-screen-only[aria-live]' ).text(
					statusFormat
						.replace( '%1$s', this.swiper.realIndex + 1 )
						.replace( '%2$s', this.getSlidesCount() )
				);
			}

			/**
			 * Autoplay stops while focus is anywhere in the widget, arrows and dots included,
			 * which sit outside the Swiper container.
			 */
			togglePauseOnFocus( toggleOn ) {
				if ( ! toggleOn ) {
					this.$element.off( 'focusin.ppInstafeed focusout.ppInstafeed' );
					return;
				}

				this.$element.on( 'focusin.ppInstafeed', () => {
					if ( this.swiper && this.swiper.autoplay && this.swiper.autoplay.running ) {
						this.swiper.autoplay.stop();
					}
				} );

				this.$element.on( 'focusout.ppInstafeed', ( e ) => {
					// Moving between two controls inside the widget is not leaving it.
					if ( e.relatedTarget && this.$element[0].contains( e.relatedTarget ) ) {
						return;
					}

					if ( this.swiper && this.swiper.autoplay && ! this.swiper.autoplay.running ) {
						this.swiper.autoplay.start();
					}
				} );
			}

			togglePauseOnHover(toggleOn) {
				if (toggleOn) {
					this.elements.$swiperContainer.on({
						mouseenter: () => {
							this.swiper.autoplay.stop();
						},
						mouseleave: () => {
							// Focus inside the widget holds the pause the pointer is releasing.
							if ( this.$element[0].contains( document.activeElement ) ) {
								return;
							}

							this.swiper.autoplay.start();
						}
					});
				} else {
					this.elements.$swiperContainer.off('mouseenter mouseleave');
				}
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-instafeed', InstafeedWidget );
	} );
})(jQuery);