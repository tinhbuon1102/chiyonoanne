<?php

$user_lastname = isset($_POST['user_lastname']) ? $_POST['user_lastname'] : '';
$user_firstname = isset($_POST['user_firstname']) ? $_POST['user_firstname'] : '';
$billing_last_name_kana = isset($_POST['billing_last_name_kana']) ? $_POST['billing_last_name_kana'] : '';
$billing_first_name_kana = isset($_POST['billing_first_name_kana']) ? $_POST['billing_first_name_kana'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$password = isset($_POST['password']) ? $_POST['password'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$is_register = isset($_POST['is_register']) ? $_POST['is_register'] : '';
$cancel_buffer = get_option('booked_cancellation_buffer', 0);
$datetime_booked = isset($_POST['cancel_datetime']) ? $_POST['cancel_datetime'] : '';
$date = date_i18n("l, F j, Y H:i A", strtotime('-' . $cancel_buffer . ' hours', strtotime($datetime_booked)));
$date_cancel_string = "このご予約のキャンセルは " . $date . " までとなります。";
if (isset($_SESSION['p_image']) && $_SESSION['p_image'] != '') {
    $p_image = $_SESSION['p_image'];
    $title = __('Inspired Photo', 'zoa');
} else {
    $p_image = '';
    $title = '';
}
$json_session = json_encode(array('user_lastname' => $user_lastname, 'user_firstname' => $user_firstname, 'billing_last_name_kana' => $billing_last_name_kana, 'billing_first_name_kana' => $billing_first_name_kana, 'email' => $email, 'phone' => $phone, 'is_register' => $is_register, 'cancel_datetime' => $date_cancel_string, 'p_image' => $p_image, 'title_area_image' => $title, 'password' => $password));
$_SESSION['your_info'] = $json_session;
echo $_SESSION['your_info'];
exit();
