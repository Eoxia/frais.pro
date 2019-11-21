/**
 * Initialise l'objet note de frais ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.payment = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.payment.init = function() {
	jQuery( document ).on( 'click', '.group-date .form-field-label-next', window.eoxiaJS.fraisPro.payment.getCurrentDate );
	jQuery( document ).on( 'click', '.amount .form-field-label-next', window.eoxiaJS.fraisPro.payment.getAmount );

};
window.eoxiaJS.fraisPro.payment.getCurrentDate = function( event ) {
	var date = new Date();
	event.preventDefault();

	jQuery( this ).closest( '.group-date' ).find( '.mysql-date' ).val( date.toISOString().slice(0, 19).replace('T', ' ') );
	jQuery( this ).closest( '.group-date' ).find( '.date' ).val( date.toLocaleDateString() );
};

window.eoxiaJS.fraisPro.payment.getAmount = function( event ) {
	jQuery( this ).closest( 'div' ).find( 'input' ).val( jQuery( this ).data( 'amount' ) );
};

window.eoxiaJS.fraisPro.payment.savedPayment = function( triggeredElement, response ) {
	triggeredElement.closest( '.wpeo-modal' ).find( '.modal-content' ).html( response.data.view );
	triggeredElement.closest( '.modal-footer' ).html( response.data.buttons_view );
	jQuery( '.wrap-frais-pro' ).html( response.data.note_view );
};
