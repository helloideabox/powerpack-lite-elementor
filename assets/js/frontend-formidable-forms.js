( function ( $ ) {
	$( window ).on( 'elementor/frontend/init', () => {

		class FormidableFormsWidget extends elementorModules.frontend.handlers.Base {

			getDefaultSettings() {
				return {
					selectors: {
						wrapper: '.pp-formidable-forms',
						form: '.pp-formidable-forms form.frm-show-form, .pp-formidable-forms form[class*="frm"]',
						title: '.pp-formidable-forms-title',
						description: '.pp-formidable-forms-description',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$wrapper: this.$element.find( selectors.wrapper ),
					$form: this.$element.find( selectors.form ),
					$title: this.$element.find( selectors.title ),
					$description: this.$element.find( selectors.description ),
				};
			}

			bindEvents() {
				if ( ! this.elements.$form.length ) {
					return;
				}

				this.initFormA11y();

				const ns = '.ppA11y_' + this.getID();

				// Formidable Forms triggers jQuery events on document
				$( document ).on( 'frmFormErrors' + ns, ( event, form, response ) => {
					if ( this.isThisForm( form ) ) {
						this.onSubmissionFailed();
					}
				} );

				$( document ).on( 'frmFormComplete' + ns + ' frmAfterSubmit' + ns, ( event, form, response ) => {
					if ( this.isThisForm( form ) ) {
						this.onSubmissionSuccess();
					}
				} );

				$( document ).on( 'frmPageChanged' + ns, ( event, form, page ) => {
					if ( this.isThisForm( form ) ) {
						this.onPageChanged();
					}
				} );

				// Listen on form submission directly as a fallback
				this.elements.$form.on( 'submit.ppA11y', () => {
					setTimeout( () => {
						if ( this.elements.$form.find( '.frm_blank_field, .frm_error_style, .frm_error' ).length ) {
							this.onSubmissionFailed();
						}
					}, 300 );
				} );

				this.observeMutations();
			}

			isThisForm( form ) {
				if ( ! form ) {
					return false;
				}
				const $f = $( form );
				return $f.is( this.elements.$form ) || Boolean( this.elements.$wrapper.has( $f ).length );
			}

			unbindEvents() {
				const ns = '.ppA11y_' + this.getID();
				$( document ).off( ns );

				if ( this.elements.$form.length ) {
					this.elements.$form.off( '.ppA11y' );
				}

				if ( this.mutationObserver ) {
					this.mutationObserver.disconnect();
				}
			}

			initFormA11y() {
				const $form  = this.elements.$form;
				const $title = this.elements.$title;
				const $desc  = this.elements.$description;

				if ( $title.length && $title.attr( 'id' ) ) {
					$form.attr( 'aria-labelledby', $title.attr( 'id' ) );
				}
				if ( $desc.length && $desc.attr( 'id' ) ) {
					$form.attr( 'aria-describedby', $desc.attr( 'id' ) );
				}

				// Error container setup if present on page load
				const $errorBox = this.elements.$wrapper.find( '.frm_error_style' );
				if ( $errorBox.length ) {
					$errorBox.attr( {
						'role': 'alert',
						'aria-live': 'assertive',
						'aria-atomic': 'true',
					} );
				}
			}

			observeMutations() {
				const wrapperNode = this.elements.$wrapper[0];
				if ( ! wrapperNode || ! window.MutationObserver ) {
					return;
				}

				this.mutationObserver = new MutationObserver( ( mutations ) => {
					for ( const mutation of mutations ) {
						// Success message detected
						const $success = this.elements.$wrapper.find( '.frm_message' );
						if ( $success.length && ! $success.data( 'pp-a11y-handled' ) ) {
							$success.data( 'pp-a11y-handled', true );
							this.focusSuccessOutput( $success );
							return;
						}

						// Error styling or fields added
						if ( 'childList' === mutation.type ) {
							const $addedNodes = $( mutation.addedNodes );
							if ( $addedNodes.find( '.frm_error, .frm_error_style, .frm_blank_field' ).length || $addedNodes.hasClass( 'frm_error_style' ) || $addedNodes.hasClass( 'frm_error' ) ) {
								this.syncFieldErrors();
							}
						}

						if ( 'attributes' === mutation.type && 'class' === mutation.attributeName ) {
							if ( $( mutation.target ).hasClass( 'frm_blank_field' ) || $( mutation.target ).hasClass( 'frm_error' ) ) {
								this.syncFieldErrors();
							}
						}
					}
				} );

				this.mutationObserver.observe( wrapperNode, {
					childList: true,
					subtree: true,
					attributes: true,
					attributeFilter: [ 'class' ],
				} );
			}

			syncFieldErrors() {
				// Formidable marks invalid fields with .frm_blank_field or field with .frm_error
				this.elements.$form.find( '.frm_blank_field, .frm_form_field.has-error' ).each( ( index, group ) => {
					const $group = $( group );
					const $input = $group.find( 'input, textarea, select' );
					const $error = $group.find( '.frm_error' );

					if ( $input.length ) {
						$input.attr( 'aria-invalid', 'true' );
						if ( $error.length ) {
							let errorId = $error.attr( 'id' );
							if ( ! errorId ) {
								errorId = 'pp-frm-err-' + this.getID() + '-' + index;
								$error.attr( 'id', errorId );
							}
							$input.attr( 'aria-describedby', errorId );
						}
					}
				} );

				this.elements.$form.find( 'input.frm_error, textarea.frm_error, select.frm_error' ).each( ( index, input ) => {
					const $input = $( input );
					$input.attr( 'aria-invalid', 'true' );
				} );
			}

			onSubmissionFailed() {
				setTimeout( () => {
					this.syncFieldErrors();

					// Focus first invalid field so VoiceOver user immediately lands on it
					const $invalidField = this.elements.$form.find(
						'.frm_blank_field input:visible, .frm_blank_field textarea:visible, .frm_blank_field select:visible, input.frm_error:visible, textarea.frm_error:visible, select.frm_error:visible'
					).first();

					if ( $invalidField.length ) {
						$invalidField.trigger( 'focus' );
					} else {
						const $errorBox = this.elements.$wrapper.find( '.frm_error_style, .frm_error' ).filter( ':visible' ).first();
						if ( $errorBox.length ) {
							$errorBox.attr( {
								'role': 'alert',
								'aria-live': 'assertive',
								'aria-atomic': 'true',
							} );
							if ( ! $errorBox.attr( 'tabindex' ) ) {
								$errorBox.attr( 'tabindex', '-1' );
							}
							$errorBox.trigger( 'focus' );
						}
					}
				}, 100 );
			}

			onSubmissionSuccess() {
				setTimeout( () => {
					const $success = this.elements.$wrapper.find( '.frm_message' ).filter( ':visible' );
					if ( $success.length ) {
						this.focusSuccessOutput( $success );
					}
				}, 100 );
			}

			focusSuccessOutput( $success ) {
				$success.attr( {
					'role': 'status',
					'aria-live': 'polite',
					'aria-atomic': 'true',
					'tabindex': '-1',
				} );
				$success.trigger( 'focus' );
			}

			onPageChanged() {
				setTimeout( () => {
					// In multi-page forms, focus first visible field on new page
					const $firstField = this.elements.$form.find(
						'input:visible:not([type=hidden]):not([type=submit]):not([type=button]), textarea:visible, select:visible'
					).first();

					if ( $firstField.length ) {
						$firstField.trigger( 'focus' );
					}
				}, 100 );
			}

			onDestroy() {
				this.unbindEvents();

				if ( super.onDestroy ) {
					super.onDestroy();
				}
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-formidable-forms', FormidableFormsWidget );
	} );
} )( jQuery );

