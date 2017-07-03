/**
 * Initialise l'objet "NDF" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */

window.eoxiaJS.noteDeFrais.NDF = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.noteDeFrais.NDF.init = function() {
	jQuery( document ).on( 'click', '.note .close', window.eoxiaJS.noteDeFrais.NDF.closeNDF );
};

window.eoxiaJS.noteDeFrais.NDF.openNdf = function( triggeredElement, response ) {
	jQuery( '.eox-note-frais' ).addClass( 'active-single' );
	jQuery( '.single-note' ).html( response.data.view );
	//JQuery( '.single-note' ).addClass( 'active' );
};

window.eoxiaJS.noteDeFrais.NDF.closeNDF = function( event ) {
	event.preventDefault();
	jQuery( '.eox-note-frais' ).removeClass( 'active-single' );
};

window.eoxiaJS.noteDeFrais.NDF.addNDF = function( triggeredElement, response ) {
	jQuery( '.single-note' ).html( response.data.view );
};
