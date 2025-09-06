/**
 * PowerPack Feedback JavaScript
 *
 * Handles both review notices and deactivation feedback popup
 */

(function($) {
	'use strict';

	// Main feedback class
	class PowerPackFeedback {
		constructor() {
			this.currentPlugin = null;
			this.deactivateLink = null;
			this.init();
		}

		init() {
			this.initDeactivationFeedback();
		}

		/**
		 * Initialize deactivation feedback functionality
		 */
		initDeactivationFeedback() {
			// Get all plugin slugs that have feedback popups
			const feedbackPopups = $('[id$="-feedback-popup"]');

			feedbackPopups.each((index, popup) => {
				const pluginSlug = $(popup).attr('id').replace('-feedback-popup', '');
				this.bindDeactivationEvents(pluginSlug);
			});
		}

		/**
		 * Bind deactivation events for a specific plugin
		 * @param {string} pluginSlug - The plugin slug
		 */
		bindDeactivationEvents(pluginSlug) {
			const safeVarName        = pluginSlug.replace(/_/g, '-');
			const deactivateSelector = `[data-slug="${safeVarName}"] .deactivate a`;

			$(document).on('click', deactivateSelector, (e) => {
				e.preventDefault();
				this.currentPlugin = pluginSlug;
				this.deactivateLink = $(e.currentTarget);
				this.showFeedbackPopup(pluginSlug);
			});
		}

		/**
		 * Show feedback popup for a plugin
		 * @param {string} pluginSlug - The plugin slug
		 */
		showFeedbackPopup(pluginSlug) {
			const popup = $(`#${pluginSlug}-feedback-popup`);

			if (popup.length) {
				popup.addClass('active');
				this.bindPopupEvents(pluginSlug);
				this.resetForm(pluginSlug);

				// Focus first radio button for accessibility
				setTimeout(() => {
					popup.find('input[type="radio"]:first').focus();
				}, 100);
			}
		}

		/**
		 * Hide feedback popup
		 * @param {string} pluginSlug - The plugin slug
		 */
		hideFeedbackPopup(pluginSlug) {
			const popup = $(`#${pluginSlug}-feedback-popup`);
			popup.removeClass('active');
			this.unbindPopupEvents(pluginSlug);
		}

		/**
		 * Bind popup events
		 * @param {string} pluginSlug - The plugin slug
		 */
		bindPopupEvents(pluginSlug) {
			const popup = $(`#${pluginSlug}-feedback-popup`);

			// Radio button change events
			popup.find('input[type="radio"]').on('change', (e) => {
				this.handleReasonChange($(e.currentTarget));
			});

			// Form submission
			popup.find('.feedback-form').on('submit', (e) => {
				e.preventDefault();
				this.submitFeedback(pluginSlug);
			});

			// Skip button
			popup.find('.feedback-skip-btn').on('click', (e) => {
				e.preventDefault();
				this.skipFeedback(pluginSlug);
			});

			// Cancel button
			popup.find('.feedback-cancel-btn').on('click', (e) => {
				e.preventDefault();
				this.hideFeedbackPopup(pluginSlug);
			});

			// Overlay click to close
			popup.on('click', (e) => {
				if (e.target === popup[0]) {
					this.hideFeedbackPopup(pluginSlug);
				}
			});

			// ESC key to close
			$(document).on('keydown.feedback', (e) => {
				if (e.keyCode === 27) {
					this.hideFeedbackPopup(pluginSlug);
				}
			});
		}

		/**
		 * Unbind popup events
		 * @param {string} pluginSlug - The plugin slug
		 */
		unbindPopupEvents(pluginSlug) {
			const popup = $(`#${pluginSlug}-feedback-popup`);
			popup.find('input[type="radio"]').off('change');
			popup.find('.feedback-form').off('submit');
			popup.find('.feedback-skip-btn').off('click');
			popup.find('.feedback-cancel-btn').off('click');
			popup.off('click');
			$(document).off('keydown.feedback');
		}

		/**
		 * Handle reason selection change
		 * @param {jQuery} radio - The selected radio button
		 */
		handleReasonChange(radio) {
			const reasonItem = radio.closest('.feedback-reason-item');
			const detailsContainer = reasonItem.find('.feedback-reason-details');

			// Remove selected class from all items
			reasonItem.siblings().removeClass('selected');
			reasonItem.siblings().find('.feedback-reason-details').removeClass('active');

			// Add selected class to current item
			reasonItem.addClass('selected');

			// Show details input if available
			if (detailsContainer.length) {
				detailsContainer.addClass('active');
				setTimeout(() => {
					detailsContainer.find('textarea').focus();
				}, 100);
			}
		}

		/**
		 * Submit feedback
		 * @param {string} pluginSlug - The plugin slug
		 */
		submitFeedback(pluginSlug) {
			const form = $(`#${pluginSlug}-feedback-popup .feedback-form`);
			const selectedReason = form.find('input[type="radio"]:checked');

			if (!selectedReason.length) {
				this.showMessage(pluginSlug, 'Please select a reason for deactivation.', 'error');
				return;
			}

			const consentCheckbox = form.find('.feedback-consent-checkbox');
			if (consentCheckbox.length && !consentCheckbox.is(':checked')) {
				this.showMessage(pluginSlug, 'Please agree to the terms to continue.', 'error');
				return;
			}

			const reason = selectedReason.val();
			const details = selectedReason.closest('.feedback-reason-item')
				.find('.feedback-details-input').val() || '';
			const consent = consentCheckbox.length ? consentCheckbox.is(':checked') : false;

			this.showLoader(pluginSlug);

			const data = {
				action: `${pluginSlug}_submit_deactivation_response`,
				reason: reason,
				details: details,
				consent: consent,
				nonce: this.getFeedbackVar(pluginSlug, 'nonce')
			};

			$.post(this.getFeedbackVar(pluginSlug, 'ajax_url'), data)
				.done(() => {
					this.showMessage(pluginSlug, this.getFeedbackVar(pluginSlug, 'strings.success'), 'success');
					setTimeout(() => {
						this.proceedWithDeactivation();
					}, 1000);
				})
				.fail(() => {
					this.hideLoader(pluginSlug);
					this.showMessage(pluginSlug, this.getFeedbackVar(pluginSlug, 'strings.error'), 'error');
				});
		}

		/**
		 * Skip feedback and proceed with deactivation
		 * @param {string} pluginSlug - The plugin slug
		 */
		skipFeedback(pluginSlug) {
			this.proceedWithDeactivation();
		}

		/**
		 * Proceed with plugin deactivation
		 */
		proceedWithDeactivation() {
			if (this.deactivateLink) {
				window.location.href = this.deactivateLink.attr('href');
			}
		}

		/**
		 * Show loader
		 * @param {string} pluginSlug - The plugin slug
		 */
		showLoader(pluginSlug) {
			const popup = $(`#${pluginSlug}-feedback-popup`);
			popup.find('.feedback-form-container').addClass('hidden');
			popup.find('.feedback-loader').addClass('active');
		}

		/**
		 * Hide loader
		 * @param {string} pluginSlug - The plugin slug
		 */
		hideLoader(pluginSlug) {
			const popup = $(`#${pluginSlug}-feedback-popup`);
			popup.find('.feedback-form-container').removeClass('hidden');
			popup.find('.feedback-loader').removeClass('active');
		}

		/**
		 * Show message
		 * @param {string} pluginSlug - The plugin slug
		 * @param {string} message - The message to show
		 * @param {string} type - The message type (success, error)
		 */
		showMessage(pluginSlug, message, type) {
			const popup = $(`#${pluginSlug}-feedback-popup`);
			let messageElement = popup.find('.feedback-message');

			if (!messageElement.length) {
				messageElement = $('<div class="feedback-message"></div>');
				popup.find('.feedback-popup-content').prepend(messageElement);
			}

			messageElement
				.removeClass('success error')
				.addClass(type)
				.text(message)
				.addClass('active');

			setTimeout(() => {
				messageElement.removeClass('active');
			}, 5000);
		}

		/**
		 * Reset form
		 * @param {string} pluginSlug - The plugin slug
		 */
		resetForm(pluginSlug) {
			const form = $(`#${pluginSlug}-feedback-popup .feedback-form`);
			form[0].reset();
			form.find('.feedback-reason-item').removeClass('selected');
			form.find('.feedback-reason-details').removeClass('active');
			form.find('.feedback-message').removeClass('active');
			this.hideLoader(pluginSlug);
		}

		/**
		 * Get feedback variable
		 * @param {string} pluginSlug - The plugin slug
		 * @param {string} key - The variable key
		 * @returns {*} The variable value
		 */
		getFeedbackVar(pluginSlug, key) {
			const varName = `${pluginSlug}_feedback_vars`;
			const vars = window[varName] || {};

			// Support dot notation for nested keys
			const keys = key.split('.');
			let value = vars;

			for (const k of keys) {
				value = value && value[k];
			}

			return value;
		}
	}

	// Utility functions
	const Utils = {
		/**
		 * Debounce function
		 * @param {Function} func - The function to debounce
		 * @param {number} wait - The delay in milliseconds
		 * @returns {Function} The debounced function
		 */
		debounce(func, wait) {
			let timeout;
			return function executedFunction(...args) {
				const later = () => {
					clearTimeout(timeout);
					func(...args);
				};
				clearTimeout(timeout);
				timeout = setTimeout(later, wait);
			};
		},

		/**
		 * Throttle function
		 * @param {Function} func - The function to throttle
		 * @param {number} limit - The limit in milliseconds
		 * @returns {Function} The throttled function
		 */
		throttle(func, limit) {
			let inThrottle;
			return function() {
				const args = arguments;
				const context = this;
				if (!inThrottle) {
					func.apply(context, args);
					inThrottle = true;
					setTimeout(() => inThrottle = false, limit);
				}
			};
		},

		/**
		 * Validate email address
		 * @param {string} email - The email to validate
		 * @returns {boolean} True if valid, false otherwise
		 */
		validateEmail(email) {
			const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
			return re.test(email);
		},

		/**
		 * Sanitize HTML
		 * @param {string} html - The HTML to sanitize
		 * @returns {string} The sanitized HTML
		 */
		sanitizeHTML(html) {
			const div = document.createElement('div');
			div.textContent = html;
			return div.innerHTML;
		},

		/**
		 * Get URL parameter
		 * @param {string} name - The parameter name
		 * @returns {string|null} The parameter value or null
		 */
		getUrlParameter(name) {
			const urlParams = new URLSearchParams(window.location.search);
			return urlParams.get(name);
		},

		/**
		 * Show toast notification
		 * @param {string} message - The message to show
		 * @param {string} type - The notification type
		 * @param {number} duration - The duration in milliseconds
		 */
		showToast(message, type = 'info', duration = 3000) {
			const toast = $(`
				<div class="feedback-toast feedback-toast-${type}">
					<div class="feedback-toast-content">
						<span class="feedback-toast-message">${Utils.sanitizeHTML(message)}</span>
						<button class="feedback-toast-close">&times;</button>
					</div>
				</div>
			`);

			$('body').append(toast);

			setTimeout(() => {
				toast.addClass('active');
			}, 100);

			const removeToast = () => {
				toast.removeClass('active');
				setTimeout(() => {
					toast.remove();
				}, 300);
			};

			toast.find('.feedback-toast-close').on('click', removeToast);

			if (duration > 0) {
				setTimeout(removeToast, duration);
			}
		}
	};

	// Initialize when DOM is ready
	$(document).ready(() => {
		window.PowerPackFeedback = new PowerPackFeedback();
		window.FeedbackUtils = Utils;
	});

	// Accessibility enhancements
	$(document).ready(() => {
		// Add ARIA labels and roles
		$('.feedback-popup-overlay').attr('role', 'dialog').attr('aria-modal', 'true');
		$('.feedback-reason-item').attr('role', 'radiogroup');
		$('.feedback-actions').attr('role', 'group');

		// Keyboard navigation for radio buttons
		$(document).on('keydown', '.feedback-reason-input', (e) => {
			const current = $(e.currentTarget);
			const items = current.closest('.feedback-form').find('.feedback-reason-input');
			const currentIndex = items.index(current);

			let newIndex = currentIndex;

			switch (e.keyCode) {
				case 38: // Up arrow
					e.preventDefault();
					newIndex = currentIndex > 0 ? currentIndex - 1 : items.length - 1;
					break;
				case 40: // Down arrow
					e.preventDefault();
					newIndex = currentIndex < items.length - 1 ? currentIndex + 1 : 0;
					break;
				case 32: // Space
					e.preventDefault();
					current.prop('checked', true).trigger('change');
					break;
			}

			if (newIndex !== currentIndex) {
				items.eq(newIndex).focus();
			}
		});

		// Focus management for popup
		$(document).on('keydown', '.feedback-popup-overlay', (e) => {
			if (e.keyCode === 9) { // Tab key
				const popup = $(e.currentTarget);
				const focusableElements = popup.find('input, textarea, button, a').filter(':visible');
				const firstElement = focusableElements.first();
				const lastElement = focusableElements.last();

				if (e.shiftKey) {
					if ($(e.target).is(firstElement)) {
						e.preventDefault();
						lastElement.focus();
					}
				} else {
					if ($(e.target).is(lastElement)) {
						e.preventDefault();
						firstElement.focus();
					}
				}
			}
		});
	});

	// Export for external use
	window.PpeFeedback = PowerPackFeedback;
	window.FeedbackUtils = Utils;

})(jQuery);
