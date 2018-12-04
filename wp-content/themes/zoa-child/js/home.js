jQuery(document).ready(function($){
	setTimeout(function(){
		//rellax js for laptop
		$('#home-product-column ul.products li:nth-child(4)').after('<li class="product product_selection middle"><div class="c-product-item js-product-item"><div class="c-scroll-fade-in-block js-parallax"><div class="season_theme"></div></div></div></li>');
		var $catchTxt = 'あなただけのための<br/>お仕立てランジェリー';
		var $catchEl = $('.season_theme');
		var $Aboutlink = '<a href="'+get_url.siteurl+'/about" class="cta">About</a>';
		$catchEl.append($catchTxt);
		$catchEl.after($Aboutlink);
		var $win = $(window);
		if ($win.width() > 991) {
			var notList = 'ul.products li:nth-child(2), ul.products li:nth-child(5), ul.products li:nth-child(8)';
			$(notList).attr("data-rellax-speed", "-1").addClass('middle');
			$("ul.products li").not(notList).attr("data-rellax-speed", "1").addClass('no_middle');
			var rellax = new Rellax('#home-product-column ul.products > li');
			$('ul.products > li').inview({
				'viewFactor': 0.3
			});
		} else {
			$('#home-product-column ul.products li.product_selection').hide();
		}
	}, 100);
});