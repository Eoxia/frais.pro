/**
 * Initialise l'objet note de frais ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.noteDeFrais.note = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.noteDeFrais.note.init = function() {
	jQuery( document ).on( 'click', '.list-note .note', window.eoxiaJS.noteDeFrais.note.goToLink  );
};

/**
 * Redirige l'utilisateur sur la note
 *
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.noteDeFrais.note.goToLink = function( event ) {
	window.location.href = jQuery( this ).data( 'link' );
};
