/**
 * Initialise l'objet note de frais ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.note = {};

/**
 * Keep the button in memory.
 *
 * @type {Object}
 */
 window.eoxiaJS.fraisPro.note.currentButton;

/**
 * Keep the media frame in memory.
 * @type {Object}
 */
 window.eoxiaJS.fraisPro.note.mediaFrame;

/**
* Keep the media frame in memory.
* @type {Object}
*/
 window.eoxiaJS.fraisPro.note.focusedElement;

/**
 * Keep the selected media in memory.
 * @type {Object}
 */
 window.eoxiaJS.fraisPro.note.selectedInfos = [];

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.note.init = function() {
	jQuery( document ).on( 'click', '.list-note .note', window.eoxiaJS.fraisPro.note.goToLink );
	jQuery( document ).on( 'click', '.display-method span.wpeo-button', window.eoxiaJS.fraisPro.note.changeDisplayMode );
	jQuery( document ).on( 'click', '.wrap-frais-pro .fraispro-mass-line-creation', window.eoxiaJS.fraisPro.note.openMedia );
};

/**
 * Redirige l'utilisateur sur la note
 *
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.note.goToLink = function( event ) {
	window.location.href = jQuery( this ).data( 'link' );
};

/**
 * Redirige l'utilisateur sur la note
 *
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.note.goToNote = function( element, response ) {
	window.location.href = response.data.link;
};

/**
 * Redirige l'utilisateur sur la note
 *
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.note.changeDisplayMode = function( event ) {
	event.preventDefault();
	jQuery( this ).closest( 'div.single-note' ).toggleClass( 'grid list' );
	jQuery( this ).closest( 'div.display-method' ).children( 'span' ).toggleClass( 'active' );
};

/**
 * [description]
 * @param  {[type]} event [description]
 * @return {[type]}       [description]
 */
window.eoxiaJS.fraisPro.note.openMedia = function( event ) {
	window.eoxiaJS.fraisPro.note.currentButton = jQuery( this );
	event.preventDefault();

	window.eoxiaJS.fraisPro.note.mediaFrame = new window.wp.media.view.MediaFrame.Post({}).open();
	window.eoxiaJS.fraisPro.note.mediaFrame.on( 'insert', function() {
		window.eoxiaJS.fraisPro.note.selectedFile();
	} );
};
/**
 * [description]
 * @param  {[type]} element [description]
 * @return {[type]}         [description]
 */
window.eoxiaJS.fraisPro.note.selectedFile = function( element ) {
	var data = {
		action: 'fp_create_line_from_picture',
		_wpnonce: window.eoxiaJS.fraisPro.note.currentButton.attr( 'data-nonce' ),
		files_id: window.eoxiaJS.fraisPro.note.selectedInfos,
		note_id: window.eoxiaJS.fraisPro.note.currentButton.attr( 'data-parent-id' )
	};

	window.eoxiaJS.fraisPro.note.mediaFrame.state().get( 'selection' ).map( function( attachment ) {
		window.eoxiaJS.fraisPro.note.selectedInfos.push( attachment.id );
	} );

	jQuery( '.single-note' ).find( '.date_modified_value' ).addClass( 'loading' );
	window.eoxiaJS.fraisPro.note.currentButton.addClass( 'loading' );
	jQuery.post( window.ajaxurl, data, function( response ) {
		window.eoxiaJS.fraisPro.note.currentButton.removeClass( 'loading' );
		window.eoxiaJS.fraisPro.note.currentButton = undefined;
		window.eoxiaJS.fraisPro.note.selectedInfos = [];
		window.eoxiaJS.fraisPro.note.mediaFrame = undefined;
		if ( response.success ) {
			jQuery( 'div.list-line' ).prepend( response.data.view );
		}
	}, 'json' );
};

/**
 * Delete the archived note from display.
 *
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.note.note_is_marked_as_archive = function( element, response ) {

	// Check if the user is on list or in a single note
	if ( 1 === jQuery( '.list-note' ).length ) {
		jQuery( 'tr.note[data-id=' + response.data.note.id + ']' ).fadeOut();
	} else {
		window.location.href = response.data.link;
	}
};
