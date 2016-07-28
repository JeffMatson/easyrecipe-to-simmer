jQuery( document ).ready( function(){

    jQuery('#select-all').click( function () {

        jQuery('.import-checkbox').each( function() {
            jQuery( this ).attr( 'checked', 'checked' );
        });

    });

    jQuery('#import-selected-button').click( function() {
        jQuery( '.import-checkbox:checked').each( function() {
            var self = this;
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'import_post',
                    post_id: jQuery(self).data('post-details')
                }
            }).done( function( data ) {
                console.log( data );
                console.log('finished');
            }).fail( function( first, second, third ) {
                console.log(first);
                console.log(second);
                console.log(third);
                console.log('failed');
            });
        });
    });

});