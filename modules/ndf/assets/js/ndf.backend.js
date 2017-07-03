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
window.eoxiaJS.noteDeFrais.NDF.init = function() {};

window.eoxiaJS.noteDeFrais.NDF.openNdf = function( triggeredElement, response ) {
	jQuery( '.liste-note' ).hide();
	jQuery( '.single-note' ).html( response.data.view );
};

window.eoxiaJS.noteDeFrais.NDF.addNDF = function( triggeredElement, response ) {
	jQuery( '.single-note' ).html( response.data.view );
};
