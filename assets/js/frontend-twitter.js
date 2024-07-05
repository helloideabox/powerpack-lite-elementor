(function ($) {
    'use strict';

	var TwitterTimelineHandler = function ($scope, $) {
		$(document).ready(function () {
			if ('undefined' !== twttr) {
				twttr.widgets.load();
			}
		});
	};

    $(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pp-twitter-timeline.default', TwitterTimelineHandler );
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pp-twitter-tweet.default', TwitterTimelineHandler );
    });

}(jQuery));
