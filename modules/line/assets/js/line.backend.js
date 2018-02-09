/**
 * Initialise l'objet "NDF" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.2.0
 */

window.eoxiaJS.fraisPro.Line = {};

/**
 * Display new created line.
 *
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.Line.displayLine = function( element, response ) {
	jQuery( 'div.list-line' ).prepend( response.data.view );
};
