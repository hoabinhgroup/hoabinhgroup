function isAlphaNum(event) {
	var regex = new RegExp("^[a-zA-Z0-9\\s]+$");
	var key = String.fromCharCode(!event.charCode ? event.which : event.charCode);
	if (!regex.test(key)) {
		event.preventDefault();
		return false;
	}
}

jQuery(window).load(function(){
	function mobile() {
		var checkWidth = document.body.clientWidth;		
		var demo = $(".owl-demo-grid");
		if (checkWidth > 767) {
			if(typeof demo.data('owlCarousel') != 'undefined') {
				demo.data('owlCarousel').destroy();
				demo.removeClass('owl-carousel');
			}
		} else if (checkWidth < 768) {
			demo.owlCarousel({
				items : 2,
				itemsDesktop : [1199,1],
				itemsDesktopSmall : [979,2],
				itemsTablet: [768, 2],
				itemsMobile: [600, 1],
			});
		}
	}

	$(document).ready(mobile);
	$(window).resize(mobile);
	//"use strict";


	if ( jQuery('.parallax').length ) {
		jQuery('.parallax').each(function(){
			jQuery(this).parallax("30%", 0.1);
		});
	}

	jQuery('.page-loader').remove();
});

jQuery(document).ready(function(){

	"use strict";
	$( ".box_collection_mobile ul li a" ).click(function() {
		$( ".box_collection_mobile ul li" ).toggleClass( "show" );
	});
	/* Method for award slider */
	jQuery(".tz-award-slider").owlCarousel({
		items : 1,
		itemsDesktop : [1199,1],
		itemsDesktopSmall : [979,1],
		itemsTablet: [768, 1],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:true,
		autoHeight : true,
		navigation : false,
		paginationNumbers:true,
		itemsScaleUp:false
	});
	jQuery("#author-slider .slider-items").owlCarousel({
		items : 1,
		itemsDesktop : [1199,1],
		itemsDesktopSmall : [979,1],
		itemsTablet: [768, 1],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:true,
		autoHeight : true,
		navigation : false,
		paginationNumbers:true,
		itemsScaleUp:false
	});

	/* Method for simple slider */
	jQuery(".tz-simple-product").owlCarousel({
		items : 1,
		itemsDesktop : [1199,1],
		itemsDesktopSmall : [979,1],
		itemsTablet: [768, 1],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:false,
		paginationNumbers:false,
		itemsScaleUp:false
	});
	// Custom Navigation Events
	jQuery(".simple_prev").click(function(){
		jQuery(".tz-simple-product").trigger('owl.prev');
	}) ;
	jQuery(".simple_next").click(function(){
		jQuery(".tz-simple-product").trigger('owl.next');
	}) ;

	/* Method for Partner slider */
	jQuery(".partner-slider").owlCarousel({
		items : 4,
		itemsDesktop : [1199,4],
		itemsDesktopSmall : [979,3],
		itemsTablet: [768, 2],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:false,
		navigation : true,
		navigationText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
		paginationNumbers:false,
		itemsScaleUp:false
	});
	jQuery("#best-seller-slider .slider-items").owlCarousel({
		items: 2,
		itemsDesktop: [1024, 2],
		itemsDesktopSmall: [991, 3],
		itemsTablet: [767, 2],
		itemsMobile: [480, 1],
		navigation: !0,
		navigationText: ['<a class="flex-prev"></a>', '<a class="flex-next"></a>'],
		slideSpeed: 500,
		autoHeight: true,
		pagination: !1
	}),
		/* featured-slider */
		$("#featured-slider .slider-items").owlCarousel({
		items: 3,
		itemsDesktop: [1024, 3],
		itemsDesktopSmall: [900, 2],
		itemsTablet: [480, 1],
		itemsMobile: [414, 1],
		navigation: !0,
		navigationText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
		slideSpeed: 500,
		autoHeight: true,
		pagination: !1
	});
	/* featured-slider 2*/
	$("#featured-slider-2 .slider-items").owlCarousel({
		items: 4,
		itemsDesktop: [1024, 4],
		itemsDesktopSmall: [992, 3],
		itemsTablet: [768, 2],
		itemsMobile: [414, 1],
		navigation: !0,
		navigationText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
		slideSpeed: 500,
		autoHeight: true,
		pagination: !1
	});
	/* featured-slider 3 */
	$("#featured-slider-3 .slider-items").owlCarousel({
		items: 1,
		itemsDesktop: [1024, 1],
		itemsDesktopSmall: [992, 1],
		itemsTablet: [768, 1],
		itemsMobile: [414, 1],
		navigation: !0,
		navigationText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
		slideSpeed: 500,
		autoHeight: true,
		pagination: !1
	});
	//Contact page notification
	setTimeout(function(){
		if (window.location.href.indexOf("?contact_posted=true") > -1) {
			alert("Gửi email thành công");
		}
	}, 1000);
	/* Method for Our Team */
	jQuery(".tz-team-slider").owlCarousel({
		items : 4,
		itemsDesktop : [1199,4],
		itemsDesktopSmall : [979,2],
		itemsTablet: [768, 2],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:false,
		paginationNumbers:false,
		itemsScaleUp:false
	});

	/* Method for Quote */
	jQuery(".tz-quote").owlCarousel({
		items : 1,
		itemsDesktop : [1199,1],
		itemsDesktopSmall : [979,1],
		itemsTablet: [768, 1],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:false,
		paginationNumbers:false,
		itemsScaleUp:false
	});

	/* Method for say */
	jQuery(".tz-say-slider").owlCarousel({
		items : 3,
		itemsDesktop : [1199,3],
		itemsDesktopSmall : [979,2],
		itemsTablet: [768, 1],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:false,
		paginationNumbers:false,
		itemsScaleUp:false
	});
	// Custom Navigation Events
	jQuery(".say_prev").click(function(){
		jQuery(".tz-say-slider").trigger('owl.prev');
	}) ;
	jQuery(".say_next").click(function(){
		jQuery(".tz-say-slider").trigger('owl.next');
	}) ;
	// tab product
	$('.__MB_NEWS_TAB3 li a').click(function(){
		var getTabId		=		$(this).attr('id'); 							
		$('.__MB_CONTAINER_READ3').hide();							
		$('.__MB_NEWS_TAB3 li a,.__MB_NEWS_TAB3 li').removeClass('active');							
		$(this).addClass('active');	
		$(this).parent().addClass('active');								
		$('.__MB_CONTAINER_READ3_'+getTabId).show();							
	});
	// slide product
	var sync1 = $("#sync1");
	var sync2 = $("#sync2");

	sync1.owlCarousel({
		singleItem : true,
		slideSpeed : 1000,
		navigation: true,
		pagination:false,
		afterAction : syncPosition,
		responsiveRefreshRate : 200,
	});

	sync2.owlCarousel({
		items : 5,
		itemsDesktop      : [1199,5],
		itemsDesktopSmall     : [979,5],
		itemsTablet       : [768,4],
		itemsMobile       : [479,3],
		pagination:false,
		responsiveRefreshRate : 100,
		afterInit : function(el){
			el.find(".owl-item").eq(0).addClass("synced");
		}
	});

	function syncPosition(el){
		var current = this.currentItem;
		$("#sync2")
			.find(".owl-item")
			.removeClass("synced")
			.eq(current)
			.addClass("synced")
		if($("#sync2").data("owlCarousel") !== undefined){
			center(current)
		}
	}

	$("#sync2").on("click", ".owl-item", function(e){
		e.preventDefault();
		var number = $(this).data("owlItem");
		sync1.trigger("owl.goTo",number);
	});

	function center(number){
		var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
		var num = number;
		var found = false;
		for(var i in sync2visible){
			if(num === sync2visible[i]){
				var found = true;
			}
		}

		if(found===false){
			if(num>sync2visible[sync2visible.length-1]){
				sync2.trigger("owl.goTo", num - sync2visible.length+2)
			}else{
				if(num - 1 === -1){
					num = 0;
				}
				sync2.trigger("owl.goTo", num);
			}
		} else if(num === sync2visible[sync2visible.length-1]){
			sync2.trigger("owl.goTo", sync2visible[1])
		} else if(num === sync2visible[0]){
			sync2.trigger("owl.goTo", num-1)
		}

	}
	/* Method for Header */
	var $_window =  jQuery(window).width();
	if ( $_window >= 992 ) {
		var $_scrollTop  = '' ;
		$_scrollTop      = jQuery(this).scrollTop();
		if ( $_scrollTop >= 30 ) {
			jQuery('.tz-header2').addClass('tz-header-animation');
			jQuery('.tz-header5').addClass('tz-header5-eff');

		}else{
			jQuery('.tz-header2').removeClass('tz-header-animation');
			jQuery('.tz-header5').removeClass('tz-header5-eff');
		}
		jQuery(window).scroll(function(){
			var $_scrollTop  = '' ;
			$_scrollTop      = jQuery(this).scrollTop();
			if ( $_scrollTop >= 30 ) {
				jQuery('.tz-header2').addClass('tz-header-animation');


			}else{
				jQuery('.tz-header2').removeClass('tz-header-animation');


			}

		});
	}

	/* Method for skill */
	if ( jQuery('.chart').length > 0 ) {
		var $_elementTop =   jQuery('.chart').offset().top;
		jQuery(window).scroll(function(){
			var $_Top = jQuery(window).scrollTop();
			if ( $_Top > $_elementTop - 600 ){
				jQuery('.chart').easyPieChart({
					easing: 'easeOutBounce',
					scaleColor: false,
					size: 150,
					lineWidth: 5,
					trackColor: '#615950',
					barColor: '#f1805f',
					onStep: function(from, to, percent) {
						jQuery(this.el).find('.percent').text(Math.round(percent));
					}
				});
				jQuery('.tz-rotate-skill').css('opacity', 1);
			}
		});
	}

	/* Method for menu offcavas */
	var $true = true;
	jQuery('.tz-offcavas').click(function(){
		if ( $true == true ){
			jQuery('.tz-offcavans-menu').animate({
				left : '0'
			},300) ;
			jQuery('.tzscrollable').addClass('tz-offcavans-menu-eff');
			$true = false;
		}else{
			jQuery('.tz-offcavans-menu').animate({
				left : '-20em'
			},150,function(){
				jQuery('.tzscrollable').removeClass('tz-offcavans-menu-eff');
			}) ;
			$true = true;
		}
	});
	jQuery('.tz-offcanvas-close').click(function(){
		jQuery('.tz-offcavans-menu').animate({
			left : '-20em'
		},150,function(){
			jQuery('.tzscrollable').removeClass('tz-offcavans-menu-eff');
		}) ;
		$true = true;
	});

	/* Method for shop features slider */
	jQuery(".features-slider").owlCarousel({
		items : 2,
		itemsDesktop : [1199,2],
		itemsDesktopSmall : [979,2],
		itemsTablet: [768, 2],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:false,
		paginationNumbers:false,
		itemsScaleUp:false
	});
	jQuery(".features_prev").click(function(){
		jQuery(".features-slider").trigger('owl.prev');
	}) ;
	jQuery(".features_next").click(function(){
		jQuery(".features-slider").trigger('owl.next');
	}) ;

	/* Method for shop sales slider */
	jQuery(".sales-slider").owlCarousel({
		items : 2,
		itemsDesktop : [1199,2],
		itemsDesktopSmall : [979,2],
		itemsTablet: [768, 2],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:false,
		paginationNumbers:false,
		itemsScaleUp:false
	});
	jQuery(".sales_prev").click(function(){
		jQuery(".sales-slider").trigger('owl.prev');
	}) ;
	jQuery(".sales_next").click(function(){
		jQuery(".sales-slider").trigger('owl.next');
	}) ;

	/* Method for shop features 2 slider */
	jQuery(".features-slider2").owlCarousel({
		items : 2,
		itemsDesktop : [1199,2],
		itemsDesktopSmall : [979,2],
		itemsTablet: [768, 2],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:false,
		paginationNumbers:false,
		itemsScaleUp:false
	});
	// Custom Navigation Events
	jQuery(".features_prev2").click(function(){
		jQuery(".features-slider2").trigger('owl.prev');
	}) ;
	jQuery(".features_next2").click(function(){
		jQuery(".features-slider2").trigger('owl.next');
	}) ;

	/* Method for shop sales 2 slider */
	jQuery(".sales-slider2").owlCarousel({
		items : 2,
		itemsDesktop : [1199,2],
		itemsDesktopSmall : [979,2],
		itemsTablet: [768, 2],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:false,
		paginationNumbers:false,
		itemsScaleUp:false
	});
	jQuery(".sales_prev2").click(function(){
		jQuery(".sales-slider2").trigger('owl.prev');
	}) ;
	jQuery(".sales_next2").click(function(){
		jQuery(".sales-slider2").trigger('owl.next');
	}) ;

	/* Method for shop Tab */
	jQuery('.tz-shop-tab').each(function(){
		jQuery(this).find('.tzshop-content:first').show();
		jQuery(this).find('.tz-shop-title small:first').addClass('tab-active');
	});
	jQuery('.tz-shop-header .tz-shop-title small').click(function(){
		jQuery(this).parent().find('small').removeClass('tab-active');
		jQuery(this).addClass('tab-active');
		var $id = jQuery(this).attr('data-option-value');
		jQuery(this).parent().parent().parent().find('.tzshop-content').slideUp();
		jQuery('#'+$id).slideDown();
	}) ;

	/* Method for Bestsellers */
	jQuery(".tz-bestsellers-slider").owlCarousel({
		items : 3,
		itemsDesktop : [1199,3],
		itemsDesktopSmall : [979,2],
		itemsTablet: [768, 2],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:false,
		paginationNumbers:false,
		itemsScaleUp:false
	});
	jQuery(".bestsellers_prev").click(function(){
		jQuery(".tz-bestsellers-slider").trigger('owl.prev');
	}) ;
	jQuery(".bestsellers_next").click(function(){
		jQuery(".tz-bestsellers-slider").trigger('owl.next');
	}) ;

	/* Method for Love Word  */
	jQuery(".tz-love-slider").owlCarousel({
		items : 3,
		itemsDesktop : [1199,3],
		itemsDesktopSmall : [979,2],
		itemsTablet: [768, 2],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:true,
		paginationNumbers:false,
		itemsScaleUp:false
	});

	/* tz-love-words-slider */
	jQuery(".tz-love-words-slider").owlCarousel({
		items : 1,
		itemsDesktop : [1199,1],
		itemsDesktopSmall : [979,1],
		itemsTablet: [768, 1],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:true,
		paginationNumbers:false,
		itemsScaleUp:false
	});

	/* Method for video */
	jQuery('.tzautoplay').click(function(){
		var $_this = jQuery(this);
		var myVideo = $_this.parents('.tz-video').find('.videoID')[0];
		jQuery(this).hide();
		$_this.parents('.tz-video').find('.tz-video-content h3').css('opacity',0);
		$_this.parents('.tz-video').find('.tz-video-content strong').css('opacity',0);
		$_this.parents('.tz-video').find('.bg-video').hide();
		$_this.parents('.tz-video').find('.tzpause').show().css('opacity',0);
		if (myVideo.paused)
			myVideo.play();

	}) ;
	jQuery('.tzpause').click(function(){
		jQuery(this).hide();
		var $_this = jQuery(this);
		var myVideo = $_this.parents('.tz-video').find('.videoID')[0];
		$_this.parents('.tz-video').find('.tz-video-content h3').css('opacity',1);
		$_this.parents('.tz-video').find('.tz-video-content strong').css('opacity',1);
		$_this.parents('.tz-video').find('.bg-video').show();
		$_this.parents('.tz-video').find('.tzautoplay').show();
		if (myVideo.play)
			myVideo.pause();

	});

	/* Method for Search */
	jQuery('.tz-search').click(function(){
		jQuery('.tz-form-search').css('display','block');
		jQuery('.tz-search-input').focus();
	}) ;
	jQuery('.tz-form-close').click(function(){
		jQuery('.tz-form-search').css('display','none');
	});

	/* Method for Single portfolio */
	jQuery(".tz-slider-full").owlCarousel({
		items : 1,
		itemsDesktop : [1199,1],
		itemsDesktopSmall : [979,1],
		itemsTablet: [768, 1],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:false,
		paginationNumbers:false,
		itemsScaleUp:false
	});
	jQuery(".tz_slider_prev").click(function(){
		jQuery(".tz-slider-full").trigger('owl.prev');
	}) ;
	jQuery(".tz_slider_next").click(function(){
		jQuery(".tz-slider-full").trigger('owl.next');
	}) ;

	/* Method for Single portfolio gallery */
	jQuery(".tz-gallery-full").owlCarousel({
		items : 4,
		itemsDesktop : [1199,4],
		itemsDesktopSmall : [979,2],
		itemsTablet: [768, 1],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:false,
		paginationNumbers:false,
		itemsScaleUp:false
	});

	/* Method for offfice */
	jQuery(".tz-offfice-slider").owlCarousel({
		items : 4,
		itemsDesktop : [1199,4],
		itemsDesktopSmall : [979,2],
		itemsTablet: [768, 1],
		itemsMobile: [479, 1],
		slideSpeed:500,
		paginationSpeed:800,
		rewindSpeed:1000,
		autoPlay:false,
		stopOnHover: false,
		singleItem:false,
		rewindNav:false,
		pagination:false,
		paginationNumbers:false,
		itemsScaleUp:false
	});

	/* Accordion Toggle Items */
	var iconOpen = 'fa-chevron-down',
		iconClose = 'fa-chevron-right';

	jQuery('.tzaccordion').each(function(){
		jQuery(this).find('p:first').show();
		jQuery(this).find('h6:first').addClass('active');
		jQuery(this).find('h6:first i').addClass(iconOpen).removeClass(iconClose);
	});
	jQuery('.tzaccordion h6').click(function() {
		var $_this = jQuery(this);
		$_this.parent().find('.active i').addClass(iconClose).removeClass(iconOpen);
		$_this.parent().find('.active').removeClass('active');
		$_this.parent().find('p').slideUp('normal');
		if(jQuery(this).next('p').is(':hidden') == true) {
			jQuery(this).find('i').addClass(iconOpen).removeClass(iconClose);
			jQuery(this).addClass('active');
			jQuery(this).next('p').slideDown('normal');
		}
	});



});