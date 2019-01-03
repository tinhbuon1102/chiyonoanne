<?php
global $current_user;
get_currentuserinfo();
?>
<div id="reservationFormCustomer" class="form_entry">
    <div class="confirm-box ch-step2">
        <div class="row flex-justify-center pad_row">
            <fieldset class="confirm_info col-md-12 col-xs-12">
                <div class="form-row">
                    <div class="field-wrapper">
                        <div class="flex-row">
                            <div class="col-md-6 col-xs-12">
                                <label class="form-row__label light-copy"><?php _e('Last Name', 'zoa'); ?><i class="required-asterisk booked-icon booked-icon-required"></i></label>
                                <input type="text" required="required" id="user_lastname" name="user_lastname" value="<?php echo $current_user->user_lastname; ?>"/>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <label class="form-row__label light-copy"><?php _e('First Name', 'zoa'); ?><i class="required-asterisk booked-icon booked-icon-required"></i></label>
                                <input type="text" required="required" id="user_firstname" name="user_firstname" value="<?php echo $current_user->user_firstname; ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field-wrapper">
                        <div class="flex-row">
                            <div class="col-md-6 col-xs-12">
                                <label class="form-row__label light-copy"><?php _e('Last Name Kana', 'zoa'); ?></label>
                                <input type="text" id="billing_last_name_kana" name="billing_last_name_kana" value="<?php echo $current_user->billing_last_name_kana; ?>"/>
                            </div>
                            <div class="col-md-6 col-xs-12">
                                <label class="form-row__label light-copy"><?php _e('First Name Kana', 'zoa'); ?></label>
                                <input type="text" id="billing_first_name_kana" name="billing_first_name_kana" value="<?php echo $current_user->billing_first_name_kana; ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field-wrapper">
                        <div class="flex-row">
                            <div class="col-md-12 col-xs-12">
                                <label class="form-row__label light-copy"><?php _e('Email', 'zoa'); ?><i class="required-asterisk booked-icon booked-icon-required"></i></label>
                                <input type="email" required="required" id="email" name="email" value="<?php echo $current_user->user_email; ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field-wrapper">
                        <div class="flex-row">
                            <div class="col-md-12 col-xs-12">

                                <label class="form-row__label light-copy"><?php _e('Phone', 'zoa'); ?></label>
                                <input type="text" id="phone" name="phone" value="<?php echo $current_user->billing_phone; ?>"/>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                if (!is_user_logged_in()) {
                    ?>
                    <div class="form-row">
                        <div class="field-wrapper">
                            <label class="form-row__label light-copy"><input type="checkbox" id="is_register" name="is_register" id="is_register" value="1"><?php _e('Do you want to register as client?', 'zoa'); ?></label>
                        </div>
                    </div>
                <?php } ?>
            </fieldset>
        </div>
        <div class="status msg2">&nbsp;</div>
    </div>
    <?php echo do_shortcode('[booked-login]'); ?>
</div>