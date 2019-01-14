<?php

function get_app_list_ajax_callback() {
    $data = getDataForDataTable();
    echo $data;
    exit();
}

add_action('wp_ajax_get_app_list_ajax', 'get_app_list_ajax_callback');

/**
 * Get data for datatable
 */
function getDataForDataTable() {
    $result = array();
    $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    $length = isset($_REQUEST['length']) ? $_REQUEST['length'] : 10;
    $result['draw'] = isset($_REQUEST['draw']) ? $_REQUEST['draw'] : 1;
    $keyword = $_REQUEST['search']['value'];
    $keyword = trim($keyword);
    $user_id = isset($_REQUEST['user_id']) ? $_REQUEST['user_id'] : 0;
    $result['recordsTotal'] = getTotal($keyword, $user_id);
    $result['recordsFiltered'] = $result['recordsTotal'];
    $list = getDataPaging($keyword, $start, $length, $user_id);

    if (@count($list) > 0) {
        foreach ($list as $value) {
            $arr_title = explode("(", $value->post_title);
            $obj[0] = str_replace("@", "-", $arr_title[0]);
            $first_name = get_post_meta($value->ID, '_appointment_guest_name', true);
            if ($first_name) {
                $obj[1] = get_post_meta($value->ID, '_appointment_guest_surname', true) . ' ' . $first_name . ' (User: Guest)';
                $first_kananame = get_post_meta($value->ID, 'billing_guest_first_name_kana', true);
                $last_kananame = get_post_meta($value->ID, 'billing_guest_last_name_kana', true);
                if ($last_kananame || $first_kananame) {
                    $obj[1] .= '<br/>' . $last_kananame . ' ' . $first_kananame;
                }
                $guest_email = get_post_meta($value->ID, '_appointment_guest_email', true);
                $obj[2] = '<a href="mailto:' . $guest_email . '">' . $guest_email . '</a>';
                $obj[3] = get_post_meta($value->ID, 'billing_guest_phone', true);
            } else {
                $user_id = $value->post_author;
                $user_info = get_userdata($user_id);
                $url = admin_url("admin.php?page=woocommerce-customers-manager&customer=" . $user_id . "&action=customer_details");
                $obj[1] = '<a href="' . $url . '">' . get_user_meta($user_id, 'last_name', true) . ' ' . get_user_meta($user_id, 'first_name', true)
                        . '</a>';
                if ($user_info->billing_last_name_kana || $user_info->billing_first_name_kana) {
                    $obj[1] .= '<br/>' . $user_info->billing_last_name_kana . ' ' . $user_info->billing_first_name_kana;
                }
                $obj[2] = '<a href="mailto:' . $user_info->user_email . '">' . $user_info->user_email . '</a>';
                $obj[3] = $user_info->billing_phone;
            }
            $obj[4] = get_post_meta($value->ID, '_cf_meta_value', true);
            $data[] = $obj;
        }
        $result['data'] = $data;
    } else {
        $result['data'] = array();
    }
    return json_encode($result);
}

function getTotal($keyword, $user_id) {
    global $wpdb;
    $query = "SELECT COUNT(DISTINCT p.ID) AS total FROM " . $wpdb->prefix . "posts AS p JOIN " . $wpdb->prefix . "postmeta AS pm ON p.ID=pm.post_id LEFT JOIN " . $wpdb->prefix . "users AS u ON p.post_author=u.ID LEFT JOIN " . $wpdb->prefix . "usermeta AS um ON p.post_author=um.user_id WHERE p.post_type='booked_appointments' AND p.post_status='publish'";

    if ($keyword != '') {
        $arr_keywork = explode(" ", $keyword);
        if (@count($arr_keywork) > 1) {
            $query .= " AND (p.post_title LIKE '%{$keyword}%' OR u.user_email LIKE '%{$keyword}%' OR (pm.meta_value LIKE '%{$keyword}%' AND pm.meta_key='_appointment_guest_email' )";
            $str_where = '';
            foreach ($arr_keywork as $value_k) {
                $str_where .= "OR (um.meta_value LIKE '%{$value_k}%' AND (um.meta_key='last_name' OR um.meta_key='first_name' OR um.meta_key='_appointment_guest_name' OR um.meta_key='_appointment_guest_surname' OR um.meta_key='billing_phone')) OR (pm.meta_value LIKE '%{$value_k}%' AND (pm.meta_key='_appointment_guest_name' OR pm.meta_key='_appointment_guest_surname'  OR pm.meta_key='billing_guest_phone'))";
            }
            $query .= $str_where . ')';
        } else {
            $query .= " AND (p.post_title LIKE '%{$keyword}%' OR u.user_email LIKE '%{$keyword}%' OR (pm.meta_value LIKE '%{$keyword}%' AND pm.meta_key='_appointment_guest_email' ) OR (um.meta_value LIKE '%{$keyword}%' AND (um.meta_key='last_name' OR um.meta_key='first_name' OR um.meta_key='_appointment_guest_name' OR um.meta_key='_appointment_guest_surname' OR um.meta_key='billing_phone')) OR (pm.meta_value LIKE '%{$keyword}%' AND (pm.meta_key='_appointment_guest_name' OR pm.meta_key='_appointment_guest_surname' OR pm.meta_key='billing_guest_phone')))";
        }
    }

    if ($user_id > 0) {
        $query .= " AND p.post_author='{$user_id}'";
    }

    $result = $wpdb->get_row($query);
    if (@count($result) > 0) {
        if (isset($result->total) && $result->total > 0) {
            return $result->total;
        } else {
            return 0;
        }
    } else {
        return 0;
    }
}

function getDataPaging($keyword, $start, $length, $user_id) {
    global $wpdb;
    $query = "SELECT DISTINCT p.ID,p.post_title,p.post_author FROM " . $wpdb->prefix . "posts AS p JOIN " . $wpdb->prefix . "postmeta AS pm ON p.ID=pm.post_id LEFT JOIN " . $wpdb->prefix . "users AS u ON p.post_author=u.ID LEFT JOIN " . $wpdb->prefix . "usermeta AS um ON p.post_author=um.user_id WHERE p.post_type='booked_appointments' AND p.post_status='publish'";
    if ($keyword != '') {
        $arr_keywork = explode(" ", $keyword);
        if (@count($arr_keywork) > 1) {
            $query .= " AND (p.post_title LIKE '%{$keyword}%' OR u.user_email LIKE '%{$keyword}%' OR (pm.meta_value LIKE '%{$keyword}%' AND pm.meta_key='_appointment_guest_email' )";
            $str_where = '';
            foreach ($arr_keywork as $value_k) {
                $str_where .= "OR (um.meta_value LIKE '%{$value_k}%' AND (um.meta_key='last_name' OR um.meta_key='first_name' OR um.meta_key='_appointment_guest_name' OR um.meta_key='_appointment_guest_surname' OR um.meta_key='billing_phone')) OR (pm.meta_value LIKE '%{$value_k}%' AND (pm.meta_key='_appointment_guest_name' OR pm.meta_key='_appointment_guest_surname'  OR pm.meta_key='billing_guest_phone'))";
            }
            $query .= $str_where . ')';
        } else {
            $query .= " AND (p.post_title LIKE '%{$keyword}%' OR u.user_email LIKE '%{$keyword}%' OR (pm.meta_value LIKE '%{$keyword}%' AND pm.meta_key='_appointment_guest_email' ) OR (um.meta_value LIKE '%{$keyword}%' AND (um.meta_key='last_name' OR um.meta_key='first_name' OR um.meta_key='_appointment_guest_name' OR um.meta_key='_appointment_guest_surname' OR um.meta_key='billing_phone')) OR (pm.meta_value LIKE '%{$keyword}%' AND (pm.meta_key='_appointment_guest_name' OR pm.meta_key='_appointment_guest_surname' OR pm.meta_key='billing_guest_phone')))";
        }
    }
    if ($user_id > 0) {
        $query .= " AND p.post_author='{$user_id}'";
    }
    $query .= "ORDER BY p.ID DESC LIMIT  $start , $length";
    $rows = $wpdb->get_results($query);

    return $rows;
}

add_action('wp_ajax_get_user_list_ajax', 'get_user_list_ajax_callback');

function get_user_list_ajax_callback() {
    $keyWork = $_REQUEST['keyWord'];
    global $wpdb;
    $query = "SELECT DISTINCT u.ID,u.user_email FROM " . $wpdb->prefix . "users AS u LEFT JOIN " . $wpdb->prefix . "usermeta AS um ON u.ID=um.user_id WHERE 1 ";
    if ($keyWork != '') {
        $arr_keywork = explode(" ", $keyWork);
        if (@count($arr_keywork) > 1) {
            $query .= " AND (u.user_email LIKE '%{$keyWork}%' ";
            $str_where = '';
            foreach ($arr_keywork as $value_k) {
                $str_where .= "OR (um.meta_value LIKE '%{$value_k}%' AND (um.meta_key='last_name' OR um.meta_key='first_name' OR um.meta_key='billing_last_name_kana' OR um.meta_key='billing_first_name_kana'))";
            }
            $query .= $str_where . ')';
        } else {
            $query .= " AND (u.user_email LIKE '%{$keyWork}%' OR (um.meta_value LIKE '%{$keyWork}%' AND (um.meta_key='last_name' OR um.meta_key='first_name' OR um.meta_key='billing_last_name_kana' OR um.meta_key='billing_first_name_kana')))";
        }
    }
    $rows = $wpdb->get_results($query);
    $ex = array();
    foreach ($rows as $value) {
        $content = get_user_meta($value->ID, 'last_name', true) . ' ' . get_user_meta($value->ID, 'first_name', true) . ' (' . $value->user_email . ')';
        $ex[] = array('content' => $content, 'id' => $value->ID);
    }
    $data = array(
        'items' => $ex
    );
    echo json_encode($data);
    exit();
}
