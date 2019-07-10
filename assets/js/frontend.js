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
                var from                = $(this).data("from"),
					to                   = $(this).data("to"),
                    speed               = $(this).data("speed"),
                    od                  = new Odometer({
                        el:             this,
                        value:          1890,
                        duration:       speed
                    });
                od.render();
                setInterval(function () {
                    od.update(to);
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
    
    var ImageScrollHandler = function($scope, $) {
        var scrollElement    = $scope.find(".pp-image-scroll-container"),
            scrollOverlay    = scrollElement.find(".pp-image-scroll-overlay"),
            scrollVertical   = scrollElement.find(".pp-image-scroll-vertical"),
			elementSettings  = getElementSettings( $scope ),
            imageScroll      = scrollElement.find('.pp-image-scroll-image img'),
            direction        = elementSettings.direction_type,
            reverse			 = elementSettings.reverse,
            trigger			 = elementSettings.trigger_type,
            transformOffset  = null;
        
        function startTransform() {
            imageScroll.css("transform", (direction == "vertical" ? "translateY" : "translateX") + "( -" +  transformOffset + "px)");
        }
        
        function endTransform() {
            imageScroll.css("transform", (direction == 'vertical' ? "translateY" : "translateX") + "(0px)");
        }
        
        function setTransform() {
            if( direction == "vertical" ) {
                transformOffset = imageScroll.height() - scrollElement.height();
            } else {
                transformOffset = imageScroll.width() - scrollElement.width();
            }
        }
        
        if( trigger == "scroll" ) {
            scrollElement.addClass("pp-container-scroll");
            if ( direction == "vertical" ) {
                scrollVertical.addClass("pp-image-scroll-ver");
            } else {
                scrollElement.imagesLoaded(function() {
                  scrollOverlay.css( { "width": imageScroll.width(), "height": imageScroll.height() } );
                });
            }
        } else {
            if ( reverse === 'yes' ) {
                scrollElement.imagesLoaded(function() {
                    scrollElement.addClass("pp-container-scroll-instant");
                    setTransform();
                    startTransform();
                });
            }
            if ( direction == "vertical" ) {
                scrollVertical.removeClass("pp-image-scroll-ver");
            }
            scrollElement.mouseenter(function() {
                scrollElement.removeClass("pp-container-scroll-instant");
                setTransform();
                reverse === 'yes' ? endTransform() : startTransform();
            });

            scrollElement.mouseleave(function() {
                reverse === 'yes' ? startTransform() : endTransform();
            });
        }
    };
    
    var AdvancedAccordionHandler = function ($scope, $) {
    	var $advanced_accordion         = $scope.find(".pp-advanced-accordion").eq(0),
            elementSettings             = getElementSettings( $scope ),
        	$accordion_title            = $scope.find(".pp-accordion-tab-title"),
        	$accordion_type             = elementSettings.accordion_type,
        	$accordion_speed            = elementSettings.toggle_speed;
			
        // Open default actived tab
        $accordion_title.each(function(){
            if ( $(this).hasClass('pp-accordion-tab-active-default') ) {
                $(this).addClass('pp-accordion-tab-show pp-accordion-tab-active');
                $(this).next().slideDown($accordion_speed)
            }
        })

        // Remove multiple click event for nested accordion
        $accordion_title.unbind("click");

        $accordion_title.click(function(e) {
            e.preventDefault();

            var $this = $(this);

            if ( $accordion_type === 'accordion' ) {
                if ( $this.hasClass("pp-accordion-tab-show") ) {
                    $this.removeClass("pp-accordion-tab-show pp-accordion-tab-active");
                    $this.next().slideUp($accordion_speed);
                } else {
                    $this.parent().parent().find(".pp-accordion-tab-title").removeClass("pp-accordion-tab-show pp-accordion-tab-active");
                    $this.parent().parent().find(".pp-accordion-tab-content").slideUp($accordion_speed);
                    $this.toggleClass("pp-accordion-tab-show pp-accordion-tab-active");
                    $this.next().slideToggle($accordion_speed);
                }
            } else {
                // For acccordion type 'toggle'
                if ( $this.hasClass("pp-accordion-tab-show") ) {
                    $this.removeClass("pp-accordion-tab-show pp-accordion-tab-active");
                    $this.next().slideUp($accordion_speed);
                } else {
                    $this.addClass("pp-accordion-tab-show pp-accordion-tab-active");
                    $this.next().slideDown($accordion_speed);
                }
            }
        });
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
			template: '<div class="pp-tooltip pp-tooltip-'+id+'"><div class="pp-tooltip-body"><div class="pp-tooltip-content"></div><div class="pp-tooltip-callout"></div></div></div>',
			position: ttipPosition,
			animDuration: 400
		} );
	};

	var TwitterTimelineHandler = function ($scope, $) {
		$(document).ready(function () {
			if ('undefined' !== twttr) {
				twttr.widgets.load();
			}
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
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-scroll-image.default', ImageScrollHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/pp-advanced-accordion.default', AdvancedAccordionHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/pp-buttons.default', PPButtonHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/pp-twitter-timeline.default', TwitterTimelineHandler);
		elementorFrontend.hooks.addAction('frontend/element_ready/pp-twitter-tweet.default', TwitterTimelineHandler);
    });
    
}(jQuery));