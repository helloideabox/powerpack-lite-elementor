(function ($) {
	$( window ).on( 'elementor/frontend/init', () => {
		class ContentRevealWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						contentWrapper: '.pp-content-reveal-content-wrapper',
						content: '.pp-content-reveal-content',
						saparator: '.pp-content-reveal-saparator',
						button: '.pp-content-reveal-button-inner',
						buttonWrapper: '.pp-content-reveal-button-wrapper',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$contentWrapper: this.$element.find( selectors.contentWrapper ),
					$content: this.$element.find( selectors.content ),
					$saparator: this.$element.find( selectors.saparator ),
					$button: this.$element.find( selectors.button ),
					$buttonWrapper: this.$element.find( selectors.buttonWrapper ),
				};
			}

			bindEvents() {
				const defaultContentState = this.getElementSettings('default_content_state'),
					contentVisibility     = this.elements.$contentWrapper.data('visibility'),
					contentHeightCustom   = this.elements.$contentWrapper.data('content-height'),
					contentHeightLines    = this.elements.$contentWrapper.data('lines'),
					contentLineHeight     = this.elements.$contentWrapper.find('.pp-content-reveal-content p').css('line-height'),
					contentPaddingTop 	  = this.elements.$content.css('padding-top');

				var contentWrapperHeight;

				if ( 'reveal' === defaultContentState ) {
					this.elements.$saparator.hide();
				}

				if ( contentVisibility == 'lines' ) {
					if ( contentHeightLines == '0' ) {
						contentWrapperHeight = contentWrapper.outerHeight();
					} else {
						contentWrapperHeight = (parseInt(contentLineHeight, 10) * contentHeightLines) + parseInt(contentPaddingTop, 10);

						if ( 'unreveal' === defaultContentState ) {
							this.elements.$contentWrapper.css( 'height', (contentWrapperHeight + 'px') );
						}
					}

					var $elems  = this.elements.$content.find( "> *" ),
						counter = 0,
						_mHeight = 0;

					var getLineHeight = function( element ) {
						var style = window.getComputedStyle( element ),
						lineHeight = null,
						placeholder = document.createElement( element.nodeName );

						placeholder.setAttribute("style","margin:0px;padding:0px;font-family:" + style.fontFamily + ";font-size:" + style.fontSize);
						placeholder.innerHTML = "test";
						placeholder = element.parentNode.appendChild( placeholder );

						lineHeight = placeholder.clientHeight;

						placeholder.parentNode.removeChild( placeholder );

						return lineHeight;
					};

					$elems.each( function( index ) {
						if ( counter < contentHeightLines ) {

							var lineHeight 	= getLineHeight( this ),
								lines 		= $(this).outerHeight() / lineHeight,
								style 		= window.getComputedStyle( this );

							if ( lines > 1 && isFinite( lines ) ) {
								var lineCounter = 0,
									i = 1;

								for( i = 1; i <= lines; i++ ) { 

									if ( counter < contentHeightLines ) {
										_mHeight += lineHeight;

										counter++;
										lineCounter++;
									}
								}

								if ( lineCounter === lines ) {
									_mHeight += parseInt( style.marginTop ) + parseInt( style.marginBottom );
								}

							} else {
								_mHeight += $(this).outerHeight( true );
								counter++;
							}
						}
					});

					if ( this.elements.$content.outerHeight( true ) - 1 <= _mHeight ) {
						this.elements.$buttonWrapper.hide();
						this.elements.$saparator.hide();
					}
				} else {
					if ( 'unreveal' === defaultContentState ) {
						this.elements.$contentWrapper.css( 'height', (contentHeightCustom + 'px') );
					}

					contentWrapperHeight = contentHeightCustom;
				}

				this.elements.$button.on( 'click', this.contentToggle.bind( this, contentWrapperHeight ) );
			}

			contentToggle( contentWrapperHeight, e ) {
				const speedUnreveal    = this.elements.$contentWrapper.data('speed') * 1000,
					contentOuterHeight = this.elements.$content.outerHeight(),
					scrollTop          = this.elements.$contentWrapper.data('scroll-top');

				e.preventDefault();

				this.elements.$saparator.slideToggle(speedUnreveal);
				this.elements.$button.toggleClass('pp-content-revealed');

				if ( this.elements.$button.hasClass('pp-content-revealed') ) {
					this.elements.$contentWrapper.animate({ height: ( contentOuterHeight + 'px') }, speedUnreveal);
				} else {
					this.elements.$contentWrapper.animate({ height: ( contentWrapperHeight + 'px') }, speedUnreveal);

					if ( scrollTop == 'yes' ) {
						$('html, body').animate({
							scrollTop: ( this.elements.$contentWrapper.offset().top - 50 ) + 'px'
						});
					}
				}
		   	}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-content-reveal', ContentRevealWidget );
	} );
})(jQuery);