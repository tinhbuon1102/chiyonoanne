<?php

$booked_mailer_actions = apply_filters('booked_mailer_actions', array(
    'booked_confirmation_email',
    'booked_admin_confirmation_email',
    'booked_reminder_email',
    'booked_admin_reminder_email',
    'booked_cancellation_email',
    'booked_admin_cancellation_email',
    'booked_approved_email',
    'booked_registration_email'
        ));

foreach ($booked_mailer_actions as $action):
    add_action($action, 'booked_mailer', 10, 5);
if(in_array($action, array('booked_admin_confirmation_email','booked_admin_reminder_email','booked_admin_cancellation_email'))){
    add_action($action, 'booked_mailer_to_another_admin', 10, 5);
}
endforeach;

function booked_mailer_tokens() {
    return apply_filters('booked_mailer_tokens', array(
        'name' => esc_html__("Display the full name of the customer.", "booked"),
        'kananame' => esc_html__("Display the full kananame name of the customer.", "booked"),
        'phone' => esc_html__("Display the phone of the customer.", "booked"),
        'email' => esc_html__("Display the customer's email address.", "booked"),
        'title' => esc_html__("Display the title of the appointment's time slot.", "booked"),
        'calendar' => esc_html__("Display the appointment's calendar name (if applicable).", "booked"),
        'date' => esc_html__("Display the appointment date.", "booked"),
        'time' => esc_html__("Display the appointment time.", "booked"),
        'customfields' => esc_html__("Display the appointment's custom field data.", "booked"),
        'id' => esc_html__("Display the appointment's unique identification number.", "booked"),
        'cancellable_datetime' => esc_html__("Display cancellable date time.", "booked"),
        'portfolio_image' => esc_html__("Display portfolio image.", "booked"),
        'cancel_text' => esc_html__("Display cancel text.", "booked"),
    ));
}

function booked_user_tokens() {
    return apply_filters('booked_user_tokens', array(
        'name' => esc_html__("Display the customer's name.", "booked"),
        'kananame' => esc_html__("Display the full kananame name of the customer.", "booked"),
        'phone' => esc_html__("Display the phone of the customer.", "booked"),
        'username' => esc_html__("Display the customer's username.", "booked"),
        'password' => esc_html__("Display the customer's password.", "booked"),
        'email' => esc_html__("Display the customer's email address.", "booked")
    ));
}

function booked_token_replacement($content, $replacements, $type = 'appointment') {

    if ($type == 'appointment'):
        $booked_tokens = booked_mailer_tokens();
    elseif ($type == 'user'):
        $booked_tokens = booked_user_tokens();
    else:
        return $content;
    endif;

    $needles = array();
    $rep_with = array();

    foreach ($booked_tokens as $token => $desc):
        if (isset($replacements[$token])):
            $needles[] = '%' . $token . '%';
            $rep_with[] = $replacements[$token];
        endif;
    endforeach;

    $content = htmlentities(str_replace($needles, $rep_with, $content), ENT_QUOTES | ENT_IGNORE, "UTF-8");
    $content = html_entity_decode($content, ENT_QUOTES | ENT_IGNORE, "UTF-8");

    return $content;
}

function booked_get_appointment_tokens($appt_id) {

    // Name & Email
    // $customer_name
    // $email
    if ($first_name = get_post_meta($appt_id, '_appointment_guest_name', true)):
        $last_name = get_post_meta($appt_id, '_appointment_guest_surname', true);
        $customer_name = ( $last_name ? $last_name . ' ' . $first_name : $first_name );
        $customer_email = get_post_meta($appt_id, '_appointment_guest_email', true);
        $kanalastname = get_post_meta($appt_id, 'billing_guest_last_name_kana', true);
        $kanafirstname = get_post_meta($appt_id, 'billing_guest_first_name_kana', true);
        $kananame = ( $kanalastname ? $kanalastname . ' ' . $kanafirstname : $kanafirstname );
        $phone = get_post_meta($appt_id, 'billing_guest_phone', true);
    else:
        $_appt = get_post($appt_id);
        $appt_author = $_appt->post_author;
        $_user = get_userdata($appt_author);
        $customer_name = booked_get_name($appt_author);
        $customer_email = $_user->user_email;
        $kanalastname = get_user_meta($appt_author, 'billing_last_name_kana', true);
        $kanafirstname = get_user_meta($appt_author, 'billing_first_name_kana', true);
        $kananame = ( $kanalastname ? $kanalastname . ' ' . $kanafirstname : $kanafirstname );
        $phone = get_user_meta($appt_author, 'billing_phone', true);
    endif;
    //portfolio_image
    $portfolio_image = get_post_meta($appt_id, 'p_image', true);
    if (isset($portfolio_image) && $portfolio_image != '') {
        $portfolio_image = '<img style="max-width: 200px" src="' . $portfolio_image . '"/>';
    } else {
        $portfolio_image = '';
    }
    //cancel text
    $myaccount_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
    $shop_phone = str_replace("-", "", get_option('woocommerce_store_phone'));
    $contactpage_url = get_site_url() . '/contact';
    $cancelText = sprintf(__('To cancel this appointment, please go to <a href="%s">My Page</a>, call to <a href="tel:%s">%s</a> or <a href="%s">contact</a> us.', 'booked'), $myaccount_url, $shop_phone, $shop_phone, $contactpage_url);
    // Calendar Name
    // $calendar_name
    $calendars = get_the_terms($appt_id, 'booked_custom_calendars');
    if (!empty($calendars)):
        foreach ($calendars as $calendar):
            $calendar_id = $calendar->term_id;
            $calendar_term = get_term_by('id', $calendar_id, 'booked_custom_calendars');
            $calendar_name = $calendar_term->name;
            break;
        endforeach;
    else:
        $calendar_name = '';
    endif;

    // Date
    // $date_text
    $date_format = get_option('date_format');
    $timestamp = get_post_meta($appt_id, '_appointment_timestamp', true);
    $date_text = date_i18n($date_format, $timestamp);

    // Time
    // $time_text
    $timeslot = get_post_meta($appt_id, '_appointment_timeslot', true);
    $timeslots = explode('-', $timeslot);
    $time_format = get_option('time_format');
    $hide_end_times = get_option('booked_hide_end_times', false);
    $timestamp_start = strtotime(date_i18n('Y-m-d', $timestamp) . ' ' . $timeslots[0]);
    $timestamp_end = strtotime(date_i18n('Y-m-d', $timestamp) . ' ' . $timeslots[1]);
    if ($timeslots[0] == '0000' && $timeslots[1] == '2400'):
        $time_text = esc_html__('All day', 'booked');
    else :
        $time_text = date_i18n($time_format, $timestamp_start) . (!$hide_end_times ? '&ndash;' . date_i18n($time_format, $timestamp_end) : '' );
    endif;

    $time_text = apply_filters('booked_emailed_timeslot_text', $time_text, $timestamp_start, $timeslot, $calendar_id);

    // Custom Fields
    // $custom_fields
    $custom_fields = get_post_meta($appt_id, '_cf_meta_value', true);

    // Title
    // $title
    $title = get_post_meta($appt_id, '_appointment_title', true);
    $cancel_buffer = get_option('booked_cancellation_buffer', 0);
    $datetime_booked = date('Y-m-d', $timestamp) . ' ' . $time_text;
    if (ICL_LANGUAGE_CODE == 'ja' || ICL_LANGUAGE_CODE == 'site-default') {
        $cancellable_datetime = date_i18n("Y年n月j日 l G:i", strtotime('-' . $cancel_buffer . ' hours', strtotime($datetime_booked)));
    } else {
        $cancellable_datetime = date_i18n("D, F S, Y H:i A", strtotime('-' . $cancel_buffer . ' hours', strtotime($datetime_booked)));
    }
    return apply_filters('booked_appointment_tokens', array(
        'name' => $customer_name,
        'kananame' => $kananame,
        'phone' => $phone,
        'date' => $date_text,
        'time' => $time_text,
        'customfields' => $custom_fields,
        'calendar' => $calendar_name,
        'email' => $customer_email,
        'title' => $title,
        'id' => $appt_id,
        'cancellable_datetime' => $cancellable_datetime,
        'portfolio_image' => $portfolio_image,
        'cancel_text' => $cancelText
    ));
}

function booked_mailer($to = false, $subject, $message, $from_email = false, $from_name = false) {

    if (!$to)
        return false;

    add_filter('wp_mail_content_type', 'booked_set_html_content_type');

    $booked_email_logo = get_option('booked_email_logo');
    if ($booked_email_logo):
        $logo = apply_filters('booked_email_logo_html', '<img src="' . $booked_email_logo . '" style="max-width:100%; height:auto; display:block; margin:10px 0 20px;">');
    else :
        $logo = apply_filters('booked_email_logo_html', '');
    endif;

    $link_color = get_option('booked_button_color', '#56C477');
    $force_sender = get_option('booked_email_force_sender', false);
    $disable_booked_mailer = get_option('booked_emailer_disabled', false);

    if ($disable_booked_mailer):
        $from_email = false;
        $from_name = false;
    elseif ($force_sender):
        $admin_email = get_option('admin_email');
        $from_email = get_option('booked_email_force_sender_from', $admin_email);
        $from_name = false;
    endif;

    if (file_exists(get_stylesheet_directory() . '/booked/email-template.html')):
        $template = file_get_contents(get_stylesheet_directory() . '/booked/email-template.html', true);
    elseif (file_exists(get_template_directory() . '/booked/email-template.html')):
        $template = file_get_contents(get_template_directory() . '/booked/email-template.html', true);
    else:
        $template = file_get_contents(untrailingslashit(BOOKED_PLUGIN_DIR) . '/includes/email-templates/default.html', true);
    endif;

    $filter = array('%content%', '%logo%', '%link_color%');
    $replace = array(wpautop($message), $logo, $link_color);
    if ($from_email):
        $headers[] = 'From: ' . ( $from_name ? $from_name . ' <' . $from_email . '>' : $from_email );
    endif;
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $message = str_replace($filter, $replace, $template);

    wp_mail($to, $subject, $message, $headers);

    remove_filter('wp_mail_content_type', 'booked_set_html_content_type');
}


function booked_mailer_to_another_admin($to = false, $subject, $message, $from_email = false, $from_name = false) {

    $current_booked_another_email_get_notification_value = get_option('booked_another_email_get_notification', '');
    $to=$current_booked_another_email_get_notification_value;
    if (!$to)
        return false;
    if(empty($to)){
        return false;
    }
    add_filter('wp_mail_content_type', 'booked_set_html_content_type');

    $booked_email_logo = get_option('booked_email_logo');
    if ($booked_email_logo):
        $logo = apply_filters('booked_email_logo_html', '<img src="' . $booked_email_logo . '" style="max-width:100%; height:auto; display:block; margin:10px 0 20px;">');
    else :
        $logo = apply_filters('booked_email_logo_html', '');
    endif;

    $link_color = get_option('booked_button_color', '#56C477');
    $force_sender = get_option('booked_email_force_sender', false);
    $disable_booked_mailer = get_option('booked_emailer_disabled', false);

    if ($disable_booked_mailer):
        $from_email = false;
        $from_name = false;
    elseif ($force_sender):
        $admin_email = get_option('admin_email');
        $from_email = get_option('booked_email_force_sender_from', $admin_email);
        $from_name = false;
    endif;

    if (file_exists(get_stylesheet_directory() . '/booked/email-template.html')):
        $template = file_get_contents(get_stylesheet_directory() . '/booked/email-template.html', true);
    elseif (file_exists(get_template_directory() . '/booked/email-template.html')):
        $template = file_get_contents(get_template_directory() . '/booked/email-template.html', true);
    else:
        $template = file_get_contents(untrailingslashit(BOOKED_PLUGIN_DIR) . '/includes/email-templates/default.html', true);
    endif;

    $filter = array('%content%', '%logo%', '%link_color%');
    $replace = array(wpautop($message), $logo, $link_color);
    if ($from_email):
        $headers[] = 'From: ' . ( $from_name ? $from_name . ' <' . $from_email . '>' : $from_email );
    endif;
    $headers[] = 'Content-Type: text/html; charset=UTF-8';
    $message = str_replace($filter, $replace, $template);

    wp_mail($to, $subject, $message, $headers);

    remove_filter('wp_mail_content_type', 'booked_set_html_content_type');
}

function booked_set_html_content_type() {
    return 'text/html';
}
