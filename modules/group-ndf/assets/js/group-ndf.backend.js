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
window.eoxiaJS.noteDeFrais.groupNDF.init = function() {};

window.eoxiaJS.noteDeFrais.groupNDF.exportedNoteDeFraisSuccess = function( triggeredElement, response ) {
	window.eoxiaJS.global.downloadFile( response.data.link, response.data.filename );
};
