(function ($) {
    "use strict";
    
    var getElementSettings = function( $element ) {
		var elementSettings = {},
			modelCID 		= $element.data( 'model-cid' );

		if ( isEditMode && modelCID ) {
			var settings 		= elementorFrontend.config.elements.data[ modelCID ],
				settingsKeys 	= elementorFrontend.config.elements.keys[ settings.attributes.widgetType || settings.attributes.elType ];

			jQuery.each( settings.getActiveControls(), function( controlKey ) {
				if ( -1 !== settingsKeys.indexOf( controlKey ) ) {
					elementSettings[ controlKey ] = settings.attributes[ controlKey ];
				}
			} );
		} else {
			elementSettings = $element.data('settings') || {};
		}

		return elementSettings;
	};

    var isEditMode		= false;

	var CustomCursorHandler = function ($scope, $) {
		var elementSettings    = getElementSettings( $scope ),
			customCursorEnable = elementSettings.pp_custom_cursor_enable,
			columnId           = $scope.data('id'),
			cursorType         = elementSettings.pp_custom_cursor_type,
			cursorText         = elementSettings.pp_custom_cursor_text,
			cursorTarget       = elementSettings.pp_custom_cursor_target,
			cursorOptions      = $scope.data('cursorOptions') || {},
			cursorIconUrl      = ( undefined !== cursorOptions ) ? cursorOptions.url : '',
			leftOffset         = ( '' !== elementSettings.pp_custom_cursor_left_offset && undefined !== elementSettings.pp_custom_cursor_left_offset ) ? parseInt( elementSettings.pp_custom_cursor_left_offset.size ) : 0,
			topOffset          = ( '' !== elementSettings.pp_custom_cursor_top_offset && undefined !== elementSettings.pp_custom_cursor_top_offset ) ? parseInt( elementSettings.pp_custom_cursor_top_offset.size ) : 0;

		if ( 'yes' !== customCursorEnable ) {
			$("#style-" + columnId).remove();
		}

		leftOffset = ( isNaN(leftOffset) ) ? 0 : leftOffset;
		topOffset  = ( isNaN(topOffset) ) ? 0 : topOffset;
		
		var selector  = ".elementor-element-" + columnId,
			$selector = $(selector);

		if ( 'selector' === cursorTarget && elementSettings.pp_custom_cursor_css_selector ) {
			selector = elementSettings.pp_custom_cursor_css_selector,
			$selector = $scope.find(selector);
		}

		// Clean old styles each render.
		$( '#style-' + columnId ).remove();

		if ( 'image' === cursorType ) {
			if ( ! cursorIconUrl ) {
				return;
			}

			$('head').append('<style type="text/css" id="style-' + columnId + '">' + selector + ', ' + selector + ' * { cursor: url(' + cursorIconUrl + ') ' + leftOffset + ' ' + topOffset + ', auto !important; }</style>');

		} else if ( 'follow-image' === cursorType ) {
			if ( ! cursorIconUrl ) {
				return;
			}

			$scope.append('<img src="' + cursorIconUrl + '" alt="Cursor Image" class="pp-cursor-pointer" aria-hidden="true">');

			$selector.on( 'mouseenter.ppCursor', function() {
				$( '.pp-custom-cursor' ).removeClass( 'pp-cursor-active' );
				$scope.addClass( 'pp-cursor-active' );

				$( document ).on( 'mousemove.ppCursor', function( e ) {
					$scope.find( '.pp-cursor-pointer' ).offset( {
						left: e.pageX + leftOffset,
						top: e.pageY + topOffset
					} );
				} );
			} ).on( 'mouseleave.ppCursor', function() {
				$scope.removeClass( 'pp-cursor-active' );
				$( document ).off( 'mousemove.ppCursor' );
			} );

		} else if ( 'follow-text' === cursorType ) {
			var safeText = cursorText.replace( /(<([^>]+)>)/ig, '' );

			$scope.append('<div class="pp-cursor-pointer pp-cursor-pointer-text">' + safeText + '</div>');

			$selector.on( 'mouseenter.ppCursor', function() {
				$( '.pp-custom-cursor' ).removeClass( 'pp-cursor-active' );
				$scope.addClass( 'pp-cursor-active' );

				var $cursor = $scope.find( '.pp-cursor-pointer' ),
					width   = $cursor.outerWidth(),
					height  = $cursor.outerHeight();

				$( document ).on( 'mousemove.ppCursor', function( e ) {
					$cursor.offset( {
						left: e.pageX + leftOffset - ( width / 2 ),
						top: e.pageY + topOffset - ( height / 2 )
					} );
				} );
			} ).on( 'mouseleave.ppCursor', function() {
				$scope.removeClass( 'pp-cursor-active' );
				$( document ).off( 'mousemove.ppCursor' );
			} );
		}
	};
    
    $(window).on('elementor/frontend/init', function () {
        if ( elementorFrontend.isEditMode() ) {
			isEditMode = true;
		}
        
        elementorFrontend.hooks.addAction('frontend/element_ready/global', CustomCursorHandler);
    });
    
}(jQuery));
