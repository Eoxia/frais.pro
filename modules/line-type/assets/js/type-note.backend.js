/**
 * Initialise l'objet "typeNote" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.2.0
 * @version 1.2.0
 */

window.eoxiaJS.fraisPro.typeNote = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 * *
 * @since 1.2.0
 * @version 1.2.0
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.typeNote.init = function() {
	window.eoxiaJS.fraisPro.typeNote.event();
};

/**
 * Gestion des évènements
 *
 * @since 1.2.0
 * @version 1.2.0
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.typeNote.event = function() {
	jQuery( document ).on( 'click', '.content .toggle .content .item', window.eoxiaJS.fraisPro.typeNote.select );
};

/**
 * Change le 'lable' du toggle.
 *
 * @since 1.2.0
 * @version 1.2.0
 *
 * @param  {ClickEvent} event L'état lors du clic.
 * @return {void}
 *
 */
window.eoxiaJS.fraisPro.typeNote.select = function( event ) {
	var row = jQuery( this ).closest( '.row' );
	event.stopPropagation();
	row.find( '.toggle .label' ).text( jQuery( this ).text() );
	row.find( '.toggle input' ).val( jQuery( this ).data( 'id' ) );
	row.find( '.toggle .content' ).removeClass( 'active' );
	if ( ! row.hasClass( 'add' ) ) {
		jQuery( this ).each( window.eoxiaJS.fraisPro.line.saveNDF );
	}

	window.eoxiaJS.fraisPro.typeNote.setStateField( row, 'km', false );
	window.eoxiaJS.fraisPro.typeNote.setStateField( row, 'ttc', true );
	window.eoxiaJS.fraisPro.typeNote.setStateField( row, 'tva', true );

	if ( jQuery( this ).data( 'special-treatment' ) ) {
		window.eoxiaJS.fraisPro.typeNote[ jQuery( this ).data( 'special-treatment' ) ]( row );
	}
};

window.eoxiaJS.fraisPro.typeNote.km_calculation = function( row ) {
	window.eoxiaJS.fraisPro.typeNote.setStateField( row, 'km', true );
	window.eoxiaJS.fraisPro.typeNote.setStateField( row, 'ttc', false );
	window.eoxiaJS.fraisPro.typeNote.setStateField( row, 'tva', false );
};

/**
 * Rend un 'contenteditable' editable ou pas.
 *
 * @since 1.2.0
 * @version 1.2.0
 *
 * @param  {HTMLUListElement} element L'élément contenant les champs 'contenteditable'.
 * @param  {string} nameField         Le champ contenteditable en question.
 * @param  {boolean} enabled          Rendre enabled ou pas.
 * @return {void}
 */
window.eoxiaJS.fraisPro.typeNote.setStateField = function( element, nameField, enabled ) {
	element.find( '.' + nameField + ' span[contenteditable]' ).attr( 'contenteditable', enabled );

	if ( enabled ) {
		element.find( '.' + nameField ).removeClass( 'disabled' );
	} else {
		element.find( '.' + nameField ).addClass( 'disabled' );
	}
};
