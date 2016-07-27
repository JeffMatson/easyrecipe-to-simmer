jQuery(document).ready(function(){

    jQuery("#select-all").click(function () {

        jQuery('.import-checkbox').each( function() {
            jQuery(this).attr('checked', 'checked');
        });

    });

});