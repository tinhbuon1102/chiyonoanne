jQuery(document).ready(function($) {
    tinymce.PluginManager.add('mb_woocommercetext_placeholder' , function( editor, url ) {
        if ( editor.id == 'mb_wysiwyg_editor' ){
            var editor_menu = haet_mb_data.placeholder_menu.text;
            for(var i = 0; i<editor_menu.length; i++){
                if ( editor_menu[i].tooltip ){
                    editor_menu[i].onclick = function() {
                        // tinymce saves the tooltip as Aria label
                        tinyMCE.get('mb_wysiwyg_editor').insertContent( this._aria.label );
                    }
                }
                if ( editor_menu[i].menu ){
                    for(var ii = 0; ii<editor_menu[i].menu.length; ii++){
                        if ( editor_menu[i].menu[ii].tooltip ){
                            editor_menu[i].menu[ii].onclick = function() {
                                // tinymce saves the tooltip as Aria label
                                tinyMCE.get('mb_wysiwyg_editor').insertContent( this._aria.label );
                            }
                        }
                        if ( editor_menu[i].menu[ii].menu ){
                            for(var iii = 0; iii < editor_menu[i].menu[ii].menu.length; iii++){
                                if ( editor_menu[i].menu[ii].menu[iii].tooltip ){
                                    editor_menu[i].menu[ii].menu[iii].onclick = function() {
                                        // tinymce saves the tooltip as Aria label
                                        tinyMCE.get('mb_wysiwyg_editor').insertContent( this._aria.label );
                                    }
                                }
                                
                            }
                        }
                    }
                }
            }

        
            editor.addButton( 'mb_woocommercetext_placeholder', {
                text: haet_mb_data.translations.placeholder_button,
                icon: false,
                type: 'menubutton',
                menu: editor_menu
            });
        }
    });
});