<div id="reservationFormConfirm" class="form_entry">
    <div class="confirm-box">
        <div class="row flex-justify-center pad_row">
            <fieldset class="confirm_info col-md-4 col-xs-12">
                <h3 class="appointment--confirm__form__title heading heading--small"><!--ご予約情報--><?php _e('Appointment Info', 'zoa'); ?></h3>
                <div class="form-row"><div class="field-wrapper">
                        <label class="form-row__label light-copy"><!--日付--><?php _e('Date', 'zoa'); ?></label>
                        <div class="text_output">
                            <span class="confirm-text-value ymd">{year}年{month}月{day}日</span>
                        </div>
                    </div></div><!--/.form-row-->
                <div class="form-row"><div class="field-wrapper">
                        <label class="form-row__label light-copy"><!--時間--><?php _e('Time', 'zoa'); ?></label>
                        <div class="text_output">
                            <span class="confirm-text-value ch-time">{time}</span>
                        </div>
                    </div></div><!--/.form-row-->
                <div class="form-row"><div class="field-wrapper">
                        <label class="form-row__label light-copy">Service</label>
                        <div class="text_output">
                            <span class="confirm-text-value ch-service">{service name}</span>
                        </div>
                    </div></div><!--/.form-row-->
            </fieldset>

            <fieldset class="confirm_info col-md-4 col-xs-12">
                <h3 class="appointment--confirm__form__title heading heading--small"><!--お客様情報--><?php _e('Customer Info', 'zoa'); ?></h3>
                <div class="form-row">
                    <div class="field-wrapper">
                        <label class="form-row__label light-copy"><?php _e('Name', 'zoa'); ?></label>
                        <div class="text_output">
                            <span class="confirm-text-value ch-name-info"></span>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field-wrapper">
                        <label class="form-row__label light-copy"><?php _e('Name Kana', 'zoa'); ?></label>
                        <div class="text_output">
                            <span class="confirm-text-value ch-kananame-info"></span>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field-wrapper">
                        <label class="form-row__label light-copy"><?php _e('Email', 'zoa'); ?></label>
                        <div class="text_output">
                            <span class="confirm-text-value ch-email-info"></span>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field-wrapper">
                        <label class="form-row__label light-copy"><?php _e('Phone', 'zoa'); ?></label>
                        <div class="text_output">
                            <span class="confirm-text-value ch-phone-info"></span>
                        </div>
                    </div>
                </div>
            </fieldset>
            <div class="confirm_info col-md-4 col-xs-12">
                <fieldset>
                    <h3 class="appointment--confirm__form__title heading heading--small"><?php _e('Additional Question', 'zoa'); ?></h3>
                    <!--start loop of custom fields-->
                    <div class="form-row">
                        <div class="field-wrapper ch-aq">
                            <label class="form-row__label light-copy">{label}</label>
                            <div class="text_output">
                                <span class="confirm-text-value">{value}</span>
                            </div>
                        </div>
                    </div>
                    <!--end loop of custom fields-->
                </fieldset>
            </div>
        </div>
        <div cancel-datetime='' class="cancel_term_text ch-term-text"></div>
    </div>
</div><!--/reservationFormConfirm-->