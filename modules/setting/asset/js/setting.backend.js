/**
 * Initialise l'objet "setting" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 6.0.0
 */
window.eoxiaJS.digirisk.setting = {};

window.eoxiaJS.digirisk.setting.init = function() {
	window.eoxiaJS.digirisk.setting.event();
};

window.eoxiaJS.digirisk.setting.event = function() {
	jQuery( document ).on( 'click', '#digi-danger-preset .save-all', window.eoxiaJS.digirisk.setting.savePresetRisks );
	jQuery( document ).on( 'click', '#digi-danger-preset table tr input:not(input[type="checkbox"]), #digi-danger-preset tr .toggle, #digi-danger-preset .dropdown-toggle, #digi-danger-preset tr textarea, #digi-danger-preset tr .popup, #digi-danger-preset tr .action', window.eoxiaJS.digirisk.setting.checkTheCheckbox );
	jQuery( document ).on( 'click', '.settings_page_digirisk-setting .list-users .wp-digi-pagination a', window.eoxiaJS.digirisk.setting.pagination );
};

window.eoxiaJS.digirisk.setting.savePresetRisks = function( event ) {
	if ( event ) {
		event.preventDefault();
	}

	if ( jQuery( '#digi-danger-preset tr.risk-row.edit.checked .save.action-input' ).length ) {
		window.eoxiaJS.loader.display( jQuery( '#digi-danger-preset .save-all' ) );
		jQuery( '#digi-danger-preset tr.risk-row.edit.checked .save.action-input' ).click();
	}
};


/**
 * Gestion de la pagination des utilisateurs.
 *
 * @param  {ClickEvent} event [description]
 *
 * @since 6.4.0
 */
window.eoxiaJS.digirisk.setting.pagination = function( event ) {
	var href = jQuery( this ).attr( 'href' ).split( '&' );
	var nextPage = href[2].replace( 'current_page=', '' );

	jQuery( '.list-users' ).addClass( 'loading' );

	var data = {
		action: 'paginate_setting_page_user',
		next_page: nextPage
	};

	event.preventDefault();

	jQuery.post( window.ajaxurl, data, function( view ) {
		jQuery( '.list-users' ).replaceWith( view );
		window.eoxiaJS.digirisk.search.renderChanged();
	} );
};


/**
 * Coches la case à cocher lors de l'action dans une ligne du tableau.
 *
 * @param  {ClickEvent} event L'état du clic.
 * @return {void}
 *
 * @since 6.2.3
 */
window.eoxiaJS.digirisk.setting.checkTheCheckbox = function( event ) {
	jQuery( this ).closest( 'tr.risk-row.edit' ).addClass( 'checked' );
	jQuery( '#digi-danger-preset .save-all' ).removeClass( 'disable' ).addClass( 'green' );
};

window.eoxiaJS.digirisk.setting.savedRiskSuccess = function( element, response ) {
	if ( jQuery( '#digi-danger-preset tr.risk-row.edit.checked .save.action-input' ).length <= 1 ) {
		window.eoxiaJS.loader.remove( jQuery( '#digi-danger-preset .save-all' ) );
	}

	jQuery( element ).closest( 'tr' ).replaceWith( response.data.template );
};


/**
 * Le callback en cas de réussite à la requête Ajax "save_capacity".
 * Affiches le message de "success".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 6.4.0
 */
window.eoxiaJS.digirisk.setting.savedCapability = function( triggeredElement, response ) {};
