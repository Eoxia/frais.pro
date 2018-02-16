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
	jQuery( document ).on( 'click', '.autocomplete-search-list li', window.eoxiaJS.fraisPro.search.select );
	jQuery( document ).on( 'click', '.bloc-reassign .autocomplete-search-list li', window.eoxiaJS.fraisPro.search.selectNote );
};

/**
 * Met l'ID dans un input caché.
 *
 * @since 1.4.0
 * @version 1.4.0
 *
 * @param MouseEvent L'état de la souri au moment du clic
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.search.select = function( event ) {
	var parent = jQuery( this ).closest( '.wpeo-autocomplete' );

	parent.find( 'input[type="hidden"]' ).val( jQuery( this ).data( 'id' ) );
	parent.find( 'input.autocomplete-search-input' ).val( jQuery( this ).data( 'result' ) );
};

/**
 * Lors de la sélection d'une note, rend le bouton enable.
 *
 * @since 1.4.0
 * @version 1.4.0
 *
 * @param MouseEvent L'état de la souri au moment du clic
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.search.selectNote = function( event ) {
	jQuery( this ).closest( '.bloc-reassign' ).find( '.button-disable' ).removeClass( 'button-disable' );
};

/**
 * Le callback en cas de réussite à la requête Ajax "fp_search_notes".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.search.searchedSuccess = function( triggeredElement, response ) {
	jQuery( 'table.list-note:not( .list-note-unaffected )' ).replaceWith( response.data.view );
};
