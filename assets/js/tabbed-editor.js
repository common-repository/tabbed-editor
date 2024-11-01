jQuery( function( $ ) {
  $( '.columns-2').removeClass( 'columns-2' );
  $( '#poststuff' ).prepend( $( '#submitdiv' ) );
  $( '#post-body' ).prepend( '<ul><li><a href="#tab-edit"><span class="dashicons dashicons-text"></span></a></li><li><a href="#tab-meta"><span class="dashicons dashicons-admin-generic"></span></a></li></ul><div id="tab-edit"></div><div id="tab-meta"></div>' );
  $( '#tab-edit' ).append( $( '#post-body-content' ) );
  $( '#tab-meta' ).append( $( '.postbox-container' ) );
  $( '#post-body' ).tabs();
  $( '#wpwrap' ).show();
});