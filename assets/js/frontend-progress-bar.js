(function ($) {
	$( window ).on( 'elementor/frontend/init', () => {
		class ProgressBarWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						progressWrapper: '.pp-progress-bar-wrapper',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings('selectors');
				return {
					$progressWrappers: this.$element.find(selectors.progressWrapper),
				};
			}

			bindEvents() {
				const observer = this.createObserver();
				observer.observe(this.$element[0]);
			}

			createObserver() {
				const options = {
					root: null,
					threshold: 0,
					rootMargin: '0px',
				};

				return new IntersectionObserver((entries) => {
					entries.forEach((entry) => {
						if (entry.isIntersecting) {
							this.initProgressBars();
						}
					});
				}, options);
			}

			initProgressBars() {
				this.elements.$progressWrappers.each((index, wrapper) => {
					const $wrapper = $(wrapper);
					const type = this.getElementSettings('type');
					const progressValue = Math.min(parseFloat($wrapper.data('value')) || 0, 100);  // Get data-value from the wrapper

					this.animateProgressBar(type, progressValue, $wrapper);
				});
			}

			animateProgressBar(type, progressValue, $wrapper) {
				switch (type) {
					case 'line':
						this.updateProgressBar($wrapper, type, progressValue, 'width');
						break;
					case 'circle':
					case 'circle_half':
						this.animateCircleProgressBar( $wrapper, type, progressValue );
						break;
					case 'vertical':
						this.updateProgressBar($wrapper, type, progressValue, 'height');
						break;
					case 'dots':
						this.updateProgressBar($wrapper, type, progressValue, 'width');
						break;
					default:
						this.updateProgressBar($wrapper, type, progressValue, 'width');
						break;
				}
			}

			updateProgressBar($wrapper, type, value, property) {
				const $fillElement = $wrapper.find('.pp-progress-fill'),
					$count         = $wrapper.find('.pp-progress-count'),
					gradient       = this.getElementSettings( 'gradient_colors' );

				if ( 'dots' === type ) {
					const width = $wrapper.outerWidth(),
						dotSize = this.getElementSettings( 'dot_size' ).size || 25,
						dotSpacing = this.getElementSettings( 'dot_spacing' ).size || 10,
						numberOfCircles = Math.ceil(width / (dotSize + dotSpacing)),
						circlesToFill = numberOfCircles * (value / 100),
						numberOfTotalFill = Math.floor(circlesToFill),
						fillPercent = 100 * (circlesToFill - numberOfTotalFill),
						className = "pp-progress-segment";

					$fillElement.attr('data-circles', numberOfCircles);
					$fillElement.attr('data-total-fill', numberOfTotalFill);
					$fillElement.attr('data-partial-fill', fillPercent);

					for (var i = 0; i < numberOfCircles; i++) {
						var innerHTML = '';

						if (i < numberOfTotalFill) {
							innerHTML = "<div class='segment-inner'></div>";
						} else if (i === numberOfTotalFill) {
							innerHTML = "<div class='segment-inner'></div>";
						}
						$fillElement.append("<div class='" + className + "'>" + innerHTML + "</div>");
					}

					this.ppDotsHandler( $wrapper );

				} else {
					$fillElement.css( property, `${value}%` );

					let gradientColors;

					if ( gradient ) {
						gradientColors = gradient.map(item => item.gradient_color).join(', ');
					}

					if ( gradientColors ) {
						$fillElement.css("background", "linear-gradient(-45deg, " + gradientColors + ")");
					}
				}

				$count.data('counter', 0).animate({ counter: value }, {
					duration: ( this.getElementSettings( 'bar_speed' ) ) ? this.getElementSettings( 'bar_speed' ) : 1500,
					easing: 'linear',
					step(counter) {
						$count.text(Math.ceil(counter) + "%");
					},
				});
			}

			ppDotsHandler( $wrapper ) {
				const $fillElement = $wrapper.find('.pp-progress-fill'),
					data = $fillElement.data(),
					speed = ( this.getElementSettings( 'bar_speed' ) ) ? this.getElementSettings( 'bar_speed' ) : 1500;

				var numberOfTotalFill = data.totalFill,
					numberOfCircles = data.circles,
					fillPercent = data.partialFill,
					increment = 0;

				dotIncrement( increment );

				function dotIncrement( inc ) {
					var $dot = $fillElement.find(".pp-progress-segment").eq(inc),
						dotWidth = 100;

					if (inc === numberOfTotalFill)
						dotWidth = fillPercent

					$dot.find(".segment-inner").animate({
						width: dotWidth + '%'
					}, speed / numberOfCircles, function () {
						increment++;
						if (increment <= numberOfTotalFill) {
							dotIncrement(increment);
						}
					});
				}
			}

			animateCircleProgressBar($wrapper, type, progressValue) {
				const $circle = $wrapper.find('.pp-bar-circle');
				const $circleHalfLeft = $wrapper.find('.pp-progress-fill-left');
				const $circleHalfRight = $wrapper.find('.pp-progress-fill-right');
				const $count = $wrapper.find('.pp-progress-count');

				const rotationMultiplier = type === 'circle' ? 3.6 : 1.8;

				$count.data('counter', 0).animate({ counter: progressValue }, {
					duration: ( this.getElementSettings( 'bar_speed' ) ) ? this.getElementSettings( 'bar_speed' ) : 1500,
					easing: 'linear',
					step(counter) {
						const rotate = counter * rotationMultiplier;

						$circleHalfLeft.css('transform', `rotate(${rotate}deg)`);

						if (rotate > 180 && type === 'circle') {
							$circle.css('clip-path', 'inset(0)');
							$circleHalfRight.css('visibility', 'visible');
						}

						$count.text(Math.ceil(counter) + "%");
					},
				});
			}
		}

		elementorFrontend.elementsHandler.attachHandler('pp-progress-bar', ProgressBarWidget);
	} );
})(jQuery);