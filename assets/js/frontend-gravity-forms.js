(function ($) {
    'use strict';

	var GFormsHandler = function( $scope, $ ) {
		if ( 'undefined' === typeof $scope ) {
			return;
		}

		$scope.find('select:not([multiple])').each(function() {
			var	gf_select_field = $( this );
			if( gf_select_field.next().hasClass('chosen-container') ) {
				gf_select_field.next().wrap( '<span class="pp-gf-select-custom"></span>' );
			} else {
				gf_select_field.wrap( '<span class="pp-gf-select-custom"></span>' );
			}
		});
	};

    $(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/pp-gravity-forms.default', GFormsHandler );
    });

}(jQuery));
