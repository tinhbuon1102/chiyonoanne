<?php
/**
 * Created by PhpStorm.
 * User: doanhcn2
 * Date: 26/07/2018
 * Time: 08:22
 */
if (!defined('ABSPATH')) {
    exit();
}
$prd_id = isset($_GET['product_id']) ? $_GET['product_id'] : "";
?>
<style>
    .magenest_sample_file{
        display: inline-block;
        margin-bottom: 15px;
        font-weight: bold;
    }
</style>
<div class="container">
    <h2><?= __('Import Gift Card', 'GIFTCARD') ?></h2>
    <div class="row form-group">
        <div class="col-xs-12">
            <ul class="nav nav-pills nav-justified thumbnail setup-panel">
                <li class="active">
                    <a href="#step-1">
                        <h4 class="list-group-item-heading"><?=__('Step 1',GIFTCARD_TEXT_DOMAIN)?></h4>
                        <p class="list-group-item-text"><?=__('Choose File Import',GIFTCARD_TEXT_DOMAIN)?></p>
                    </a>
                </li>
                <li class="disabled">
                    <a href="#step-2">
                        <h4 class="list-group-item-heading"><?=__('Step 2',GIFTCARD_TEXT_DOMAIN)?></h4>
                        <p class="list-group-item-text"><?=__('Mapping fields',GIFTCARD_TEXT_DOMAIN)?></p>
                    </a>
                </li>
                <li class="disabled">
                    <a href="#step-3">
                        <h4 class="list-group-item-heading"><?=__('Step 3',GIFTCARD_TEXT_DOMAIN)?></h4>
                        <p class="list-group-item-text"><?=__('Import to database',GIFTCARD_TEXT_DOMAIN)?></p>
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <div class="row setup-content" id="step-1">
        <div class="col-xs-12">
            <div class="col-md-12 well text-center">
                <h2><?=__('Choose File Import',GIFTCARD_TEXT_DOMAIN)?></h2>
                <div class="magenest_sample_file">
                    <?php
                    $link_sample = GIFTCARD_URL.'/assets/magenest_sample_giftcard_code.csv';
                    $link_image = GIFTCARD_URL.'/assets/download.png'
                    ?>
                    <a href="<?= $link_sample ?>" title="Sample csv" class="button" style="color: #428dc7;">
                        <?=__('Download File Sample Data',GIFTCARD_TEXT_DOMAIN)?>
                    </a>
                </div>
                <form class="container" id="form1" enctype="multipart/form-data" method="post" action="">
                    <div class="col-md-12 well text-center">
                        <div>
                            <label for="fileToUpload"><?=__('Select a File to Upload (Support file .csv)',GIFTCARD_TEXT_DOMAIN)?></label><br />
                            <input type="file" name="fileToUpload" id="fileToUpload" style="display: none"/>
                        </div>
                    </div>
                    <div id="fileName"></div>
                    <div id="fileSize"></div>
                    <div id="fileType"></div>
                    <div class="row">
                        <input type="button" value="<?= __('Upload File' , 'GIFTCARD') ?>" class="btn btn-primary btn-lg" id="import_button" disabled/>
                    </div>
                    <div id="progressNumber"></div>
                </form>
            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-2">
        <div class="col-xs-12">
            <div class="col-md-12 well">
                <h2 class="text-center"><?=__('Mapping Fields',GIFTCARD_TEXT_DOMAIN)?></h2>
                <table class="table table-striped table-hover">
                    <thead>
                        <th><?=__('Gift Card Fiels',GIFTCARD_TEXT_DOMAIN)?></th>
                        <th><?=__('File Collums',GIFTCARD_TEXT_DOMAIN)?></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?=__('Gift Card Code',GIFTCARD_TEXT_DOMAIN)?></td>
                            <td>
                                <select class="form-control" name="gc_code" id="gc_code">

                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?=__('Balance',GIFTCARD_TEXT_DOMAIN)?></td>
                            <td>
                                <select class="form-control" name="gc_balance" id="gc_balance">

                                </select>
                            </td>
                        </tr>

                        <tr>
                            <td><?=__('Status',GIFTCARD_TEXT_DOMAIN)?></td>
                            <td>
                                <select class="form-control" name="gc_status" id="gc_status">
                                    <option value="-1" selected>In stock</option>
                                </select>
                            </td>
                        </tr>
                    </tbody>

                </table>
                <h2 class="text-center"><?=__('Product config',GIFTCARD_TEXT_DOMAIN)?></h2>
                <table class="table table-striped table-hover">
                    <thead>
                    <th><?=__('Product Attribute',GIFTCARD_TEXT_DOMAIN)?></th>
                    <th><?=__('Value',GIFTCARD_TEXT_DOMAIN)?></th>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?=__('Product',GIFTCARD_TEXT_DOMAIN)?></td>
                            <td>
                                <select name="gc_product" class="form-control" id="gc_product">
                                    <option value="0">------ <?= __('Specify the Giftcard product','GIFTCARD'); ?> ------</option>
                                    <?php
                                    $args = array(
                                        'post_type' => 'product',
                                        'post_status' => 'publish',
                                        'posts_per_page' => -1
                                    );

                                    $products = get_posts($args);

                                    foreach ($products as $product){
                                        $productId = $product->ID;//_giftcard;
                                        $is_giftcard = get_post_meta($productId,'_giftcard', true);
                                        if($is_giftcard != "yes") continue;
	                                    if (!empty($prd_id) && $prd_id == $product->ID)  $selected = "selected"; else $selected = "";
                                        echo '<option value="'.$product->ID.'" '. $selected .'>'.get_the_title($product->ID).'</option>';
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <td colspan="2">
                            <button id="activate-step-3" class="btn btn-primary btn-lg"><?= __('Import to Database' , GIFTCARD_TEXT_DOMAIN) ?></button>
                        </td>
                    </tfoot>
                </table>

            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-3">
        <div class="col-xs-12">
            <div class="col-md-12 well">
                <h1 class="text-center"><?=__('Progress to Database',GIFTCARD_TEXT_DOMAIN)?></h1>
                <div id="save_to_database_progress">
<!--                    <div id="save_to_database_bar"></div>-->
                </div>
                <br/>
                <div id="gc_result_save"></div>
	            <?php
	                if (!empty($prd_id)) {
	                	?>
		                <a id="activate-step-3" class="btn btn-primary btn-lg" href="<?= admin_url('post.php?post=' .$prd_id. '&action=edit'); ?>"><?= __('Back to Product' , GIFTCARD_TEXT_DOMAIN) ?></a>
		                <?php
	                }
	            ?>
	            <a id="activate-step-3" class="btn btn-primary btn-lg" href="<?= admin_url('edit.php?post_type=shop_giftcard'); ?>"><?= __('Gift Card Page' , GIFTCARD_TEXT_DOMAIN) ?></a>
            </div>
        </div>
    </div>
</div>
