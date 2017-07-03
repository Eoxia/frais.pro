/**
 * Initialise l'objet "NDF" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */

window.eoxiaJS.noteDeFrais.NDF = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.noteDeFrais.NDF.init = function() {
	jQuery( document ).on( 'click', '.note .close', window.eoxiaJS.noteDeFrais.NDF.closeNDF );
	jQuery( document ).on( 'click', '.row.add .action', window.eoxiaJS.noteDeFrais.NDF.addRowNDF );
	jQuery( document ).on( 'keydown', '.row.add span[contenteditable="true"]', function( event ) {
		if ( event.ctrlKey && 13 === event.keyCode ) {
			jQuery( this ).closest( '.row' ).find( '.action' ).click();
		}
	} );
	jQuery( document ).on( 'click', '.saveNDF', window.eoxiaJS.noteDeFrais.NDF.saveNDF );
};

window.eoxiaJS.noteDeFrais.NDF.openNdf = function( triggeredElement, response ) {
	jQuery( '.eox-note-frais' ).addClass( 'active-single' );
	jQuery( '.single-note' ).html( response.data.view );
};

window.eoxiaJS.noteDeFrais.NDF.closeNDF = function( event ) {
	jQuery( '.eox-note-frais' ).removeClass( 'active-single' );
};

window.eoxiaJS.noteDeFrais.NDF.addNDF = function( triggeredElement, response ) {
	jQuery( '.single-note' ).html( response.data.view );
};

window.eoxiaJS.noteDeFrais.NDF.addRowNDF = function( event ) {
	var addForm = jQuery( this ).closest( '.row' );
	var rowClone = addForm.clone();
	rowClone.removeClass( 'add' );
	addForm.before( rowClone );
	addForm.find( 'span[contenteditable="true"]' ).each( function( index ) {
		var defaultValue = '';
		if ( undefined !== jQuery( this ).data( 'defaultValue' ) ) {
			defaultValue = jQuery( this ).data( 'defaultValue' );
		}
		jQuery( this ).text( defaultValue );
	} );
};

window.eoxiaJS.noteDeFrais.NDF.saveNDF = function() {

};

window.eoxiaJS.noteDeFrais.NDF.isModified = function() {
	jQuery( window ).on( 'beforeunload.edit-post', function() {
		return true;
	} );
	jQuery( window ).trigger( 'beforeunload' );
};
