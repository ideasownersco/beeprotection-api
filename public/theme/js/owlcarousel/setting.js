(function($) {
	'use strict';
      $("#theme-features").owlCarousel({
        autoPlay : true,stopOnHover : true,pagination : false,navigation:true,
		lazyLoad : true,paginationSpeed : 1000,goToFirstSpeed : 2000,singleItem : true,autoHeight : true,
      });
	  
      $("#client-testimoni").owlCarousel({
        autoPlay : 3000,stopOnHover : true,pagination : false,navigation:true,paginationSpeed : 1000,goToFirstSpeed : 2000,singleItem : true,autoHeight : true,transitionStyle:"fade"
      });
	  
      $("#clients-logo").owlCarousel({
        autoPlay : true,stopOnHover : true,pagination : false,navigation:true,paginationSpeed : 1000,goToFirstSpeed : 2000,items : 5,autoHeight : true,
      });
})(jQuery);