(function ($) {
    'use strict';

	var WrapperLinkHandler = function( $scope ) {
		if ( $scope.data( 'pp-wrapper-link' ) ) {
			var wrapperLink = $scope.data('pp-wrapper-link'),
				id          = $scope.data('id'),
				url         = wrapperLink.url,
				isExternal  = wrapperLink.is_external ? '_blank' : '_self',
				rel         = wrapperLink.nofollow ? 'nofollow' : '',
				anchorTag   = document.createElement('a');

			$scope.on('click.onPPWrapperLink', function() {
				anchorTag.id            = 'pp-wrapper-link-' + id;
				anchorTag.href          = url;
				anchorTag.target        = isExternal;
				anchorTag.rel           = rel;
				anchorTag.style.display = 'none';

				document.body.appendChild(anchorTag);

				var anchorObj = document.getElementById(anchorTag.id);
				anchorObj.click();

				var timeout = setTimeout(function() {
					document.body.removeChild(anchorObj);
					clearTimeout(timeout);
				});
			});
		}
	};

    $(window).on('elementor/frontend/init', function () {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/global', WrapperLinkHandler );
    });
    
}(jQuery));
