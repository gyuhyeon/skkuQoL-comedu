document.onload = function() {
    var result;
    jQuery.ajax({
            type        : 'GET', // define the type of HTTP verb we want to use
            url         : '/QOLassets/proxy.php', // the url where we want to GET
            async       : false
        })
            // using the done promise callback
            .done(function(data) {

                // log data to the console so we can see
                //console.log(data); 
                result = data;
				
                // here we will handle errors and validation messages
            });
    //do things with ajax result here
    var rh=jQuery('<div></div>');
    rh.html(result);
    jQuery('section.section4 > div.in_section3 div.in_office3 > div.wrap_widgetA > h2 > span')[0].innerText = "사범대학 공지사항";
    jQuery('section.section4 > div.in_section3 div.in_office3 > div.wrap_widgetA > h2 > a')[0].href="http://coe.skku.edu/coe/menu_6/data_01.jsp";
    jQuery('section.section4 > div.in_section3 div.in_office3 > div.wrap_widgetA > div.DW_StA_normal > div > div > div > ul.widgetA')[0].innerHTML = '<li class="widgetA_li0 bg2"><span class="date"></span><a href="" class="title"></a></li><li class="widgetA_li1 bg1"><span class="date"></span><a href="" class="title"></a></li><li class="widgetA_li2 bg2"><span class="date"></span><a href="" class="title"></a></li><li class="widgetA_li3 bg1"><span class="date"></span><a href="" class="title"></a></li><li class="widgetA_li4 bg2"><span class="date"></span><a href="" class="title"></a></li>'; //리스트 초기화
    for(var i=0; i<5; ++i){
        var parent='section.section4 > div.in_section3 div.in_office3 > div.wrap_widgetA > div.DW_StA_normal > div > div > div > ul.widgetA > ';
         jQuery(parent+'li.widgetA_li'+i+' > span')[0].innerHTML = jQuery('table > tbody > td')[i*6+3].innerHTML.slice(-5);
         jQuery(parent+'li.widgetA_li'+i+' > a')[0].href = "http://coe.skku.edu/coe/menu_6/data_01.jsp"+jQuery('table > tbody > td.title > a')[i].href;
         jQuery(parent+'li.widgetA_li'+i+' > a')[0].innerHTML = jQuery('table > tbody > td.title > a')[i].innerHTML;
    }
    
}