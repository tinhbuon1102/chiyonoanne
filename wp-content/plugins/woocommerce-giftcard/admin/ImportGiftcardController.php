<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 26/07/2018
 * Time: 08:21
 */

namespace admin;


class ImportGiftcardController
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', array($this, 'load_style_scripts'), 99);
    }

    public function load_style_scripts(){
        wp_register_script('import_gc_js', GIFTCARD_URL . '/assets/js/ImportGiftcard.js', array());

        wp_register_style('import_gc_style', GIFTCARD_URL . '/assets/css/import_giftcard.css');
    }

    public static function import_giftcard_view(){
        wp_enqueue_script('boostrap_js', '//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js');
        wp_enqueue_style('boostrap_css', '//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css');

        wp_enqueue_style('import_gc_style');
        wp_enqueue_script('import_gc_js');
        wp_enqueue_script('select2');

        wp_localize_script('import_gc_js', 'gc_import', array('ajax_url' => admin_url('admin-ajax.php'), 'gc_sercurity' => wp_create_nonce('import_file_gc')));

        ob_start();
        $template_path = GIFTCARD_PATH . 'admin/view/';
        $default_path  = GIFTCARD_PATH . 'admin/view/';
        wc_get_template( 'ImportGiftCard.php', array(), $template_path, $default_path );
        echo ob_get_clean();
    }

    public static function process_file(){
	    $data_response = array('status' => false, 'message' => '', 'col_title' => array(), 'data' => array());
	    if ( ! check_ajax_referer( 'import_file_gc', 'security' ) ) {
			$data_response['message'] = 'Permission deny';
            wp_die( json_encode($data_response) );

        } else {

            if (isset($_FILES['file_gc'])){
                if ($_FILES['file_gc']['error'] > 0){
                	$data_response['message'] = "file error";
                    wp_die(json_encode($data_response));
                } else {
                    // read file's ext
                    $ext = pathinfo($_FILES['file_gc']['name'], PATHINFO_EXTENSION);

                    if (in_array($ext, array('csv', 'xls', 'xlsx'))){


                    	// check file extension
                    	if ($ext == 'csv'){
                    		$file_data = self::read_file_csv($_FILES);
	                    }

	                    if ($file_data == false){
                    		// return error
	                    }

	                    // response data
	                    $data_response['status'] = true;
                    	$data_response['col_title'] = $file_data[0];
                    	unset($file_data[0]);
                    	$data_response['data'] = $file_data;

                        wp_die(json_encode($data_response));
                    } else {
                        // not support
	                    $data_response['message'] = 'file not support';
                        wp_die(json_encode($data_response));
                    }
                }
            } else {
            	$data_response['message'] = "file not found";
                wp_die(json_encode($data_response));
            }
        }
    }

    private static function read_file_csv(&$file = null){
    	if ($file == null){
    		return false;
	    }
		try {
			$file_content = @file_get_contents($file['file_gc']['tmp_name']);
			$lines = array();

			foreach (str_getcsv($file_content, "\n") as $line){
                $line = str_replace(array(",","|"), ";", $line);
                $lines[] = str_getcsv($line, ";");
            }
		} catch (\Exception $exception){
			error_log("Read file csv error: " + $exception->getMessage());
	    } finally {
			unlink($file['file_gc']["tmp_name"]);
		}

	    return $lines;
    }

    public static function process_before_save_data(){
        $data_response = array('status' => false, 'message' => 'Some thing error');
        if ( ! check_ajax_referer( 'import_file_gc', 'security' ) ) {
            $data_response['message'] = 'Permission deny';
            wp_die( json_encode($data_response) );

        } else {
            // process data

            $gc_datas = $_POST['gc_data'];
            $field_mapping = $_POST['field_mapping'];

            $product_config['product_id'] = $_POST['product_conf']['product_id'];

            $data = array(
                'product_id' => $product_config['product_id'],
            );
            $error = 0;
            $success = 0;
            $list_price = [];

            foreach ($gc_datas as $index => $gc_data){
                $data['code'] = $gc_data[$field_mapping['code']];
                // check code
                $check = 0;
                $check = ImportGiftcardModel::check_duplicate_code($data['code']);
                if ($check > 0){
                    $result[$index] = array('status' => false, 'message' => 'Code duplicate', 'data' => $gc_data);
                    $error++;
                    continue;
                }
                $balance = $gc_data[$field_mapping['balance']];
                $checkBalance = ImportGiftcardModel::check_balance($balance);
                if(!$checkBalance){
                    $result[$index] = array('status' => false, 'message' => 'Balance of the code fail', 'data' => $gc_data);
                    $error++;
                    continue;
                }
                $data['balance'] = $gc_data[$field_mapping['balance']];
//                $data['expiry_date'] = $gc_data[$field_mapping['expiry_date']];
                $data['status'] = $field_mapping['status'];
                $gc_id = \admin\ImportGiftcardModel::create_giftcard($data);
                if($gc_id){
                    $list_price[] = $data['balance'];
                    $result[$index] = array('status' => true, 'message' => 'Successfull' , 'data' => $gc_data);
                    $success++;
                } else {
                    $result[$index] = array('status' => false, 'message' => 'Can\'t save to database' , 'data' => $gc_data);
                    $error++;
                }
            }

            $data_response['number_of_row_success'] = $success;
            $data_response['number_of_row_error'] = $error;
            $data_response['result_save'] = $result;

            if ($success > 0){
                // update product data
                sort($list_price);
                $product_config['price'] = $list_price;
                \admin\ImportGiftcardModel::update_product_config($product_config);

                $data_response['status'] = true;
                $data_response['message'] = 'Save successfull';

                wp_die(json_encode($data_response));
            }

            $data_response['message'] = 'Save error';

            wp_die(json_encode($data_response));
        }
        wp_die(json_encode($data_response));
    }
}