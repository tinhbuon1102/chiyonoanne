<?php

$user_lastname = isset($_POST['user_lastname']) ? $_POST['user_lastname'] : '';
$user_firstname = isset($_POST['user_firstname']) ? $_POST['user_firstname'] : '';
$billing_last_name_kana = isset($_POST['billing_last_name_kana']) ? $_POST['billing_last_name_kana'] : '';
$billing_first_name_kana = isset($_POST['billing_first_name_kana']) ? $_POST['billing_first_name_kana'] : '';
$email = isset($_POST['email']) ? $_POST['email'] : '';
$phone = isset($_POST['phone']) ? $_POST['phone'] : '';
$is_register = isset($_POST['is_register']) ? $_POST['is_register'] : '';
$json_session = json_encode(array('user_lastname' => $user_lastname, 'user_firstname' => $user_firstname, 'billing_last_name_kana' => $billing_last_name_kana, 'billing_first_name_kana' => $billing_first_name_kana, 'email' => $email, 'phone' => $phone, 'is_register' => $is_register));
$_SESSION['your_info'] = $json_session;
echo $_SESSION['your_info'];
exit();
