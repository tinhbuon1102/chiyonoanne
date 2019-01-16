(function($) {
  $(document).ready(function(){
    //Write jQuery script here
    
  });
	$('iframe').on('load',function(){
		$('iframe').contents().find('body').addClass('iframe-body');
	});
})(jQuery);