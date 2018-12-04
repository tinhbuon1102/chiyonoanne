var haet_mailbuilder = haet_mailbuilder || {};
var mb_woocommerce_relatedproducts = mb_woocommerce_relatedproducts || {};

var mb_text = mb_text || {};

/*************************************
*   GLOBAL FUNCTIONS haet_mailbuilder
* ***********************************/
haet_mailbuilder.create_content_relatedproducts = function( $contentelement, element_id, content_array ){
    var $ = jQuery;
    if( content_array['mb-relatedproducts-num-items'] ){
        for( var i = $('#'+element_id+' .mb-edit-related-products-table td').length; i<content_array['mb-relatedproducts-num-items']; i++){
            mb_woocommerce_relatedproducts.add_column( $('#'+element_id+' .mb-edit-related-products-table td:last-child') );
        }
    }

    $contentelement.find('td').each(function(){
        var styles = content_array[ 'mb-relatedproducts-styles' ];
        if( styles != undefined && styles != "" ){
            styles = JSON.parse( styles );
            mb_woocommerce_relatedproducts.apply_cell_styles( styles );
        }
        
        mb_woocommerce_relatedproducts.apply_cell_content( content_array[ 'mb-relatedproducts-content' ] );
        
    });

    if( content_array[ 'mb-relatedproducts-source' ] != undefined )
        $('#mb-relatedproducts-source').val( content_array[ 'mb-relatedproducts-source' ] );
    haet_mailbuilder.serialize_content();   
    
}


/*************************************
*   CONTENT TYPE INTERNAL FUNCTIONS
* ***********************************/
mb_woocommerce_relatedproducts.add_column = function( $cell ){
    var $ = jQuery;
    var $table = $cell.parents('table.mb-edit-table:first');
    var num_cols = $cell.parent().children('td').length;
    if( num_cols<4 ){
        var $new_cell = $cell.clone();
        $cell.after( $new_cell );
        num_cols++;
    }
    $('#mb-relatedproducts-num-items').val(num_cols);
}




mb_woocommerce_relatedproducts.remove_column = function( $cell ){
    var $ = jQuery;
    var $table = $cell.parents('table.mb-edit-table');
    var num_cols = $cell.parent().children('td').length;
    if( num_cols>2 && confirm( haet_mb_data.translations.confirm_delete_column ) ){
        $cell.remove();
        num_cols--;
    }
    $('#mb-relatedproducts-num-items').val(num_cols);
}





mb_woocommerce_relatedproducts.apply_cell_content = function( content ){
    var $ = jQuery;
    
    
    var $table = $('.mb-edit-related-products-table');
    if( content == undefined )
        content = $table.find('textarea').val();
    else
        $table.find('textarea').val(content);
    
    $table.find('td').each(function(){
        var col_index = $(this).prevAll('td').length;
        if( col_index >= haet_mb_data.placeholders.productstable[ 'items' ].length )
            col_index = 0;
        var preview_content = content.replace(/\[([a-z0-9\_]*)\]/gi, function fill_placeholder(placeholder){
            placeholder = placeholder.replace('[','').replace(']','');

            var placeholder_value;
            if( placeholder.toLowerCase() in haet_mb_data.placeholders.productstable[ 'items' ][col_index] ){
                if( haet_mb_data.placeholders.productstable[ 'items' ][col_index][ placeholder.toLowerCase() ] == "" )
                    placeholder_value = placeholder;
                else
                    placeholder_value = haet_mb_data.placeholders.productstable[ 'items' ][col_index][placeholder.toLowerCase()];
            }else
                placeholder_value = '['+placeholder+']';
            return placeholder_value;
        });
        $(this).find('.mb-content-preview').html( preview_content );    
    });

}



mb_woocommerce_relatedproducts.apply_cell_styles = function( styles ){
    var $ = jQuery;
    var $table = $('.mb-edit-related-products-table');

    $table.find('td').css( styles );

    $table.find('.mb-cell-styles').val( JSON.stringify( styles ) );
}




jQuery(document).ready(function($) {
    // Add column
    $('#mailbuilder-content').on('click', '.mb-contentelement-relatedproducts .mb-edit-cell .mb-add-column', function(e){
        e.stopPropagation();
        var $cell = $(this).parents( '.mb-edit-cell' );
        mb_woocommerce_relatedproducts.add_column( $cell );
        haet_mailbuilder.serialize_content();
    });

    // Remove column
    $('#mailbuilder-content').on('click', '.mb-contentelement-relatedproducts .mb-edit-cell .mb-remove-column', function(e){
        e.stopPropagation();
        var $cell = $(this).parents( '.mb-edit-cell' );
        mb_woocommerce_relatedproducts.remove_column( $cell );
        haet_mailbuilder.serialize_content();
    });

    // Related Products Source
    $('#mailbuilder-content').on('change', '#mb-relatedproducts-source', function(){
        haet_mailbuilder.serialize_content();    
    });
    
    // make cell content editable
    $('#mailbuilder-content').on('click', '.mb-contentelement-relatedproducts .mb-edit-cell-content', function(e){
        
        var $cell = $( this ).parents('.mb-edit-cell');

        // WYSIWYG Editor
        var $textarea = $cell.find('textarea'); 
        tinymce.editors['mb_tiny_wysiwyg_editor'].setContent( $textarea.val() );

        var $settings = $( '#mb-edit-cell' );

        var $sidebar = $('.mailbuilder-settings-sidebar');
        $sidebar.addClass('sidebar-wide');

        $sidebar.find('.mailbuilder-sidebar-element.active').removeClass('active');

        $settings.addClass( 'active' );

        var default_styles = { 
                'border-left-style'     :   'none', 
                'border-top-style'      :   'none',
                'border-right-style'    :   'none',
                'border-bottom-style'   :   'none', 
                'border-left-color'     :   '#000000', 
                'border-top-color'      :   '#000000',
                'border-right-color'    :   '#000000',
                'border-bottom-color'   :   '#000000', 
                'border-left-width'     :   '1px', 
                'border-top-width'      :   '1px', 
                'border-right-width'    :   '1px', 
                'border-bottom-width'   :   '1px',
                'width'                 :   'auto',
                'padding-left'          :   '0px',
                'padding-top'           :   '0px',
                'padding-right'         :   '0px',
                'padding-bottom'        :   '0px'
            };

        var raw_styles = $cell.find('.mb-cell-styles').val();
        var styles = {};
        if ( raw_styles ){
            styles = JSON.parse( raw_styles );
        }
        //merge with default styles
        styles = $.extend( default_styles, styles );

        $.each( styles, function(key,val){
            if( key.indexOf('-color') > -1 ) // color values
                $settings.find('#mb-cell-'+key).wpColorPicker('color', val );
            else if( key.indexOf('-style') > -1 ) // border style
                $settings.find('#mb-cell-'+key.replace('style','enabled') ).prop('checked', val == 'solid' );
            else // general settings
                $settings.find('#mb-cell-'+key).val( val );
        });
        

        $settings.fadeIn(300);

        $settings.find('.mb-apply').one('click', function(){
            $settings.removeClass( 'active' );
            $sidebar.find('.mb-add-wrap').addClass('active');
            $sidebar.removeClass('sidebar-wide');
            
            mb_woocommerce_relatedproducts.apply_cell_content( tinymce.editors['mb_tiny_wysiwyg_editor'].getContent() );

            tinymce.editors['mb_tiny_wysiwyg_editor'].setContent('');
            // get style settings
            styles = {
                    'border-left-style'     : ( $('#mb-cell-border-left-enabled').prop('checked') ? 'solid' : 'none' ),
                    'border-top-style'      : ( $('#mb-cell-border-top-enabled').prop('checked') ? 'solid' : 'none' ),
                    'border-right-style'    : ( $('#mb-cell-border-right-enabled').prop('checked') ? 'solid' : 'none' ),
                    'border-bottom-style'   : ( $('#mb-cell-border-bottom-enabled').prop('checked') ? 'solid' : 'none' ),
                };

            // use default_styles as list of available styles
            $.each( default_styles, function(key,val){
                
                if( key.indexOf('-style') > -1 ) // border style
                    styles[ key ] = ( $('#mb-cell-' + key.replace('style','enabled') ).prop('checked') ? 'solid' : 'none' );
                else
                    styles[ key ] = $('#mb-cell-'+key ).val();
            });

            mb_woocommerce_relatedproducts.apply_cell_styles( styles );

            haet_mailbuilder.serialize_content();
        });

        $settings.find('.mb-cancel').one('click', function(){
            $settings.removeClass( 'active' );
            $sidebar.find('.mb-add-wrap').addClass('active');
            $sidebar.removeClass('sidebar-wide');
            
            tinymce.editors['mb_tiny_wysiwyg_editor'].setContent('');
        });
            
    });


    $('input.color').wpColorPicker();
});