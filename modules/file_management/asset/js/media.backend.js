/**
 * Initialise l'objet "media" ainsi que la méthode "init" obligatoire pour la bibliothèque EoxiaJS.
 *
 * @since 1.0
 * @version 6.2.5.0
 */
window.eoxiaJS.noteDeFrais.media = {
	file_frame: undefined,
	element_id: undefined,
	have_thumbnail: undefined,
	object_name: undefined,
	_wpnonce: undefined,
	element: undefined
};

/**
 * Appel la méthode "event"
 *
 * @return {void}
 *
 * @since 1.0
 * @version 6.2.5.0
 */
window.eoxiaJS.noteDeFrais.media.init = function() {
  window.eoxiaJS.noteDeFrais.media.event();
};

window.eoxiaJS.noteDeFrais.media.event = function() {
  jQuery( document ).on( 'click', '.media:not(.loading):not(.disabled), .upload-model', window.eoxiaJS.noteDeFrais.media.open_popup );
};

window.eoxiaJS.noteDeFrais.media.open_popup = function( event ) {
	var element = jQuery( this );

  event.preventDefault();

  window.eoxiaJS.noteDeFrais.media.element = jQuery( this );
  window.eoxiaJS.noteDeFrais.media.element_id = element.data( 'id' );
  window.eoxiaJS.noteDeFrais.media._wpnonce = element.data( 'nonce' );
  window.eoxiaJS.noteDeFrais.media.title = element.data( 'title' );
  window.eoxiaJS.noteDeFrais.media.object_name = element.data( 'object-name' );
  window.eoxiaJS.noteDeFrais.media.type = element.data( 'type' );
  window.eoxiaJS.noteDeFrais.media.namespace = element.data( 'namespace' );
  window.eoxiaJS.noteDeFrais.media.action = element.data( 'action' );
  window.eoxiaJS.noteDeFrais.media.have_thumbnail = element.hasClass( 'wp-digi-element-thumbnail' ) ? true : false;
  window.wp.media.model.settings.post.id = element.data( 'id' );

  window.eoxiaJS.noteDeFrais.media.load_media_upload( element, element.data( 'id' )  );
};

window.eoxiaJS.noteDeFrais.media.load_media_upload = function( element, post_id ) {
  if ( !window.eoxiaJS.noteDeFrais.media.file_frame ) {
    window.eoxiaJS.noteDeFrais.media.file_frame = new window.wp.media.view.MediaFrame.Post( {
      title: jQuery( element ).data( 'uploader_title' ),
      button: {
        text: jQuery( element ).data( 'uploader_button_text' ),
      },
      multiple: false
    } );
    window.eoxiaJS.noteDeFrais.media.file_frame.el.className += ' digi-upload-' + post_id;
    window.eoxiaJS.noteDeFrais.media.file_frame.on( "insert", function() { window.eoxiaJS.noteDeFrais.media.selected_file( element ); } );
  }

  window.eoxiaJS.noteDeFrais.media.open_media_upload();
};

window.eoxiaJS.noteDeFrais.media.open_media_upload = function() {
  window.eoxiaJS.noteDeFrais.media.file_frame.open();
  return;
};

window.eoxiaJS.noteDeFrais.media.selected_file = function( element ) {
  var selected_file = window.eoxiaJS.noteDeFrais.media.file_frame.state().get( 'selection' );
  var selected_JSON;
  var selected_file_id;
  selected_file.map( function( attachment ) {
    selected_JSON = attachment.toJSON();
    selected_file_id = attachment.id;
  } );

  if ( window.eoxiaJS.noteDeFrais.media.element_id === 0 && window.eoxiaJS.noteDeFrais.media.action != 'eo_set_model' ) {
    window.eoxiaJS.noteDeFrais.media.display_attachment( selected_JSON, element );
  } else {
    window.eoxiaJS.noteDeFrais.media.associate_file( selected_file_id );
  }
};

window.eoxiaJS.noteDeFrais.media.display_attachment = function( selected_JSON, element ) {
  window.eoxiaJS.noteDeFrais.media.element.find( 'img' ).attr( 'src', selected_JSON.sizes.thumbnail.url ).show();

  window.eoxiaJS.noteDeFrais.media.element.find( 'i' ).hide();
  window.eoxiaJS.noteDeFrais.media.element.find( 'input.input-file-image' ).val( selected_JSON.id );
};

window.eoxiaJS.noteDeFrais.media.associate_file = function( selectedFileId ) {
	if ( 'eo_set_model' === window.eoxiaJS.noteDeFrais.media.action ) {
		jQuery( '.upload-model[data-type="' + window.eoxiaJS.noteDeFrais.media.type + '"]' ).addClass( 'loading' );
	} else {
		jQuery( 'span.media[data-id="' + window.eoxiaJS.noteDeFrais.media.element_id + '"]' ).addClass( 'loading' );
	}

  var data = {
    action: window.eoxiaJS.noteDeFrais.media.action,
    file_id: selectedFileId,
    _wpnonce: window.eoxiaJS.noteDeFrais.media._wpnonce,
    title: window.eoxiaJS.noteDeFrais.media.title,
    type: window.eoxiaJS.noteDeFrais.media.type,
    namespace: window.eoxiaJS.noteDeFrais.media.namespace,
    element_id: window.eoxiaJS.noteDeFrais.media.element_id,
    object_name: window.eoxiaJS.noteDeFrais.media.object_name,
    thumbnail: window.eoxiaJS.noteDeFrais.media.have_thumbnail,
  };

  jQuery.post( window.ajaxurl, data, function( response ) {
    if ( response.data.type == 'set_model' ) {
      jQuery( '#digi-handle-model' ).html( response.data.template );
    }
    else {
      jQuery( '.media[data-id="'+ window.eoxiaJS.noteDeFrais.media.element_id + '"]' ).replaceWith( response.data.template );
			jQuery( '.gallery' ).remove();
    }
  });
};
