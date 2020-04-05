var name = '';
$(document).ready(function(){
	mainjs.initPopularSwiper();
	mainjs.initclientCarousel();
	mainjs.init();
	mainjs.initHomeMainSlider();
	if($('#totalAtoZ').length){
		mainjs.onScrollAtoZ();
		mainjs.goToPartner();
	}
	var url      = window.location.href;
	var spliturl = url.split('/');
	//alert(spliturl[spliturl.length-1]);
	console.log(spliturl);
	name = localStorage.getItem('closedOnce');	
	if (name == 'null' && (spliturl.length<5) && (spliturl[spliturl.length-1]=="")){
		mainjs.loadEmailSignUpPopup();
	}
});
$(window).load(function(){

	var url      = window.location.href;
	var urlNoProtocol = url.replace(/^https?\:\/\//i, "");
	var url_array = new Array();
	url_array = urlNoProtocol.split('/');
	var reverseArr = url_array.reverse();
	//alert(reverseArr[1]);
	if(reverseArr[2]!='products' && reverseArr[1]!='search'){
		$('.cs-loader').addClass('hideloader');
	}
	
});
var mainjs = {
	init:function(){
		var that = this;
		that.defineUIEvents();
		if($('#left-sidebar').length>0)
			that.addClassLeftSidebar('#left-sidebar');
		if($('#user-menu').length>0)
			that.addClassLeftSidebar('#user-menu');
		that.onEmailModalClose();
	},
	loadEmailSignUpPopup:function(){
		$('#myModal').modal('show');
	},
	onEmailModalClose:function(){
		$('.footerSignUpPopUp').on('click',function(){
			if (name == 'null'){

				localStorage.setItem("closedOnce", "1");	
							    		
			}

		});
	},	
	initPopularSwiper:function(){
		var swiper = new Swiper('.popular-products .swiper-container', {
	        pagination: '.swiper-pagination',
	        paginationClickable: false,
	        slidesPerView: 5,
	        spaceBetween: 30,
	        nextButton: '.popular-products .swiper-button-next',
	    	prevButton: '.popular-products .swiper-button-prev',
	        breakpoints: {
	            1024: {
	                slidesPerView: 5,
	                spaceBetween: 40
	            },
	            768: {
	                slidesPerView: 3,
	                spaceBetween: 30
	            },
	            640: {
	                slidesPerView: 2,
	                spaceBetween: 20
	            },
	            360: {
	                slidesPerView: 1,
	                spaceBetween: 20
	            }
	        }
	    });
	},
	initclientCarousel:function(){
		var swiper = new Swiper('.client-section .swiper-container', {
	        pagination: '.swiper-pagination',
	        paginationClickable: false,
	        slidesPerView: 4,
	        spaceBetween: 40,
	        nextButton: '.client-section .swiper-button-next',
	    	prevButton: '.client-section .swiper-button-prev',
	        breakpoints: {
	            1024: {
	                slidesPerView: 4,
	                spaceBetween: 40
	            },
	            768: {
	                slidesPerView: 3,
	                spaceBetween: 30
	            },
	            640: {
	                slidesPerView: 2,
	                spaceBetween: 20
	            },
	            360: {
	                slidesPerView: 1,
	                spaceBetween: 20
	            }
	        }
	    });
	},
	initHomeMainSlider:function(){
		var swiper = new Swiper('.cashback-showcase .swiper-container', {
	        pagination: '.swiper-pagination',
	        autoplay: 1500,
	        autoplayDisableOnInteraction: true,
	        paginationClickable: true
	    });
	},
	addClassLeftSidebar:function(selector){
		var window_width = $(window).width();
		if(window_width < 768) {
			$(selector).addClass('collapse');			
		}
	},
	preventDropdownCLosing:function(selector){
		$(document).on('click', selector , function (e) {
	      e.stopPropagation();
	    });
	},
	toggleIcon:function(e){
		$(e.target)
		        .prev('.panel-heading')
		        .find(".more-less")
		        .toggleClass('glyphicon-plus glyphicon-minus');
	},
	onScrollAtoZ:function(){
		var totalAtoZOffset = $('#totalAtoZ').offset().top;
		var footerTop = $('footer').offset().top;
		footerTop =footerTop-240;
		//alert(totalAtoZOffset);
		$(window).on('scroll',function(){
			var currentScroll = $(window).scrollTop();
			if((currentScroll>totalAtoZOffset) && (currentScroll<footerTop))
				$('#totalAtoZ').addClass('smallheader');
			else
				$('#totalAtoZ').removeClass('smallheader');
		});
	},
	goToPartner:function(){
		$('.totalatoz ol li a').on('click',function(){
			var $this = $(this);
			var x = $this.attr('data-goTo');
			//alert(x);
			if($('#'+x).length){
				$('html, body').animate({
			        scrollTop: $("#"+x).offset().top-100
			    }, 400);
			}
		});
	},
	defineUIEvents:function(){
		var that = this;
		/*.........check all brand checkbox .......*/
		$(document).on('change','.brand-listing #brand-all',function(){			
			var $this = $(this);			
			if($this.prop("checked")==true){
				$this.parents('.brand-listing').find('input[type="checkbox"]').prop("checked","checked");
			}
		});

		/*......back-to-top jquery....*/
		$(document).on('click','.back-to-top',function(){			
			$('html, body').animate({
		        scrollTop: $("body").offset().top
		    }, 400);
		});

		/*.........open-menu jQuery......*/
		$(document).on('click','.open-menu',function(){
			$('header nav').toggleClass('openmenu');
			$('body').toggleClass('ovhidden');
			if($('header nav').hasClass('openmenu') && ($(document).find('.close-menu').length == 0)){
				$('<div class="close-menu"></div>').appendTo('body');
			}
			else{
				$(document).find('.close-menu').remove();
			}
		});

		$(document).on('click','.close-menu,.close-nav',function(){			
			$('.open-menu').trigger('click');
		});
		
		$('.panel-group').on('hidden.bs.collapse', function(e){
			that.toggleIcon(e);
		});
		$('.panel-group').on('shown.bs.collapse', function(e){
			that.toggleIcon(e);
		});	

		$(document).on("click","#left ul.nav li.parent > a > span.sign", function(){          
	        $(this).find('i:first').toggleClass("icon-minus");      
	    }); 
	    
	    // Open Le current menu
	    $("#left ul.nav li.parent.active > a > span.sign").find('i:first').addClass("icon-minus");
	    $("#left ul.nav li.current").parents('ul.children').addClass("in");	
		
		that.preventDropdownCLosing('.search-bar .dropdown-menu');
		that.preventDropdownCLosing('nav > ul > li .dropdown-menu');
	}
}