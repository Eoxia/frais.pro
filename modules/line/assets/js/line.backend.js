/**
 * Initialise l'objet "Line" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.4.0
 */

window.eoxiaJS.fraisPro.line = {};

window.eoxiaJS.fraisPro.line.init = function() {
	window.eoxiaJS.fraisPro.line.event();
};

/**
 * Gestion des évènements
 *
 * @since 1.4.0
 * @version 1.4.0
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.line.event = function() {
	jQuery( document ).on( 'keyup', 'input[type=text]', window.eoxiaJS.fraisPro.line.checkInputStatus );
};

/**
 * Display new created line.
 *
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.line.displayLine = function( element, response ) {
	jQuery( 'div.list-line' ).prepend( response.data.view );
};

/**
 * Delete a line from display after user delete it.
 *
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.line.deleteLineFromDisplay = function( element, response ) {
	jQuery( 'div.line[data-id=' + response.data.line.id + ']' ).fadeOut();
};

/**
 * Check a line status, after an element is changed on the line.
 *
 * @param  {[type]} event [description]
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.line.checkInputStatus = function( event ) {
	var currentInputValue = jQuery( this ).val();

	window.eoxiaJS.fraisPro.line.setStatusField( jQuery( this ).closest( 'div.form-element' ), true );

	window.eoxiaJS.fraisPro.line.checkLineStatus( jQuery( this ) );
};

/**
 * Check a line status, after an element is changed on the line.
 *
 * @param  {[type]} event [description]
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.line.checkLineStatus = function( element ) {
	var line = element.closest( 'div.line-content' );
	var lineAction = line.children( 'div.status' );
	var pinStatus = lineAction.find( 'span.pin' );

	var hasError = false;
	line.children( 'div.form-element' ).each( function() {
		if ( jQuery( this ).hasClass( 'input-error' ) ) {
			hasError = true;
		}
	} );

	if ( hasError ) {
		if ( ! pinStatus.hasClass( 'line-error' ) ) {
			pinStatus.removeClass( 'line-ok' );
			pinStatus.addClass( 'line-error' );
			lineAction.attr( 'aria-label', fraisPro.lineStatusInvalid );
		}
	} else {
		if ( ! pinStatus.hasClass( 'line-ok' ) ) {
			pinStatus.removeClass( 'line-error' );
			pinStatus.addClass( 'line-ok' );
			lineAction.attr( 'aria-label', fraisPro.lineStatusValid );
		}
	}
};

/**
 * Applique ou enlève la classe erreur sur les champs, selon si ils sont correctement renseignés.
 *
 * @since 1.4.0
 * @version 1.4.0
 *
 * @param  {HTMLULListElement} element L'élément contenant les champs 'contenteditable'.
 * @param  {string} nameField         Le champ contenteditable en question.
 * @param  {boolean} action           Action a effectuer: ajouter/supprimer.
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.line.setStatusField = function( inputContainer, action ) {
	var input = inputContainer.find( 'input' ).val();
	if ( action && ( ( '' === input ) || ( 0 == input ) ) ) {
		inputContainer.addClass( 'input-error' );
	} else {
		inputContainer.removeClass( 'input-error' );
	}
};

/**
 * Permet ou supprime la possibilité d'écrire dans un champs de type text selon le type de note sélectionné.
 *
 * @since 1.2.0
 * @version 1.4.0
 *
 * @param  {HTMLULListElement} element L'élément contenant les champs 'contenteditable'.
 * @param  {string} nameField         Le champ contenteditable en question.
 * @param  {boolean} enabled          Rendre enabled ou pas.
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.line.setReadOnly = function( element, nameField, enabled ) {
	element.find( '.' + nameField + ' input[type=text]' ).attr( 'readonly', enabled );
};
