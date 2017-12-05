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
	jQuery( document ).on( 'click', '.single-note .note .close', window.eoxiaJS.noteDeFrais.NDF.close );
};

window.eoxiaJS.noteDeFrais.NDF.refresh = function( triggeredElement, response ) {
	if ( ! response.data.no_refresh || ( false === response.data.no_refresh ) ) {
		jQuery( '.single-note' ).html( response.data.view );
	}
	if ( response.data.ndf ) {
		jQuery( '.note[data-id="' + response.data.ndf.id + '"] .ttc .value' ).text( response.data.ndf.tax_inclusive_amount );
		jQuery( '.note[data-id="' + response.data.ndf.id + '"] .tva .value' ).text( response.data.ndf.tax_amount );
		jQuery( '.note[data-id="' + response.data.ndf.id + '"] .update .value' ).text( response.data.ndf.date_modified.date_human_readable );
		jQuery( '.note[data-id="' + response.data.ndf.id + '"] .status .value' ).html( response.data.ndf.validation_status );
	}
};

window.eoxiaJS.noteDeFrais.NDF.open = function( triggeredElement, response ) {
	if ( response.data.main_view ) {
		jQuery( '.eox-note-frais' ).replaceWith( response.data.main_view );
	}
	jQuery( '.eox-note-frais' ).addClass( 'active-single' );
	window.eoxiaJS.noteDeFrais.NDF.refresh( triggeredElement, response );
};

window.eoxiaJS.noteDeFrais.NDF.close = function( event ) {
	jQuery( '.eox-note-frais' ).removeClass( 'active-single' );
};

window.eoxiaJS.noteDeFrais.NDF.saveStatus = function( event ) {
	if ( ( 'paye' === jQuery( this ).closest( 'li' ).data( 'type' ) ) && ! confirm( noteDeFrais.confirmMarkAsPayed ) ) {
		return false;
	}
	var toggle = jQuery( this ).closest( '.validation_status.toggle' );
	var type = jQuery( this ).closest( 'li' ).data( 'type' );
	var serialize = '';
	event.stopPropagation();
	toggle.find( '.label' ).html( jQuery( this ).text() );
	toggle.find( 'input[name="validation_status"]' ).val( jQuery( this ).text() );
	toggle.find( '.action .label' )[0].className = 'label pin-status ' + jQuery( this ).closest( 'li' ).data( 'type' );
	toggle.find( '.content' ).removeClass( 'active' );
	serialize = toggle.find( 'input' ).serialize();
	jQuery( '.single-note' ).find( '.date_modified_value' ).addClass( 'loading' );
	jQuery.post( ajaxurl, serialize, function( response ) {
		jQuery( '.note[data-id="' + response.data.ndf.id + '"] .status span.value' )[0].className = 'value pin-status ' + type;
		window.eoxiaJS.noteDeFrais.NDF.refresh( null, response );
	}, 'json' );
};

window.eoxiaJS.noteDeFrais.NDF.exportedNoteDeFraisSuccess = function( triggeredElement, response ) {
	window.eoxiaJS.global.downloadFile( response.data.link, response.data.filename );
};
window.eoxiaJS.noteDeFrais.NDF.archived = function( triggeredElement, response ) {
	jQuery( triggeredElement ).closest( '.note' ).fadeOut();
};
