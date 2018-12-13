// var thec_current_year = new Date().getFullYear();
// var thwec_settings_advanced = (function($, window, document) {
//      /*------------------------------------
//   	*---- ON-LOAD FUNCTIONS - SATRT -----
//   	*------------------------------------*/
//   	$(function() {
//   		var advanced_settings_form = $('#advanced_settings_form');
//   		if(advanced_settings_form[0]) {
//   			thwec_base.setupEnhancedMultiSelectWithValue(advanced_settings_form);
//   		}
//   	});
//      /*------------------------------------
//   	*---- ON-LOAD FUNCTIONS - END -----
//   	*------------------------------------*/
  	
//      /*------------------------------------
//   	*---- Custom Validations - SATRT -----
//   	*------------------------------------*/
//   	var VALIDATOR_ROW_HTML  = '<tr>';
//       VALIDATOR_ROW_HTML += '<td style="width:190px;"><input type="text" name="i_validator_name[]" placeholder="Validator Name" style="width:180px;"/></td>';
//   		VALIDATOR_ROW_HTML += '<td style="width:190px;"><input type="text" name="i_validator_label[]" placeholder="Validator Label" style="width:180px;"/></td>';
//   		VALIDATOR_ROW_HTML += '<td style="width:190px;"><input type="text" name="i_validator_pattern[]" placeholder="Validator Pattern" style="width:180px;"/></td>';
//   		VALIDATOR_ROW_HTML += '<td style="width:190px;"><input type="text" name="i_validator_message[]" placeholder="Validator Message" style="width:180px;"/></td>';
//   		VALIDATOR_ROW_HTML += '<td class="action-cell">';
//   		VALIDATOR_ROW_HTML += '<a href="javascript:void(0)" onclick="thwepoAddNewValidatorRow(this, 0)" class="dashicons dashicons-plus" title="Add new validator"></a></td>';
//   		VALIDATOR_ROW_HTML += '<td class="action-cell">';
//   		VALIDATOR_ROW_HTML += '<a href="javascript:void(0)" onclick="thwepoRemoveValidatorRow(this, 0)" class="dashicons dashicons-no-alt" title="Remove validator"></a></td>';
//   		VALIDATOR_ROW_HTML += '</tr>';
  		
//   	var CNF_VALIDATOR_ROW_HTML  = '<tr>';
//           CNF_VALIDATOR_ROW_HTML += '<td style="width:190px;"><input type="text" name="i_cnf_validator_name[]" placeholder="Validator Name" style="width:180px;"/></td>';
//   		CNF_VALIDATOR_ROW_HTML += '<td style="width:190px;"><input type="text" name="i_cnf_validator_label[]" placeholder="Validator Label" style="width:180px;"/></td>';
//   		CNF_VALIDATOR_ROW_HTML += '<td style="width:190px;"><input type="text" name="i_cnf_validator_pattern[]" placeholder="Field Name" style="width:180px;"/></td>';
//   		CNF_VALIDATOR_ROW_HTML += '<td style="width:190px;"><input type="text" name="i_cnf_validator_message[]" placeholder="Validator Message" style="width:180px;"/></td>';
//   		CNF_VALIDATOR_ROW_HTML += '<td class="action-cell">';
//   		CNF_VALIDATOR_ROW_HTML += '<a href="javascript:void(0)" onclick="thwepoAddNewValidatorRow(this, 1)" class="dashicons dashicons-plus" title="Add new validator"></a></td>';
//   		CNF_VALIDATOR_ROW_HTML += '<td class="action-cell">';
//   		CNF_VALIDATOR_ROW_HTML += '<a href="javascript:void(0)" onclick="thwepoRemoveValidatorRow(this, 1)" class="dashicons dashicons-no-alt" title="Remove validator"></a></td>';
//   		CNF_VALIDATOR_ROW_HTML += '</tr>';
  		
//   	addNewValidatorRow = function addNewValidatorRow(elm, prefix){
//   		var ptable = $(elm).closest('table');
//   		var rowsSize = ptable.find('tbody tr').size();
  		
//   		var ROW_HTML = VALIDATOR_ROW_HTML;
//   		if(prefix == 1){
//   			ROW_HTML = CNF_VALIDATOR_ROW_HTML;
//   		}
  			
//   		if(rowsSize > 0){
//   			ptable.find('tbody tr:last').after(ROW_HTML);
//   		}else{
//   			ptable.find('tbody').append(ROW_HTML);
//   		}
//   	}
  	
//   	removeValidatorRow = function removeValidatorRow(elm, prefix){
//   		var ptable = $(elm).closest('table');
//   		$(elm).closest('tr').remove();
//   		var rowsSize = ptable.find('tbody tr').size();
  		
//   		var ROW_HTML = VALIDATOR_ROW_HTML;
//   		if(prefix == 1){
//   			ROW_HTML = CNF_VALIDATOR_ROW_HTML;
//   		}
  			
//   		if(rowsSize == 0){
//   			ptable.find('tbody').append(ROW_HTML);
//   		}
//   	}
//      /*------------------------------------
//   	*---- Custom Validations - END -----
//   	*------------------------------------*/


//   	/*--------------------------------------------
//   	*---- Droppable Content Fuctions - Start -----
//   	*---------------------------------------------*/

//   	dragg_features = function dragg_features(id,droppedItem){
//       // block_new_id=id_generator(id);
//       // if(droppedItem.hasClass('column_layout')!=true){
//       //   id = id.replace(/\d+/g, '');
//       // }
//       // else{
//       //   droppedItem.removeClass('column_layout').addClass('col_struct');
//       // }
//       // var data_content=template_functions(id);
//       // if(id=='one_column'|| id=='two_column' || id=='three_column' || id=='four_column' ){
//       //   // droppedItem.attr('id',block_new_id).addClass('col_class').html(data_content);
//       //   droppedItem.data('block-id',block_new_id).addClass('col_class').html(data_content);
//       // }
//       // else{
//       //   // droppedItem.attr('id',block_new_id).html(data_content);
//       //   droppedItem.data('block-id',block_new_id).html(data_content);
//       // }
//     }

//       /*--------------------------------------------
//       *---- Droppable Content Fuctions - End -------
//       *---------------------------------------------*/

//       /*--------------------------------------------------------------
//       *---- Function to return Template of Dropped Block - Start -----
//       *--------------------------------------------------------------*/
   	

//     function template_functions(id){
//    		var user_data=thwec_var['userdata'];
//    		var image_folder = thwec_var['image_folder_path'];
//       // if(["one_column", "two_column", "three_column","four_column"].includes(id)){
//       //   var block_content = '<div class="thec-icon-panel"><span class="dashicons dashicons-edit icon_props edit_icon" style="position:relative;float:right;"></span>';
//       //   block_content+= '<span class="thec-block-handle thwec_icons" style="position:relative;background-color:gray;border-radius:2px;width:30px;margin:auto 0;font-size: 14px;line-height: 20px;padding: 2px 10px;z-index:999;">Drag</span>';
//       //   block_content+= '<span class="dashicons dashicons-admin-page icon_props clone_icon thwec_icons" style="float:right;"></span><span class="dashicons dashicons-trash icon_props delete_icon" style="position:relative;float:right;"></span></div>';
//       // }
//       // else{
//         var block_content = '<div class="thec-icon-panel"><span class="dashicons dashicons-edit icon_props edit_icon" style="position:relative;"></span>';
//         block_content+= '<span class="dashicons dashicons-admin-page icon_props clone_icon thwec_icons"></span><span class="dashicons dashicons-trash icon_props delete_icon" style="position:relative;"></span>';
//         block_content+= '<span class="thwec-block-handle thwec_icons" style="position:relative;background-color:gray;border-radius:2px;width:30px;margin:auto 0;font-size: 14px;line-height: 20px;padding: 2px 10px;z-index:999;">Drag</span></div>';
//       // }    
//       if(id=="header_details"){
//         block_content += header_details();
//       }
//       else if(id=="footer_details"){
//         block_content += footer_details();
//       }
//       else if(id=="divider"){
//         block_content += divider();
//       }
//       else if(id=="text"){
//         block_content += text();
//       }
//       else if(id=="image"){
//         block_content += image(image_folder);
//       }
//       else if(id=="customer_details"){
//         block_content += customer_details(user_data);
//       }
//       else if(id=="billing_details"){
//         block_content += billing_details(user_data);
//       }
//       else if(id=="shipping_details"){
//         block_content += shipping_details(user_data);
//       }
//       else if(id=="order_details"){
//         block_content += order_details();
//       }
//       else if(id=="social"){
//         block_content += social(image_folder);
//       }
//       else if(id=="button"){
//         block_content += button();
//       }
//       else if(id=="video"){
//         block_content += video();
//       }
//       else if(id=="gif"){
//         block_content += gif();
//       }
//       else if(id=="coupons"){
//         block_content += coupons();
//       }
//       else if(id=="menu"){
//         block_content += menu();
//       }
//       else if(id=="one_column"){
//         block_content += one_column();
//       }
//       else if(id=="two_column"){
//         block_content += two_column();
//       }
//       else if(id=="three_column"){
//         block_content += three_column();
//       }
//       else if(id=="four_column"){
//         block_content += four_column();
//       }
//       return block_content;
//     }
      
//     // function header_details(){
        
//     //   var header_details = '<table class="header_class" cellpadding="0" cellspacing="0" style="background-color:#01796f;overflow: hidden;text-align: center;box-sizing: border-box;position: relative;" width="100%"><tr><td valign="top">';
//     //   header_details += '<table width="100%" cellspacing="0" cellpadding="0"><tr><td><table class="thwec-header-class" style="background-color:#01796f;width:100%;">';
//     //   header_details += '<tr style="display:none;"><td style="padding:0px 48px;"><div id="header_logo_block" style="width:180px;margin:0 auto;padding-top:30px;"></div></td></tr>';
//     //   header_details += '<tr><td class="block-padding-class" style="padding:36px 48px;">';
//     //   // header_details+= '<div class="thec-header-text-block" style="margin:0 auto;">';
//     //   header_details += '<h1 class="thwec-header-title" style="color:#ffffff;font-family:\'Helvetica Neue\',Helvetica,Roboto,Arial,sans-serif;font-size:30px;font-weight:300;line-height:150%;margin:0;text-align:left;padding:0px;">New customer order</h1>';
//     //   header_details += '</td></tr></table></td></tr></table>';
//     //   return header_details;
//     // }
//     // <tr style="display:none;">';
//       // header_details+= '<td style="padding:0px 48px;"><div id="header_logo_block" style="width:180px;margin:0 auto;padding-top:30px;"></div></td></tr>

//     function footer_details(){
      	
//       var footer_details = '<table class="footer_class" border="0" width="100%" cellpadding="10" cellspacing="0" style="background-color:#954aa8;box-sizing: border-box;"><tr><td valign="top" style="padding:0;">';
//      	footer_details+= '<table width="100%" border="0" cellpadding="10" cellspacing="0"><tr><td class="block-padding-class" style="padding:8px 48px 8px 48px;border:0;color:#91fba4;font-family:Arial;font-size:12px;line-height:125%;text-align:center;">';
//       footer_details+= '<div class="thwec-copyright" style="color:white;font-family:Arial;font-size:12px;line-height:125%;text-align:center;"><p>Copyright &copy; '+ thec_current_year +' <b>Company Name</b>. All rights reserved.</p></div>';
//       footer_details+= '<div class="thwec-footer-title"><p style="font-family:Arial;font-size:12px;line-height:125%;">Powered By ThemeHigh</p></div>';
//       footer_details+= '<div class="footer-url" style="font-family:Arial;font-size:12px;line-height:125%;text-align:center;"><a href="#" class="thec-footer-unsubscribe">unsubscribe</a></div>';
//       footer_details+= '</td></tr></table></td></tr></table>';
//       return footer_details;
//     }
//     function divider(){
        	
//       var divider = '<table class="thec-divider" border="0" width="100%" style="/*margin-top:-12px;margin-bottom:-15px;*/"><tr><td valign="top">';
//       divider+= '<table align="center" width="100%" border="0"><tr><td class="block-padding-class"><div class="thec-divider-class"><hr style="border:none;border-top: 1px solid gray;height: 1px;"/></div>';
//       divider+= '</td></tr></table></td></tr></table>';
//       return divider;
//     }
      
//     function text(){
//       var text =	'<table><tr><td valign="top" width="100%">';
//       text+= 	'<table width="100%"><tr><td valign="top">';
//       text+=	'<table align="center" border="0" width="100%"><tr><td class="block-padding-class" style="padding:6px 48px 0">';
//       text+= '<div class="thec-label" style="color:#636363;font-family:\'Helvetica Neue\',Helvetica,Roboto,Arial,sans-serif;font-size:14px;line-height:150%;text-align:left;">';
//       text+= '<p class="thec-text-class" style="text-align:justify;font-size:14px;line-height:150%;color:#636363;">Your order has been received and is now being processed. Your order details are shown below for your reference:</p>'
//       text+= '</div></td></tr></table>';
//       text+=	'</td></tr></table></td></tr></table>';
//       return text;
//     }
    
//     function image(image_folder){
//       var image = '<div class="image element_class">';
//         	// image+='<div class="button" id="image_upload_btn" style="margin-top:40px;display:none;"><br/></div>';
//       image+= '<table cellspacing="0" cellpadding="0"><tr><td valign="top" width="100%">';
//       image+= '<table class="block_color_table" cellspacing="0" cellpadding="0" width="100%"><tr><td valign="top" align="left">';
//       image+= '<table class="image_table" cellspacing="0" cellpadding="0" border="0" align="center"><tr>';
//       image+= '<td class="block-padding-class" style="padding:0px 0px;"><div id="header_logo_block">';
//       image+= '<img src="'+image_folder+'images/sample_image-3.jpg" style="width:100%;height:100%;display:block;" alt="" />';
//       image+= '</div></td>';
//       image+= '</tr></table></td></tr></table></td></tr></table></div>';
//       return image;
//     } 

//     function customer_details(user_data){
      
//       var customer = '<table id="customer_details" class="thec-details-table" cellspacing="0" cellpadding="0" border="0" style="width: 100%; vertical-align: top;padding:0;" border="0">';
//       customer += '<tr><td class="block-padding-class" style="padding:5px 48px; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top" width="50%">';      
//       customer += '<h2 class="thec-details-label" style="color:#47f968;display:block;font-family:\'Helvetica Neue\',Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:170%;text-align:center;padding:0px 15px;">Shipping Details</h2>';
//         // var billing = '<table class="thec-billing-class" border="0" width="100%"><tr><td valign="top" align="left">';
//        //  billing+= '<table align="center"  border="0" style="text-align:left;width:100%;"><tr><td class="block-padding-class">';
//        //  billing+= '<table align="center" class="thec-details-style" border="0"><tr><td><div class="thec-label" style=" font-size: 14px;line-height: 150%;margin-bottom: -2px;"><h2 style="font-size:18px;line-height:130%;margin:0px;padding:3px;">Billing Details</h2></div>';
//       customer+= '<address class="address customer_details_address" style="text-align:center;padding:0px 15px;line-height:150%;border:0px !important;">'+user_data["first_name"]+" "+user_data["last_name"]+'<br />';
//       customer+= ''+user_data["user_email"]+'</address></td></tr></address></td></tr></table>';                        
//        //  billing+= '</td></tr></table></td></tr></table>';
//       return customer;
//     } 

//     function billing_details(user_data){
      
//       var billing = '<table cellpadding="0" cellspacing="0"><tr><td><table id="billing_address" class="thec-details-table" cellspacing="0" cellpadding="0" border="0" style="width: 100%; vertical-align: top; padding-bottom: 15px;" border="0">';
//       billing += '<tr><td class="block-padding-class" style="padding:5px 48px; font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top" width="50%">';     	
//       billing += '<h2 class="thec-details-label" style="color:#47f968;display:block;font-family:\'Helvetica Neue\',Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:170%;text-align:center;padding:0px 15px;">Billing Details</h2>';
//       	// var billing = '<table class="thec-billing-class" border="0" width="100%"><tr><td valign="top" align="left">';
//      	 //  billing+= '<table align="center"  border="0" style="text-align:left;width:100%;"><tr><td class="block-padding-class">';
//      	 //  billing+= '<table align="center" class="thec-details-style" border="0"><tr><td><div class="thec-label" style=" font-size: 14px;line-height: 150%;margin-bottom: -2px;"><h2 style="font-size:18px;line-height:130%;margin:0px;padding:3px;">Billing Details</h2></div>';
//       billing+= '<address class="address billing_details_address" style="text-align:center;padding:0px 15px;line-height:150%;border:0px !important;">'+user_data["billing_first_name"]+" "+user_data["billing_last_name"]+'<br />';
//      	billing+= ''+user_data["billing_company"]+'<br />';
//      	billing+= ''+user_data["billing_address_1"]+'<br />';
//      	billing+= ''+user_data["billing_city"]+'<br />'+user_data["billing_state"]+'<br />';
//      	billing+= ''+user_data["billing_country"]+'<br />'+user_data["billing_postcode"]+'</address></td></tr></table></td></tr></table>';    	 	    	 	    	 	
//      	 //  billing+= '</td></tr></table></td></tr></table>';
//       return billing;
//     } 

//     function shipping_details(user_data){
          
//       var shipping = '<table id="shipping_address" class="thec-details-table" cellspacing="0" cellpadding="0" border="0" style="width: 100%; vertical-align: top; padding-bottom: 15px;" border="0">';
//       shipping += '<tr><td class="block-padding-class" style="padding:5px 48px;font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; border:0; padding:0;" valign="top" width="50%">';      
//       shipping += '<h2 class="thec-details-label" style="color:#47f968;display:block;font-family:\'Helvetica Neue\',Helvetica,Roboto,Arial,sans-serif;font-size:18px;font-weight:bold;line-height:170%;text-align:center;padding:0px 15px;">Shipping Detials</h2>';
//       // var shipping = '<table class="thec-shipping-class" border="0" width="100%"><tr><td valign="top" align="left">';
//       // shipping+= '<table align="center"  border="0" style="text-align:left;"width:100%;><tr><td class="block-padding-class">';
//       // shipping+= '<table align="center" class="thec-details-style" border="0"><tr><td><div class="thec-label" style=" font-size: 14px;line-height: 150%;margin-bottom: -2px;"><h2 style="font-size:18px;line-height:130%;margin:0px;padding:3px;">Shipping Details</h2></div>';
//       shipping+= '<address class="address shipping_details_address" style="text-align:center;padding:0px 15px;line-height:150%;border:0px !important;">'+user_data["billing_first_name"]+" "+user_data["billing_last_name"]+'<br />';
//       shipping+= ''+user_data["shipping_company"]+'<br />';
//       shipping+= ''+user_data["shipping_address_1"]+'<br />';
//       shipping+= ''+user_data["shipping_city"]+'<br />'+user_data["shipping_state"]+'<br />';
//       shipping+= ''+user_data["shipping_country"]+'<br />'+user_data["shipping_postcode"]+'</address></td></tr></table>';                       
//       // shipping+= '</td></tr></table></td></tr></table>';
//       return shipping;        
//     }

//     function order_details(){
    
//       var order = '<div class="label"></div>';
//       order+= '<table class="thwec-table" width="100%"><tr><td style="padding:0px 48px 0;" class="block-padding-class">';
//       order+= '<h2 style="font-size:18px;text-align:left;line-height:130%;" class="thec-order-details-heading" style="color: #4286f4;"><u>Order #248</u> (November 22, 017)</h2>';
//       order+= '<table class="thec-order-table" border="1" cellpadding="10px" style="width:100%;padding-top: 10px;padding-bottom:10px;margin-top: 10px;margin-bottom:30px;border-collapse: collapse;" class="thec-details-style"><tr><th>Product</th><th>Quantity</th><th>Price</th></tr>';
//       order+='<tr><td>Party Wear</td><td>1</td><td>&#x20B9;1500</td></tr>';
//       order+='<tr><td>Casual Shirt</td><td>1</td><td>&#x20B9;500</td></tr>';
//       order+='<tr><td>Rolex Watch</td><td>1</td><td>&#x20B9;6000</td></tr>';
//       order+='<tr><th colspan="2">Subtotal</th><td>&#x20B9;500</td></tr>';
//       order+='<tr><th colspan="2">Shipping</th><td>&#x20B9;500</td></tr>';
//       order+='<tr><th colspan="2">Payment Method</th><td>Cash On Delivery</td></tr>';
//       order+='<tr><th colspan="2">Total</th><td>&#x20B9;8000</td></tr>';
//       order+= '</table></td></tr></table>';
//       return order;
//     } 
      	
//     function social(image_folder){
//       	var social = '<div class="social element_class">';
//         social+= '<table cellspacing="0" cellpadding="0" border="0" width="100%"><tr><td valign="top">';
//         social+= '<table cellspacing="0" cellpadding="5" align="center" class="social_icons_table" border="0"><tr><td class="block-padding-class">';
//         social+= '<div class="thec_social_icon" style="width: 17%;display: inline-block;padding-left: 0px;padding-right:10px;margin: 0px;"><a href="http://www.facebook.com" class="facebook" style="text-decoration:none;box-shadow:none;"><img src="'+image_folder+'/images/fb_icon.png" style="width: 100%;height: 100%;display:block;"></a></div>';
//         social+= '<div class="thec_social_icon" style="width: 17%;display: inline-block;padding-left: 0px;padding-right:10px;margin: 0px;"><a href="http://www.mail.google.com" class="gmail" style="text-decoration:none;box-shadow:none;"><img src="'+image_folder+'/images/gmail.png" style="width: 100%;height: 100%;display:block;"></a></div>';
//         social+= '<div class="thec_social_icon" style="width: 17%;display: inline-block;padding-left: 0px;padding-right:10px;margin: 0px;"><a href="http://www.twitter.com" class="twitter" style="text-decoration:none;box-shadow:none;"><img src="'+image_folder+'/images/twitter_icon.png" style="width: 100%;height: 100%;display:block;"></a></div>';
//         social+= '<div class="thec_social_icon" style="width: 17%;display: inline-block;padding-left: 0px;padding-right:10px;margin: 0px;"><a href="http://www.youtube.com" class="youtube" style="text-decoration:none;box-shadow:none;"><img src="'+image_folder+'/images/youtube_icon.png" style="width: 100%;height: 100%;display:block;"></a></div>';
//         social+= '</td></tr></table></td></tr></table></div>';
//       	return social;
//     }        
//     function button(){
//       var button = '<div class="ui_button element_class">';
//      	button+= '<table class="" border="0" width="100%" cellpadding="0" cellspacing="0"><tr><td valign="top">';
//      	button+= '<table align="left" width="100%" cellpadding="0" cellspacing="0" border="0" class="button-table"><tr><td class="block-padding-class" valign="top" align="left" style="padding:0">';
//      	// button+= '<div class="btn_wrapper" style="display:inline-block;"><div class="thec-label" style="width: 72%;padding: 10px;display: inline-block;vertical-align:middle;"><p style="text-align:justify;">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.</p></div>';
//       button += '<div class="url_button" style="text-align: center;border: 1px solid royalblue;padding: 10px 0px;margin: auto;width: 100%;background-color: royalblue;border-radius: 2px;">';
//       button += '<a href="#" alt="" class="thec-button" style="color: #fff;line-height: 150%;font-size: 13px; text-decoration: none;box-shadow:none;">Click Here</a></div>';
//      	button+= '</div></td></tr></table></td></tr></table></div>';
//       return button;
//     }
//     function video(){
//       	var video = '<div class="ui_button element_class">';
//      	video+= '<table cellpadding="0" cellspacing="0" border="0" width="100%"><tr><td valign="top">';
//      	video+= '<table cellpadding="0" cellspacing="0" align="left" width="100%" border="0"><tr><td valign="top" align="left">';
//      	video+= '<table cellpadding="0" cellspacing="0" class="thec-video-class" align="center"><tr><td><div class="thec-label" style="text-align:left;position:relative;width:355px;height:200px;">';
//         video+= '<iframe class= "block-video" width="100%" height="100%" style="position:absolute;top:0;left:0;bottom:0;right:0;" src="https://www.youtube.com/embed/8OBfr46Y0cQ" frameborder="0" allowfullscreen></iframe></div></td></tr></table>';
//      	// video+= '<video width="854" height="480" autoplay><source class="block-video" src="https://www.youtube.com/embed/8OBfr46Y0cQ" ></video></div></td></tr></table>';
//      	video+= '</td></tr></table></td></tr></table></div>';
//       	return video;
//     }
//     function gif(){
//         var gif = '<div class="element_class">';
//         gif+= '<table cellpadding="0" cellspacing="0" class="thec-gif-class" border="0" width="100%"><tr><td valign="top">';
//         gif+= '<table cellpadding="0" cellspacing="0" align="left" width="100%" border="0"><tr><td valign="top" align="left">';
//         gif+= '<table cellpadding="0" cellspacing="0" align="center" class="gif-table" width="100%"><tr><td><div class="thec-label" style="text-align:center;width:100%;margin:auto;">';
//         gif+= '<img class="block-gif" style="width:100%;height:100%;display:block;" src="https://media.giphy.com/media/Z5W9H5DtCWN4k/giphy.gif"></div></td></tr></table>';
//         gif+= '</td></tr></table></td></tr></table></div>';
//         return gif;
//     }
//     function coupons(){
//         var coupon = '<div class="element_class">';
//         coupon+= '<table class="thec-coupon-class" border="0" width="100%"><tr><td valign="top">';
//         coupon+= '<table align="left" width="100%" border="0"><tr><td valign="top" align="left">';
//         coupon+= '<table align="center" class="" style="width:100%;box-sizing:border-box;"><tr><td>';
//         coupon+= '<div class="coupon-block" style="width:100%;box-sizing:border-box;color:white;border: 1px dashed red ;overflow: hidden;border-width:3px;background-color: white;">';
//         coupon+= '<div class="thec-label" style="text-align:center;min-width:70%;float:right;min-height:160px;background-color:#6699ff;"><p class="coupon-name" style="line-height:10px;">Sample Coupon</p><p class="off-percentage-text" style="font-size:2em;line-height:14px;font-weight:bold;color:red;">Save Upto 40% </p>';
//         coupon+= '<p>This is a small description about coupon</p><p>Promo Code <span class="promo-code" style="background-color:white;padding:5px 6px;margin:1px 5px;color:black;font-weight:bold;">MR34GH9I4K1</span></div>';
//         coupon+= '<div class="image-box" style="float:left;width:25%;"><img class="block-image" style="width:100%;height:100%;margin: 5px 10px;" src="http://lofrev.net/wp-content/photos/2017/03/amazon_logo.png"></div></div></td></tr></table>';
//         coupon+= '</td></tr></table></td></tr></table></div>';
//         return coupon;
//     }
//     function menu(){
//         var menu = '<div class="element_class">';
//         menu+= '<table class="menu_class" border="0" width="100%" cellpadding="0px" cellspacing="0px"><tr><td valign="top" class="block-padding-class">';
//         menu+= '<ul class="thec-menu-list"  style="list-style-type: none;margin: 0;padding: 0;overflow: hidden;background-color: #333;"><li class="item1" style=" float: left;margin-bottom: 0px;color:white;"><a href="" class="menu-item-link" style="display: block;color: inherit;text-align: center;padding: 14px 16px;text-decoration: none;line-height:10px;">Item 1</a></li>';
//         menu+= '<li class="item2" style=" float: left;margin-bottom: 0px;color:white;"><a href="" class="menu-item-link" style="display: block;color: inherit;text-align: center;padding: 14px 16px;text-decoration: none;line-height:10px;">Item 2</a></li>';
//         menu+= '<li class="item3" style=" float: left;margin-bottom: 0px;color:white;"><a href="" class="menu-item-link" style="display: block;color: inherit;text-align: center;padding: 14px 16px;text-decoration: none;line-height:10px;">Item 3</a></li>';
//         menu+= '<li class="item4" style=" float: left;margin-bottom: 0px;color:white;"><a href="" class="menu-item-link" style="display: block;color: inherit;text-align: center;padding: 14px 16px;text-decoration: none;line-height:10px;">Item 4</a></li>';
//         menu+= '<li class="item5" style=" float: left;margin-bottom: 0px;color:white;"><a href="" class="menu-item-link" style="display: block;color: inherit;text-align: center;padding: 14px 16px;text-decoration: none;line-height:10px;">Item 5</a></li></ul>';
//         menu+= '</td></tr></table></div>';
//         return menu;
//     }
      
//       // ---------------------------------------------- Layout Stuctures ---------------------------------------------------------------

//       function one_column(){

//           var col_structure = '<table width="100%" cellpadding="0" cellspacing = "0" class="block-padding-class"><tr>';
//           col_structure += '<td width="100%" valign="top" class="col1-col1" style="padding:10px 6px;"><div class="col1 cols" data-number="1"></div></td>';
//           col_structure += '</tr></table>';
//           return col_structure;        
//       }

//       function two_column(){
//           var col_structure  = '<table width="100%" cellpadding="0" cellspacing = "0" class="block-padding-class"><tr>';
//           col_structure += '<td width="50%" valign="top" class="col2-col1" style="padding:6px 6px !important;"><div class="col2 cols" data-number="2"></div></td>';
//           col_structure += '<td width="50%" valign="top" class="col2-col2" style="padding:6px 6px !important;"><div class="col2 cols" data-number="2"></div></td></tr></table>';
//           return col_structure;        
//       }

//       function three_column(){
//           var col_structure = '<table width="100%" cellpadding="0" cellspacing = "0" class="block-padding-class"><tr>';
//           col_structure += '<td width="32%" valign="top" class="col3-col1" style="padding:6px 6px;"><div class="col3 cols" data-number="3"></div></td>';
//           col_structure += '<td width="32%" valign="top" class="col3-col2" style="padding:6px 6px;"><div class="col3 cols" data-number="3"></div></td>';
//           col_structure += '<td width="32%" valign="top" class="col3-col3" style="padding:6px 6px;"><div class="col3 cols" data-number="3"></div></td></tr></table>';
//           return col_structure;
//       }	

//       function four_column(){
          
//           var col_structure = '<table width="100%" cellpadding="0" cellspacing = "0" class="block-padding-class"><tr>';            
//           col_structure += '<td width="23.7%" valign="top" class="col4-col1" style="padding:6px 6px;"><div class="col4 cols" data-number="4"></div></td>';
//           col_structure += '<td width="23.7%" valign="top" class="col4-col2" style="padding:6px 6px;"><div class="col4 cols" data-number="4"></div></td>';
//           col_structure += '<td width="23.7%" valign="top" class="col4-col3" style="padding:6px 6px;"><div class="col4 cols" data-number="4"></div></td>';
//           col_structure += '<td width="23.7%" valign="top" class="col4-col4" style="padding:6px 6px;"><div class="col4 cols" data-number="4"></div></td></tr></table>';
//           return col_structure;
//       }

//       /*--------------------------------------------------------------
//       *---- Function to return Template of Dropped Block - END -----
//       *--------------------------------------------------------------*/

//       /*--------------------------------------------
//       *---- Random ID Generator Fuction - START ----
//       *---------------------------------------------*/


//   	id_generator = function id_generator(id){
//       	count++;
//       	data = id+count;
//       	// console.log(data);
//       	return data;
//    	}

//       /*--------------------------------------------
//       *---- Random ID Generator Fuction - END ------
//       *---------------------------------------------*/


//       /*--------------------------------------------
//       *---- Popup Form Save Fuction - START --------
//       *---------------------------------------------*/


//   	dialog_save_form = function dialog_save_form(dataid,block_object){
//    		  // var data=[];  
//        //  var pop_input = $('#popup_form');
//        //  pop_input.find(':input').each(function(){      
//        //    data_attributes =  set_field_values($(this));
//        //    if(data_attributes){
//        //      data.push(data_attributes);
//        //    }
//        //  });
//        //  // var data_block=$('#'+dataid);
//        //  set_up_hidden_fields(data,block_object);
//        //  view_changes(pop_input,block_object);
//    	}

//     function set_up_hidden_fields(data,block_object){
//       var data_length = data.length; 
//       var i=0;
//       var hidden_block = block_object.closest('.ui-draggable');
//       if(hidden_block.find('>input[type="hidden"]').length > 0 ){
//         $.each(data, function(key, value) {
//           hidden_block.find('input[type="hidden"][name="'+data[i]['name']+'"]').val(data[i]['value']);
//           i <= data_length ? i++ : '';
//         });
//       }else{
//         $.each(data, function(key, value) { 
//           $('<input type="hidden" name="'+data[i]['name']+'" value="'+data[i]['value']+'" data-type="'+data[i]['type']+'"/>').appendTo(hidden_block);
//           i<=data_length ? i++ : '';
//         });
//       }
//     }


//     function set_field_values($input_obj){
//       var data_attributes ={};
//       if(typeof($input_obj.attr('type')) === 'undefined'){
//         data_attributes['name']  = $input_obj.attr('name');
//         data_attributes['type']  = 'textarea';
//         data_attributes['value'] = $input_obj.val();
//       }
//       else if($input_obj.attr('type')=='radio' && $input_obj.is(':checked')){
//         data_attributes['name']  = $input_obj.attr('name');
//         data_attributes['type']  = $input_obj.attr('type');
//         data_attributes['value'] = $input_obj.val();
//       }
//       else if($input_obj.attr('type')!='radio'){
//         data_attributes['name']  = $input_obj.attr('name');
//         data_attributes['type']  = $input_obj.attr('type');
//         data_attributes['value'] = $input_obj.val();
//       }
//       // else{

//       // } 
//       if(data_attributes['name']){
//         return data_attributes;
//       }
//     }
//       /*--------------------------------------------
//       *---- Popup Form Save Fuction - END ----------
//       *---------------------------------------------*/

//       /*-------------------------------------------------------------------------------------
//       *---- Preparing popup to choose table based on ID of the Block Fuction - START --------
//       *------------------------------------------------------------------------------------*/


//     thwec_prepare_popup = function thwec_prepare_popup(dataid){
//       var table_block_id = dataid.replace(/\d+/g, '');
//       if(["one_column", "two_column", "three_column","four_column"].includes(table_block_id)){
//         $('#dialog_form').find('.thwec_field_form_general').html($('#thwec_field_form_id_row').html());
//       }
//       else{
//         $('#dialog_form').find('.thwec_field_form_general').html($('#thwec_field_form_id_'+table_block_id).html());
//       }  
//     }

//       /*-------------------------------------------------------------------------------------
//       *---- Preparing popup to choose table based on ID of the Block Fuction - END ---------
//       *------------------------------------------------------------------------------------*/

//       /*------------------------------------------------------------------------------------
//       *---- Function to View Changes to Blocks with respect to the POPUP FORM  - START -----
//       *------------------------------------------------------------------------------------*/

//   	function view_changes(pop_input,block_object){
//       var data_block = block_object.closest('.ui-draggable');
      
//       var text = data_block.find('input[name="i_block_text"]').val();
//       var sub_text = data_block.find('input[name="i_block_sub_text"]').val();
//       var base = data_block.find('input[name="i_block_base_color"]').val();
//       var main_text_color = data_block.find('input[name="i_block_main_font_color"]').val();
//       var sub_text_color = data_block.find('input[name="i_block_sub_font_color"]').val();
//       var main_text_size = data_block.find('input[name="i_block_main_font_size"]').val();
//       var sub_text_size = data_block.find('input[name="i_block_sub_font_size"]').val();
//       var align = data_block.find('input[name="i_block-text-align-value"]').val();
//       var pad_top = data_block.find('input[name="i_block-padding-top"]').val();
//       var pad_right = data_block.find('input[name="i_block-padding-right"]').val();
//       var pad_bottom = data_block.find('input[name="i_block-padding-bottom"]').val();
//       var pad_left = data_block.find('input[name="i_block-padding-left"]').val(); 
//       // console.log(pad_top+' '+pad_right+' '+pad_bottom+' '+pad_left);                    
//       // pad_top     =   pad_top != '' ?   pad_top : 0;
//       // pad_right   =   pad_right != '' ? pad_right : 0;
//       // pad_bottom  =   pad_bottom != '' ? pad_bottom  : 0;
//       // pad_left    =   pad_left != '' ?  pad_left : 0;   
//       // align != '' ? align = align : align = "center";
//       // alert(align);
//       //------------------------ Changes in ** HEADER ** as per the Pop-up Values ----------------------------
      
//       if(temp_id == 'header_details'){
//         var img_option = data_block.find('input[name="i_image-option"]').val();
//         var align_img = data_block.find('input[name="i_block-image-align-value"]').val();
//       	var img = data_block.find('input[name="i_block_image_url"]').val();
//         img = img == '' ? '' : img ; 
//         main_text_color != null ? data_block.find('.thwec-header-title').css("color",main_text_color) : '';   
//         text != null ? data_block.find('.thwec-header-title').html(text): '';   
//         base != null ? data_block.find('.thwec-header-class').css('background-color',base): '';    
//         main_text_size != null ? data_block.find('.thwec-header-title').css('font-size',main_text_size+'px'): '';   
//         align != null ? data_block.find('.thwec-header-title').css('text-align',align): '';   
//         data_block.find('.block-padding-class').css("padding-top",pad_top+'px');
//         data_block.find('.block-padding-class').css("padding-right",pad_right+'px');
//         data_block.find('.block-padding-class').css("padding-bottom",pad_bottom+'px');
//         data_block.find('.block-padding-class').css("padding-left",pad_left+'px');  
//         if(align_img){                  
//           if(img!=''){
//             data_block.find('.thec-header-text-block').css({'width' :'50%',}); 
//             data_block.find('#header_logo_block').css('float',align_img);
//           }
//       	}
//         if(img!='' && img_option == 'checked'){
//           if(data_block.find('#header_logo_block').find('img').length >0){
//             data_block.find('#header_logo_block').find('img').attr('src',img);
//           }
//           else{
//             data_block.find('#header_logo_block').html('<img style="width:100%;height:100%;border:1px solid white;border-radius:4px;" src="'+img+'">');
//             data_block.find('#header_logo_block').closest('tr').css('display','table-row');
//           }
//         }
//         else if(img_option == ''){
//           data_block.find('#header_logo_block').find('img').remove();
//           data_block.find('#header_logo_block').closest('tr').css('display','none');
//         }
//       }

//       // ------------------------ Changes in **  FOOTER ** as per the Pop-up Values ----------------------------


//     	else if(temp_id == 'footer_details'){
//         text != null ? data_block.find('.thwec-footer-title p').html(text) : '';   
//         var subtext_align = data_block.find('input[name="i_block-subtext-align-value"]').val();
//         var link_align = data_block.find('input[name="i_block-link-align-value"]').val();
//         if(sub_text){
//           sub_text = sub_text.replace('[CopyRight]',' Copyright &copy; ');
//           sub_text = sub_text.replace('[Date]',thec_current_year);
//           data_block.find('.thwec-copyright p').html(sub_text);                
//         }
//         base != null ? data_block.find('.footer_class').css('background-color',base): '';   
//         main_text_color != null ? data_block.find('.thwec-footer-title p').css("color",main_text_color) : '';   
//         main_text_size != null ? data_block.find('.thwec-footer-title p').css("font-size",main_text_size+'px') : '';   
//         sub_text_size != null ? data_block.find('.thwec-copyright p').css("font-size",sub_text_size+'px') : '';   
//         sub_text_color != null ? data_block.find('.thwec-copyright p').css("color",sub_text_color) : '';   
//         subtext_align != '' ? data_block.find('.thwec-copyright').css("text-align",subtext_align) : '';  
//         link_align != '' ? data_block.find('.footer-url').css("text-align",link_align) : ''; 
//         align != '' ? data_block.find('.thwec-footer-title').css("text-align",align) : '';   
//        var block_link_color = pop_input.find('input[name="i_block-hyperlink-color"]').val();
//         var block_url = pop_input.find('input[name="i_block-hyperlink-url"]').val();
                  
//         block_link_color != null ? data_block.find('.thec-footer-unsubscribe')[0].style.setProperty('color',block_link_color,'important') : '';   
//         block_url != null ?  data_block.find('.thec-footer-unsubscribe').attr('href',block_url) : '';   
          
//         data_block.find('.block-padding-class').css("padding-top",pad_top+'px');
//         data_block.find('.block-padding-class').css("padding-right",pad_right+'px');
//         data_block.find('.block-padding-class').css("padding-bottom",pad_bottom+'px');
//         data_block.find('.block-padding-class').css("padding-left",pad_left+'px');
//     	}

//       // ------------------------ Changes in ** IMAGE ** Block as per the Pop-up Values ----------------------------


//       else if(temp_id == 'image'){
//         var img_size = data_block.find('input[name="i_block-range"]').val();
//         var img = $('#popup_form').find('input[name="i_block_image_url"]').val();
//         // alert(img);
//         data_block.find('img').attr('src',img);
//         img_size != '' ? data_block.find('.image_table').css('width',img_size+'%') : '' ;
//         align != '' ? data_block.find('.image_table').attr('align',align) : '' ;
//         base != '' ? data_block.find('.block_color_table').css('background-color',base) : '' ;
//         data_block.find('.block-padding-class').css("padding-top",pad_top+'px');
//         data_block.find('.block-padding-class').css("padding-right",pad_right+'px');
//         data_block.find('.block-padding-class').css("padding-bottom",pad_bottom+'px');
//         data_block.find('.block-padding-class').css("padding-left",pad_left+'px');
//       }

          
//       // ------------------------ Changes in ** SOCIAL ** Block as per the Pop-up Values ----------------------------


//       else if(temp_id == 'social'){
//         var facebook_url = data_block.find('input[name="i_block_social_url1"]').val();
//         var fb_visibility = data_block.find('input[name="i_social-url-option1"]').val();  
//         var gmail_url = data_block.find('input[name="i_block_social_url2"]').val();
//         var gmail_visibility = data_block.find('input[name="i_social-url-option2"]').val();            
//         var twitter_url = data_block.find('input[name="i_block_social_url3"]').val();
//         var twitter_visibility = data_block.find('input[name="i_social-url-option3"]').val();            
//         var youtube_url = data_block.find('input[name="i_block_social_url4"]').val();
//         var youtube_visibility = data_block.find('input[name="i_social-url-option4"]').val();            
//         var icon_size = data_block.find('input[name="i_block_main_font_size"]').val();
//         // console.log(fb_visibility+' - '+gmail_visibility+' - '+twitter_visibility+' - '+youtube_visibility);

//         data_block.find('.facebook').attr("href",facebook_url).closest('.thec_social_icon').css('display',fb_visibility); 
//         data_block.find('.gmail').attr("href",gmail_url).closest('.thec_social_icon').css('display',gmail_visibility); 
//         data_block.find('.twitter').attr("href",twitter_url).closest('.thec_social_icon').css('display',twitter_visibility); 
//         data_block.find('.youtube').attr("href",youtube_url).closest('.thec_social_icon').css('display',youtube_visibility); 
//         // data_block.find('.gmail').css('display',gmail_visibility).attr("href",gmail_url); 
//         // data_block.find('.twitter').css('display',twitter_visibility).attr("href",twitter_url); 
//         // data_block.find('.youtube').css('display',youtube_visibility).attr("href",youtube_url);
//         base != '' ? data_block.find('.social_icons_table').css('background-color',base) : '';
//         align != '' ? data_block.find('.social_icons_table tr td:first').attr('align',align) : '';  
//         icon_size != '' ? data_block.find('.thec_social_icon').css('width',icon_size+'%'): '' ;
//         pad_top != '' ? data_block.find('.block-padding-class').css("padding-top",pad_top+'px') : '';
//         pad_right != '' ? data_block.find('.block-padding-class').css("padding-right",pad_right+'px') : '';
//         pad_bottom != '' ? data_block.find('.block-padding-class').css("padding-bottom",pad_bottom+'px') : '';
//         pad_left != '' ? data_block.find('.block-padding-class').css("padding-left",pad_left+'px') : '';
//       }

   
//       // -------------- Changes in ** CUSTOMER , BILLING & SHIPPING ** Block  as per the Pop-up Values ----------


//       else if(["customer_details", "billing_details", "shipping_details"].includes(temp_id)){
//           // console.log(data_block);
//         text!='' ? data_block.find('.thec-details-label').html(text):'';  
//         base != '' ? data_block.find('.thec-details-table').css('background-color',base): '';   
//         data_block.find('.thec-details-label')[0].style.setProperty("color",main_text_color,'important');  
//         data_block.find('.thec-details-label').css("font-size",main_text_size+'px');
//         // sub_text_color !='' ? data_block.find('.address').css('color:'+sub_text_color+'!important') : '';
//         mydiv = document.getElementById(data_block.attr('id'));
//         // mydiv.getElementsByClassName('address').style.setProperty('color',sub_text_color,'!important');
//         sub_text_color != '' ? data_block.find('.address')[0].style.setProperty('color',sub_text_color,'important') : '' ;
//         data_block.find('.address').css("font-size",sub_text_size+'px');
//         align != '' ? data_block.find('.thec-details-label, .address').css('text-align',align) : '';
//         data_block.find('.block-padding-class').css("padding-top",pad_top+'px');
//         data_block.find('.block-padding-class').css("padding-right",pad_right+'px');
//         data_block.find('.block-padding-class').css("padding-bottom",pad_bottom+'px');
//         data_block.find('.block-padding-class').css("padding-left",pad_left+'px');  
//       }

//       // ------------------------ Changes in ** DIVIDER ** Block as per the Pop-up Values ----------------------------
   

//       else if(temp_id == 'divider'){
//         var border_color = data_block.find('input[name="i_block-content-color"]').val();
//         var border_size = data_block.find('input[name="i_block-content-size"]').val();
//         var element_border = data_block.find('input[name="i_block-radio"]').val();
//         border_size = border_size == '' ? 1 : border_size ;
//         border_color = border_color == '' ? '#000000' : border_color;
//         element_border == '' ? element_border = 'solid' : element_border = element_border;
//         base != '' ?  data_block.find('.thec-divider').css('background-color',base) : '';
//         data_block.find('.thec-divider-class hr').css('border-top',border_size+'px '+element_border+' '+border_color);
//         data_block.find('.block-padding-class').css("padding",pad_top+'px '+pad_right+'px '+pad_bottom+'px '+pad_left+'px');                       
//       }

         
//       // ------------------------ Changes in ** TEXT ** Block as per the Pop-up Values ----------------------------
   

//       else if(temp_id == 'text'){
//           var text = data_block.find('input[name="i_block-textarea"]').val();
//           if(text){
//               data_block.find('.thec-label').html('<p class="thec-text-class">'+text+'</p>');                
//           }
//           data_block.find('.thec-text-class').css('color',main_text_color);
//           data_block.find('.thec-text-class').css('font-size',main_text_size+'px');
//           data_block.find('.thec-text-class').css('text-align',align);
//            data_block.find('.block-padding-class').css("padding-top",pad_top+'px');
//           data_block.find('.block-padding-class').css("padding-right",pad_right+'px');
//           data_block.find('.block-padding-class').css("padding-bottom",pad_bottom+'px');
//           data_block.find('.block-padding-class').css("padding-left",pad_left+'px');  
//           base!='' ? data_block.css('background-color',base) : '';                       
//       }

        
//       // ------------------------ Changes in ** BUTTON ** as per the Pop-up Values ----------------------------


//       else if(temp_id == 'button'){
            
//         var btn_text = data_block.find('input[name="i_block-button-name"]').val();
//         var btn_url_text = data_block.find('input[name="i_block-button-url"]').val();
//         var btn_url_alt_text = data_block.find('input[name="i_block-button-alt-text"]').val();
//         var btn_bg = data_block.find('input[name="i_block-button-bgcolor"]').val();
//         var btn_border = data_block.find('input[name="i_block-button-border-color"]').val();
//         var btn_size = data_block.find('input[name="i_block-range"]').val();
//         var btn_align = data_block.find('input[name="i_block-align-value"]').val();

//         main_text_size == '' ? main_text_size = '20px': main_text_size = main_text_size;
//         main_text_color == '' ? main_text_color = '#000000' : main_text_color = main_text_color;
//         btn_text == '' ? btn_text ="Button" : btn_text = btn_text;
//         btn_bg = btn_bg != '' ? btn_bg : 'royalblue' ;
//         base != '' ? data_block.find('.button-table').css('background-color',base) : '' ; 
//         data_block.find('.thec-button').html(btn_text).attr({'href':btn_url_text,'alt': btn_url_alt_text });
//         data_block.find('.thec-button').css({'font-size':main_text_size+'px','color': main_text_color});
//         data_block.find('.url_button').css({'width':btn_size+'%','float':btn_align,'background-color':btn_bg,'border':'1px solid '+btn_border})
//         data_block.find('.block-padding-class').css('padding',pad_top+'px '+pad_right+'px '+pad_bottom+'px '+pad_left+'px');
//       }

          
//       // ------------------- Changes in ** ORDER DETAILS **  Block as per the Pop-up Values ---------------------
   


//       else if(temp_id == 'order_details'){
//         var table_background = data_block.find('input[name="i_block-button-bgcolor"]').val();
//         data_block.find('.thec-order-details-heading').css('color',main_text_color);
//         data_block.find('.thec-order-details-heading').css('font-size',main_text_size+'px');
//         align != '' ?  data_block.find('.thec-order-details-heading').css('text-align',align) : '';
//         base != '' ? data_block.find('.thwec-table').css('background-color',base) : '' ;
//         table_background != '' ? data_block.find('.thec-order-table').css('background-color',table_background) : '' ;
//         data_block.find('.block-padding-class').css("padding-top",pad_top+'px');
//         data_block.find('.block-padding-class').css("padding-right",pad_right+'px');
//         data_block.find('.block-padding-class').css("padding-bottom",pad_bottom+'px');
//         data_block.find('.block-padding-class').css("padding-left",pad_left+'px');
//       }

   
//       // ---------------------- Changes in ** VIDEO ** Block as per the Pop-up Values --------------------------
        
          
//       else if(temp_id == 'video'){
//         // data_block.find('.thec-label').html('<iframe src="https://giphy.com/embed/Z5W9H5DtCWN4k" width="480" height="405" frameBorder="0" class="giphy-embed" allowFullScreen></iframe><p><a href="https://giphy.com/gifs/animals-being-jerks-tock-Z5W9H5DtCWN4k">via GIPHY</a></p>');
//         var video_size = data_block.find('input[name="i_block-range"]').val();
//         var video_url = data_block.find('input[name="i_block_image_url"]').val();
//         data_block.find('.thec-label').css('width',block_width+'%');

//         var video_url_parsing = video_url.split("/");
//         video_url = video_url_parsing[video_url_parsing.length-1].replace('watch?v=','');    
//         data_block.find('.block-video').attr('src','https://www.youtube.com/embed/'+video_url);
//         base != '' ? data_block.find('.thec-video-class').css('background-color',base) : '';
//         align != '' ? data_block.find('.thec-video-class').attr('align',align) : '';
//         data_block.find('.block-padding-class').css("padding-top",pad_top+'px');
//         data_block.find('.block-padding-class').css("padding-right",pad_right+'px');
//         data_block.find('.block-padding-class').css("padding-bottom",pad_bottom+'px');
//         data_block.find('.block-padding-class').css("padding-left",pad_left+'px');

//       }

         
//       // ------------------------ Changes in ** GIF ** Block as per the Pop-up Values --------------------------
         

//       else if(temp_id == 'gif'){
//         var gif_size = data_block.find('input[name="i_block-range"]').val();
//         var gif_align = data_block.find('input[name="i_block-align-value"]').val();
//         var gif_url = data_block.find('input[name="i_block-gif-url"]').val();
//         gif_url != '' ? gif_url = gif_url : gif_url = 'https://media.giphy.com/media/xT0xeP1oX0sSlD0D4s/giphy.gif';
//         base != '' ? data_block.find('.gif-table').css('background-color',base) : '';
//         data_block.find('.thec-label').css('width',gif_size+'%');
//         data_block.find('.block-gif').attr('src',gif_url);
//         data_block.find('.thec-label').css('float',gif_align);
//         data_block.find('.gif-table').css("padding",pad_top+'px '+pad_right+'px '+pad_bottom+'px '+pad_left+'px');
//       }

//       // ------------------------ Changes in ** MENU ** Block as per the Pop-up Values --------------------------
         
//       else if(temp_id == 'menu'){
//         var menuitem1 = data_block.find('input[name="i_block-menu-text-1"]').val();
//         var menuitem1_visibility = data_block.find('input[name="i_menu-option1"]').val();  
//         var menuitem2 = data_block.find('input[name="i_block-menu-text-2"]').val();
//         var menuitem2_visibility = data_block.find('input[name="i_menu-option2"]').val();            
//         var menuitem3 = data_block.find('input[name="i_block-menu-text-3"]').val();
//         var menuitem3_visibility = data_block.find('input[name="i_menu-option3"]').val();            
//         var menuitem4 = data_block.find('input[name="i_block-menu-text-4"]').val();
//         var menuitem4_visibility = data_block.find('input[name="i_menu-option4"]').val();
//         var menuitem5 = data_block.find('input[name="i_block-menu-text-5"]').val();
//         var menuitem5_visibility = data_block.find('input[name="i_menu-option5"]').val();
        
//         var menu_align = data_block.find('input[name="i_block-align-value"]').val();  
//         var menu_background = data_block.find('input[name="i_block-button-bgcolor"]').val();  

//         menu_align = menu_align == '' ? 'left' : menu_align;
//         main_text_color = main_text_color =='' ? 'white' : main_text_color;
//         pad_top = pad_top == '' ? "14" : pad_top;
//         pad_bottom = pad_bottom == '' ? "14" : pad_bottom;
//         pad_left = pad_left == '' ? "16" : pad_left;
//         pad_right = pad_right == '' ? "16" : pad_right;
//         menuitem1_visibility = menuitem1_visibility == '' ? 'none' : 'block' ;
//         menuitem2_visibility = menuitem2_visibility == '' ? 'none' : 'block' ;
//         menuitem3_visibility = menuitem3_visibility == '' ? 'none' : 'block' ;
//         menuitem4_visibility = menuitem4_visibility == '' ? 'none' : 'block' ;
//         menuitem5_visibility = menuitem5_visibility == '' ? 'none' : 'block' ;

//         data_block.find('.item1 .menu-item-link').html(menuitem1).css('display',menuitem1_visibility); 
//         data_block.find('.item2 .menu-item-link').html(menuitem2).css('display',menuitem2_visibility); 
//         data_block.find('.item3 .menu-item-link').html(menuitem3).css('display',menuitem3_visibility); 
//         data_block.find('.item4 .menu-item-link').html(menuitem4).css('display',menuitem4_visibility); 
//         data_block.find('.item5 .menu-item-link').html(menuitem5).css('display',menuitem5_visibility); 
//         data_block.find('.menu-item-link').parent('li').css({'color':main_text_color,'font-size':main_text_size+'px','float':menu_align});
//         data_block.find('.thec-menu-list').css("padding-top",pad_top+'px');
//         data_block.find('.thec-menu-list').css("padding-right",pad_right+'px');
//         data_block.find('.thec-menu-list').css("padding-bottom",pad_bottom+'px');
//         data_block.find('.thec-menu-list').css("padding-left",pad_left+'px');
//         data_block.find('.block-padding-class').css('background-color',base);
//         menu_background != '' ? data_block.find('ul').css('background-color',menu_background) : '';
//       }

//       else if(["one_column", "two_column", "three_column","four_column"].includes(temp_id)){
//         console.log(data_block.find('> .block-padding-class').length);
//         data_block.find('> .block-padding-class').css("padding-top",pad_top+'px');
//         data_block.find('> .block-padding-class').css("padding-right",pad_right+'px');
//         data_block.find('> .block-padding-class').css("padding-bottom",pad_bottom+'px');
//         data_block.find('> .block-padding-class').css("padding-left",pad_left+'px');
//         var bg_image = data_block.find('> input[name="i_block_image_url"]').val();
//         var image_option = data_block.find('> input[name="i_bg-image-option"]').val();
//         var color_option = data_block.find('> input[name="i_bg-color-option"]').val();
//         if(image_option == 'checked' && color_option == ''){
//           data_block.find('> .block-padding-class').css('background-color','transparent');
//           data_block.find('> .block-padding-class').css('background-image', 'url(' + bg_image + ')');
//         }
//         else if(image_option == '' && color_option == 'checked'){
//           data_block.find('> .block-padding-class').css('background-image', 'none');
//           data_block.find('> .block-padding-class').css('background-color',base);
//         }
//       }

//     } 	 	
//    	/*--------------------------------------------*
//   	*---- Droppable Content Fuctions - End ------*
//   	*---------------------------------------------*/

//       /*---------------------------------------------
//       *---- Image Upload Function Setup - START -----
//       *---------------------------------------------*/

//       setup_image_uploader=function setup_image_uploader(popup_img){
          
//           var frame;
//           frame = wp.media({
//               title: 'Upload Media Of Your Interest',
//               button: {
//                   text: "Let's Choose this" 
//               },
//               multiple: false  // Set to true to allow multiple files to be selected
//           });
//           frame.open();
//           frame.on( 'select', function() { 
//               // Get media attachment details from the frame state
//               var thwec_admin_attachment = frame.state().get('selection').first().toJSON();
//               // console.log(thwec_admin_attachment);
//               if(popup_img =='header_img'){
//                   // $('#header_logo_block').html('<img src="'+thwec_admin_attachment['url']+'"/>').css('display','block');
//                   $('input[name="i_block_image_url"]').val(thwec_admin_attachment['url']);
//               }
//               else if(popup_img == 'image_block'){
//                   $('.thec-image-preview').html('<img src="'+thwec_admin_attachment['url']+'"/><div class="thec-img-description-layer"><p class="thec-img-description"  style="font-size:100%;">Click to Upload</p></div>');
//               }
//           }); 
//       }
  		
//       /*---------------------------------------------
//       *---- Image Upload Function Setup - END  -----
//       *---------------------------------------------*/

//   	return {
//   		addNewValidatorRow : addNewValidatorRow,
//   		removeValidatorRow : removeValidatorRow,
//     };
// }(window.jQuery, window, document));	

// /* Advance Settings */
// function thwecAddNewValidatorRow(elm, prefix){
// 	thwec_settings_advanced.addNewValidatorRow(elm, prefix);
// }
// function thwecRemoveValidatorRow(elm, prefix){
// 	thwec_settings_advanced.removeValidatorRow(elm, prefix);
// }
var thwec_base = (function($, window, document) {
	'use strict';
	
	/* convert string to url slug */
	/*function sanitizeStr( str ) {
		return str.toLowerCase().replace(/[^\w ]+/g,'').replace(/ +/g,'_');
	};	 
	
	function escapeQuote( str ) {
		str = str.replace( /[']/g, '&#39;' );
		str = str.replace( /["]/g, '&#34;' );
		return str;
	}
	
	function unEscapeQuote( str ) {
		str = str.replace( '&#39;', "'" );
		str = str.replace( '&#34;', '"' );
		return str;
	}*/
	
	function escapeHTML(html) {
	   var fn = function(tag) {
		   var charsToReplace = {
			   '&': '&amp;',
			   '<': '&lt;',
			   '>': '&gt;',
			   '"': '&#34;'
		   };
		   return charsToReplace[tag] || tag;
	   }
	   return html.replace(/[&<>"]/g, fn);
	}
	 	 
	function isHtmlIdValid(id) {
		//var re = /^[a-z]+[a-z0-9\_]*$/;
		var re = /^[a-z\_]+[a-z0-9\_]*$/;
		return re.test(id.trim());
	}
	
	function isValidHexColor(value) {      
		if ( preg_match( '/^#[a-f0-9]{6}$/i', value ) ) { // if user insert a HEX color with #     
			return true;
		}     
		return false;
	}
	
	function setup_tiptip_tooltips(){
		var tiptip_args = {
			'attribute': 'data-tip',
			'fadeIn': 50,
			'fadeOut': 50,
			'delay': 200
		};

		$('.tips').tipTip( tiptip_args );
	}
	
	function setup_enhanced_multi_select(parent){
		parent.find('select.thwec-enhanced-multi-select').each(function(){
			if(!$(this).hasClass('enhanced')){
				$(this).select2({
					minimumResultsForSearch: 10,
					allowClear : true,
					placeholder: $(this).data('placeholder')
				}).addClass('enhanced');
			}
		});
	}
	
	function setup_enhanced_multi_select_with_value(parent){
		parent.find('select.thwec-enhanced-multi-select').each(function(){
			if(!$(this).hasClass('enhanced')){
				$(this).select2({
					minimumResultsForSearch: 10,
					allowClear : true,
					placeholder: $(this).data('placeholder')
				}).addClass('enhanced');
				
				var value = $(this).data('value');
				value = value.split(",");
				
				$(this).val(value);
				$(this).trigger('change');
			}
		});
	}
	
	function setup_color_picker(form){
		form.find('.thpladmin-colorpick').iris({
			change: function( event, ui ) {
				$( this ).parent().find( '.thpladmin-colorpickpreview' ).css({ backgroundColor: ui.color.toString() });
			},
			hide: true,
			border: true
		}).click( function() {
			$('.iris-picker').hide();
			$(this ).closest('td').find('.iris-picker').show();
		});
	
		$('body').click( function() {
			$('.iris-picker').hide();
		});
	
		$('.thpladmin-colorpick').click( function( event ) {
			event.stopPropagation();
		});
	}
	
	function setup_color_pick_preview(form){
		form.find('.thpladmin-colorpick').each(function(){
			$(this).parent().find('.thpladmin-colorpickpreview').css({ backgroundColor: this.value });
		});
	}
	
	function setup_popup_tabs(form, selector_prefix){
		$("."+selector_prefix+"-tabs-menu a").click(function(event) {
			event.preventDefault();
			$(this).parent().addClass("current");
			$(this).parent().siblings().removeClass("current");
			var tab = $(this).attr("href");
			$("."+selector_prefix+"-tab-content").not(tab).css("display", "none");
			$(tab).fadeIn();
		});
	}

	// function setup_draggable(elm, target, start_handler){
	// 	$(elm).draggable({
 //        	helper: "clone",
 //        	revert: 'invalid',
 //        	activeClass:"ui-state-active",
 //        	connectToSortable: target,
 //        	cursor: "move",
 //        	opacity: 0.5,
 //      	});
	// }

	// function setup_tinymce(field_name){
	// 	tinymce.init({
 //    		selector: field_name
 //  		});
	// }
	
	/*function open_form_tab(elm, tab_id, form_type){
		var tabs_container = $("#thwepo-tabs-container_"+form_type);
		
		$(elm).parent().addClass("current");
		$(elm).parent().siblings().removeClass("current");
		var tab = $("#"+tab_id+"_"+form_type);
		tabs_container.find(".thpladmin-tab-content").not(tab).css("display", "none");
		$(tab).fadeIn();
	}*/
	
	function prepare_field_order_indexes(elm) {
		$(elm+" tbody tr").each(function(index, el){
			$('input.f_order', el).val( parseInt( $(el).index(elm+" tbody tr") ) );
		});
	}
	
	function setup_sortable_table(parent, elm, left){
		parent.find(elm+" tbody").sortable({
			items:'tr',
			cursor:'move',
			axis:'y',
			handle: 'td.sort',
			scrollSensitivity:40,
			helper:function(e,ui){
				ui.children().each(function(){
					$(this).width($(this).width());
				});
				ui.css('left', left);
				return ui;
			}		
		});	
		
		$(elm+" tbody").on("sortstart", function( event, ui ){
			ui.item.css('background-color','#f6f6f6');										
		});
		$(elm+" tbody").on("sortstop", function( event, ui ){
			ui.item.removeAttr('style');
			prepare_field_order_indexes(elm);
		});
	}
	
	function get_property_field_value(form, type, name){
		var value = '';
		
		switch(type) {
			case 'select':
				value = form.find("select[name=i_"+name+"]").val();
				value = value == null ? '' : value;
				break;
				
			case 'checkbox':
				value = form.find("input[name=i_"+name+"]").prop('checked');
				value = value ? 1 : 0;
				break;
			
			case 'textarea':
				value = form.find("textarea[name=i_"+name+"]").val();
				value = value == null ? '' : value;
				break;
			default:
				value = form.find("input[name=i_"+name+"]").val();
				value = value == null ? '' : value;
		}	
		
		return value;
	}
	
	function set_property_field_value(form, type, name, value, multiple){
		switch(type) {
			case 'select':
				if(multiple == 1 && typeof(value) === 'string'){
					value = value.split(",");
					name = name+"[]";
				}
				form.find('select[name="i_'+name+'"]').val(value);
				break;
				
			case 'checkbox':
				value = value == 1 ? true : false;
				form.find("input[name=i_"+name+"]").prop('checked', value);
				break;
				
			case 'textarea':
				form.find("textarea[name=i_"+name+"]").val(value);
				break;
				
			default:
				form.find("input[name=i_"+name+"]").val(value);
		}	
	}
	
	function convert_rgb2hex(rgb){
 		rgb = rgb.match(/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i);
 		return (rgb && rgb.length === 4) ? "#" +
  		("0" + parseInt(rgb[1],10).toString(16)).slice(-2) +
  		("0" + parseInt(rgb[2],10).toString(16)).slice(-2) +
  		("0" + parseInt(rgb[3],10).toString(16)).slice(-2) : '';
	}

	return {
		escapeHTML : escapeHTML,
		isHtmlIdValid : isHtmlIdValid,
		isValidHexColor : isValidHexColor,
		setup_tiptip_tooltips : setup_tiptip_tooltips,
		setupEnhancedMultiSelect : setup_enhanced_multi_select,
		setupEnhancedMultiSelectWithValue : setup_enhanced_multi_select_with_value,
		setupColorPicker : setup_color_picker,
		setup_color_pick_preview : setup_color_pick_preview,
		setupSortableTable : setup_sortable_table,
		setupPopupTabs : setup_popup_tabs,
		// setup_draggable : setup_draggable,
		// setup_tinymce : setup_tinymce,	
		// openFormTab : open_form_tab,
		get_property_field_value : get_property_field_value,
		set_property_field_value : set_property_field_value,
		// convert_rgb2hex : convert_rgb2hex,
   	};
}(window.jQuery, window, document));

/* Common Functions */
function thwecSetupEnhancedMultiSelectWithValue(elm){
	thwec_base.setupEnhancedMultiSelectWithValue(elm);
}

function thwecSetupSortableTable(parent, elm, left){
	thwec_base.setupSortableTable(parent, elm, left);
}

function thwecSetupPopupTabs(parent, elm, left){
	thwec_base.setupPopupTabs(parent, elm, left);
}

function thwecOpenFormTab(elm, tab_id, form_type){
	thwec_base.openFormTab(elm, tab_id, form_type);
}
function thwec_setup_color_picker(form){
	thwec_base.setupColorPicker(form);
}
// function thwec_convert_rgb2hex(elm){
// 	thwec_base.convert_rgb2hex(elm);
// }
var thwec_tbuilder = (function($, window, document) {
    'use strict';

    var LAYOUT_BLOCKS = new Array('one_column', 'two_column', 'three_column', 'four_column', 'left-large-column', 'right-large-column', 'gallery-column');
    var LAYOUT_COLUMNS = new Array('one_column_one', 'two_column_one', 'two_column_two', 'three_column_one', 'three_column_two', 'three_column_three', 'four_column_one', 'four_column_two', 'four_column_three', 'four_column_four','column_clone');
    var HOOK_FEATURES = new Array('email_header','email_order_details','before_order_table','after_order_table','order_meta','customer_details','email_footer','downloadable_product');
    // var VISIBLE_OPTIONS = new Array('icon1_visibility','icon2_visibility','icon3_visibility','icon4_visibility','icon5_visibility','icon6_visibility','icon7_visibility');
    var IMG_CSS = new Array('bg_image','background');
    var COLUMN_NUMBER = {'one_column':1, 'two_column':2, 'three_column':3, 'four_column':4};
    var NUMBER_TO_WORDS = {1:'one',2:'two',3:'three',4:'four'};
    var preview_wrapper;
    var DRAGG_CLASS;    
    var TRACK_LIST = $('#tb_temp_builder');
    var TBUILDER = $('ul.tracking-list');
    var LAYOUT_OBJ={};
    var confirm_flag = true;
    var POPUP_FLAG = false;
    var BLANK_TD_DATA = '<span class="builder-add-btn btn-add-element"><p>+ Add Element</p></span>';
    var APPEND_FLAG='';
    var BASIC_PROPS = {"width":"33.333333333333336%","b_t":"1px","b_r":"1px","b_b":"1px","b_l":"1px","border_style":"dotted","border_color":"#dddddd"};

    function initialize_tbuilder(){ 
        preview_wrapper = $('#thwec_tbuilder_editor_preview');
        // thwec_base.setup_draggable('.block_element', '.thwec-columns');
        // thwec_base.setup_draggable('.column_layout', '#tb_temp_builder');
        //thwec_base.setup_draggable('.block_hook', '.thwec-columns');
        initialize_css_styles();
        setup_template_builder();
        setup_builder_block_edit_pp();
        setup_track_panel_clicks();
        setup_track_panel_hover();
        setup_popup_clicks();
        setup_builder_element_click();

    }
    /*----------------------------------
     *---- Helper Variabes - START -----
     *----------------------------------*/
    var FONT_LIST = {
        "helvetica" : "'Helvetica Neue',Helvetica,Roboto,Arial,sans-serif",
        "georgia" : "Georgia, serif",
        "times" : "'Times New Roman', Times, serif",
        "arial" : "Arial, Helvetica, sans-serif",
        "arial-black" : "'Arial Black', Gadget, sans-serif",
        "comic-sans" : "'Comic Sans MS', cursive, sans-serif",
        "impact" : "Impact, Charcoal, sans-serif",
        "tahoma" : "Tahoma, Geneva, sans-serif",
        "trebuchet" : "'Trebuchet MS', Helvetica, sans-serif",
        "verdana" : "Verdana, Geneva, sans-serif",
    }
    
    var CSS_PROPS = {   
        p_t : {name : 'padding-top'},
        p_r : {name : 'padding-right'},
        p_b : {name : 'padding-bottom'},
        p_l : {name : 'padding-left'}, 
        m_t : {name : 'margin-top'},
        m_r : {name : 'margin-right'},
        m_b : {name : 'margin-bottom'},
        m_l : {name : 'margin-left'},


        width : {name : 'width'},
        height : {name : 'height'},

        b_t : {name : 'border-top'},
        b_r : {name : 'border-right'},
        b_b : {name : 'border-bottom'},
        b_l : {name : 'border-left'},
        border_style : {name : 'border-style'},
        border_color : {name : 'border-color'},
        border_radius : {name : 'border-radius'},
        bg_image : {name : 'background-image'},
        bg_color     : {name : 'background-color'},
        bg_position : {name : 'background-position'},
        bg_size : {name : 'background-size'},
        bg_repeat : {name : 'background-repeat'},


        color : {name : 'color'},
        font_size : {name : 'font-size'},
        font_weight : {name : 'font-weight'},
        text_align : {name : 'text-align'},
        line_height : {name : 'line-height'},
        font_family : {name : 'font-family'},


        align : {name : 'float'},
        img_width : {name : 'width'},
        img_height : {name : 'height'},
        img_bg_color : {name : 'background-color'},
        img_p_t : {name : 'padding-top'},
        img_p_r : {name : 'padding-right'},
        img_p_b : {name : 'padding-bottom'},
        img_p_l : {name : 'padding-left'},
        img_m_t : {name : 'margin-top'},
        img_m_r : {name : 'margin-right'},
        img_m_b : {name : 'margin-bottom'},
        img_m_l : {name : 'margin-left'},  
        img_border_width_top : {name : 'border-top'},  
        img_border_width_right : {name : 'border-right'},  
        img_border_width_bottom : {name : 'border-bottom'},  
        img_border_width_left : {name : 'border-left'},  
        img_border_style : {name : 'border-style'},  
        img_border_color : {name : 'border-color'},  
        img_border_radius : {name : 'border-radius'},  

        details_color : {name : 'color'},
        details_font_size : {name : 'font-size'},
        details_font_weight : {name : 'font-weight'},
        details_text_align : {name : 'text-align'},
        details_line_height : {name : 'line-height'},
        details_font_family : {name : 'font-family'},


        content_border_width_top : {name : 'border-top'},
        content_border_width_right : {name : 'border-right'},
        content_border_width_bottom : {name : 'border-bottom'},
        content_border_width_left : {name : 'border-left'},
        content_border_style : {name : 'border-style'},
        content_border_color : {name : 'border-color'},
        content_border_radius : {name : 'border-radius'},
        content_width : {name : 'width'},
        content_height : {name : 'height'},
        content_bg_color : {name : 'background-color'},

        content_p_t : {name : 'padding-top'},
        content_p_r : {name : 'padding-right'},
        content_p_b : {name : 'padding-bottom'},
        content_p_l : {name : 'padding-left'},
        content_m_t : {name : 'margin-top'},
        content_m_r : {name : 'margin-right'},
        content_m_b : {name : 'margin-bottom'},
        content_m_l : {name : 'margin-left'},
        cellspacing : {name : 'border-spacing'},
        divider_width :{name : 'border-top-width'},
        divider_color :{name : 'border-top-color'},
        divider_style :{name : 'border-top-style'},
        product_img : {name : 'display'},
        // product_sku : {name : 'display'},
        // bg_image : {name : 'background-image'},
        url1 : {name : 'display'},
        url2 : {name : 'display'},
        url3 : {name : 'display'},
        url4 : {name : 'display'},
        url5 : {name : 'display'},
        url6 : {name : 'display'},
        url7 : {name : 'display'},
        url : {name : 'display'}
        // bg_image_size   : {name : 'background-size'},
        // bg_image_repeat : {name : 'background-repeat'},
    }
    
    var ELM_BLOCK_FORM_PROPS = {
        p_t : {name : 'padding_top', type : 'text'},
        p_r : {name : 'padding_right', type : 'text'},
        p_b : {name : 'padding_bottom', type : 'text'},
        p_l : {name : 'padding_left', type : 'text'}, 
        m_t : {name : 'margin_top', type : 'text'},
        m_r : {name : 'margin_right', type : 'text'},
        m_b : {name : 'margin_bottom', type : 'text'},
        m_l : {name : 'margin_left', type : 'text'},


        width : {name : 'width', type : 'text'},
        height : {name : 'height', type : 'text'},

        b_t : {name : 'border_width_top', type : 'text'},
        b_r : {name : 'border_width_right', type : 'text'},
        b_b : {name : 'border_width_bottom', type : 'text'},
        b_l : {name : 'border_width_left', type : 'text'},
        border_style : {name : 'border_style', type : 'select'},
        border_color : {name : 'border_color', type : 'text'},
        border_radius : {name : 'border_radius', type : 'text'},

        bg_color     : {name : 'bg_color', type : 'text'},
        bg_image : {name : 'bg_image', type : 'text'},
        bg_position : {name : 'bg_position', type : 'text'},
        bg_size : {name : 'bg_size', type : 'text'},
        bg_repeat : {name : 'bg_repeat', type : 'select'},

        content : {name : 'content', type : 'text',attribute : 'yes'},
        color : {name : 'color', type : 'text'},
        font_size : {name : 'font_size', type : 'text'},
        font_weight : {name : 'font_weight', type : 'text'},
        text_align : {name : 'text_align', type : 'select'},
        line_height : {name : 'line_height', type : 'text'},
        font_family : {name : 'font_family', type : 'select'},
        
        url : {name : 'url', type : 'text', attribute : 'yes'},
        title : {name : 'title', type : 'text', attribute : 'yes'},
        align : {name : 'align', type : 'select'},
        img_width : {name : 'img_width', type : 'text'},
        content_width : {name : 'content_width', type : 'text'},
        content_height : {name : 'content_height', type : 'text'},
        img_height : {name : 'img_height', type : 'text'},
        img_bg_color : {name : 'img_bg_color', type : 'text'},
        img_p_t : {name : 'img_padding_top', type : 'text'},
        img_p_r : {name : 'img_padding_right', type : 'text'},
        img_p_b : {name : 'img_padding_bottom', type : 'text'},
        img_p_l : {name : 'img_padding_left', type : 'text'},
        img_m_t : {name : 'img_margin_top', type : 'text'},
        img_m_r : {name : 'img_margin_right', type : 'text'},
        img_m_b : {name : 'img_margin_bottom', type : 'text'},
        img_m_l : {name : 'img_margin_left', type : 'text'},  
        img_border_width_top : {name : 'img_border_width_top', type : 'text'},  
        img_border_width_right : {name : 'img_border_width_right', type : 'text'},  
        img_border_width_bottom : {name : 'img_border_width_bottom', type : 'text'},  
        img_border_width_left : {name : 'img_border_width_left', type : 'text'},  
        img_border_style : {name : 'img_border_style', type : 'select'},  
        img_border_color : {name : 'img_border_color', type : 'text'},  
        img_border_radius : {name : 'img_border_radius', type : 'text'},  

        details_color : {name : 'details_color', type : 'text'},
        details_font_size : {name : 'details_font_size', type : 'text'},
        details_font_weight : {name : 'details_font_weight', type : 'text'},
        details_text_align : {name : 'details_text_align', type : 'select'},
        details_line_height : {name : 'details_line_height', type : 'text'},
        details_font_family : {name : 'font_family', type : 'select'},


        textarea_content : {name : 'textarea_content', type : 'textarea', attribute : 'yes'},
        
        content_border_width_top : {name : 'content_border_width_top', type : 'text'},
        content_border_width_right : {name : 'content_border_width_right', type : 'text'},
        content_border_width_bottom : {name : 'content_border_width_bottom', type : 'text'},
        content_border_width_left : {name : 'content_border_width_left', type : 'text'},
        content_border_style : {name : 'content_border_style', type : 'select'},
        content_border_color : {name : 'content_border_color', type : 'text'},
        content_border_radius : {name : 'content_border_radius', type : 'text'},
        content_bg_color : {name : 'content_bg_color', type : 'text'},
        
        content_p_t : {name : 'content_padding_top', type : 'text'},
        content_p_r : {name : 'content_padding_right', type : 'text'},
        content_p_b : {name : 'content_padding_bottom', type : 'text'},
        content_p_l : {name : 'content_padding_left', type : 'text'},
        content_m_t : {name : 'content_margin_top', type : 'text'},
        content_m_r : {name : 'content_margin_right', type : 'text'},
        content_m_b : {name : 'content_margin_bottom', type : 'text'},
        content_m_l : {name : 'content_margin_left', type : 'text'},
        cellspacing : {name: 'cellspacing', type:'text'},
        url1 : {name : 'url1', type : 'text', attribute : 'yes'},
        url2 : {name : 'url2', type : 'text', attribute : 'yes'},
        url3 : {name : 'url3', type : 'text', attribute : 'yes'},
        url4 : {name : 'url4', type : 'text', attribute : 'yes'},
        url5 : {name : 'url5', type : 'text', attribute : 'yes'},
        url6 : {name : 'url6', type : 'text', attribute : 'yes'},
        url7 : {name : 'url7', type : 'text', attribute : 'yes'},
        divider_width : {name : 'divider_width', type : 'text'},
        divider_color : {name : 'divider_color', type : 'text'},
        divider_style : {name : 'divider_style', type : 'select'},
        product_img : {name : 'checkbox_option_image', type : 'checkbox'},

        // text1_align : {name : 'block_text1_align', type : 'select'},
        // content1_border_color : {name : 'block_content1_border_color', type : 'text'},
        // content1_border_radius : {name : 'block_content1_border_radius', type : 'text'},
        // content1_border_width : {name : 'block_content1_border_width', type : 'text'},
        // content1_bg_color : {name : 'block_content1_bg_color', type : 'text'},
        // content1_width : {name : 'block_content1_width', type : 'text'},
        // content2_p_t : {name : 'block_content2_padding_top', type : 'text'},
        // content2_p_r : {name : 'block_content2_padding_right', type : 'text'},
        // content2_p_b : {name : 'block_content2_padding_bottom', type : 'text'},
        // content2_p_l : {name : 'block_content2_padding_left', type : 'text'},   
        // block_text2 : {name : 'block_text2', type : 'text',attribute : 'yes'},
        // text2_color : {name : 'block_text2_color', type : 'text'},
        // text2_size : {name : 'block_text2_size', type : 'text'},
        // text2_align : {name : 'block_text2_align', type : 'select'},
        // content2_border_color : {name : 'block_content2_border_color', type : 'text'},
        // content2_border_width : {name : 'block_content2_border_width', type : 'text'},
        // content2_border_radius : {name : 'block_content2_border_radius', type : 'text'},
        // content2_bg_color : {name : 'block_content2_bg_color', type : 'text'},
        // content2_width : {name : 'block_content2_width', type : 'text'},
        // border_top_width : {name : 'block_border_top_width', type : 'text'},
        // content2_align : {name : 'block_float_align', type : 'select'},
        // content1_p_t : {name : 'block_content1_padding_top', type : 'text'},
        // content1_p_r : {name : 'block_content1_padding_right', type : 'text'},
        // content1_p_b : {name : 'block_content1_padding_bottom', type : 'text'},
        // content1_p_l : {name : 'block_content1_padding_left', type : 'text'},
        // block_text_url : {name : 'block_text_url', type : 'text',attribute : 'yes'},
        // link_color : {name : 'block_text_ur_color', type : 'text',attribute : 'no'},
        // text3_color : {name : 'block_text3_color', type : 'text'},
        // text3_size : {name : 'block_text3_size', type : 'text'},
        // text3_align : {name : 'block_text3_align', type : 'select'},
        // block_text4 : {name : 'block_text4', type : 'text',attribute : 'yes'},
        // text4_color : {name : 'block_text4_color', type : 'text'},
        // text4_size : {name : 'block_text4_size', type : 'text'},
        // text_line_height : {name : 'block_text_line_height', type : 'text'},
        // block_text5 : {name : 'block_text5', type : 'text',attribute : 'yes'},
        // block_textarea : {name : 'block_textarea', type : 'textarea',attribute : 'yes'},
        // block_text_url1 : {name : 'block_text_url1', type : 'text', attribute : 'yes'},
        // block_text_url2 : {name : 'block_text_url2', type : 'text', attribute : 'yes'},
        // block_text_url3 : {name : 'block_text_url3', type : 'text', attribute : 'yes'},
        // block_text_url4 : {name : 'block_text_url4', type : 'text', attribute : 'yes'},
        // block_text_url5 : {name : 'block_text_url5', type : 'text', attribute : 'yes'},
        // block_text_url6 : {name : 'block_text_url6', type : 'text', attribute : 'yes'},
        // block_text_url7 : {name : 'block_text_url7', type : 'text', attribute : 'yes'},
        // block_link_title : {name : 'block_link_title', type : 'text', attribute : 'yes'},
        // divider_type : {name : 'block_divider_options', type : 'select'},
        // block_text5 : {name : 'block_text5', type : 'text', attribute : 'yes'},
        
        // p_t : {name : 'block_padding_top', type : 'text'},
        // p_r : {name : 'block_padding_right', type : 'text'},
        // p_b : {name : 'block_padding_bottom', type : 'text'},
        // p_l : {name : 'block_padding_left', type : 'text'}, 
        // product_sku : {name : 'checkbox_option_sku', type : 'checkbox'},
        // icon1_visibility : {name : 'social_icon_option1', type : 'checkbox'},
        // icon2_visibility : {name : 'social_icon_option2', type : 'checkbox'},
        // icon3_visibility : {name : 'social_icon_option3', type : 'checkbox'},
        // icon4_visibility : {name : 'social_icon_option4', type : 'checkbox'},
        // icon5_visibility : {name : 'social_icon_option5', type : 'checkbox'},
        // icon6_visibility : {name : 'social_icon_option6', type : 'checkbox'},
        // icon7_visibility : {name : 'social_icon_option7', type : 'checkbox'},
    }

    var DEFAULT_CSS = {
        'color' : 'transparent',
        'background-color' : 'transparent',
        'border-color': 'transparent',
        'background-color' : 'transparent',
        'padding-top' : '0px',
        'padding-right'  : '0px',
        'padding-bottom' : '0px',
        'padding-left' : '0px',
        'background-image' : 'none',
    }

    var VISIBLE_OPTIONS = {
        url : {name : 'url', css: 'block'},
        url1  : {name : 'url1', css: 'inline-block'},
        url2  : {name : 'url2', css: 'inline-block'},
        url3  : {name : 'url3', css: 'inline-block'},
        url4  : {name : 'url4', css: 'inline-block'},
        url5  : {name : 'url5', css: 'inline-block'},
        url6  : {name : 'url6', css: 'inline-block'},
        url7  : {name : 'url7', css: 'inline-block'},
    };




     /*----------------------------------
     *---- Helper Variabes - END -----
     *----------------------------------*/



    /*----------------------------------
     *---- Helper Fuctions - Start -----
     *----------------------------------*/
    function is_layout_block(name){
        if($.inArray(name, LAYOUT_BLOCKS) !== -1){
            return true;
        }
        return false;
    }
    
    function is_layout_column(name){
        if($.inArray(name, LAYOUT_COLUMNS) !== -1){
            return true;
        }
        return false;
    }

    function is_hooks_features(name){
        if($.inArray(name, HOOK_FEATURES) !== -1){
            return true;
        }
        return false;
    }

     function get_random_string(){
        // var randNum = Math.floor(100000 + Math.random() * 900000);
        // return prefix+'_'+randNum;
        var block_id = $('#tb_temp_builder').attr('data-global-id');
        var new_block_id = parseInt(block_id)+1;
        if($('#tb_'+new_block_id).length > 0){
            get_new_random_id(new_block_id);
        }else{
            $('#tb_temp_builder').attr('data-global-id',new_block_id);
        }
        return new_block_id;
    }

    function get_new_random_id(new_id){
      var new_id = parseInt(new_id)+1;
      if($('#tb_'+new_id).length > 0){
        get_new_random_id(new_id);
      }else{
        $('#tb_temp_builder').attr('data-global-id',new_block_id);
      }
    }

    function get_column_html(id){
        var html = '<td class="column-padding thwec-col thwec-columns" id="tb_'+id+'">'+BLANK_TD_DATA+'</td>';
        return html;
    }

    function reset_col_width(row,columns){
        // console.log(row);
        var col_width = 100/parseInt(columns);//alert(columns+'-'+col_width);
        // $('#tb_temp_builder').find('#'+row+'_tb td').each(function(index, el) {
        //     $(this).css('width',col_width+'%');
        // });
        var siblings_props;
        $('#tb_temp_builder').find('#tb_'+row+' tbody > tr > td').each(function(index, el) {
            var data_props = $(this).attr('data-props');
            if($(this).attr('id')){
                var block_id = $(this).attr('id').replace('tb_','');
                if(data_props){
                    data_props = JSON.parse(data_props);
                    siblings_props = data_props;
                }else{
                    // var data_props=siblings_props;
                    var data_props= BASIC_PROPS;
                }
                data_props['width'] = col_width+'%';
                var data_props_json = JSON.stringify(data_props);
                $(this).attr('data-props',data_props_json);
                prepare_css_functions(data_props, block_id, 'column_clone');
            }
        });
    }

    function reset_column_count(parent_id,status){
        var cols = $('#'+parent_id).attr('data-columns');
        if(status=='add'){
             var col_count = parseInt(cols)+1;
        }else if(status == 'delete'){
             var col_count = parseInt(cols)-1;
        }
        $('#'+parent_id).attr('data-columns',col_count);
        return col_count;
    }


    function get_cleaned_block_name(name){ 
        var text = name.replace(/_/g,' ').replace(/\b[a-z]/g, function(string) {
            return string.toUpperCase();
        });
        return text;
    }

        // function prepare_css(props){
    //     console.log(props);
    //     var css = '';
    //     $.each( CSS_PROPS, function( name, css_prop ) {
    //         var css_pname = css_prop['name'];//console.log(css_pname);
            
    //         // if(props[name]){
    //         //     css += css_pname+':'+props[name]+';';
    //         // }
    //     });
    //     return css;
    // }

    function prepare_css(props){
        var css = '';
        $.each( props, function( name, css_prop ) {
            if(name in CSS_PROPS){
                var css_pname = CSS_PROPS[name]['name'];
                css_prop =  css_prop ? css_prop : DEFAULT_CSS[css_pname];
                if(css_prop){
                    css += css_pname+':'+css_prop+';';
                }
            }
        });
        return css;
    }    

    function prepare_css_props(props, keys){
        var new_props = {};
        $.each(keys, function(i, name) {
            new_props[name] = props[name];
        });
        return new_props;
    }

    function get_css_parent_selector(blockId, isPrev){
        var p_selector = "#"+blockId;
        var selector = isPrev ? p_selector : p_selector+' ';
        return selector;
    }

    function initialize_css_styles(){
        var css = $('#thwec_template_css').html();
        var override_css = $('#thwec_template_css_override').html();
        var preview_override_css = $('#thwec_template_css_preview_override').html();
        var new_override_css = css+override_css;
        var new_preview_override_css = css+preview_override_css;
        $('#thwec_template_css_override').html(new_override_css);
        $('#thwec_template_css_preview_override').html(new_preview_override_css);
    }

    function setup_blank_track_panel_msg(){
        if($('ul.tracking-list').find('.rows').length < 1){
            $('.new-template-notice').css('display','block');
        }else{
            $('.new-template-notice').css('display','none');
        } 
    }

    /*----------------------------------
     *---- Helper Fuctions - END -------
     *----------------------------------*/

     /*----------------------------------
     *---- Click Fuctions - START -------
     *----------------------------------*/

     function template_action_add_row(elm){
        var popup = $('#thwec_builder_block_pp');
        var form = $('#thwec_builder_block_form');
        POPUP_FLAG = false;
        APPEND_FLAG = false;
        // thwec_base.set_property_field_value(form, 'hidden', 'popup_flag', flag, 0);
        setup_popup_form_general(popup, form, 'add-row');
     }

    function setup_track_panel_clicks(){

        $('.tracking-list').on('click', '.list-collapse', function(event) { // List Toggle
            $(this).toggleClass('dashicons-arrow-down dashicons-arrow-right');
            $(this).closest('li').find('> ul').toggle();
        });

        /*$('.thwec-tbuilder-elm-grid').find('#add-row').click(function(event) {
            var popup = $('#thwec_builder_block_pp');
            var form = $('#thwec_builder_block_form');
            POPUP_FLAG = false;
            APPEND_FLAG = false;
            // thwec_base.set_property_field_value(form, 'hidden', 'popup_flag', flag, 0);
            setup_popup_form_general(popup, form, 'add-row');
        });*/
        $('.tracking-list').find('.btn-add-element').click(function(event) {
            var popup = $('#thwec_builder_block_pp');
            var form = $('#thwec_builder_block_form');
            // thwec_base.set_property_field_value(form, 'hidden', 'popup_flag', flag, 0);
            POPUP_FLAG = false;
            setup_popup_form_general(popup, form, 'add-element');
        });

        $('.tracking-list').on('click', '.btn-add-column', function(event) {
            event.preventDefault();
            var result = confirm_flag ? confirm($('#add_col_confirm').html()) : true;
            if (result) {
                confirm_flag = false;
                var row_id = $(this).closest('li').data('parent');
                var column_id = get_random_string();
                var column_html = get_column_html(column_id);
                $('#tb_temp_builder').find('#tb_'+row_id+' > tbody > tr:first').append(column_html);
                
                // var column_number =  $(this).parents().eq(2).attr('data-columns');
                // var new_column_number = parseInt(column_number)+1;
                // $(this).parents().eq(2).data('columns',new_column_number);
                var column_track_html = add_new_track_col(row_id, column_id,'column_clone',null);
                $(column_track_html).insertBefore($('ul.tracking-list').find('#'+row_id+' > ul > li.panel-add-new'));
                var new_column_number = reset_column_count(row_id,'add');
                reset_col_width(row_id,new_column_number);
                setup_template_builder();
                // console.log(column_number+' - '+new_column_number);
            }
        });  

        $('.thwec-tbuilder-wrapper').on('click', '.btn-add-element', function(event) {
            event.preventDefault();
            var form = $('#thwec_builder_block_form');
            var popup = $('#thwec_builder_block_pp');
            POPUP_FLAG = false;
            APPEND_FLAG = true;
            if($(this).hasClass('panel-add-btn')){
                var status = 'panel';
                // console.log($(this).closest('.columns').data('parent'));
                var click_li = $(this).closest('li.columns').attr('id');
            }else if($(this).hasClass('builder-add-btn')){
                var status = 'builder';
                var click_li = $(this).closest('td').attr('id').replace('tb_','');
            }  
            thwec_base.set_property_field_value(form, 'hidden', 'block_id', click_li, 0);
            // thwec_base.set_property_field_value(form, 'hidden', 'popup_flag', flag, 0);
            setup_popup_form_general(popup, form, 'add-element');
            // $('#popup_flag').val(popup_flag);
            // $('#popup_block_id').val(click_id);
            // $('#builder_block_edit_pp_table').html($('#complete_feature_table').html());
            // POPUP.dialog('open'); 
        });
        
    }

    function builder_block_delete(elm){
        if($(elm).closest('li').hasClass('hooks')){
            var id = $(elm).closest('li').attr('id');
            $(elm).closest('li').remove();
            var block = $('#tb_temp_builder').find('[data-hook="'+id+'"]');
            var block_parent = block.closest('.thwec-columns');
            block.remove();
            if(block_parent.find('.builder-block').length < 1 && block_parent.find('.hook-code').length < 1){ // Show html to add new content on deleting all elements inside
                    block_parent.html(BLANK_TD_DATA);
                }
        }else{
            event.preventDefault();
            event.stopPropagation();
            var select_block = $(elm).closest('.thwec-settings');
            var delete_li = select_block.closest('li');
            var delete_id = select_block.closest('li').attr('id');
            var delete_class = delete_li.attr('class');
            var builder_element = $('#tb_temp_builder').find('#tb_'+delete_id);
            var panel_element = $('ul.tracking-list').find('#'+delete_id);
            if(delete_class == 'rows' || delete_class == 'element-list'){     //  deleting rows or elements on clicking delete
                var builder_element_parent = builder_element.closest('.thwec-columns');
                builder_element.remove();
                // console.log(builder_element);
                panel_element.remove();
                if(builder_element_parent.find('.builder-block').length < 1){ // Show html to add new content on deleting all elements inside
                    builder_element_parent.html(BLANK_TD_DATA);
                }

            }else if(delete_class == 'columns'){ // If deleting columns, count of column updated on rows and width of resulting columns reset 
                var builder_element_parent = builder_element.closest('.thwec-row');
                var panel_element_parent = panel_element.closest('.rows');
                var columns = panel_element_parent.attr('data-columns');
                if(columns <= 1){
                    builder_element_parent.remove();
                    panel_element_parent.remove();
                }else{
                    var updated_columns = parseInt(columns)-1;
                    panel_element_parent.attr('data-columns',updated_columns); 
                    builder_element.remove();
                    panel_element.remove();
                    var builder_element_parent_id = builder_element_parent.attr('id').replace('tb_','');
                    reset_col_width(builder_element_parent_id,updated_columns); // Resetting width of each column in the parent table
                }    
            }
        }
        setup_blank_track_panel_msg();
    }

     function setup_popup_clicks(){
        $('.thwec_field_form_general').on('click', '.elm-col', function(event) {
            $('.thwec_field_form_general').find('td').removeClass('elm-selected');
            $(this).addClass('elm-selected');
             var form = $('#thwec_builder_block_form');
            var name = $(this).find('div').data('block-name');
            thwec_base.set_property_field_value(form, 'hidden', 'block_name', name, 0);
        });
    }

    function setup_builder_element_click(){
        $('#tb_temp_builder').on('click', '.thwec-block,.hook-code', function(event) {
            if($(this).hasClass('hook-code')){
                var block_id = $(this).attr('data-hook');
                panel_id = block_id;
            }else{
                var block_id = $(this).attr('id');
                var panel_id = block_id.replace('tb_','');
            }
           
            if(block_id){
                // var panel_id = block_id.replace('tb_','');
                var track_panel = $('ul.tracking-list');
                track_panel.find('.thwec-panel-highlight').removeClass('thwec-panel-highlight');
                var panel_obj = track_panel.find('#'+panel_id);

                focus_selected_element(panel_obj);

                if(panel_obj.closest('.elements').css('display')=='none'){ 
                    panel_obj.closest('.elements').css('display','block');
                    var dashicon_class = panel_obj.closest('.elements').closest('.columns').find('>.layout-lis-item >.list-collapse');
                    if(dashicon_class.hasClass('dashicons-arrow-right')){
                        dashicon_class.removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
                    }
                }

                panel_obj.closest('.elements').parentsUntil('ul.tracking-list').each(function(index, el) {
                    get_parent_node($(this));
                });
            }
        });
    }

    function focus_selected_element(elm){
        var elmItem = elm.find('.layout-lis-item');
        elmItem.addClass('thwec-panel-highlight');

        $('html, body').animate({scrollTop: get_element_position(elm)}, 500);

        setTimeout(
            function() { 
                elmItem.removeClass('thwec-panel-highlight'); 
            },
            5000
        );
    }

    function get_element_position(elm){
        return parseInt(elm.offset().top)-150;
    }

    function get_parent_node($node_obj){
        if($node_obj.hasClass('columns')){
            var dashicon_class = $node_obj.find('>.layout-lis-item >.list-collapse');
            if(dashicon_class.hasClass('dashicons-arrow-right')){
                dashicon_class.removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
                if($node_obj.find('>.elements')){
                    $node_obj.find('>.elements').css('display','block');
                }
            }
        }else if($node_obj.hasClass('rows')){
            var dashicon_class = $node_obj.find('>.layout-lis-item >.list-collapse');
            if(dashicon_class.hasClass('dashicons-arrow-right')){
                dashicon_class.removeClass('dashicons-arrow-right').addClass('dashicons-arrow-down');
                if($node_obj.find('>.sorting-pointer')){
                    $node_obj.find('>.sorting-pointer').css('display','block');
                }
            }
        }
    }

     /*----------------------------------
     *---- Click Fuctions - END ---------
     *----------------------------------*/

    /*----------------------------------
    *---- Hover Fuctions - START -------
    *----------------------------------*/

    function setup_track_panel_hover(){
        $('.tracking-list').on('mouseenter', '.thwec-settings', function(event){
            $(".tracking-list").find('.settings-expand.settings-active').removeClass('settings-active');     
            var settings_tab = $(this).find('.settings-expand');
            settings_tab.addClass('settings-active');
        }).on('mouseleave','.thwec-settings',function(event){
            var settings_tab = $(this).find('.settings-expand');
            settings_tab.removeClass('settings-active');
        });
    }

    /*----------------------------------
    *---- Hover Fuctions - END ---------
    *----------------------------------*/




    /*----------------------------------------------
     *---- Sortable Content Fuctions - Start ------
     *----------------------------------------------*/

    function setup_template_builder(){        

        // sortable_tracking_elements('ul.tracking-list', '.sortable-row-handle', '.rows', '.tracking-list', sortable_start_handler, sortable_out_handler, sortable_stop_handler, null, sortable_receive_handler, sortable_update_handler);
        sortable_tracking_elements('ul.tracking-list', '.sortable-row-handle', '.rows', '.elements,.tracking-list', sortable_start_handler, sortable_out_handler, sortable_stop_handler, null, sortable_receive_handler, sortable_update_handler);
        sortable_tracking_elements('.sorting-pointer', '.sortable-col-handle', '> li.columns', '', sortable_start_handler, sortable_out_handler, sortable_stop_handler, null, null, sortable_update_handler);
        sortable_tracking_elements('.elements', '.sortable-elm-handle', 'li:not(.panel-add-new)', '.elements,ul.tracking-list', sortable_start_handler, sortable_out_handler, sortable_stop_handler, null, null, sortable_update_handler);
    }
    
    function sortable_tracking_elements(elm, handle, items, connectWith, start_handler, out_handler, stop_handler, over_handler, receive_handler, update_handler){
        $(elm).sortable({
            handle: handle,
            axis:'x,y',
            items: items,
            scroll: false,
            cursor: "move",
            helper:"clone",
            placeholder: "sortable-row-placeholder",
            connectWith: connectWith,
            forcePlaceholderSize: true,
            start: start_handler,
            out: out_handler,
            over: over_handler,
            stop: stop_handler,
            receive: receive_handler,
            update: update_handler
        });
        $(elm).disableSelection();
    }


    function sortable_start_handler(event, ui){
        DRAGG_CLASS = ui.item.attr('class');
        // if(DRAGG_CLASS == 'rows' || DRAGG_CLASS == 'columns'){
            // $(".sorting-pointer").sortable("disable");
           //  $(this).sortable("refresh");
            // $(this).sortable('refreshPositions');
            $(ui.item).show();
            $(ui.helper).addClass('dragg');    
            var clone_html = ui.item.find('> .layout-lis-item')[0].outerHTML;
            
                if(clone_html.indexOf('down')){
                    clone_html = clone_html.replace('down','right');
                }
            ui.helper.html(clone_html);
    }

    function sortable_out_handler(event, ui){
        if(DRAGG_CLASS == 'rows' || DRAGG_CLASS == 'element-list'){
            if(ui.item.closest('.columns').length > 0){
                var prev_parent_id = ui.item.closest('.columns').attr('id');
                ui.item.data('prev-parent',prev_parent_id);
            }
        }
    }

    function sortable_stop_handler(event, ui){ 
        // sortable_droppable('.thwec-columns', '.thwecicon-drag', '.thwec-columns', sortable_start_handler_local, sortable_stop_handler_local, sortable_receive_handler_local);
        DRAGG_CLASS == '';
        $(ui.helper).removeClass('dragg');
        if($(this).hasClass('elements') && ui.item.hasClass('elements')){ //console.log('setting flag to true');
            // SORTABLE_DISABLE = true;
        }
    }
    
    function sortable_over_handler(event, ui){
       // console.log($(this).attr('class'));
       
    }
    function sortable_receive_handler(event, ui){
       if($(this).hasClass('tracking-list') && DRAGG_CLASS == 'element-list'){
            ui.placeholder.hide();
            ui.sender.sortable('cancel');  
            alert('Cannot place an element outside a column');
            // SORTABLE_DISABLE = false; 
        }
        // console.log(ui.sender.attr('class'));
        
    }
    
    function sortable_update_handler(event, ui){
       
        var track_id = 'tb_'+ui.item.attr('id');
        var next_id =  ui.item.next().attr('id');
        var prev_id =  ui.item.prev().attr('id');
        
        if(prev_id){
            prev_id = 'tb_'+prev_id;
            // $( '<p>Welcome to test</p>' ).insertAfter($('#tb_'+prev_id));
            $( $('#tb_temp_builder').find('#'+track_id) ).insertAfter($('#'+prev_id));
        }else{
            next_id = 'tb_'+next_id;
            $( $('#tb_temp_builder').find('#'+track_id) ).insertBefore($('#'+next_id));
        }

        if(ui.item.closest('.elements').closest('.columns').length > 0){
                          
            var prev_parent = ui.item.data('prev-parent'); 
            var current_parent = ui.item.closest('.elements').closest('.columns').attr('id');
            var track_id = ui.item.attr('id');
            var next_id =  ui.item.next().attr('id');
            var prev_id =  ui.item.prev().attr('id');
            // console.log(track_id+' - '+next_id+' - '+prev_id+' - '+prev_parent+' - '+current_parent);
            if(ui.item.closest('.columns').attr('id')){
                var column_id = ui.item.closest('.columns').attr('id');
            }
            var data = $('#tb_temp_builder').find('#tb_'+track_id);
            if(prev_id){
                $(data).insertAfter($('#tb_'+prev_id));
            }else if(next_id){
                $(data).insertBefore($('#tb_'+next_id));
            }
            else if(!next_id && !prev_id){
                $('#tb_temp_builder').find('#tb_'+column_id).html(data);
            }

            if($('#tb_'+prev_parent).find('.builder-block').length < 1){
               $('#tb_'+prev_parent).html(BLANK_TD_DATA);
            }
        }
    }

    /*----------------------------------------------
     *---- Sortable Content Fuctions - END ---------
     *----------------------------------------------*/

    
    /*----------------------------------------------
    *---- New Insertion Function  -  START ---------
    *----------------------------------------------*/


    function add_builder_elements(blockName, blockId){
        var new_id = get_random_string(); 
        var track_html = prepare_new_track_html(blockName,new_id);
        // var html = prepare_new_block_content_html(blockId, blockName);
        var html = prepare_new_block_content_html(blockName);
        create_track_builder_blocks(html,track_html, blockId);
        setup_blank_track_panel_msg();       
    }


    function create_track_builder_blocks(builder_html,track_html, blockId){
        // console.log(TRACK_LIST.length)
        // alert(APPEND_FLAG);
        if(blockId && APPEND_FLAG){
            var id = 'tb_'+blockId;
            $(track_html).insertBefore($('ul.tracking-list').find('#'+blockId+' > .elements >li.panel-add-new'));
            var target = $('#tb_temp_builder').find('#'+id);
            if(target.find('>.btn-add-element').length > 0){
                target.find('>.btn-add-element').remove();
            }
            target.append(builder_html);
        }else{
            $('ul.tracking-list').append(track_html);
            $('#tb_temp_builder').append(builder_html);
        }
        APPEND_FLAG='';
       
       //  if(status == 't-panel' && block_type == 'layout'){ 
       //      TRACK_LIST.append(track_data);
       // }else if((status == 'builder' || status == 't-panel-child') && (block_type == 'element' || block_type == 'layout')){ //alert('row / elemnt inside builder');
       //      TRACK_LIST.find('#'+parent_id+'_tp .elements').append(track_data);
       // }else if(status == 't-panel' && block_type == ''){ //alert('column');
       //      // TRACK_LIST.find('#'+parent_id+'_tp').append(track_data);
       //      $(track_data).insertBefore(TRACK_LIST.find('#'+parent_id+'_tp > ul > li.panel-add-new'));
    }

    function prepare_new_track_html(blockName, row_id){
        if(is_layout_block(blockName)){
            var html = '<li id="'+row_id+'" class="rows" data-columns="'+COLUMN_NUMBER[blockName]+'">';
            LAYOUT_OBJ['row'] = 'tb_'+row_id;
            html+= $('#thwec_tracking_panel_row_html').html();
            html = html.replace('{bl_id}', row_id);
            html = html.replace('{bl_name}', blockName);
            html+= '<ul class="sorting-pointer">';
            for(var i=1;i<=COLUMN_NUMBER[blockName];i++){
                var column_id = get_random_string();
                var column_name = 'column_'+i;
                LAYOUT_OBJ[column_name] = 'tb_'+column_id;
                html+= add_new_track_col(row_id, column_id, blockName, i);
            }
            html+= '<li class="panel-add-new" data-parent="'+row_id+'"><a href="#" class="btn-add-column panel-add-btn">Add Column</a></li>';
            html+= '</ul></li>';
        }else if(is_hooks_features(blockName)){
            var name = get_cleaned_block_name(blockName);
            var html = '<li id="'+row_id+'" class="hooks">';
            html+= $('#thwec_tracking_panel_hook_html').html();
            html = html.replace('{name}',get_cleaned_block_name(blockName));
            html+= '</li>';
            LAYOUT_OBJ['hook'] = row_id;
        }else{
            var html = '<li id="'+row_id+'" class="element-list">';
            html+= $('#thwec_tracking_panel_elm_html').html();
            html = html.replace('{name}',get_cleaned_block_name(blockName));
            html = html.replace('{bl_id}', row_id);
            html = html.replace('{bl_name}', blockName);
            html+= '</li>;'
            LAYOUT_OBJ['element'] = 'tb_'+row_id;
        }

        return html;

    }

    function add_new_track_col(row_id, column_id, blockName, i){
        var column_name = blockName+'_'+NUMBER_TO_WORDS[i];
        if(!is_layout_column(column_name)){
            column_name = 'column_clone'; 
        }
        var t_html = '<li id="'+column_id+'" class="columns" data-parent="'+row_id+'">';
        t_html+= $('#thwec_tracking_panel_col_html').html();
        t_html = t_html.replace('{bl_id}', column_id);
        t_html = t_html.replace('{bl_name}', column_name); 
        t_html+= '<ul class="elements" style="display:none;">';
        t_html+= '<li class="thwec-hidden-sortable"></li><li class="panel-add-new"><a href="#" class="btn-add-element panel-add-btn">Add Element</a></li>';
        t_html+= '</ul></li>';
        return t_html;
    }


     /*----------------------------------------------
    *---- New Insertion Function  -  END ------------
    *----------------------------------------------*/

    /*----------------------------------------------
    *---- HTML Cleaning Functions  -  START --------
    *----------------------------------------------*/

    function clean_block_content_element_html(block_elm,blockName,index){
        var html = block_elm.html();
        html = html.replace('{'+blockName+'}',LAYOUT_OBJ[index]);
        LAYOUT_OBJ={};
        return html;
    }

    function clean_block_content_layout_html(block_elm,blockName){

        var html = block_elm.html();
        html = html.replace(blockName,LAYOUT_OBJ['row']);
        for(var i=1;i<=COLUMN_NUMBER[blockName];i++){
            var column_name = 'column_'+i;
            var replace_name = blockName+'_'+i;
            html = html.replace(replace_name,LAYOUT_OBJ[column_name]);
        }
        LAYOUT_OBJ={};
        return html;
    }

    /*----------------------------------------------
    *---- HTML Cleaning Functions  -  END ----------
    *----------------------------------------------*/

    /*----------------------------------------------
    *---- POPUP Functions  -  START ----------------
    *----------------------------------------------*/
    function open_builder_block_edit_pp(elm, blockId, blockName){
        var popup = $('#thwec_builder_edit_block_pp');
        var form = $('#thwec_builder_block_edit_form');
        thwec_base.set_property_field_value(form, 'hidden', 'block_id', blockId, 0);
        thwec_base.set_property_field_value(form, 'hidden', 'block_name', blockName, 0);
        POPUP_FLAG = true;
        setup_popup_form_edit(popup, form, blockName,blockId);
        thwec_base.setupColorPicker(form);
        thwec_base.setup_color_pick_preview(form);
         thwec_base.setup_tiptip_tooltips();

    }

    function setup_popup_form_general(popup, form, blockName, blockId){
        prepare_builder_block_form(popup, blockName);
        // thwec_base.set_property_field_value(form, 'hidden', 'popup_flag', flag, 0);
        popup.dialog('open');
    }

    function setup_popup_form_edit(popup, form, blockName, blockId){
        prepare_builder_block_edit_form(popup, blockName);
        populate_builder_block_form_general(form, blockId, blockName);
        // thwec_base.set_property_field_value(form, 'hidden', 'popup_flag', flag, 0);
        popup.dialog('open');
    }

    function populate_builder_block_form_general(form, blockId, blockName){
        
        var block_props_json = $('#tb_'+blockId).attr('data-props');
        var block_props = block_props_json ? JSON.parse(block_props_json) : '';
        var url_props_json = $('#tb_'+blockId).attr('data-social');
        var url_props = url_props_json ? JSON.parse(url_props_json): '';
        var popups_flag = block_props ? true :  false ;
        // console.log(blockName);
        block_props = block_props ? block_props :  get_default_form_values(blockName);
        // block_props = block_props ? block_props :  '';
        // console.log(block_props);
        if(block_props){
            $.each(block_props, function (key, value) {
                var props = ELM_BLOCK_FORM_PROPS[key];
                // console.log(key+' - '+value);
                if(popups_flag){
                    if(props['name'] && (key in VISIBLE_OPTIONS)){ 
                        value = (value == VISIBLE_OPTIONS[key]['css']) ? url_props[key] : ''; 
                    }
                    else if($.inArray(key,IMG_CSS) != -1 && value){
                        value = value.match(/\((.*)\)/)[1];
                    }else if(key == 'product_img'){ 
                        value = (value == 'block') ? 1 : 0;
                    }
                }
                if(props['type'] == 'fourside'){
                    props['type'] = 'text';
                }
                if(key == 'url'){
                    form.find('.img_preview_image').html('<img src="'+value+'">');
                }
                if(key == 'bg_image' && value !=''){
                    form.find('.img_preview_bg_image').html('<img src="'+value+'">');
                }
                if(key == 'font_family'){
                    $.each(FONT_LIST, function(key,valueObj){
                        if(valueObj==value){
                            value = key;
                        }
                    });
                }
                thwec_base.set_property_field_value(form, props['type'], props['name'], value, 0);
            });
            // thwec_base.setup_color_pick_preview(form);
        }
    }

    function get_default_form_values(blockName){
        var default_values = {};
        switch(blockName){
            case 'one_column':
            case 'two_column':
            case 'three_column':
            case 'four_column':
                    default_values = {
                        width : '100%',
                        p_t  : '0px',
                        p_r : '0px',
                        p_b : '0px',
                        p_l : '0px',
                        m_t : '0px',
                        m_r : 'auto',
                        m_b : '0px',
                        m_l : 'auto',
                        b_t : '0px',
                        b_r : '0px',
                        b_b : '0px',
                        b_l : '0px',
                        cellspacing : '0px',
                        align : 'none',
                    };
                    break;   

            case 'one_column_one':
                    default_values = {
                        width : '100%',
                        b_t : '1px',
                        b_r : '1px',
                        b_b : '1px',
                        b_l : '1px',
                        border_color : '#dddddd',
                        border_style : 'dotted',
                    };
                    break;  
            case 'two_column_one':
            case 'two_column_two':
                    default_values = {
                        width : '50%',
                        b_t : '1px',
                        b_r : '1px',
                        b_b : '1px',
                        b_l : '1px',
                        border_color : '#dddddd',
                        border_style : 'dotted',
                    };
                    break;
            case 'three_column_one':
            case 'three_column_two':
            case 'three_column_three':
                    default_values = {
                        width : '33%',
                        b_t : '1px',
                        b_r : '1px',
                        b_b : '1px',
                        b_l : '1px',
                        border_color : '#dddddd',
                        border_style : 'dotted',
                    };
                    break;   
            case 'four_column_one':
            case 'four_column_two':
            case 'four_column_three':
            case 'four_column_four':
                    default_values = {
                        width : '25%',
                        b_t : '1px',
                        b_r : '1px',
                        b_b : '1px',
                        b_l : '1px',
                        border_color : '#dddddd',
                        border_style : 'dotted',
                    };
                    break;
                                             
            // case 'column-one':
            //         default_values = {
            //             width : '100%',
            //             height :'80px',
            //             font_size :  '40px',
            //             text_align : 'center',
            //         };
            //         break;        
                    
            case 'header_details':
                    default_values = {
                        width : '100%',
                        height : '98px',
                        p_t : '0px',
                        p_r : '0px',
                        p_b : '0px',
                        p_l : '0px',
                        m_t : '0px',
                        m_r : 'auto',
                        m_b : '0px',
                        m_l : 'auto',
                        b_t : '0px',
                        b_r : '0px',
                        b_b : '0px',
                        b_l : '0px',
                        content : 'Email Template Header',
                        color :'#ffffff',
                        font_size :  '40px',
                        text_align : 'center',
                        line_height : '150%',
                        font_weight : 'normal',
                        bg_color : '#0099ff',
                        img_p_t :  '15px',
                        img_p_r : '0px',
                        img_p_b :'15px',
                        img_p_l : '0px',
                        img_m_t : '0px',
                        img_m_r : 'auto',
                        img_m_b : '0px',
                        img_m_l : 'auto'
                    };
                    break;
            case 'footer_details':
                    default_values = {
                        width : '100%',
                        height : '92px',
                        p_t : '15px',
                        p_r : '15px',
                        p_b : '15px',
                        p_r : '15px',
                        m_t : '0px',
                        m_r : '0px',
                        m_b : '0px',
                        m_l : '0px',
                        b_t : '0px',
                        b_r : '0px',
                        b_b : '0px',
                        b_l : '0px',
                        color : '#636363',
                        text_align : 'center',
                        font_size : '12px', 
                        textarea_content : '<p>Company Name&nbsp;&nbsp;|&nbsp;&nbsp;Address 1&nbsp;&nbsp;|&nbsp;&nbsp;Address 2</p><p>If you no more wish to receive our emails, please click <a href="#">unsubscribe</a></p>',
                    };
                    break;                    
            case 'social':
                    default_values = {
                        text_align : 'center',
                        url1 : 'http://www.facebook.com/',
                        url2 : 'http://www.mail.google.com/',
                        url3 : 'http://www.twitter.com/',
                        url4 : 'http://www.youtube.com/',
                        url5 : 'http://www.linkedin.com/',
                        url6 : 'https://www.pinterest.com/',
                        url7 : 'https://www.instagram.com/',
                        img_width : '40px',
                        img_height : '40px',
                        img_p_t : '0px',
                        img_p_r : '3px',
                        img_p_b : '0px',
                        img_p_l : '3px',
                        p_t : '0px',
                        p_r : '0px',
                        p_b : '0px',
                        p_l : '0px',
                        m_t : '12px',
                        m_r : 'auto',
                        m_b : '12px',
                        m_l : 'auto',
                    };
                    break;
            case 'gif':
                    default_values = {
                        url : 'https://media.giphy.com/media/Z5W9H5DtCWN4k/giphy.gif',
                        align : 'none',
                        width : '200px',
                        height : '200px',
                        p_t : '12px',
                        p_r : '12px',
                        p_b : '12px',
                        p_l : '12px',
                        m_t : '0px',
                        m_r : 'auto',
                        m_b : '0px',
                        m_l : 'auto',
                        b_t : '0px',
                        b_r : '0px',
                        b_b : '0px',
                        b_l : '0px',
                    };
                    break;
            case 'billing_address':
                    default_values = {
                        width : '100%',
                        height: '208px',
                        align : 'none',
                        p_t : '0px',
                        p_r : '0px',
                        p_b : '0px',
                        p_l : '0px',
                        m_t : '0px',
                        m_r : 'auto',
                        m_b : '0px',
                        m_l : 'auto',
                        b_t : '0px',
                        b_r : '0px',
                        b_b : '0px',
                        b_l : '0px',
                        content : 'Billing Details',
                        color : '#0099ff',
                        font_size : '18px',
                        text_align : 'center',
                        font_weight : 'bold',
                        line_height : '170%',
                        details_color : '#444444',
                        details_font_size : '13px',
                        details_text_align : 'center',
                        details_line_height : '150%',
                    };
                    break;    
            case 'shipping_address':
                    default_values = {
                        width : '100%',
                        height: '171px',
                        align : 'none',
                        p_t : '0px',
                        p_r : '0px',
                        p_b : '0px',
                        p_l : '0px',
                        m_t : '0px',
                        m_r : 'auto',
                        m_b : '0px',
                        m_l : 'auto',
                        b_t : '0px',
                        b_r : '0px',
                        b_b : '0px',
                        b_l : '0px',
                        content : 'Shipping Details',
                        color : '#0099ff',
                        font_size : '18px',
                        text_align : 'center',
                        font_weight : 'bold',
                        line_height : '170%',
                        details_color : '#444444',
                        details_font_size : '13px',
                        details_text_align : 'center',
                        details_line_height : '150%',
                    };
                    break; 
            case 'customer_address':
                    default_values = {
                        width : '100%',
                        height: '113px',
                        align : 'none',
                        p_t : '0px',
                        p_r : '0px',
                        p_b : '0px',
                        p_l : '0px',
                        m_t : '0px',
                        m_r : 'auto',
                        m_b : '0px',
                        m_l : 'auto',
                        b_t : '0px',
                        b_r : '0px',
                        b_b : '0px',
                        b_l : '0px',
                        content : 'Customer Details',
                        color : '#0099ff',
                        font_size : '18px',
                        text_align : 'center',
                        font_weight : 'bold',
                        line_height : '170%',
                        details_color : '#444444',
                        details_font_size : '13px',
                        details_text_align : 'center',
                        details_line_height : '150%',
                    };
                    break;
            case 'image':
                    default_values = {
                        url : 'http://localhost/wootest/wp-content/uploads/2018/08/image.jpg',
                        align : 'none',
                        img_width : '272px',
                        img_height : '164px',
                        align : 'none',
                        img_m_t : '12px',
                        img_m_r : 'auto',
                        img_m_b : '12px',
                        img_m_l : 'auto',

                        // content1_border_color : '',
                        // content2_align : 'none',
                    };
                    break;   
            case 'button':
                    default_values = {
                        content : 'Click here',
                        // block_text_url : '#47f968',
                        // block_link_title : 'alt text',
                        font_size : '13px',
                        color : '#ffffff',
                        content_bg_color : '#4169e1',
                        content_border_color : '#4169e1',
                        line_height : '150%',
                        width : '80px',
                        // content1_border_radius : '2px',
                        // content1_width : '30%',
                        details_text_align : 'center',
                        content_p_t : '10px',
                        content_p_r : '0px',
                        content_p_b : '10px',
                        content_p_l : '0px',
                        p_t :'10px',
                        p_r : '0px',
                        p_b : '10px',
                        p_l : '0px',
                        m_t :'15px',
                        m_r : 'auto',
                        m_b : '15px',
                        m_l : 'auto',
                        b_t : '1px',
                        b_r : '1px',
                        b_b : '1px',
                        b_l : '1px',
                        border_style : 'solid',
                        border_color : '#4169E1',
                        bg_color : '#4169E1',
                        align: 'none',
                        text_align : 'center'
                    };
                    break;  
            case 'divider':
                    default_values = {
                        width : '70%',
                        divider_width : '2px',
                        divider_style : 'solid',
                        divider_color : 'gray',
                        m_t : '40px',
                        m_r : 'auto',
                        m_b : '40px',
                        m_l : 'auto',
                    };
                    break;
            case 'gap':
                    default_values = {
                        width : '100%',
                        height : '48px',
                        p_t : '0px',
                        p_r : '0px',
                        p_b : '0px',
                        p_l : '0px',
                        m_t : '0px',
                        m_r : '0px',
                        m_b : '0px',
                        m_l : '0px',
                        b_t : '0px',
                        b_r : '0px',
                        b_b : '0px',
                        b_l : '0px',
                    };
                    break;
            case 'text':
                    default_values = {
                        width : '100%',
                        align : 'none',
                        p_t : '12px',
                        p_r : '10px',
                        p_b : '12px',
                        p_l : '10px',
                        m_t : '0px',
                        m_r : 'auto',
                        m_b : '0px',
                        m_l : 'auto',
                        b_t : '0px',
                        b_r : '0px',
                        b_b : '0px',
                        b_l : '0px',
                        color : '#636363',
                        font_size : '14px',
                        line_height : '17pt',
                        text_align : 'center',
                        font_weight : 'normal',
                    };
                    break;        
            case 'order_details':
                    default_values = {
                        width : '100%',
                        height : 'auto',
                        align : 'none',  
                        p_t : '20px',
                        p_r : '48px',
                        p_b : '20px',
                        p_l : '48px',
                        b_t : '0px',
                        b_r : '0px',
                        b_b : '0px',
                        b_l : '0px',
                        m_t : '0px',
                        m_r : 'auto',
                        m_b : '0px',
                        m_l : 'auto',
                        bg_color : '#ffffff',
                        content : 'Order',
                        font_size: '18px',
                        text_align: 'left',
                        line_height: '130%',
                        color: '#4286f4',
                        content_width : '100%',
                        content_bg_color : '#ffffff',
                        content_border_color : '#e5e5e5',
                        content_p_t :'12px',
                        content_p_r :'12px',
                        content_p_b :'12px',
                        content_p_l :'12px',
                        details_color :'#636363',
                        details_font_size :'14px',
                        details_line_height :'150%',
                        details_text_align :'left',
                    };
                    break;     
            case 'temp_builder' : 
                    default_values = {
                        b_t : '1px',
                        b_r : '1px',
                        b_b : '1px',
                        b_l : '1px',
                        border_style : 'solid',
                        border_color : '#dedede',
                        bg_color : '#edf1e4'
                    };
                    break;                                     
            default :
                    default_values ='';
        }
        return default_values;
    }


    function save_popup_form_data(form){
        // console.log(thwec_builder_block_form);
        // var popup_flag = thwec_base.get_property_field_value(form, 'hidden', 'popup_flag');
        var blockName =  thwec_base.get_property_field_value(form, 'hidden', 'block_name');
        var blockId = thwec_base.get_property_field_value(form, 'hidden', 'block_id');
        add_builder_elements(blockName, blockId);
        setup_template_builder();
    }

    function save_popup_form_edit_data(form){
        // console.log(thwec_builder_block_form);
        // var popup_flag = thwec_base.get_property_field_value(form, 'hidden', 'popup_flag');
        var blockName =  thwec_base.get_property_field_value(form, 'hidden', 'block_name');
        var blockId = thwec_base.get_property_field_value(form, 'hidden', 'block_id');
        save_builder_block_data(form);
        setup_template_builder();

    }

    function builder_container_css(form){ 
        $.each( ELM_BLOCK_FORM_PROPS, function( key, props ) {
            var value = thwec_base.get_property_field_value(form, props['type'], props['name']);
            if(value!=''){
                $('#tb_temp_builder').css(CSS_PROPS[key]['name'],value);
            }
        });
    }

    function prepare_builder_block_form(popup, blockName){
        if(is_layout_block(blockName)){ 
            popup.find('.thwec_field_form_general').html($('#thwec_field_form_id_row').html());
        }else if(is_layout_column(blockName)){ 
            popup.find('.thwec_field_form_general').html($('#thwec_field_form_id_col').html());
        }else{
            popup.find('.thwec_field_form_general').html($('#thwec_field_form_id_'+blockName).html());
        }  
    }

    function prepare_builder_block_edit_form(popup, blockName){
        if(is_layout_block(blockName)){ 
            popup.find('.thwec_field_form_edit').html($('#thwec_field_form_id_row').html());
        }else if(is_layout_column(blockName)){ 
            popup.find('.thwec_field_form_edit').html($('#thwec_field_form_id_col').html());
        }else{
            popup.find('.thwec_field_form_edit').html($('#thwec_field_form_id_'+blockName).html());
        }  
    }
  

     function prepare_new_block_content_html(blockName){
        var block_elm = '';
        var elm_type = '';
        if(blockName == "one_column"){
            block_elm = $('#thwec_template_layout_1_col');
            elm_type = 'layout';

        }else if(blockName == "two_column"){
            block_elm = $('#thwec_template_layout_2_col');
            elm_type = 'layout';

        }else if(blockName == "three_column"){
            block_elm = $('#thwec_template_layout_3_col');
            elm_type = 'layout';

        }else if(blockName == "four_column"){
            block_elm = $('#thwec_template_layout_4_col');
            elm_type = 'layout';

        }else if(blockName == "left-large-column"){
            block_elm = $('#thwec_template_layout_left_large_col');
            elm_type = 'layout';

        }else if(blockName == "right-large-column"){
            block_elm = $('#thwec_template_layout_right_large_col');
            elm_type = 'layout';

        }else if(blockName == "gallery-column"){
            block_elm = $('#thwec_template_layout_gallery_col');
            elm_type = 'layout';

        }else if(blockName == "header_details"){
            block_elm = $('#thwec_template_elm_header');
            elm_type = 'block';

        }else if(blockName == "footer_details"){
            block_elm = $('#thwec_template_elm_footer');
            elm_type = 'block';

        }else if(blockName == "customer_address"){
            block_elm = $('#thwec_template_elm_customer_address');
            elm_type = 'block';
            
        }else if(blockName == "order_details"){
            block_elm = $('#thwec_template_elm_order_details');
            elm_type = 'block';
            
        }else if(blockName == "billing_address"){
            block_elm = $('#thwec_template_elm_billing_address');
            elm_type = 'block';
            
        }else if(blockName == "shipping_address"){
            block_elm = $('#thwec_template_elm_shipping_address');
            elm_type = 'block';
            
        }else if(blockName == "text"){
            block_elm = $('#thwec_template_elm_text');
            elm_type = 'block';
            
        }else if(blockName == "image"){
            block_elm = $('#thwec_template_elm_image');
            elm_type = 'block';
            
        }else if(blockName == "social"){
            block_elm = $('#thwec_template_elm_social');
            elm_type = 'block';
            
        }else if(blockName == "button"){
            block_elm = $('#thwec_template_elm_button');
            elm_type = 'block';
            
        }else if(blockName == "divider"){
            block_elm = $('#thwec_template_elm_divider');
            elm_type = 'block';
            
        }else if(blockName == "gap"){
            block_elm = $('#thwec_template_elm_gap');
            elm_type = 'block';
            
        }else if(blockName == "gif"){
            block_elm = $('#thwec_template_elm_gif');
            elm_type = 'block';
            
        }else if(blockName == "video"){
            block_elm = $('#thwec_template_elm_video');  
            elm_type = 'block';

        }else if(blockName == "email_header"){
            block_elm = $('#thwec_template_hook_email_header');
            elm_type = 'hook';  

        }else if(blockName == "email_order_details"){
            block_elm = $('#thwec_template_hook_order_details'); 
            elm_type = 'hook'; 

        }else if(blockName == "before_order_table"){
            block_elm = $('#thwec_template_hook_before_order_table');  
            elm_type = 'hook';

        }else if(blockName == "after_order_table"){
            block_elm = $('#thwec_template_hook_after_order_table');
            elm_type = 'hook';  

        }else if(blockName == "order_meta"){
            block_elm = $('#thwec_template_hook_order_meta');
            elm_type = 'hook';  

        }else if(blockName == "customer_details"){
            block_elm = $('#thwec_template_hook_customer_details');
            elm_type = 'hook';  

        }else if(blockName == "email_footer"){
            block_elm = $('#thwec_template_hook_email_footer');  
            elm_type = 'hook';
        }else if(blockName == "downloadable_product"){
            block_elm = $('#thwec_template_downloadable_product');
            elm_type = 'hook';
        }

        var block_html = '';
        if(block_elm.length){
            if(elm_type == 'layout'){
                block_html = clean_block_content_layout_html(block_elm,blockName);            
            }else if(elm_type == 'hook'){
                block_html = clean_block_content_element_html(block_elm,blockName,'hook');
            }else{
                block_html = clean_block_content_element_html(block_elm,blockName,'element');
            }
            // block_html = block_elm.html();
            // console.log(block_elm.html());
        }
        return block_html;
    }



    function save_builder_block_data(form){
        var block_id = thwec_base.get_property_field_value(form, 'hidden', 'block_id');
        var block_name = thwec_base.get_property_field_value(form, 'hidden', 'block_name');
        // if(block_name == 'image' || block_name == 'header_details'){ console.log('seperate_function');
        //     hide_image_if_empty(form,block_id);
        // }
        var css_props = {};
        var js_props = {};
        var url_props = {};
        // // console.log(form.find('textarea[name="i_textarea_content"]').val()); 
        $.each( ELM_BLOCK_FORM_PROPS, function( key, props ) {
            var value = thwec_base.get_property_field_value(form, props['type'], props['name']);
            if(typeof props['attribute'] != 'undefined'){
                js_props[key] = value;
                // console.log(js_props);
            //     // key in  VISIBLE_OPTIONS ? css_props[key] = value : '';
            }else{
                css_props[key] = value;
            }
            
            if(key in  VISIBLE_OPTIONS){
                url_props[key] = value;
                value = (value !== '') ? VISIBLE_OPTIONS[key]['css'] : 'none';
            }
            if($.inArray(key, IMG_CSS) !== -1 && value){
                value = 'url('+value+')';
            }
            else if(key == 'product_img'){
                value = (value == 1) ? 'block' : 'none';
                set_order_table_product_image(value,block_id);
            }else if(key == 'font_family' && value in FONT_LIST){
                value = FONT_LIST[value];
            }
            // // props['name'] == 'textarea_content' ? console.log(props['name']+' - '+props['type']+' - '+value) : '';


            css_props[key] = value;
        });
        // $.extend(css_props, js_props);
        var props_json = JSON.stringify(css_props);
        var url_props = JSON.stringify(url_props);
        $('#tb_'+block_id).attr('data-props',props_json);
        $('#tb_'+block_id).attr('data-social',url_props);
        prepare_css_functions(css_props, block_id, block_name);
        prepare_text_override(js_props, block_id, block_name);
    }

    // function hide_image_if_empty(form,id,name){
    //     var class_name = name == 'header_details' ? ' .header-logo-ph' : ''; 
    //     var url_val = thwec_base.get_property_field_value(form, 'text', 'url');
    //     var value = url_val == '' ? 'none' : 'block';
    //     console.log($('#tb_temp_builder').find('#tb_'+id+class_name));
    //     $('#tb_temp_builder').find('#tb_'+id+class_name).css('display',value);
    // }

    function set_order_table_product_image(value,block_id){
        var elm = $('#tb_'+block_id).find('.thwec-order-item-img');
        if(value == 'block'){
            $('#tb_'+block_id).find('.thwec-order-item-img').addClass('show-product-img');
        }else if(value == 'none'){    
            if(elm.hasClass('show-product-img')){
                $('#tb_'+block_id).find('.thwec-order-item-img').removeClass('show-product-img');
            }
        }
        
    }

    function prepare_css_functions(props, block_id, block_name){
        var tb_css_override_elm = $('#thwec_template_css_override');
        var tb_css_override = tb_css_override_elm.html();
        tb_css_override += prepare_css_override(props, 'tb_'+block_id, block_name, true);
        tb_css_override_elm.html(tb_css_override);

        var prev_css_override_elm = $('#thwec_template_css_preview_override');
        var prev_css_override = prev_css_override_elm.html();
        prev_css_override += prepare_css_override(props, 'tp_'+block_id, block_name, true);
        prev_css_override_elm.html(prev_css_override);
    }

    function prepare_text_override(js_props, block_id, block_name){ 
        block_id = 'tb_'+block_id;
        var text_elm_class = prepare_text_attributes(js_props, block_id, block_name);
        $.each(text_elm_class, function( name, props) {
            var block_ref = $('#'+block_id).find(props['class']); 
            // alert(js_props[name]);
            block_ref = block_ref.length < 1  ? $('#'+block_id) : block_ref;
            if(block_ref){ 
                if(props['attribute'] == 'image' ){
                    // js_props[name] == '' ? block_ref.css('display','none') : block_ref.css('display','block'); console.log(js_props[name]);
                    block_ref.attr('src', js_props[name]);

                }else if(props['attribute'] == 'html'){
                    block_ref.html(js_props[name]);

                }else if(props['attribute'] == 'link'){
                    block_ref.attr('href', js_props[name]);

                }else if(props['attribute'] == 'title'){
                    block_ref.attr('title', js_props[name]);
                } 
            }
        });
    }

    function prepare_text_attributes(js_props, block_id, block_name){
        var text_css = {};
        switch(block_name){
            case 'header_details':
                text_css =  {
                    content : {'class' : '.header-text h1', 'attribute' : 'html'},
                    url : {'class' : '.header-logo-ph img', 'attribute' : 'image'},
                };
                break;
            case 'footer_details' :
                text_css =  {
                    textarea_content : {'class' : '.footer-text', 'attribute' : 'html'},
                };
                break;
            case 'customer_address' :
                text_css =  {
                    content : {'class' : '.thwec-customer-header', 'attribute' : 'html'},
                };
                break;
            case 'order_details' :
                text_css = { 
                    content : {'class' : '.thwec-order-heading .order-title', 'attribute' : 'html'},
                };
                break;
            case 'billing_address' :
                text_css =  {
                    content : {'class' : '.thwec-billing-header', 'attribute' : 'html'},
                };
                break;   
            case 'shipping_address' :
                text_css =  {
                    content : {'class' : '.thwec-shipping-header', 'attribute' : 'html'},
                };
                break; 
            case 'text' :
                text_css = {
                    textarea_content : {'class' : '.thwec-block-text', 'attribute' : 'html'},
                };
                break;
            case 'image' :
                text_css = {
                    url : {'class' : 'img', 'attribute' : 'image'},
                };
                break;  
            case 'social' :
                text_css = {
                    block_text_url1 : {'class' : '.facebook', 'attribute' : 'link'},
                    block_text_url2 : {'class' : '.gmail', 'attribute' : 'link'},
                    block_text_url3 : {'class' : '.twitter', 'attribute' : 'link'},
                    block_text_url4 : {'class' : '.youtube', 'attribute' : 'link'},
                    block_text_url5 : {'class' : '.linkedin', 'attribute' : 'link'},
                    block_text_url6 : {'class' : '.pinterest', 'attribute' : 'link'},
                    block_text_url7 : {'class' : '.instagram', 'attribute' : 'link'},
                };
                break;
            case 'button' :
                text_css = {
                    content : {'class' : '.thwec-button-link', 'attribute' : 'html'},
                    url : {'class' : '.thwec-button-link', 'attribute' : 'link'},
                    title : {'class' : '.thwec-button-link', 'attribute' : 'title'},
                };
                break;
            case 'menu' :
                text_css = {
                    block_text_url1 : {'class' : '.menu-item1 .menu-link', 'attribute' : 'link'},
                    block_text_url2 : {'class' : '.menu-item2 .menu-link', 'attribute' : 'link'},
                    block_text_url3 : {'class' : '.menu-item3 .menu-link', 'attribute' : 'link'},
                    block_text_url4 : {'class' : '.menu-item4 .menu-link', 'attribute' : 'link'},
                    block_text_url5 : {'class' : '.menu-item5 .menu-link', 'attribute' : 'link'},
                    block_text1 : {'class' : '.menu-item1 .menu-link', 'attribute' : 'html'},
                    block_text2 : {'class' : '.menu-item2 .menu-link', 'attribute' : 'html'},
                    block_text3 : {'class' : '.menu-item3 .menu-link', 'attribute' : 'html'},
                    block_text4 : {'class' : '.menu-item4 .menu-link', 'attribute' : 'html'},
                    block_text5 : {'class' : '.menu-item5 .menu-link', 'attribute' : 'html'},
                };
                break;
            case 'gif' :
                text_css = {
                    url : {'class' : 'img', 'attribute' : 'image'},
                };
                break;
            default    :
                text_css = '';
        }
        return text_css;
    }

    function prepare_css_override(props, blockId, blockName, isPrev){
        var css = '';
        switch(blockName) {
            case 'one_column':
            case 'two_column':
            case 'three_column':
            case 'four_column':
            case 'row_clone':
                css = prepare_css_override_layout_row(props, blockId, isPrev);
                break;
            case 'one_column_one':
            case 'two_column_one':
            case 'two_column_two':
            case 'three_column_one':
            case 'three_column_two':
            case 'three_column_three':
            case 'four_column_one':
            case 'four_column_two':
            case 'four_column_three':
            case 'four_column_four':
            case 'column_clone':
                css = prepare_css_override_layout_col(props, blockId, isPrev);
                break;
            case 'header_details':
                css = prepare_css_override_elm_header(props, blockId, isPrev);
                break;
            case 'footer_details':
                css = prepare_css_override_elm_footer(props, blockId, isPrev);
                break;
            case 'customer_address':
                css = prepare_css_override_elm_customer(props, blockId, isPrev);
                break; 
            case 'billing_address':
                css = prepare_css_override_elm_billing(props, blockId, isPrev);
                break; 
            case 'shipping_address':
                css = prepare_css_override_elm_shipping(props, blockId, isPrev);
                break;      
            case 'text':
                css = prepare_css_override_elm_text(props, blockId, isPrev);
                break;
            case 'image':
                css = prepare_css_override_elm_image(props, blockId, isPrev);
                break;                                                                    
            case 'social':
                css = prepare_css_override_elm_social(props, blockId, isPrev);
                break;  
            case 'button':
                css = prepare_css_override_elm_button(props, blockId, isPrev);
                break;       
            case 'order_details':
                css = prepare_css_override_elm_order(props, blockId, isPrev);
                break;  
            case 'gap':
                css = prepare_css_override_elm_gap(props, blockId, isPrev);
                break;  
            case 'divider':
                css = prepare_css_override_elm_divider(props, blockId, isPrev);
                break;      
            case 'gif':
                css = prepare_css_override_elm_gif(props, blockId, isPrev);
                break;  
            case 'temp_builder':
                css = prepare_css_override_builder_container(props, blockId, isPrev);
                break;  
            default:
                css = '';
        }
        return css;
    }


    function setup_builder_block_edit_pp(){
        $('#thwec_builder_block_pp').dialog({
            modal: true,
            minWidth: 600,
            maxHeight: 500,
            // minWidth: 800,
            // maxHeight: 800,
            resizable: false,
            autoOpen: false,
            title:'Customize Settings',
            dialogClass: 'tbuilder-elm-pp',
            buttons: [
                {
                    text: "Cancel",
                    click: function() { 
                        $(this).dialog("close"); 
                        var form = $("#thwec_builder_block_form");
                        if(!POPUP_FLAG){
                            thwec_base.set_property_field_value(form, 'hidden', 'block_name', '', 0);
                                thwec_base.set_property_field_value(form, 'hidden', 'block_id', '', 0);
                        }
                    }  
                },
                {
                    text: "Save Changes",
                    click: function() {
                        var form = $("#thwec_builder_block_form");
                        var result = validate_builder_block_form(form);
                        if(result){
                            // save_builder_block_data(form);
                            save_popup_form_data(form);
                            $(this).dialog("close");
                            //form.submit();
                        }
                    }
                }
            ]
        });

        $('#thwec_builder_edit_block_pp').dialog({
            modal: true,
            minWidth: 800,
            maxHeight: 500,
            resizable: false,
            autoOpen: false,
            title:'Customize Settings',
            dialogClass: 'tbuilder-elm-props-pp',
            buttons: [
                {
                    text: "Cancel",
                    click: function() { 
                        $(this).dialog("close"); 
                        var form = $("#thwec_builder_block_edit_form");
                        thwec_base.set_property_field_value(form, 'hidden', 'block_name', '', 0);
                        thwec_base.set_property_field_value(form, 'hidden', 'block_id', '', 0);
                    }  
                },
                {
                    text: "Save Changes",
                    click: function() {
                        var form = $("#thwec_builder_block_edit_form");
                        // var result = validate_builder_block_form(form);
                        // if(result){
                            // save_builder_block_data(form);
                            save_popup_form_edit_data(form);
                            $('#tb_temp_builder').attr('data-css-change','false');
                            $(this).dialog("close");
                            //form.submit();
                        // }
                    }
                }
            ]
        });
    }

    function validate_builder_block_form(form){
        if(form.find('.elm-selected').length < 1){
            alert('Select any one option to continue');
            return false;
        }else{
            return true;
        }
    }



    /*----------------------------------------------
    *---- POPUP Functions  -  END ------------------
    *----------------------------------------------*/
    

    /*----------------------------------------------
    *---- Styling Functions  -  START --------------
    *----------------------------------------------*/


     function prepare_css_override_layout_row(props, blockId, isPrev){
        var p_selector = get_css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".thwec-row"; 

        var row_props = prepare_css_props(props, ['width', 'height', 'align', 'bg_color', 'bg_image', 'bg_size', 'bg_position', 'bg_repeat', 'p_t', 'p_r', 'p_b', 'p_l', 'm_t', 'm_r', 'm_b', 'm_l', 'b_t', 'b_r', 'b_b', 'b_l', 'border_style', 'border_color', 'cellspacing']);
        var row_css = w_selector+' {';
        row_css += prepare_css(row_props);
        row_css += '}';

        return row_css;
    }

    function prepare_css_override_layout_col(props, blockId, isPrev){
        var p_selector = get_css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".column-padding{";

        var col_props = prepare_css_props(props, ['width', 'height', 'text_align', 'p_t', 'p_r', 'p_b', 'p_l', 'b_t', 'b_r', 'b_b', 'b_l', 'border_style', 'border_color', 'bg_color', 'bg_image', 'bg_size', 'bg_size', 'bg_repeat', 'bg_position']);
        var col_css = w_selector;
        col_css += prepare_css(col_props);
        col_css += '}';
        return col_css;
    }

    function prepare_css_override_elm_header(props, blockId, isPrev){ 
        var p_selector = get_css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".thwec-block-header";

        var w_props = prepare_css_props(props, ['width', 'height', 'bg_color', 'bg_image', 'bg_repeat', 'bg_position', 'bg_size', 'm_t', 'm_r', 'm_b', 'm_l', 'b_t', 'b_r', 'b_b', 'b_l', 'border_style', 'border_color']);
        var w_css = w_selector+'{';
        w_css += prepare_css(w_props);
        w_css += '}';

        var img_props = prepare_css_props(props, ['url', 'img_p_t', 'img_p_r', 'img_p_b', 'img_p_l', 'img_m_t', 'img_m_r', 'img_m_b', 'img_m_l', 'img_height','img_width','img_border_width_top','img_border_width_right', 'img_border_width_bottom', 'img_border_width_left', 'img_border_color', 'img_border_style', 'img_p_t', 'img_p_r', 'img_p_b', 'img_p_l', 'img_m_t', 'img_m_r', 'img_m_b', 'img_m_l', 'img_bg_color', 'align']);
        var img_css = w_selector+' .header-logo .header-logo-ph{';
        img_css += prepare_css(img_props);
        img_css += '}';

        var h1_props = prepare_css_props(props, ['font_size', 'color','font_weight','text_align', 'line_height','font_family']);
        var h1_css = w_selector+' .header-text h1{';
        h1_css += prepare_css(h1_props);
        h1_css += '}';

        return w_css+img_css+h1_css;
    }

    function prepare_css_override_elm_footer(props, blockId, isPrev){
        var p_selector = get_css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".thwec-block-footer";

        var tb_props = prepare_css_props(props, ['width', 'height', 'bg_color', 'bg_image', 'bg_repeat', 'bg_position', 'bg_size', 'm_t', 'm_r', 'm_b', 'm_l', 'b_t', 'b_r', 'b_b', 'b_l', 'border_style', 'border_color']);
        var tb_css = w_selector+'{';
        tb_css += prepare_css(tb_props);
        tb_css += '}';


        var w_props = prepare_css_props(props, ['p_t', 'p_r', 'p_b', 'p_l']);
        var w_css = w_selector+' .footer-padding{';
        w_css += prepare_css(w_props);
        w_css += '}';

        var t1_props = prepare_css_props(props, ['color', 'font_weight', 'font_size', 'text_align']);
        var t1_css = w_selector+' .footer-padding p{';
        t1_css += prepare_css(t1_props);
        t1_css += '}';


        return tb_css+w_css+t1_css;
    }



     function prepare_css_override_elm_text(props, blockId, isPrev){
        var p_selector = get_css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".thwec-block-text";

        // var table_props = prepare_css_props(props, ['width', 'height', 'align', 'm_t', 'm_r', 'm_b', 'm_l', 'bg_color', 'bg_image', 'bg_size', 'bg_position', 'bg_repeat', 'p_t', 'p_r', 'p_b', 'p_l', 'b_t', 'b_r', 'b_b', 'b_l', 'border_color', 'border_style', 'color', 'font_size', 'font_weight', 'line_height', 'text_align','font_family']);
        // var table_css = w_selector+' *{';
        // table_css += prepare_css(table_props);
        // table_css += '}';

        var table_props = prepare_css_props(props, ['width', 'height', 'align', 'm_t', 'm_r', 'm_b', 'm_l', 'p_t', 'p_r', 'p_b', 'p_l', 'bg_color', 'bg_image', 'bg_size', 'bg_position', 'bg_repeat', 'b_t', 'b_r', 'b_b', 'b_l', 'border_color', 'border_style', 'color', 'font_size', 'font_weight', 'line_height', 'text_align','font_family']);
        var table_css = w_selector+'{';
        table_css += prepare_css(table_props);
        table_css += '}';

        var elm_props = prepare_css_props(props, ['color', 'font_size', 'font_weight', 'line_height', 'text_align','font_family']);
        var elm_css = w_selector+' *{';
        elm_css += prepare_css(elm_props);
        elm_css += '}';

        // return table_css;
        return table_css+elm_css;
    }

    function prepare_css_override_elm_image(props, blockId, isPrev){
        var p_selector = get_css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".thwec-block-image";

        var w_props = prepare_css_props(props, ['img_width', 'img_height', 'align', 'img_m_t', 'img_m_r', 'img_m_b', 'img_m_l', 'bg_color', 'bg_image', 'bg_size', 'bg_position', 'bg_repeat', 'img_p_t', 'img_p_r', 'img_p_b', 'img_p_l', 'img_border_width_top', 'img_border_width_right', 'img_border_width_bottom', 'img_border_width_left', 'img_border_style', 'img_border_color', 'img_bg_color']);
        var w_css = w_selector+'{';
        w_css += prepare_css(w_props);
        w_css += '}';

        return w_css;
    }    

    function prepare_css_override_elm_social(props, blockId, isPrev){
        var p_selector = get_css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".thwec-block-social";

        var tb_props = prepare_css_props(props, ['width', 'height', 'text_align', 'm_t', 'm_r', 'm_b', 'm_l','p_t', 'p_r', 'p_b', 'p_l', 'bg_color', 'bg_image', 'bg_size', 'bg_position', 'bg_repeat', 'b_t', 'b_r', 'b_b', 'b_l', 'border_style', 'border_color']);
        var w_css = w_selector+'{';
        w_css += prepare_css(tb_props);
        w_css += '}';


        var icons_props = prepare_css_props(props, [ 'img_p_t', 'img_p_r', 'img_p_b', 'img_p_l', 'img_width', 'img_height']);
        var icons_css = w_selector+' .thwec-social-icon{';
        icons_css += prepare_css(icons_props);
        icons_css += '}';

        var icon1_props = prepare_css_props(props, ['url1']);
        var icon1_css = w_selector+' .facebook{';
        icon1_css += prepare_css(icon1_props);
        icon1_css += '}';

        var icon2_props = prepare_css_props(props, ['url2']);
        var icon2_css = w_selector+' .gmail{';
        icon2_css += prepare_css(icon2_props);
        icon2_css += '}';

        var icon3_props = prepare_css_props(props, ['url3']);
        var icon3_css = w_selector+' .twitter{';
        icon3_css += prepare_css(icon3_props);
        icon3_css += '}';

        var icon4_props = prepare_css_props(props, ['url4']);
        var icon4_css = w_selector+' .youtube{';
        icon4_css += prepare_css(icon4_props);
        icon4_css += '}';

        var icon5_props = prepare_css_props(props, ['url5']);
        var icon5_css = w_selector+' .linkedin{';
        icon5_css += prepare_css(icon5_props);
        icon5_css += '}';

        var icon6_props = prepare_css_props(props, ['url6']);
        var icon6_css = w_selector+' .pinterest{';
        icon6_css += prepare_css(icon6_props);
        icon6_css += '}';

        var icon7_props = prepare_css_props(props, ['url7']);
        var icon7_css = w_selector+' .instagram{';
        icon7_css += prepare_css(icon7_props);
        icon7_css += '}';

        return w_css+icons_css+icon1_css+icon2_css+icon3_css+icon4_css+icon5_css+icon6_css+icon7_css;
    }


    function prepare_css_override_elm_button(props, blockId, isPrev){
        var p_selector = get_css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".thwec-button-link";

        var link_props = prepare_css_props(props, ['align', 'font_weight', 'line_height', 'font_size','font_family','color', 'width', 'height', 'm_t', 'm_r', 'm_b', 'm_l', 'bg_color','bg_image', 'bg_repeat', 'bg_size', 'bg_position', 'b_t', 'b_b', 'b_l', 'b_r',  'border_style', 'border_color','p_t', 'p_r', 'p_b', 'p_l', 'text_align']);
        var link_css = w_selector+'{';
        link_css += prepare_css(link_props);
        link_css += '}';        

        return link_css;
    }

    function prepare_css_override_elm_customer(props, blockId, isPrev){
        var p_selector = get_css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".thwec-block-customer";

        var tb_props = prepare_css_props(props, ['align', 'width', 'height', 'bg_color', 'bg_image', 'bg_size', 'bg_position', 'bg_repeat', 'b_t', 'b_r', 'b_b', 'b_l', 'border_style', 'border_color', 'm_t', 'm_r', 'm_b', 'm_l']);
        var tb_css = w_selector+'{';
        tb_css += prepare_css(tb_props);
        tb_css += '}';

        var w_props = prepare_css_props(props, ['p_t', 'p_r', 'p_b', 'p_l']);
        var w_css = w_selector+' .customer-padding{';
        w_css += prepare_css(w_props);
        w_css += '}';

        var h2_props = prepare_css_props(props, ['font_size', 'color','text_align','font_weight','line_height']);
        var h2_css = w_selector+' .thwec-customer-header{';
        h2_css += prepare_css(h2_props);
        h2_css += '}';

        var details_props = prepare_css_props(props, ['details_font_size', 'details_color','details_text_align','details_font_family','details_font_weight','details_line_height']);
        var details_css = w_selector+' .thwec-customer-body{';
        details_css += prepare_css(details_props);
        details_css += '}';

        return tb_css+w_css+h2_css+details_css;
    }

    function prepare_css_override_elm_billing(props, blockId, isPrev){
        var p_selector = get_css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".thwec-block-billing";

        var tb_props = prepare_css_props(props, ['align', 'width', 'height', 'bg_color', 'bg_image', 'bg_size', 'bg_position', 'bg_repeat', 'b_t', 'b_r', 'b_b', 'b_l', 'border_style', 'border_color', 'm_t', 'm_r', 'm_b', 'm_l']);
        var tb_css = w_selector+'{';
        tb_css += prepare_css(tb_props);
        tb_css += '}';

        var w_props = prepare_css_props(props, ['p_t', 'p_r', 'p_b', 'p_l']);
        var w_css = w_selector+' .billing-padding{';
        w_css += prepare_css(w_props);
        w_css += '}';

        var h2_props = prepare_css_props(props, ['font_size', 'color','text_align','font_weight','line_height']);
        var h2_css = w_selector+' .thwec-billing-header{';
        h2_css += prepare_css(h2_props);
        h2_css += '}';

        var details_props = prepare_css_props(props, ['details_font_size', 'details_color','details_text_align','details_font_family','details_font_weight','details_line_height']);
        var details_css = w_selector+' .thwec-billing-body{';
        details_css += prepare_css(details_props);
        details_css += '}';

        return tb_css+w_css+h2_css+details_css;
    }

    function prepare_css_override_elm_shipping(props, blockId, isPrev){
        var p_selector = get_css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".thwec-block-shipping";

        var tb_props = prepare_css_props(props, ['align', 'width', 'height', 'bg_color', 'bg_image', 'bg_size', 'bg_position', 'bg_repeat', 'b_t', 'b_r', 'b_b', 'b_l', 'border_style', 'border_color', 'm_t', 'm_r', 'm_b', 'm_l']);
        var tb_css = w_selector+'{';
        tb_css += prepare_css(tb_props);
        tb_css += '}';

        var w_props = prepare_css_props(props, ['p_t', 'p_r', 'p_b', 'p_l']);
        var w_css = w_selector+' .shipping-padding{';
        w_css += prepare_css(w_props);
        w_css += '}';

        var h2_props = prepare_css_props(props, ['font_size', 'color','text_align','font_weight','line_height']);
        var h2_css = w_selector+' .thwec-shipping-header{';
        h2_css += prepare_css(h2_props);
        h2_css += '}';

        var details_props = prepare_css_props(props, ['details_font_size', 'details_color','details_text_align','details_font_family','details_font_weight','details_line_height']);
        var details_css = w_selector+' .thwec-shipping-body{';
        details_css += prepare_css(details_props);
        details_css += '}';

        return tb_css+w_css+h2_css+details_css;
    }

    function prepare_css_override_elm_order(props, blockId, isPrev){
        var p_selector = get_css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".thwec-block-order";

        var elm_props = prepare_css_props(props, ['align', 'width', 'height', 'bg_color', 'm_t', 'm_r', 'm_b', 'm_l', 'bg_image', 'bg_size', 'bg_repeat', 'bg_repeat', 'b_t', 'b_r', 'b_b', 'b_l', 'border_style', 'border_color']);
        var elm_css = w_selector+'{';
        elm_css += prepare_css(elm_props);
        elm_css += '}';

        var w_props = prepare_css_props(props, ['p_t', 'p_r', 'p_b', 'p_l']);
        var w_css = w_selector+' .order-padding{';
        w_css += prepare_css(w_props);
        w_css += '}';

        var h2_props = prepare_css_props(props, ['color', 'font_size', 'text_align', 'font_weight', 'line_height','font_family']);
        var h2_css = w_selector+' .thwec-order-heading{';
        h2_css += prepare_css(h2_props);
        h2_css += '}';

        var table_props = prepare_css_props(props, [ 'content_width', 'content_height', 'content_bg_color', 'content_border_color', 'content_p_t', 'content_p_r', 'content_p_b', 'content_p_l', 'content_m_t', 'content_m_r', 'content_m_b', 'content_m_l','details_font_family']);
        var table_css = w_selector+' .thwec-order-table{';
        table_css += prepare_css(table_props);
        table_css += '}';

        var tb_content_props = prepare_css_props(props, ['details_font_size', 'details_color', 'details_text_align','details_font_family','content_border_color', 'details_font_weight', 'details_line_heights']);
        var tb_content_css = w_selector+' .thwec-td{';
        tb_content_css += prepare_css(tb_content_props);
        tb_content_css += '}';      

        var tb_image_props = prepare_css_props(props, ['product_img']);
        var tb_image_css = w_selector+' .thwec-td .thwec-order-item-img{';
        tb_image_css += prepare_css(tb_image_props);
        tb_image_css += '}';    

        return elm_css+w_css+h2_css+table_css+tb_content_css+tb_image_css;
    }



    function prepare_css_override_elm_gap(props, blockId, isPrev){
        var p_selector = get_css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".thwec-block-gap";

        var w_props = prepare_css_props(props, ['width', 'height', 'm_t', 'm_r', 'm_b', 'm_l', 'bg_color','bg_image', 'bg_repeat', 'bg_size', 'bg_position', 'b_t', 'b_b', 'b_l', 'b_r',  'border_style', 'border_color','p_t', 'p_r', 'p_b', 'p_l']);
        var w_css = w_selector+'{';
        w_css += prepare_css(w_props);
        w_css += '}';

        return w_css;
    }


    function prepare_css_override_elm_divider(props, blockId, isPrev){
        var p_selector = get_css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".thwec-block-divider";
        
        var hr_props = prepare_css_props(props, ['width', 'divider_width', 'divider_color', 'divider_style', 'm_t', 'm_r', 'm_b', 'm_l',]);
        var hr_css = w_selector+'{';
        hr_css += prepare_css(hr_props);
        hr_css += '}';

        return hr_css;   
    }

    function prepare_css_override_elm_gif(props, blockId, isPrev){
        var p_selector = get_css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".thwec-block-gif";

        var tb_props = prepare_css_props(props, ['align', 'width', 'height', 'bg_image', 'bg_position', 'bg_size', 'bg_repeat', 'bg_color', 'm_t', 'm_r', 'm_b', 'm_l', 'b_t', 'b_r', 'b_b', 'b_l', 'border_color', 'border_style', 'p_t', 'p_r', 'p_b', 'p_l']);
        var tb_css = w_selector+'{';
        tb_css += prepare_css(tb_props);
        tb_css += '}';

        return tb_css;
    }

    function prepare_css_override_builder_container(props, blockId, isPrev){
        var p_selector = get_css_parent_selector(blockId, isPrev);
        var w_selector = p_selector+".main-builder";

        var tb_props = prepare_css_props(props, ['b_t', 'b_r', 'b_b', 'b_l', 'border_style', 'border_color', 'bg_size', 'bg_repeat', 'bg_color', 'bg_image', 'bg_position']);
        var tb_css = w_selector+'{';
        tb_css += prepare_css(tb_props);
        tb_css += '}';

        return tb_css;
    }

    /*----------------------------------------------
    *---- Styling Functions  -  END ----------------
    *----------------------------------------------*/

    function setup_image_uploader(elm,prop){  
        var frame;
        frame = wp.media({
            title: 'Upload Media Of Your Interest',
            button: {
                text: "Choose this" 
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });
        frame.open();
        frame.on( 'select', function() { 
            // Get media attachment details from the frame state
            var thwec_admin_attachment = frame.state().get('selection').first().toJSON();
            var thwec_admin_attachment_url = thwec_admin_attachment['url'];
            var form_table = $('#thwec_builder_edit_block_pp');
            if(prop=='bg_image'){
                thwec_base.set_property_field_value(form_table, 'text', 'bg_image', thwec_admin_attachment_url, 0);
            }else if(prop == 'image'){
                thwec_base.set_property_field_value(form_table, 'text', 'url', thwec_admin_attachment_url, 0);
            }
            form_table.find('.img_preview_'+prop).html('<img src="'+thwec_admin_attachment_url+'"/>');
        }); 
    }
        
    function clear_tbuilder(elm){
        $('#tb_temp_builder').empty();
        $('ul.tracking-list').empty();
        setup_blank_track_panel_msg();
    }

function builder_block_clone(elm){
        var clone_status = '';
        var select_block = $(elm).closest('.thwec-settings');
        var selected_li = select_block.closest('li');
        var selected_li_class = select_block.closest('li').attr('class');
        var selected_id = select_block.closest('li').attr('id');
        var selected_builder_block = $('#tb_temp_builder').find('#tb_'+selected_id);
        var panel_clone = selected_li.clone([true][true]);
        var builder_clone = selected_builder_block.clone([true][true]);
        panel_clone.find('.settings-active').removeClass('settings-active');
        // console.log(panel_clone.html());
       
       var id = panel_clone.attr('id');
       var parent_id = panel_clone.attr('data-parent');
       var new_id = get_random_string();
       var data_props = panel_clone.attr('data-props');
       var data_social = panel_clone.attr('data-social');
       panel_clone.attr('id',new_id);
       panel_clone.find('> .layout-lis-item > .thwec-settings > .settings-expand').html(function(i, oldHTML) {
            return oldHTML.replace(id, new_id);
        }); // Change id of edit link inside li 

        if(panel_clone.hasClass('rows')){
            panel_clone.find('> .sorting-pointer > li').each(function(index, el) {
                if($(this).attr('data-parent')){
                    $(this).attr('data-parent',new_id);
                }
             });
        }
        builder_clone.attr('id','tb_'+new_id);
        setup_clone_css(builder_clone,new_id);
        if(panel_clone.find('[id]')){           // Means either it is a row or column with element(not blank column)
            panel_clone.find('[id]').each(function(index, el) { 
                var find = $(this).attr('id');
                var find_class = $(this).attr('class');
                if(find_class == 'rows'){ // if nested row 
                    $(this).find('> .sorting > li').each(function(index, el) {
                        if($(this).attr('data-parent')){
                            $(this).attr('data-parent',id);
                        }
                    });
                }
                var replace = get_random_string();
                $(this).attr('id',replace);
                $(this).find('> .layout-lis-item > .thwec-settings > .settings-expand').html(function(i, oldHTML) {
                    return oldHTML.replace(find, replace);
                 });
                // builder_clone.find('#tb_'+find).attr('id','tb_'+replace);
                var builder_obj =  builder_clone.find('#tb_'+find);
                setup_clone_css(builder_obj,replace);
                builder_clone.find('#tb_'+find).attr('id','tb_'+replace);
            });
        }
        if(selected_li.hasClass('rows')){
            clone_status = true;

        }else if(selected_li.hasClass('columns')){
            var result = confirm($('#add_col_confirm').html());
            if(result){
                clone_status = true;    
            }else{
                clone_status = false;
            }

        }else if(selected_li.hasClass('element-list')){
            clone_status=true;
        }

        if(clone_status){
            $(builder_clone).insertAfter(selected_builder_block);
            $(panel_clone).insertAfter(selected_li);
            if(selected_li_class == 'columns'){ 
                var cols = $('#'+parent_id).attr('data-columns');
                var col_count = parseInt(cols)+1;
                $('#'+parent_id).attr('data-columns',col_count);
                reset_col_width(parent_id, col_count);
            }

        }
        setup_template_builder();
    }    
    
    function setup_clone_css(builder_obj,replace){
        if(builder_obj.attr('data-props')){
            var builder_css = builder_obj.attr('data-props');
            builder_css = JSON.parse(builder_css);
            if(builder_obj.attr('data-block-name')){
                var obj_type = builder_obj.attr('data-block-name');
            }
            else if(builder_obj.hasClass('thwec-row')){
                var obj_type = 'row_clone';
            }else if(builder_obj.hasClass('thwec-col')){
                var obj_type = 'column_clone';
            }
            prepare_css_functions(builder_css, replace, obj_type);
        }
    }

    return {
        initialize_tbuilder : initialize_tbuilder,
        open_builder_block_edit_pp : open_builder_block_edit_pp,
        // prepare_new_block_id : prepare_new_block_id,
        // prepare_new_block_html : prepare_new_block_html,
        // create_new_block : create_new_block,
        builder_block_delete : builder_block_delete,
        builder_block_clone : builder_block_clone,
        // prepare_css_functions : prepare_css_functions,
        clear_tbuilder : clear_tbuilder,
        //setup_template_builder : setup_template_builder,
        //id_generator : id_generator,
        //thwec_prepare_popup : thwec_prepare_popup,
        setup_image_uploader : setup_image_uploader,

        template_action_add_row : template_action_add_row,
    };
}(window.jQuery, window, document));    

function thwecBuilderBlockEdit(elm, blockId, blockName){
    thwec_tbuilder.open_builder_block_edit_pp(elm, blockId, blockName);    
}

function thwecClearTemplateBuilder(elm){
    thwec_tbuilder.clear_tbuilder(elm);
}

function thwecBuilderBlockDelete(elm){
    thwec_tbuilder.builder_block_delete(elm);    
}
function thwecBuilderBlockClone(elm){
    thwec_tbuilder.builder_block_clone(elm);    
}

// function thwecEditContainer(elm){
//     thwec_tbuilder.builder_container_edit();
// }

function thwecImageUploader(elm,prop){
    thwec_tbuilder.setup_image_uploader(elm,prop);
}

function thwecTActionAddRow(elm){
    thwec_tbuilder.template_action_add_row(elm);
}


var thwec_settings = (function($, window, document) {
  	'use strict';
  	var dataid; 
  	
 
	/*------------------------------------
	*---- ON-LOAD FUNCTIONS - SATRT ----- 
	*------------------------------------*/
	$(function() {
		var settings_form = $('#thwec_settings_fields_form');
		initialize_thwec();
		delete_template_button();
		//image_section();
		//autoload_products_and_categories();
	});
	/*------------------------------------
	*---- ON-LOAD FUNCTIONS - END -------
	*------------------------------------*/

	function initialize_thwec(){
        thwec_base.setup_tiptip_tooltips();
        thwec_tbuilder.initialize_tbuilder();
        setup_preview_pp();
        setup_reload_functions();
    }
    function create_new_template(){
    	location.reload();
    	prepare_page_reload();
    }

    function setup_reload_functions(){

    	window.addEventListener("beforeunload", function (event) {
    		prepare_page_reload();
		});
    }

    function prepare_page_reload(){
    	var nav_tab = $('#tb_temp_builder').parents('.thwec-tbuilder-wrapper').siblings('.woo-nav-tab-wrapper');;
  		var active_nav_tab = nav_tab.find('.nav-tab-active').html();
  	// 	var active_nav_tab = $('.nav-tab nav-tab-active').html();
  		var builder_obj = $('#tb_temp_builder');
  		var block_length = builder_obj.find('.builder-block').length;
    	var data_track = builder_obj.attr('data-track-save');
    	var data_global = builder_obj.attr('data-global-id');
		var data_css = builder_obj.attr('data-css-change');
		if(active_nav_tab == 'General Settings' && block_length > 0 && data_track != data_global){
				event.returnValue = "\o/";
		}else if(data_css=='false'){
			event.returnValue = "\o/";
		}
		else{
			return ;
		}
    }

    function click_tab_functions(elm){
  
    		var active_tab = $(elm).closest('.nav-tab-wrapper').find('.nav-tab-active').html();
    		var builder_obj = $('#tb_temp_builder');
    		var block_length = builder_obj.find('.builder-block').length;
    		var data_track = builder_obj.attr('data-track-save');
    		var data_global = builder_obj.attr('data-global-id');
    		if(active_tab == 'General Settings' && block_length > 0 && data_track != data_global && data_css=='false'){
    			var result = confirm($('#save_changes_confirm').html());
    			if(!result){
    				event.preventDefault();
    			}
    		}
    }

    function save_template(elm){
    	var template_builder = $('#tb_temp_builder');
		var block_length = template_builder.find('.thwec-block').length;
		var tname = $('#template_save_name').val();    
		var tcontent = $('#template_drag_and_drop').innerHTML;
		var tcss = $('#thwec_template_css').html();
		var input_validation = custom_tname_validation(tname);
		if(block_length <= 0){
			alert('Add elements to save the template');
		}else if(block_length > 0 && input_validation){
			if(input_validation == 'empty'){
				alert('Template name is empty');
			}else if(input_validation == 'illegal'){
				alert('Use only letters ([a-z],[A-Z]), digits ([0-9]), hyphen ("-") and underscores ("_") for template name.');
			}else if(input_validation == 'success'){
				template_builder.attr('data-track-save',template_builder.attr('data-global-id'));
				prepare_template_content(tname, tcontent, tcss);
				template_builder.attr('data-css-change','true');
			}
		} 
    }

    function custom_tname_validation(tname){
    	if(tname==''){
    		return 'empty';
    	}else if(/^[a-zA-Z0-9-_ ]*$/.test(tname) == false) {
    		return 'illegal';
		}
    	else{ 
    		return 'success';
    	}
    }

    function prepare_template_content(name, content, css){
    	$('#template_save_name').attr('value', $('#template_save_name').val());
    	
		var render_hooks = true;
		set_preview_template_content(render_hooks);

		var template_data = $('#thwec_tbuilder_editor_preview').html();
		// var content_raw = $('.thwec-tbuilder-editor-wrapper').html();
		var content_raw = $('.thwec-tbuilder-wrapper').wrap('<p/>').parent().html();
		$('.thwec-tbuilder-wrapper').unwrap();
		var content_cleaned = '<div class="thwec_wrapper">'+template_data+'</div>';
		var css_cleaned = css+$('#thwec_template_css_preview_override').html();
		ajax_call_save_data(name, content_raw, content_cleaned, css_cleaned);
    }

    function ajax_call_save_data(name, content_raw, content_cleaned, css_cleaned){
        var sample_data = {
            action: 'thwec_save_email_template',
            template_name: name,
            template_edit_data: content_raw,
            template_render_data:  content_cleaned,
            template_render_css: css_cleaned,
        };
        $.ajax({
            type: 'POST',
            url: ajaxurl,                        
            data: sample_data,
            success:function(data){
                // console.log(data);
                // alert('success');
                if($('#wpbody-content #thwec_message').length > 0){
                	$('#wpbody-content #thwec_message').remove();
                }
                var message = '<div id="thwec_message" class="updated notice is-dismissible">';
                message+= '<p>Template <strong>Saved</strong>.</p></div>';
                $(message).insertBefore($('#wpbody-content').find('> h2')).delay(3000).fadeOut();
                // $('#wpbody-content #thwec_message').remove();
                // $('.thwec-tbuilder-messages p').html('Template Saved Successfully').show().delay(2000).fadeOut();
                // location.reload();
            },
            error: function(){
                alert('error');
            }
        });
    }

    function setup_preview_pp(){
    	if($('#thwec_tbuilder_editor_preview').length == 0){
			var prev_div = '<div id="thwec_tbuilder_editor_preview" class="thwec-tbuilder-editor-preview" style="display: none;"></div>';
			$('.thwec-tbuilder-editor-wrapper').find('.thwec-tbuilder-editor-grid').append(prev_div);
		}
		
        var popup_height = $(window).innerHeight()-50;
        var popup_width = $(window).width();

        var preview_popup = $( '#thwec_tbuilder_editor_preview').dialog({
            autoOpen: false,
            // minWidth: 600,
            width: 650,
            maxHeight: popup_height,
            modal: true,
            title:'Template Preview',
            dialogClass: 'tbuilder-preview-pp',
            buttons: {
                Close: function() {
                    preview_popup.dialog( "close" );
                },
            }
        }); 
    }

    function show_template_preview(elm){
		/* Position of  preview Div is changed once preview is clicked. i.e, ui-dialog classes are applied to div
		and is placed along with other divs on top or header of page. Once a saved template is opened, and edited again and reopened
		,there is no preview div since html() takes content of a particular area which doesn't contain
		the preview div (on top ). So appending a preview_div if page doesn't contain any */ 
		if($('#tb_temp_builder').find('.thwec-block').length > 0){
			var show_hooks = false;
			set_preview_template_content(show_hooks); // Check order of functions
			open_template_preview_pp();
			
		}else{
			alert('Nothing to Preview');
		}
    }

	function set_preview_template_content(show_hooks){
		var preview_html = $('#tb_temp_builder').clone(true);
		clean_preview_panel(preview_html);
		preview_html.find('.btn-add-element').remove();
		
		preview_html.find('.builder-block').each(function(index, el) {
			if(show_hooks){
				var block_name = $(this).data('block-name');
				set_contents_hooks_and_data($(this), block_name);
			}
			if($(this).attr('id')){
				var id = $(this).attr('id');
				id = id.replace('tb_','tp_');
				$(this).attr('id',id);
			}
			if($(this).hasClass('thwec-row')){
				$(this).find(' tbody> tr > .thwec-columns').each(function(index, el) {
					var id = $(this).attr('id');
					id = id.replace('tb_','tp_');
					$(this).attr('id',id);
				});
			}
			// console.log($(this).find('> .thwec-columns').length);
			// if($(this).find('>.thwec-columns')){
				
				// clean_block_element($(this));
			//}//else{
			// 	clean_block_layout($(this));
			// }   

		// preview_html.find('.block_hook').each(function(index, el) {
		// 	$(this).contents().unwrap();
		// });
	});
		preview_html.find('.builder-block').removeClass('builder-block');
		$('#thwec_tbuilder_editor_preview').html(preview_html);
		preview_html.find('[data-props]').each(function(index, el) {
			$(this).removeAttr('data-props');
		});
		preview_html.find('[data-social]').each(function(index, el) {
			$(this).removeAttr('data-social');
		});
	}

	function clean_preview_panel(elm){
		// elm.removeAttr('id');
		elm.find('p.hook-code').each(function(index, el) {
			$(this).removeAttr('data-hook');
		});
		elm.attr('id',elm.attr('id').replace('tb_','tp_'));
		elm.removeClass('thwec-dropable sortable ui-sortable ui-droppable');
		elm.find('.thwec-icon-panel').remove(); // Removing all icon panels of rows
		elm.find('.thwec-columns .dashicons-edit').remove(); // Removing all icon panels inside columns
		elm.find('input[type="hidden"]').remove(); // Removing all hidden fields
		// elm.find('.thwec-columns').css('border','none');
		elm.find('.thwec-columns').css('min-height','0');
		elm.find('.ui-sortable').removeClass('ui-sortable ui-droppable');
	}


	function clean_block_layout(elm){
		var prev_elm_id = elm.attr('data-prev-elm');
		var content_elm = elm.find('> .thwec-row');

		if(content_elm.length){
			content_elm.attr('id', prev_elm_id);
			content_elm.unwrap();
		}
	}

	function clean_block_element(elm){
		var prev_elm_id = elm.attr('data-prev-elm');
		var content_elm = elm.find('> .thwec-block');

		if(content_elm.length){
			content_elm.attr('id', prev_elm_id);
			content_elm.unwrap();
		}
	}

	function set_contents_hooks_and_data(block_elm, block_name){
		if(block_name == 'order_details'){
			
			// calculate_hook_position($(this),'{before_order_hook1}',null,true,false);
			// calculate_hook_position($(this),'{before_order_hook2}',null,true,false);
			//calculate_hook_position($prev_obj,'{after_order_hook1}',null,true,true);
			// calculate_hook_position($prev_obj,'{after_order_hook2}',null,true,true);
			// var order_details = block_elm.find('.thwec-block-order');
			block_elm.find('.thwec-order-heading').html('{order_heading}');
			block_elm.find('.thwec-order-table .woocommerce_order_item_class-filter2').remove();
			block_elm.find('.thwec-order-table .order-footer .order-footer-row:gt(0)').remove();
			
			if(block_elm.find('.thwec-order-item-img').hasClass('show-product-img')){
				block_elm.find('.order-item').html('{order_items_img}');
			}else{
				block_elm.find('.order-item').html('{order_items}');
			}

			block_elm.find('.order-item-qty').html('{order_items_qty}');
			block_elm.find('.order-item-price').html('{order_items_price}');
			block_elm.find('.order-total-label').html('{total_label}');
			block_elm.find('.order-total-value').html('{total_value}');
			
			block_elm.find('.order-head').each(function(index, el) {
				$(this).html('{Order_'+$(this).text()+'}');
			});
		}

		if(block_name == "billing_address"){
			calculate_hook_position(block_elm,'{billing_address}','thwec-billing-body',false,false);
		}
		if(block_name == "shipping_address"){
			calculate_hook_position(block_elm,'{shipping_address}','thwec-shipping-body',false,false);
		}
		if(block_name == "customer_address"){
			calculate_hook_position(block_elm,'{customer_hook}',null,true,false);
			calculate_hook_position(block_elm,'{customer_address}','thwec-customer-body',false,false);
		}
	}

	function calculate_hook_position($obj,$hook_name,$class_name,$control,$position){
		var elm_blk = $obj.closest('.thwec-element-block');
		var elm_col = $obj.closest('.thwec-columns');
		var row_col = $obj.closest('.column_layout');
		var insert = '';

		if($control){
			if(elm_blk.siblings().length){
				insert = elm_blk;
			}else if(elm_blk.closest('.column-padding').length){
				insert = elm_col;
			}else{
				insert = row_col;
			}

			if($position){
				$('<span>'+$hook_name+'</span>').insertAfter(insert);          
			}else{
				$('<span>'+$hook_name+'</span>').insertBefore(insert); 
			}
		}
		else{
			$obj.find('.'+$class_name).html('<span>'+$hook_name+'</span>'); 
		}
	}

	function open_template_preview_pp(){
		$('#thwec_tbuilder_editor_preview').dialog('open');
	}

	/*--------------------------------------------------
	 *------- Functions of Add/Edit Template Page ------
	 *--------------------------------------------------*/
    function edit_template_change_listner(elm){
    	var form = $('#thwec_edit_template_form');
    	var template_to_edit = thwec_base.get_property_field_value(form, 'select', 'edit_template');
        // var template_to_edit = $('select[name="i_edit-template"] option:selected').val();
        // var template_to_edit_text = $('select[name="i_edit-template"] option:selected').text();
        if(template_to_edit==''){
          	alert('Select a template to edit');
          	event.preventDefault();
        } else {
          	// $('input[name="template_to_edit"]').val(template_to_edit_text);
          	$('input[name="template_to_edit"]').val(template_to_edit);
        }
    }

    function delete_template_button(){
    	$('#delete_template').click(function(event) {
    		var form = $('#thwec_edit_template_form');
			var value = thwec_base.get_property_field_value(form, 'select', 'edit_template');
    		if(value == ''){
    			alert('Select template to delete');
    			event.preventDefault();
    		}else{
    			var delete_option = confirm('Delete the selected template ?');
    			if(!delete_option){
    				event.preventDefault();
    			}
    		}
    	});
    }

    return {
        save_template : save_template,
        show_template_preview : show_template_preview,
        // click_tab_functions : click_tab_functions,
        create_new_template : create_new_template,
        edit_template_change_listner : edit_template_change_listner
    };
}(window.jQuery, window, document));  

function thwecNewTemplate(elm){
	thwec_settings.create_new_template(elm);
}

function thwecSaveTemplate(elm){
    thwec_settings.save_template(elm);    
}

function thwecPreviewTemplate(elm){
    thwec_settings.show_template_preview(elm);    
}
function editTemplateChangeListner(elm){
	thwec_settings.edit_template_change_listner(elm);
}

// function thwecClickTab(elm){
// 	thwec_settings.click_tab_functions(elm);
// }
