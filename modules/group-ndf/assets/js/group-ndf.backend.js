/**
 * Initialise l'objet "groupNDF" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */

window.eoxiaJS.noteDeFrais.groupNDF = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */

window.eoxiaJS.noteDeFrais.groupNDF.init = function() {
	jQuery( document ).on( 'click', '.validation_status.toggle .content .item', window.eoxiaJS.noteDeFrais.groupNDF.saveStatus );
};

window.eoxiaJS.noteDeFrais.groupNDF.saveStatus = function( event ) {
	var toggle = jQuery( this ).closest( '.validation_status.toggle' );
	var serialize = '';
	event.stopPropagation();
	toggle.find( '.label' ).html( jQuery( this ).html() );
	toggle.find( 'input[name="validation_status"]' ).val( jQuery( this ).html() );
	toggle.find( '.content' ).removeClass( 'active' );
	serialize = toggle.find( 'input' ).serialize();
	jQuery.post( ajaxurl, serialize, function( response ) {
		window.eoxiaJS.noteDeFrais.NDF.refreshNDF( null, response );
	}, 'json' );
};

window.eoxiaJS.noteDeFrais.groupNDF.exportedNoteDeFraisSuccess = function( triggeredElement, response ) {
	window.eoxiaJS.global.downloadFile( response.data.link, response.data.filename );
};
