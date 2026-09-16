(function ($) {
	$( window ).on( 'elementor/frontend/init', () => {
		class PostsWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						swiperContainer: '.pp-swiper-slider',
						swiperSlide: '.swiper-slide',
						postsContainer: '.pp-posts-container',
						posts: '.pp-posts',
						couponsContainer: '.pp-coupons',
						couponsGrid: '.pp-coupons-grid-wrapper',
						coupon: '.pp-coupon',
						filters: '.pp-post-filters',
						filtersDropdown: '.pp-post-filters-dropdown',
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
					effect: 'slide',
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$swiperContainer: this.$element.find( selectors.swiperContainer ),
					$swiperSlide: this.$element.find( selectors.swiperSlide ),
					$postsContainer: this.$element.find( selectors.postsContainer ),
					$posts: this.$element.find( selectors.posts ),
					$couponsContainer: this.$element.find( selectors.couponsContainer ),
					$couponsGrid: this.$element.find( selectors.couponsGrid ),
					$coupon: this.$element.find( selectors.coupon ),
					$filters: this.$element.find( selectors.filters ),
					$filtersDropdown: this.$element.find( selectors.filtersDropdown ),
				};
			}

			getSliderSettings(prop) {
				const sliderSettings = ( this.elements.$swiperContainer && this.elements.$swiperContainer.length && undefined !== this.elements.$swiperContainer.data('slider-settings') ) ? this.elements.$swiperContainer.data('slider-settings') : '';

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

			getLayout() {
				let layout = ( undefined !== this.elements.$posts.data('layout') ) ? this.elements.$posts.data('layout') : 'grid';

				if ( 'pp-coupons' === this.getWidgetType() ) {
					layout = this.getElementSettings('layout');
				}

				return layout;
			}

			getDeviceSlidesPerView(device) {
				const slidesPerViewKey = 'slides_per_view' + ('desktop' === device ? '' : '_' + device);
				let spv = +this.getSliderSettings(slidesPerViewKey);

				if ( this.isEdit ) {
					const colKey = 'columns' + ('desktop' === device ? '' : '_' + device);
					const skin = ( typeof this.getSkin === 'function' ) ? this.getSkin() : '';
					const colSetting = this.getElementSettings(colKey) || ( skin ? this.getElementSettings( skin + '_' + colKey ) : null ) || ( 'desktop' === device ? this.getElementSettings('columns') : null );
					if ( colSetting ) {
						spv = +colSetting;
					}
				}

				if ( ! spv ) {
					if ( 'widescreen' === device || 'laptop' === device || 'tablet_extra' === device ) {
						return this.getDeviceSlidesPerView('desktop');
					}
					if ( 'mobile_extra' === device ) {
						return this.getDeviceSlidesPerView('tablet');
					}
				}

				const slidesCount = this.getSlidesCount();
				const targetSPV = spv || this.getSettings('slidesPerView')[device];
				return slidesCount ? Math.min(slidesCount, targetSPV) : targetSPV;
			}

			getSlidesPerView(device) {
				if ('slide' === this.getEffect()) {
					return this.getDeviceSlidesPerView(device);
				}
				return 1;
			}

			getDeviceSlidesToScroll(device) {
				const slidesToScrollKey = 'slides_to_scroll' + ('desktop' === device ? '' : '_' + device);
				return Math.min(this.getSlidesCount(), +this.getSliderSettings(slidesToScrollKey) || 1);
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
				const reduceMotion = this.prefersReducedMotion();

				const swiperOptions = {
					slidesPerView:              this.getSlidesPerView('desktop'),
					slidesPerGroup:             this.getSlidesToScroll('desktop'),
					spaceBetween:               this.getSpaceBetween(),
					loop:                       sliderSettings.loop,
					centeredSlides:             'yes' === sliderSettings.centered_slides,
					speed:                      reduceMotion ? 0 : sliderSettings.speed,
					autoHeight:                 true,
					effect:                     this.getEffect(),
					watchSlidesVisibility:      true,
					watchSlidesProgress:        true,
					preventClicksPropagation:   false,
					slideToClickedSlide:        true,
					handleElementorBreakpoints: true,
					keyboard: {
						enabled: true,
						onlyInViewport: true,
					},
					a11y: {
						enabled: true,
						...( typeof ppPostsScript !== 'undefined' && ppPostsScript.i18n ? ppPostsScript.i18n : {} ),
					}
				};

				if ( 'fade' === this.getEffect() ) {
					swiperOptions.fadeEffect = {
						crossFade: true,
					};
				}

				if ( sliderSettings.show_arrows ) {
					swiperOptions.navigation = {
						prevEl: ( this.isEdit ) ? '.elementor-swiper-button-prev' : `.swiper-button-prev-${this.getID()}`,
						nextEl: ( this.isEdit ) ? '.elementor-swiper-button-next' : `.swiper-button-next-${this.getID()}`,
					};
				}

				if ( sliderSettings.pagination ) {
					swiperOptions.pagination = {
						el: ( this.isEdit ) ? '.swiper-pagination' : `.swiper-pagination-${this.getID()}`,
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

				if ( !this.isEdit && sliderSettings.autoplay && ! reduceMotion ) {
					swiperOptions.autoplay = {
						delay: sliderSettings.autoplay_speed,
						disableOnInteraction: !!sliderSettings.pause_on_interaction
					};
				}

				return swiperOptions;
			}

			bindEvents() {
				const search = this.$element.find( '.pp-post-filters-container' ).data( 'search-form' );

				if ( 'carousel' !== this.getLayout() ) {
					if ( 'masonry' === this.getLayout() ) {
						this.initMasonryLayout();
					}

					if (
						this.elements.$posts.hasClass( 'pp-posts-skin-checkerboard' ) &&
						this.elements.$posts.hasClass( 'pp-posts-height-auto' ) &&
						this.$element.hasClass( 'pp-equal-height-yes' ) ) {
						this.setEqualHeightCheckerboard();
					}

					this.setPostsCount(1);

					this.initFilters();

					if ( 'show' === search ) {
						this.getSearchForm();
					}

					this.initLoadMore();

					this.initNumberedPagination();

					this.initInfiniteScroll();
				}

				if ( 'carousel' === this.getLayout() ) {
					this.initSlider();
				}

				if ( 'pp-coupons' === this.getWidgetType() ) {
					this.onCouponClick();
				}
			}

			initMasonryLayout() {
				if ( ! this.elements.$posts || ! this.elements.$posts.length ) {
					return;
				}

				const self = this;

				this.masonry = new PPMasonry( {
					container: this.elements.$posts[0],
					itemSelector: '.pp-grid-item-wrap',
				} );

				this.masonry.observeResize();

				// Measure once images are loaded, then keep it in sync as (lazy) images resolve.
				this.$element.imagesLoaded( function () {
					self.masonry.setReady();
					self.masonry.layout();
				} );

				this.elements.$posts.find('img').on('load', function () {
					self.masonry.layoutIfReady();
				} );
			}

			onElementChange( propertyName ) {
				const layout = this.getLayout();

				if ( 'masonry' === layout ) {
					if ( this.masonry ) {
						setTimeout( () => this.masonry.layout() );
					}
				} else if ( 'carousel' === layout ) {
					if ( propertyName && ( propertyName.indexOf('columns') !== -1 || 'layout' === propertyName ) ) {
						const selectors = this.getSettings( 'selectors' );
						if ( this.$element.find( selectors.swiperContainer ).length ) {
							this.initSlider();
						}
						return;
					}

					if ( this.swiper ) {
						// Keep the Swiper gap in sync with the (live) Column Spacing var while editing,
						// since the slide gap is Swiper's spaceBetween rather than CSS padding.
						const container = ( this.elements.$posts && this.elements.$posts.length )
							? this.elements.$posts[0]
							: ( ( this.elements.$couponsContainer && this.elements.$couponsContainer.length )
								? this.elements.$couponsContainer[0]
								: ( this.$element && this.$element.length ? this.$element[0] : null ) );

						if ( container ) {
							const gap = parseFloat( getComputedStyle( container ).getPropertyValue('--grid-column-gap') ) || 0;
							this.swiper.params.spaceBetween = gap;
							this.swiper.update();
						}
					}
				}
			}

			onDestroy() {
				if ( this.swiper ) {
					this.swiper.destroy( true, true );
					this.swiper = null;
				}

				if ( this.masonry ) {
					this.masonry.destroy();
				}

				if ( super.onDestroy ) {
					super.onDestroy();
				}
			}

			initFilters() {
				const self = this;

				this.elements.$filters.find( '.pp-post-filter' ).off( 'click' ).on( 'click', function() {
					$(this).siblings().removeClass( 'pp-filter-current' ).attr( 'aria-selected', 'false' ).attr( 'tabindex', '-1' );
					$(this).addClass( 'pp-filter-current' ).attr( 'aria-selected', 'true' ).attr( 'tabindex', '0' );

					// The name only: leave out the count and its screen-reader text.
					const filterText = $(this).clone().children().remove().end().text().trim();

					if ( self.elements.$couponsContainer.length > 0 ) {
						// Announced together with the result count once the coupons have loaded.
						self.couponFilterName = filterText;
					} else {
						const filterStatusTemplate = ( typeof ppPostsScript !== 'undefined' && ppPostsScript.filter_status ) ? ppPostsScript.filter_status : 'Showing posts for %s';
						self.$element.find( '.pp-posts-status' ).text( filterStatusTemplate.replace( '%s', filterText ) );
					}

					if ( self.elements.$filtersDropdown.length > 0 ) {
						let currFilterUpdate = $(this).data( 'filter' ),
							currFilter = self.elements.$filtersDropdown.find('li[data-filter="' + currFilterUpdate + '"]');

						currFilter.siblings().removeClass( 'pp-filter-current' ).attr( 'aria-selected', 'false' ).attr( 'tabindex', '-1' );
						currFilter.addClass( 'pp-filter-current' ).attr( 'aria-selected', 'true' ).attr( 'tabindex', '0' );
						self.setDropdownLabel( $(this) );
					}

					if ( self.elements.$couponsContainer.length > 0 ) {
						self.postsFilterAjax( self, $(this), 'coupon' );
					} else {
						self.postsFilterAjax( self, $(this), '' );
					}
				});

				// Tablist keyboard: arrows, Home and End move focus only; Enter or Space loads the filter,
				// so browsing the tabs doesn't fire a request per key press.
				this.elements.$filters.find( '.pp-post-filter' ).off( 'keydown' ).on( 'keydown', function(e) {
					const $items = self.elements.$filters.find( '.pp-post-filter' );
					const currentIndex = $items.index( this );
					const targets = {
						ArrowRight: currentIndex + 1,
						ArrowDown: currentIndex + 1,
						ArrowLeft: currentIndex - 1,
						ArrowUp: currentIndex - 1,
						Home: 0,
						End: $items.length - 1,
					};

					if ( e.key in targets ) {
						e.preventDefault();
						const $next = $items.eq( ( targets[ e.key ] + $items.length ) % $items.length );
						$items.attr( 'tabindex', '-1' );
						$next.attr( 'tabindex', '0' ).trigger( 'focus' );
					} else if ( 'Enter' === e.key || ' ' === e.key ) {
						e.preventDefault();
						$(this).trigger( 'click' );
					}
				});

				// Leaving the tablist puts the single tab stop back on the selected tab.
				this.elements.$filters.off( 'focusout.ppTabs' ).on( 'focusout.ppTabs', function(e) {
					if ( ! this.contains( e.relatedTarget ) ) {
						$(this).find( '.pp-post-filter' ).attr( 'tabindex', '-1' ).filter( '.pp-filter-current' ).attr( 'tabindex', '0' );
					}
				});

				// Post Filter Dropdown for Mobile device.
				const $ddButton = this.elements.$filtersDropdown.find( '.pp-post-filters-dropdown-button' );
				const $ddList = this.elements.$filtersDropdown.find( '.pp-post-filters-dropdown-list' );
				const focusCurrentOption = () => {
					const $current = $ddList.find( '.pp-filter-current' );
					( $current.length ? $current : $ddList.find( '[role="option"]' ) ).first().trigger( 'focus' );
				};
				const closeDropdown = ( returnFocus ) => {
					$ddList.hide();
					$ddButton.attr( 'aria-expanded', 'false' );

					if ( returnFocus ) {
						$ddButton.trigger( 'focus' );
					}
				};

				$ddButton.off( 'click keydown' ).on( 'click', function() {
					const open = ! $ddList.is( ':visible' );

					$ddList.toggle( open );
					$(this).attr( 'aria-expanded', open ? 'true' : 'false' );

					if ( open ) {
						focusCurrentOption();
					}
				}).on( 'keydown', function(e) {
					if ( 'Enter' === e.key || ' ' === e.key ) {
						e.preventDefault();
						$(this).trigger( 'click' );
					} else if ( 'ArrowDown' === e.key ) {
						e.preventDefault();

						if ( $ddList.is( ':visible' ) ) {
							focusCurrentOption();
						} else {
							$(this).trigger( 'click' );
						}
					}
				});

				$ddList.off( 'keydown' ).on( 'keydown', '[role="option"]', function(e) {
					const $options = $ddList.find( '[role="option"]' );
					const index = $options.index( this );
					const targets = {
						ArrowDown: Math.min( index + 1, $options.length - 1 ),
						ArrowUp: Math.max( index - 1, 0 ),
						Home: 0,
						End: $options.length - 1,
					};

					if ( e.key in targets ) {
						e.preventDefault();
						$options.eq( targets[ e.key ] ).trigger( 'focus' );
					} else if ( 'Enter' === e.key || ' ' === e.key ) {
						e.preventDefault();
						$(this).trigger( 'click' );
					} else if ( 'Escape' === e.key ) {
						e.preventDefault();
						closeDropdown( true );
					} else if ( 'Tab' === e.key ) {
						closeDropdown( false );
					}
				});

				this.elements.$filtersDropdown.find( '.pp-post-filters-dropdown-item' ).off( 'click' ).on( 'click', function() {
					$(this).siblings().removeClass( 'pp-filter-current' ).attr( 'aria-selected', 'false' ).attr( 'tabindex', '-1' );
					$(this).addClass( 'pp-filter-current' ).attr( 'aria-selected', 'true' ).attr( 'tabindex', '0' );

					self.setDropdownLabel( $(this) );

					if ( self.elements.$couponsContainer.length > 0 ) {
						self.couponFilterName = $(this).clone().children().remove().end().text().trim();
					}

					// Return focus only when the choice came from the open list, not from a URL hash.
					closeDropdown( $ddList.length > 0 && $ddList[0].contains( document.activeElement ) );

					let currFilterUpdate = $(this).data( 'filter' ),
						currFilter = self.elements.$filters.find('li[data-filter="' + currFilterUpdate + '"]');

					currFilter.siblings().removeClass( 'pp-filter-current' ).attr( 'aria-selected', 'false' ).attr( 'tabindex', '-1' );
					currFilter.addClass( 'pp-filter-current' ).attr( 'aria-selected', 'true' ).attr( 'tabindex', '0' );

					if ( self.elements.$couponsContainer.length > 0 ) {
						self.postsFilterAjax( self, $(this), 'coupon' );
					} else {
						self.postsFilterAjax( self, $(this), '' );
					}
				});

				if ( this.elements.$filters.length > 0 || this.elements.$filtersDropdown.length > 0 ) {
					// Trigger filter by hash parameter in URL.
					this.initFiltersOnHashChange(self);

					// Trigger filter on hash change in URL.
					$(window).on( 'hashchange', function() {
						this.initFiltersOnHashChange(self);
					}.bind(this) );
				}
			}

			initFiltersOnHashChange(self) {
				if ( '' !== location.hash ) {
					let filterHash = location.hash.split('#')[1];

					self.elements.$filters.find('li[data-filter=".' + filterHash + '"]').trigger('click');

					if ( self.elements.$filtersDropdown.length > 0 ) {
						self.elements.$filtersDropdown.find('li[data-filter=".' + filterHash + '"]').trigger('click');
					}
				}
			}

			getSearchForm() {
				const searchInput = this.$element.find( '.pp-search-form-input' ),
					searchAction  = this.$element.find( '.pp-post-filters-container' ).data( 'search-action' ),
					self = this;

				searchInput.on({
					focus: function focus() {
						self.$element.find( '.pp-search-form' ).addClass('pp-search-form-focus');
					},
					blur: function blur() {
						self.$element.find( '.pp-search-form' ).removeClass('pp-search-form-focus');
					}
				});

				if ( 'instant' == searchAction ) {
					this.$element.find('.pp-search-form-input').keyup( debounce(function () {
						self.postsSearchAjax( self );
					}) );

					// debounce so filtering doesn't happen every millisecond
					function debounce(pp, threshold) {
						let timeout;
						threshold = threshold || 100;
						return function debounced() {
							clearTimeout(timeout);
							let args = arguments;
							let _this = this;

							function delayed() {
								pp.apply(_this, args);
							}
							timeout = setTimeout(delayed, threshold);
						};
					}
				} else if ( 'button-click' == searchAction ) {
					this.$element.find( '.pp-search-form-submit' ).on( 'click', function() {
						self.postsSearchAjax( self );
					});
				}
			}

			initNumberedPagination() {
				const self = this;

				$('body').on( 'click', '.pp-posts-pagination-ajax .page-numbers', function(e) {
					const $posts_scope = $(this).closest( '.elementor-widget-pp-posts' ),
						$coupon_scope  = $(this).closest( '.elementor-widget-pp-coupons' );

					let $scope = ( $coupon_scope.length > 0 ) ? $coupon_scope : $posts_scope,
						lastItem = $scope.find( '.pp-post-wrap' ).last(),
						container = $scope.find( '.pp-posts' ),
						pageID = container.data('page');

					if ( $coupon_scope.length > 0 ) {
						lastItem = $coupon_scope.find( '.pp-coupon' ).last(),
						container = $coupon_scope.find( '.pp-coupons-grid-wrapper' ),
						pageID = container.data('page');
					}

					if ( 'main' == container.data( 'query-type' ) ) {
						return;
					}

					e.preventDefault();

					lastItem.after( '<div class="pp-post-loader"><div class="pp-loader"></div><div class="pp-loader-overlay"></div></div>' );

					let pageNumber = 1,
						curr = parseInt( $scope.find( '.pp-posts-pagination .page-numbers.current' ).html() );

					if ( $(this).hasClass( 'next' ) ) {
						pageNumber = curr + 1;
					} else if ( $(this).hasClass( 'prev' ) ) {
						pageNumber = curr - 1;
					} else {
						pageNumber = $(this).html();
					}

					let $args = {
						'page_id':     pageID,
						'widget_id':   self.getID(),
						'filter':      $scope.find( '.pp-filter-current' ).data( 'filter' ),
						'taxonomy':    $scope.find( '.pp-filter-current' ).data( 'taxonomy' ),
						'page_number': pageNumber,
						'ajax_for':    ''
					};

					if ( $coupon_scope.length > 0 ) {
						$args.ajax_for = 'coupon';

						$('html, body').animate({
							scrollTop: ( ( $coupon_scope.find( '.pp-coupons-grid' ).offset().top ) - 30 )
						}, self.prefersReducedMotion() ? 0 : 'slow');

						$args.restore_focus = true;

					} else {
						$args.skin = $scope.find( '.pp-posts' ).data( 'skin' );

						$('html, body').animate({
							scrollTop: ( ( $scope.find( '.pp-posts-container' ).offset().top ) - 30 )
						}, 'slow');
					}

					self.callAjax( self, $args );
				} );
			}

			initLoadMore() {
				const self = this;
				this.loadStatus = true;

				// Load More is a link with role="button": Space activates it too.
				self.$element.off( 'keydown.ppLoadMore' ).on( 'keydown.ppLoadMore', '.pp-post-load-more[role="button"]', function(e) {
					if ( ' ' === e.key ) {
						e.preventDefault();
						$(this).trigger( 'click' );
					}
				} );

				$(document).on( 'click', '.pp-post-load-more', function(e) {
					const $posts_scope = $(this).closest( '.elementor-widget-pp-posts' ),
						$coupon_scope  = $(this).closest( '.elementor-widget-pp-coupons' );

					e.preventDefault();

					if ( elementorFrontend.isEditMode() ) {
						loader.show();
						coupon_loader.show();

						return false;
					}

					let $scope    = ( $coupon_scope.length > 0 ) ? $coupon_scope : $posts_scope,
						loader    = $scope.find( '.pp-posts-loader' ),
						pageCount = self.getPostsCount(),
						category  = $scope.find( '.pp-filter-current' ).data( 'filter' ),
						taxonomy  = $scope.find( '.pp-filter-current' ).data( 'taxonomy' ),
						pageID    = $scope.find( '.pp-posts' ).data('page');

					if ( $coupon_scope.length > 0 ) {
						pageID = $coupon_scope.find( '.pp-coupons-grid-wrapper' ).data('page');
					}

					let $args = {
						'page_id':     pageID,
						'widget_id':   self.getID(),
						'filter':      ( typeof category !== 'undefined' && category ) ? category : '',
						'taxonomy':    ( typeof taxonomy !== 'undefined' && taxonomy ) ? taxonomy : '',
						'page_number': ( pageCount + 1 ),
						'ajax_for':    ''
					};

					if ( $coupon_scope.length > 0 ) {
						$args.ajax_for = 'coupon';
						$args.restore_focus = true;
					} else {
						$args.skin = $scope.find( '.pp-posts' ).data( 'skin' );
					}

					self.total = $scope.find( '.pp-posts-pagination' ).data( 'total' );

					if ( true == self.loadStatus ) {
						if ( pageCount < self.total ) {
							loader.show();
							$(this).hide();
							self.callAjax( self, $args, true, pageCount );
							pageCount++;
							self.loadStatus = false;
						}
					}
				} );
			}

			initInfiniteScroll() {
				let self   = this,
					count  = 1,
					loader = this.$element.find( '.pp-posts-loader' );

				this.loadStatus = true;

				if ( this.elements.$postsContainer.hasClass( 'pp-posts-infinite-scroll' ) || this.elements.$couponsContainer.hasClass( 'pp-coupons-infinite-scroll' ) ) {
					let windowHeight50 = jQuery(window).outerHeight() / 1.25;

					$(window).scroll( function () {
						if ( elementorFrontend.isEditMode() ) {
							loader.show();
							return false;
						}

						let $container = self.$element,
							$wrapper   = self.elements.$posts,
							$lastItem  = $container.find( '.pp-post:last' );

						if ( self.elements.$couponsContainer.hasClass( 'pp-coupons-infinite-scroll' ) ) {
							$wrapper   = self.elements.$couponsGrid,
							$lastItem  = $container.find( '.pp-coupon:last' );
						}

						let $args = {
							'page_id':     $wrapper.data('page'),
							'widget_id':   self.getID(),
							'filter':      $container.find( '.pp-filter-current' ).data( 'filter' ),
							'taxonomy':    $container.find( '.pp-filter-current' ).data( 'taxonomy' ),
							'page_number': $container.find( '.page-numbers.current' ).next( 'a' ).html(),
							'ajax_for':    ''
						};

						self.total = $container.find( '.pp-posts-pagination' ).data( 'total' );

						if ( ( $(window).scrollTop() + windowHeight50 ) >= ( $lastItem.offset().top ) ) {

							if ( self.elements.$couponsContainer.hasClass( 'pp-coupons-infinite-scroll' ) ) {
								$args.ajax_for = 'coupon';
							} else {
								$args.skin = $container.find( '.pp-posts' ).data( 'skin' );
							}

							if ( true == self.loadStatus ) {
								if ( count < self.total ) {
									loader.show();
									self.callAjax( self, $args, true );
									count++;
									self.loadStatus = false;
								}
							}
						}
					} );
				}
			}

			postsFilterAjax( self, $this, $coupon ) {
				let $lastItem = this.elements.$posts.find( '.pp-post-wrap' ).last(),
					pageID    = this.elements.$posts.data('page');

				if ( 'coupon' === $coupon ) {
					$lastItem = this.elements.$couponsGrid.find( '.pp-coupon' ).last(),
					pageID = this.elements.$couponsGrid.data('page');
				}

				$lastItem.after( '<div class="pp-posts-loader-wrap"><div class="pp-loader"></div><div class="pp-loader-overlay"></div></div>' );

				let $args = {
					'page_id':     pageID,
					'widget_id':   this.getID(),
					'filter':      $this.data( 'filter' ),
					'taxonomy':    $this.data( 'taxonomy' ),
					'page_number': 1,
					'ajax_for':    ''
				};

				if ( 'coupon' === $coupon ) {
					$args.ajax_for = 'coupon';
				} else {
					$args.skin = this.$element.find( '.pp-posts' ).data( 'skin' );
				}

				this.callAjax( self, $args );
			}

			postsSearchAjax($this) {
				this.$element.find( '.pp-posts .pp-grid-item-wrap' ).last().after( '<div class="pp-posts-loader-wrap"><div class="pp-loader"></div><div class="pp-loader-overlay"></div></div>' );

				let $args = {
					'page_id':     this.$element.find( '.pp-posts' ).data('page'),
					'widget_id':   this.$element.data( 'id' ),
					'search':      this.$element.find( '.pp-search-form-input' ).val(),
					'page_number': 1
				};

				this.callAjax( $this, $args );
			}

			callAjax( self, $obj, $append, $count ) {
				let loader = this.$element.find( '.pp-posts-loader' );
				this.$element.find( '.pp-post-load-more' ).attr( 'aria-busy', 'true' );

				$.ajax({
					url: ppPostsScript.ajax_url,
					data: {
						action:      'pp_get_post',
						page_id:     $obj.page_id,
						widget_id:   $obj.widget_id,
						category:    $obj.filter,
						search:      $obj.search,
						taxonomy:    $obj.taxonomy,
						skin:        $obj.skin,
						page_number: $obj.page_number,
						ajax_for:    $obj.ajax_for,
						nonce:       ppPostsScript.posts_nonce,
					},
					dataType: 'json',
					type: 'POST',
					success: function( data ) {
						let $container = self.elements.$postsContainer,
							sel = $container.find( '.pp-posts' );

						if ( 'coupon' === $obj.ajax_for ) {
							$container = self.elements.$couponsContainer,
							sel = $container.find( '.pp-coupons-grid-wrapper' );
						}

						const countBefore = sel.children( '.pp-coupon' ).length;
						let not_found = $container.find( '.pp-posts-empty' );

						not_found.remove();

						if ( $(not_found).length == 0 ) {
							$(data.data.not_found).insertBefore(sel);
						}

						if ( true == $append ) {
							let html_str = data.data.html;
							sel.append( html_str );
						} else {
							sel.html( data.data.html );
						}

						$container.find( '.pp-posts-pagination-wrap' ).html( data.data.pagination );

						//	Complete the process 'loadStatus'
						self.loadStatus = true;
						if ( true == $append ) {
							loader.hide();
							$container.find( '.pp-post-load-more' ).show();
						}

						self.setPostsCount( $obj.page_number );

						if ( 'coupon' !== $obj.ajax_for ) {
							let layout = $container.find( '.pp-posts' ).data( 'layout' );

							if ( 'masonry' == layout && self.masonry ) {
								$container.imagesLoaded( function() {
									self.masonry.setReady();
									self.masonry.layout();
								});
							}
						}

						$count = $count + 1;

						if ( $count == self.total ) {
							$container.find( '.pp-post-load-more' ).hide();
						}

						self.$element.find( '.pp-post-load-more' ).attr( 'aria-busy', 'false' );
						if ( 'coupon' === $obj.ajax_for ) {
							self.afterCouponsLoaded( sel, $obj, $append, countBefore );
						} else {
							const postsLoadedText = ( typeof ppPostsScript !== 'undefined' && ppPostsScript.posts_loaded ) ? ppPostsScript.posts_loaded : 'New posts loaded';
							const firstPostTitle  = sel.find( '.pp-post-title, .pp-coupon-title' ).first().text().trim();
							const statusMessage   = firstPostTitle ? postsLoadedText + ': ' + firstPostTitle : postsLoadedText;
							self.$element.find( '.pp-posts-status' ).text( statusMessage );
						}

						self.$element.trigger('posts.rendered', [self.$element]);
					}
				} ).done( function() {
					self.$element.find( '.pp-post-load-more' ).attr( 'aria-busy', 'false' );
					if ( self.$element.find( '.elementor-invisible' ).length > 0 ) {
						self.$element.find( '.elementor-invisible' ).removeClass( 'elementor-invisible' );
					}
				} );
			}

			setEqualHeightCheckerboard() {
				const elementorBreakpoints = elementorFrontend.config.breakpoints;
				let maxHeight = 0,
					$height = 'auto';

				this.$element.find('.pp-post-wrap').each( function() {
					if ( $(this).find('.pp-post-content').outerHeight() > maxHeight ) {
						maxHeight = $(this).find('.pp-post-content').outerHeight();
					}
				});

				if ( $(window).width() >= elementorBreakpoints.md ) {
					$height = maxHeight;
				}

				this.$element.find('.pp-post-wrap').css('height',$height);
			}

			setPostsCount(count) {
				this.$element.find('.pp-post-load-more').attr('data-count', count);
			}

			getPostsCount() {
				return this.$element.find('.pp-post-load-more').data('count');
			}

			/**
			 * @since x.x.x
			 */
			prefersReducedMotion() {
				return !! ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches );
			}

			/**
			 * Write a message to a live region. Clearing it first makes a repeat of the same message speak again.
			 *
			 * @since x.x.x
			 */
			announce( $region, message ) {
				$region.text( '' );

				setTimeout( () => {
					$region.text( message );
				}, 100 );
			}

			/**
			 * Show the chosen filter on the dropdown button without replacing its icon.
			 *
			 * @since x.x.x
			 */
			setDropdownLabel( $source ) {
				const $button = this.elements.$filtersDropdown.find( '.pp-post-filters-dropdown-button' );
				const $label = $button.find( '.pp-post-filters-dropdown-label' );

				if ( $label.length ) {
					$label.text( $source.clone().children().remove().end().text().trim() );
				} else {
					$button.html( $source.html() );
				}
			}

			/**
			 * Announce the coupons a filter or page produced, and move focus to them when the control used is gone.
			 *
			 * @since x.x.x
			 */
			afterCouponsLoaded( $grid, args, append, countBefore ) {
				const i18n = ( typeof ppPostsScript !== 'undefined' ) ? ppPostsScript : {};
				const $coupons = $grid.children( '.pp-coupon' );
				let message = ( i18n.coupons_loaded || 'Coupons shown: %s' ).replace( '%s', $coupons.length );

				if ( this.couponFilterName ) {
					message = ( i18n.coupons_filter_status || 'Showing coupons for %s' ).replace( '%s', this.couponFilterName ) + '. ' + message;
					this.couponFilterName = '';
				}

				this.announce( this.$element.find( '.pp-posts-status' ), message );

				const currentTabId = this.elements.$filters.find( '.pp-filter-current' ).attr( 'id' );

				if ( currentTabId ) {
					this.elements.$couponsContainer.attr( 'aria-labelledby', currentTabId );
				}

				// Page numbers are replaced and Load More is hidden, so focus would fall to the page start.
				if ( ! args.restore_focus ) {
					return;
				}

				let $target = append ? $coupons.eq( countBefore ) : $coupons.first();

				if ( ! $target.length ) {
					$target = $grid;
				}

				$target.attr( 'tabindex', '-1' ).one( 'blur', function() {
					$(this).removeAttr( 'tabindex' );
				} );
				$target[0].focus();
			}

			/**
			 * Copy text to the clipboard without moving keyboard focus.
			 *
			 * @since x.x.x
			 */
			copyCouponText( code ) {
				if ( navigator.clipboard && window.isSecureContext ) {
					// The API can exist yet be refused (permissions policy, iframes), so fall back rather than fail.
					return navigator.clipboard.writeText( code ).catch( () => this.copyCouponTextFallback( code ) );
				}

				return this.copyCouponTextFallback( code );
			}

			/**
			 * Copy through a temporary field, then put focus back where it was.
			 *
			 * @since x.x.x
			 */
			copyCouponTextFallback( code ) {
				return new Promise( ( resolve, reject ) => {
					const active = document.activeElement;
					const field = document.createElement( 'textarea' );
					let copied = false;

					// Set as a property, never as HTML.
					field.value = code;
					field.setAttribute( 'readonly', '' );
					field.className = 'elementor-screen-only';
					document.body.appendChild( field );
					field.select();

					try {
						copied = document.execCommand( 'copy' );
					} catch ( e ) {
						copied = false;
					}

					field.remove();

					if ( active && active.focus ) {
						active.focus( { preventScroll: true } );
					}

					if ( copied ) {
						resolve();
					} else {
						reject();
					}
				} );
			}

			onCouponClick() {
				const self = this;

				// Delegated, so coupons added by filters, pagination and loop clones work too.
				this.$element
					.off( '.ppCoupon' )
					.on( 'keydown.ppCoupon', '.pp-coupon-code[role="button"]', function(e) {
						if ( 'Enter' === e.key || ' ' === e.key ) {
							e.preventDefault();
							$(this).trigger( 'click' );
						}
					} )
					.on( 'click.ppCoupon', '.pp-coupon-code[role="button"]', function() {
						self.copyCoupon( $(this) );
					} );
			}

			/**
			 * Copy a coupon code, reveal it for the Reveal style, and announce the result.
			 *
			 * The accessible name comes from the visible content, so once the code shows
			 * it is read as "SAVE10 Copied" rather than a label that hides the code.
			 *
			 * @since x.x.x
			 */
			copyCoupon( $btn ) {
				const settings = this.getElementSettings();
				const i18n = ( typeof ppPostsScript !== 'undefined' ) ? ppPostsScript : {};
				const code = String( $btn.attr( 'data-coupon-code' ) || '' );
				const copiedText = settings.coupon_copied_text || i18n.copied_text || 'Copied';
				const $status = this.$element.find( '.pp-coupon-status' );
				const instant = this.prefersReducedMotion();

				const showCopied = () => {
					const $copy = $btn.find( '.pp-coupon-copy-text' );

					$btn.addClass( 'pp-copied' );

					if ( instant ) {
						$copy.text( copiedText ).show();
					} else {
						$copy.fadeOut( () => {
							$copy.text( copiedText ).fadeIn();
						} );
					}
				};

				const reveal = () => {
					$btn.find( '.pp-coupon-code-text-wrap' ).removeClass( 'pp-unreavel' ).removeAttr( 'aria-hidden' );
					$btn.find( '.pp-coupon-code-text' ).text( code );
					$btn.find( '.pp-coupon-reveal-wrap' ).remove();
				};

				const finish = ( copied ) => {
					this.announce( $status, copied
						? ( i18n.coupon_copied || 'Coupon code %s copied to clipboard' ).replace( '%s', code )
						: ( i18n.coupon_copy_failed || 'Could not copy the coupon code' ) );

					if ( 'reveal' !== settings.coupon_style ) {
						if ( copied ) {
							showCopied();
						}
						return;
					}

					// The code is revealed even when copying fails, so it can still be read and typed.
					if ( instant ) {
						reveal();
						if ( copied ) {
							showCopied();
						}
						return;
					}

					$btn.find( '.pp-coupon-reveal-wrap' ).css( 'transform', 'translate(200px, 0px)' );

					setTimeout( reveal, 150 );

					if ( copied ) {
						setTimeout( showCopied, 500 );
					}
				};

				this.copyCouponText( code ).then( () => finish( true ), () => finish( false ) );
			}

			async initSlider() {
				if ( this.swiper ) {
					this.swiper.destroy( true, true );
					this.swiper = null;
				}

				const selectors = this.getSettings( 'selectors' );
				const $swiperContainer = this.$element.find( selectors.swiperContainer );

				if ( ! $swiperContainer.length ) {
					return;
				}

				this.elements.$swiperContainer = $swiperContainer;
				this.elements.$swiperSlide = this.$element.find( selectors.swiperSlide );

				const sliderSettings    = this.getSliderSettings(),
					carouselEqualHeight = this.$element.find( '.pp-posts' ).data( 'equal-height' );

				const Swiper = elementorFrontend.utils.swiper;
				this.swiper = await new Swiper(this.elements.$swiperContainer, this.getSwiperOptions());

				if ( ! this.swiper ) {
					return;
				}

				// Arrows and dots sit outside the slider, so pausing listens on the wrap that holds all of them.
				const $carouselWrap = this.elements.$swiperContainer.closest( '.swiper-container-wrap' );
				this.$carouselWrap = $carouselWrap.length ? $carouselWrap : this.elements.$swiperContainer;

				// Autoplay can be off by setting or by the reduced-motion preference; only then is there anything to pause.
				const autoplayOn = !! ( this.swiper.params.autoplay && this.swiper.params.autoplay.enabled );
				// Coupons does not put pause_on_hover in data-slider-settings; the control is frontend_available.
				const pauseOnHover = sliderSettings.pause_on_hover || this.getElementSettings( 'pause_on_hover' );

				if ( autoplayOn && 'yes' === pauseOnHover ) {
					this.togglePauseOnHover(true);
				}

				if ( autoplayOn && !this.isEdit ) {
					this.togglePauseOnFocus(true);
				}

				this.updateSlideA11y();
				this.swiper.on('slideChangeTransitionEnd resize breakpoint', () => {
					this.updateSlideA11y();
				});

				this.syncCarouselLive();
				this.swiper.on('autoplayStart autoplayStop', () => {
					this.syncCarouselLive();
				});

				const updateCarouselStatus = () => {
					if ( ! this.swiper ) {
						return;
					}

					const activeIndex = ( this.swiper.realIndex !== undefined ? this.swiper.realIndex : this.swiper.activeIndex ) + 1;
					const totalSlides = this.elements.$swiperSlide.filter(':not(.swiper-slide-duplicate)').length || this.getSlidesCount();
					const postsI18n = ( typeof ppPostsScript !== 'undefined' && ppPostsScript.i18n ) ? ppPostsScript.i18n : {};
					const statusTemplate = postsI18n.slideStatus || 'Showing Slide %1$s of %2$s';
					let statusText = statusTemplate.replace( '%1$s', activeIndex ).replace( '%2$s', totalSlides );

					let postTitle = '';
					let $activeSlide = null;

					if ( this.swiper.slides && this.swiper.slides[ this.swiper.activeIndex ] ) {
						$activeSlide = $( this.swiper.slides[ this.swiper.activeIndex ] );
					}
					if ( ( ! $activeSlide || ! $activeSlide.length ) && this.elements.$swiperContainer ) {
						$activeSlide = this.elements.$swiperContainer.find( '.swiper-slide-active' );
					}
					if ( $activeSlide && $activeSlide.length ) {
						const $titleEl = $activeSlide.find( '.pp-post-title, .pp-coupon-title' ).first();
						if ( $titleEl.length ) {
							postTitle = $titleEl.text().trim();
						}
					}

					if ( postTitle ) {
						statusText += ': ' + postTitle;
					}

					const $statusEl = this.$element.find( '.pp-post-carousel-status, .pp-coupons-carousel-status' );
					$statusEl.text( statusText );
				};

				this.swiper.on( 'slideChange', updateCarouselStatus );
				this.swiper.on( 'slideChangeTransitionEnd', updateCarouselStatus );

				this.elements.$swiperContainer.on( 'click', '.pp-slider-arrow, .elementor-swiper-button-prev, .elementor-swiper-button-next', () => {
					setTimeout( () => {
						updateCarouselStatus();
					}, 50 );
				} );

				if ( 'yes' === carouselEqualHeight ) {
					this.setEqualHeight();
				}
			}

			togglePauseOnHover(toggleOn) {
				const $wrap = this.$carouselWrap || this.elements.$swiperContainer;

				$wrap.off( '.ppHover' );

				if ( ! toggleOn ) {
					return;
				}

				$wrap.on( {
					'mouseenter.ppHover': () => {
						this.isHovered = true;
						this.swiper.autoplay.stop();
					},
					'mouseleave.ppHover': () => {
						this.isHovered = false;

						// Keyboard focus still inside keeps it paused.
						if ( ! this.hasFocusWithin ) {
							this.swiper.autoplay.start();
						}
					},
				} );
			}

			togglePauseOnFocus(toggleOn) {
				const $wrap = this.$carouselWrap || this.elements.$swiperContainer;

				$wrap.off( '.ppFocus' );

				if ( ! toggleOn ) {
					return;
				}

				$wrap.on( {
					'focusin.ppFocus': () => {
						this.hasFocusWithin = true;

						if ( this.swiper && this.swiper.autoplay && this.swiper.autoplay.running ) {
							this.swiper.autoplay.stop();
						}
					},
					'focusout.ppFocus': ( e ) => {
						// Moving between a slide, an arrow and a dot stays inside.
						if ( e.relatedTarget && $wrap[0].contains( e.relatedTarget ) ) {
							return;
						}

						this.hasFocusWithin = false;

						if ( ! this.isHovered && this.swiper && this.swiper.autoplay && ! this.swiper.autoplay.running ) {
							this.swiper.autoplay.start();
						}
					},
				} );
			}

			/**
			 * Silence the slide status while slides move by themselves; announce the moves a user makes.
			 *
			 * @since x.x.x
			 */
			syncCarouselLive() {
				const running = !! ( this.swiper && this.swiper.autoplay && this.swiper.autoplay.running );

				this.$element.find( '.pp-post-carousel-status, .pp-coupons-carousel-status' ).attr( 'aria-live', running ? 'off' : 'polite' );
			}

			updateSlideA11y() {
				if ( ! this.swiper || ! this.swiper.slides ) {
					return;
				}

				// Read the slides from Swiper, so loop clones created after init are included.
				$( Array.prototype.slice.call( this.swiper.slides ) ).each( function() {
					const $slide = $(this);
					const isShown = ! $slide.hasClass( 'swiper-slide-duplicate' ) &&
						( $slide.hasClass( 'swiper-slide-visible' ) || $slide.hasClass( 'swiper-slide-active' ) );

					if ( isShown ) {
						$slide.removeAttr( 'aria-hidden' );
					} else {
						$slide.attr( 'aria-hidden', 'true' );
					}

					$slide.find( 'a[href], button, input, select, textarea, [tabindex]' ).each( function() {
						const $el = $(this);

						// Remember the original tabindex once, so showing a slide restores it rather than forcing 0.
						if ( undefined === $el.attr( 'data-pp-tabindex' ) ) {
							$el.attr( 'data-pp-tabindex', $el.attr( 'tabindex' ) || '' );
						}

						if ( ! isShown ) {
							$el.attr( 'tabindex', '-1' );
							return;
						}

						const original = $el.attr( 'data-pp-tabindex' );

						if ( '' === original ) {
							$el.removeAttr( 'tabindex' );
						} else {
							$el.attr( 'tabindex', original );
						}
					} );
				} );
			}

			setEqualHeight() {
				let activeSlide = this.$element.find( '.swiper-slide-visible' ),
					maxHeight     = -1;

				activeSlide.each( function() {
					let post       = $(this).find( '.pp-post' ),
						postHeight = post.outerHeight();

					if ( maxHeight < postHeight ) {
						maxHeight = postHeight;
					}
				});

				activeSlide.each( function() {
					let selector = $(this).find( '.pp-post' );

					selector.animate({ height: maxHeight }, { duration: 200, easing: 'linear' });
				});
			}
		}

		const widgets = {
			'posts': [ 'default', 'classic', 'card', 'checkerboard', 'creative', 'event', 'news', 'portfolio', 'overlap', 'template' ],
			'coupons': '',
		}

		$.each( widgets, function( widget, skin ) {
			if ( 'object' ===  typeof skin ) {
				$.each( skin, function( index, wSkin ) {
					elementorFrontend.elementsHandler.attachHandler( 'pp-' + widget, PostsWidget, wSkin );
				});
			} else {
				elementorFrontend.elementsHandler.attachHandler( 'pp-' + widget, PostsWidget );
			}
		});
	} );
})(jQuery);