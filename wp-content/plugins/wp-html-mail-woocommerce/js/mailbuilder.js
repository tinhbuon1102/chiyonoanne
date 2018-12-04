var haet_mailbuilder = haet_mailbuilder || {};

jQuery(document).ready(function($) {
    $('.mb-restore-defaults').on( 'click', function(e){
        if( confirm( haet_mb_data.translations.confirm_restore_default_content ) ){
            e.preventDefault();
            $('#mb_restore_defaults').val(1);
            $('#publish').click();
        }
    });
});