(function ($) {
    'use strict';

    var isEditMode = false;
    
    $(window).on('elementor/frontend/init', function () {
        if ( elementorFrontend.isEditMode() ) {
			isEditMode = true;
		}
		
		if (isEditMode) {
			parent.document.addEventListener("mousedown", function(e) {
				var widgets = parent.document.querySelectorAll(".elementor-element--promotion");

				if (widgets.length > 0) {
					for (var i = 0; i < widgets.length; i++) {
						if (widgets[i].contains(e.target)) {
							var dialog = parent.document.querySelector("#elementor-element--promotion__dialog");
							var icon = widgets[i].querySelector(".icon > i");

							if (icon.classList.toString().indexOf("ppicon") >= 0) {
								dialog.querySelector(".dialog-buttons-action").style.display = "none";
								e.stopImmediatePropagation();

								if (dialog.querySelector(".pp-dialog-buttons-action") === null) {
									var button = document.createElement("a");
									var buttonText = document.createTextNode("Upgrade to PowerPack Pro");

									button.setAttribute("href", "https://powerpackelements.com/upgrade/?utm_medium=pp-elements-lite&utm_source=pp-editor-icons&utm_campaign=pp-pro-upgrade");
									button.setAttribute("target", "_blank");
									button.classList.add(
										"elementor-button",
										"go-pro",
										"dialog-button",
										"dialog-action",
										"dialog-buttons-action",
										"pp-dialog-buttons-action"
									);
									button.appendChild(buttonText);

									dialog.querySelector(".dialog-buttons-action").insertAdjacentHTML("afterend", button.outerHTML);
								} else {
									dialog.querySelector(".pp-dialog-buttons-action").style.display = "";
								}
							} else {
								dialog.querySelector(".dialog-buttons-action").style.display = "";

								if (dialog.querySelector(".pp-dialog-buttons-action") !== null) {
									dialog.querySelector(".pp-dialog-buttons-action").style.display = "none";
								}
							}

							// stop loop
							break;
						}
					}
				}
			});
		}
    });
    
}(jQuery));