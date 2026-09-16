( function ( $ ) {
	$( window ).on( 'elementor/frontend/init', () => {

		class ContactForm7Widget extends elementorModules.frontend.handlers.Base {

			getDefaultSettings() {
				return {
					selectors: {
						wrapper: '.pp-contact-form-7',
						form: '.pp-contact-form-7 form.wpcf7-form',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$wrapper: this.$element.find( selectors.wrapper ),
					$form: this.$element.find( selectors.form ),
				};
			}

			bindEvents() {
				if ( ! this.elements.$form.length ) {
					return;
				}

				this.flagWrappedLabels();

				this.elements.$form.on( 'wpcf7invalid.ppA11y', this.onInvalid.bind( this ) );
				this.elements.$form.on(
					'wpcf7mailsent.ppA11y wpcf7mailfailed.ppA11y wpcf7spam.ppA11y',
					this.onResponse.bind( this )
				);
			}

			unbindEvents() {
				if ( this.elements.$form.length ) {
					this.elements.$form.off( '.ppA11y' );
				}
			}

			flagWrappedLabels() {
				if ( ! this.elements.$wrapper.hasClass( 'labels-hide' ) ) {
					return;
				}

				this.elements.$form.find( 'label' ).each( ( index, label ) => {
					if ( label.querySelector( 'input, textarea, select' ) ) {
						label.classList.add( 'pp-label-has-control' );
					}
				} );
			}

			onInvalid() {
				var $invalidField = this.elements.$form.find( '.wpcf7-not-valid' ).first();

				if ( $invalidField.length ) {
					$invalidField.trigger( 'focus' );
				} else {
					this.focusResponseOutput();
				}
			}

			onResponse() {
				this.focusResponseOutput();
			}

			focusResponseOutput() {
				var $summary = this.elements.$form.closest( '.wpcf7' ).find( '.wpcf7-response-output' );

				if ( ! $summary.length ) {
					return;
				}

				if ( ! $summary.attr( 'tabindex' ) ) {
					$summary.attr( 'tabindex', '-1' );
				}

				$summary.trigger( 'focus' );
			}

			onDestroy() {
				this.unbindEvents();

				if ( super.onDestroy ) {
					super.onDestroy();
				}
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-contact-form-7', ContactForm7Widget );
	} );
} )( jQuery );
