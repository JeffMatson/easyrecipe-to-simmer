jQuery( document ).ready( function(){

    // If the Select All button is clicked...
    jQuery('#select-all').click( function () {

        // Check everything.
        jQuery('.import-checkbox').each( function() {
            jQuery( this ).attr( 'checked', 'checked' );
        });

    });

    // When the import selected button is clicked...
    jQuery('#import-selected-button').click( function() {
        // Determine which checkboxes are checked.
        jQuery( '.import-checkbox:checked').each( function() {
            var self = this;
            // Send the AJAX request to handle the import.
            jQuery.ajax({
                url: ajaxurl,
                type: 'POST',
                data: {
                    action: 'import_post',
                    post_id: jQuery( self ).data('post-details')
                }
            }).done( function( data ) {
                // Once complete, show in console log for debugging.
                console.log( data );
                console.log('finished');
            }).fail( function( first, second, third ) {
                // If it failed miserably, show the data.
                console.log( first );
                console.log( second );
                console.log( third );
                console.log('failed');
            });
        });
    });

});