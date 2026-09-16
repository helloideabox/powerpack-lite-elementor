( function ( $ ) {
	$( window ).on( 'elementor/frontend/init', () => {

		class FluentFormsWidget extends elementorModules.frontend.handlers.Base {

			getDefaultSettings() {
				return {
					selectors: {
						wrapper: '.pp-fluent-forms',
						form: '.pp-fluent-forms form.frm-fluent-form, .pp-fluent-forms form[class*="fluentform"]',
						title: '.pp-fluent-forms-title',
						description: '.pp-fluent-forms-description',
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

				// Fluent Forms triggers custom jQuery events on the form
				this.elements.$form.on( 'fluentform_submission_failed.ppA11y', this.onSubmissionFailed.bind( this ) );
				this.elements.$form.on( 'fluentform_submission_success.ppA11y', this.onSubmissionSuccess.bind( this ) );

				// Listen on document level as well in case events bubble up
				const formId = this.elements.$form.data( 'form_id' );
				$( document ).on( 'fluentform_submission_failed.ppA11y_' + this.getID(), ( event, data ) => {
					if ( data && ( data.formId == formId || $( event.target ).is( this.elements.$form ) ) ) {
						this.onSubmissionFailed();
					}
				} );
				$( document ).on( 'fluentform_submission_success.ppA11y_' + this.getID(), ( event, data ) => {
					if ( data && ( data.formId == formId || $( event.target ).is( this.elements.$form ) ) ) {
						this.onSubmissionSuccess();
					}
				} );

				this.observeMutations();
			}

			unbindEvents() {
				if ( this.elements.$form.length ) {
					this.elements.$form.off( '.ppA11y' );
				}
				$( document ).off( '.ppA11y_' + this.getID() );

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

				// Error container setup
				const $errorBox = $form.find( '.ff-errors-in-stack' );
				if ( $errorBox.length ) {
					$errorBox.attr( {
						'role': 'alert',
						'aria-live': 'assertive',
						'aria-atomic': 'true',
					} );
				}
			}

			observeMutations() {
				const formNode = this.elements.$form[0];
				if ( ! formNode || ! window.MutationObserver ) {
					return;
				}

				this.mutationObserver = new MutationObserver( ( mutations ) => {
					for ( const mutation of mutations ) {
						// Success message detected
						const $success = this.elements.$wrapper.find( '.ff-message-success' );
						if ( $success.length && ! $success.data( 'pp-a11y-handled' ) ) {
							$success.data( 'pp-a11y-handled', true );
							this.focusSuccessOutput( $success );
							return;
						}

						// Error classes added to form elements
						if ( 'attributes' === mutation.type && 'class' === mutation.attributeName ) {
							if ( $( mutation.target ).hasClass( 'ff-el-is-error' ) ) {
								this.syncFieldErrors();
							}
						}
					}
				} );

				this.mutationObserver.observe( this.elements.$wrapper[0], {
					childList: true,
					subtree: true,
					attributes: true,
					attributeFilter: [ 'class' ],
				} );
			}

			syncFieldErrors() {
				this.elements.$form.find( '.ff-el-is-error' ).each( ( index, group ) => {
					const $group = $( group );
					const $input = $group.find( 'input, textarea, select' );
					const $error = $group.find( '.text-danger, .error' );

					if ( $input.length && $error.length ) {
						$input.attr( 'aria-invalid', 'true' );
						let errorId = $error.attr( 'id' );
						if ( ! errorId ) {
							errorId = 'pp-ff-err-' + this.getID() + '-' + index;
							$error.attr( 'id', errorId );
						}
						$input.attr( 'aria-describedby', errorId );
					}
				} );
			}

			onSubmissionFailed() {
				setTimeout( () => {
					this.syncFieldErrors();

					// Focus first invalid field so VoiceOver user immediately lands on it
					const $invalidField = this.elements.$form.find(
						'.ff-el-is-error input, .ff-el-is-error textarea, .ff-el-is-error select, input.error, textarea.error, select.error'
					).first();

					if ( $invalidField.length ) {
						$invalidField.trigger( 'focus' );
					} else {
						const $errorBox = this.elements.$form.find( '.ff-errors-in-stack' ).filter( ':visible' );
						if ( $errorBox.length ) {
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
					const $success = this.elements.$wrapper.find( '.ff-message-success' );
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

			onDestroy() {
				this.unbindEvents();

				if ( super.onDestroy ) {
					super.onDestroy();
				}
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-fluent-forms', FluentFormsWidget );
	} );
} )( jQuery );

