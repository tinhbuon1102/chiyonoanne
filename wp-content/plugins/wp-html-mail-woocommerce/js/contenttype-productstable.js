var haet_mailbuilder = haet_mailbuilder || {};
var mb_woocommerce_productstable = mb_woocommerce_productstable || {};

var mb_text = mb_text || {};

/*************************************
*   GLOBAL FUNCTIONS haet_mailbuilder
* ***********************************/
haet_mailbuilder.create_content_productstable = function( $contentelement, element_id, content_array ){
    var $ = jQuery;

    for( var i = 1; i<10; i++){
        if( 'mb-edit-productstable-header[' + i + ']' in content_array ){
            mb_woocommerce_productstable.add_column( $('.mb-edit-productstable thead td.mb-edit-cell:first-child') );
        }
    }

    $contentelement.find('.mb-edit-table td.mb-edit-cell').each(function(){
        var $cell = $(this);
        
        var style_field_name = $cell.find('.mb-cell-styles').attr('name');
        var styles = content_array[ style_field_name ];
        if( styles != undefined && styles != "" )
            styles = JSON.parse( content_array[ style_field_name ] )
        if( style_field_name in content_array && content_array[ style_field_name ] != undefined )
            mb_woocommerce_productstable.apply_cell_styles( $cell, styles );

        var content_field_name = $cell.find('textarea').attr('name');
        if( content_field_name in content_array && content_array[ content_field_name ] != undefined )
            mb_woocommerce_productstable.apply_cell_content( $cell, content_array[ content_field_name ] );

    });
}


/*************************************
*   CONTENT TYPE INTERNAL FUNCTIONS
* ***********************************/
mb_woocommerce_productstable.add_column = function( $cell ){
    var $ = jQuery;
    var $table = $cell.parents('table.mb-edit-productstable:first');
    var cell_index = $cell.prevAll('td').length;

    $table.find('tr td:nth-child(' + ( cell_index + 1) + ')').each( function(){
        if( !$(this).hasClass('mb-purchase-note-cell') ){
            var $new_cell = $(this).clone();
            $(this).after( $new_cell );
        }
    });

    $table.find('tr td.mb-purchase-note-cell').each( function(){
        $(this).attr( 'colspan', $table.find('thead tr td').length );
    });

    $table.find('td.mb-edit-cell').each(function(){
        mb_woocommerce_productstable.update_field_names( $(this) );
    });
}




mb_woocommerce_productstable.remove_column = function( $cell ){
    var $ = jQuery;
    var $table = $cell.parents('table.mb-edit-table');
    var cell_index = $cell.prevAll('td').length;
    var num_cols = $cell.parent().children('td').length;
    if( num_cols>1 && confirm( haet_mb_data.translations.confirm_delete_column ) ){
        $table.find('tr td:nth-child(' + ( cell_index + 1) + ')').remove();

        $table.find('tr td.mb-purchase-note-cell').each( function(){
            $(this).attr( 'colspan', $table.find('thead tr td').length );
        });

        $table.find('td.mb-edit-cell').each(function(){
            mb_woocommerce_productstable.update_field_names( $(this) );
        });
    }
}



mb_woocommerce_productstable.update_field_names = function( $cell ){
    var table_type = 'productstable';
    if( $cell.parents('.mb-edit-totalstable').length )
        table_type = 'totalstable';

    var cell_type = 'body';
    if ( $cell.parents('thead').length )
        cell_type = 'header';
    if ( $cell.hasClass( 'mb-purchase-note-cell' ) )
        cell_type = 'purchasenote';

    $cell.children('.mb-cell-content').attr('name','mb-edit-' + table_type + '-'+cell_type+'['+$cell.prevAll('td').length+']');
    $cell.children('.mb-cell-styles').attr('name','mb-edit-' + table_type + '-'+cell_type+'-styles['+$cell.prevAll('td').length+']');
}




mb_woocommerce_productstable.apply_cell_content = function( $cell, content ){
    var $ = jQuery;
    
    $cell.find('textarea').val(content);
    var $table = $cell.parents('table.mb-edit-table');

    if ( $cell.parents('thead').length )
        $cell.find( '.mb-content-preview' ).html( content );


    else if ( $cell.parents('tbody').length ){
        var cell_index = $cell.prevAll('td').length;
        if( $cell.parent().hasClass('mb-item-content-row') ){
            $table.find('tbody tr.mb-item-content-row').find('td:nth-child(' + (cell_index + 1) + ')').each(function(){
                var row_index = $(this).parent().prevAll('tr.mb-item-content-row').length;
                var preview_content = content.replace(/\[([a-z0-9\_]*)\]/gi, function fill_placeholder(placeholder){
                    placeholder = placeholder.replace('[','').replace(']','');

                    var placeholder_value;
                    if( 
                        typeof haet_mb_data.placeholders.productstable  !== undefined
                        && typeof haet_mb_data.placeholders.productstable[ ( $table.hasClass('mb-edit-productstable') ? 'items' : 'totals' ) ]  !== undefined
                        && typeof haet_mb_data.placeholders.productstable[ ( $table.hasClass('mb-edit-productstable') ? 'items' : 'totals' ) ][row_index]  !== undefined 
                        && placeholder.toLowerCase() in haet_mb_data.placeholders.productstable[ ( $table.hasClass('mb-edit-productstable') ? 'items' : 'totals' ) ][row_index] 
                    ){
                        if( haet_mb_data.placeholders.productstable[ ( $table.hasClass('mb-edit-productstable') ? 'items' : 'totals' ) ][row_index][ placeholder.toLowerCase() ] == "" )
                            placeholder_value = placeholder;
                        else
                            placeholder_value = haet_mb_data.placeholders.productstable[ ( $table.hasClass('mb-edit-productstable') ? 'items' : 'totals' ) ][row_index][placeholder.toLowerCase()];
                    }else
                        placeholder_value = '['+placeholder+']';
                    return placeholder_value;
                });
                $(this).find('.mb-content-preview').html( preview_content );    
            });
        } else if( $cell.parent().hasClass('mb-purchase-note-row') ){
            $table.find('tbody .mb-purchase-note-cell').each(function(){
                var row_index = $(this).parent().prevAll('tr.mb-purchase-note-row').length;
                var preview_content = content.replace(/\[([a-z0-9\_]*)\]/gi, function fill_placeholder_purchase_note(placeholder){
                    placeholder = placeholder.replace('[','').replace(']','');

                    var placeholder_value;
                    if( placeholder.toLowerCase() in haet_mb_data.placeholders.productstable[ 'items' ][row_index] ){
                        if ( haet_mb_data.placeholders.productstable[ 'items' ][row_index][placeholder.toLowerCase()] == "" )
                            placeholder_value = placeholder;
                        else
                            placeholder_value = haet_mb_data.placeholders.productstable[ 'items' ][row_index][placeholder.toLowerCase()];
                    }else
                        placeholder_value = '['+placeholder+']';
                    return placeholder_value;
                });
                $(this).find('.mb-content-preview').html( preview_content );    
            });
        }
    }
}



mb_woocommerce_productstable.apply_cell_styles = function( $cell, styles ){
    //console.log( $cell, styles );
    var $ = jQuery;
    var cell_index = $cell.prevAll('td').length;
    var $table = $cell.parents('table.mb-edit-table');

    if( $cell.parent().hasClass('mb-item-content-row') ){
        $table.find('tbody tr.mb-item-content-row td:nth-child(' + (cell_index + 1) + ')').css( styles );
    }else if( $cell.parent().hasClass('mb-purchase-note-row') ){
        $table.find('tbody tr.mb-purchase-note-row td').css( styles );
    }
    else
        $cell.css( styles );

    $cell.find('.mb-cell-styles').val( JSON.stringify( styles ) );
}



/*************************************
*   EXPORT TABLE CONFIGURATION
* ***********************************/
mb_woocommerce_productstable.show_content_export = function( $export_button ){
    var $ = jQuery;
    var raw_content = $('#mailbuilder_json').val();

    var content_array = [];

    if( raw_content.length )
        content_array = JSON.parse( raw_content );

    var $element = $export_button.parents('.mb-contentelement');
    var element_id = $element.attr('id');

    $element.find('.mb-import-export').toggleClass('active');
    $element.find('.import-export-settings').val( JSON.stringify( content_array[element_id] ) ).click(function(){
        $(this).select();
    });
};


/*************************************
*   IMPORT TABLE CONFIGURATION
* ***********************************/
mb_woocommerce_productstable.import_configuration = function( $export_button ){
    var $ = jQuery;

    var $element = $export_button.parents('.mb-contentelement');
    var element_id = $element.attr('id');
    var raw_content = $element.find('.import-export-settings').val();

    if( raw_content.length ){
        content_array = JSON.parse( raw_content );

        var $contentelement = $('#mailbuilder-templates .mb-contentelement-productstable')
            .clone()
            .attr('id',element_id)
            .replaceAll($element);
            
        haet_mailbuilder.create_content_productstable( $contentelement, element_id, content_array['content'] );
        haet_mailbuilder.serialize_content();
    }

};



jQuery(document).ready(function($) {
    $('.mb-contentelement-productstable .mb-edit-table td.mb-edit-cell').each( function(){ 
        mb_woocommerce_productstable.update_field_names( $(this) );
    } );
    

    // Add column
    $('#mailbuilder-content').on('click', '.mb-contentelement-productstable .mb-edit-cell .mb-add-column', function(e){
        e.stopPropagation();
        var $cell = $(this).parents( '.mb-edit-cell' );
        mb_woocommerce_productstable.add_column( $cell );
        haet_mailbuilder.serialize_content();
    });

    // Remove column
    $('#mailbuilder-content').on('click', '.mb-contentelement-productstable .mb-edit-cell .mb-remove-column', function(e){
        e.stopPropagation();
        var $cell = $(this).parents( '.mb-edit-cell' );
        mb_woocommerce_productstable.remove_column( $cell );
        haet_mailbuilder.serialize_content();
    });

    
    // make cell content editable
    $('#mailbuilder-content').on('click', '.mb-contentelement-productstable .mb-edit-cell-content', function(e){
        
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
        

        $settings.find('.mb-apply').one('click', function(){
                $settings.removeClass( 'active' );
                $sidebar.find('.mb-add-wrap').addClass('active');
                $sidebar.removeClass('sidebar-wide');
                
                mb_woocommerce_productstable.apply_cell_content( $cell, tinymce.editors['mb_tiny_wysiwyg_editor'].getContent() );

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

                mb_woocommerce_productstable.apply_cell_styles( $cell, styles );

                haet_mailbuilder.serialize_content();
            });

        $settings.find('.mb-cancel').one('click', function(){
                $settings.removeClass( 'active' );
                $sidebar.find('.mb-add-wrap').addClass('active');
                $sidebar.removeClass('sidebar-wide');

                tinymce.editors['mb_tiny_wysiwyg_editor'].setContent('');
            });
            
    });

    // Import Export
    $('#mailbuilder-content').on('click', '.mb-contentelement-productstable .import-export-link, .mb-contentelement-productstable .import-export-close', function(e){
        e.preventDefault();
        mb_woocommerce_productstable.show_content_export( $(this) );
    });

    $('#mailbuilder-content').on('click', '.mb-contentelement-productstable .import-export-apply', function(e){
        e.preventDefault();
        mb_woocommerce_productstable.import_configuration( $(this) );
    });

    $('input.color').wpColorPicker();
});




