(function($) {
	'use strict';
	/*
	Dropdown
	=========================== */
	$('ul li.dropdown').hover(function () {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn();
			return false;
		}, function () {
			$(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut();
			return false;
	});
	
	/*
	Header
	=========================== */
	$(window).scroll(function(){
		var scrollTop = $(window).scrollTop();
		if(scrollTop != 0){
			$(".navbar-default").addClass("top-nav-collapse");
			return false;
		} else {
			$(".navbar-default").removeClass("top-nav-collapse");
			return false;
		}
	});

	/*
	tooltip
	=========================== */
	$('.tooltips').tooltip({
		selector: "a[data-toggle^=tooltip]"
	})	
	
	/*
	Image hover
	=========================== */	
	$(".img-caption").css({'opacity':'0','filter':'alpha(opacity=0)'});	
	$('.img-wrapper').on("mouseenter", function() {
		$(this).find('.img-caption').stop().fadeTo(900, 1);$(".img-zoom", this).stop().animate({left:'50%'},{queue:false,duration:600});$(".img-link", this).stop().animate({right:'50%'},{queue:false,duration:600});$(".img-meta", this).stop().animate({bottom:'50%'},{queue:false,duration:800});
		return false;
	})
	$('.img-wrapper').on("mouseleave", function() {
		$(this).find('.img-caption').stop().fadeTo(900, 0);$(".img-zoom", this).stop().animate({left:'-50%'},{queue:false,duration:300});$(".img-link", this).stop().animate({right:'-50%'},{queue:false,duration:300});$(".img-meta", this).stop().animate({bottom:'-50%'},{queue:false,duration:900});
		return false;
	});	

	/* Client logo hover
	=========================== */	
	$(".logo-hover").css({'opacity':'0','filter':'alpha(opacity=0)'});	
	$('.client-link').on("mouseenter", function() {
		$(this).find('.logo-hover').stop().fadeTo(900, 1);$(this).find('.client-logo').stop().fadeTo(900, 0);
		return false;
	})
	$('.client-link').on("mouseleave", function() {
		$(this).find('.logo-hover').stop().fadeTo(900, 0);$(this).find('.client-logo').stop().fadeTo(900, 1);
		return false;
	});	

	/*
	Bounce animated
	=========================== */	
	$(".e_bounce").hover(
		function () {
			$(this).addClass("animated bounce");
			return false;
		},
		function () {
			$(this).removeClass("animated bounce");
			return false;
		}
	);
})(jQuery);