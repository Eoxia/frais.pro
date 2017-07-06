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
	var currentFocusout = true;
	jQuery( document ).on( 'click', '.single-note .note .close', window.eoxiaJS.noteDeFrais.NDF.closeNDF );
	jQuery( document ).on( 'click', '.row.add .action .ion-ios-plus', window.eoxiaJS.noteDeFrais.NDF.saveNDF );
	jQuery( document ).on( 'keydown', '.row.add span[contenteditable]', function( event ) {
		if ( event.ctrlKey && 13 === event.keyCode ) {
			jQuery( this ).closest( '.row' ).find( '.action .ion-ios-plus' ).click();
		}
	} );
	jQuery( document ).on( 'keydown', '.row:not(.add) span[contenteditable]', function( event ) {
		if ( currentFocusout ) {
			jQuery( this ).focusout( function( event ) {
				jQuery( this ).each( window.eoxiaJS.noteDeFrais.NDF.saveNDF );
				currentFocusout = true;
			} );
		}
		currentFocusout = false;
	} );
	jQuery( document ).on( 'click', '.toggle .content .item', window.eoxiaJS.noteDeFrais.NDF.select );
};

window.eoxiaJS.noteDeFrais.NDF.refreshNDF = function( triggeredElement, response ) {
	jQuery( '.single-note' ).html( response.data.view );
	jQuery( '.note[data-group_id="' + response.data.group.id + '"] .ttc .value' ).text( response.data.group.ttc );
	jQuery( '.note[data-group_id="' + response.data.group.id + '"] .tva .value' ).text( response.data.group.tx_tva );
};

window.eoxiaJS.noteDeFrais.NDF.openNdf = function( triggeredElement, response ) {
	jQuery( '.eox-note-frais' ).addClass( 'active-single' );
	window.eoxiaJS.noteDeFrais.NDF.refreshNDF( triggeredElement, response );
};

window.eoxiaJS.noteDeFrais.NDF.closeNDF = function( event ) {
	jQuery( '.eox-note-frais' ).removeClass( 'active-single' );
};

window.eoxiaJS.noteDeFrais.NDF.saveNDF = function( event ) {
	var name, extra, value = '';
	var serialize = jQuery( '.single-note .note' ).not( '.row' ).children( 'input' ).serialize();
	if ( 0 !== serialize.length ) {
		serialize += '&';
	}
	jQuery( this ).closest( '.row' ).find( 'input' ).each( function() {
		if ( 0 !== serialize.length ) {
			serialize += '&';
		}
		name = jQuery( this ).serialize().substr( 0, jQuery( this ).serialize().indexOf( '=' ) );
		extra = name.substr( ( ( -1 === name.indexOf( '%5B' ) ) ? name.length : name.indexOf( '%5B' ) ) );
		name = name.substr( 0, ( ( -1 === name.indexOf( '%5B' ) ) ? name.length : name.indexOf( '%5B' ) ) );
		value = jQuery( this ).serialize().substr( jQuery( this ).serialize().indexOf( '=' ) );
		serialize += 'row[' + jQuery( this ).closest( '.row' ).data( 'i' ) + '][' + name + ']' + extra + value;
	} );
	jQuery( this ).closest( '.row' ).find( 'span[contenteditable]' ).each( function( index ) {
		if ( undefined !== jQuery( this ).data( 'name' ) ) {
			if ( 0 !== serialize.length ) {
				serialize += '&';
			}
			serialize += jQuery( this ).data( 'name' ) + '=' + jQuery( this ).text();
		}
	} );

	jQuery.post( ajaxurl, serialize, function( response ) {
		window.eoxiaJS.noteDeFrais.NDF.refreshNDF( null, response );
	}, 'json' );
};

window.eoxiaJS.noteDeFrais.NDF.select = function( event ) {
	event.stopPropagation();
	jQuery( this ).closest( '.row' ).find( '.toggle .label' ).text( jQuery( this ).text() );
	jQuery( this ).closest( '.row' ).find( '.toggle input' ).val( jQuery( this ).text() );
	jQuery( this ).closest( '.row' ).find( '.toggle .content' ).removeClass( 'active' );
	if ( ! jQuery( this ).closest( '.row' ).hasClass( 'add' ) ) {
		jQuery( this ).each( window.eoxiaJS.noteDeFrais.NDF.saveNDF );
	}
};

window.eoxiaJS.noteDeFrais.NDF.isModified = function() {
	jQuery( window ).on( 'beforeunload.edit-post', function() {
		return true;
	} );
	jQuery( window ).trigger( 'beforeunload' );
};
