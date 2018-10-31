(function ($) {
    "use strict";
    
    var getElementSettings = function ($element) {
		var elementSettings  = {},
			modelCID         = $element.data('model-cid');

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

	var MapHandler = function ($scope, $) {
		var map_elem                = $scope.find('.pp-google-map').eq(0),
            elementSettings         = getElementSettings( $scope ),
			locations               = map_elem.data('locations'),
			zoom                    = (map_elem.data('zoom') != '') ? map_elem.data('zoom') : 4,
			map_type                = (elementSettings.map_type != '') ? elementSettings.map_type : 'roadmap',
			streeview_control       = (elementSettings.map_option_streeview == 'yes') ? true : false,
			map_type_control        = (elementSettings.map_type_control == 'yes') ? true : false,
			zoom_control            = (elementSettings.zoom_control == 'yes') ? true : false,
			fullscreen_control      = (elementSettings.fullscreen_control == 'yes') ? true : false,
			scroll_zoom             = (elementSettings.map_scroll_zoom == 'yes') ? 'auto' : 'none',
			map_style               = (map_elem.data('custom-style') != '') ? map_elem.data('custom-style') : '',
			animation               = (elementSettings.marker_animation != '') ? elementSettings.marker_animation : '',
			iw_max_width            = (map_elem.data('iw-max-width') != '') ? map_elem.data('iw-max-width') : '',
			mapOptions              = '',
			marker_animation        = '',
			map                     = '',
			i                       = '';
        
        if ( animation == 'drop' ) {
        	var marker_animation = google.maps.Animation.DROP;
        } else if ( animation == 'bounce' ) {
        	var marker_animation = google.maps.Animation.BOUNCE;
        }
		
		(function initMap() {
			var latlng = new google.maps.LatLng(locations[0][0], locations[0][1]);
			mapOptions = {
				zoom:               zoom,
				center:             latlng,
				mapTypeId:          map_type,
				mapTypeControl:     map_type_control,
				streetViewControl:  streeview_control,
				zoomControl:        zoom_control,
				fullscreenControl:  fullscreen_control,
				gestureHandling:    scroll_zoom,
				styles:             map_style
			}
			var map = new google.maps.Map($scope.find('.pp-google-map')[0], mapOptions);
			
			var infowindow = new google.maps.InfoWindow();

			for (i = 0; i < locations.length; i++) {
                
				var icon           = '',
					lat            = locations[i][0],
					lng            = locations[i][1],
					info_win       = locations[i][2],
					title          = locations[i][3],
					description    = locations[i][4],
					icon_type      = locations[i][5],
					icon_url       = locations[i][6],
					icon_size      = locations[i][7],
					iw_on_load     = locations[i][8];
                
				if ( lat.length != '' && lng.length != '' ) {
                    
					if ( icon_type == 'custom' ) {

						icon_size = parseInt(icon_size);

						icon = {
							url: icon_url
						};
                        
                        if( ! isNaN( icon_size ) ) {

                    		icon.scaledSize = new google.maps.Size( icon_size, icon_size );
                            icon.origin = new google.maps.Point( 0, 0 );
                            icon.anchor = new google.maps.Point( 0, 0 );

                    	}
					}

					var marker = new google.maps.Marker({
						position:       new google.maps.LatLng(lat, lng),
						map:            map,
						title:          title,
						icon:           icon,
                        animation:      marker_animation,
					});
					
					if ( info_win == 'yes' && iw_on_load == 'iw_open' ) {
						var contentString = '<div class="pp-infowindow-content">';
						contentString += '<div class="pp-infowindow-title">'+title+'</div>';
						if ( description.length != '' ) {
							contentString += '<div class="pp-infowindow-description">'+description+'</div>';
						}
						contentString += '</div>';
                        
                        if ( iw_max_width != ''  ) {
		                	var max_width = parseInt( iw_max_width );
		                	var infowindow = new google.maps.InfoWindow({
	                            content: contentString,
	                            maxWidth: max_width
	                        } );
		                } else {
	                        var infowindow = new google.maps.InfoWindow({
	                            content: contentString,
	                        } );
		                }
                        
						infowindow.open(map, marker);
					}
					
					// Event that closes the Info Window with a click on the map
					google.maps.event.addListener(map, 'click', (function(infowindow) {
						return function() {
							infowindow.close();
						}
					})(infowindow));

					if ( info_win == 'yes' && locations[i][3] != '' ) {
						google.maps.event.addListener(marker, 'click', (function(marker, i) {
							return function() {
								var contentString = '<div class="pp-infowindow-content">';
									contentString += '<div class="pp-infowindow-title">'+locations[i][3]+'</div>';
									if ( locations[i][3].length != '' ) {
										contentString += '<div class="pp-infowindow-description">'+locations[i][4]+'</div>';
									}
									contentString += '</div>';

								infowindow.setContent(contentString);
                                
                                if ( iw_max_width != ''  ) {
                                    var max_width = parseInt( iw_max_width );
                                    var InfoWindowOptions = { maxWidth : max_width };
                                    infowindow.setOptions( { options:InfoWindowOptions } );
                                }

								infowindow.open(map, marker);
							}
						})(marker, i));
					}
				}
			}
		})()
	};
    
    $(window).on('elementor/frontend/init', function () {
        if ( elementorFrontend.isEditMode() ) {
			isEditMode = true;
		}
        
        elementorFrontend.hooks.addAction('frontend/element_ready/pp-google-maps.default', MapHandler);
    });
    
}(jQuery));