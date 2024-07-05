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

			getLayout() {
				let layout = ( undefined !== this.elements.$posts.data('layout') ) ? this.elements.$posts.data('layout') : 'grid';

				if ( 'pp-coupons' === this.getWidgetType() ) {
					layout = this.getElementSettings('layout');
				}

				return layout;
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

				const swiperOptions = {
					// initialSlide:               this.getInitialSlide(),
					slidesPerView:              this.getSlidesPerView('desktop'),
					slidesPerGroup:             this.getSlidesToScroll('desktop'),
					spaceBetween:               this.getSpaceBetween(),
					loop:                       sliderSettings.loop,
					centeredSlides:             'yes' === sliderSettings.centered_slides,
					speed:                      sliderSettings.speed,
					autoHeight:                 true,
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

				if ( !this.isEdit && sliderSettings.autoplay ) {
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
				const selector = this.$element.find( '.pp-posts' ),
					layout = this.getLayout();

				this.$element.imagesLoaded( function(e) {
					selector.isotope({
						layoutMode: layout,
						itemSelector: '.pp-grid-item-wrap',
					});

				});
			}

			initFilters() {
				const self = this;

				this.elements.$filters.find( '.pp-post-filter' ).off( 'click' ).on( 'click', function() {
					$(this).siblings().removeClass( 'pp-filter-current' );
					$(this).addClass( 'pp-filter-current' );

					if ( self.elements.$filtersDropdown.length > 0 ) {
						let currFilterUpdate = $(this).data( 'filter' ),
							currFilter = self.elements.$filtersDropdown.find('li[data-filter="' + currFilterUpdate + '"]');

						currFilter.siblings().removeClass( 'pp-filter-current' );
						currFilter.addClass( 'pp-filter-current' );
						self.elements.$filtersDropdown.find( '.pp-post-filters-dropdown-button' ).html( $(this).html() );
					}

					if ( self.elements.$couponsContainer.length > 0 ) {
						self.postsFilterAjax( self, $(this), 'coupon' );
					} else {
						self.postsFilterAjax( self, $(this), '' );
					}
				});

				// Post Filter Dropdown for Mobile device.
				this.elements.$filtersDropdown.find( '.pp-post-filters-dropdown-button' ).on( 'click', function() {
					self.elements.$filtersDropdown.find( '.pp-post-filters-dropdown-list' ).toggle();
				});

				this.elements.$filtersDropdown.find( '.pp-post-filters-dropdown-item' ).off( 'click' ).on( 'click', function() {
					$(this).siblings().removeClass( 'pp-filter-current' );
					$(this).addClass( 'pp-filter-current' );

					self.elements.$filtersDropdown.find( '.pp-post-filters-dropdown-list' ).hide();
					self.elements.$filtersDropdown.find( '.pp-post-filters-dropdown-button' ).html( $(this).html() );

					let currFilterUpdate = $(this).data( 'filter' ),
						currFilter = self.elements.$filters.find('li[data-filter="' + currFilterUpdate + '"]');

					currFilter.siblings().removeClass( 'pp-filter-current' );
					currFilter.addClass( 'pp-filter-current' );

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
						}, 'slow');

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
						'filter':      category ?? '',
						'taxonomy':    taxonomy ?? '',
						'page_number': ( pageCount + 1 ),
						'ajax_for':    ''
					};

					if ( $coupon_scope.length > 0 ) {
						$args.ajax_for = 'coupon';
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
							let layout = $container.find( '.pp-posts' ).data( 'layout' ),
								selector = $container.find( '.pp-posts' );

							if ( 'masonry' == layout ) {
								$container.imagesLoaded( function() {
									selector.isotope( 'reloadItems' );
									selector.isotope({
										layoutMode: layout,
										itemSelector: '.pp-grid-item-wrap',
									});
								});
							}
						}

						$count = $count + 1;

						if ( $count == self.total ) {
							$container.find( '.pp-post-load-more' ).hide();
						}

						self.$element.trigger('posts.rendered', [self.$element]);
					}
				} ).done( function() {
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

			onCouponClick() {
				const elementSettings = this.getElementSettings();

				this.elements.$coupon.each(function () {
					const couponCode = $(this).find('.pp-coupon-code').attr('data-coupon-code');

					$(this).find('.pp-coupon-code').not('.pp-copied').on('click', function() {
						if ( $(this).find( '.pp-coupon-code-no-code' ).length > 0 ) {
							return;
						}

						const clicked = $(this);
						let tempInput = '<input type="text" value="' + couponCode + '" id="ppCouponInput">';

						clicked.append(tempInput);

						const copyText = document.getElementById('ppCouponInput');
						copyText.select();
						document.execCommand('copy');
						$('#ppCouponInput').remove();

						if ('copy' === elementSettings.coupon_style) {
							clicked.addClass('pp-copied');
							clicked.find('.pp-coupon-copy-text').fadeOut().text(ppPostsScript.copied_text).fadeIn();
						} else {
							clicked.find('.pp-coupon-reveal-wrap').css({
								'transform': 'translate(200px, 0px)'
							});
							setTimeout(function () {
								clicked.find('.pp-coupon-code-text-wrap').removeClass('pp-unreavel');
								clicked.find('.pp-coupon-code-text').text(couponCode);
								clicked.find('.pp-coupon-reveal-wrap').remove();
							}, 150);
							setTimeout(function () {
								clicked.addClass('pp-copied');
								clicked.find('.pp-coupon-copy-text').fadeOut().text(ppPostsScript.copied_text).fadeIn();
							}, 500);
						}
					});
				});
			}

			async initSlider() {
				const sliderSettings    = this.getSliderSettings(),
					carouselEqualHeight = this.$element.find( '.pp-posts' ).data( 'equal-height' );

				const Swiper = elementorFrontend.utils.swiper;
    			this.swiper = await new Swiper(this.elements.$swiperContainer, this.getSwiperOptions());

				if ('yes' === sliderSettings.pause_on_hover) {
					this.togglePauseOnHover(true);
				}

				if ( 'yes' === carouselEqualHeight ) {
					this.setEqualHeight();
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