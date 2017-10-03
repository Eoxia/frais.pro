/**
 * Initialise l'objet "NDF" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.2.0
 */

window.eoxiaJS.noteDeFrais.NDFL = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.0.0
 * @version 1.2.0
 */
window.eoxiaJS.noteDeFrais.NDFL.init = function() {
	var currentFocusout = true;

	jQuery( document ).on( 'blur keyup paste keydown click', '.row.add li span[contenteditable]', window.eoxiaJS.noteDeFrais.NDFL.updateHiddenInput );

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
	jQuery( document ).on( 'keydown', '.libelle span', window.eoxiaJS.noteDeFrais.NDFL.focusSelect );
};

// Quand on change de date dans le calendrier.
window.eoxiaJS.noteDeFrais.NDFL.changeDate = function( element ) {
	// On affiche date sélectionnée dans le "champs" contenteditable.
	element.closest( '.group-date' ).find( 'span[contenteditable="true"]' ).text( window.eoxiaJS.date.convertMySQLDate( element.val(), false ) );

	// Si on est dans le cadre d'une édition de ligne alors on lance l'enregistrement
	if ( ! element.closest( 'ul.row' ).hasClass( 'add' ) ) {
		element.each( window.eoxiaJS.noteDeFrais.NDFL.saveNDF );
	}
};

window.eoxiaJS.noteDeFrais.NDFL.updateHiddenInput = function( event ) {
	if ( 0 >= jQuery( this ).text().length ) {
		jQuery( this ).closest( 'li' ).find( '.ndfl-placeholder' ).addClass( 'hidden' );
	}
};

window.eoxiaJS.noteDeFrais.NDFL.beforeDisplayModeChange = function( element ) {
	var displayTypeToActivate = jQuery( element ).attr( 'data-display-mode' );
	var mainContainer = jQuery( element ).closest( 'div.container' ).find( '.flex-table' );

	if ( ! mainContainer.hasClass( displayTypeToActivate ) ) {
		return true;
	} else {
		return false;
	}
};

window.eoxiaJS.noteDeFrais.NDFL.confirmDeletion = function( element ) {
	return confirm( jQuery( element ).data().confirmText );
};

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

window.eoxiaJS.noteDeFrais.NDFL.focusSelect = function( event ) {
	var code = ( event.keyCode ? event.keyCode : event.which );
	if ( 9 == code ) {
		event.preventDefault();
		jQuery( this ).blur();
		jQuery( this ).closest( '.row' ).find( '.toggle .content' ).addClass( 'active' );
	}
};
