(function ($) {
	PPCountdown = function (settings, $scope, $) {
		this.id = settings.id = $scope.data('id');
		this.settings = settings;
		this.node = $scope;
		this.wrap = this.node.find('.pp-countdown-wrapper');
		this.timertype = settings.timer_type;
		this.timer_format = settings.timer_format;
		this.timer_layout = settings.timer_layout;
		this.timer_labels = settings.timer_labels;
		this.timer_labels_singular = settings.timer_labels_singular;
		this.redirect_link = settings.redirect_link.url;
		this.redirect_link_target = settings.redirect_link_target;
		this.fixed_timer_action = settings.fixed_timer_action;
		this.evergreen_timer_action = settings.evergreen_timer_action;
		this.evergreen_date_days = settings.days;
		this.evergreen_date_hour = settings.hours;
		this.evergreen_date_minutes = settings.minutes;
		this.evergreen_date_seconds = settings.seconds;
		this.timezone = settings.time_zone;
		this.timer_exp_text = "";

		if (this.timezone === 'NULL' || this.timezone === '') {
			this.timezone = null;
		}

		if ('evergreen' === this.timertype) {
			this.timer_date = new Date();
		} else {
			this.timer_date = new Date(settings.years, (settings.months - 1), settings.days, settings.hours, settings.minutes);
		}

		if (settings.timer_exp_text) {
			this.timer_exp_text = settings.timer_exp_text;
		}

		if (this.timertype == "fixed") {
			this._initFixedTimer();
		}

		if (this.timertype == "evergreen") {

			var currdate = '';
			var timevar = 0;

			if ($.cookie("countdown-" + settings.id) == undefined) {
				$.cookie("countdown-" + settings.id, true);
				$.cookie("countdown-" + settings.id + "-currdate", new Date());
				$.cookie("countdown-" + settings.id + "-day", this.evergreen_date_days);
				$.cookie("countdown-" + settings.id + "-hour", this.evergreen_date_hour);
				$.cookie("countdown-" + settings.id + "-min", this.evergreen_date_minutes);
				$.cookie("countdown-" + settings.id + "-sec", this.evergreen_date_seconds);

			}

			currdate = new Date($.cookie("countdown-" + settings.id + "-currdate"));

			timevar = (parseFloat(this.evergreen_date_days * 24 * 60 * 60) + parseFloat(this.evergreen_date_hour * 60 * 60) + parseFloat(this.evergreen_date_minutes * 60) + parseFloat(this.evergreen_date_seconds)) * 1000;

			currdate.setTime(currdate.getTime() + timevar);

			this.timer_date = currdate;

			this._initEverGreenTimer();
		}

		this._initCountdown();
	};
	PPCountdown.prototype = {

		_initCountdown: function () {
			fixed_timer_action = this.fixed_timer_action;
			settings = this.settings;
			var action = '';

			if (this.timertype == "fixed") {
				action = this.fixed_timer_action;
			} else {
				action = this.evergreen_timer_action;
			}

			$.cookie("countdown-" + settings.id + "expiremsg", null);
			$.cookie("countdown-" + settings.id + "redirect", null);
			$.cookie("countdown-" + settings.id + "redirectwindow", null);
			$.cookie("countdown-" + settings.id + "hide", null);
			$.cookie("countdown-" + settings.id + "reset", null);

			$.removeCookie("countdown-" + settings.id + "expiremsg");
			$.removeCookie("countdown-" + settings.id + "redirect");
			$.removeCookie("countdown-" + settings.id + "redirectwindow");
			$.removeCookie("countdown-" + settings.id + "hide");
			$.removeCookie("countdown-" + settings.id + "reset");


			if (action == "msg") {

				$.cookie("countdown-" + settings.id + "expiremsg", settings.expire_message, { expires: 365 });

			} else if (action == "redirect") {

				$.cookie("countdown-" + settings.id + "redirect", settings.redirect_link, { expires: 365 });
				$.cookie("countdown-" + settings.id + "redirectwindow", settings.redirect_link_target, { expires: 365 });

			} else if (action == "hide") {

				$.cookie("countdown-" + settings.id + "hide", "yes", { expires: 365 });

			} else if (action == 'reset') {
				$.cookie("countdown-" + settings.id + "reset", "yes", { expires: 365 });
			}
		},

		_initFixedTimer: function () {

			var dateNow = new Date();

			if ((dateNow.getTime() - this.timer_date.getTime()) > 0) {
				if (this.fixed_timer_action == 'msg') {
					if (parseInt(window.location.href.toLowerCase().indexOf("?elementor")) === parseInt(-1)) {
						this.wrap.append(this.timer_exp_text);
					} else {
						this.wrap.countdown({
							until: this.timer_date,
							format: this.timer_format,
							layout: this.timer_layout,
							labels: this.timer_labels.split(","),
							timezone: this.timezone,
							labels1: this.timer_labels_singular.split(","),
							expiryText: this.timer_exp_text
						});
					}

				} else if (this.fixed_timer_action == 'redirect') {

					if (parseInt(window.location.href.toLowerCase().indexOf("?elementor")) === parseInt(-1)) {
						window.open(this.redirect_link, this.redirect_link_target);
					} else {
						this.wrap.countdown({
							until: this.timer_date,
							format: this.timer_format,
							layout: this.timer_layout,
							labels: this.timer_labels.split(","),
							timezone: this.timezone,
							labels1: this.timer_labels_singular.split(","),
							expiryText: this.timer_exp_text
						});
					}

				} else if (this.fixed_timer_action == 'hide') {
					if (parseInt(window.location.href.toLowerCase().indexOf("?elementor")) === parseInt(-1)) {
						this.wrap.countdown('destroy');
					} else {
						this.wrap.countdown({
							until: this.timer_date,
							format: this.timer_format,
							layout: this.timer_layout,
							labels: this.timer_labels.split(","),
							timezone: this.timezone,
							labels1: this.timer_labels_singular.split(","),
							expiryText: this.timer_exp_text
						});
					}

				} else {
					this.wrap.countdown({
						until: this.timer_date,
						format: this.timer_format,
						layout: this.timer_layout,
						labels: this.timer_labels.split(","),
						timezone: this.timezone,
						labels1: this.timer_labels_singular.split(","),
					});
				}
			} else {
				if (this.fixed_timer_action === 'msg') {
					if (parseInt(window.location.href.toLowerCase().indexOf("?elementor")) === parseInt(-1)) {
						this.wrap.countdown({
							until: this.timer_date,
							format: this.timer_format,
							layout: this.timer_layout,
							labels: this.timer_labels.split(","),
							timezone: this.timezone,
							labels1: this.timer_labels_singular.split(","),
							expiryText: this.timer_exp_text,
						});
					} else {
						this.wrap.countdown({
							until: this.timer_date,
							format: this.timer_format,
							layout: this.timer_layout,
							labels: this.timer_labels.split(","),
							timezone: this.timezone,
							labels1: this.timer_labels_singular.split(","),
						});
					}
				} else if (this.fixed_timer_action == 'redirect') {

					this.wrap.countdown({
						until: this.timer_date,
						format: this.timer_format,
						layout: this.timer_layout,
						labels: this.timer_labels.split(","),
						timezone: this.timezone,
						labels1: this.timer_labels_singular.split(","),
						expiryText: this.timer_exp_text,
						onExpiry: this._redirectCounter
					});

				} else if (this.fixed_timer_action == 'hide') {

					this.wrap.countdown({
						until: this.timer_date,
						format: this.timer_format,
						layout: this.timer_layout,
						labels: this.timer_labels.split(","),
						timezone: this.timezone,
						labels1: this.timer_labels_singular.split(","),
						expiryText: this.timer_exp_text,
						onExpiry: this._destroyCounter
					});

				} else {
					this.wrap.countdown({
						until: this.timer_date,
						format: this.timer_format,
						layout: this.timer_layout,
						labels: this.timer_labels.split(","),
						timezone: this.timezone,
						labels1: this.timer_labels_singular.split(","),
						expiryText: this.timer_exp_text
					});
				}
			}
		},

		_destroyCounter: function () {
			if (parseInt(window.location.href.toLowerCase().indexOf("?elementor")) === parseInt(-1)) {
				jQuery(this).countdown('destroy');
			}
		},

		_redirectCounter: function () {

			redirect_link = jQuery.cookie(jQuery(this)[0].id + "redirect");
			redirect_link_target = jQuery.cookie(jQuery(this)[0].id + "redirectwindow");

			if (parseInt(window.location.href.toLowerCase().indexOf("?elementor")) === parseInt(-1)) {
				window.open(redirect_link, redirect_link_target);
			} else {
				return;
			}
		},

		_initEverGreenTimer: function () {
			var dateNow = new Date();

			if ((dateNow.getTime() - this.timer_date.getTime()) > 0) {

				if (this.evergreen_timer_action == 'msg') {
					if (parseInt(window.location.href.toLowerCase().indexOf("?elementor")) === parseInt(-1)) {
						this.wrap.append($.cookie("countdown-" + this.settings.id + "expiremsg"));
					} else {
						this.wrap.countdown({
							until: this.timer_date,
							format: this.timer_format,
							layout: this.timer_layout,
							labels: this.timer_labels.split(","),
							timezone: this.timezone,
							labels1: this.timer_labels_singular.split(","),
							expiryText: $.cookie("countdown-" + this.settings.id + "expiremsg"),
						});
					}

				} else if (this.evergreen_timer_action == 'redirect') {

					if (parseInt(window.location.href.toLowerCase().indexOf("?elementor")) === parseInt(-1)) {
						window.open(this.redirect_link, this.redirect_link_target);
					} else {
						this.wrap.countdown({
							until: this.timer_date,
							format: this.timer_format,
							layout: this.timer_layout,
							labels: this.timer_labels.split(","),
							timezone: this.timezone,
							labels1: this.timer_labels_singular.split(","),
							onExpiry: this._redirectCounter
						});
					}

				} else if (this.evergreen_timer_action == 'hide') {
					if (parseInt(window.location.href.toLowerCase().indexOf("?elementor")) === parseInt(-1)) {
						this.wrap.countdown('destroy');
					} else {
						this.wrap.countdown({
							until: this.timer_date,
							format: this.timer_format,
							layout: this.timer_layout,
							labels: this.timer_labels.split(","),
							timezone: this.timezone,
							labels1: this.timer_labels_singular.split(","),
							onExpiry: this._destroyCounter
						});
					}

				} else if (this.evergreen_timer_action == 'reset') {

					this.wrap.countdown({
						until: this.timer_date,
						format: this.timer_format,
						layout: this.timer_layout,
						labels: this.timer_labels.split(","),
						timezone: this.timezone,
						labels1: this.timer_labels_singular.split(","),
						onExpiry: this._restartCountdown
					});

				} else {
					this.wrap.countdown({
						until: this.timer_date,
						format: this.timer_format,
						layout: this.timer_layout,
						labels: this.timer_labels.split(","),
						timezone: this.timezone,
						labels1: this.timer_labels_singular.split(","),
					});
				}
			} else {
				if (this.evergreen_timer_action == 'msg') {

					if (parseInt(window.location.href.toLowerCase().indexOf("?elementor")) === parseInt(-1)) {
						this.wrap.countdown({
							until: this.timer_date,
							format: this.timer_format,
							layout: this.timer_layout,
							labels: this.timer_labels.split(","),
							timezone: this.timezone,
							labels1: this.timer_labels_singular.split(","),
							expiryText: $.cookie("countdown-" + this.settings.id + "expiremsg"),
						});
					} else {
						this.wrap.countdown({
							until: this.timer_date,
							format: this.timer_format,
							layout: this.timer_layout,
							labels: this.timer_labels.split(","),
							timezone: this.timezone,
							labels1: this.timer_labels_singular.split(","),
						});
					}

				} else if (this.evergreen_timer_action == 'redirect') {

					this.wrap.countdown({
						until: this.timer_date,
						format: this.timer_format,
						layout: this.timer_layout,
						labels: this.timer_labels.split(","),
						timezone: this.timezone,
						labels1: this.timer_labels_singular.split(","),
						onExpiry: this._redirectCounter
					});

				} else if (this.evergreen_timer_action == 'hide') {

					this.wrap.countdown({
						until: this.timer_date,
						format: this.timer_format,
						layout: this.timer_layout,
						labels: this.timer_labels.split(","),
						timezone: this.timezone,
						labels1: this.timer_labels_singular.split(","),
						onExpiry: this._destroyCounter
					});

				} else if (this.evergreen_timer_action == 'reset') {

					this.wrap.countdown({
						until: this.timer_date,
						format: this.timer_format,
						layout: this.timer_layout,
						labels: this.timer_labels.split(","),
						timezone: this.timezone,
						labels1: this.timer_labels_singular.split(","),
						onExpiry: this._restartCountdown
					});

				} else {
					this.wrap.countdown({
						until: this.timer_date,
						format: this.timer_format,
						layout: this.timer_layout,
						labels: this.timer_labels.split(","),
						timezone: this.timezone,
						labels1: this.timer_labels_singular.split(","),
					});
				}
			}

		},

		_restartCountdown: function () {

			$.cookie("countdown-" + settings.id + "-currdate", new Date());

			currdate = new Date($.cookie("countdown-" + settings.id + "-currdate"));

			var evergreen_date_days = $.cookie("countdown-" + settings.id + "-day");
			var evergreen_date_hour = $.cookie("countdown-" + settings.id + "-hour");
			var evergreen_date_minutes = $.cookie("countdown-" + settings.id + "-min");
			var evergreen_date_seconds = $.cookie("countdown-" + settings.id + "-sec");

			var timevar = (parseFloat(evergreen_date_days * 24 * 60 * 60) + parseFloat(evergreen_date_hour * 60 * 60) + parseFloat(evergreen_date_minutes * 60) + parseFloat(evergreen_date_seconds)) * 1000;
			currdate.setTime(currdate.getTime() + timevar);

			this.timer_date = currdate;

			jQuery(this).countdown('option', { until: this.timer_date });
		},
	};

})(jQuery);
