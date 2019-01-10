<?php


//For Appointments of Customer
function get_app_list_ajax_customer_callback() {
    $data = getDataAppForDataTable();
    echo $data;
    exit();
}

add_action('wp_ajax_get_app_list_ajax_customer', 'get_app_list_ajax_customer_callback');

/**
 * Get data for datatable
 */
function getDataAppForDataTable() {
    $result = array();
    $start = isset($_REQUEST['start']) ? $_REQUEST['start'] : 0;
    $length = isset($_REQUEST['length']) ? $_REQUEST['length'] : 10;
    $result['draw'] = isset($_REQUEST['draw']) ? $_REQUEST['draw'] : 1;
    $keyword = $_REQUEST['search']['value'];
    $keyword = trim($keyword);
    $user_id = isset($_REQUEST['customer']) ? $_REQUEST['customer'] : 0;
    $result['recordsTotal'] = getTotalForCustomer($user_id);
    $result['recordsFiltered'] = $result['recordsTotal'];
    $list = getDataPagingForCustome($start, $length, $user_id);

    if (@count($list) > 0) {
        foreach ($list as $value) {
            $arr_title = explode("(", $value->post_title);
            $obj[0] = str_replace("@", "-", $arr_title[0]);
            $obj[1] = get_post_meta($value->ID, '_cf_meta_value', true);
            $data[] = $obj;
        }
        $result['data'] = $data;
    } else {
        $result['data'] = array();
    }
    return json_encode($result);
}

function getTotalForCustomer($user_id) {
    global $wpdb;
    $query = "SELECT COUNT(DISTINCT p.ID) AS total FROM " . $wpdb->prefix . "posts AS p WHERE p.post_type='booked_appointments' AND p.post_status='publish'";
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

function getDataPagingForCustome($start, $length, $user_id) {
    global $wpdb;
    $query = "SELECT DISTINCT p.ID,p.post_title,p.post_author FROM " . $wpdb->prefix . "posts AS p WHERE p.post_type='booked_appointments' AND p.post_status='publish'";
    if ($user_id > 0) {
        $query .= " AND p.post_author='{$user_id}'";
    }
    $query .= "ORDER BY p.ID DESC LIMIT  $start , $length";
    $rows = $wpdb->get_results($query);

    return $rows;
}
//End