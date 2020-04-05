/*new PerfectScrollbar('.login_frm');*/

// Header Fixing
function FixedHeader() {

  $(window).scroll(function () {
        var top_offset = $(window).scrollTop();
        if (top_offset == 0) {
            $('.main-header').removeClass('static-header');
        } else {
            $('.main-header').addClass('static-header');
        }
    })
}

//ps scroll
var ps ='';
function pscroll(x){
     ps = new PerfectScrollbar('.'+x, {
    wheelSpeed: 2,
    wheelPropagation: true,
    minScrollbarLength: 20,
  });
}


$(window).load(function(){

    var isshow = localStorage.getItem('isshow');
    if (isshow== null) {
        localStorage.setItem('isshow', 1);

        // Show popup here
        $('#newsletter_modal').modal('show');
    }

  
})


;(function($) {

  if (typeof $.fn.tooltip.Constructor === 'undefined') {
    throw new Error('Bootstrap Tooltip must be included first!');
  }

  var Tooltip = $.fn.tooltip.Constructor;

  // add customClass option to Bootstrap Tooltip
  $.extend( Tooltip.Default, {
      customClass: ''
  });

  var _show = Tooltip.prototype.show;

  Tooltip.prototype.show = function () {

    // invoke parent method
    _show.apply(this,Array.prototype.slice.apply(arguments));

    if ( this.config.customClass ) {
      var tip = this.getTipElement();
      $(tip).addClass(this.config.customClass);
    }

  };

})(window.jQuery);

function ticket_letter_count(){}


$(document).ready(function(){

//

$("#ticket_subj_field").keydown(function(){
 var p_count_numb = $(this).val().length;
       $("#ticket_subj_count_numb").text(p_count_numb);

if(p_count_numb>=50){

if($('.custom_errorcount').length>0){
$('.custom_errorcount').html('Subject should be 50 letters');
}
else{
    $("#ticket_subj_field").next().append('<label class="error custom_errorcount">Subject should be 50 letters</label>')
}
}
else{
  $('.custom_errorcount').remove();
  
}


});

//

$("#ticket_dsc_field").keydown(function(e){
    var p_count_numb = $(this).val().length;
    $("#ticket_dsc_count_numb").text(p_count_numb);

    if(p_count_numb>=500){
      $(this).val($(this).val().substring(0, 500));
      if($('.custom_errorcount').length>0){
          $('.custom_errorcount').html('Description should be 500 letters');
      }
      else{
          $("#ticket_dsc_field").next().append('<label class="error custom_errorcount">Description should be 500 letters</label>')
      }
      
    }
    else{
      $('.custom_errorcount').remove();
    }

});


  //
  $('[data-toggle="tooltip"]:not(.uncomn-tooltip)').tooltip();

  //approved tooltip
  $('.approved-info-btn').tooltip({
    customClass: 'tooltip-approved'
  });

   //pending tooltip
    $('.pending-info-btn').tooltip({
    customClass: 'tooltip-pending'
  });

  //scrollber
if($('.giftCard-blnc-select-sec ul').length > 0) {
  pscroll('giftCard-blnc-select-sec ul');
}
//
if($('.comn-left-bar').length > 0) {
  pscroll('comn-left-bar');
}
if($('.left-middle-content').length > 0) {
  pscroll('left-middle-content');
}
if($('.chat-outer').length > 0) {
  pscroll('chat-outer');
}
if($('.referral-card-content').length > 0) {
  pscroll('referral-card-content');
}

if($('.inviteFriend-list-table').length > 0) {
  pscroll('inviteFriend-list-table');
} 

if($('.allstore-left-content-list').length > 0) {
  pscroll('allstore-left-content-list');
} 

if($('.store-tc-content').length > 0) {
  pscroll('store-tc-content');
} 

if($('.faq-left-content-list').length > 0) {
  pscroll('faq-left-content-list');
} 

if($('.cate-dropdown-menu').length > 0) {
  pscroll('cate-dropdown-menu');
}



$('.cate-dropdown-item').on('show.bs.dropdown', function () {
  ps.destroy();
  setTimeout(function(){pscroll('cate-dropdown-menu');},1000)
   
})

/*if($('.faq-right-content .tab-content').length > 0) {
  pscroll('tab-content');
} */

/*$('.faq-left-content-list a[data-toggle="pill"]').on('shown.bs.tab', function (e) {
ps.destroy();
pscroll('tab-content');
});*/

// banner-slider

   var swiper = new Swiper('.banner-slider-sec .swiper-container', {
      centeredSlides: true,
      loop:true,
      speed:700,
      autoplay:false,
/*      autoplay: {
        delay: 2500,
        disableOnInteraction: false,
      },*/
      pagination: {
      el: '.banner-slider-sec .swiper-pagination',
      clickable: true,
      },
    });

//similar store slider
    var swiper = new Swiper('.similar-store-slider .swiper-container', {
      slidesPerView: 3,
      slidesPerColumn: 2,
      spaceBetween: 30,

      navigation: {
        nextEl: '.similar-store-slider .swiper-button-next',
        prevEl: '.similar-store-slider .swiper-button-prev',
      },
      breakpoints: {
        991: {
          spaceBetween:15,
        },
      }
    });

   // header-fixed
   FixedHeader();

   // sign form input



     $('.sign-form .form-input').blur(function() {
    var $this = $(this);
    if ($this.val())
      $this.addClass('used');
    else
      $this.removeClass('used');
  });

  // left submenu  
  
  $('.submenu-wrap a').on('click',function(){
  $('.submenu-content').slideUp();
  $(this).parents('.submenu-wrap').find('.submenu-content').stop(true ).slideToggle();

  });

  //popover

 
  $('.cardInfo-popover').popover({
    container: 'body',
  })
  .on("mouseenter", function () {
        var _this = this;
        $(this).popover("show");
        $(".popover").on("mouseleave", function () {
            $(_this).popover('hide');
        });
    }).on("mouseleave", function () {
        var _this = this;
        setTimeout(function () {
            if (!$(".popover:hover").length) {
                $(_this).popover("hide");
            }
        }, 300);
});


// left menu toggle

$('.comn-left-toggle').on('click', function(){

$('.comn-left-bar-wrap').toggleClass('open');

});

// dashboard dsboard-referral-card-heading toggle

$('.dsboard-referral-card-heading').on('click', function(){

$('.dsboard-referral-card').toggleClass('open');
$('.referral-card-content-wrap').slideToggle();

});

//emotion select
$('.emotion-dropdown-menu li').on('click', function(){
  var li_value = $(this).data("value");
  console.log(li_value);
  $('.emotion-dropdown-selected-value').html(li_value);
});

//code edit toggle

$('.inviteFriend-editCode-edit-btn').on('click', function(){
  $(this).parents('.inviteFriend-input-editCode').removeClass('not-edit-mode');
  var len = $('.inviteFriend-input-editCode input').val().length;
  $('.inviteFriend-input-editCode input').focus();
     $('.inviteFriend-input-editCode input')[0].setSelectionRange(len, len);
  $('.inviteFriend-editCode-edit-btn').css('display','none');
  $('.inviteFriend-editCode-save-btn').css('display','block');
});

$('.inviteFriend-editCode-save-btn').on('click', function(){
  $(this).parents('.inviteFriend-input-editCode').addClass('not-edit-mode');
  $('.inviteFriend-editCode-edit-btn').css('display','block');
  $('.inviteFriend-editCode-save-btn').css('display','none');
});

// store category
$(window).resize(function(){
if($(window).width()<992){
storeCat();
}
});

if($(window).width()<992){
storeCat();
}

function storeCat(){
$('.allstore-left-content h3').on('click', function(){
$('.allstore-left-content').stop(true, true).delay(200).toggleClass('open');
$('.allstore-left-content-list').stop(true, true).delay(200).slideToggle(300);
});
}

//header category dropdown

$('.cate-dropdown-item').hover(function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(200).addClass('show');
}, function() {
  $(this).find('.dropdown-menu').stop(true, true).delay(200).removeClass('show');
});

//





});/*document.ready*/


//faq scroll

var wWidth = '';
var navHight = '';

$(document).ready(function () {
  wWidth = $(window).width();
    $(document).on("scroll", onScroll);
    
    //smoothscroll
    $('.faq-left-content-list a[href^="#"]').on('click', function (e) {
        e.preventDefault();
        //$(document).off("scroll");
        
        $('.faq-left-content-list a').each(function () {
            $(this).removeClass('active');
        })
        $(this).addClass('active');
      
        var target = this.hash,
            menu = target;
        $target = $(target);

        if(wWidth > 767){

        $('html, body').stop().animate({
            scrollTop: parseInt($target.offset().top)-145
        }, 500); 
      }

    else{
   navHight = $('.faq-left-content').height();
        $('html, body').stop().animate({
            scrollTop: parseInt($target.offset().top)-(navHight+135)
        }, 500); 
      }


       // $(document).scroll();
    });
});

function onScroll(event){
  //alert();
    var scrollPos = $(document).scrollTop();
    $('.faq-left-content-list a').each(function () {
        var currLink = $(this);
        var refElement = $(currLink.attr("href"));

        if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
            $('.faq-left-content-list a').removeClass("active");
            currLink.addClass("active");
        }
        else{
            currLink.removeClass("active");
        }
    });
}


$(window).scroll(function(){

if($('.faq-left-col').length>0){
  var menuTop = $('.faq-left-col').offset().top -135;

var subMenuHolder = $('.faq-left-content-wrap');


console.log();

      if ( $(document).scrollTop() > menuTop ) {
    
        subMenuHolder.addClass('faqmenu-fixed-top');
        
      } else if ( $(document).scrollTop() <= menuTop) {
        
        if ( subMenuHolder.hasClass('faqmenu-fixed-top') ) {
          
          subMenuHolder.addClass('faqmenu-fixed-top');
          
          setTimeout(function(){
            subMenuHolder.removeClass('faqmenu-fixed-top');
          }, 100 );
        }
      }       
    }

 if($('.faq-left-content-list').length>0){
    var lastId = $('.faq-left-content-list a:last-child').attr("href");
    var faqleftOffset = $(lastId).offset().top - 700;


  if($(document).scrollTop() >= faqleftOffset){
    subMenuHolder.removeClass('faqmenu-fixed-top');
    }
  }
      
});



//Fixed scroll

/*if($('.signup-sec').length >0){
var prev_offset = 0;
var offset =$('.signup-sec').offset().top - 500;
  $(window).scroll(function () {
        var prsnt_offset = $(window).scrollTop();
         

        if (prsnt_offset > prev_offset) {

            
             prev_offset = prsnt_offset;

            console.log(prev_offset+'::'+prsnt_offset);

             if(prsnt_offset >= offset){
                 $('.comn-fixed-sec').removeClass('fixed-bottom');
               }
               else{
                $('.comn-fixed-sec').addClass('fixed-bottom');
               }
        }


         else {
            prev_offset = prsnt_offset;
            
            $('.comn-fixed-sec').removeClass('fixed-bottom');
        }
    });
}*/


//how it work block active

$(document).ready(function () {

if($('.howItwork-step').length >0){

$(window).scroll(function () {

  //alert();
  var doc_top = $(window).scrollTop();


var element_1 = $('#howItwork_step_1').offset().top;
var element_2 = $('#howItwork_step_2').offset().top;
var element_3 = $('#howItwork_step_3').offset().top;
var element_4 = $('#howItwork_step_4').offset().top;
var element_5 = $('#howItwork_step_5').offset().top;

console.log(element_1);
console.log(element_2);
console.log(element_3);
console.log(element_4);
console.log(element_5);
console.log(doc_top);

if(element_1 >= doc_top){
$('.blue-line').css('top' , 0 );
$('.blue-line').addClass('active');

$('.howItwork-step-block').removeClass('active');
$('.howItwork-step-block-1').addClass('active');

}

else if(element_1 < doc_top && element_2 >= doc_top){

if(wWidth<=1199){
$('.blue-line').css('top' , 270);
}
else{
  $('.blue-line').css('top' , 320);
}

$('.blue-line').addClass('active');

$('.howItwork-step-block').removeClass('active');
$('.howItwork-step-block-2').addClass('active');

}

else if(element_1 < doc_top && element_2 < doc_top && element_3 >= doc_top ){
  if(wWidth<=1199){
$('.blue-line').css('top' , 540);
}
else{
 $('.blue-line').css('top' , 640); 
}

$('.blue-line').addClass('active');

$('.howItwork-step-block').removeClass('active');
$('.howItwork-step-block-3').addClass('active');

}
else if(element_1 < doc_top && element_2 < doc_top && element_3 < doc_top && element_4 >= doc_top ){
  if(wWidth<=1199){
$('.blue-line').css('top' , 810);
}
else{
$('.blue-line').css('top' , 960);
}

$('.blue-line').addClass('active');

$('.howItwork-step-block').removeClass('active');
$('.howItwork-step-block-4').addClass('active');

}

else{
  if(wWidth<=1199){
  $('.blue-line').css('top' , 1080);
}
else{
  $('.blue-line').css('top' , 1280);
}

  $('.blue-line').addClass('active');
  setTimeout(function(){
     $('.howItwork-sign-btn').removeClass('border-btn');
   },500);

  $('.howItwork-step-block').removeClass('active');
$('.howItwork-step-block-5').addClass('active');
 
}

})

}

});