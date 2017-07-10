/**
 * Initialise l'objet note de frais ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
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
	jQuery( document ).on( 'click', '.validation_status.toggle .content .item', window.eoxiaJS.noteDeFrais.NDF.saveStatus );
};

window.eoxiaJS.noteDeFrais.NDF.saveStatus = function( event ) {
	var toggle = jQuery( this ).closest( '.validation_status.toggle' );
	var type = jQuery( this ).closest( 'li' ).data( 'type' );
	var serialize = '';
	event.stopPropagation();
	toggle.find( '.label' ).html( jQuery( this ).text() );
	toggle.find( 'input[name="validation_status"]' ).val( jQuery( this ).text() );
	toggle.find( '.action .label' )[0].className = 'label pin-status ' + jQuery( this ).closest( 'li' ).data( 'type' );
	toggle.find( '.content' ).removeClass( 'active' );
	serialize = toggle.find( 'input' ).serialize();
	jQuery.post( ajaxurl, serialize, function( response ) {
		jQuery( '.note[data-id="' + response.data.ndf.id + '"] .status span.value' )[0].className = 'value pin-status ' + type;
		window.eoxiaJS.noteDeFrais.NDF.refreshNDF( null, response );
	}, 'json' );
};

window.eoxiaJS.noteDeFrais.NDF.exportedNoteDeFraisSuccess = function( triggeredElement, response ) {
	window.eoxiaJS.global.downloadFile( response.data.link, response.data.filename );
};
window.eoxiaJS.noteDeFrais.NDF.archived = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.note' ).fadeOut();
};
