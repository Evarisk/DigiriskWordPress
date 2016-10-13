"use strict";

window.eva_lib = {};
window.digirisk = {};

window.eva_lib.init = function() {
	window.eva_lib.load_list_script();
	window.eva_lib.init_array_form();
}

window.eva_lib.load_list_script = function() {
	for ( var key in window.digirisk ) {
		console.log('Initialisation de l\'objet: ' + key);
		window.digirisk[key].init();
	}
}

window.eva_lib.init_array_form = function() {
	console.log('Init array form');
	 window.eva_lib.array_form.init();
}

jQuery(document).ready(window.eva_lib.init);
