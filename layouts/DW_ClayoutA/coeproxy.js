document.body.onload = function() {
    var result;
    noConflictQuery.ajax({
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
    var rh=noConflictQuery('<div></div>');
    rh.html(result);
    noConflictQuery('section.section4 > div.in_section3 div.in_office3 > div.wrap_widgetA > h2 > span')[0].innerText = "사범대학 공지사항";
    noConflictQuery('section.section4 > div.in_section3 div.in_office3 > div.wrap_widgetA > h2 > a')[0].href="http://coe.skku.edu/coe/menu_6/data_01.jsp";
    noConflictQuery('section.section4 > div.in_section3 div.in_office3 > div.wrap_widgetA > div.DW_StA_normal > div > div > div > ul.widgetA')[0].innerHTML = '<li class="widgetA_li0 bg2"><span class="date"></span><a href="" class="title"></a></li><li class="widgetA_li1 bg1"><span class="date"></span><a href="" class="title"></a></li><li class="widgetA_li2 bg2"><span class="date"></span><a href="" class="title"></a></li><li class="widgetA_li3 bg1"><span class="date"></span><a href="" class="title"></a></li><li class="widgetA_li4 bg2"><span class="date"></span><a href="" class="title"></a></li>'; //리스트 초기화
    for(var i=0; i<5; ++i){
        var parent='section.section4 > div.in_section3 div.in_office3 > div.wrap_widgetA > div.DW_StA_normal > div > div > div > ul.widgetA > ';
         noConflictQuery(parent+'li.widgetA_li'+i+' > span')[0].innerHTML = noConflictQuery('table > tbody > td', rh)[i*6+3].innerHTML.slice(-5);
         noConflictQuery(parent+'li.widgetA_li'+i+' > a')[0].href = "http://coe.skku.edu/coe/menu_6/data_01.jsp"+noConflictQuery('table > tbody > td.title > a', rh)[i].href;
         noConflictQuery(parent+'li.widgetA_li'+i+' > a')[0].innerHTML = noConflictQuery('table > tbody > td.title > a', rh)[i].innerHTML;
    }
    
}