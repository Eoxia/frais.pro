/**
 * Initialise l'objet "Line" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.line = {};

/**
 * Initialise l'objet.
 *
 * @since 1.4.0
 * @version 1.4.0
 */
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
	jQuery( document ).on( 'focus', '.list-line .form-element:not(.form-element-disable) input[type=text]', window.eoxiaJS.fraisPro.line.selectInputValue );
	jQuery( document ).on( 'keyup', '.list-line .form-element:not(.form-element-disable) input[type=text]', window.eoxiaJS.fraisPro.line.checkInputStatus );
	jQuery( document ).on( 'blur', '.list-line .form-element:not(.form-element-disable) input[type=text]', window.eoxiaJS.fraisPro.line.save );
};

/**
 * Sélection automatique de la valeur du champs sélectionnée.
 *
 * @param  {[type]} element  [description]
 * @param  {[type]} response [description]
 *
 * @return {void}          [description]
 */
window.eoxiaJS.fraisPro.line.selectInputValue = function( element, response ) {
	jQuery( this ).select();
};

/**
 * Enregistrement d'une ligne.
 *
 * @param  {[type]} element  [description]
 * @param  {[type]} response [description]
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.line.save = function( event, element ) {
	// Compile mathemetic operator.
	if ( jQuery( this ).closest( '.form-element' ).hasClass( 'ttc' ) ) {
		var content = math.evaluate(jQuery( this ).val());
		jQuery( this ).val(content);
	}

	if ( jQuery( this ).closest( '.form-element' ).hasClass( 'tva' ) ) {
		var content = math.evaluate(jQuery( this ).val());
		jQuery( this ).val(content);
	}


	element = element ? element : jQuery( this );
	var parentElement = element.closest( 'div.line-content' );
	var listInput = window.eoxiaJS.arrayForm.getInput( parentElement );
	var data = {
		'action': 'fp_update_line',
		'_wpnonce': parentElement.closest( 'div.list-line' ).data( 'nonce' ),
		'id': parentElement.closest( 'div.line' ).data( 'id' ),
		'parent_id': parentElement.closest( 'div.single-note' ).data( 'id' )
	};

	if ( jQuery( 'div.single-note' ).find( '.wpeo-notification' )[0].fraisProTimeOut ) {
		clearTimeout( jQuery( 'div.single-note' ).find( '.wpeo-notification' )[0].fraisProTimeOut );
	}
	parentElement.closest( 'div.single-note' ).find( '.wpeo-notification' ).addClass( 'notification-active' );
	parentElement.closest( 'div.single-note' ).find( '.wpeo-notification .notification-title' ).html( fraisPro.updateInProgress );
	parentElement.closest( 'div.single-note' ).find( '.note-last-update' ).html( fraisPro.updateInProgress );

	for ( i = 0; i < listInput.length; i++ ) {
		if ( listInput[i].name ) {
			data[listInput[i].name] = window.eoxiaJS.arrayForm.getInputValue( listInput[i] );
		}
	}

	window.eoxiaJS.request.send( jQuery( this ), data );
};

/**
 * After line is saved. Do some action.
 *
 * @param  {[type]} element  [description]
 * @param  {[type]} response [description]
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.line.lineSaved = function( element, response ) {
	jQuery( '.note-recap .note-ttc span.value' ).html( response.data.note.data.tax_inclusive_amount );
	jQuery( '.note-recap .note-tva span.value' ).html( response.data.note.data.tax_amount );
	if ( element.closest( '.form-element' ).hasClass( 'km' ) ) {
		jQuery( 'div[data-id=' + response.data.line.data.id + '] input[name=tax_inclusive_amount]' ).val( response.data.line.data.tax_inclusive_amount );
		jQuery( 'div[data-id=' + response.data.line.data.id + '] input[tax_amount]' ).val( response.data.line.data.tax_amount );
	}
	jQuery( '.title .note-last-update' ).html( response.data.note_last_update );

	jQuery( 'div.single-note' ).find( '.wpeo-notification .notification-title' ).html( fraisPro.updateDone );
	jQuery( 'div.single-note' ).find( '.wpeo-notification' )[0].fraisProTimeOut = setTimeout( function() {
		jQuery( 'div.single-note' ).find( '.wpeo-notification' ).removeClass( 'notification-active' );
		jQuery( 'div.single-note' ).find( '.wpeo-notification .notification-title' ).html( '' );
	}, 3000 );
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
	if ( 1 === jQuery( 'div.list-line .table-row.line.notice-info' ).length ) {
		jQuery( 'div.list-line .table-row.line.notice-info' ).hide();
	}

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
	jQuery( element ).closest( '.line' ).fadeOut( function() {
		window.eoxiaJS.fraisPro.note.checkGotLine( jQuery( element ).closest( 'div.single-note' ) );
		jQuery( '.single-note[data-id=' + response.data.id + '] .note-ttc .value' ).text( response.data.tax_inclusive_amount );
		jQuery( '.single-note[data-id=' + response.data.id + '] .note-tva .value' ).text( response.data.tax_amount );
	} );
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
	var line = jQuery( this ).closest( 'div.line-content' );
	var lineAction = line.children( 'div.status' );
	var pinStatus = lineAction.find( 'span.pin' );

	window.eoxiaJS.fraisPro.line.setStatusField( jQuery( this ).closest( 'div.form-element' ), true );

	if ( ! jQuery( '.form-element.empty input[name="tax_inclusive_amount"]' ).length && ! jQuery( '.form-element.empty input[name="tax_amount"]' ).length ) {
		if (parseFloat(jQuery('input[name="tax_inclusive_amount"]').val()) < parseFloat(jQuery('input[name="tax_amount"]').val())) {
			jQuery('input[name="tax_inclusive_amount"]').closest('.form-element').addClass('input-error');
			jQuery('input[name="tax_amount"]').closest('.form-element').addClass('input-error');
		} else {
			jQuery('input[name="tax_inclusive_amount"]').closest('.form-element').removeClass('input-error');
			jQuery('input[name="tax_amount"]').closest('.form-element').removeClass('input-error');
		}
	}

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
 * @param  {boolean} action           Action a effectuer: ajouter/supprimer.
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.line.setStatusField = function( element, action ) {
	var input = element.find( 'input' ).val();
	var isRequired = false;
	if ( element.hasClass( 'input-is-required' ) ) {
		isRequired = true;
	}

	if ( isRequired && action && ( ( '' === input ) || ( 0 == input ) ) ) {
		element.addClass( 'input-error empty' );
	} else {
		element.removeClass( 'input-error empty' );
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
	if ( ! enabled ) {
		element.find( '.' + nameField ).removeClass( 'form-element-disable' );
	} else {
		element.find( '.' + nameField ).addClass( 'form-element-disable' );
	}
	element.find( '.' + nameField + ' input[type=text]' ).attr( 'readonly', enabled );
};

/**
 * Rafraichit le bouton permettant d'associer une image a une ligne.
 *
 * @since 1.2.0
 * @version 1.4.0
 *
 * @param  {HTMLULListElement} args
 *
 * @return {void}
 */
window.eoxiaJS.fraisPro.line.eoUploadAssociatedFile = function( args ) {
	if ( window.eoxiaJS.upload.currentButton.hasClass( 'media-grid' ) ) {
		window.eoxiaJS.upload.currentButton = args.element.closest( '.line' ).find( '.media.media-list' );
	} else {
		window.eoxiaJS.upload.currentButton = args.element.closest( '.line' ).find( '.media.media-grid' );
	}
	window.eoxiaJS.upload.refreshButton( args.response.data );
};
