<span class="wpep-form-control-wrap">
    <fieldset class="wpep_container" id="wpep_container">  
        <legend><?php _e('Credit Card', 'wp-easy-pay'); ?></legend>
        <div class="messages"></div>
        <div class="wpep-field">
            <label for="wpep-card-number"><?php _e('Card Number', 'wp-easy-pay'); ?></label>
            <div id="wpep-card-number"></div>              
        </div>

        <div class="wpep-field date">
            <label for="wpep-expiration-date"><?php _e('Expiry (MM/YY)', 'wp-easy-pay'); ?></label>
            <div id="wpep-expiration-date"></div>                
        </div>

        <div class="wpep-field cvv">
            <label for="wpep-cvv"><?php _e('Card Code', 'wp-easy-pay'); ?></label>
            <div id="wpep-cvv"></div>                
        </div>

        <div class="wpep-field">
            <label for="wpep-postal-code"><?php _e('Card Postal Code', 'wp-easy-pay'); ?></label>
            <div id="wpep-postal-code"></div>                
        </div>            

        <div class="wpep-field amount" style="<?php if (!$user_set_amount): ?>display: none;<?php endif; ?>">
            <label for="wpep-postal-code"><?php _e('Amount', 'wp-easy-pay'); ?></label>
            <input type="number" <?php if (!$user_set_amount): ?>readonly=""<?php endif; ?> value="<?php echo $amount; ?>" class="wpep-amount"/>            
        </div>

        <div class="wpep-field">
            <button type="button" class="wpep-button-submit"><?php echo $button_text; ?></button>   
            <img style="display: none;" src="<?php echo WPEP_PLUGIN_URL; ?>assets/img/wpep-loader.gif" class="wpep-loader"/>
        </div> 
    </fieldset>
</span>