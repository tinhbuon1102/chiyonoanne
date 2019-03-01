<?php
if ( function_exists( 'hfe_render_header' ) ) :
	hfe_render_footer();
else:
?>

<footer id="theme-footer">
	<?php zoa_footer(); ?>
</footer>

<?php
endif;
// close tag content container `</div>`
zoa_after_content();
echo '<div class="site-hider"><div class="site-hider__logo"></div></div>';
echo '<div class="site-overlay"></div>';
// quick view markup
if ( class_exists( 'woocommerce' ) ) {
	zoa_product_action();
}

if ( get_theme_mod( 'ajax_search', false ) ) {
	zoa_ajax_search_form();
} else {
	zoa_dialog_search_form();
}

?>
<a href="#" class="scroll-to-top js-to-top">
	<i class="ion-chevron-up"></i>
</a>
</div><!-- #theme-container -->
<?php
if ( true === get_theme_mod( 'loading', false ) ) {
	echo '<span class="is-loading-effect"></span>';
}
?>

<?php
wp_footer();
?>

<div class="remodal" data-remodal-id="portfolio_modal" id="portfolio_modal" role="dialog" data-remodal-options="hashTracking: false, closeOnOutsideClick: true">
  <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
  <div class="remodal_wraper">
    
  </div>
  <br>
</div>

<div class="remodal" data-remodal-id="news_top_modal" id="news_top_modal" role="dialog" data-remodal-options="hashTracking: false, closeOnOutsideClick: true">
  <button data-remodal-action="close" class="remodal-close" aria-label="Close"></button>
  <div class="remodal_wraper">
    
  </div>
  <br>
</div>


</body>
</html>
