window.eoxiaJS.fraisPro = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.init = function() {
	window.eoxiaJS.fraisPro.event();
};

/**
 * Les évènements de la recherche.
 *
 * @since 1.4.0
 * @version 1.4.0
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.event = function() {
	jQuery( document ).on( 'click', '.dropdown-content li', window.eoxiaJS.fraisPro.selectStatus );
};

/**
 * Met l'ID du status dans un input caché.
 *
 * @since 1.4.0
 * @version 1.4.0
 *
 * @param MouseEvent L'état de la souris au moment du clic
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.selectStatus = function( event ) {
	var parent = jQuery( this ).closest( '.wpeo-dropdown' );

	parent.find( 'input' ).val( jQuery( this ).data( 'id' ) );
	parent.find( 'button > span' ).html( jQuery( this ).html() );
};
