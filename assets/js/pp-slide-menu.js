( function ( $ ) {
	'use strict';

	$( window ).on( 'elementor/frontend/init', () => {
		const supportsInert = ( 'undefined' !== typeof HTMLElement ) && ( 'inert' in HTMLElement.prototype );

		// Namespace for every binding this handler makes, so a destroyed widget can
		// release them without disturbing handlers from other scripts.
		const EVENT_NS = '.ppSlideMenu';

		class SlideMenuWidget extends elementorModules.frontend.handlers.Base {
			getDefaultSettings() {
				return {
					selectors: {
						container: '.pp-slide-menu',
						menu:      '.pp-slide-menu__menu',
						links:     'li.pp-slide-menu-item-has-children > a',
						arrow:     '.pp-slide-menu-arrow',
						submenu:   '.pp-slide-menu-sub-menu',
						back:      '.pp-slide-menu-back',
						backIcon:  '.pp-slide-menu-back-icon',
					},
					classes: {
						activeItem:   'pp-slide-menu-item-is-active',
						active:       'pp-slide-menu-is-active',
						activeParent: 'pp-slide-menu-is-active-parent',
						expanded:     'pp-slide-menu-submenu-open',
					},
				};
			}

			getDefaultElements() {
				const selectors = this.getSettings( 'selectors' );

				return {
					$container: this.$element.find( selectors.container ),
					$menu:      this.$element.find( selectors.menu ),
					$links:     this.$element.find( selectors.links ),
				};
			}

			bindEvents() {
				// Open panels, deepest last, so Escape and the reset paths can unwind
				// them without re-deriving the chain from the DOM.
				this.openPanels = [];

				const elementSettings = this.getElementSettings(),
					selectors       = this.getSettings( 'selectors' ),
					isAccordion     = 'accordion' === elementSettings.submenu_behavior,
					linkNavigation  = 'yes' === elementSettings.link_navigation,
					useParentLabel  = 'parent_label' === elementSettings.back_text_source,
					backTextCustom  = elementSettings.back_text,
					backIcon        = this.$element.find( selectors.backIcon ).html() || '',
					self            = this;

				this.elements.$links.each( function () {
					const $link  = $( this ),
						$item    = $link.parent(),
						$submenu = $link.next( selectors.submenu ),
						$arrow   = $link.prev( selectors.arrow );

					if ( ! $submenu.length ) {
						return;
					}

					// Guard against a second pass over markup that was already processed.
					// Flagged on the panel rather than detected from the back row, which
					// accordion mode never adds.
					if ( $submenu.data( 'ppSlideMenuBound' ) ) {
						return;
					}

					$submenu.data( 'ppSlideMenuBound', true );

					const $triggers = linkNavigation ? $arrow.add( $link ) : $arrow;

					// With link navigation on, the link doubles as a disclosure control
					// and has to expose the same state as the arrow.
					if ( linkNavigation ) {
						$link.attr( 'aria-expanded', 'false' );
					}

					// Accordion panels expand under their parent, so they need no back
					// row and no panel bookkeeping - just a toggle.
					const $back = isAccordion ? $() : self.buildBackItem(
						useParentLabel ? $link.text().trim() : backTextCustom,
						backIcon
					);

					$triggers.on( 'click' + EVENT_NS, function ( event ) {
						event.preventDefault();
						event.stopPropagation();

						if ( isAccordion ) {
							self.toggleInlineSubmenu( $item, $arrow, $submenu, $triggers );
							return;
						}

						self.openSubmenu( $item, $arrow, $submenu, $triggers, $back );
					} );

					// The arrow carries role="button", so it has to implement the
					// keyboard activation that a real button would give for free.
					$arrow.on( 'keydown' + EVENT_NS, function ( event ) {
						if ( 'Enter' !== event.key && ' ' !== event.key && 'Spacebar' !== event.key ) {
							return;
						}

						event.preventDefault();
						$( this ).trigger( 'click' );
					} );

					if ( $back.length ) {
						$back.on( 'click' + EVENT_NS, function ( event ) {
							event.preventDefault();
							event.stopPropagation();

							self.closeSubmenu( $item, $arrow, $submenu, $triggers );
						} );

						$submenu.prepend( $back );
					}

					// Closed panels stay in the DOM, so they have to be taken out of the
					// tab order and the accessibility tree until they are opened.
					self.setSubmenuHidden( $submenu, true );
				} );

				this.bindClosingEvents( elementSettings );

				elementorFrontend.elements.$window.on( 'resize' + EVENT_NS + this.getID(), () => this.onWindowResize() );
			}

			/**
			 * Wire up the ways of leaving an open sub-menu behind.
			 *
			 * @param {Object} elementSettings Resolved widget settings.
			 */
			bindClosingEvents( elementSettings ) {
				if ( 'yes' === elementSettings.close_on_escape ) {
					this.$element.on( 'keydown' + EVENT_NS, ( event ) => {
						if ( 'Escape' !== event.key && 'Esc' !== event.key ) {
							return;
						}

						if ( this.closeDeepestPanel() ) {
							event.stopPropagation();
						}
					} );
				}

				if ( 'yes' === elementSettings.close_on_click_outside ) {
					// Delegated on the document, so it has to be released by the same
					// per-widget namespace that unbindEvents uses for the window.
					$( document ).on( 'click' + EVENT_NS + this.getID(), ( event ) => {
						if ( this.$element.get( 0 ).contains( event.target ) ) {
							return;
						}

						this.resetPanels();
					} );
				}

				if ( 'yes' === elementSettings.close_on_link_click ) {
					// Toggles stop propagation before this runs, so only links that
					// actually navigate reach it.
					this.$element.on( 'click' + EVENT_NS, '.pp-slide-menu-item-link', ( event ) => {
						if ( $( event.currentTarget ).is( 'button' ) ) {
							return;
						}

						this.resetPanels();
					} );
				}
			}

			unbindEvents() {
				if ( this.resizeTimer ) {
					clearTimeout( this.resizeTimer );
				}

				elementorFrontend.elements.$window.off( EVENT_NS + this.getID() );
				$( document ).off( EVENT_NS + this.getID() );

				// Handlers live on the menu's own nodes, including the back rows this
				// handler injected, so the whole subtree is cleared in one pass.
				this.$element.find( '*' ).addBack().off( EVENT_NS );
			}

			/**
			 * Close the most recently opened panel.
			 *
			 * @return {boolean} Whether there was anything open to close.
			 */
			closeDeepestPanel() {
				const panel = this.openPanels[ this.openPanels.length - 1 ];

				if ( ! panel ) {
					return false;
				}

				if ( panel.accordion ) {
					this.toggleInlineSubmenu( panel.$item, panel.$arrow, panel.$submenu, panel.$triggers );
				} else {
					this.closeSubmenu( panel.$item, panel.$arrow, panel.$submenu, panel.$triggers );
				}

				return true;
			}

			/**
			 * Return the menu to its top level.
			 */
			resetPanels() {
				// Closed deepest first, so each level restores the one above it. Bounded
				// by the starting depth in case a panel ever fails to deregister.
				let remaining = this.openPanels.length;

				while ( this.openPanels.length && remaining-- > 0 ) {
					this.closeDeepestPanel();
				}
			}

			/**
			 * Record a panel as open.
			 *
			 * @param {Object} panel Panel bookkeeping.
			 */
			registerPanel( panel ) {
				this.openPanels.push( panel );
			}

			/**
			 * Drop a panel from the open list.
			 *
			 * @param {jQuery} $submenu Sub-menu panel.
			 */
			deregisterPanel( $submenu ) {
				const element = $submenu.get( 0 );

				this.openPanels = this.openPanels.filter( ( panel ) => panel.$submenu.get( 0 ) !== element );
			}

			/**
			 * Measure the root list at its natural height.
			 *
			 * Measured on demand rather than cached at startup: in the editor the
			 * handler can run before the widget stylesheet has applied, and until it
			 * does the sub-menus are still in flow, so the root measures far too tall.
			 *
			 * @return {number} Natural height in pixels.
			 */
			getNaturalMenuHeight() {
				const $menu = this.elements.$menu,
					element = $menu.get( 0 ),
					inline  = element.style.height;

				$menu.css( { height: '' } );

				const natural = $menu.height();

				// Put back the height being transitioned from and force the browser to
				// commit it, otherwise the caller's value animates from auto and snaps.
				element.style.height = inline;
				void element.offsetHeight;

				return natural;
			}

			/**
			 * Whether sub-menus expand in place rather than sliding in as panels.
			 *
			 * @return {boolean} True when the Accordion behavior is selected.
			 */
			isAccordion() {
				return 'accordion' === this.getElementSettings( 'submenu_behavior' );
			}

			/**
			 * Whether the menu keeps a stable height instead of tracking the open panel.
			 *
			 * @return {boolean} True when the Fixed Height option is on.
			 */
			isFixedHeight() {
				return 'yes' === this.getElementSettings( 'menu_fixed_height' );
			}

			onWindowResize() {
				if ( this.resizeTimer ) {
					clearTimeout( this.resizeTimer );
				}

				this.resizeTimer = setTimeout( () => this.refreshHeight(), 150 );
			}

			/**
			 * Recalculate the inline height after a resize.
			 *
			 * The height is a pixel value measured at one viewport width, so it goes
			 * stale as soon as the menu is resized or a breakpoint changes.
			 */
			refreshHeight() {
				const selectors = this.getSettings( 'selectors' ),
					classes   = this.getSettings( 'classes' ),
					$menu     = this.elements.$menu;

				$menu.css( { height: '' } );

				// Accordion mode never sets an inline height, so clearing it is all
				// that is needed here.
				if ( this.isAccordion() || this.isFixedHeight() ) {
					return;
				}

				// Panels nest, so the last active one in document order is the deepest.
				const $active = this.$element.find( selectors.submenu + '.' + classes.active ).last();

				if ( $active.length ) {
					$menu.css( { height: $active.height() } );
				}
			}

			/**
			 * Build the "Back" row prepended to every sub-menu.
			 *
			 * @param {string} text     Back label.
			 * @param {string} iconHtml Rendered back icon markup.
			 * @return {jQuery} The back menu item.
			 */
			buildBackItem( text, iconHtml ) {
				const $item = $( '<li>', { 'class': 'menu-item pp-menu-item pp-slide-menu-item pp-slide-menu-back' } ),
					$icon   = $( '<span>', { 'class': 'pp-slide-menu-arrow pp-icon', 'aria-hidden': 'true' } ).html( iconHtml ),
					$button = $( '<button>', { type: 'button', 'class': 'pp-slide-menu-item-link pp-menu-sub-item-back' } ).text( text );

				return $item.append( $icon, $button );
			}

			/**
			 * Toggle a sub-menu's availability to keyboard and assistive tech.
			 *
			 * The panel stays rendered so it can animate out, only its interactivity
			 * is switched.
			 *
			 * @param {jQuery}  $submenu Sub-menu panel.
			 * @param {boolean} hidden   Whether the panel is closed.
			 */
			setSubmenuHidden( $submenu, hidden ) {
				if ( hidden ) {
					$submenu.attr( 'aria-hidden', 'true' );
				} else {
					$submenu.removeAttr( 'aria-hidden' );
				}

				this.setInert( $submenu, hidden, this.getPanelControls( $submenu ) );
			}

			/**
			 * Toggle the interactivity of a panel's own rows.
			 *
			 * Used on the panel a sub-menu was opened from: it is covered by the
			 * sub-menu (overlay) or pushed off screen (push), but its links stay in
			 * the tab order otherwise. Only the rows are switched, never the panel
			 * itself - the open sub-menu is nested inside one of them and inert
			 * cascades to descendants.
			 *
			 * @param {jQuery}  $panel Panel whose rows are being switched.
			 * @param {boolean} inert  Whether the rows should be unreachable.
			 */
			setPanelRowsInert( $panel, inert ) {
				const $controls = this.getPanelControls( $panel );

				this.setInert( $controls, inert, $controls );
			}

			/**
			 * Collect a panel's own focusable controls, excluding nested panels.
			 *
			 * @param {jQuery} $panel Menu panel.
			 * @return {jQuery} The panel's direct controls.
			 */
			getPanelControls( $panel ) {
				return $panel.children( 'li' ).children( 'a, button, [role="button"]' );
			}

			/**
			 * Take elements out of the tab order and the accessibility tree.
			 *
			 * @param {jQuery}  $targets  Elements to switch.
			 * @param {boolean} inert     Whether they should be unreachable.
			 * @param {jQuery}  $controls Focusable controls to fall back to where
			 *                            `inert` is unsupported.
			 */
			setInert( $targets, inert, $controls ) {
				if ( supportsInert ) {
					if ( inert ) {
						$targets.attr( 'inert', '' );
					} else {
						$targets.removeAttr( 'inert' );
					}

					return;
				}

				// Fallback without `inert`: the tab order can still be corrected, but
				// the rows stay readable by assistive tech.
				if ( inert ) {
					$controls.attr( 'tabindex', '-1' );
					return;
				}

				$controls.each( function () {
					const $control = $( this );

					// Arrows are custom buttons and need to stay explicitly focusable.
					if ( $control.is( '[role="button"]' ) ) {
						$control.attr( 'tabindex', '0' );
					} else {
						$control.removeAttr( 'tabindex' );
					}
				} );
			}

			/**
			 * Move focus without letting the browser scroll the menu out of view.
			 *
			 * The container clips its panels with overflow:hidden, which still leaves it
			 * programmatically scrollable. Panels sit a full width outside it, so
			 * focusing a control inside one makes the browser scroll it into view and
			 * the menu ends up showing empty space once the panel transforms into place.
			 *
			 * @param {jQuery} $target Element to receive focus.
			 */
			focusWithoutScroll( $target ) {
				const target = $target.get( 0 );

				if ( ! target ) {
					return;
				}

				// Browsers that do not understand the options object simply focus as
				// usual, which is why any scroll that slipped through is undone below.
				target.focus( { preventScroll: true } );

				const container = this.elements.$container.get( 0 );

				if ( container ) {
					container.scrollLeft = 0;
					container.scrollTop  = 0;
				}
			}

			/**
			 * Expand or collapse a sub-menu in place, for accordion mode.
			 *
			 * Nothing is positioned or measured here: the panel simply joins the flow
			 * under its parent item and the menu grows to fit.
			 *
			 * @param {jQuery} $item     Parent menu item.
			 * @param {jQuery} $arrow    Sub-menu toggle.
			 * @param {jQuery} $submenu  Sub-menu panel.
			 * @param {jQuery} $triggers Elements that toggle this panel.
			 */
			toggleInlineSubmenu( $item, $arrow, $submenu, $triggers ) {
				const classes = this.getSettings( 'classes' ),
					isOpen    = $item.hasClass( classes.expanded );

				$item.toggleClass( classes.expanded, ! isOpen );
				$arrow.toggleClass( classes.active, ! isOpen );
				$triggers.attr( 'aria-expanded', isOpen ? 'false' : 'true' );

				this.setSubmenuHidden( $submenu, isOpen );

				if ( isOpen ) {
					this.deregisterPanel( $submenu );
				} else {
					this.registerPanel( { $item, $arrow, $submenu, $triggers, accordion: true } );
				}
			}

			/**
			 * Slide a sub-menu into view.
			 *
			 * @param {jQuery} $item     Parent menu item.
			 * @param {jQuery} $arrow    Sub-menu toggle.
			 * @param {jQuery} $submenu  Sub-menu panel.
			 * @param {jQuery} $triggers Elements that open this panel.
			 * @param {jQuery} $back     The panel's back row.
			 */
			openSubmenu( $item, $arrow, $submenu, $triggers, $back ) {
				const classes = this.getSettings( 'classes' ),
					$parent   = $submenu.parents( 'ul' ).first();

				$item.addClass( classes.activeItem );
				$arrow.addClass( classes.active );
				$submenu.addClass( classes.active );
				$parent.addClass( classes.activeParent );

				if ( ! this.isFixedHeight() ) {
					this.elements.$menu.css( { height: $submenu.height() } );
				}

				$triggers.attr( 'aria-expanded', 'true' );
				this.setSubmenuHidden( $submenu, false );

				this.registerPanel( { $item, $arrow, $submenu, $triggers, accordion: false } );

				// Move focus into the panel that just became the active one.
				this.focusWithoutScroll( $back.children( 'button' ) );

				// Only once focus has left, or the row being blurred is the arrow that
				// was just used to get here.
				this.setPanelRowsInert( $parent, true );
			}

			/**
			 * Slide a sub-menu back out.
			 *
			 * @param {jQuery} $item     Parent menu item.
			 * @param {jQuery} $arrow    Sub-menu toggle.
			 * @param {jQuery} $submenu  Sub-menu panel.
			 * @param {jQuery} $triggers Elements that open this panel.
			 */
			closeSubmenu( $item, $arrow, $submenu, $triggers ) {
				const classes = this.getSettings( 'classes' ),
					$previous = $submenu.parents( 'ul' ).first();

				$item.removeClass( classes.activeItem );
				$arrow.removeClass( classes.active );
				$submenu.removeClass( classes.active );
				$previous.removeClass( classes.activeParent );

				if ( ! this.isFixedHeight() ) {
					// Intermediate panels have no inline height, so they measure directly;
					// the root has to be measured with its own inline height taken off.
					const isRoot = $previous.is( this.elements.$menu );

					this.elements.$menu.css( { height: isRoot ? this.getNaturalMenuHeight() : $previous.height() } );
				}

				$triggers.attr( 'aria-expanded', 'false' );

				// The panel being returned to has to be interactive again before it can
				// take focus back.
				this.setPanelRowsInert( $previous, false );

				// Only chase focus if it was inside the menu to begin with. Closing from
				// a click elsewhere on the page must not pull it back.
				if ( this.$element.get( 0 ).contains( document.activeElement ) ) {
					// Restored before the panel is made inert, otherwise focus lands on
					// the body in between.
					this.focusWithoutScroll( $arrow );
				}

				this.setSubmenuHidden( $submenu, true );
				this.deregisterPanel( $submenu );
			}
		}

		elementorFrontend.elementsHandler.attachHandler( 'pp-slide-menu', SlideMenuWidget );
	} );
} )( jQuery );
