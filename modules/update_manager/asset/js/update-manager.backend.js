/**
 * Initialise l'objet "updateManager" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.4.0
 * @version 1.4.0
 */

window.eoxiaJS.fraisPro.updateManager = {};

/**
 * La méthode appelée automatiquement par la bibliothèque EoxiaJS.
 *
 * @return {void}
 *
 * @since 1.4.0
 * @version 1.4.0
 */
window.eoxiaJS.fraisPro.updateManager.init = function() {
	window.eoxiaJS.fraisPro.updateManager.requestUpdate();
	window.addEventListener( 'beforeunload', window.eoxiaJS.fraisPro.updateManager.safeExit );
};

window.eoxiaJS.fraisPro.updateManager.requestUpdateFunc = {
	endMethod: []
};
window.eoxiaJS.fraisPro.updateManager.requestUpdate = function( args ) {
	var key             = jQuery( 'input.current-key' ).val();
	var versionToUpdate = jQuery( 'input[name="version_available[]"]:first' ).val();
	var action          = jQuery( 'input[name="version[' + versionToUpdate + '][action][]"]:first' ).val();
	var description     = jQuery( 'input[name="version[' + versionToUpdate + '][description][]"]:first' ).val();
	var data = {
		action: action,
		versionToUpdate: versionToUpdate,
		args: args
	};

	if ( versionToUpdate ) {
		if ( ( args && ! args.more ) || ! args ) {
			jQuery( '.log' ).append( '<li><h2>Update <strong>' + versionToUpdate + '</strong> in progress...</h2></li>' );
		}

		if ( action ) {
			if ( args && args.moreDescription ) {
				description += args.moreDescription;
			}

			jQuery( '.log' ).append( '<li>' + description + fraisPro.loader + '</li>' );

			jQuery.post( ajaxurl, data, function( response ) {
				jQuery( '.log img' ).remove();

				if ( response.data.done ) {
					if ( response.data.args && response.data.args.doneDescription ) {
						jQuery( '.log' ).append( '<li>' + response.data.args.doneDescription + '</li>' );
						delete response.data.args.doneDescription;
					}

					jQuery( 'input[name="version[' + versionToUpdate + '][action][]"]:first' ).remove();
					jQuery( 'input[name="version[' + versionToUpdate + '][description][]"]:first' ).remove();

					if ( 0 == jQuery( 'input[name="version[' + versionToUpdate + '][action][]"]:first' ).length ) {
						delete response.data.args;

						jQuery( 'input[name="version_available[]"]:first' ).remove();
					}
					if ( 0 == jQuery( 'input[name="version_available[]"]:first' ).length ) {
						delete response.data.args;

						jQuery.post( ajaxurl, { action: 'tm_redirect_to_dashboard', key: key }, function( response ) {
							jQuery( '.log' ).append( '<li>' + response.data.message + '</li>' );
							window.removeEventListener( 'beforeunload', window.eoxiaJS.fraisPro.updateManager.safeExit );
							window.location = response.data.url;
						});
					} else {

						if ( response.data.args && response.data.args.resetArgs ) {
							delete response.data.args;
						}
						window.eoxiaJS.fraisPro.updateManager.requestUpdate( response.data.args );
					}
				} else {
					window.eoxiaJS.fraisPro.updateManager.requestUpdate( response.data.args );
				}
			} )
			.fail( function( error, t, r ) {
				// @todo Gérer ce cas dans une action personnalisée.
				jQuery( '.log' ).append( '<li>Erreur: veuillez consulter les logs de la version: ' + versionToUpdate + '</li>' );
				jQuery.post( ajaxurl, { action: 'tm_redirect_to_dashboard', key: key, error_version: versionToUpdate, error_status: error.status, error_text: error.responseText }, function( response ) {
					window.removeEventListener( 'beforeunload', window.eoxiaJS.fraisPro.updateManager.safeExit );
					window.location = response.data.url;
				});
			} );
		}
	}

	if ( jQuery( '.no-update' ).length ) {
		jQuery.post( ajaxurl, { action: 'tm_redirect_to_dashboard', key: key }, function( response ) {
			jQuery( '.log' ).append( '<li>' + response.data.message + '</li>' );
			window.removeEventListener( 'beforeunload', window.eoxiaJS.fraisPro.updateManager.safeExit );
			window.location = response.data.url;
		});
	}
};

/**
 * Vérification avant la fermeture de la page si la mise à jour est terminée.
 *
 * @since 1.4.0
 * @version 1.4.0
 *
 * @param  {WindowEventHandlers} event L'évènement de la fenêtre.
 * @return {string}
 */
window.eoxiaJS.fraisPro.updateManager.safeExit = function( event ) {
	if ( fraisPro.updateDataUrlPage === event.currentTarget.adminpage ) {
		var confirmationMessage = fraisPro.confirmUpdateManagerExit;
		event.returnValue = confirmationMessage;
		return confirmationMessage;
	}
};
