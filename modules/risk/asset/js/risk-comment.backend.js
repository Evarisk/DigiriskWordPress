"use strict";

var digi_risk_comment = {
	$: undefined,

	event: function( $ ) {
		digi_risk_comment.$ = $;

    digi_risk_comment.$( document ).on( 'click', '.wp-digi-action-comment-delete', function( event ) { digi_risk_comment.delete_comment( event, digi_risk_comment.$( this ) ); } );
	},

  delete_comment: function( event, element ) {
    if( window.confirm( window.digi_confirm_delete ) ) {
      var data = {
        action: 'delete_comment',
        _wpnonce: digi_risk.$( element ).data( 'nonce' ),
        risk_id: digi_risk.$( element ).data( 'risk-id' ),
        id: digi_risk.$( element ).data( 'id' ),
      };
      digi_risk.$( element ).closest( 'li' ).remove();
      digi_risk.$.post( window.ajaxurl, data, function() {} );
    }
  }
};
