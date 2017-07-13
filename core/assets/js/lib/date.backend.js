if ( ! window.eoxiaJS.date ) {

	window.eoxiaJS.date = {};

	window.eoxiaJS.date.init = function() {
		jQuery( document ).on( 'click', 'input.date', function( e ) {
			jQuery( this ).datetimepicker( {
				'lang': 'fr',
				'format': 'd/m/Y',
				timepicker: false
			} );
			jQuery( this ).datetimepicker( 'show' );
		} );

		jQuery( document ).on( 'click', 'input.date-time', function( e ) {
			jQuery( this ).datetimepicker( {
				'lang': 'fr',
				'format': 'd/m/Y H:i'
			} );
			jQuery( this ).datetimepicker( 'show' );
		} );

		jQuery( document ).on( 'click', '*[contenteditable="true"].date-time', function( e ) {
			jQuery( this ).datetimepicker( {
				'lang': 'fr',
				'format': 'd/m/Y H:i',
				onChangeDateTime: function( dp, $input ) {
					if ( $input.val() ) {
						jQuery( $input ).text( $input.val() );
					}
				}
			} );
			jQuery( this ).datetimepicker( 'show' );
		} );
	};
}
