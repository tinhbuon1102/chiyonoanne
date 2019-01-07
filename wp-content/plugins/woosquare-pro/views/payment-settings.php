<div class="welcome-panel" >

<form action="<?php echo get_admin_url( ); ?>admin-post.php" method="post" >   
   <h1 class="screen-reader-text">Checkout</h1>
	<input type="hidden" name="action" value="add_foobar">
   <br class="clear">
   <h2>Square</h2>
   <p>Square works by adding payments fields in an iframe and then sending the details to Square for verification and processing.</p>
   <table class="form-table">
      <tbody>
	  
	  
	  <?php 
	  
		if(!empty($square_payment_settin)){
			$unserialize_array = $square_payment_settin;
		} else {
			$unserialize_array = array(
				'enabled' => 'no',
				'title' => 'Credit card (Square)',
				'description' => 'Pay with your credit card via Square.',
				'capture' =>  'no' ,
				'create_customer' => 'no',								'Send_customer_info' => 'no',
				'logging' => 'no'
		);
		
		}
		
	  ?>
         <tr valign="top">
            <th scope="row" class="titledesc">
               <label for="woocommerce_square_enabled">Enable/Disable</label>
            </th>
            <td class="forminp">
               <fieldset>
                  <legend class="screen-reader-text"><span>Enable/Disable</span></legend>
                  <label for="woocommerce_square_enabled">
                  <input type="checkbox" name="woocommerce_square_enabled" id="woocommerce_square_enabled"  value="1" <?php checked( 'yes' == $unserialize_array['enabled'] ); ?> />Enable Square</label><br>
               
			   </fieldset>
            </td>
         </tr>
         <tr valign="top">
            <th scope="row" class="titledesc">
               <label for="woocommerce_square_title">Title</label>
            </th>
            <td class="forminp">
               <fieldset>
                  <legend class="screen-reader-text"><span>Title</span></legend>
                  <input class="input-text regular-input " type="text" name="woocommerce_square_title" id="woocommerce_square_title" style="" value="<?php echo $unserialize_array['title'] ?>" placeholder="">
                  <p class="description">This controls the title which the user sees during checkout.</p>
               </fieldset>
            </td>
         </tr>
         <tr valign="top">
            <th scope="row" class="titledesc">
               <label for="woocommerce_square_description">Description</label>
            </th>
            <td class="forminp">
               <fieldset>
                  <legend class="screen-reader-text"><span>Description</span></legend>
                  <textarea rows="5" cols="180" class="input-text wide-input " type="textarea" name="woocommerce_square_description" id="woocommerce_square_description" style="" placeholder=""><?php echo $unserialize_array['description'] ?></textarea>
                  <p class="description">This controls the description which the user sees during checkout.</p>
               </fieldset>
            </td>
         </tr>
         <tr valign="top">
            <th scope="row" class="titledesc">
               <label for="woocommerce_square_capture">Delay Capture</label>
            </th>
            <td class="forminp">
               <fieldset>
                  <legend class="screen-reader-text"><span>Delay Capture</span></legend>
                  <label for="woocommerce_square_capture">
				  <input type="checkbox" name="woocommerce_square_capture" id="woocommerce_square_capture"  value="1" <?php checked( 'yes' == $unserialize_array['capture'] ); ?> />Enable Delay Capture</label><br>
                  <p class="description">When enabled, the request will only perform an Auth on the provided card. You can then later perform either a Capture or Void.</p>
               </fieldset>
            </td>
         </tr>
         <tr valign="top">
            <th scope="row" class="titledesc">
               <label for="woocommerce_square_create_customer">Create Customer</label>
            </th>
            <td class="forminp">
               <fieldset>
                  <legend class="screen-reader-text"><span>Create Customer</span></legend>
                  <label for="woocommerce_square_create_customer">
                  <input type="checkbox" name="woocommerce_square_create_customer" id="woocommerce_square_create_customer"  value="1" <?php checked( 'yes' == $unserialize_array['create_customer'] ); ?> />Enable Create Customer</label><br>
                  <p class="description">When enabled, processing a payment will create a customer profile on Square.</p>
               </fieldset>
            </td>
         </tr>
         <tr valign="top">
            <th scope="row" class="titledesc">
               <label for="woocommerce_square_logging">Logging</label>
            </th>
            <td class="forminp">
               <fieldset>
                  <legend class="screen-reader-text"><span>Logging</span></legend>
                  <label for="woocommerce_square_logging">
                  <input type="checkbox" name="woocommerce_square_logging" id="woocommerce_square_logging"  value="1" <?php checked( 'yes' == $unserialize_array['logging'] ); ?> />Log debug messages</label><br>
                  <p class="description">Save debug messages to the WooCommerce System Status log.</p>
               </fieldset>
            </td>
         </tr>		 <tr valign="top">			<?php if(!function_exists('woosquare_trans_note_sync_add_on')){ ?>				<th scope="row" class="titledesc">				   <label for="Send_customer_info">Send Customer Info</label>				</th>				<td class="forminp">				   <fieldset>					  <legend class="screen-reader-text"><span>Send Customer Info</span></legend>					  <label for="Send_customer_info">					  <input type="checkbox" name="Send_customer_info" id="Send_customer_info"  value="1" <?php checked( 'yes' == @$unserialize_array['Send_customer_info'] ); ?> />Send first name last name</label><br>					  <p class="description">Send first name last name with order to square.</p>				   </fieldset>				</td>			<?php } else {				echo woosquare_trans_note_sync_add_on();			} ?>         </tr>      </tbody>   </table>
   <p class="submit">
      <input name="save" class="button-primary woocommerce-save-button" type="submit" value="Save changes">
      <input type="hidden" id="_wpnonce" name="_wpnonce" value="6952bcc533"><input type="hidden" name="_wp_http_referer" value="/wordpresswosquaresameh/wp-admin/admin.php?page=wc-settings&amp;tab=checkout&amp;section=square">		
   </p>
</form>
</div>