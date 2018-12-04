var haet_mailbuilder = haet_mailbuilder || {};
var mb_woocommerce_wgm = mb_woocommerce_wgm || {};

var mb_text = mb_text || {};

/*************************************
*   GLOBAL FUNCTIONS haet_mailbuilder
* ***********************************/
haet_mailbuilder.create_content_wgm = function( $contentelement, element_id, content_array ){
    var $ = jQuery;

    for( var field in content_array ){
        if( field == 'wgmcontent' ){
            try{
                mb_woocommerce_wgm.apply_content( $contentelement, JSON.parse( content_array[field] ) );
            }catch(e){
                console.log( 'ERROR: ' + content_array[field] );
            }
        }
        if( field == 'wgmstyle' ){
            try{
                mb_woocommerce_wgm.apply_styles( $contentelement, JSON.parse( content_array[field] ) );
            }catch(e){
                console.log( 'ERROR: ' + content_array[field] );
            }
        }
    }
    
}


/*************************************
*   CONTENT TYPE INTERNAL FUNCTIONS
* ***********************************/
mb_woocommerce_wgm.apply_content = function( $element, content ){
    var $ = jQuery;
    
    $element.find('.mb-wgm-content').val( JSON.stringify( content ) );
}





mb_woocommerce_wgm.apply_styles = function( $element, styles ){
    var $ = jQuery;
    
    $element.find('.mb-wgm-style').val( JSON.stringify( styles ) );

    $.each( styles, function( key, css ){
        var attribute = key.substring( key.indexOf('-')+1 );
        if( attribute.indexOf('size') > -1 )
            css += 'px';
        if( key.indexOf('h1') > -1 )
            $element.find('.mb-content-preview h1').css( attribute, css );
        else if( key.indexOf('h2') > -1 )
            $element.find('.mb-content-preview h2').css( attribute, css );
        else if( key.indexOf('h3') > -1 )
            $element.find('.mb-content-preview h3').css( attribute, css );
        else if( key.indexOf('text') == 0 )
            $element.find('.mb-content-preview').css( attribute, css );
    });
}


jQuery(document).ready(function($) {
    // make content editable
    $('#mailbuilder-content').on('click', '.mb-contentelement-content-wgm', function(e){
        var $element = $(this);
        $( 'body' ).addClass( 'mb-overlay' );
        var $popup = $( '#mb_wgm' );
        

        // read content settings
        var default_content = {
            'imprint'               : true,
            'terms'                 : true,
            'cancellation_policy'   : true,
            'cancellation_policy_for_digital_goods': true,
            'cancellation_policy_for_digital_goods_acknowlagement': true,
            'delivery'              : true,
            'payment_methods'       : true
        }

        var raw_content = $element.find('.mb-wgm-content').val();
        var content = {};
        if ( raw_content ){
            content = JSON.parse( raw_content );
        }
        //merge with default content
        content = $.extend( default_content, content );

        $.each( content, function(key,enabled){
            $popup.find('#mb-wgm-'+key ).prop('checked', enabled );
        });


        //read style settings
        var default_styles = { 
                'h1-font-family'        :   'Arial, Helvetica, sans-serif', 
                'h1-font-size'          :   '14',
                'h1-font-weight'        :   'bold',
                'h1-font-style'         :   'italic', 
                'h1-text-align'         :   'left', 
                'h1-color'              :   '#888888',
                'h2-font-family'        :   'Arial, Helvetica, sans-serif', 
                'h2-font-size'          :   '13',
                'h2-font-weight'        :   'bold',
                'h2-font-style'         :   'italic', 
                'h2-text-align'         :   'left', 
                'h2-color'              :   '#888888',
                'h3-font-family'        :   'Arial, Helvetica, sans-serif', 
                'h3-font-size'          :   '12',
                'h3-font-weight'        :   'bold',
                'h3-font-style'         :   'normal', 
                'h3-text-align'         :   'left', 
                'h3-color'              :   '#888888',
                'text-font-family'      :   'Arial, Helvetica, sans-serif', 
                'text-font-size'        :   '12',
                'text-font-weight'      :   'normal',
                'text-font-style'       :   'normal', 
                'text-text-align'       :   'left', 
                'text-color'            :   '#888888',
            };

        var raw_styles = $element.find('.mb-wgm-style').val();
        var styles = {};
        if ( raw_styles ){
            styles = JSON.parse( raw_styles );
        }
        //merge with default styles
        styles = $.extend( default_styles, styles );

        $.each( styles, function(key,val){
            if( key.indexOf('-color') > -1 ) // color values
                $popup.find('#mb-wgm-'+key).wpColorPicker('color', val );
            else if( key.indexOf('-font-style') > -1 ) // font-style
                $popup.find('#mb-wgm-'+key).prop('checked', val == 'italic' );
            else if( key.indexOf('-font-weight') > -1 ) // font-weight
                $popup.find('#mb-wgm-'+key).prop('checked', val == 'bold' );
            else if( key.indexOf('-text-align') > -1 ) // text-align
                $popup.find('#mb-wgm-'+key+'_'+val).prop('checked', true );
            else // general settings
                $popup.find('#mb-wgm-'+key).val( val );
        });
        

        $popup.fadeIn(300);

        $popup.find('.mb-apply').one('click', function(){
                $popup.fadeOut( 200 );
                $( 'body' ).removeClass( 'mb-overlay' );
                
                // get content settings
                styles = {};

                // use default_styles as list of available styles
                $.each( default_styles, function(key,val){
                    
                    if( key.indexOf('-font-style') > -1 ) // font style
                        styles[ key ] = ( $('#mb-wgm-' + key ).prop('checked') ? 'italic' : 'normal' );
                    else if( key.indexOf('-font-weight') > -1 ) // font weight
                        styles[ key ] = ( $('#mb-wgm-' + key ).prop('checked') ? 'bold' : 'normal' );
                    else if( key.indexOf('-text-align') > -1 ) // text-align
                        styles[ key ] = $popup.find('[name="mb-wgm-'+key+'"]:checked' ).val();
                    else
                        styles[ key ] = $('#mb-wgm-'+key ).val();
                });

                mb_woocommerce_wgm.apply_styles( $element, styles );

                $.each( default_content, function(key,val){
                    content[ key ] = $('#mb-wgm-' + key ).prop('checked');
                });

                mb_woocommerce_wgm.apply_content( $element, content ); 

                haet_mailbuilder.serialize_content();
            });

        $popup.find('.mb-cancel').one('click', function(){
                $popup.fadeOut( 200 );
                $( 'body' ).removeClass( 'mb-overlay' );
                tinymce.editors['mb_tiny_wysiwyg_editor'].setContent('');
            });
            
    });


    $('input.color').wpColorPicker();
});