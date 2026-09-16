( function ( $ ) {
	$( window ).on( 'elementor/frontend/init', () => {

		var VISUALLY_HIDDEN_STYLE = 'position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0,0,0,0);white-space:nowrap;border:0;';

		class ChartsWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						chart: '.pp-chart-wrapper',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );
				return {
					$chart: this.$element.find( selectors.chart ),
				};
			}

			bindEvents() {
				if ( ! this.elements.$chart.length ) {
					return;
				}

				var $wrapper = this.elements.$chart,
					dataSettings = $wrapper.data( 'settings' );

				// Nothing to render (e.g. an editor placeholder is showing instead).
				if ( ! dataSettings ) {
					return;
				}

				var $canvas = this.buildAccessibleCanvas( $wrapper );

				if ( window.matchMedia && window.matchMedia( '(prefers-reduced-motion: reduce)' ).matches ) {
					dataSettings.options = dataSettings.options || {};
					dataSettings.options.animation = false;
				}

				this.intersectionObserver = elementorModules.utils.Scroll.scrollObserver( {
					callback: event => {
						if ( event.isInViewport ) {
							this.intersectionObserver.unobserve( $canvas[0] );

							var ctx = $canvas[0].getContext( '2d' );
							var ChartJS = window.ppChartJS || window.Chart;
							this.chartInstance = new ChartJS( ctx, dataSettings );
						}
					}
				} );

				this.intersectionObserver.observe( $canvas[0] );
			}

			/**
			 * Build the chart <canvas> with the ARIA metadata screen readers (including VoiceOver) need to make sense of it, since canvas pixel content is invisible to assistive technology on its own.
			 * 
			 * @param {jQuery} $wrapper
			 * @return {jQuery} the created canvas, wrapped in jQuery.
			 */
			buildAccessibleCanvas( $wrapper ) {
				var chartId = $wrapper.data( 'id' ) || ( 'pp-chart-' + Date.now() ),
					ariaLabel = $wrapper.data( 'aria-label' ) || '',
					ariaDescription = $wrapper.data( 'aria-desc' ) || '',
					tableId = $wrapper.data( 'table-id' ) || '',
					descId = ariaDescription ? ( chartId + '-desc' ) : '';

				$wrapper.empty();

				var canvas = document.createElement( 'canvas' );
				canvas.id = chartId;
				canvas.setAttribute( 'role', 'img' );
				canvas.setAttribute( 'tabindex', '0' );

				if ( ariaLabel ) {
					canvas.setAttribute( 'aria-label', ariaLabel );
				}

				var describedBy = [ descId, tableId ].filter( Boolean ).join( ' ' );
				if ( describedBy ) {
					canvas.setAttribute( 'aria-describedby', describedBy );
				}

				$wrapper.append( canvas );

				if ( descId ) {
					var descEl = document.createElement( 'span' );
					descEl.id = descId;
					descEl.className = 'pp-chart-sr-only';
					descEl.setAttribute( 'style', VISUALLY_HIDDEN_STYLE );
					descEl.textContent = ariaDescription;
					$wrapper.append( descEl );
				}

				return $wrapper.find( '> canvas' );
			}

			onDestroy() {
				if ( this.intersectionObserver && this.elements.$chart && this.elements.$chart.length ) {
					var $canvas = this.elements.$chart.find( '> canvas' );
					if ( $canvas.length ) {
						this.intersectionObserver.unobserve( $canvas[0] );
					}
				}

				if ( this.chartInstance ) {
					this.chartInstance.destroy();
					this.chartInstance = null;
				}

				if ( super.onDestroy ) {
					super.onDestroy();
				}
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-charts', ChartsWidget );
	} );
} ) ( jQuery );
