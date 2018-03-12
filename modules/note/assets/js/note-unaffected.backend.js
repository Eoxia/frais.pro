/**
 * Initialise l'objet note de frais ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.noteUnaffected = {};


/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.noteUnaffected.init = function() {
	jQuery( document ).on( 'click', '.list-line .line input[type="checkbox"]', window.eoxiaJS.fraisPro.noteUnaffected.checkLine );
	jQuery( document ).on( 'click', '.bloc-reassign .wpeo-button', window.eoxiaJS.fraisPro.noteUnaffected.reassignLineUnaffected );
};

window.eoxiaJS.fraisPro.noteUnaffected.checkLine = function( event ) {
	var lineNb = 0;
	jQuery( this ).closest( '.list-line' ).find( 'input[type="checkbox"]' ).each( function() {
		if ( jQuery( this ).is( ':checked' ) ) {
			lineNb++;
		}
	});
	jQuery( 'span.fp_lines_to_reassign' ).html( lineNb );

	window.eoxiaJS.fraisPro.noteUnaffected.buttonState();
};

/**
 * Récupères toutes les données nécesseraires envoyé d'envoyer la requête XHR pour réassigner les lignes désaffectées.
 *
 * @since 1.4.0
 * @version 1.4.0
 *
 * @param  {ClickEvent} event L'état du clic.
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.noteUnaffected.reassignLineUnaffected = function( event ) {
	var data = {};
	var linesToReassignId = [];

	if ( jQuery( 'div.single-note' ).find( '.wpeo-notification' )[0].fraisProTimeOut ) {
		clearTimeout( jQuery( 'div.single-note' ).find( '.wpeo-notification' )[0].fraisProTimeOut );
	}

	jQuery( this ).closest( 'div.single-note' ).find( '.wpeo-notification' ).addClass( 'notification-active' );
	jQuery( this ).closest( 'div.single-note' ).find( '.wpeo-notification .notification-title' ).html( fraisPro.updateInProgress );

	data.action           = jQuery( this ).closest( '.bloc-reassign' ).find( 'input[name="action"]' ).val();
	data._wpnonce         = jQuery( this ).closest( '.bloc-reassign' ).find( 'input[name="_wpnonce"]' ).val();
	data._wp_http_referer = jQuery( this ).closest( '.bloc-reassign' ).find( 'input[name="_wp_http_referer"]' ).val();
	data.parent_id        = jQuery( 'input[name="selected_note_id"]' ).val();

	jQuery( '.list-line input[type="checkbox"]:checked' ).each( function( key, element ) {
		linesToReassignId.push( jQuery( this ).val() );
	} );

	data.lines_id        = linesToReassignId;
	data.current_note_id = jQuery( '.single-note input[name="id"]' ).val();

	window.eoxiaJS.loader.display( jQuery( this ) );
	window.eoxiaJS.request.send( jQuery( this ), data );
};

/**
 * Le callback en cas de réussite à la requête Ajax "reassign_lines".
 *
 * @param  {HTMLDivElement} triggeredElement  L'élement HTML déclenchant la requête Ajax.
 * @param  {Object}         response          Les données renvoyées par la requête Ajax.
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.noteUnaffected.reassignedLineUnaffectedSuccess = function( element, response ) {
	window.eoxiaJS.loader.remove( element );

	element.addClass( 'button-disable' );

	jQuery( '.bloc-reassign .autocomplete-icon-after' ).click();
	jQuery( 'span.fp_lines_to_reassign' ).html( 0 );

	for ( var key in response.data.updated_lines_id ) {
		jQuery( '.list-line .line[data-id="' + response.data.updated_lines_id[key] + '"]' ).remove();
	}

	jQuery( 'div.single-note' ).find( '.wpeo-notification .notification-title' ).html( fraisPro.lineAffectedSuccessfully );
	jQuery( 'div.single-note' ).find( '.wpeo-notification' )[0].fraisProTimeOut = setTimeout( function() {
		jQuery( 'div.single-note' ).find( '.wpeo-notification' ).removeClass( 'notification-active' );
		jQuery( 'div.single-note' ).find( '.wpeo-notification .notification-title' ).html( '' );
	}, 3000 );

	window.eoxiaJS.fraisPro.note.checkGotLine( jQuery( 'div.single-note' ) );
};

/**
 * Permet de définir l'état du bouton d'assignation des lignes a une note.
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.noteUnaffected.buttonState = function() {
	var haveCheckedLine = jQuery( '.list-line input[type="checkbox"]:checked' ).length > 0 ? true : false;
	var selectedNote = jQuery( 'input[name=selected_note_id]' ).val();
	var associationButton = jQuery( '.bloc-reassign' ).find( '.wpeo-button' );

	if ( haveCheckedLine && selectedNote.length ) {
		associationButton.removeClass( 'button-disable' );
	} else {
		associationButton.addClass( 'button-disable' );
	}
};
