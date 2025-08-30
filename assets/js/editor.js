;( function( elementor, $, window ) {

	// Query Control

	var ControlQuery = elementor.modules.controls.Select2.extend( {

		cache: null,
		isTitlesReceived: false,

		getSelect2Placeholder: function getSelect2Placeholder() {
			var self = this;
			
			return {
				id: '',
				text: self.model.get('placeholder') || 'All',
			};
		},

		getSelect2DefaultOptions: function getSelect2DefaultOptions() {
			var self = this;

			return jQuery.extend( elementor.modules.controls.Select2.prototype.getSelect2DefaultOptions.apply( this, arguments ), {
				ajax: {
					transport: function transport( params, success, failure ) {
						var data = {
							q 			: params.data.q,
							query_type 		: self.model.get('query_type'),
							query_options 	: self.model.get('query_options'),
							object_type 	: self.model.get('object_type'),
						};

						return elementorCommon.ajax.addRequest('pp_query_control_filter_autocomplete', {
							data 	: data,
							success : success,
							error 	: failure,
						});
					},
					data: function data( params ) {
						return {
							q 	 : params.term,
							page : params.page,
						};
					},
					cache: true
				},
				escapeMarkup: function escapeMarkup(markup) {
					return markup;
				},
				minimumInputLength: 1
			});
		},

		getValueTitles: function getValueTitles() {
			var self 			= this,
			    ids 			= this.getControlValue(),
			    queryType 		= this.model.get('query_type'),
			    queryOptions 	= this.model.get('query_options'),
			    objectType 		= this.model.get('object_type');

			if ( ! ids || ! queryType ) return;

			if ( ! _.isArray( ids ) ) {
				ids = [ ids ];
			}

			elementorCommon.ajax.loadObjects({
				action 	: 'pp_query_control_value_titles',
				ids 	: ids,
				data 	: {
					query_type 		: queryType,
					query_options 	: queryOptions,
					object_type 	: objectType,
					unique_id 		: '' + self.cid + queryType,
				},
				success: function success(data) {
					self.isTitlesReceived = true;
					self.model.set('options', data);
					self.render();
				},
				before: function before() {
					self.addSpinner();
				},
			});
		},

		addSpinner: function addSpinner() {
			this.ui.select.prop('disabled', true);
			this.$el.find('.elementor-control-title').after('<span class="elementor-control-spinner ee-control-spinner">&nbsp;<i class="fa fa-spinner fa-spin"></i>&nbsp;</span>');
		},

		onReady: function onReady() {
			setTimeout( elementor.modules.controls.Select2.prototype.onReady.bind(this) );

			if ( ! this.isTitlesReceived ) {
				this.getValueTitles();
			}
		},

		onBeforeDestroy: function onBeforeDestroy() {
			if (this.ui.select.data('select2')) {
				this.ui.select.select2('destroy');
			}

			this.$el.remove();
		},

	} );

	// Add Control Handlers
	elementor.addControlView( 'pp-query', ControlQuery );
} )( elementor, jQuery, window );

/* === PowerPack: Pricing Table Editor Notice === */
(function(elementor, $, window){
	// Guard: ensure module utils exist
	if (typeof elementorModules === 'undefined' || !elementorModules.editor || !elementorModules.editor.utils) {
		return;
	}

	var TableMasterPricingTableNotice = elementorModules.editor.utils.Module.extend({
		eventName: 'tablemaster_pricing_table_notice',
		control: null,

		onInit: function() {
			// Listen when any section in the editor panel is activated.
			// We'll only proceed if our control actually exists in that section.
			elementor.channels.editor.on('section:activated', this.onSectionActive.bind(this));
		},

		onSectionActive: function(/* sectionName */) {
			// Reset cached control on each section activation
			this.control = null;

			// If the user dismissed this notice in this account, remove the control (if rendered) and stop.
			if (Array.isArray(elementor.config.user.dismissed_editor_notices) &&
				elementor.config.user.dismissed_editor_notices.indexOf(this.eventName) !== -1) {
				if (this.getPromoControl()) {
					this.getPromoControl().remove();
				}
				return;
			}

			// Only proceed if our control exists in the currently active section.
			if (!this.hasPromoControl()) {
				return;
			}

			this.registerEvents();
		},

		getPromoControl: function() {
			if (!this.control) {
				// Control ID provided by user
				this.control = this.getEditorControlView('upgrade_powerpack_lite_notices');
			}
			return this.control;
		},

		hasPromoControl: function() {
			return !!this.getPromoControl();
		},

		registerEvents: function() {
			var controlView = this.getPromoControl();
			if (!controlView || !controlView.$el) {
				return;
			}

			// Dismiss button
			var $dismissBtn = controlView.$el.find('.elementor-control-notice-dismiss');
			var onDismissBtnClick = (function(event) {
				$dismissBtn.off('click', onDismissBtnClick);
				event.preventDefault();
				this.dismiss();
				controlView.remove();
			}).bind(this);
			$dismissBtn.on('click', onDismissBtnClick);

			// Primary action button (Elementor button component)
			var $actionBtn = controlView.$el.find('.e-btn-1');
			var onActionBtn = (function(event) {
				$actionBtn.off('click', onActionBtn);
				event.preventDefault();
				this.onAction(event);
				controlView.remove();
			}).bind(this);
			$actionBtn.on('click', onActionBtn);
		},

		ajaxRequest: function(name, data) {
			elementorCommon.ajax.addRequest(name, { data: data });
		},

		dismiss: function() {
			this.ajaxRequest('dismissed_editor_notices', { dismissId: this.eventName });

			// Prevent opening the same hint again in current editor session.
			this.ensureNoPromoControlInSession();
		},

		ensureNoPromoControlInSession: function() {
			if (!Array.isArray(elementor.config.user.dismissed_editor_notices)) {
				elementor.config.user.dismissed_editor_notices = [];
			}
			elementor.config.user.dismissed_editor_notices.push(this.eventName);
		},

		onAction: function(event) {
			try {
				var settingsData = (event.target.closest('button') || {}).dataset && (event.target.closest('button').dataset.settings || '{}');
				var settings = JSON.parse(settingsData);
				var actionURL = settings.action_url || null;
				if (actionURL) {
					window.open(actionURL, '_blank');
				}
			} catch (e) {
				// swallow
			}

			// Optional tracking hook (customize source if needed)
			this.ajaxRequest('powerpack_tablemaster_campaign', { source: 'pp-pricing-table-notice' });

			this.ensureNoPromoControlInSession();
		}
	});

	// Instantiate the module so onInit runs.
	new TableMasterPricingTableNotice();

})(elementor, jQuery, window);
