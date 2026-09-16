(function ($) {
    'use strict';

	var SELECTOR = '.pp-twitter-buttons[data-iframe-title], .pp-twitter-timeline[data-iframe-title]';

	/**
	 * Twitter swaps the anchor for a cross-origin iframe whose only title is
	 * "Twitter Tweet Button", "Twitter Follow Button" or "Twitter Timeline" — never the
	 * account or the hashtag, and never unique when a page carries two of them.
	 * Reapply the accessible name the widget computed.
	 */
	function applyTitle( wrap ) {
		var want = wrap.getAttribute( 'data-iframe-title' ),
			iframe = wrap.querySelector( 'iframe' );

		// Bail when the title already matches so that reacting to attribute changes
		// cannot feed back into itself.
		if ( ! iframe || ! want || iframe.getAttribute( 'title' ) === want ) {
			return;
		}

		iframe.setAttribute( 'title', want );
	}

	/**
	 * Twitter inserts the iframe well after widgets.load() resolves, and the embed's
	 * own 'rendered' event does not reach us for every widget type — the timeline
	 * renders from a separately bundled chunk with its own event bus. Watching the
	 * wrapper covers both, and also re-applies the name if Twitter titles the iframe
	 * after inserting it.
	 */
	function watchWrap( wrap ) {
		var observer;

		if ( wrap.ppTitleWatched ) {
			return;
		}

		wrap.ppTitleWatched = true;

		applyTitle( wrap );

		if ( 'undefined' === typeof MutationObserver ) {
			return;
		}

		observer = new MutationObserver( function () {
			applyTitle( wrap );
		} );

		observer.observe( wrap, {
			childList: true,
			subtree: true,
			attributes: true,
			attributeFilter: [ 'title' ]
		} );

		// Twitter is long done by then; stop watching rather than leave an observer
		// running for the life of the page.
		window.setTimeout( function () {
			observer.disconnect();
		}, 30000 );
	}

	var TwitterWidgetHandler = function ($scope) {
		var root = $scope[0];

		if ( 'undefined' === typeof twttr || ! twttr.widgets ) {
			return;
		}

		// querySelectorAll only looks at descendants, so test the root separately.
		if ( root.matches && root.matches( SELECTOR ) ) {
			watchWrap( root );
		}

		Array.prototype.forEach.call( root.querySelectorAll( SELECTOR ), watchWrap );

		twttr.widgets.load( root );
	};

    $(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pp-twitter-buttons.default', TwitterWidgetHandler );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pp-twitter-timeline.default', TwitterWidgetHandler );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pp-twitter-tweet.default', TwitterWidgetHandler );
    });

}(jQuery));
