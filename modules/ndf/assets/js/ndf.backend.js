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
	jQuery( document ).on( 'click', '.row.add .action .ion-ios-plus', window.eoxiaJS.noteDeFrais.NDF.addRowNDF );
	jQuery( document ).on( 'click', '.row .action .ion-trash-a', window.eoxiaJS.noteDeFrais.NDF.deleteRowNDF );
	jQuery( document ).on( 'keydown', '.row.add span[contenteditable]', function( event ) {
		if ( event.ctrlKey && 13 === event.keyCode ) {
			jQuery( this ).closest( '.row' ).find( '.action .ion-ios-plus' ).click();
		}
	} );
	jQuery( document ).on( 'click', '.saveNDF', window.eoxiaJS.noteDeFrais.NDF.saveNDF );
	jQuery( document ).on( 'click', '.toggle .content .item', window.eoxiaJS.noteDeFrais.NDF.select );
};

window.eoxiaJS.noteDeFrais.NDF.openNdf = function( triggeredElement, response ) {
	jQuery( '.eox-note-frais' ).addClass( 'active-single' );
	jQuery( '.single-note' ).html( response.data.view );
};

window.eoxiaJS.noteDeFrais.NDF.closeNDF = function( event ) {
	jQuery( '.eox-note-frais' ).removeClass( 'active-single' );
};

window.eoxiaJS.noteDeFrais.NDF.addRowNDF = function( event ) {
	var addForm = jQuery( this ).closest( '.row' );
	var rowClone = addForm.clone();
	rowClone.removeClass( 'add' );
	jQuery( '.heading' ).after( rowClone );
	rowClone.find( 'span[contenteditable]' ).each( function( index ) {
		this.dataset.name = 'row[' + addForm.data( 'i' ) + '][' + this.dataset.inputName + ']';
		delete this.dataset.inputName;
	} );
	addForm.find( 'span[contenteditable]' ).each( function( index ) {
		var defaultValue = '';
		if ( undefined !== jQuery( this ).data( 'defaultValue' ) ) {
			defaultValue = jQuery( this ).data( 'defaultValue' );
		}
		jQuery( this ).text( defaultValue );
	} );
	addForm.data( 'i', parseInt( addForm.data( 'i' ) ) + 1 );
};

window.eoxiaJS.noteDeFrais.NDF.deleteRowNDF = function( event ) {
	jQuery( this ).closest( '.row' ).remove();
};

window.eoxiaJS.noteDeFrais.NDF.saveNDF = function() {
	var serialize = jQuery( '.note input' ).serialize();
	jQuery( '.note *[contenteditable="true"]' ).each( function( index ) {
		if ( undefined !== jQuery( this ).data( 'name' ) ) {
			if ( 0 !== serialize.length ) {
				serialize += '&';
			}
			serialize += jQuery( this ).data( 'name' ) + '=' + jQuery( this ).text();
		}
	} );
	jQuery.post( ajaxurl, serialize );
	//JQuery( '.note .close' ).click();
	location.reload();
};

window.eoxiaJS.noteDeFrais.NDF.select = function() {

};

window.eoxiaJS.noteDeFrais.NDF.isModified = function() {
	jQuery( window ).on( 'beforeunload.edit-post', function() {
		return true;
	} );
	jQuery( window ).trigger( 'beforeunload' );
};
