(function ($) {
    "use strict";
    
    var getElementSettings = function( $element ) {
		var elementSettings = {},
			modelCID 		= $element.data( 'model-cid' );

		if ( isEditMode && modelCID ) {
			var settings 		= elementorFrontend.config.elements.data[ modelCID ],
				settingsKeys 	= elementorFrontend.config.elements.keys[ settings.attributes.widgetType || settings.attributes.elType ];

			jQuery.each( settings.getActiveControls(), function( controlKey ) {
				if ( -1 !== settingsKeys.indexOf( controlKey ) ) {
					elementSettings[ controlKey ] = settings.attributes[ controlKey ];
				}
			} );
		} else {
			elementSettings = $element.data('settings') || {};
		}

		return elementSettings;
	};

    var isEditMode		= false;
    
    var ImageHotspotHandler = function ($scope, $) {
		var id 				= $scope.data('id'),
			elementSettings = getElementSettings( $scope ),
        	$tt_arrow       = elementSettings.tooltip_arrow,
			$tt_trigger     = elementSettings.tooltip_trigger;
		
        $('.pp-hot-spot-wrap[data-tooltip]').each(function () {
            var $tt_position        = $(this).data('tooltip-position'),
				$tt_template        = '',
				$tt_size            = $(this).data('tooltip-size'),
				$animation_in       = $(this).data('tooltip-animation-in'),
				$animation_out      = $(this).data('tooltip-animation-out');

            // tablet
            if ( window.innerWidth <= 1024 && window.innerWidth >= 768 ) {
                $tt_position = $scope.find('.pp-hot-spot-wrap[data-tooltip]').data('tooltip-position-tablet');
            }

            // mobile
            if ( window.innerWidth < 768 ) {
                $tt_position = $scope.find('.pp-hot-spot-wrap[data-tooltip]').data('tooltip-position-mobile');
            }
            
            if ( $tt_arrow == 'yes' ) {
                $tt_template = '<div class="pp-tooltip pp-tooltip-'+id+' pp-tooltip-'+$tt_size+'"><div class="pp-tooltip-body"><div class="pp-tooltip-content"></div><div class="pp-tooltip-callout"></div></div></div>';
            } else {
                $tt_template = '<div class="pp-tooltip pp-tooltip-'+id+' pp-tooltip-'+$tt_size+'"><div class="pp-tooltip-body"><div class="pp-tooltip-content"></div></div></div>';
			}
			
			var tooltipConfig = {
                template		: $tt_template,
				position		: $tt_position,
				animationIn		: $animation_in,
				animationOut	: $animation_out,
				animDuration	: 400,
                toggleable		: ($tt_trigger === 'click') ? true : false
			};

			console.log(tooltipConfig);
            
            $(this)._tooltip( tooltipConfig );
        });
    };
    
    var ImageComparisonHandler = function ($scope, $) {
        var image_comparison_elem       = $scope.find('.pp-image-comparison').eq(0),
            settings                    = image_comparison_elem.data('settings');
        
        image_comparison_elem.twentytwenty({
            default_offset_pct:         settings.visible_ratio,
            orientation:                settings.orientation,
            before_label:               settings.before_label,
            after_label:                settings.after_label,
            move_slider_on_hover:       settings.slider_on_hover,
            move_with_handle_only:      settings.slider_with_handle,
            click_to_move:              settings.slider_with_click,
            no_overlay:                 settings.no_overlay
        });
    };
    
    var CounterHandler = function ($scope, $) {
        var counter_elem                = $scope.find('.pp-counter').eq(0),
            $target                     = counter_elem.data('target');
        
        $(counter_elem).waypoint(function () {
            $($target).each(function () {
                var v                   = $(this).data("to"),
                    speed               = $(this).data("speed"),
                    od                  = new Odometer({
                        el:             this,
                        value:          0,
                        duration:       speed
                    });
                od.render();
                setInterval(function () {
                    od.update(v);
                });
            });
        },
            {
                offset:             "80%",
                triggerOnce:        true
            });
    };
    
    var LogoCarouselHandler = function ($scope, $) {
        var $carousel                   = $scope.find('.pp-logo-carousel').eq(0),
            $items                      = ($carousel.data("items") !== undefined) ? $carousel.data("items") : 3,
            $items_tablet               = ($carousel.data("items-tablet") !== undefined) ? $carousel.data("items-tablet") : 3,
            $items_mobile               = ($carousel.data("items-mobile") !== undefined) ? $carousel.data("items-mobile") : 3,
            $margin                     = ($carousel.data("margin") !== undefined) ? $carousel.data("margin") : 10,
            $margin_tablet              = ($carousel.data("margin-tablet") !== undefined) ? $carousel.data("margin-tablet") : 10,
            $margin_mobile              = ($carousel.data("margin-mobile") !== undefined) ? $carousel.data("margin-mobile") : 10,
            $effect                     = ($carousel.data("effect") !== undefined) ? $carousel.data("effect") : 'slide',
            $speed                      = ($carousel.data("speed") !== undefined) ? $carousel.data("speed") : 400,
            $autoplay                   = ($carousel.data("autoplay") !== undefined) ? $carousel.data("autoplay") : 999999,
            $loop                       = ($carousel.data("loop") !== undefined) ? $carousel.data("loop") : 0,
            $grab_cursor                = ($carousel.data("grab-cursor") !== undefined) ? $carousel.data("grab-cursor") : 0,
            $pagination                 = ($carousel.data("pagination") !== undefined) ? $carousel.data("pagination") : '.swiper-pagination',
            $pagination_type            = ($carousel.data("pagination-type") !== undefined) ? $carousel.data("pagination-type") : 'bullets',
            $arrows                     = ($carousel.data("arrows") !== undefined) ? $carousel.data("arrows") : false,
            $arrow_next                 = ($carousel.data("arrow-next") !== undefined) ? $carousel.data("arrow-next") : '.swiper-button-next',
            $arrow_prev                 = ($carousel.data("arrow-prev") !== undefined) ? $carousel.data("arrow-prev") : '.swiper-button-prev',
            
            mySwiper = new Swiper($carousel, {
                direction:              'horizontal',
                speed:                  $speed,
                autoplay:               $autoplay,
                effect:                 $effect,
                slidesPerView:          $items,
                spaceBetween:           $margin,
                grabCursor:             $grab_cursor,
                paginationClickable:    true,
                autoHeight:             true,
                loop:                   $loop,
                autoplay: {
                    delay: $autoplay,
                },
                pagination: {
                    el: $pagination,
                    type: $pagination_type,
                    clickable: true,
                },
                navigation: {
                    nextEl: $arrow_next,
                    prevEl: $arrow_prev,
                },
                breakpoints: {
                    // when window width is <= 480px
                    480: {
                        slidesPerView:  $items_mobile,
                        spaceBetween:   $margin_mobile
                    },
                    // when window width is <= 640px
                    768: {
                        slidesPerView:  $items_tablet,
                        spaceBetween:   $margin_tablet
                    }
                }
            });
    };
    
    var InfoBoxCarouselHandler = function ($scope, $) {
        var $carousel                   = $scope.find('.pp-info-box-carousel').eq(0),
            $items                      = ($carousel.data("items") !== undefined) ? $carousel.data("items") : 3,
            $items_tablet               = ($carousel.data("items-tablet") !== undefined) ? $carousel.data("items-tablet") : 3,
            $items_mobile               = ($carousel.data("items-mobile") !== undefined) ? $carousel.data("items-mobile") : 3,
            $margin                     = ($carousel.data("margin") !== undefined) ? $carousel.data("margin") : 10,
            $margin_tablet              = ($carousel.data("margin-tablet") !== undefined) ? $carousel.data("margin-tablet") : 10,
            $margin_mobile              = ($carousel.data("margin-mobile") !== undefined) ? $carousel.data("margin-mobile") : 10,
            $effect                     = ($carousel.data("effect") !== undefined) ? $carousel.data("effect") : 'slide',
            $speed                      = ($carousel.data("speed") !== undefined) ? $carousel.data("speed") : 400,
            $autoplay                   = ($carousel.data("autoplay") !== undefined) ? $carousel.data("autoplay") : 999999,
            $loop                       = ($carousel.data("loop") !== undefined) ? $carousel.data("loop") : 0,
            $grab_cursor                = ($carousel.data("grab-cursor") !== undefined) ? $carousel.data("grab-cursor") : 0,
            $dots                       = ($carousel.data("dots") !== undefined) ? $carousel.data("dots") : false,
            $pagination                 = ($carousel.data("pagination") !== undefined) ? $carousel.data("pagination") : '.swiper-pagination',
            $pagination_type            = ($carousel.data("pagination-type") !== undefined) ? $carousel.data("pagination-type") : 'bullets',
            $arrow_next                 = ($carousel.data("arrow-next") !== undefined) ? $carousel.data("arrow-next") : '.swiper-button-next',
            $arrow_prev                 = ($carousel.data("arrow-prev") !== undefined) ? $carousel.data("arrow-prev") : '.swiper-button-prev',
            $arrows                     = ($carousel.data("arrows") !== undefined) ? $carousel.data("arrows") : false,
            
            mySwiper = new Swiper($carousel, {
                direction:              'horizontal',
                speed:                  $speed,
                effect:                 $effect,
                slidesPerView:          $items,
                spaceBetween:           $margin,
                grabCursor:             $grab_cursor,
                loop:                   $loop,
                autoplay: {
                    delay: $autoplay,
                },
                pagination: {
                    el: $pagination,
                    type: $pagination_type,
                    clickable: true,
                },
                navigation: {
                    nextEl: $arrow_next,
                    prevEl: $arrow_prev,
                },
                breakpoints: {
                    // when window width is <= 480px
                    480: {
                        slidesPerView:  $items_mobile,
                        spaceBetween:   $margin_mobile
                    },
                    // when window width is <= 640px
                    768: {
                        slidesPerView:  $items_tablet,
                        spaceBetween:   $margin_tablet
                    }
                }
            });
    };
    
    var InstaFeedPopupHandler = function ($scope, $) {
        var instafeed_elem              = $scope.find('.pp-instagram-feed').eq(0),
            settings                    = instafeed_elem.data('settings'),
            pp_widget_id                = settings.target,
            pp_popup                    = settings.popup,
            like_span                   = (settings.likes === '1') ? '<span class="likes"><i class="fa fa-heart"></i> {{likes}}</span>' : '',
            comments_span               = (settings.comments === '1') ? '<span class="comments"><i class="fa fa-comment"></i> {{comments}}</span>' : '',
            $more_button                = instafeed_elem.find('.pp-load-more-button'),
        
            feed = new Instafeed({
                get:                    'user',
                userId:                 settings.user_id,
                sortBy:                 settings.sort_by,
                accessToken:            settings.access_token,
                limit:                  settings.images_count,
                target:                 pp_widget_id,
                resolution:             settings.resolution,
                orientation:            'portrait',
                template:               function () {
                    if (pp_popup === '1') {
                        if (settings.layout === 'carousel') {
                            return '<div class="pp-feed-item swiper-slide"><a href="{{image}}"><div class="pp-overlay-container">' + like_span + comments_span + '</div><img src="{{image}}" /></a></div>';
                        } else {
                            return '<div class="pp-feed-item"><a href="{{image}}"><div class="pp-overlay-container">' + like_span + comments_span + '</div><img src="{{image}}" /></a></div>';
                        }
                    } else {
                        if (settings.layout === 'carousel') {
                            return '<div class="pp-feed-item swiper-slide">' +
                                '<a href="{{link}}">' +
                                    '<div class="pp-overlay-container">' + like_span + comments_span + '</div>' +
                                    '<img src="{{image}}" />' +
                                '</a>' +
                                '</div>';
                        } else {
                            return '<div class="pp-feed-item">' +
                                '<a href="{{link}}">' +
                                    '<div class="pp-overlay-container">' + like_span + comments_span + '</div>' +
                                    '<img src="{{image}}" />' +
                                '</a>' +
                                '</div>';
                        }
                    }
                }(),
                after: function () {
                    if (settings.layout === 'carousel') {
                        var $carousel                   = $scope.find('.swiper-container').eq(0),
                            $items                      = ($carousel.data("items") !== undefined) ? $carousel.data("items") : 3,
                            $items_tablet               = ($carousel.data("items-tablet") !== undefined) ? $carousel.data("items-tablet") : 3,
                            $items_mobile               = ($carousel.data("items-mobile") !== undefined) ? $carousel.data("items-mobile") : 3,
                            $margin                     = ($carousel.data("margin") !== undefined) ? $carousel.data("margin") : 10,
                            $margin_tablet              = ($carousel.data("margin-tablet") !== undefined) ? $carousel.data("margin-tablet") : 10,
                            $margin_mobile              = ($carousel.data("margin-mobile") !== undefined) ? $carousel.data("margin-mobile") : 10,
                            $effect                     = ($carousel.data("effect") !== undefined) ? $carousel.data("effect") : 'slide',
                            $speed                      = ($carousel.data("speed") !== undefined) ? $carousel.data("speed") : 400,
                            $autoplay                   = ($carousel.data("autoplay") !== undefined) ? $carousel.data("autoplay") : 999999,
                            $loop                       = ($carousel.data("loop") !== undefined) ? $carousel.data("loop") : 0,
                            $grab_cursor                = ($carousel.data("grab-cursor") !== undefined) ? $carousel.data("grab-cursor") : 0,
                            $arrows                     = ($carousel.data("arrows") !== undefined) ? $carousel.data("arrows") : false,
                            $pagination                 = ($carousel.data("pagination") !== undefined) ? $carousel.data("pagination") : '.swiper-pagination',
                            $pagination_type            = ($carousel.data("pagination-type") !== undefined) ? $carousel.data("pagination-type") : 'bullets',

                            mySwiper = new Swiper($carousel, {
                                direction:              'horizontal',
                                speed:                  $speed,
                                effect:                 $effect,
                                slidesPerView:          $items,
                                spaceBetween:           $margin,
                                grabCursor:             $grab_cursor,
                                pagination:             '.swiper-pagination',
                                paginationClickable:    true,
                                loop:                   $loop,
                                autoplay: {
                                    delay: $autoplay,
                                },
                                pagination: {
                                    el: $pagination,
                                    type: $pagination_type,
                                    clickable: true,
                                },
                                navigation: {
                                    nextEl: '.swiper-button-next',
                                    prevEl: '.swiper-button-prev',
                                },
                                breakpoints: {
                                    // when window width is <= 480px
                                    480: {
                                        slidesPerView:  $items_mobile,
                                        spaceBetween:   $margin_mobile
                                    },
                                    // when window width is <= 640px
                                    768: {
                                        slidesPerView:  $items_tablet,
                                        spaceBetween:   $margin_tablet
                                    }
                                }
                            });
                    }
                    if (!this.hasNext()) {
                        $more_button.attr('disabled', 'disabled');
                    }
                },
                success: function() {
                    $more_button.removeClass( 'pp-button-loading' );
                    $more_button.find( '.pp-load-more-button-text' ).html( 'Load More' );
                }
            });
        
        $more_button.on('click', function() {
            feed.next();
            $more_button.addClass( 'pp-button-loading' );
            $more_button.find( '.pp-load-more-button-text' ).html( 'Loading...' );
        });
        
        feed.run();
        
        if (pp_popup === '1') {
            $(pp_widget_id).each(function () {
                $(this).magnificPopup({
                    delegate: 'div a', // child items selector, by clicking on it popup will open
                    gallery: {
                        enabled: true,
                        navigateByImgClick: true,
                        preload: [0, 1]
                    },
                    type: 'image'
                });
            });
        }
    };
    
    var TeamMemberCarouselHandler = function ($scope, $) {
        var $carousel                   = $scope.find('.pp-tm-carousel').eq(0),
            $items                      = ($carousel.data("items") !== undefined) ? $carousel.data("items") : 3,
            $items_tablet               = ($carousel.data("items-tablet") !== undefined) ? $carousel.data("items-tablet") : 2,
            $items_mobile               = ($carousel.data("items-mobile") !== undefined) ? $carousel.data("items-mobile") : 1,
            $margin                     = ($carousel.data("margin") !== undefined) ? $carousel.data("margin") : 10,
            $margin_tablet              = ($carousel.data("margin-tablet") !== undefined) ? $carousel.data("margin-tablet") : 10,
            $margin_mobile              = ($carousel.data("margin-mobile") !== undefined) ? $carousel.data("margin-mobile") : 10,
            $speed                      = ($carousel.data("speed") !== undefined) ? $carousel.data("speed") : 400,
            $autoplay                   = ($carousel.data("autoplay") !== undefined) ? $carousel.data("autoplay") : 999999,
            $loop                       = ($carousel.data("loop") !== undefined) ? $carousel.data("loop") : 0,
            $grab_cursor                = ($carousel.data("grab-cursor") !== undefined) ? $carousel.data("grab-cursor") : 0,
            $pagination                 = ($carousel.data("pagination") !== undefined) ? $carousel.data("pagination") : '.swiper-pagination',
            $pagination_type            = ($carousel.data("pagination-type") !== undefined) ? $carousel.data("pagination-type") : 'bullets',
            $arrows                     = ($carousel.data("arrows") !== undefined) ? $carousel.data("arrows") : false,
            $arrow_next                 = ($carousel.data("arrow-next") !== undefined) ? $carousel.data("arrow-next") : '.swiper-button-next',
            $arrow_prev                 = ($carousel.data("arrow-prev") !== undefined) ? $carousel.data("arrow-prev") : '.swiper-button-prev',
            
            mySwiper = new Swiper($carousel, {
                direction:              'horizontal',
                speed:                  $speed,
                slidesPerView:          $items,
                spaceBetween:           $margin,
                grabCursor:             $grab_cursor,
                paginationClickable:    true,
                loop:                   $loop,
                autoplay: {
                    delay: $autoplay,
                },
                pagination: {
                    el: $pagination,
                    type: $pagination_type,
                    clickable: true,
                },
                navigation: {
                    nextEl: $arrow_next,
                    prevEl: $arrow_prev,
                },
                breakpoints: {
                    // when window width is <= 480px
                    480: {
                        slidesPerView:  $items_mobile,
                        spaceBetween:   $margin_mobile
                    },
                    // when window width is <= 640px
                    768: {
                        slidesPerView:  $items_tablet,
                        spaceBetween:   $margin_tablet
                    }
                }
            });
    };
    
    var ImageSliderHandler = function ( $scope, $ ) {
        var $carousel            = $scope.find( '.pp-image-slider' ).eq( 0 ),
            $slider_wrap         = $scope.find( '.pp-image-slider-wrap' ),
            $thumbs_nav          = $scope.find( '.pp-image-slider-container .pp-image-slider-thumb-item-wrap' ),
            elementSettings      = getElementSettings( $scope ),
            $arrow_next          = elementSettings.arrow,
            $arrow_prev          = ( $arrow_next !== undefined ) ? $arrow_next.replace( "right", "left" ) : '',
			$items               = ( elementSettings.slides_per_view !== undefined && elementSettings.slides_per_view !== '' ) ? parseInt( elementSettings.slides_per_view ) : 1,
			$items_tablet        = ( elementSettings.slides_per_view_tablet !== undefined && elementSettings.slides_per_view_tablet !== '' ) ? parseInt( elementSettings.slides_per_view_tablet ) : 1,
			$items_mobile        = ( elementSettings.slides_per_view_mobile !== undefined && elementSettings.slides_per_view_mobile !== '' ) ? parseInt( elementSettings.slides_per_view_mobile ) : 1,
			$items_scroll        = ( elementSettings.slides_to_scroll !== undefined && elementSettings.slides_to_scroll !== '' ) ? parseInt( elementSettings.slides_to_scroll ) : 1,
			$items_scroll_tablet = ( elementSettings.slides_to_scroll_tablet !== undefined && elementSettings.slides_to_scroll_tablet !== '' ) ? parseInt( elementSettings.slides_to_scroll_tablet ) : 1,
			$items_scroll_mobile = ( elementSettings.slides_to_scroll_mobile !== undefined && elementSettings.slides_to_scroll_mobile !== '' ) ? parseInt( elementSettings.slides_to_scroll_mobile ) : 1;
        
            $carousel.slick({
                slidesToShow:           $items,
				slidesToScroll:  		$items_scroll,
                autoplay:               'yes' === elementSettings.autoplay,
                autoplaySpeed:          elementSettings.autoplay_speed,
                arrows:                 'yes' === elementSettings.arrows,
                prevArrow:              '<div class="pp-slider-arrow pp-arrow pp-arrow-prev"><i class="' + $arrow_prev + '"></i></div>',
				nextArrow:              '<div class="pp-slider-arrow pp-arrow pp-arrow-next"><i class="' + $arrow_next + '"></i></div>',
                dots:                   'yes' === elementSettings.dots,
                fade:                   'fade' === elementSettings.effect,
                speed:                  elementSettings.animation_speed,
                infinite:               'yes' === elementSettings.infinite_loop,
                pauseOnHover:           'yes' === elementSettings.pause_on_hover,
                adaptiveHeight:         'yes' === elementSettings.adaptive_height,
                rtl:                    'right' === elementSettings.direction,
                responsive: [
                    {
                    breakpoint: 1024,
                        settings: {
                            slidesToShow: $items_tablet,
							slidesToScroll: $items_scroll_tablet,
                        }
                    },
                    {
                    breakpoint: 768,
                        settings: {
                            slidesToShow: $items_mobile,
							slidesToScroll: $items_scroll_mobile,
                        }
                    },
                ]
            });

            $carousel.slick( 'setPosition' );

            if ( elementSettings.skin == 'slideshow' ) {
                $thumbs_nav.removeClass('pp-active-slide');
                $thumbs_nav.eq(0).addClass('pp-active-slide');

                $carousel.on('beforeChange', function ( event, slick, currentSlide, nextSlide ) {
                    var currentSlide = nextSlide;
                    $thumbs_nav.removeClass('pp-active-slide');
                    $thumbs_nav.eq( currentSlide ).addClass('pp-active-slide');
                });

                $thumbs_nav.each( function( currentSlide ) {
                    $(this).on( 'click', function ( e ) {
                        e.preventDefault();
                        $carousel.slick( 'slickGoTo', currentSlide );
                    });
                });
            }

            if ( isEditMode ) {
                $slider_wrap.resize( function() {
                    $carousel.slick( 'setPosition' );
                });
            }
    };

	var ModalPopupHandler = function ($scope, $) {
		var popup_elem                  = $scope.find('.pp-modal-popup').eq(0),
			$main_class                 = popup_elem.data('main-class'),
			$popup_layout               = popup_elem.data('popup-layout'),
			$close_button               = (popup_elem.data('close-button') === 'yes') ? true : false,
			$close_button_pos           = popup_elem.data('close-button-pos'),
			$effect                     = popup_elem.data('effect'),
			$type                       = popup_elem.data('type'),
			$iframe_class               = popup_elem.data('iframe-class'),
			$src                        = popup_elem.data('src'),
			$trigger_element            = popup_elem.data('trigger-element'),
			$delay                      = popup_elem.data('delay'),
			$trigger                    = popup_elem.data('trigger'),
			$popup_id                   = popup_elem.data('popup-id'),
			$display_after              = popup_elem.data('display-after'),
			$esc_exit                   = (popup_elem.data('esc') === 'yes') ? true : false,
			$click_exit                 = (popup_elem.data('click') === 'yes') ? true : false;
			$main_class += ' ' + $popup_layout + ' ' + $close_button_pos + ' ' + $effect;
		//console.log($main_class);
		if ($trigger == 'exit-intent') {
			var flag = true,
				mouseY = 0,
				topValue = 0;

			if ( $display_after === 0 ) {
				$.removeCookie($popup_id, { path: '/' });
			}
			window.addEventListener("mouseout", function (e) {
				mouseY = e.clientY;
				if (mouseY < topValue && !$.cookie($popup_id) ) {
					$.magnificPopup.open({
						items: {
							src: $src //ID of inline element
						},
						type: $type,
						showCloseBtn: $close_button,
						enableEscapeKey: $esc_exit,
						closeOnBgClick: $click_exit,
						removalDelay: 500, //Delaying the removal in order to fit in the animation of the popup
						mainClass: 'mfp-fade mfp-fade-side', //The actual animation
					});

					if ( $display_after > 0 ) {
						$.cookie($popup_id, $display_after, { expires: $display_after, path: '/' });
					} else {
						$.removeCookie( $popup_id );
					}
				}
			},
			false);
		}
		else if ( $trigger == 'page-load') {
			if ( $display_after === 0 ) {
				$.removeCookie($popup_id, { path: '/' });
			}
			if ( !$.cookie($popup_id) ) {
				setTimeout(function() {
					$.magnificPopup.open({
						items: {
							src: $src 
						},
						type: $type,
						showCloseBtn: $close_button,
						enableEscapeKey: $esc_exit,
						closeOnBgClick: $click_exit,
					});

					if ( $display_after > 0 ) {
						$.cookie($popup_id, $display_after, { expires: $display_after, path: '/' });
					} else {
						$.removeCookie( $popup_id );
					}
				}, $delay);
			}
		} else {
			if (typeof $trigger_element === 'undefined' || $trigger_element === '') {
				$trigger_element = '.pp-modal-popup-link'
			}
			//console.log($trigger_element);
			$( $trigger_element ).magnificPopup({
				image: {
					markup: '<div class="' + $iframe_class + '">'+
							'<div class="modal-popup-window-inner">'+
							'<div class="mfp-figure">'+
							'<div class="mfp-close"></div>'+
							'<div class="mfp-img"></div>'+
							'<div class="mfp-bottom-bar">'+
								'<div class="mfp-title"></div>'+
								'<div class="mfp-counter"></div>'+
							'</div>'+
							'</div>'+
							'</div>'+
							'</div>',
				},
				iframe: {
					markup: '<div class="' + $iframe_class + '">'+
							'<div class="modal-popup-window-inner">'+
							'<div class="mfp-iframe-scaler">'+
								'<div class="mfp-close"></div>'+
								'<iframe class="mfp-iframe" frameborder="0" allowfullscreen></iframe>'+
							'</div>'+
							'</div>'+
							'</div>',
				},
				items: {
					src: $src,
					type: $type,
				},
				removalDelay: 500,
				showCloseBtn: $close_button,
				enableEscapeKey: $esc_exit,
				closeOnBgClick: $click_exit,
				mainClass: $main_class,
			});
		}
		$.extend(true, $.magnificPopup.defaults, {
			tClose: 'Close',
		});
	};

	var TableHandler = function ($scope, $) {
		var table_elem      = $scope.find('.pp-table').eq(0);
		
		$( document ).trigger( "enhance.tablesaw" );
	};

	var AdvancedTabsHandler = function ($scope, $) {
		var titleContainer     = $scope.find('.pp-advanced-tabs'),
			titleMain          = $scope.find('.pp-advanced-tabs-wrapper'),
			titleVertical      = $scope.find('.pp-advanced-tabs-wrapper.at-vertical'),
			titleWrap          = titleMain.find('.pp-advanced-tabs-title'),
			titleFirstChild    = titleMain.find(">:first-child").toggleClass(titleWrap ),
			// Content
			contentWrap        = $scope.find('.pp-advanced-tabs-content-wrapper'),
			contentTab         = $scope.find('.pp-advanced-tabs-content-wrapper.at-vertical-content'),
			contentSection     = $scope.find('.pp-advanced-tabs-content'),
			contentFirstChild  = contentWrap.find(".pp-advanced-tabs-content:nth-child(2)"),
			// ResponsiveTab
			responsiveTab      = contentWrap.find(".pp-advanced-tabs-title.pp-tab-responsive"),
			responsiveTabInner = responsiveTab.find(".pp-advanced-tabs-title-inner");

		titleFirstChild.find('.active-slider-span').addClass('activated-slider-span');

		if ($(window).width() < 768) {
			contentFirstChild.fadeIn();
		}

		$( titleWrap ).click( function(){
			var tabId = $( this ).data( 'index' );
			titleWrap.removeClass('at-active');
			titleWrap.find('.active-slider-span').removeClass('activated-slider-span');

			$( this ).addClass('at-active');
			$( this ).find('.active-slider-span').addClass('activated-slider-span');

			var contentActive = contentWrap.find('#pp-advanced-tabs-content-' + tabId );
			contentSection.removeClass('at-active-content');
			contentActive.addClass('at-active-content');
		});

// For Responsive Tabs

	responsiveTabInner.find('.pp-toggle-icon').addClass('fa-plus');
		$( responsiveTab ).click(function () {
			var $this           = $(this),
				responsiveTabId = $this.data( 'index' ),
				contentActive   = contentWrap.find('#pp-advanced-tabs-content-' + responsiveTabId );

			responsiveTab.find('.pp-toggle-icon').removeClass('fa-minus');
			contentSection.slideUp();

			if ( ! $this.hasClass('responsive-active') ) {
				$this.addClass('responsive-active');
				contentActive.slideDown(400, function () {
					if (contentActive.is(':visible')) {
						$this.find('.pp-toggle-icon').addClass('fa-minus');
						contentActive.css('display', 'block');
					} else {
						$this.find('.pp-toggle-icon').removeClass('fa-minus');
					}
				});
			} else {
				responsiveTab.removeClass('responsive-active');
			}
		});
// Height
		contentTab.each(function () {
			var highestBox = 0;
			$( '.pp-advanced-tabs-content', this ).each(function () {
				if ( $( this ).outerHeight() > highestBox) {
					highestBox = $( this ).outerHeight();
				}
			});
			var titleVerticalHeight = titleVertical.outerHeight();

			var maxHeight = Math.max(titleVerticalHeight, highestBox);

			// Responsive Content Height
			if ( $(window).width() < 768 ) {
				contentSection.css('height', 'auto');
			} else {
				contentSection.css( 'height', maxHeight+'px' );
			}
			titleVertical.css( 'height', maxHeight+'px' );
		});
	}
	
	var PPCountdownHandler = function ($scope, $) {
		var wrap = $scope.find('.pp-countdown-wrapper'),
			settings = JSON.parse( $scope.find('[name=pp-countdown-settings]').val() );

		new PPCountdown( settings, $scope, $ );
	};

    var ToggleHandler = function ($scope, $) {
        var toggle_elem             = $scope.find('.pp-toggle-container').eq(0);
        $(toggle_elem).each(function () {
            var $toggle_target      = $(this).data('toggle-target');
            var $toggle_switch      = $($toggle_target).find('.pp-toggle-switch');
            $($toggle_target).find('.pp-primary-toggle-label').addClass("active");
            $($toggle_switch).toggle(
                function() {
                    var $parent_container = $(this).closest('.pp-toggle-container');
                    console.log($parent_container);
                    $($parent_container).find('.pp-toggle-content-wrap').removeClass("primary");
                    $($parent_container).children('.pp-toggle-content-wrap').addClass("secondary");
                    $($parent_container).find('.pp-toggle-switch-container').addClass("pp-toggle-switch-on");
                    $(this).parent().parent().find('.pp-primary-toggle-label').removeClass("active");
                    $(this).parent().parent().find('.pp-secondary-toggle-label').addClass("active");
                },
                function() {
                    var $parent_container = $(this).closest('.pp-toggle-container');
                    $($parent_container).children('.pp-toggle-content-wrap').addClass("primary");
                    $($parent_container).children('.pp-toggle-content-wrap').removeClass("secondary");
                    $($parent_container).find('.pp-toggle-switch-container').removeClass("pp-toggle-switch-on");
                    $(this).parent().parent().find('.pp-primary-toggle-label').addClass("active");
                    $(this).parent().parent().find('.pp-secondary-toggle-label').removeClass("active");
                }
            );
        });
    };

	var AdvancedMenuHandler = function ($scope, $) {

		new PPAdvancedMenu( $scope );
		
	};
    
    var PPCountdownHandler = function ($scope, $) {
		var wrap = $scope.find('.pp-countdown-wrapper'),
			settings = JSON.parse( $scope.find('[name=pp-countdown-settings]').val() );

		new PPCountdown( settings, $scope, $ );
	};

	var AdvancedMenuHandler = function ($scope, $) {

		new PPAdvancedMenu( $scope );

	};
    
    var ImageGalleryHandler = function ($scope, $) {
        
        var $gallery = $scope.find('.pp-image-gallery').eq(0),
            settings = $gallery.data('settings'),
            cachedItems = [],
            cachedIds = [];
        
        if ( ! isEditMode ) {

            if ( $gallery.hasClass('pp-image-gallery-masonry') || $gallery.hasClass('pp-image-gallery-filter-enabled') || settings.pagination == 'yes' ) {

                var $layout_mode = 'fitRows';

                if ( $gallery.hasClass('pp-image-gallery-masonry') ) {
                    $layout_mode = 'masonry';
                }

                var $isotope_args = {
                    itemSelector:   '.pp-grid-item-wrap',
                    layoutMode		: $layout_mode,
                    percentPosition : true,
                },
                    $isotope_gallery = {};

                $scope.imagesLoaded( function(e) {
                    $isotope_gallery = $gallery.isotope( $isotope_args );
                });

                $scope.on( 'click', '.pp-gallery-filter', function() {
                    var $this = $(this),
                        filterValue = $this.attr('data-filter');

                    $this.siblings().removeClass('pp-active');
                    $this.addClass('pp-active');

                    $isotope_gallery.isotope({ filter: filterValue });
                });
            }
        }

		var $tilt_enable = (settings.tilt_enable !== undefined) ? settings.tilt_enable : '';

        if ( $tilt_enable == 'yes' ) {
            $( $gallery ).find('.pp-grid-item').tilt({
                disableAxis: settings.tilt_axis,
                maxTilt: settings.tilt_amount,
                scale: settings.tilt_scale,
                speed: settings.tilt_speed,
				perspective: 1000,
            });
		}
		
		$gallery.find('.pp-grid-item-wrap').each(function() {
			cachedIds.push( $(this).data('item-id') );
		});
		
		// Load More
		$scope.find('.pp-gallery-load-more').on('click', function(e) {
			e.preventDefault();

			var $this = $(this);
			$this.addClass('disabled pp-loading');

			if ( cachedItems.length > 0 ) {
				gallery_render_items();
			} else {

				var data = {
					action: 'pp_gallery_get_images',
					pp_action: 'pp_gallery_get_images',
					settings: settings
				};

				$.ajax({
					type: 'post',
					url: window.location.href.split( '#' ).shift(),
					data: data,
					success: function(response) {
						if ( response.success ) {
							var items = response.data.items;
							if ( items ) {
								$(items).each(function() {
									if ( $(this).hasClass('pp-grid-item-wrap') ) {
										cachedItems.push( this );
									}
								});
							}

							gallery_render_items();
						}
					},
					error: function(xhr, desc) {
						console.log(desc);
					}
				});
			}
		});
	
		function gallery_render_items() {
			$scope.find('.pp-gallery-load-more').removeClass( 'disabled pp-loading' );

			if ( cachedItems.length > 0 ) {
				var count = 1;
				var items = [];

				$(cachedItems).each(function() {
					var id = $(this).data('item-id');

					if ( -1 === $.inArray( id, cachedIds ) ) {
						if ( count <= parseInt( settings.per_page ) ) {
							cachedIds.push( id );
							items.push( this );
							count++;
						} else {
							return false;
						}
					}
				});

				if ( items.length > 0 ) {
					items = $(items);

					$gallery.isotope('insert', items);
					setTimeout(function() {
						$gallery.isotope('layout');
					}, 500);
				}
                
                if ( $tilt_enable == 'yes' ) {
                    $( $gallery ).find('.pp-grid-item').tilt({
                        disableAxis: settings.tilt_axis,
                        maxTilt: settings.tilt_amount,
                        scale: settings.tilt_scale,
                        speed: settings.tilt_speed,
                    });
                }

				if ( cachedItems.length === cachedIds.length ) {
					$scope.find('.pp-gallery-pagination').hide();
				}
			}
		}
	};
	
	var OffCanvasContentHandler = function ($scope, $) {
		new PPOffcanvasContent( $scope );
	};

	var PPButtonHandler = function ( $scope, $) {
		var id = $scope.data('id');
		var ttipPosition = $scope.find('.pp-button[data-tooltip]').data('tooltip-position');

		// tablet
		if ( window.innerWidth <= 1024 && window.innerWidth >= 768 ) {
			ttipPosition = $scope.find('.pp-button[data-tooltip]').data('tooltip-position-tablet');
		}
		// mobile
		if ( window.innerWidth < 768 ) {
			ttipPosition = $scope.find('.pp-button[data-tooltip]').data('tooltip-position-mobile');
		}
		$scope.find('.pp-button[data-tooltip]')._tooltip( {
			template: '<div class="pp-tooltip pp-tooltip-'+id+'"><div class="tooltip-body"></div><div class="tooltip-callout"></div></div>',
			position: ttipPosition,
			animDuration: 400
		} );
	};

    
    var ShowcaseHandler = function ( $scope, $ ) {
        var $carousel            = $scope.find( '.pp-showcase-preview' ).eq( 0 ),
            $slider_wrap         = $scope.find( '.pp-showcase-preview-wrap' ),
            $nav_wrap            = $scope.find( '.pp-showcase-navigation-items' ),
            $nav                 = $scope.find( '.pp-showcase .pp-showcase-navigation-item-wrap' ),
            $video_wrap          = $scope.find( '.pp-showcase .pp-video-container' ),
            elementSettings      = getElementSettings( $scope ),
            $arrow_next          = elementSettings.arrow,
            $arrow_prev          = ( $arrow_next !== undefined ) ? $arrow_next.replace( "right", "left" ) : '',
            $scrollable_nav      = elementSettings.scrollable_nav,
            $preview_position    = elementSettings.preview_position,
            $stack_on            = elementSettings.preview_stack;
        
            $carousel.slick({
                slidesToShow:           1,
				slidesToScroll:  		1,
                autoplay:               'yes' === elementSettings.autoplay,
                autoplaySpeed:          elementSettings.autoplay_speed,
                arrows:                 'yes' === elementSettings.arrows,
                prevArrow:              '<div class="pp-slider-arrow pp-arrow pp-arrow-prev"><i class="' + $arrow_prev + '"></i></div>',
				nextArrow:              '<div class="pp-slider-arrow pp-arrow pp-arrow-next"><i class="' + $arrow_next + '"></i></div>',
                dots:                   'yes' === elementSettings.dots,
                fade:                   'fade' === elementSettings.effect,
                speed:                  elementSettings.animation_speed,
                infinite:               'yes' === elementSettings.infinite_loop,
                pauseOnHover:           'yes' === elementSettings.pause_on_hover,
                adaptiveHeight:         'yes' === elementSettings.adaptive_height,
                rtl:                    'right' === elementSettings.direction,
                asNavFor:               ( $scrollable_nav == 'yes' ) ? $nav_wrap : '',
            });

            $carousel.slick( 'setPosition' );
        
            if ( $scrollable_nav == 'yes' ) {
                
                $nav_wrap.slick({
                    slidesToShow:       ( elementSettings.nav_items !== undefined && elementSettings.nav_items !== '' ) ? parseInt( elementSettings.nav_items ) : 5,
                    slidesToScroll:     1,
                    asNavFor:           $carousel,
                    arrows:             false,
                    dots:               false,
                    infinite:           'yes' === elementSettings.infinite_loop,
                    centerMode:         true,
                    focusOnSelect:      true,
                    vertical:           ($preview_position == 'top') ? false : true,
                    centerMode:         true,
                    centerPadding:      '0px',
                    responsive:         [
                        {
                        breakpoint: 1024,
                            settings: {
                                slidesToShow: ( elementSettings.nav_items_tablet !== undefined && elementSettings.nav_items_tablet !== '' ) ? parseInt( elementSettings.nav_items_tablet ) : 3,
                                slidesToScroll: 1,
                                vertical: ($stack_on == 'tablet') ? false : true,
                            }
                        },
                        {
                        breakpoint: 768,
                            settings: {
                                slidesToShow: ( elementSettings.nav_items_mobile !== undefined && elementSettings.nav_items_mobile !== '' ) ? parseInt( elementSettings.nav_items_mobile ) : 2,
                                slidesToScroll: 1,
                                vertical: false,
                            }
                        },
                    ],
                });
                
            } else {
                
                $nav.removeClass('pp-active-slide');
                $nav.eq(0).addClass('pp-active-slide');

                $carousel.on('beforeChange', function ( event, slick, currentSlide, nextSlide ) {
                    var currentSlide = nextSlide;
                    $nav.removeClass('pp-active-slide');
                    $nav.eq( currentSlide ).addClass('pp-active-slide');
                });

                $nav.each( function( currentSlide ) {
                    $(this).on( 'click', function ( e ) {
                        e.preventDefault();
                        $carousel.slick( 'slickGoTo', currentSlide );
                    });
                });
                
            }

            if ( isEditMode ) {
                $slider_wrap.resize( function() {
                    $carousel.slick( 'setPosition' );
                });
            }
        
            $video_wrap.off( 'click' ).on( 'click', function( e ) {

                var $iframe = $( "<iframe/>" ),
                    $vid_src = $( this ).data( 'src' ),
                    $player = $( this ).find( '.pp-video-player' );
                
                $iframe.attr( 'src', $vid_src );
				$iframe.attr( 'frameborder', '0' );
				$iframe.attr( 'allowfullscreen', '1' );
				$iframe.attr( 'allow', 'autoplay;encrypted-media;' );

				$player.html( $iframe );

            });
    };
    
    var TimelineHandler = function ( $scope, $ ) {
        var $carousel            = $scope.find( '.pp-timeline-horizontal .pp-timeline-items' ).eq( 0 ),
            $slider_wrap         = $scope.find( '.pp-timeline-wrapper' ),
            $slider_nav          = $scope.find( '.pp-timeline-navigation' ),
            elementSettings      = getElementSettings( $scope ),
            $arrow_next          = elementSettings.arrow,
            $arrow_prev          = ( $arrow_next !== undefined ) ? $arrow_next.replace( "right", "left" ) : '',
			$items               = ( elementSettings.columns !== undefined && elementSettings.columns !== '' ) ? parseInt( elementSettings.columns ) : 3,
			$items_tablet        = ( elementSettings.columns_tablet !== undefined && elementSettings.columns_tablet !== '' ) ? parseInt( elementSettings.columns_tablet ) : 2,
			$items_mobile        = ( elementSettings.columns_mobile !== undefined && elementSettings.columns_mobile !== '' ) ? parseInt( elementSettings.columns_mobile ) : 1;
        
		if ( elementSettings.layout == 'horizontal' ) {
			$carousel.slick({
				slidesToShow:           $items,
				slidesToScroll:  		1,
				autoplay:               'yes' === elementSettings.autoplay,
				autoplaySpeed:          elementSettings.autoplay_speed,
				arrows:                 false,
				centerMode:             true,
				speed:                  elementSettings.animation_speed,
				infinite:               true,
				rtl:                    'right' === elementSettings.direction,
				asNavFor:               $slider_nav,
				responsive: [
					{
					breakpoint: 1024,
						settings: {
							slidesToShow: $items_tablet,
						}
					},
					{
					breakpoint: 768,
						settings: {
							slidesToShow: $items_mobile,
						}
					},
				]
			});

			$slider_nav.slick({
				slidesToShow:           $items,
				slidesToScroll:  		1,
				autoplay:               'yes' === elementSettings.autoplay,
				autoplaySpeed:          elementSettings.autoplay_speed,
				asNavFor:               $carousel,
				arrows:                 'yes' === elementSettings.arrows,
				prevArrow:              '<div class="pp-slider-arrow pp-arrow pp-arrow-prev"><i class="' + $arrow_prev + '"></i></div>',
				nextArrow:              '<div class="pp-slider-arrow pp-arrow pp-arrow-next"><i class="' + $arrow_next + '"></i></div>',
				centerMode:             true,
				infinite:               true,
				focusOnSelect:          true,
				responsive: [
					{
					breakpoint: 1024,
						settings: {
							slidesToShow: $items_tablet,
						}
					},
					{
					breakpoint: 768,
						settings: {
							slidesToShow: $items_mobile,
						}
					},
				]
			});

			$carousel.slick( 'setPosition' );

			if ( isEditMode ) {
				$slider_wrap.resize( function() {
					$carousel.slick( 'setPosition' );
				});
			}
		}

		// PPTimeline
		var settings = {};

		if ( isEditMode ) {
			settings.window = elementor.$previewContents;
		}

		var timeline = new PPTimeline( settings, $scope );
    };
    
    var CardSliderHandler = function ($scope, $) {
        var $carousel                   = $scope.find('.pp-card-slider').eq(0),
            elementSettings             = getElementSettings( $scope ),
            $speed                      = ($carousel.data("speed") !== undefined) ? $carousel.data("speed") : 400,
            $autoplay                   = (elementSettings.autoplay === 'yes') ? true : false,
            $autoplay_delay             = ($autoplay == true) ? elementSettings.autoplay_speed : 999999,
            $pagination                 = (elementSettings.pagination === 'yes') ? '.swiper-pagination' : '',
            $arrows                     = ($carousel.data("arrows") !== undefined) ? $carousel.data("arrows") : false,
            $arrow_next                 = ($carousel.data("arrow-next") !== undefined) ? $carousel.data("arrow-next") : '.swiper-button-next',
            $arrow_prev                 = ($carousel.data("arrow-prev") !== undefined) ? $carousel.data("arrow-prev") : '.swiper-button-prev',
            
            mySwiper = new Swiper($carousel, {
                direction:              'horizontal',
                speed:                  $speed,
                effect:                 'fade',
                fadeEffect: {
                    crossFade: true
                },
                slidesPerView:          1,
                grabCursor:             'yes' === elementSettings.grab_cursor,
                paginationClickable:    true,
                autoHeight:             false,
                loop:                   'yes' === elementSettings.loop,
                autoplay: {
                    delay: $autoplay_delay,
                },
                pagination: {
                    el: $pagination,
                    type: elementSettings.pagination_type,
                    clickable: true,
                },
                navigation: {
                    nextEl: $arrow_next,
                    prevEl: $arrow_prev,
                },
            });
    };
    
    $(window).on('elementor/frontend/init', function () {
        if ( elementorFrontend.isEditMode() ) {
			isEditMode = true;
		}
        
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-image-hotspots.default', ImageHotspotHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-image-comparison.default', ImageComparisonHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-counter.default', CounterHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-logo-carousel.default', LogoCarouselHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-info-box-carousel.default', InfoBoxCarouselHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-instafeed.default', InstaFeedPopupHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-team-member-carousel.default', TeamMemberCarouselHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-modal-popup.default', ModalPopupHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-table.default', TableHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-toggle.default', ToggleHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-countdown.default', PPCountdownHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-image-gallery.default', ImageGalleryHandler);
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-image-slider.default', ImageSliderHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/pp-advanced-menu.default', AdvancedMenuHandler);
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pp-advanced-tabs.default', AdvancedTabsHandler );
		elementorFrontend.hooks.addAction('frontend/element_ready/pp-offcanvas-content.default', OffCanvasContentHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/pp-buttons.default', PPButtonHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/pp-showcase.default', ShowcaseHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/pp-timeline.default', TimelineHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/pp-card-slider.default', CardSliderHandler);
    });
    
}(jQuery));