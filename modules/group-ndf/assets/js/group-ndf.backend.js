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
	jQuery( document ).on( 'focusout', '.single-note .note .title[contenteditable]', window.eoxiaJS.noteDeFrais.groupNDF.saveGroup );
};

window.eoxiaJS.noteDeFrais.groupNDF.saveGroup = function( event ) {
	var serialize = jQuery( '.single-note .note' ).not( '.row' ).children( 'input' ).serialize();
	if ( undefined !== jQuery( this ).data( 'name' ) ) {
		if ( 0 !== serialize.length ) {
			serialize += '&';
		}
		serialize += jQuery( this ).data( 'name' ) + '=' + jQuery( this ).text();
	}
	jQuery.post( ajaxurl, serialize );
};
