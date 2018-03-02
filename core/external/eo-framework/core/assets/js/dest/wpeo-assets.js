'use strict';

if ( ! window.eoxiaJS ) {
	window.eoxiaJS = {};
	window.eoxiaJS.scriptsLoaded = false;
}

if ( ! window.eoxiaJS.scriptsLoaded ) {
	window.eoxiaJS.init = function() {
		window.eoxiaJS.load_list_script();
		window.eoxiaJS.init_array_form();
	};

	window.eoxiaJS.load_list_script = function() {
		if ( ! window.eoxiaJS.scriptsLoaded ) {
			var key = undefined, slug = undefined;
			for ( key in window.eoxiaJS ) {

				if ( window.eoxiaJS[key].init ) {
					window.eoxiaJS[key].init();
				}

				for ( slug in window.eoxiaJS[key] ) {

					if ( window.eoxiaJS[key] && window.eoxiaJS[key][slug] && window.eoxiaJS[key][slug].init ) {
						window.eoxiaJS[key][slug].init();
					}

				}
			}

			window.eoxiaJS.scriptsLoaded = true;
		}
	};

	window.eoxiaJS.init_array_form = function() {
		 window.eoxiaJS.arrayForm.init();
	};

	window.eoxiaJS.refresh = function() {
		var key = undefined;
		var slug = undefined;
		for ( key in window.eoxiaJS ) {
			if ( window.eoxiaJS[key].refresh ) {
				window.eoxiaJS[key].refresh();
			}

			for ( slug in window.eoxiaJS[key] ) {

				if ( window.eoxiaJS[key] && window.eoxiaJS[key][slug] && window.eoxiaJS[key][slug].refresh ) {
					window.eoxiaJS[key][slug].refresh();
				}
			}
		}
	};

	window.eoxiaJS.cb = function( cbName, cbArgs ) {
		var key = undefined;
		var slug = undefined;
		for ( key in window.eoxiaJS ) {

			for ( slug in window.eoxiaJS[key] ) {

				if ( window.eoxiaJS[key] && window.eoxiaJS[key][slug] && window.eoxiaJS[key][slug][cbName] ) {
					window.eoxiaJS[key][slug][cbName](cbArgs);
				}
			}
		}
	};

	jQuery( document ).ready( window.eoxiaJS.init );
}

/**
 * Gestion des actions XHR principaux
 *
 * -action-input:     Déclenches une requête XHR avec les balises inputs contenu dans le contenaire parent.
 * -action-attribute: Déclenches une requête XHR avec les attributs de l'élément déclencheur.
 * -action-delete:    Déclenches une requête XHR avec les attributs de l'élément déclencheur si l'utilisateur confirme la popin "confirm" du navigateur.
 *
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! window.eoxiaJS.action ) {
	/**
	 * Declare the object action.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @type {Object}
	 */
	window.eoxiaJS.action = {};

	/**
	 * This method call the event method
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return {void}
	 */
	window.eoxiaJS.action.init = function() {
		window.eoxiaJS.action.event();
	};

	/**
	 * This method initialize the click event on three classes.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @return {void}
	 */
	window.eoxiaJS.action.event = function() {
		jQuery( document ).on( 'click', '.action-input:not(.no-action)', window.eoxiaJS.action.execInput );
		jQuery( document ).on( 'click', '.action-attribute:not(.no-action)', window.eoxiaJS.action.execAttribute );
		jQuery( document ).on( 'click', '.action-delete:not(.no-action)', window.eoxiaJS.action.execDelete );
	};

	/**
	 * Make a request with input value founded inside the parent of the HTML element clicked.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param  {MouseEvent} event Properties of element triggered by the MouseEvent.
	 *
	 * @return {void}
	 */
	window.eoxiaJS.action.execInput = function( event ) {
		var element = jQuery( this ), parentElement = element, listInput = undefined, data = {}, i = 0, doAction = true, key = undefined, inputAlreadyIn = [];
		event.preventDefault();

		if ( element.attr( 'data-parent' ) ) {
			parentElement = element.closest( '.' + element.attr( 'data-parent' ) );
		}

		/** Méthode appelée avant l'action */
		if ( element.attr( 'data-module' ) && element.attr( 'data-before-method' ) ) {
			doAction = false;
			doAction = window.eoxiaJS[element.attr( 'data-namespace' )][element.attr( 'data-module' )][element.attr( 'data-before-method' )]( element );
		} else {
			if ( ! doAction ) {
				doAction = window.eoxiaJS.action.checkBeforeCB(element);
			}
		}

		if ( doAction ) {
			window.eoxiaJS.loader.display( element );

			listInput = window.eoxiaJS.arrayForm.getInput( parentElement );
			for ( i = 0; i < listInput.length; i++ ) {
				if ( listInput[i].name && -1 === inputAlreadyIn.indexOf( listInput[i].name ) ) {
					inputAlreadyIn.push( listInput[i].name );
					data[listInput[i].name] = window.eoxiaJS.arrayForm.getInputValue( listInput[i] );
				}
			}

			element.get_data( function( attrData ) {
				for ( key in attrData ) {
					data[key] = attrData[key];
				}

				window.eoxiaJS.request.send( element, data );
			} );
		}
	};

	/**
	 * Make a request with data on HTML element clicked.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param  {MouseEvent} event Properties of element triggered by the MouseEvent.
	 *
	 * @return {void}
	 */
	window.eoxiaJS.action.execAttribute = function( event ) {
	  var element = jQuery( this );
		var doAction = true;

		event.preventDefault();

		/** Méthode appelée avant l'action */
		if ( element.attr( 'data-module' ) && element.attr( 'data-before-method' ) ) {
			doAction = false;
			doAction = window.eoxiaJS[element.attr( 'data-namespace' )][element.attr( 'data-module' )][element.attr( 'data-before-method' )]( element );
		}

		if ( element.hasClass( '.grey' ) ) {
			doAction = false;
		}

		if ( doAction ) {
			if ( jQuery( this ).attr( 'data-confirm' ) ) {
				if ( window.confirm( jQuery( this ).attr( 'data-confirm' ) ) ) {
					element.get_data( function( data ) {
						window.eoxiaJS.loader.display( element );
						window.eoxiaJS.request.send( element, data );
					} );
				}
			} else {
				element.get_data( function( data ) {
					window.eoxiaJS.loader.display( element );
					window.eoxiaJS.request.send( element, data );
				} );
			}
		}

		event.stopPropagation();
	};

	/**
	 * Make a request with data on HTML element clicked with a custom delete message.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param  {MouseEvent} event Properties of element triggered by the MouseEvent.
	 *
	 * @return {void}
	 */
	window.eoxiaJS.action.execDelete = function( event ) {
		var element = jQuery( this );
		var doAction = true;

		event.preventDefault();

		/** Méthode appelée avant l'action */
		if ( element.attr( 'data-namespace' ) && element.attr( 'data-module' ) && element.attr( 'data-before-method' ) ) {
			doAction = false;
			doAction = window.eoxiaJS[element.attr( 'data-namespace' )][element.attr( 'data-module' )][element.attr( 'data-before-method' )]( element );
		}

		if ( element.hasClass( '.grey' ) ) {
			doAction = false;
		}

		if ( doAction ) {
			if ( window.confirm( element.attr( 'data-message-delete' ) ) ) {
				element.get_data( function( data ) {
					window.eoxiaJS.loader.display( element );
					window.eoxiaJS.request.send( element, data );
				} );
			}
		}
	};

	/**
	 * Si une méthode de callback existe avant l'action, cette méthode l'appel.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param  {Object} element L'élément déclencheur.
	 *
	 * @return {bool}           True si l'action peut être envoyé, sinon False.
	 */
	window.eoxiaJS.action.checkBeforeCB = function( element ) {
		var beforeMethod = element.attr( 'wpeo-before-cb' );

		if ( ! beforeMethod ) {
			return true;
		}

		beforeMethod = beforeMethod.split( '/' );

		if ( ! beforeMethod[0] || ! beforeMethod[1] || ! beforeMethod[2] ) {
			return true;
		}

		return window.eoxiaJS[beforeMethod[0]][beforeMethod[1]][beforeMethod[2]]( element );
	}
}

/**
 * Action for make request AJAX.
 *
 * @since 1.0.0-easy
 * @version 1.0.0-easy
 */

if ( ! window.eoxiaJS.arrayForm ) {
	/**
	 * Declare the object arrayForm.
	 *
	 * @since 1.0.0-easy
	 * @version 1.0.0-easy
	 * @type {Object}
	 */
	window.eoxiaJS.arrayForm = {};

	window.eoxiaJS.arrayForm.init = function() {};

	window.eoxiaJS.arrayForm.event = function() {};

	window.eoxiaJS.arrayForm.getInput = function( parent ) {
		return parent.find( 'input, textarea, select' );
	};

	window.eoxiaJS.arrayForm.getInputValue = function( input ) {
		switch ( input.getAttribute( 'type' ) ) {
			case 'checkbox':
				return input.checked;
				break;
			case 'radio':
				return jQuery( 'input[name="' + jQuery( input ).attr( 'name' ) + '"]:checked' ).val();
				break;
			default:
				return input.value;
				break;
		}
	};
}

if ( ! jQuery.fn.get_data ) {
	jQuery.fn.get_data = function( cb ) {
		this.each( function() {
			var data = {};
			var i = 0;
			var localName = undefined;

			for ( i = 0; i <  jQuery( this )[0].attributes.length; i++ ) {
				localName = jQuery( this )[0].attributes[i].localName;
				if ( 'data' === localName.substr( 0, 4 ) || 'action' === localName ) {
					localName = localName.substr( 5 );

					if ( 'nonce' === localName ) {
						localName = '_wpnonce';
					}

					localName = localName.replace( '-', '_' );
					data[localName] =  jQuery( this )[0].attributes[i].value;
				}
			}

			cb( data );
		} );
	};
}

/**
 * Gestion du dropdown.
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! window.eoxiaJS.autoComplete  ) {
	window.eoxiaJS.autoComplete = {};

	window.eoxiaJS.autoComplete.init = function() {
		window.eoxiaJS.autoComplete.event();
	};

	window.eoxiaJS.autoComplete.event = function() {
		jQuery( document ).on( 'keyup', '.wpeo-autocomplete input', window.eoxiaJS.autoComplete.keyUp );
		jQuery( document ).on( 'click', '.wpeo-autocomplete .autocomplete-icon-after', window.eoxiaJS.autoComplete.deleteContent );
		jQuery( document ).on( 'click', 'body', window.eoxiaJS.autoComplete.close );
	};

	/**
	 * Make request when keyUp.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param  {KeyboardEvent} event Status of keyboard when keyUp event.
	 *
	 * @return {void}
	 */
	window.eoxiaJS.autoComplete.keyUp = function(event) {
		var element = jQuery( this );
		var parent  = element.closest( '.wpeo-autocomplete' );
		var label   = element.closest( '.autocomplete-label' );

		// If is not a letter or a number, stop func.
		if ( ! (event.which <= 90 && event.which >= 48 ) && event.which != 8 &&  event.which <= 96 && event.which >= 105  ) {
			return;
		}

		parent.find( 'input[type="hidden"]' ).val( '' );

		// If empty searched value, stop func.
		if ( element.val().length === 0 ) {
			parent.removeClass( 'autocomplete-full' );
			return;
		} else {

			// Add this class for display the empty button.
			if ( ! parent.hasClass( 'autocomplete-full' ) ) {
				parent.addClass( 'autocomplete-full' );
			}
		}

		// If already request in queue, abort it.
		if ( parent[0].xhr ) {
			parent[0].xhr.abort();
		}

		var data = {
			action: parent.attr( 'data-action' ),
			_wpnonce: parent.attr( 'data-nonce' ),
			s: element.val(),
		};

		window.eoxiaJS.autoComplete.initProgressBar( parent, label );
		window.eoxiaJS.autoComplete.handleProgressBar( parent, label );

		parent[0].xhr = window.eoxiaJS.request.send( jQuery( this ), data, function( triggeredElement, response ) {
			window.eoxiaJS.autoComplete.clear( parent, label );

			parent.addClass( 'autocomplete-active' );
			parent.find( '.autocomplete-search-list' ).addClass( 'autocomplete-active' );

			if ( response.data && response.data.view ) {
				parent.find( '.autocomplete-search-list' ).html( response.data.view );
			}
		});
	};

	/**
	 * Delete the content and result list.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param  {[type]} event [description]
	 * @return {[type]}       [description]
	 */
	window.eoxiaJS.autoComplete.deleteContent = function( event ) {
		var element = jQuery( this );
		var parent  = element.closest( '.wpeo-autocomplete' );
		var label   = element.closest( '.autocomplete-label' );

		parent.find( 'input' ).val( '' );
		parent.find( 'input' ).trigger( 'keyUp' );

		parent.removeClass( 'autocomplete-active' );
		parent.removeClass( 'autocomplete-full' );
		parent.find( '.autocomplete-search-list' ).removeClass( 'autocomplete-active' );

		if ( parent[0].xhr ) {
			parent[0].xhr.abort();
			window.eoxiaJS.autoComplete.clear(parent, label);
		}
	};

	/**
	 * Close result list
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param  {[type]} event [description]
	 * @return {[type]}       [description]
	 */
	window.eoxiaJS.autoComplete.close = function( event ) {
		jQuery( '.wpeo-autocomplete.autocomplete-active' ).each ( function() {
			jQuery( this ).removeClass( 'autocomplete-active' );
			jQuery( this ).find( '.autocomplete-search-list' ).removeClass( 'autocomplete-active' );
		} );
	};

	/**
	 * Handle progress bar.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param {} parent
	 * @param {} label
	 *
	 * @return {void}
	 */
	window.eoxiaJS.autoComplete.initProgressBar = function( parent, label ) {
		// Init two elements for loading bar.
		if ( label.find( '.autocomplete-loading').length == 0 ) {
			var el = jQuery( '<span class="autocomplete-loading"></span>' );
			label[0].autoCompleteLoading = el;
			label.append( label[0].autoCompleteLoading );

			var elBackground = jQuery( '<span class="autocomplete-loading-background"></span>' );
			label[0].autoCompletedLoadingBackground = elBackground;
			label.append( label[0].autoCompletedLoadingBackground );
		}
	};

	/**
	 * Handle with of the progress bar.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param {} parent
	 * @param {} label
	 *
	 * @return {void}
	 */
	window.eoxiaJS.autoComplete.handleProgressBar = function( parent, label ) {
		parent.find( '.autocomplete-loading' ).css({
			width: '0%'
		});

		setTimeout(function() {
			parent.find( '.autocomplete-loading' ).css({
				width: '5%'
			});
		}, 10 );

		label[0].currentTime = 5;

		if ( ! label[0].interval ) {
			label[0].interval = setInterval( function() {
				label[0].currentTime += 3;

				if ( label[0].currentTime >= 90 ) {
					label[0].currentTime = 90;
				}

				label.find( '.autocomplete-loading' ).css({
					width: label[0].currentTime + '%',
				});
			}, 1000 );
		}
	};

	/**
	 * Clear data of the autocomplete.
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @param {} parent
	 * @param {} label
	 *
	 * @return {void}
	 */
	window.eoxiaJS.autoComplete.clear = function( parent, label ) {
		if ( label[0] ) {
			clearInterval(label[0].interval);
			label[0].interval = undefined;
		}

		if ( parent[0] ) {
			parent[0].xhr = undefined;
		}

		parent.find( '.autocomplete-loading' ).css({
			width: '100%',
		});

		setTimeout( function() {
			jQuery( label[0].autoCompleteLoading ).remove();
			jQuery( label[0].autoCompletedLoadingBackground ).remove();
		}, 600 );
	};
}

/**
 * Handle date
 *
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! window.eoxiaJS.date ) {

	window.eoxiaJS.date = {};

	window.eoxiaJS.date.init = function() {
		jQuery( document ).on ('click', '.group-date .date', function( e ) {
			var defaultDate = jQuery( this ).closest( '.group-date' ).find( '.mysql-date' ).val() ?  jQuery( this ).closest( '.group-date' ).find( '.mysql-date' ).val() : new Date();
			jQuery( this ).datetimepicker( {
				lang: 'fr',
				format: 'd/m/Y',
				mask: true,
				timepicker: false,
				startDate: defaultDate,
				closeOnDateSelect: true,
				onChangeDateTime : function(ct, $i) {
					$i.closest( '.group-date' ).find( '.mysql-date' ).val( ct.dateFormat('Y-m-d') );
				}
			} ).datetimepicker( 'show' );
		});
	};
}

/**
 * Gestion du dropdown.
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! window.eoxiaJS.dropdown  ) {
	window.eoxiaJS.dropdown = {};

	window.eoxiaJS.dropdown.init = function() {
		window.eoxiaJS.dropdown.event();
	};

	window.eoxiaJS.dropdown.event = function() {
		jQuery( document ).on( 'keyup', window.eoxiaJS.dropdown.keyup );
		jQuery( document ).on( 'click', '.wpeo-dropdown:not(.dropdown-active) .dropdown-toggle:not(.disabled)', window.eoxiaJS.dropdown.open );
		jQuery( document ).on( 'click', 'body', window.eoxiaJS.dropdown.close );
	};

	window.eoxiaJS.dropdown.keyup = function( event ) {
		if ( 27 === event.keyCode ) {
			window.eoxiaJS.dropdown.close();
		}
	};

	window.eoxiaJS.dropdown.open = function( event ) {
		window.eoxiaJS.dropdown.close();

		var triggeredElement = jQuery( this );
		triggeredElement.closest( '.wpeo-dropdown' ).addClass( 'dropdown-active' );

		/* Toggle Button Icon */
		var angleElement = triggeredElement.find('[data-fa-i2svg]');
		if ( angleElement ) {
			window.eoxiaJS.dropdown.toggleAngleClass( angleElement );
		}

		event.stopPropagation();
	};

	window.eoxiaJS.dropdown.close = function( event ) {
		jQuery( '.wpeo-dropdown.dropdown-active:not(.no-close)' ).each( function() {
			var toggle = jQuery( this );
			toggle.removeClass( 'dropdown-active' );
			event.stopPropagation();


			/* Toggle Button Icon */
			var angleElement = jQuery( this ).find('.dropdown-toggle').find('[data-fa-i2svg]');
			if ( angleElement ) {
				window.eoxiaJS.dropdown.toggleAngleClass( angleElement );
			}
		});

	};

	window.eoxiaJS.dropdown.toggleAngleClass = function( button ) {
		if ( button.hasClass('fa-caret-down') || button.hasClass('fa-caret-up') ) {
			button.toggleClass('fa-caret-down').toggleClass('fa-caret-up');
		}
		else if ( button.hasClass('fa-caret-circle-down') || button.hasClass('fa-caret-circle-up') ) {
			button.toggleClass('fa-caret-circle-down').toggleClass('fa-caret-circle-up');
		}
		else if ( button.hasClass('fa-angle-down') || button.hasClass('fa-angle-up') ) {
			button.toggleClass('fa-angle-down').toggleClass('fa-angle-up');
		}
		else if ( button.hasClass('fa-chevron-circle-down') || button.hasClass('fa-chevron-circle-up') ) {
			button.toggleClass('fa-chevron-circle-down').toggleClass('fa-chevron-circle-up');
		}
	}
}

if ( ! window.eoxiaJS.form ) {
	window.eoxiaJS.form = {};

	window.eoxiaJS.form.init = function() {
	    window.eoxiaJS.form.event();
	};
	window.eoxiaJS.form.event = function() {
	    jQuery( document ).on( 'click', '.submit-form', window.eoxiaJS.form.submitForm );
	};

	window.eoxiaJS.form.submitForm = function( event ) {
		var element = jQuery( this );
		var doAction = true;

		event.preventDefault();

	/** Méthode appelée avant l'action */
		if ( element.attr( 'data-module' ) && element.attr( 'data-before-method' ) ) {
			doAction = false;
			doAction = window.eoxiaJS[element.attr( 'data-module' )][element.attr( 'data-before-method' )]( element );
		}

		if ( doAction ) {
			element.closest( 'form' ).ajaxSubmit( {
				success: function( response ) {
					if ( response && response.data.module && response.data.callback ) {
						window.eoxiaJS[response.data.module][response.data.callback]( element, response );
					}

					if ( response && response.success ) {
						if ( response.data.module && response.data.callback_success ) {
							window.eoxiaJS[response.data.module][response.data.callback_success]( element, response );
						}
					} else {
						if ( response.data.module && response.data.callback_error ) {
							window.eoxiaJS[response.data.module][response.data.callback_error]( element, response );
						}
					}
				}
			} );
		}
	};

	window.eoxiaJS.form.reset = function( formElement ) {
		var fields = formElement.find( 'input, textarea, select' );

		fields.each(function () {
			switch( jQuery( this )[0].tagName ) {
				case 'INPUT':
				case 'TEXTAREA':
					jQuery( this ).val( jQuery( this )[0].defaultValue );
					break;
				case 'SELECT':
					jQuery( this ).val( 'OK' );
					break;
				default:
					jQuery( this ).val( jQuery( this )[0].defaultValue );
					break;
			}
		} );
	};
}

if ( ! window.eoxiaJS.global ) {
	window.eoxiaJS.global = {};

	window.eoxiaJS.global.init = function() {};

	window.eoxiaJS.global.downloadFile = function( urlToFile, filename ) {
		var alink = document.createElement( 'a' );
		alink.setAttribute( 'href', urlToFile );
		alink.setAttribute( 'download', filename );
		if ( document.createEvent ) {
			var event = document.createEvent( 'MouseEvents' );
			event.initEvent( 'click', true, true );
			alink.dispatchEvent( event );
		} else {
			alink.click();
		}
	};

	window.eoxiaJS.global.removeDiacritics = function( input ) {
		var output = '';
		var normalized = input.normalize( 'NFD' );
		var i = 0;
		var j = 0;

		while ( i < input.length ) {
			output += normalized[j];

			j += ( input[i] == normalized[j] ) ? 1 : 2;
			i++;
		}

		return output;
	};

	}

/**
 * Gestion du loader.
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! window.eoxiaJS.loader ) {
	window.eoxiaJS.loader = {};

	window.eoxiaJS.loader.init = function() {
		window.eoxiaJS.loader.event();
	};

	window.eoxiaJS.loader.event = function() {
	};

	window.eoxiaJS.loader.display = function( element ) {
		// Loader spécial pour les "button-progress".
		if ( element.hasClass( 'button-progress' ) ) {
			element.addClass( 'button-load' )
		} else {
			element.addClass( 'wpeo-loader' );
			var el = jQuery( '<span class="loader-spin"></span>' );
			element[0].loaderElement = el;
			element.append( element[0].loaderElement );
		}
	};

	window.eoxiaJS.loader.remove = function( element ) {
		if ( 0 < element.length && ! element.hasClass( 'button-progress' ) ) {
			element.removeClass( 'wpeo-loader' );

			jQuery( element[0].loaderElement ).remove();
		}
	};
}

/**
 * Gestion de la modal.
 *
 * La modal peut être ouverte par deux moyens:
 * -Avec une requête AJAX.
 * -En plaçant la vue directement dans le DOM.
 *
 * Dans tous les cas, il faut placer un élément HTML avec la classe ".wpeo-modal-event".
 *
 * Cette élement doit contenir différent attributs.
 *
 * Les attributs pour ouvrir la popup avec une requête AJAX:
 * - data-action: Le nom de l'action WordPress.
 * - data-title : Le titre de la popup.
 * - data-class : Pour ajouter une classe dans le contenaire principale de la popup.
 *
 * Les attributs pour ouvrir la popup avec une vue implémentée directement dans le DOM:
 * - data-parent: La classe de l'élement parent contenant la vue de la popup
 * - data-target: La classe de la popup elle même.
 *
 * La modal généré en AJAX est ajouté dans la balise <body> temporairement. Une fois celle-ci fermée
 * elle se détruit du DOM.
 *
 * La modal implémentée dans le DOM (donc non généré en AJAX) reste dans le DOM une fois fermée.
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! window.eoxiaJS.modal  ) {
	window.eoxiaJS.modal = {};

	/**
	 * La vue de la modal (Utilisé pour la requête AJAX, les variables dans la vue *{{}}* ne doit pas être modifiées.).
	 * Voir le fichier /core/view/modal.view.php
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @type string
	 */
	window.eoxiaJS.modal.popupTemplate = wpeo_framework.modalView;

	/**
	 * Les boutons par défault de la modal (Utilisé pour la requête AJAX, les variables dans la vue *{{}}* ne doit pas être modifiées.).
	 * Voir le fichier /core/view/modal-buttons.view.php
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @type string
	 */
	window.eoxiaJS.modal.defaultButtons = wpeo_framework.modalDefaultButtons;

	/**
	 * Le titre par défault de la modal (Utilisé pour la requête AJAX, les variables dans la vue *{{}}* ne doit pas être modifiées.).
	 * Voir le fichier /core/view/modal-title.view.php
	 *
	 * @since 1.0.0
	 * @version 1.0.0
	 *
	 * @type string
	 */
	window.eoxiaJS.modal.defaultTitle = wpeo_framework.modalDefaultTitle;

	window.eoxiaJS.modal.init = function() {
		window.eoxiaJS.modal.event();
	};

	window.eoxiaJS.modal.event = function() {
		jQuery( document ).on( 'keyup', window.eoxiaJS.modal.keyup );
		jQuery( document ).on( 'click', '.wpeo-modal-event', window.eoxiaJS.modal.open );
		jQuery( document ).on( 'click', '.wpeo-modal .modal-container', window.eoxiaJS.modal.stopPropagation );
		jQuery( document ).on( 'click', '.wpeo-modal .modal-close', window.eoxiaJS.modal.close );
		jQuery( document ).on( 'click', 'body', window.eoxiaJS.modal.close );
	};

	window.eoxiaJS.modal.keyup = function( event ) {
		if ( 27 === event.keyCode ) {
			jQuery( '.wpeo-modal.modal-active:not(.no-close) .modal-close:first' ).click();
		}
	};

	window.eoxiaJS.modal.open = function( event ) {
		var triggeredElement = jQuery( this );
		var callbackData = {};
		var key = undefined;

		// Si data-action existe, ce script ouvre la popup en lançant une requête AJAX.
		if ( triggeredElement.attr( 'data-action' ) ) {
			window.eoxiaJS.loader.display( triggeredElement );

			triggeredElement.get_data( function( data ) {
				for ( key in callbackData ) {
					if ( ! data[key] ) {
						data[key] = callbackData[key];
					}
				}

				window.eoxiaJS.request.send( triggeredElement, data, function( element, response ) {
					window.eoxiaJS.loader.remove( triggeredElement );

					if ( response.data.view ) {
						var el = jQuery( document.createElement( 'div' ) );
						el[0].className = 'wpeo-modal modal-active';
						el[0].innerHTML = window.eoxiaJS.modal.popupTemplate;
						el[0].typeModal = 'ajax';
						triggeredElement[0].modalElement = el;

						if ( triggeredElement.attr( 'data-title' ) ) {
							el[0].innerHTML = el[0].innerHTML.replace( '{{title}}', triggeredElement.attr( 'data-title' ) );
						}

						if ( triggeredElement.attr( 'data-class' ) ) {
							el[0].className += ' ' + triggeredElement.attr( 'data-class' );
						}

						jQuery( 'body' ).append( triggeredElement[0].modalElement );

						el[0].innerHTML = el[0].innerHTML.replace( '{{content}}', response.data.view );

						if ( response.data.buttons_view ) {
							el[0].innerHTML = el[0].innerHTML.replace( '{{buttons}}', response.data.buttons_view );
						} else {
							el[0].innerHTML = el[0].innerHTML.replace( '{{buttons}}', window.eoxiaJS.modal.defaultButtons );
						}

						if ( ! triggeredElement.attr( 'data-title' ) ) {
							el[0].innerHTML = el[0].innerHTML.replace( '{{title}}', window.eoxiaJS.modal.defaultTitle );
						}

						if ( window.eoxiaJS.refresh ) {
							window.eoxiaJS.refresh();
						}
					}
				} );
			});
		} else {
			// Stop le script si un de ses deux attributs n'est pas déclaré.
			if ( ! triggeredElement.attr( 'data-parent' ) || ! triggeredElement.attr( 'data-target' ) ) {
				event.stopPropagation();
				return;
			}

			var target = triggeredElement.closest( '.' + triggeredElement.attr( 'data-parent' ) ).find( '.' + triggeredElement.attr( 'data-target' ) );
			target.addClass( 'modal-active' );
			target[0].typeModal = 'default';
			triggeredElement[0].modalElement = target;
		}

		event.stopPropagation();
	};

	window.eoxiaJS.modal.stopPropagation = function( event ) {
		event.stopPropagation();
	};

	window.eoxiaJS.modal.close = function( event ) {
		jQuery( '.wpeo-modal.modal-active:not(.no-close)' ).each( function() {
			var popup = jQuery( this );
			popup.removeClass( 'modal-active' );
			if ( 'default' !== popup[0].typeModal ) {
				setTimeout( function() {
					popup.remove();
				}, 200 );
			}
		} );
	};
}

if ( ! window.eoxiaJS.popover ) {
	window.eoxiaJS.popover = {};

	window.eoxiaJS.popover.init = function() {
		window.eoxiaJS.popover.event();
	};

	window.eoxiaJS.popover.event = function() {
		jQuery( document ).on( 'click', '.wpeo-popover-event.popover-click', window.eoxiaJS.popover.click );
	};

	window.eoxiaJS.popover.click = function( event ) {
		window.eoxiaJS.popover.toggle( jQuery( this ) );
	};

	window.eoxiaJS.popover.toggle = function( element ) {
		var direction = ( element.data( 'direction' ) ) ? element.data( 'direction' ) : 'top';
		var el = jQuery( '<span class="wpeo-popover popover-' + direction + '">' + element.attr( 'aria-label' ) + '</span>' );
		var pos = element.position();
		var offset = element.offset();

		if ( element[0].popoverElement ) {
			jQuery( element[0].popoverElement ).remove();
			delete element[0].popoverElement;
		} else {
			element[0].popoverElement = el;
			jQuery( 'body' ).append( element[0].popoverElement );

			if ( element.data( 'color' ) ) {
				el.addClass( 'popover-' + element.data( 'color' ) );
			}

			var top = 0;
			var left = 0;

			switch( element.data( 'direction' ) ) {
				case 'left':
					top = ( offset.top - ( el.outerHeight() / 2 ) + ( element.outerHeight() / 2 ) ) + 'px';
					left = ( offset.left - el.outerWidth() - 10 ) + 3 + 'px';
					break;
				case 'right':
					top = ( offset.top - ( el.outerHeight() / 2 ) + ( element.outerHeight() / 2 ) ) + 'px';
					left = offset.left + element.outerWidth() + 8 + 'px';
					break;
				case 'bottom':
					top = ( offset.top + element.height() + 10 ) + 10 + 'px';
					left = ( offset.left - ( el.outerWidth() / 2 ) + ( element.outerWidth() / 2 ) ) + 'px';
					break;
				case 'top':
					top = offset.top - el.outerHeight() - 4  + 'px';
					left = ( offset.left - ( el.outerWidth() / 2 ) + ( element.outerWidth() / 2 ) ) + 'px';
					break;
				default:
					top = offset.top - el.outerHeight() - 4  + 'px';
					left = ( offset.left - ( el.outerWidth() / 2 ) + ( element.outerWidth() / 2 ) ) + 'px';
					break;
			}

			el.css( {
				'top': top,
				'left': left,
				'opacity': 1
			} );
		}
	};

	window.eoxiaJS.popover.remove = function( element ) {
		if ( element[0].popoverElement ) {
			jQuery( element[0].popoverElement ).remove();
			delete element[0].popoverElement;
		}
	};
}

/**
 * Handle POPUP
 *
 * @since 1.0.0-easy
 * @version 1.1.0-easy
 */

if ( ! window.eoxiaJS.popup  ) {
	window.eoxiaJS.popup = {};

	window.eoxiaJS.popup.init = function() {
		window.eoxiaJS.popup.event();
	};

	window.eoxiaJS.popup.event = function() {
		jQuery( document ).on( 'keyup', window.eoxiaJS.popup.keyup );
	  jQuery( document ).on( 'click', '.open-popup, .open-popup i', window.eoxiaJS.popup.open );
	  jQuery( document ).on( 'click', '.open-popup-ajax', window.eoxiaJS.popup.openAjax );
	  jQuery( document ).on( 'click', '.popup .container, .digi-popup-propagation', window.eoxiaJS.popup.stop );
	  jQuery( document ).on( 'click', '.popup .container .button.green', window.eoxiaJS.popup.confirm );
	  jQuery( document ).on( 'click', '.popup .close', window.eoxiaJS.popup.close );
	  jQuery( document ).on( 'click', 'body', window.eoxiaJS.popup.close );
	};

	window.eoxiaJS.popup.keyup = function( event ) {
		if ( 27 === event.keyCode ) {
			jQuery( '.popup .close' ).click();
		}
	};

	window.eoxiaJS.popup.open = function( event ) {
		var triggeredElement = jQuery( this );

		if ( triggeredElement.is( 'i' ) ) {
			triggeredElement = triggeredElement.parents( '.open-popup' );
		}

		var target = triggeredElement.closest(  '.' + triggeredElement.data( 'parent' ) ).find( '.' + triggeredElement.data( 'target' ) + ':first' );
		var cbObject, cbNamespace, cbFunc = undefined;

		if ( target ) {
			target[0].className = 'popup';

			if ( triggeredElement.attr( 'data-class' ) ) {
				target.addClass( triggeredElement.attr( 'data-class' ) );
			}

			target.addClass( 'active' );
		}

		if ( target.is( ':visible' ) && triggeredElement.data( 'cb-namespace' ) && triggeredElement.data( 'cb-object' ) && triggeredElement.data( 'cb-func' ) ) {
			cbNamespace = triggeredElement.data( 'cb-namespace' );
			cbObject = triggeredElement.data( 'cb-object' );
			cbFunc = triggeredElement.data( 'cb-func' );

			// On récupères les "data" sur l'élement en tant qu'args.
			triggeredElement.get_data( function( data ) {
				window.eoxiaJS[cbNamespace][cbObject][cbFunc]( triggeredElement, target, event, data );
			} );
		}

	  event.stopPropagation();
	};

	/**
	 * Ouvre la popup en envoyant une requête AJAX.
	 * Les paramètres de la requête doivent être configurer directement sur l'élement
	 * Ex: data-action="load-workunit" data-id="190"
	 *
	 * @since 1.0.0-easy
	 * @version 1.1.0-easy
	 *
	 * @param  {[type]} event [description]
	 * @return {[type]}       [description]
	 */
	window.eoxiaJS.popup.openAjax = function( event ) {
		var element = jQuery( this );
		var callbackData = {};
		var key = undefined;
		var target = jQuery( this ).closest(  '.' + jQuery( this ).data( 'parent' ) ).find( '.' + jQuery( this ).data( 'target' ) + ':first' );

		/** Méthode appelée avant l'action */
		if ( element.attr( 'data-module' ) && element.attr( 'data-before-method' ) ) {
			callbackData = window.eoxiaJS[element.attr( 'data-namespace' )][element.attr( 'data-module' )][element.attr( 'data-before-method' )]( element );
		}

		if ( target ) {
			target[0].className = 'popup';

			if ( element.attr( 'data-class' ) ) {
				target.addClass( element.attr( 'data-class' ) );
			}

			target.addClass( 'active' );
		}

		target.find( '.container' ).addClass( 'loading' );

		if ( jQuery( this ).data( 'title' ) ) {
			target.find( '.title' ).text( jQuery( this ).data( 'title' ) );
		}

		jQuery( this ).get_data( function( data ) {
			delete data.parent;
			delete data.target;

			for ( key in callbackData ) {
				if ( ! data[key] ) {
					data[key] = callbackData[key];
				}
			}

			window.eoxiaJS.request.send( element, data );
		});

		event.stopPropagation();
	};

	window.eoxiaJS.popup.confirm = function( event ) {
		var triggeredElement = jQuery( this );
		var cbNamespace, cbObject, cbFunc = undefined;

		if ( ! jQuery( '.popup' ).hasClass( 'no-close' ) ) {
			jQuery( '.popup' ).removeClass( 'active' );

			if ( triggeredElement.attr( 'data-cb-namespace' ) && triggeredElement.attr( 'data-cb-object' ) && triggeredElement.attr( 'data-cb-func' ) ) {
				cbNamespace = triggeredElement.attr( 'data-cb-namespace' );
				cbObject = triggeredElement.attr( 'data-cb-object' );
				cbFunc = triggeredElement.attr( 'data-cb-func' );

				// On récupères les "data" sur l'élement en tant qu'args.
				triggeredElement.get_data( function( data ) {
					window.eoxiaJS[cbNamespace][cbObject][cbFunc]( triggeredElement, event, data );
				} );
			}
		}
	};

	window.eoxiaJS.popup.stop = function( event ) {
		event.stopPropagation();
	};

	window.eoxiaJS.popup.close = function( event ) {
		if ( ! jQuery( 'body' ).hasClass( 'modal-open' ) ) {
			jQuery( '.popup:not(.no-close)' ).removeClass( 'active' );
			jQuery( '.digi-popup:not(.no-close)' ).removeClass( 'active' );
		}
	};
}

"use strict";

var regex = {
	validateEmail: function(email) {
	    var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	    return re.test(email);
	},

	validateEndEmail: function( endEmail ) {
		var re = /^[a-zA-Z0-9]+\.[a-zA-Z0-9]+(\.[a-z-A-Z0-9]+)?$/;
		return re.test( endEmail );
	}
};

if ( ! window.eoxiaJS.render ) {
	window.eoxiaJS.render = {};

	window.eoxiaJS.render.init = function() {
		window.eoxiaJS.render.event();
	};

	window.eoxiaJS.render.event = function() {};

	window.eoxiaJS.render.callRenderChanged = function() {
		var key = undefined;
		var slug = undefined;

		for ( key in window.eoxiaJS ) {
			if ( window.eoxiaJS[key].renderChanged ) {
				window.eoxiaJS[key].renderChanged();
			}

			for ( slug in window.eoxiaJS[key] ) {
				if ( window.eoxiaJS[key][slug].renderChanged ) {
					window.eoxiaJS[key][slug].renderChanged();
				}
			}
		}
	};
}

/**
 * Gestion des requêtes XHR.
 *
 * @since 1.0.0
 * @version 1.0.0
 */
if ( ! window.eoxiaJS.request ) {
	window.eoxiaJS.request = {};

	window.eoxiaJS.request.init = function() {};

	window.eoxiaJS.request.send = function( element, data, cb ) {
		return jQuery.post( window.ajaxurl, data, function( response ) {
			window.eoxiaJS.loader.remove( element.closest( '.wpeo-loader' ) );

			if ( element.hasClass( 'button-progress' ) ) {
				element.removeClass( 'button-load' ).addClass( 'button-success' );
				setTimeout( function() {
					element.removeClass( 'button-success' );

					if ( cb ) {
						cb( element, response );
					} else {
						if ( response && response.success ) {
							if ( response.data.namespace && response.data.module && response.data.callback_success ) {
								window.eoxiaJS[response.data.namespace][response.data.module][response.data.callback_success]( element, response );
							} else if ( response.data.module && response.data.callback_success ) {
								window.eoxiaJS[response.data.module][response.data.callback_success]( element, response );
							}
						} else {
							if ( response.data.namespace && response.data.module && response.data.callback_error ) {
								window.eoxiaJS[response.data.namespace][response.data.module][response.data.callback_error]( element, response );
							}
						}
					}
				}, 1000 );
			} else {
				if ( cb ) {
					cb( element, response );
				} else {
					if ( response && response.success ) {
						if ( response.data.namespace && response.data.module && response.data.callback_success ) {
							window.eoxiaJS[response.data.namespace][response.data.module][response.data.callback_success]( element, response );
						} else if ( response.data.module && response.data.callback_success ) {
							window.eoxiaJS[response.data.module][response.data.callback_success]( element, response );
						}
					} else {
						if ( response.data.namespace && response.data.module && response.data.callback_error ) {
							window.eoxiaJS[response.data.namespace][response.data.module][response.data.callback_error]( element, response );
						}
					}
				}
			}
		}, 'json').fail( function() {
			window.eoxiaJS.loader.remove( element.closest( '.wpeo-loader' ) );

			if ( element.hasClass( 'button-progress' ) ) {
				element.removeClass( 'button-load' ).addClass( 'button-error' );
				setTimeout( function() {
					element.removeClass( 'button-error' );
				}, 1000 );
			}
		});
	};

	window.eoxiaJS.request.get = function( url, data ) {
		jQuery.get( url, data, function( response ) {
			if ( response && response.success ) {
				if ( response.data.namespace && response.data.module && response.data.callback_success ) {
					window.eoxiaJS[response.data.namespace][response.data.module][response.data.callback_success]( response );
				}
			} else {
				if ( response.data.namespace && response.data.module && response.data.callback_error ) {
					window.eoxiaJS[response.data.namespace][response.data.module][response.data.callback_error]( response );
				}
			}
		}, 'json' );
	};

}

/**
 * Gestion des onglets.
 *
 * @since 1.0.0
 * @version 1.0.0
 */

if ( ! window.eoxiaJS.tab ) {
	window.eoxiaJS.tab = {};

	window.eoxiaJS.tab.init = function() {
		window.eoxiaJS.tab.event();
	};

	window.eoxiaJS.tab.event = function() {
	  jQuery( document ).on( 'click', '.wpeo-tab .tab-element', window.eoxiaJS.tab.load );
	};

	window.eoxiaJS.tab.load = function( event ) {
		var tabTriggered = jQuery( this );
		var data = {};

	  event.preventDefault();
		event.stopPropagation();

		tabTriggered.closest( '.wpeo-tab' ).find( '.tab-element.tab-active' ).removeClass( 'tab-active' );
		tabTriggered.addClass( 'tab-active' );

		if ( ! tabTriggered.attr( 'data-action' ) ) {
			tabTriggered.closest( '.wpeo-tab' ).find( '.tab-content.tab-active' ).removeClass( 'tab-active' );
			tabTriggered.closest( '.wpeo-tab' ).find( '.tab-content[id="' + tabTriggered.attr( 'data-target' ) + '"]' ).addClass( 'tab-active' );
		} else {
			data = {
				action: tabTriggered.attr( 'data-action' ),
				_wpnonce: tabTriggered.attr( 'data-nonce' ),
				target: tabTriggered.attr( 'data-target' ),
				title: tabTriggered.attr( 'data-title' ),
				element_id: tabTriggered.attr( 'data-id' )
		  };

			window.eoxiaJS.loader.display( tabTriggered );

			jQuery.post( window.ajaxurl, data, function( response ) {
				window.eoxiaJS.loader.remove( tabTriggered );
				tabTriggered.closest( '.wpeo-tab' ).find( '.tab-content.tab-active' ).removeClass( 'tab-active' );
				tabTriggered.closest( '.wpeo-tab' ).find( '.tab-content' ).addClass( 'tab-active' );
				tabTriggered.closest( '.wpeo-tab' ).find( '.tab-content' ).html( response.data.view );

				window.eoxiaJS.tab.callTabChanged();
			} );
		}

	};

	window.eoxiaJS.tab.callTabChanged = function() {
		var key = undefined, slug = undefined;
		for ( key in window.eoxiaJS ) {
			for ( slug in window.eoxiaJS[key] ) {
				if ( window.eoxiaJS && window.eoxiaJS[key] && window.eoxiaJS[key][slug].tabChanged ) {
					window.eoxiaJS[key][slug].tabChanged();
				}
			}
		}
	};
}

if ( ! window.eoxiaJS.toggle ) {
	window.eoxiaJS.toggle = {};

	window.eoxiaJS.toggle.init = function() {
		window.eoxiaJS.toggle.event();
	};

	window.eoxiaJS.toggle.event = function() {
	  jQuery( document ).on( 'click', '.toggle:not(.disabled), .toggle:not(.disabled) i', window.eoxiaJS.toggle.open );
	  jQuery( document ).on( 'click', 'body', window.eoxiaJS.toggle.close );
	};

	window.eoxiaJS.toggle.open = function( event ) {
		var target = undefined;
		var data = {};
		var i = 0;
		var listInput = undefined;
		var key = undefined;
		var elementToggle = jQuery( this );

		if ( elementToggle.is( 'i' ) ) {
			elementToggle = elementToggle.parents( '.toggle' );
		}

		jQuery( '.toggle .content.active' ).removeClass( 'active' );
		jQuery( '.toggle' ).closest( '.mask' ).removeClass( 'mask' );

		if ( elementToggle.attr( 'data-parent' ) ) {
			target = elementToggle.closest( '.' + elementToggle.attr( 'data-parent' ) ).find( '.' + elementToggle.attr( 'data-target' ) );
		} else {
			target = jQuery( '.' + elementToggle.attr( 'data-target' ) );
		}

		if ( target ) {
			target.toggleClass( 'active' );

			if ( jQuery( event.currentTarget ).hasClass( 'toggle' ) ) {
				event.stopPropagation();
			}
		}

		if ( elementToggle.attr( 'data-mask' ) ) {
			target.closest( '.' + elementToggle.attr( 'data-mask' ) ).addClass( 'mask' );
		}

		if ( elementToggle.attr( 'data-action' ) ) {
			elementToggle.addClass( 'loading' );

			listInput = window.eoxiaJS.arrayForm.getInput( elementToggle );
			for ( i = 0; i < listInput.length; i++ ) {
				if ( listInput[i].name ) {
					data[listInput[i].name] = listInput[i].value;
				}
			}

			elementToggle.get_data( function( attrData ) {
				for ( key in attrData ) {
					data[key] = attrData[key];
				}

				window.eoxiaJS.request.send( elementToggle, data );
			} );
		}
	};

	window.eoxiaJS.toggle.close = function( event ) {
		jQuery( '.toggle .content' ).removeClass( 'active' );
		jQuery( '.toggle' ).closest( '.mask' ).removeClass( 'mask' );

		event.stopPropagation();
	};
}

if ( ! window.eoxiaJS.tooltip ) {
	window.eoxiaJS.tooltip = {};

	window.eoxiaJS.tooltip.init = function() {
		window.eoxiaJS.tooltip.event();
	};

	window.eoxiaJS.tooltip.event = function() {
		jQuery( document ).on( 'mouseenter', '.wpeo-tooltip-event', window.eoxiaJS.tooltip.display );
		jQuery( document ).on( 'mouseleave', '.wpeo-tooltip-event', window.eoxiaJS.tooltip.remove );
	};

	window.eoxiaJS.tooltip.display = function( event ) {
		var direction = ( jQuery( this ).data( 'direction' ) ) ? jQuery( this ).data( 'direction' ) : 'top';
		var el = jQuery( '<span class="wpeo-tooltip tooltip-' + direction + '">' + jQuery( this ).attr( 'aria-label' ) + '</span>' );
		var pos = jQuery( this ).position();
		var offset = jQuery( this ).offset();
		jQuery( this )[0].tooltipElement = el;
		jQuery( 'body' ).append( jQuery( this )[0].tooltipElement );

		if ( jQuery( this ).data( 'color' ) ) {
			el.addClass( 'tooltip-' + jQuery( this ).data( 'color' ) );
		}

		var top = 0;
		var left = 0;

		switch( jQuery( this ).data( 'direction' ) ) {
			case 'left':
				top = ( offset.top - ( el.outerHeight() / 2 ) + ( jQuery( this ).outerHeight() / 2 ) ) + 'px';
				left = ( offset.left - el.outerWidth() - 10 ) + 3 + 'px';
				break;
			case 'right':
				top = ( offset.top - ( el.outerHeight() / 2 ) + ( jQuery( this ).outerHeight() / 2 ) ) + 'px';
				left = offset.left + jQuery( this ).outerWidth() + 8 + 'px';
				break;
			case 'bottom':
				top = ( offset.top + jQuery( this ).height() + 10 ) + 10 + 'px';
				left = ( offset.left - ( el.outerWidth() / 2 ) + ( jQuery( this ).outerWidth() / 2 ) ) + 'px';
				break;
			case 'top':
				top = offset.top - el.outerHeight() - 4  + 'px';
				left = ( offset.left - ( el.outerWidth() / 2 ) + ( jQuery( this ).outerWidth() / 2 ) ) + 'px';
				break;
			default:
				top = offset.top - el.outerHeight() - 4  + 'px';
				left = ( offset.left - ( el.outerWidth() / 2 ) + ( jQuery( this ).outerWidth() / 2 ) ) + 'px';
				break;
		}

		el.css( {
			'top': top,
			'left': left,
			'opacity': 1
		} );
	};

	window.eoxiaJS.tooltip.remove = function( event ) {
		jQuery( jQuery( this )[0].tooltipElement ).remove();
	};
}