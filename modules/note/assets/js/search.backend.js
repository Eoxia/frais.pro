/**
 * Initialise l'objet note de frais ainsi que la méthode "init" de l'objet "search" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.search = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.search.init = function() {
	window.eoxiaJS.fraisPro.search.event();
};

/**
 * Les évènements de la recherche.
 *
 * @since 1.4.0
 * @version 1.4.0
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.search.event = function() {
	jQuery( '.bloc-search' ).on( 'click', '.dropdown-content li', window.eoxiaJS.fraisPro.search.selectStatus );
	jQuery( '.bloc-search' ).on( 'click', '.autocomplete-search-list li', window.eoxiaJS.fraisPro.search.selectUser );
};

/**
 * Met l'ID du status dans un input caché.
 *
 * @since 1.4.0
 * @version 1.4.0
 *
 * @param MouseEvent L'état de la souri au moment du clic
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.search.selectStatus = function( event ) {
	var parent = jQuery( this ).closest( '.wpeo-dropdown' );

	parent.find( 'input' ).val( jQuery( this ).data( 'id' ) );
	parent.find( 'span' ).text( jQuery( this ).text() );

};

/**
 * Met l'ID de l'utilisateur dans un input caché.
 *
 * @since 1.4.0
 * @version 1.4.0
 *
 * @param MouseEvent L'état de la souri au moment du clic
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.search.selectUser = function( event ) {
	var parent = jQuery( this ).closest( '.wpeo-autocomplete' );

	parent.find( 'input[type="hidden"]' ).val( jQuery( this ).data( 'id' ) );
	parent.find( '.autocomplete-icon-after' ).click();
	parent.find( 'input[type="text"]' ).val( jQuery( this ).data( 'result' ) );
};
