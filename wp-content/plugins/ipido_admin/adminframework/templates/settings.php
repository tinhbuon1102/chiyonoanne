<?php
/**
 * @uses CSFramework_Settings @class
 */
$single_page   	= ( $class->is( 'single_page' ) === true ) ? 'yes' : 'no';
$sticky_header	= ( $class->is( 'sticky_header' ) === true ) ? 'csf-sticky-header' : false;
$ajax          	= ( $class->is( 'ajax_save' ) === true ) ? 'yes' : 'no';
$title         	= $class->_option( 'framework_title' );
$subtitle 		= $class->_option('framework_subtitle');
$has_nav       	= ( $class->is( 'has_nav' ) === false ) ? 'csf-show-all' : '';
?>
<div class="wrap"><h1 class="wp-heading-inline"><?php echo $title; ?></h1>
	<div class="csf-framework csf-option-framework csf-theme-<?php echo $class->theme(); ?>"
		data-theme="<?php echo $class->theme(); ?>"
		data-single-page="<?php echo $single_page; ?>"
		data-stickyheader="<?php echo $sticky_header; ?>">

		<form method="post" action="options.php" enctype="multipart/form-data" class="csf-form" id="csframework_form">
			<?php settings_fields( $class->get_unique() ); ?>
			<input type="hidden" class="csf-reset" name="csf-section-id" value="<?php echo $class->active( false ); ?>"/>
			<!--<input class="csf_parent_section_id" type="hidden" name="csf-parent-id" value="<?php echo $class->active(); ?>"/>-->

			<?php

			csf_template( $class->override_location(), $class->theme() . '.php', array(
				'class'         => $class,
				'single_page'   => $single_page,
				'sticky_header' => $sticky_header,
				'ajax'          => $ajax,
				'title'         => $title,
				'subtitle'		=> $subtitle,
				'has_nav'       => $has_nav,
			) );
			?>

		</form>
		<div class="clear"></div>
	</div>
</div>
