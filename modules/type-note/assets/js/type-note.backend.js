/**
 * Initialise l'objet "typeNote" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.2.0
 * @version 1.2.0
 */

window.eoxiaJS.noteDeFrais.typeNote = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 * *
 * @since 1.2.0
 * @version 1.2.0
 *
 * @return {void}
 */
window.eoxiaJS.noteDeFrais.typeNote.init = function() {
	window.eoxiaJS.noteDeFrais.typeNote.event();
};

/**
 * Gestion des évènements
 *
 * @since 1.2.0
 * @version 1.2.0
 *
 * @return {void}
 */
window.eoxiaJS.noteDeFrais.typeNote.event = function() {
	jQuery( document ).on( 'click', '.content .toggle .content .item', window.eoxiaJS.noteDeFrais.typeNote.select );
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
window.eoxiaJS.noteDeFrais.typeNote.select = function( event ) {
	var row = jQuery( this ).closest( '.row' );
	event.stopPropagation();
	row.find( '.toggle .label' ).text( jQuery( this ).text() );
	row.find( '.toggle input' ).val( jQuery( this ).data( 'id' ) );
	row.find( '.toggle .content' ).removeClass( 'active' );
	if ( ! row.hasClass( 'add' ) ) {
		jQuery( this ).each( window.eoxiaJS.noteDeFrais.NDFL.saveNDF );
	}

	window.eoxiaJS.noteDeFrais.typeNote.setStateField( row, 'km', false );
	window.eoxiaJS.noteDeFrais.typeNote.setStateField( row, 'ttc', true );
	window.eoxiaJS.noteDeFrais.typeNote.setStateField( row, 'tva', true );

	if ( jQuery( this ).data( 'special-treatment' ) ) {
		window.eoxiaJS.noteDeFrais.typeNote[ jQuery( this ).data( 'special-treatment' ) ]( row );
	}
};

window.eoxiaJS.noteDeFrais.typeNote.km_calculation = function( row ) {
	window.eoxiaJS.noteDeFrais.typeNote.setStateField( row, 'km', true );
	window.eoxiaJS.noteDeFrais.typeNote.setStateField( row, 'ttc', false );
	window.eoxiaJS.noteDeFrais.typeNote.setStateField( row, 'tva', false );
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
window.eoxiaJS.noteDeFrais.typeNote.setStateField = function( element, nameField, enabled ) {
	element.find( '.' + nameField + ' span[contenteditable]' ).attr( 'contenteditable', enabled );

	if ( enabled ) {
		element.find( '.' + nameField ).removeClass( 'disabled' );
	} else {
		element.find( '.' + nameField ).addClass( 'disabled' );
	}
};
