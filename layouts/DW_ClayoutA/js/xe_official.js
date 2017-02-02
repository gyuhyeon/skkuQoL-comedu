jQuery(function($){

// Main Menu
	var sHeader = $('div.header')
	var sMenu = $('ul#menu');
	var aItem = sMenu.find('>li>a');
	var sItem = sMenu.find('>li');
	var ssItem = sMenu.find('li');
	var sshhItem = sMenu.find('>li>div');
	var aaItem = sshhItem.find('a');
	var LastLi = sMenu.find('li').last();
	var lastEvent = null;
	var div_Height=sshhItem.outerHeight();
	var div_Height = div_Height + 79;
	function sMenuSlide(){
		
		var t = $(this);
		t.next().children().find('li').removeClass('highlight');
		sHeader.addClass('act_header');
		$('div.gnb').animate({
			height: div_Height
		  }, 300 );
		
	}
	aItem.mouseover(sMenuSlide).focus(sMenuSlide);

	function fadeOut_menu(){
		sHeader.removeClass('act_header');
		$('div.gnb').stop(true,true).animate({
			height: "79"
		  }, 30 );
		}
	$('.wrap_menu').mouseleave(fadeOut_menu);
	LastLi.focusout(fadeOut_menu);

	function hideMenuToggle(){
		var thisBtn = $(this);
    	var targetBox = $('.' + 'li_' + thisBtn.attr('name'));
    	var targetLi = $('.li_banner');
		$('.li_banner').css('display','none');
    	targetBox.css('display','block');	
    }
    sItem.mouseover(hideMenuToggle); 

// 언어선택창
	function LoginToggle(){
			
			if ($('.selectLang').hasClass('none_login')) {
				$('.selectLang').removeClass('none_login');
				$('.selectLang').slideDown(200);           
			} else {
				$('.selectLang').slideUp(200);
				$('.selectLang').addClass('none_login');
			
			}; 
		};
		$('.toggle').click(LoginToggle);
// h2 하단 라인 액션
	$('.wrap_widgetA').hover(
	  function () {
		 $(this).find("hr.DW_StA_hr").addClass('DW_StA_hr_act');
		$(this).find("hr.DW_StA_hr").animate({
		width : '100%'
		}, 300);
	}, 
	  function () {
	  $(this).find("hr.DW_StA_hr").removeClass('DW_StA_hr_act');
		$(this).find("hr.DW_StA_hr").stop(true, true).animate({
		width : 30
		}, 300);
	  }
	);
	if (!$('.foot_absolute').children('a').hasClass('ds_dw')) {
		$('.xe').css('display','none');

	}
// 배너형 액션
	

	$('.in_banner').hover(
	  function () {
		 
		$(this).find(".OverBanner1").fadeIn('400');
		
	}, 
	  function () {
	 $(this).find(".OverBanner1").stop().fadeOut('400');

		
	  }
	);
// SNS 로그인
	var keep_msg = $("#warning");
		$(".chk_label").on("mouseenter mouseleave focusin focusout", function (e) {
			if(e.type == "mouseenter" || e.type == "focusin") {
				keep_msg.show();
			}
			else {
				keep_msg.hide();
			}
		});
		$(".sns_login").click(function () {
			$(".login_widget").show();
			return false;
		});
		$(".btn_ly_popup").click(function () {
			$(".login_widget").hide();
			return false;
		});
		
		$("input").blur(function () {
			var $this = $(this);
			if ($this.val()) {
				$this.addClass("used");
			}
			else {
				$this.removeClass("used");
			}
		});



// 빵조각
	$('ul.breadclumb').find('>li').last().addClass('last_breadclumb');	
// 쫄쫄이

		$(document).on("scroll", onScroll);
			$('.nav a[href^="#"]').on('click', function (e) {
				e.preventDefault();
				$(document).off("scroll");
				
				$('.nav a').each(function () {
					$(this).removeClass('active');
				})
				$(this).addClass('active');
			  
				var target = this.hash,
					menu = target;
				$target = $(target);
			   $('html, body').stop().animate({
					'scrollTop': $target.offset().top
				}, 600, 'swing', function () {
					window.location.hash = target;
					$(document).on("scroll", onScroll);
				});
			});

			function onScroll(event){
				var scrollPos = $(window).scrollTop();
				var bodyPosition = $('#content').position().top;
				var bodyHeight = $('.body').outerHeight();
				
				
				
				$('.nav a').each(function () {
					var currLink = $(this);
					var refElement = $(currLink.attr("href"));
					if (refElement.position().top <= scrollPos && refElement.position().top + refElement.height() > scrollPos) {
						$('.nav ul li a').removeClass("active");
						currLink.addClass("active");
					}
					else{
						currLink.removeClass("active");
					}
				});
			}
		
// 헤더 액션
 var lastScroll = 0;

      $(window).scroll(function(event){
	  if  ($(window).scrollTop() >= 120){
		
          //Sets the current scroll position
          var st = $(this).scrollTop();
          //Determines up-or-down scrolling
          if (st > lastScroll){
             //Replace this with your function call for downward-scrolling
			
				 $('.header').stop().animate({
						top: -140                  
						}, 100, 'swing');

			
          }
          else {
             //Replace this with your function call for upward-scrolling
             $('.header').stop().animate({
                    top:-35                  
                    }, 100, 'swing');
          }
          //Updates scroll position
          lastScroll = st;
		 }
		if  ($(window).scrollTop() <= 120){
			var st = $(this).scrollTop();
			$('.header').stop().animate({
                    top: 0                  
                    }, 100, 'swing');
		}

      });


});