( function ( $ ) {
	$( window ).on( 'elementor/frontend/init', () => {

		class WPFormsWidget extends elementorModules.frontend.handlers.Base {

			getDefaultSettings() {
				return {
					selectors: {
						wrapper: '.pp-wpforms',
						form: '.pp-wpforms form.wpforms-form',
						confirmation: '.wpforms-confirmation-container-full, .wpforms-confirmation-container',
						errorContainer: '.wpforms-error-container',
						fieldError: 'label.wpforms-error',
						steps: '.wpforms-page-indicator-steps',
						page: '.wpforms-page',
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
				const ns = '.ppA11y_' + this.getID();

				$( document )
					.on( 'wpformsAjaxSubmitSuccessConfirmation' + ns, ( event ) => {
						if ( this.isThisForm( event.target ) ) {
							this.onConfirmation();
						}
					} )
					.on( 'wpformsAjaxSubmitFailed' + ns + ' wpformsAjaxSubmitError' + ns, ( event ) => {
						if ( this.isThisForm( event.target ) ) {
							this.onErrors();
						}
					} )
					.on( 'wpformsPageChange' + ns, ( event, currentPage, $form ) => {
						if ( this.isThisForm( $form ) ) {
							this.onPageChange( currentPage );
						}
					} );

				// Client side validation failures do not fire a WPForms event, and a
				// non-AJAX submit reloads the page with the state already rendered.
				this.elements.$form.on( 'submit.ppA11y', () => {
					const i18n = window.ppWPFormsScript || {};

					if ( i18n.submitting ) {
						this.updateStatus( i18n.submitting );
					}

					setTimeout( () => {
						if ( this.hasErrors() ) {
							this.onErrors();
						}
					}, 300 );
				} );

				// Keep the invalid flag in step with WPForms as fields are corrected.
				this.$element.on( 'blur.ppA11y change.ppA11y', 'input, textarea, select', () => {
					setTimeout( () => {
						this.syncFieldErrors();
					}, 50 );
				} );

				this.syncInitialState();
			}

			unbindEvents() {
				$( document ).off( '.ppA11y_' + this.getID() );

				this.$element.off( '.ppA11y' );

				if ( this.elements.$form && this.elements.$form.length ) {
					this.elements.$form.off( '.ppA11y' );
				}
			}

			/**
			 * Whether an element belongs to the form this handler is attached to.
			 *
			 * @param {Object} elem Element or jQuery object the event originated from.
			 *
			 * @return {boolean} True when the element is inside this widget.
			 */
			isThisForm( elem ) {
				if ( ! elem ) {
					return false;
				}

				const $elem = $( elem );

				if ( ! $elem.length ) {
					return false;
				}

				return Boolean( this.$element.has( $elem ).length ) || $elem.is( this.$element );
			}

			hasErrors() {
				const selectors = this.getSettings( 'selectors' );

				return Boolean(
					this.$element.find( selectors.fieldError ).length ||
					this.$element.find( selectors.errorContainer ).filter( ':visible' ).length
				);
			}

			/**
			 * Flag invalid fields and clear the flag once they validate.
			 *
			 * WPForms marks an invalid field with a class only in the classic render
			 * path, so nothing tells assistive technology the value was rejected.
			 */
			syncFieldErrors() {
				this.$element.find( '.wpforms-field input, .wpforms-field textarea, .wpforms-field select' ).each( function () {
					const $field = $( this );

					if ( $field.hasClass( 'wpforms-error' ) ) {
						$field.attr( 'aria-invalid', 'true' );
					} else if ( 'true' === $field.attr( 'aria-invalid' ) ) {
						$field.removeAttr( 'aria-invalid' );
					}
				} );
			}

			/**
			 * Announce and focus state that is already in the DOM on page load.
			 *
			 * Covers non-AJAX submits, where the browser navigates and the confirmation
			 * or the validation errors are part of the rendered page.
			 */
			syncInitialState() {
				const selectors = this.getSettings( 'selectors' );
				const $success = this.$element.find( selectors.confirmation ).filter( ':visible' );

				if ( $success.length ) {
					this.focusOutput( $success.first() );

					return;
				}

				if ( this.hasErrors() ) {
					this.onErrors();
				}
			}

			onConfirmation() {
				setTimeout( () => {
					const selectors = this.getSettings( 'selectors' );
					const $success = this.$element.find( selectors.confirmation ).filter( ':visible' ).first();

					if ( $success.length ) {
						this.focusOutput( $success );
					}
				}, 100 );
			}

			onErrors() {
				setTimeout( () => {
					const selectors = this.getSettings( 'selectors' );
					const i18n = window.ppWPFormsScript || {};

					if ( i18n.form_has_errors ) {
						this.updateStatus( i18n.form_has_errors );
					}

					const $errorContainer = this.$element.find( selectors.errorContainer ).filter( ':visible' ).first();

					if ( $errorContainer.length ) {
						$errorContainer.attr( {
							role: 'alert',
							'aria-live': 'assertive',
							'aria-atomic': 'true',
						} );
					}

					this.syncFieldErrors();

					// Land the user on the first field that needs attention.
					const $invalidField = this.$element.find(
						'.wpforms-field input.wpforms-error:visible, .wpforms-field textarea.wpforms-error:visible, .wpforms-field select.wpforms-error:visible'
					).first();

					if ( $invalidField.length ) {
						$invalidField.trigger( 'focus' );
					} else if ( $errorContainer.length ) {
						this.focusOutput( $errorContainer );
					}
				}, 100 );
			}

			/**
			 * Announce the new page and move focus into it.
			 *
			 * The page indicator is a plain span, so a page change is silent and focus
			 * stays on a navigation button that has just been replaced.
			 *
			 * @param {number} currentPage Number of the page that was just shown.
			 */
			onPageChange( currentPage ) {
				setTimeout( () => {
					const selectors = this.getSettings( 'selectors' );
					const i18n = window.ppWPFormsScript || {};
					const totalPages = this.$element.find( selectors.page ).length;

					if ( i18n.page_status && currentPage && totalPages ) {
						this.updateStatus(
							i18n.page_status.replace( '%1$s', currentPage ).replace( '%2$s', totalPages )
						);
					}

					const $firstField = this.$element.find(
						'.wpforms-page:visible'
					).find(
						'input:visible:not([type=hidden]):not([type=submit]):not([type=button]), textarea:visible, select:visible'
					).first();

					if ( $firstField.length ) {
						$firstField.trigger( 'focus' );
					}
				}, 100 );
			}

			/**
			 * Make a rendered message announceable and move focus to it.
			 *
			 * @param {Object} $output jQuery object of the message container.
			 */
			focusOutput( $output ) {
				if ( $output.data( 'pp-a11y-handled' ) ) {
					return;
				}

				$output.data( 'pp-a11y-handled', true );

				$output.attr( {
					role: 'status',
					'aria-live': 'polite',
					'aria-atomic': 'true',
					tabindex: '-1',
				} );

				$output.trigger( 'focus' );
			}

			updateStatus( message ) {
				const statusElem = document.getElementById( 'pp-wpforms-status-' + this.getID() );

				if ( ! statusElem ) {
					return;
				}

				statusElem.textContent = '';

				setTimeout( () => {
					statusElem.textContent = message;
				}, 50 );
			}

			onDestroy() {
				this.unbindEvents();

				if ( super.onDestroy ) {
					super.onDestroy();
				}
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-wpforms', WPFormsWidget );
	} );
} )( jQuery );
