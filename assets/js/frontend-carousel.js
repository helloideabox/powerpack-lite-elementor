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
				const selectors = this.getSettings( 'selectors' );
				return {
					$swiperContainer: this.$element.find( selectors.swiperContainer ),
					$swiperSlide: this.$element.find( selectors.swiperSlide ),
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

				if ( !this.isEdit && sliderSettings.autoplay ) {
					swiperOptions.autoplay = {
						delay: sliderSettings.autoplay_speed,
						disableOnInteraction: !!sliderSettings.pause_on_interaction
					};
				}

				return swiperOptions;
			}

			bindEvents() {
				this.initSlider();
			}

			async initSlider() {
				const elementSettings = this.getElementSettings();

				const Swiper = elementorFrontend.utils.swiper;
    			this.swiper = await new Swiper(this.elements.$swiperContainer, this.getSwiperOptions());

				this.thumbsNav();

				if ('yes' === elementSettings.pause_on_hover) {
					this.togglePauseOnHover(true);
				}

				if ( 'yes' === elementSettings.equal_height_boxes ) {
					this.setEqualHeight();

					this.swiper.on('slideChange', function() {
						this.setEqualHeight();
					}.bind(this) );
				}

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
					this.elements.$swiperContainer.on({
						mouseenter: () => {
							this.swiper.autoplay.stop();
						},
						mouseleave: () => {
							this.swiper.autoplay.start();
						}
					});
				} else {
					this.elements.$swiperContainer.off('mouseenter mouseleave');
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