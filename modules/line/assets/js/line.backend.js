/**
 * Initialise l'objet "NDF" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.2.0
 */

window.eoxiaJS.noteDeFrais.Line = {};

window.eoxiaJS.noteDeFrais.Line.displayLine = function( element, response ) {
	jQuery( 'div.list-line' ).prepend( response.data.view );
};
