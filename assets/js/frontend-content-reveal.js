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
						buttonWrapper: '.pp-content-reveal-buttons-wrapper',
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

				var contentWrapperHeight,
					visibleLines = parseInt( contentHeightLines, 10 );

				if ( 'reveal' === defaultContentState ) {
					this.elements.$saparator.hide();
				}

				if ( contentVisibility == 'lines' ) {
					if ( isNaN( visibleLines ) ) {
						contentWrapperHeight = this.elements.$contentWrapper.outerHeight();
					} else {
						// A visible amount of 0 lines collapses the content completely, leaving only the button.
						contentWrapperHeight = visibleLines > 0 ? ( parseInt(contentLineHeight, 10) * visibleLines ) + parseInt(contentPaddingTop, 10) : 0;

						if ( isNaN( contentWrapperHeight ) ) {
							contentWrapperHeight = this.elements.$contentWrapper.outerHeight();
						} else if ( 'unreveal' === defaultContentState ) {
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
						if ( counter < visibleLines ) {

							var lineHeight 	= getLineHeight( this ),
								lines 		= $(this).outerHeight() / lineHeight,
								style 		= window.getComputedStyle( this );

							if ( lines > 1 && isFinite( lines ) ) {
								var lineCounter = 0,
									i = 1;

								for( i = 1; i <= lines; i++ ) { 

									if ( counter < visibleLines ) {
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
					contentWrapperHeight = parseInt( contentHeightCustom, 10 );

					if ( isNaN( contentWrapperHeight ) ) {
						contentWrapperHeight = this.elements.$contentWrapper.outerHeight();
					} else if ( 'unreveal' === defaultContentState ) {
						this.elements.$contentWrapper.css( 'height', (contentWrapperHeight + 'px') );
					}
				}

				this.elements.$button.on( 'click keydown', ( e ) => {
					if ( 'keydown' === e.type ) {
						const isActivationKey = 'Enter' === e.key || ' ' === e.key || 'Spacebar' === e.key || 'Space' === e.code;

						if ( ! isActivationKey ) {
							return;
						}
					}

					e.preventDefault();
					this.contentToggle( contentWrapperHeight );
				} );

				// Tabbing onto a link or field in the clipped part expands the content, so focus is never on something hidden.
				this.elements.$content.on( 'focusin', ( e ) => {
					if ( this.elements.$button.hasClass( 'pp-content-revealed' ) || ! this.elements.$buttonWrapper.is( ':visible' ) ) {
						return;
					}

					const wrapper = this.elements.$contentWrapper[0],
						// Measured from the content's top: focus has already scrolled the overflow:hidden
						// wrapper to the target, so its position against the wrapper reads as in view.
						targetBottom = e.target.getBoundingClientRect().bottom - this.elements.$content[0].getBoundingClientRect().top;

					if ( targetBottom > wrapper.clientHeight ) {
						wrapper.scrollTop = 0;
						this.contentToggle( contentWrapperHeight );
					}
				} );
			}

			contentToggle( contentWrapperHeight ) {
				const prefersReducedMotion = window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches,
					speedUnreveal      = prefersReducedMotion ? 0 : this.elements.$contentWrapper.data('speed') * 1000,
					contentOuterHeight = this.elements.$content.outerHeight(),
					scrollTop          = this.elements.$contentWrapper.data('scroll-top'),
					$wrapper           = this.elements.$contentWrapper,
					$button            = this.elements.$button;

				this.elements.$saparator.slideToggle(speedUnreveal);
				$button.toggleClass('pp-content-revealed');

				const isExpanded = $button.hasClass('pp-content-revealed'),
					ariaLabel    = $button.attr( isExpanded ? 'data-aria-label-open' : 'data-aria-label-closed' );

				$button.attr( 'aria-expanded', isExpanded ? 'true' : 'false' );

				// Only the icon-only state carries an aria-label; a state with visible text is named by that text.
				if ( ariaLabel ) {
					$button.attr( 'aria-label', ariaLabel );
				} else {
					$button.removeAttr( 'aria-label' );
				}

				if ( isExpanded ) {
					$wrapper.stop().animate({ height: ( contentOuterHeight + 'px') }, speedUnreveal, () => {
						// Drop the fixed height so zoom, text spacing and reflow cannot clip the open content.
						$wrapper.addClass( 'pp-content-revealed-wrapper' ).css( 'height', '' );
					});
				} else {
					$wrapper.stop()
						.css( 'height', $wrapper.outerHeight() + 'px' )
						.removeClass( 'pp-content-revealed-wrapper' )
						.animate({ height: ( contentWrapperHeight + 'px') }, speedUnreveal);

					if ( scrollTop == 'yes' ) {
						$('html, body').animate({
							scrollTop: ( $wrapper.offset().top - 50 ) + 'px'
						}, speedUnreveal);
					}
				}
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-content-reveal', ContentRevealWidget );
	} );
})(jQuery);