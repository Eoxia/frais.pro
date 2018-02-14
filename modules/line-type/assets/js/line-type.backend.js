/**
 * Initialise l'objet "lineType" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.2.0
 * @version 1.4.0
 */

window.eoxiaJS.fraisPro.lineType = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 * *
 * @since 1.2.0
 * @version 1.2.0
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.lineType.init = function() {
	window.eoxiaJS.fraisPro.lineType.event();
};

/**
 * Gestion des évènements
 *
 * @since 1.2.0
 * @version 1.2.0
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.lineType.event = function() {
	jQuery( document ).on( 'click', '.type .wpeo-dropdown li', window.eoxiaJS.fraisPro.lineType.select );
};

/**
 * Change les états des champs au changement.
 *
 * @since 1.2.0
 * @version 1.4.0
 *
 * @param  {ClickEvent} event L'état lors du clic.
 * @return {void}
 *
 */
window.eoxiaJS.fraisPro.lineType.select = function( event ) {
	var line = jQuery( this ).closest( 'div.line-content' );
	var inputIsReadOnly = '';
	var inputIsRequired = '';

	// Change the state of current input to no error.
	jQuery( this ).closest( 'div.form-element' ).removeClass( 'input-error' );

	// If there is a special treatment key on cliqued element, launch specific action.
	if ( jQuery( this ).data( 'special-treatment' ) ) {
		inputIsReadOnly = true;
		inputIsRequired = true;
	} else {
		inputIsReadOnly = false;
		inputIsRequired = false;
	}

	// Check input value for displaying or not error on line.
	window.eoxiaJS.fraisPro.line.setStatusField( jQuery( '.form-element.km' ), inputIsRequired );
	window.eoxiaJS.fraisPro.line.setStatusField( jQuery( '.form-element.ttc' ), ! inputIsRequired );
	window.eoxiaJS.fraisPro.line.setStatusField( jQuery( '.form-element.tva' ), ! inputIsRequired );

	// Mark field as readonly for KM by default. And remove readonly on amounts.
	window.eoxiaJS.fraisPro.line.setReadOnly( line, 'km', ! inputIsReadOnly );
	window.eoxiaJS.fraisPro.line.setReadOnly( line, 'ttc', inputIsReadOnly );
	window.eoxiaJS.fraisPro.line.setReadOnly( line, 'tva', inputIsReadOnly );

	window.eoxiaJS.fraisPro.line.checkLineStatus( jQuery( this ) );
};
