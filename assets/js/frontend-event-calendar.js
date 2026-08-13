(function ($) {
	$(window).on('elementor/frontend/init', function () {
		// This edition renders the month grid only. The paid edition adds the
		// week, day and list views, and the toolbar buttons that switch between
		// them, so the view is fixed here rather than read from a setting.
		const INITIAL_VIEW = 'dayGridMonth';

		class PPEventCalendarWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						container: '.pp-event-calendar-container',
						calendar: '.pp-event-calendar',
						popup: '.pp-event-calendar-popup-wrapper',
						popupClose: '.pp-event-calendar-popup-close'
					}
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings('selectors');

				return {
					$container: this.$element.find(selectors.container),
					$calendarEl: this.$element.find(selectors.calendar),
					$popup: this.$element.find(selectors.popup),
					$popupClose: this.$element.find(selectors.popupClose)
				};
			}

			onDestroy() {
				if (this.calendar) {
					this.calendar.destroy();
					this.calendar = null;
				}
			}

			bindEvents() {
				const widgetId = this.getID(),
					calendarElement = this.elements.$calendarEl.length ? this.elements.$calendarEl[0] : document.getElementById('pp-event-calendar-' + widgetId),
					elementSettings = this.getElementSettings(),
					timesFormat = this.elements.$container.data('time-format'),
					popup = this.elements.$popup,
					popupClose = this.elements.$popupClose;

				if (!calendarElement) {
					return;
				}

				// Convert a repeater of button groups into a FullCalendar toolbar string.
				// Each group's buttons are joined with "," (touching); groups are joined with " " (spaced).
				const buildToolbarSlot = function (groups) {
					if (!Array.isArray(groups) || groups.length === 0) {
						return '';
					}
					return groups
						.map((group) => Array.isArray(group.buttons) ? group.buttons.join(',') : '')
						.filter((str) => str.length > 0)
						.join(' ');
				};

				const headerToolbar = {
					left: buildToolbarSlot(elementSettings.header_left_groups),
					center: buildToolbarSlot(elementSettings.header_center_groups),
					right: buildToolbarSlot(elementSettings.header_right_groups)
				};

				const footerToolbar = ('yes' === elementSettings.show_footer_toolbar) ? {
					left: buildToolbarSlot(elementSettings.footer_left_groups),
					center: buildToolbarSlot(elementSettings.footer_center_groups),
					right: buildToolbarSlot(elementSettings.footer_right_groups)
				} : false;

				// Helper to format times according to provided format string (best-effort)
				const buildTimeFormatter = function (timeFormatString) {
					// returns a function(date) => formatted string
					return function (date) {
						// date is a Date object
						if (!date || !(date instanceof Date)) {
							return '';
						}
						const options = {};

						// Determine hour options
						if (timeFormatString && timeFormatString.indexOf('H') !== -1) {
							options.hour = '2-digit';
							options.hour12 = false;
						} else {
							options.hour = 'numeric';
							options.hour12 = true;
						}
						options.minute = '2-digit';

						const formatted = new Intl.DateTimeFormat('en-US', options).format(date);
						if (timeFormatString && (timeFormatString.indexOf('a') !== -1)) {
							return formatted.toLowerCase();
						}
						return formatted;
					};
				};

				// Resolve calendar sizing: 'auto' fits content, 'fixed' uses an explicit
				// height, 'aspectRatio' sizes height relative to width.
				let calendarHeight = 'auto';
				let calendarAspectRatio;
				if ('fixed' === elementSettings.calendar_height_type && elementSettings.calendar_height && elementSettings.calendar_height.size) {
					const heightSetting = elementSettings.calendar_height;
					// px -> a plain number; other units (vh) -> a CSS string value.
					calendarHeight = ('vh' === heightSetting.unit) ? (heightSetting.size + heightSetting.unit) : heightSetting.size;
				} else if ('aspectRatio' === elementSettings.calendar_height_type) {
					// Leave height unset so FullCalendar applies aspectRatio instead.
					calendarHeight = undefined;
					// Convert a "W:H" ratio string into the numeric ratio FullCalendar expects.
					const ratioParts = String(elementSettings.calendar_aspect_ratio || '').split(':');
					calendarAspectRatio = (2 === ratioParts.length && parseFloat(ratioParts[1])) ? (parseFloat(ratioParts[0]) / parseFloat(ratioParts[1])) : 1.35;
				}

				// Prev/Next buttons: arrows (FullCalendar default) or custom text labels.
				let navButtonIcons;
				let navButtonText;
				if ('text' === elementSettings.nav_button_style) {
					// Disabling icons makes every nav button fall back to its text label,
					// so prev/next and prevYear/nextYear all use the labels below.
					navButtonIcons = false;
					navButtonText = {
						prev: elementSettings.prev_button_text || 'Prev',
						next: elementSettings.next_button_text || 'Next',
						prevYear: elementSettings.prev_year_button_text || 'Prev Year',
						nextYear: elementSettings.next_year_button_text || 'Next Year'
					};
				}

				// Event time labels follow the site's time format (WordPress 'time_format'):
				// 24-hour formats show "14:30"; 12-hour formats show full lowercase "am/pm".
				const is24HourFormat = !!(timesFormat && (timesFormat.indexOf('H') !== -1 || timesFormat.indexOf('G') !== -1));
				const eventTimeFormat = is24HourFormat
					? { hour: '2-digit', minute: '2-digit', hour12: false }
					: { hour: 'numeric', minute: '2-digit', meridiem: 'lowercase' };

				// Initialize FullCalendar with the resolved event data.
				const initCalendar = (eventData) => {
					// Destroy any previous instance to avoid duplicate calendars on editor re-render.
					if (this.calendar) {
						this.calendar.destroy();
						this.calendar = null;
					}

					const calendar = new FullCalendar.Calendar(calendarElement, {
						headerToolbar: headerToolbar,
						footerToolbar: footerToolbar,
						buttonIcons: navButtonIcons,
						buttonText: navButtonText,
						timeZone: elementSettings.timezone,
						firstDay: elementSettings.first_day,
						initialView: INITIAL_VIEW,
						dayMaxEvents: true,
						height: calendarHeight,
						aspectRatio: calendarAspectRatio,
						eventDisplay: elementSettings.event_display,
						eventTimeFormat: eventTimeFormat,
						events: eventData,
						eventClick: (info) => {
							// "Do Nothing": swallow the click, no popup, no navigation.
							if ('none' === elementSettings.event_click_action) {
								info.jsEvent.preventDefault();
								return;
							}

							// "Open Popup": show popup and populate fields
							if ('popup' === elementSettings.event_click_action) {
								info.jsEvent.preventDefault();

								const parseToDate = function (timeString) {
									return new Date(timeString);
								};
								const time_format = timesFormat !== undefined ? timesFormat : 'g:i a';
								const timeFormatter = buildTimeFormatter(time_format);

								const allDay = info.event.allDay,
									title = info.event.title,
									startDate = info.event.startStr,
									endDate = info.event.endStr,
									guest = info.event.extendedProps ? info.event.extendedProps.guest : '',
									location = info.event.extendedProps ? info.event.extendedProps.location : '',
									description = info.event.extendedProps ? info.event.extendedProps.description : '',
									detailsUrl = info.event.url,
									imageUrl = info.event.extendedProps ? info.event.extendedProps.image : '';

								const titleWrap = popup.find('.pp-event-calendar-event-title'),
									timeWrap = popup.find('.pp-event-calendar-event-time-wrap'),
									guestWrap = popup.find('.pp-event-calendar-event-guest-wrap'),
									locationWrap = popup.find('.pp-event-calendar-event-location-wrap'),
									descWrap = popup.find('.pp-event-calendar-popup-desc'),
									detailsWrap = popup.find('.pp-event-calendar-popup-readmore-link'),
									imageWrap = popup.find('.pp-event-calendar-popup-image'),
									imageTitleWrap = popup.find('.pp-event-calendar-popup-image-title');

								// hide all initially
								imageWrap.hide();
								titleWrap.hide();
								timeWrap.hide();
								guestWrap.hide();
								locationWrap.hide();
								descWrap.hide();
								detailsWrap.hide();
								popup.addClass('pp-event-calendar-popup-ready');

								// image markup
								popup.removeClass('pp-event-calendar-popup-has-image');
								imageTitleWrap.text(title || '');
								if (imageUrl) {
									popup.addClass('pp-event-calendar-popup-has-image');
									imageWrap.show();
									imageWrap.find('img').attr('src', imageUrl).attr('alt', title || '');
								}

								// title markup
								if (title) {
									titleWrap.show();
									titleWrap.text(title);
								}

								// guest markup
								if (guest) {
									guestWrap.show();
									guestWrap.find('span.pp-event-calendar-event-guest').text(guest);
								}

								// location markup
								if (location) {
									locationWrap.show();
									locationWrap.find('span.pp-event-calendar-event-location').text(location);
								}

								// description markup
								if (description) {
									descWrap.show();
									descWrap.html(description);
								}

								// time markup
								if (allDay !== true) {
									timeWrap.show();
									const sDate = parseToDate(startDate);
									const eDate = parseToDate(endDate);
									const startTimeText = timeFormatter(sDate);
									let endTimeText = 'Invalid Data';
									if (!isNaN(eDate.getTime()) && sDate.getTime() < eDate.getTime()) {
										endTimeText = timeFormatter(eDate);
									}
									timeWrap.find('span.pp-event-calendar-event-time').text(startTimeText + ' - ' + endTimeText);
								} else {
									timeWrap.show();
									timeWrap.find('span.pp-event-calendar-event-time').text(elementSettings.allday_text);
								}

								// read more markup
								if (detailsUrl) {
									detailsWrap.show();
									detailsWrap.attr('href', detailsUrl);
									if ('on' === info.event.extendedProps.external) {
										detailsWrap.attr('target', '_blank');
									} else {
										detailsWrap.removeAttr('target');
									}
									if ('on' === info.event.extendedProps.nofollow) {
										detailsWrap.attr('rel', 'nofollow');
									} else {
										detailsWrap.removeAttr('rel');
									}
								}
							} else {
								// "Open Link": follow the event's URL (external opens in a new tab)
								if (info.event.url && info.event.extendedProps && info.event.extendedProps.external) {
									info.jsEvent.preventDefault();
									const id = this.$element.data('id');
									const anchor = document.createElement('a');
									anchor.id = 'pp-event-calendar-link-' + id;
									anchor.href = info.event.url;
									anchor.target = info.event.extendedProps.external ? '_blank' : '_self';
									anchor.rel = info.event.extendedProps.nofollow ? 'nofollow noreferer' : '';
									anchor.style.display = 'none';
									document.body.appendChild(anchor);
									const anchorReal = document.getElementById(anchor.id);
									anchorReal.click();
									// cleanup
									setTimeout(function () {
										if (anchorReal && anchorReal.parentNode) {
											anchorReal.parentNode.removeChild(anchorReal);
										}
									}, 100);
									return false;
								}
							}
						}
					});

					if ('yes' === elementSettings.show_week_numbers) {
						calendar.setOption('weekNumbers', true);
					}

					if ('yes' === elementSettings.nav_links) {
						calendar.setOption('navLinks', true);
					}

					if ('yes' !== elementSettings.show_weekends) {
						calendar.setOption('weekends', false);
					}

					if (Array.isArray(elementSettings.hidden_days) && elementSettings.hidden_days.length) {
						calendar.setOption('hiddenDays', elementSettings.hidden_days.map((day) => parseInt(day, 10)));
					}

					if ('yes' !== elementSettings.display_event_time) {
						calendar.setOption('displayEventTime', false);
					} else if ('yes' !== elementSettings.display_event_end) {
						calendar.setOption('displayEventEnd', false);
					}

					if ('yes' !== elementSettings.default_current_month && elementSettings.default_month) {
						calendar.gotoDate(elementSettings.default_month);
					}

					if (elementSettings.event_color) {
						calendar.setOption('eventColor', elementSettings.event_color);
					}

					this.calendar = calendar;

					// Render calendar
					calendar.render();
				};

				if (true === this.isEdit && elementSettings.events && elementSettings.events.models) {
					const eventData = [];
					$.map(elementSettings.events.models, function (value, i) {
						const eventItem = {};
						const isAllDay = ('yes' === value.attributes.all_day);

						eventItem['id'] = i;
						eventItem['classNames'] = 'elementor-repeater-item-' + value.attributes._id;
						eventItem['title'] = value.attributes.event_title;
						eventItem['description'] = value.attributes.description;
						eventItem['start'] = isAllDay ? value.attributes.start_event_allday : value.attributes.start_event;
						eventItem['end'] = isAllDay ? value.attributes.end_event_allday : value.attributes.end_event;
						eventItem['url'] = (value.attributes.event_url && value.attributes.event_url.url) ? value.attributes.event_url.url : '';
						eventItem['allDay'] = value.attributes.all_day;
						eventItem['guest'] = value.attributes.guest;
						eventItem['location'] = value.attributes.location;
						eventItem['image'] = value.attributes.image || '';

						eventData.push(eventItem);
					});

					initCalendar(eventData);
				} else {
					const $inline = this.$element.find('.pp-event-calendar-events');
					let inlineRaw = $inline.length ? $inline.text() : '';
					inlineRaw = inlineRaw ? inlineRaw.trim() : '';

					let eventData = [];
					try {
						eventData = JSON.parse(inlineRaw);
					} catch (e) {
						eventData = [];
					}

					initCalendar(eventData);
				}

				this.elements.$popup.on('click', (e) => {
					e.stopPropagation();
					// if clicked on wrapper or close button
					if (e.target === e.currentTarget || e.target === popupClose[0] || e.target === popupClose.find('svg')[0]) {
						popup.addClass('pp-event-calendar-popup-removing').removeClass('pp-event-calendar-popup-ready');
					}
				});
			}
		}

		elementorFrontend.elementsHandler.attachHandler('pp-event-calendar', PPEventCalendarWidget);
	});
})(jQuery);
