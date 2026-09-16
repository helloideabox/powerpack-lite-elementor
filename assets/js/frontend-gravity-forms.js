( function ( $ ) {
	$( window ).on( 'elementor/frontend/init', () => {

		class GravityFormsWidget extends elementorModules.frontend.handlers.Base {

			getDefaultSettings() {
				return {
					selectors: {
						wrapper: '.pp-gravity-form',
						form: '.pp-gravity-form form[id^="gform_"], .pp-gravity-form .gform_wrapper form, .pp-gravity-form form',
						title: '.pp-gravity-form-title, .gform_title',
						description: '.pp-gravity-form-description, .gform_description',
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
				this.wrapSelectFields();
				this.initFormA11y();

				const ns = '.ppA11y_' + this.getID();

				// Gravity Forms triggers custom jQuery events on document
				$( document ).on( 'gform_post_render' + ns, ( event, formId, currentPage ) => {
					if ( this.isThisForm( formId ) ) {
						this.onPostRender( formId, currentPage );
					}
				} );

				$( document ).on( 'gform_page_loaded' + ns, ( event, formId, currentPage ) => {
					if ( this.isThisForm( formId ) ) {
						this.onPageChanged( currentPage );
					}
				} );

				$( document ).on( 'gform_confirmation_loaded' + ns, ( event, formId ) => {
					if ( this.isThisForm( formId ) ) {
						this.onSubmissionSuccess();
					}
				} );

				// Native submit listener as fallback
				if ( this.elements.$form.length ) {
					this.elements.$form.on( 'submit.ppA11y', () => {
						const i18n = window.ppGravityFormsScript || {};
						if ( i18n.submitting ) {
							this.updateStatus( i18n.submitting );
						}

						setTimeout( () => {
							if ( this.elements.$wrapper.find( '.gfield_error, .gform_validation_errors, .validation_error, .gform_submission_error' ).length ) {
								this.onSubmissionFailed();
							}
						}, 300 );
					} );
				}

				this.observeMutations();
			}

			isThisForm( formIdOrElem ) {
				if ( ! formIdOrElem ) {
					return false;
				}
				if ( typeof formIdOrElem === 'number' || typeof formIdOrElem === 'string' ) {
					const hasId = Boolean( this.elements.$wrapper.find( '#gform_' + formIdOrElem + ', #gform_wrapper_' + formIdOrElem ).length );
					if ( hasId ) {
						return true;
					}
				}
				const $elem = $( formIdOrElem );
				if ( $elem.length ) {
					return $elem.is( this.elements.$form ) || Boolean( this.elements.$wrapper.has( $elem ).length );
				}
				return false;
			}

			unbindEvents() {
				const ns = '.ppA11y_' + this.getID();
				$( document ).off( ns );

				if ( this.elements.$form && this.elements.$form.length ) {
					this.elements.$form.off( '.ppA11y' );
				}

				if ( this.mutationObserver ) {
					this.mutationObserver.disconnect();
				}
			}

			wrapSelectFields() {
				this.$element.find( 'select:not([multiple])' ).each( function () {
					const $select = $( this );
					if ( $select.parent().hasClass( 'pp-gf-select-custom' ) ) {
						return;
					}
					if ( $select.next().hasClass( 'chosen-container' ) ) {
						$select.next().wrap( '<span class="pp-gf-select-custom"></span>' );
					} else {
						$select.wrap( '<span class="pp-gf-select-custom"></span>' );
					}
				} );
			}

			initFormA11y() {
				const selectors = this.getSettings( 'selectors' );
				const $wrapper  = this.elements.$wrapper;
				const $form     = $wrapper.find( selectors.form );
				const $desc     = $wrapper.find( selectors.description );

				// Remove any aria-labelledby / aria-describedby on form wrappers that suppress
				// gform_title and gform_description in VoiceOver / WebKit accessibility tree.
				$form.removeAttr( 'aria-labelledby aria-describedby' );
				$wrapper.find( '.gform_wrapper' ).removeAttr( 'aria-labelledby aria-describedby' );

				// Ensure heading container, title and description are explicitly accessible
				$wrapper.find( '.gform_heading, .gform_title, .gform_description, .pp-gravity-form-heading' ).removeAttr( 'aria-hidden' );

				// Ensure description span is treated as a readable text element by VoiceOver
				$desc.each( function () {
					const $d = $( this );
					if ( ! $d.attr( 'role' ) && ( $d.is( 'span' ) || $d.is( 'div' ) ) ) {
						$d.attr( 'role', 'text' );
					}
				} );

				// Multi-page step list accessibility
				const $stepList = $wrapper.find( '.gf_page_steps' );
				if ( $stepList.length ) {
					$stepList.attr( {
						'role': 'navigation',
						'aria-label': 'Form steps',
					} );
					$stepList.find( '.gf_step' ).each( function () {
						const $step = $( this );
						if ( $step.hasClass( 'gf_step_active' ) ) {
							$step.attr( 'aria-current', 'step' );
						} else {
							$step.removeAttr( 'aria-current' );
						}
					} );
				}

				// Check if validation errors are already present (non-AJAX submit)
				if ( $wrapper.find( '.gfield_error, .gform_validation_errors, .validation_error, .gform_submission_error' ).length ) {
					this.syncFieldErrors();
					this.onSubmissionFailed();
				}

				// Check if confirmation message is already present (non-AJAX submit)
				const $success = $wrapper.find( '.gform_confirmation_wrapper, .gform_confirmation_message, .gforms_confirmation_message' ).filter( ':visible' );
				if ( $success.length && ! $success.data( 'pp-a11y-handled' ) ) {
					$success.data( 'pp-a11y-handled', true );
					this.focusSuccessOutput( $success );
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
						const $success = this.elements.$wrapper.find( '.gform_confirmation_wrapper, .gform_confirmation_message, .gforms_confirmation_message' ).filter( ':visible' );
						if ( $success.length && ! $success.data( 'pp-a11y-handled' ) ) {
							$success.data( 'pp-a11y-handled', true );
							this.focusSuccessOutput( $success );
							return;
						}

						// Validation errors added to DOM
						if ( 'childList' === mutation.type ) {
							const $addedNodes = $( mutation.addedNodes );
							if ( $addedNodes.find( '.gfield_error, .gform_validation_errors, .validation_error, .gform_submission_error' ).length ||
								$addedNodes.hasClass( 'gfield_error' ) ||
								$addedNodes.hasClass( 'gform_validation_errors' ) ||
								$addedNodes.hasClass( 'validation_error' ) ||
								$addedNodes.hasClass( 'gform_submission_error' ) ) {
								this.onSubmissionFailed();
							}
						}

						if ( 'attributes' === mutation.type && 'class' === mutation.attributeName ) {
							if ( $( mutation.target ).hasClass( 'gfield_error' ) ) {
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
				const $wrapper = this.elements.$wrapper;

				// Validation summary container setup
				const $errorBox = $wrapper.find( '.gform_validation_errors, .validation_error, .gform_submission_error' ).filter( ':visible' );
				if ( $errorBox.length ) {
					$errorBox.attr( {
						'role': 'alert',
						'aria-live': 'assertive',
						'aria-atomic': 'true',
					} );
					if ( ! $errorBox.attr( 'tabindex' ) ) {
						$errorBox.attr( 'tabindex', '-1' );
					}
				}

				// Field-level errors setup
				$wrapper.find( '.gfield_error' ).each( ( index, group ) => {
					const $group  = $( group );
					const $inputs = $group.find( 'input, textarea, select' );
					const $error  = $group.find( '.validation_message, .gfield_description.validation_message' );

					if ( $inputs.length ) {
						$inputs.attr( 'aria-invalid', 'true' );

						if ( $error.length ) {
							let errorId = $error.attr( 'id' );
							if ( ! errorId ) {
								errorId = 'pp-gf-err-' + this.getID() + '-' + index;
								$error.attr( 'id', errorId );
							}
							$inputs.each( function () {
								const $input = $( this );
								const currentDescribedBy = $input.attr( 'aria-describedby' ) || '';
								if ( currentDescribedBy.indexOf( errorId ) === -1 ) {
									const newDescribedBy = currentDescribedBy ? currentDescribedBy + ' ' + errorId : errorId;
									$input.attr( 'aria-describedby', newDescribedBy );
								}
							} );
						}
					}
				} );
			}

			onSubmissionFailed() {
				setTimeout( () => {
					this.syncFieldErrors();

					const i18n = window.ppGravityFormsScript || {};
					if ( i18n.form_has_errors ) {
						this.updateStatus( i18n.form_has_errors );
					}

					// Focus first invalid field so VoiceOver user immediately lands on it
					const $invalidField = this.elements.$wrapper.find(
						'.gfield_error input:visible, .gfield_error textarea:visible, .gfield_error select:visible'
					).first();

					if ( $invalidField.length ) {
						$invalidField.trigger( 'focus' );
					} else {
						const $errorBox = this.elements.$wrapper.find( '.gform_validation_errors, .validation_error, .gform_submission_error' ).filter( ':visible' ).first();
						if ( $errorBox.length ) {
							$errorBox.trigger( 'focus' );
						}
					}
				}, 100 );
			}

			onSubmissionSuccess() {
				setTimeout( () => {
					const $success = this.elements.$wrapper.find( '.gform_confirmation_wrapper, .gform_confirmation_message, .gforms_confirmation_message' ).filter( ':visible' ).first();
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

			onPageChanged( currentPage ) {
				setTimeout( () => {
					// Update step list active indicator
					const $stepList = this.elements.$wrapper.find( '.gf_page_steps' );
					if ( $stepList.length ) {
						$stepList.find( '.gf_step' ).each( function () {
							const $step = $( this );
							if ( $step.hasClass( 'gf_step_active' ) ) {
								$step.attr( 'aria-current', 'step' );
							} else {
								$step.removeAttr( 'aria-current' );
							}
						} );
					}

					// Focus first visible input field on new page
					const $firstField = this.elements.$wrapper.find(
						'input:visible:not([type=hidden]):not([type=submit]):not([type=button]), textarea:visible, select:visible'
					).first();

					if ( $firstField.length ) {
						$firstField.trigger( 'focus' );
					}
				}, 100 );
			}

			onPostRender( formId, currentPage ) {
				// Re-cache form reference after AJAX render
				const selectors = this.getSettings( 'selectors' );
				this.elements.$form = this.$element.find( selectors.form );

				this.wrapSelectFields();
				this.initFormA11y();

				if ( this.elements.$wrapper.find( '.gfield_error, .gform_validation_errors, .validation_error, .gform_submission_error' ).length ) {
					this.onSubmissionFailed();
				} else if ( this.elements.$wrapper.find( '.gform_confirmation_wrapper, .gform_confirmation_message, .gforms_confirmation_message' ).length ) {
					this.onSubmissionSuccess();
				}
			}

			updateStatus( message ) {
				const statusElem = document.getElementById( 'pp-gravity-form-status-' + this.getID() );
				if ( statusElem ) {
					statusElem.textContent = '';
					setTimeout( () => {
						statusElem.textContent = message;
					}, 50 );
				}
			}

			onDestroy() {
				this.unbindEvents();

				if ( super.onDestroy ) {
					super.onDestroy();
				}
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-gravity-forms', GravityFormsWidget );
	} );
} )( jQuery );
