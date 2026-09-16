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

				// Month, weekday and am/pm names in the site language, printed with the popup.
				let dateI18n = {};
				try {
					dateI18n = JSON.parse(popup.attr('data-date-i18n') || '{}');
				} catch (e) {
					dateI18n = {};
				}

				// Format a date with a PHP date() format string, the syntax WordPress uses for
				// its own date and time settings. A backslash escapes the next character.
				const formatPhpDate = function (date, format, utc) {
					if (!format || isNaN(date.getTime())) {
						return '';
					}

					const get = (unit) => date[(utc ? 'getUTC' : 'get') + unit](),
						pad = (value) => String(value).padStart(2, '0'),
						year = get('FullYear'),
						month = get('Month'),
						day = get('Date'),
						weekday = get('Day'),
						hours = get('Hours'),
						hours12 = hours % 12 || 12,
						meridiem = hours < 12 ? 'am' : 'pm';

					// Site-language name, falling back to English when none was printed.
					const name = (list, index, options) => (dateI18n[list] && dateI18n[list][index]) ||
						new Intl.DateTimeFormat('en-US', Object.assign({ timeZone: utc ? 'UTC' : undefined }, options)).format(date);

					const tokens = {
						d: () => pad(day),
						D: () => name('weekdaysShort', weekday, { weekday: 'short' }),
						j: () => day,
						l: () => name('weekdays', weekday, { weekday: 'long' }),
						N: () => weekday || 7,
						S: () => (day > 3 && day < 21) ? 'th' : ({ 1: 'st', 2: 'nd', 3: 'rd' }[day % 10] || 'th'),
						w: () => weekday,
						F: () => name('months', month, { month: 'long' }),
						m: () => pad(month + 1),
						M: () => name('monthsShort', month, { month: 'short' }),
						n: () => month + 1,
						Y: () => year,
						y: () => pad(year % 100),
						a: () => (dateI18n.meridiem && dateI18n.meridiem[meridiem]) || meridiem,
						A: () => (dateI18n.meridiem && dateI18n.meridiem[meridiem.toUpperCase()]) || meridiem.toUpperCase(),
						g: () => hours12,
						G: () => hours,
						h: () => pad(hours12),
						H: () => pad(hours),
						i: () => pad(get('Minutes')),
						s: () => pad(get('Seconds'))
					};

					let output = '';

					for (let i = 0; i < format.length; i++) {
						const char = format[i];

						if ('\\' === char) {
							i++;
							output += format[i] || '';
						} else {
							output += Object.prototype.hasOwnProperty.call(tokens, char) ? tokens[char]() : char;
						}
					}

					return output;
				};

				// Text for an Event Date & Time popup field, built from the formats on its element.
				const getEventTimeText = function (event, $field) {
					const dateFormat = $field.attr('data-date-format') || '',
						timeFormat = $field.attr('data-time-format') || '',
						start = new Date(event.startStr),
						end = new Date(event.endStr);

					if (event.allDay) {
						const alldayText = $field.attr('data-allday-text') || elementSettings.allday_text || '';

						// All-day dates carry no time, so read them in UTC to keep the day from shifting.
						let dayText = formatPhpDate(start, dateFormat, true);

						if (dayText && !isNaN(end.getTime())) {
							// FullCalendar's all-day end date is exclusive.
							end.setUTCDate(end.getUTCDate() - 1);

							const endDayText = formatPhpDate(end, dateFormat, true);
							if (end.getTime() > start.getTime() && endDayText !== dayText) {
								dayText += ' - ' + endDayText;
							}
						}

						return [dayText, alldayText].filter(Boolean).join(' ');
					}

					const startText = [formatPhpDate(start, dateFormat), formatPhpDate(start, timeFormat)].filter(Boolean).join(' ');

					if (isNaN(end.getTime()) || end.getTime() <= start.getTime()) {
						return startText;
					}

					// Repeat the date on the end only when the event runs into another day.
					const endDate = start.toDateString() === end.toDateString() ? '' : formatPhpDate(end, dateFormat),
						endText = [endDate, formatPhpDate(end, timeFormat)].filter(Boolean).join(' ');

					return endText ? startText + ' - ' + endText : startText;
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

								const title = info.event.title,
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

								// date & time markup: each field has its own formats
								timeWrap.each(function () {
									const $wrap = $(this),
										$time = $wrap.find('span.pp-event-calendar-event-time'),
										timeText = getEventTimeText(info.event, $time);

									$time.text(timeText);
									$wrap.toggle('' !== timeText);
								});

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
