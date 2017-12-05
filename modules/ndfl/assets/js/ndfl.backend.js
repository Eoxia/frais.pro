/**
 * Initialise l'objet "NDF" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.2.0
 */

window.eoxiaJS.noteDeFrais.NDFL = {};

/**
 * Keep the button in memory.
 *
 * @type {Object}
 */
 window.eoxiaJS.noteDeFrais.NDFL.currentButton;

/**
 * Keep the media frame in memory.
 * @type {Object}
 */
 window.eoxiaJS.noteDeFrais.NDFL.mediaFrame;

/**
* Keep the media frame in memory.
* @type {Object}
*/
 window.eoxiaJS.noteDeFrais.NDFL.focusedElement;

/**
 * Keep the selected media in memory.
 * @type {Object}
 */
 window.eoxiaJS.noteDeFrais.NDFL.selectedInfos = [];

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

	jQuery( document ).on( 'click', '.row li span[contenteditable=true]:not(.date)', window.eoxiaJS.noteDeFrais.NDFL.updateHiddenInput );
	jQuery( document ).on( 'blur', '.row li span[contenteditable=true]:not(.date)', function( event ) {
		if ( window.eoxiaJS.noteDeFrais.NDFL.focusedElement ) {
			window.eoxiaJS.noteDeFrais.NDFL.focusedElement = undefined;
		}
	} );

	jQuery( document ).on( 'click', '.row.add .action .icon', window.eoxiaJS.noteDeFrais.NDFL.saveNDF );

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
	jQuery( document ).on( 'click', '.eox-note-frais .fraispro-mass-line-creation', window.eoxiaJS.noteDeFrais.NDFL.openMedia );
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
	if ( ! window.eoxiaJS.noteDeFrais.NDFL.focusedElement ) {
		document.execCommand( 'selectAll', false, null );
	}

	window.eoxiaJS.noteDeFrais.NDFL.focusedElement = jQuery( this );
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
	window.eoxiaJS.loader.display( jQuery( this ).closest( '.row' ) );
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

window.eoxiaJS.noteDeFrais.NDFL.openMedia = function( event ) {
	window.eoxiaJS.noteDeFrais.NDFL.currentButton = jQuery( this );
	event.preventDefault();

	window.eoxiaJS.noteDeFrais.NDFL.mediaFrame = new window.wp.media.view.MediaFrame.Post({}).open();
	window.eoxiaJS.noteDeFrais.NDFL.mediaFrame.on( 'insert', function() {
		window.eoxiaJS.noteDeFrais.NDFL.selectedFile();
	} );
};

window.eoxiaJS.noteDeFrais.NDFL.selectedFile = function( element ) {
	var data = {
		action: 'fraispro_create_line_from_picture',
		_wpnonce: window.eoxiaJS.noteDeFrais.NDFL.currentButton.attr( 'data-nonce' ),
		files_id: window.eoxiaJS.noteDeFrais.NDFL.selectedInfos,
		ndf_id: window.eoxiaJS.noteDeFrais.NDFL.currentButton.attr( 'data-ndf-id' ),
		display_mode: window.eoxiaJS.noteDeFrais.NDFL.currentButton.closest( '.note' ).find( 'input[name=display_mode]' ).val()
	};

	window.eoxiaJS.noteDeFrais.NDFL.mediaFrame.state().get( 'selection' ).map( function( attachment ) {
		window.eoxiaJS.noteDeFrais.NDFL.selectedInfos.push( attachment.id );
	} );

	jQuery( '.single-note' ).find( '.date_modified_value' ).addClass( 'loading' );
	window.eoxiaJS.noteDeFrais.NDFL.currentButton.addClass( 'loading' );
	jQuery.post( window.ajaxurl, data, function( response ) {
		window.eoxiaJS.noteDeFrais.NDFL.currentButton.removeClass( 'loading' );

		window.eoxiaJS.noteDeFrais.NDFL.currentButton = undefined;
		window.eoxiaJS.noteDeFrais.NDFL.selectedInfos = [];
		window.eoxiaJS.noteDeFrais.NDFL.mediaFrame = undefined;

		window.eoxiaJS.noteDeFrais.NDF.refresh( null, response );
	}, 'json' );
};
