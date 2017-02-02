jQuery(function($){
		var $searchEl = $('.act_search');
        var $searchForm = $('.search_area'); 


		 $searchEl.click(function(){
            if($searchForm.is(':hidden')){
                $searchForm.fadeIn().find('input').focus();
               
            }
            return false;
        });
		 $('.btn_close').click(function(){
            var $this = $(this);
            $this.parent().fadeOut().find('input').val('');
            
            $searchEl.focus();
            return false;
        });
// 메뉴 액션
		if (!$('.foot_absolute').children('a').hasClass('ds_dw')) {
			$('.xe').css('display','none');

		}
		function TotalToggle(){
			
			if ($('.mm_mobile_menu').hasClass('none_mobile_menu')) { 
				$('.header').addClass('act_header');
				$('.mm_mobile_menu').removeClass('none_mobile_menu');
				$('.fix_mobile').css('display','block');
				$('#scrollUp').css('display','none');
				$('.mm_mobile_menu').stop().animate({width: '280px'}, 200, 'swing');
			} else {
				$('.mm_mobile_menu').addClass('none_mobile_menu');
				$('.header').removeClass('act_header');
				$('body').css('overflow-x','auto').stop().animate({ paddingLeft: '0',width:'100%'}, 200, 'swing');
				$('.fix_mobile').css('display','none');
				$('#scrollUp').css('display','block');
				$('.mm_mobile_menu').stop().animate({width: '0'}, 200, 'swing');
			}
			return false;
		}
		$('.mobile_menu_act').click(TotalToggle);

		var gItem = $('li.mm-list-li');
		var lastEvent = null;
		function gMenuToggle(){
			var t = $(this);
			if (t.next('ul').is(':hidden') || t.next('ul').length == 0) {
				gItem.find('>ul').slideUp(200);
				gItem.find('button').removeClass('hover');
				t.next('ul').slideDown(200);
				t.addClass('hover');            
			} else {
				gItem.find('>ul').slideUp(200);
				gItem.find('button').removeClass('hover');
			
			}; 
		};
		gItem.find('>button').click(gMenuToggle);



	function SearchToggle(){
			
			
			if ($('.mm_search_menu').hasClass('none_search_menu')) {
				$('.header').addClass('act_header');
				$('.mm_search_menu').removeClass('none_search_menu');
				$('.fix_mobile').css('display','block');
				$('#scrollUp').css('display','none');
				$('.mm_search_menu').stop().animate({width: '280px'}, 200, 'swing');
				
				
			} else {
				$('.mm_search_menu').addClass('none_search_menu');
				$('.header').removeClass('act_header');
				$('.fix_mobile').css('display','none');
				$('#scrollUp').css('display','block');
				$('.mm_search_menu').stop().animate({width: '0'}, 200, 'swing');
				
			
			}
			return false;
		}
	$('.search_menu').click(SearchToggle);


// 빵조각
	$('ul.breadclumb').find('>li').last().addClass('last_breadclumb');	
// 스크롤 메뉴
	 var lastScroll = 0;
      $(window).scroll(function(event){
	  if  ($(window).scrollTop() >= 90 ){

          //Sets the current scroll position
          var st = $(this).scrollTop();
          //Determines up-or-down scrolling
          if (st > lastScroll){
             //Replace this with your function call for downward-scrolling
			if (!$('.header').hasClass('act_header')) {
				 $('.wrap_header').stop().animate({
						top: -65                  
						}, 50, 'swing');

			}
          }
          else {
             //Replace this with your function call for upward-scrolling
             $('.wrap_header').stop().animate({
                    top: 0                  
                    }, 50, 'swing');
          }
          //Updates scroll position
          lastScroll = st;
		 	}
      });
	
});