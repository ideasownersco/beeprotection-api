(function($) {
    function gallery(){
			$(".img-caption").css({'opacity':'0','filter':'alpha(opacity=0)'});	
			$('.img-wrapper').hover(function(){
				$(this).find('.img-caption').stop().fadeTo(900, 1);$(".img-zoom", this).stop().animate({left:'50%'},{queue:false,duration:600});$(".img-link", this).stop().animate({right:'50%'},{queue:false,duration:600});$(".img-meta", this).stop().animate({bottom:'50%'},{queue:false,duration:800});
			}, function() {
				$(this).find('.img-caption').stop().fadeTo(900, 0);$(".img-zoom", this).stop().animate({left:'-50%'},{queue:false,duration:300});$(".img-link", this).stop().animate({right:'-50%'},{queue:false,duration:300});$(".img-meta", this).stop().animate({bottom:'-50%'},{queue:false,duration:900});
			});
			
			$(".img-caption:first a[data-pretty^='prettyPhoto']").prettyPhoto({animation_speed:'normal',theme:'pp_default',slideshow:3000, autoplay_slideshow: false});$(".img-caption:gt(0) a[data-pretty^='prettyPhoto']").prettyPhoto({animation_speed:'fast',slideshow:10000, hideflash: true});
		return false;
        }

    var $itemsHolder = $('.gallery-wrapper');var $itemsClone = $itemsHolder.clone();var $filterClass = "";
    $('ul.filter li').click(function(e) {
		$(".filter li").removeClass("active");	
    e.preventDefault();
    $filterClass = $(this).attr('data-value');
        if($filterClass == 'all'){ var $filters = $itemsClone.find('.gallery-item'); }
        else { var $filters = $itemsClone.find('.gallery-item[data-type='+ $filterClass +']'); }
        $itemsHolder.quicksand(
          $filters,
          { duration: 1000 },
          gallery
          );
		  return false;
    });
})(jQuery);