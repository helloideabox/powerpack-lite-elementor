/**
 * PowerPack shared masonry helper.
 *
 * A lightweight, Elementor-Gallery-style masonry: items are absolutely positioned
 * into the currently shortest column, sized by their rendered height. Column count
 * comes from a CSS variable (media-query resolved) and the column/row gaps are read
 * from the container's CSS 'gap' (resolved to px for any unit) — so there's no item
 * padding and no load-in shift.
 *
 * Usage from a widget handler:
 *   this.masonry = new PPMasonry( { container: el, itemSelector: '.pp-grid-item-wrap' } );
 *   this.masonry.observeResize();
 *   imagesLoaded( el, () => { this.masonry.setReady(); this.masonry.layout(); } );
 *   // onElementChange → this.masonry.layout();
 *   // onDestroy       → this.masonry.destroy();
 *
 * @since x.x.x
 */
( function ( $, window ) {
	'use strict';

	class PPMasonry {
		/**
		 * @param {Object}      settings
		 * @param {HTMLElement} settings.container       The masonry container element.
		 * @param {string}      settings.itemSelector    Direct-child item selector.
		 * @param {string}      [settings.excludeSelector] Extra selector to skip (e.g. items mid filter-animation).
		 * @param {string}      [settings.columnsVar]    CSS var holding the column count. Default '--pp-gallery-columns'.
		 * @param {number}      [settings.defaultColumns] Fallback column count. Default 3.
		 */
		constructor( settings ) {
			this.container       = settings.container;
			this.$container      = $( this.container );
			this.itemSelector    = settings.itemSelector || '.pp-grid-item-wrap';
			this.excludeSelector = settings.excludeSelector || '';
			this.columnsVar      = settings.columnsVar || '--pp-gallery-columns';
			this.defaultColumns  = settings.defaultColumns || 3;

			this.ready          = false;
			this.lastWidth      = null;
			this.resizeObserver = null;
		}

		/**
		 * Mark the instance ready so the ResizeObserver starts relaying out (used to
		 * hold off until images are loaded).
		 */
		setReady() {
			this.ready = true;
		}

		/**
		 * Visible items to lay out (skips filtered-out and excluded items).
		 */
		getItems() {
			let $items = this.$container.children( this.itemSelector ).not('.pp-grid-item-hidden');

			if ( this.excludeSelector ) {
				$items = $items.not( this.excludeSelector );
			}

			return $items.filter(':visible');
		}

		/**
		 * Strip the inline positioning so items fall back to the container's CSS grid.
		 *
		 * @param {jQuery} [$items] Items to reset; defaults to all items.
		 */
		reset( $items ) {
			this.$container.css( 'height', '' );

			( $items || this.$container.children( this.itemSelector ) ).each( function () {
				this.style.position = '';
				this.style.width = '';
				this.style.left = '';
				this.style.right = '';
				this.style.top = '';
			} );
		}

		/**
		 * Run a masonry layout pass.
		 */
		layout() {
			const container = this.container;

			if ( ! container ) {
				return;
			}

			const items = this.getItems();

			// Reset first so measurements come from the natural (CSS grid) layout.
			this.reset( items );

			if ( ! items.length ) {
				return;
			}

			const style = getComputedStyle( container ),
				horizontalGap  = parseFloat( style.columnGap ) || 0,
				verticalGap    = parseFloat( style.rowGap ) || 0,
				containerWidth = this.$container.width();

			// Column count from the CSS var (media-query resolved). Fall back to
			// measuring the item width only if the variable isn't available.
			let columns = parseInt( style.getPropertyValue( this.columnsVar ), 10 );

			if ( ! columns || columns < 1 ) {
				const measuredWidth = items[0].getBoundingClientRect().width;
				columns = measuredWidth ? Math.max( 1, Math.round( containerWidth / measuredWidth ) ) : this.defaultColumns;
			}

			const isRtl    = 'rtl' === style.direction,
				itemWidth  = ( containerWidth - horizontalGap * ( columns - 1 ) ) / columns,
				colHeights = new Array( columns ).fill( 0 );

			items.each( function ( index ) {
				const item = this;

				item.style.position = 'absolute';
				item.style.width = itemWidth + 'px';

				// Shortest column, biased to the natural column for the first row
				// (5px threshold keeps ties in left-to-right order).
				let column = index % columns,
					columnHeight = colHeights[ column ];

				colHeights.forEach( function ( height, i ) {
					if ( height && columnHeight > height + 5 ) {
						columnHeight = height;
						column = i;
					}
				} );

				item.style.top = colHeights[ column ] + 'px';
				item.style[ isRtl ? 'right' : 'left' ] = ( column * ( itemWidth + horizontalGap ) ) + 'px';

				colHeights[ column ] += item.getBoundingClientRect().height + verticalGap;
			} );

			// Height the container to the tallest column (minus the trailing gap).
			this.$container.css( 'height', Math.max( 0, Math.max.apply( Math, colHeights ) - verticalGap ) + 'px' );
		}

		/**
		 * Lay out only if the instance has been marked ready.
		 */
		layoutIfReady() {
			if ( this.ready ) {
				this.layout();
			}
		}

		/**
		 * Relayout whenever the container's width changes (not its height, which we
		 * set). Catches the Elementor editor settling the widget width a frame late.
		 */
		observeResize() {
			if ( ! this.container || 'undefined' === typeof ResizeObserver || this.resizeObserver ) {
				return;
			}

			this.resizeObserver = new ResizeObserver( () => {
				if ( ! this.ready ) {
					return;
				}

				const width = this.container.clientWidth;

				if ( width !== this.lastWidth ) {
					this.lastWidth = width;
					this.layout();
				}
			} );

			this.resizeObserver.observe( this.container );
		}

		/**
		 * Disconnect the ResizeObserver (call from the handler's onDestroy).
		 */
		destroy() {
			if ( this.resizeObserver ) {
				this.resizeObserver.disconnect();
				this.resizeObserver = null;
			}
		}
	}

	window.PPMasonry = PPMasonry;
} )( jQuery, window );
