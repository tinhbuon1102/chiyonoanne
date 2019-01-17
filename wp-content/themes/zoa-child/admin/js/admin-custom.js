$(function() {
	$('iframe').on('load',function(){
            alert('runing');
		$('iframe').contents().find('#wpwrap').addClass('iframe-body');
	});
});