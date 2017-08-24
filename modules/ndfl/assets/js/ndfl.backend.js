/**
 * Initialise l'objet "NDF" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */

window.eoxiaJS.noteDeFrais.NDFL = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0.0
 * @version 1.0.0.0
 */
window.eoxiaJS.noteDeFrais.NDFL.init = function() {
	var currentFocusout = true;
	jQuery( document ).on( 'click', '.row.add .action .ion-ios-plus', window.eoxiaJS.noteDeFrais.NDFL.saveNDF );
	jQuery( document ).on( 'keydown', '.row.add span[contenteditable]', function( event ) {
		if ( event.ctrlKey && 13 === event.keyCode ) {
			jQuery( this ).closest( '.row' ).find( '.action .ion-ios-plus' ).click();
		}
	} );
	jQuery( document ).on( 'keydown', '.row:not(.add) span[contenteditable]', function( event ) {
		if ( currentFocusout ) {
			jQuery( this ).focusout( function( event ) {
				jQuery( this ).each( window.eoxiaJS.noteDeFrais.NDFL.saveNDF );
				currentFocusout = true;
			} );
		}
		currentFocusout = false;
	} );
	jQuery( document ).on( 'click', '.content .toggle .content .item', window.eoxiaJS.noteDeFrais.NDFL.select );
	jQuery( document ).on( 'keydown', '.libelle span', window.eoxiaJS.noteDeFrais.NDFL.focusSelect );
};

window.eoxiaJS.noteDeFrais.NDFL.confirmDeletion = function( element ) {
	return confirm( jQuery( element ).data().confirmText );
}

window.eoxiaJS.noteDeFrais.NDFL.saveNDF = function( event ) {
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
	jQuery( this ).closest( '.row' ).addClass( 'loading' );
	jQuery( '.single-note' ).find( '.date_modified_value' ).addClass( 'loading' );
	jQuery.post( ajaxurl, serialize, function( response ) {
		window.eoxiaJS.noteDeFrais.NDF.refresh( null, response );
	}, 'json' );
};

window.eoxiaJS.noteDeFrais.NDFL.select = function( event ) {
	event.stopPropagation();
	jQuery( this ).closest( '.row' ).find( '.toggle .label' ).text( jQuery( this ).text() );
	jQuery( this ).closest( '.row' ).find( '.toggle input' ).val( jQuery( this ).text() );
	jQuery( this ).closest( '.row' ).find( '.toggle .content' ).removeClass( 'active' );
	if ( ! jQuery( this ).closest( '.row' ).hasClass( 'add' ) ) {
		jQuery( this ).each( window.eoxiaJS.noteDeFrais.NDFL.saveNDF );
	} else {
		if ( 'Autre' == jQuery( this ).text() ) {
			jQuery( this ).closest( '.row' ).find( '.km span[contenteditable]' ).attr( 'contenteditable', false );
			jQuery( this ).closest( '.row' ).find( '.km' ).addClass( 'disabled' );
			jQuery( this ).closest( '.row' ).find( '.ttc.disabled span[contenteditable]' ).attr( 'contenteditable', true );
			jQuery( this ).closest( '.row' ).find( '.ttc.disabled' ).removeClass( 'disabled' );
			jQuery( this ).closest( '.row' ).find( '.tva.disabled span[contenteditable]' ).attr( 'contenteditable', true );
			jQuery( this ).closest( '.row' ).find( '.tva.disabled' ).removeClass( 'disabled' );
		} else {
			jQuery( this ).closest( '.row' ).find( '.km.disabled span[contenteditable]' ).attr( 'contenteditable', true );
			jQuery( this ).closest( '.row' ).find( '.km.disabled' ).removeClass( 'disabled' );
			jQuery( this ).closest( '.row' ).find( '.ttc span[contenteditable]' ).attr( 'contenteditable', false );
			jQuery( this ).closest( '.row' ).find( '.ttc' ).addClass( 'disabled' );
			jQuery( this ).closest( '.row' ).find( '.tva span[contenteditable]' ).attr( 'contenteditable', false );
			jQuery( this ).closest( '.row' ).find( '.tva' ).addClass( 'disabled' );
		}
	}
};

window.eoxiaJS.noteDeFrais.NDFL.focusSelect = function( event ) {
	var code = ( event.keyCode ? event.keyCode : event.which );
	if ( 9 == code ) {
		event.preventDefault();
		jQuery( this ).blur();
		jQuery( this ).closest( '.row' ).find( '.toggle .content' ).addClass( 'active' );
	}
};