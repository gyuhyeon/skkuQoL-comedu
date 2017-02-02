/**
##
## @Package:    xe_official_planner123
## @File name:	plannerXE123_skin.js
## @Author:     Keysung Chung (keysung2004@gmail.com)
## @Copyright:  © 2009 Keysung Chung(keysung2004@gmail.com)
## @Contributors: Clements J. SONG (http://clements.kyunggi.ca/ , clements_song@hotmail.com)
## @Release:	under GPL-v2 License.
## @License:	http://www.opensource.org/licenses/gpl-2.0.php
##
## Redistribution and use in source and binary forms, with or without modification,
## are permitted provided that the following conditions are met:
##
## Redistributions of source code must retain the above copyright notice, this list of
## conditions and the following disclaimer.
## Redistributions in binary form must reproduce the above copyright notice, this list
## of conditions and the following disclaimer in the documentation and/or other materials
## provided with the distribution.
##
## Neither the name of the author nor the names of contributors may be used to endorse
## or promote products derived from this software without specific prior written permission.
##
## THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" AND ANY
## EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
## MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE
## COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL,
## EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE
## GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED
## AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING
## NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED
## OF THE POSSIBILITY OF SUCH DAMAGE.
##
##  - 2015.11.05 : Ver 4.8.0. - Modify column width & first day of the week.
##  - 2014.06.01 : Ver 4.4.0. - Change Jquery format.
##  - 2011.07.01 : Ver 4.0.0. - Add time table. 
**/
/******************************************************************************/

/* calendar(Monthly) - 월단위 일정 출력 */
function doDisplaySchedule(schedule_html_json,rs_style,ind_mobile) {
jQuery(function($){ 
	var tr_width=0, td_width=0, position=0, sg_height=0, cnt_low=0, cnt_day=0, udt_day=0, test_week=0,
		test_lenght=0,
		outhtml = "",
		low_pos_top = 0,
		row_height = 16,
		brow_adj = 0,
		brow_adj_2 = 0,
		tr_width = $("#planner_week0").width();
	// 요일별 날자 넓이 계산을 위헤 추가
	$(".planner_calendar th").each( function( index ) {
		brow_adj_2 += $(this).width();
	});
	brow_adj = (tr_width - brow_adj_2) / 7; //브라우져 차이 폭 조정
	var th_width_arr = new Array();
	$(".planner_calendar th").each( function( index ) {
		th_width_arr[index] = $(this).width() + brow_adj;
	});

	// var	arrayFromPHP = {$schedule_html_json};  // PHP에서 만든 일정 어레이를 받아서
	var	arrayFromPHP = schedule_html_json;  // PHP에서 만든 일정 어레이를 받아서
  if (arrayFromPHP && tr_width > 0) {		// 처리할 일정이 있으면 아래내용처리
	var cnt_sg = arrayFromPHP.length;		// 일정 갯수를 저장 하고

		// row 계산을위해 3차원 어레이를 만든다.
		row_arr = new Array(14);		// 한달 최대 6주 -> V410 에서 14 주 까지 처리하도록 수정
		for (i=0; i<14 ; i++) {
			row_arr[i] = new Array(cnt_sg);	// 각주마다 예상되는 row 갯수만큼 어레이 만들고
			for (j=0; j<cnt_sg ; j++) {
				row_arr[i][j] = new Array(7);	// 각 row에 요일만큼 어레이 만든다
			}
		}

	$.each(arrayFromPHP, function (i, elem) {	// 각 일정 마다
		var pln_week = Number(elem.week),		// 주 순서
			pln_weekday = Number(elem.weekday),	// 컬럼 순서(요일아님)
			pln_syymmdd = elem.yymmdd,			// 개별일정일 (yyyy-m-d)
			pln_date = Number(elem.date),		// 개별일정일 (dd)
			pln_length = Number(elem.length),	// 개별일정기간
			ind_find = "";

		var pln_syymmdd_arr = pln_syymmdd.split("-");

		// 일정기간 감안 일정이 위치할 포지션 계산.
		for (cnt_low=0; cnt_low<cnt_sg; cnt_low++) {	// 첫 row 부터 
			test_lenght = 0;	// 검사필드 클리어
			for (cnt_day = pln_weekday; cnt_day<(pln_weekday+pln_length); cnt_day++) {  //각 row의 해당 요일부터 일정기간까지 빈간 검사
				if (!row_arr[pln_week][cnt_low][cnt_day]) {		// 해당 row의 해당요일이 비어 있으면 계속 하고
					test_lenght += 1;						// 테스트 필드에 1을 더해놓고
					if (test_lenght >= pln_length) {		// 빈공간이 충분하면
						low_pos_top = row_height * cnt_low;	// 해당줄의 top 위치를 계산 

						// 일정길이(요일별 칸 넓이가 다른경우를 고려 일정길이 계산)
						var pln_width_new = 0;
						for (j=pln_weekday; j<pln_weekday+pln_length; j++)   
						{
							pln_width_new += th_width_arr[j];
						}
						position_week = $("#week_schedule_" + pln_week).position();	// 주별 장기일정 콘테이너 위치 저장
						position = $("#day_schedule_container_" + pln_syymmdd).position();	// 일별 하루일정 콘테이너 위치 저장
						outhtml += "<div id='wc-" + pln_week + "-" + i + "-" + elem.pln_srl + "' class='drag' style='position: absolute; z-index:5; left:" 
							+ (position.left - position_week.left) + "px; top: " +( low_pos_top) + "px; width:" + (pln_width_new-1) + "px;'>" + elem.html +"</div>";  //위치 계산후 코드생성 (V471수정)
						ind_find = "Y";
						test_lenght = 0;

						if (pln_length == 1) {	// 하루일정일 경우 일정 높이구하고
						$('#dummy').width(th_width_arr[pln_weekday]).empty(); // V480 수정d
						$(elem.html).appendTo("#dummy");
						sg_height = ($('#dummy').height()); // '#dummy > div' 를 '#dummy'로 수정 : v460
						// alert (sg_height +" " +$('#dummy').width());
						}

						// 하루일정으로 그림있는경우, 하루일정이면서 높이가 한줄 이상, 기념일, 휴일일경우 (V220: a를 c로 변경)
						if (pln_length == 1 && elem.image || pln_length == 1 && sg_height > row_height || elem.segtype == 'c' || elem.segtype == 'b') {
							if (elem.segtype == 'c' || elem.segtype == 'b')	{	// 기념일, 휴일
								outhtml = "<div>" + elem.html +"</div>";  // 코드생성
							} else {	// 일정
								outhtml = "<div id='dc-" + i + "-" + elem.pln_srl + "' class='drag' >" + elem.html +"</div>";  // 코드 생성 2
							}
							$(outhtml).appendTo('#day_schedule_container_'+ pln_syymmdd);   // 일별 콘테이너 출력
							outhtml = null;
							break;		// 완료되어 for 빠져 나가고
						} else {
							$(outhtml).appendTo('#week_schedule_'+ pln_week);   // 주별로 콘테이너 출력
							outhtml = null;
							 if (rs_style == "N") {  //반복일정표시 (rs_style : Y=제목한번, N=제목여러번, S=일정분리 (N일때만 해당 div있음)
								$(".inside").css({"width":Math.floor(tr_width/7)});
								$(".inside_end").css({"width":Math.floor(tr_width/7)-4});
							 }
							// 어레이에 해당칸을 사용했다는 표시해놓고
							for (udt_day = pln_weekday; udt_day<(pln_weekday + pln_length); udt_day++) {  
								row_arr[pln_week][cnt_low][udt_day] = "*";
							}
							// 장기일정 row 갯수 * row높이가 현재 space div 높이보다 클때 space 높이변경 (일별출력콘테이너 시작위치 조정위해)
							for (cnt_d=0; cnt_d<pln_length; cnt_d++) { 
								//wrk_date = pln_date + cnt_d; //v420
								var tmp_date = new Date(Number(pln_syymmdd_arr[0]), Number(pln_syymmdd_arr[1])-1, Number(pln_syymmdd_arr[2]) + cnt_d);
								var wrk_date = tmp_date.getFullYear() + "-" + Number(tmp_date.getMonth()+1) + "-" + Number(tmp_date.getDate());
								cur_height = $("#day_space_"+wrk_date).height();
								if ((cnt_low + 1)*row_height > cur_height ) {
									$("#day_space_"+wrk_date).height((cnt_low + 1)*row_height + 0);
								}
							}
							break;	// 완료되어 for 빠져 나가고
						}
					}
				}else{	// 해당 row의 해당요일이 비어있지않으면 다음 Row 검사위해 for 빠져나가고
					test_lenght = 0;
					break;
				}
			}
			if (ind_find == "Y") {	// 완료 되었으면 다음일정 처리를 위해 for 빠져 나간다.
				break;
			}
		}
	});
  }  // '처리할 일정이 있으면' 루프끝

  //  div planner123을 visibility:hidden 으로 했을때 대비
  $('#planner123').css("visibility", "visible");	
  //  drag & drop test (drop을 위해서는 아마도 모듈단계에서 extra value update 지원이 필요할듯..)
  if (!ind_mobile) {
	if( typeof $().draggable == 'function' ) { 
  		$('.drag').draggable({ revert: 'invalid', zIndex: 6 });// 각 일정을 draggable로...
	}
  }

  /* mouse over배경색 조정을 위하여... */
  var ind_hover = "";
  var id_cell = "";
  $('.planner_calendar td').hover(
    function () {
		id_cell = this;
		$(".planner_calendar .schedule_view").hover(
			function () {
				$(id_cell).removeClass("hover");
				ind_hover = "N";
			},
			function () {
				ind_hover = "Y";
				$(id_cell).addClass("hover");
			}
		);
		if (ind_hover == "Y" || ind_hover == "") {
			$(this).addClass("hover");
		}
	},
    function () {
		$(this).removeClass("hover");
    }
  ); // end mouse over

}); 
}

/******************************************************************************/
/* calendar(Monthly) - 일정폭 조정 */
function doResizeScheduleWidth(schedule_html_json) {
jQuery(function($){ 
	var	brow_adj = 0,
		brow_adj_2 = 0,
		tr_width = $("#planner_week0").width();
	// 요일별 날자 넓이 계산을 위헤 추가
	$(".planner_calendar th").each( function( index ) {
		brow_adj_2 += $(this).width();
	});
	brow_adj = (tr_width - brow_adj_2) / 7; //브라우져 차이 폭 조정
	var th_width_arr = new Array();
	$(".planner_calendar th").each( function( index ) {
		th_width_arr[index] = $(this).width() + brow_adj;
	});
	// var arrayFromPHP = {$schedule_html_json};  // PHP에서 만든 일정 어레이를 받아서
	var	arrayFromPHP = schedule_html_json;  // PHP에서 만든 일정 어레이를 받아서
	if (arrayFromPHP) {  // 처리할 일정이 있으면 아래내용처리
		$.each(arrayFromPHP, function (i, elem) {	// 각 일정 마다
			var pln_week = Number(elem.week),		// 주 순서
				pln_weekday = Number(elem.weekday),	// 컬럼 순서(요일아님)
				pln_syymmdd = elem.yymmdd,			// 개별일정일 (yyyy-m-d)
				pln_date = Number(elem.date),		// 일
				pln_length = Number(elem.length);	// 일정기간
				//alert( 'length: ' + pln_length + ' / 컬럼순서: ' + pln_weekday );
			if ($('#wc-' + pln_week + '-' + i + '-' + elem.pln_srl).length){
				// var pln_width_new = tr_width * pln_length/7;
				// 일정길이(요일별 칸 넓이가 다른경우를 고려해해 일정길이 게산)
				var pln_width_new = 0;
				for (j=pln_weekday; j<pln_weekday+pln_length; j++)
				{
					pln_width_new += th_width_arr[j];
				}
				position_week = $("#week_schedule_" + pln_week).position();	// 주별 장기일정 콘테이너 위치 저장
				position = $("#day_schedule_container_" + pln_syymmdd).position();	// 일별 하루일정 콘테이너 위치 저장
				$('#wc-' + pln_week + '-' + i + '-' + elem.pln_srl).width(pln_width_new-1);  // 각 장기일정 폭 조정
				$('#wc-' + pln_week + '-' + i + '-' + elem.pln_srl).css({left: (position.left - position_week.left) + "px"});  // 각장기 left 위치 조정
				// alert("day:" + elem.date + " tr:" + tr_width + " td:" + tr_width/7 +" width:" + pln_width_new);	// 검사용
			}
		});
	}
}); 
}

/******************************************************************************/
/* calendar(Monthly) -카테고리 이동 (simple, standard, list, weekly)*/
function doChgCategory(category_srl) {
jQuery(function($){ 
	if (!category_srl) {
    location.href = decodeURI(current_url).setQuery('category','');
	} else {
    location.href = decodeURI(current_url).setQuery('category',category_srl);
	}
}); 
}

/******************************************************************************/
/* calendar(Myplan) - weekly action plan 작성 */
function doUpdateMyplan(module_name, module_srl, document_srl, week_count, weekday, obj) { 
jQuery(function($){ 
	//alert(module_name +"-"+ module_srl +"-"+ document_srl +"-"+ week_count +"-"+ weekday);
	var content = $('#myplan_content').val();
	var title = $('#myplan_title').val();
	var content_arr = new Array();
	content_arr = explode('|=@=|',$('#myplan_content').val());
	var content_arr_week = explode('|@|',content_arr[week_count]);
	var sharpen = str_replace("'","`", $('#sharpen').val());
	var role = str_replace("'","`", $('#role').val());
	var remark = str_replace("'","`", $('#remark').val());
	var task = str_replace("'","`", $('#task').val());
	content_arr_week[7] = sharpen;
	content_arr_week[8] = role;
	content_arr_week[9] = remark;
	content_arr_week[weekday] = task;
	var new_content_week = implode('|@|',content_arr_week);
	content_arr[week_count] = new_content_week;
	var new_content = implode('|=@=|',content_arr);

	var new_doc = new Array();
	new_doc['module_srl'] = module_srl;
	new_doc['document_srl'] = document_srl;
	new_doc['title'] = title;
	new_doc['content'] = new_content;
	new_doc['is_secret'] = "Y";
	new_doc['status'] = "SECRET";
	new_doc['extra_vars'] = "X";	// status code로 사용됨

	var data=$(obj.form).serializeArray(); // stop_spambot을 위헤 기타항목 추가(V480)
	$.each(data, function(i, field){
		var val_temp = $.trim(field.value);
		new_doc[field.name] = val_temp;
	});

    switch (module_name){
        case 'board':	// board 모듈
			exec_xml('board', 'procBoardInsertDocument', new_doc, completeCallModuleAction);
        break;
        case 'bodex':	// BODEX 모듈
			exec_xml('bodex', 'procBoardInsertDocument', new_doc, completeCallModuleAction);
        break;
        default:
			exec_xml('board', 'procBoardInsertDocument', new_doc, completeCallModuleAction);
        break;
    }
}); 
}

/* *************************************************************************** */
/* calendar(Time table) - 주단위 render grid for time table */
function fnMakeTableGrid(dispStart_date, dispEnd_date,ind_mobile,lang_type){
jQuery(function($){ 

	//var contenttable_width = 1149;
	var	grid_table;	// 코드저장 변수
	var disp_area_width = $('#planner123').width();  // 전체 화면 폭
	var	label_width = 130;	// 레이블 폭은 130px로 고정
	var	content_div_width = disp_area_width - label_width;
	var scroll_width = content_div_width -17;  // 스크롤바폭 조정
    if (!ind_mobile) { // 모바일 아닐때일때
	    var contenttable_width = scroll_width * 2 + 2;  // 타임테이블 (1-24시간)의 폭을 보이는 스크롤영역의 2배로...즉 12시간 (모바일일경우 좁을듯...)
	} else {
	    var contenttable_width = scroll_width * 3 + 2;  // 타임테이블 (1-24시간)의 폭을 보이는 스크롤영역의 2배로...즉 8시간 
	}
	if(lang_type == 'ko' || lang_type == 'jp') {
		var weekday_label=new Array("日","月","火","水","木","金","土"); 
	} else if(lang_type == 'zh-TW' || lang_type == 'zh-CN') {
		var weekday_label=new Array("日","一","二","三","四","五","六"); 
	} else {
		var weekday_label=new Array("Sun","Mon","Tue","Wed","Thu","Fri","Sat"); 
	}
	var arr_s = dispStart_date.split(",")
	var arr_e = dispEnd_date.split(",")
	var d_s = new Date(arr_s[0],arr_s[1]-1,arr_s[2],arr_s[3],arr_s[4],arr_s[5]);// 기간-시작
	var d_e = new Date(arr_e[0],arr_e[1]-1,arr_e[2],arr_e[3],arr_e[4],arr_e[5]);// 기간-끝
	var start = d_s.getTime()+3600000*1;// 기간-시작 stamp: DST 끝나는날 고려 1시간 뒤로
	var end = d_e.getTime();// 기간-끝 time stamp
	var period = Math.round((end-start)/86400000);// 기간 날수
	if ((end-start)/86400000 > period)
	{
		period += 1;
	}
	var today_date = new Date();
	var today_yy = today_date.getFullYear();
	var today_mm = today_date.getMonth()+1;
	var today_dd = today_date.getDate();
	var today_hh = today_date.getHours(); // 0-23
	var tmp_date = new Date();

	grid_table += "<table class='Timetable_table' id='Timetable_table' cellspacing='0' cellpadding='0' border='0'>";
	// table header (1-24시간 헤더)
	grid_table += "<tr><td class='header_empty_cell' id='header_empty_cell' border='1'>&nbsp;</td>";
	grid_table += "<td><div class='header_div' id='header_div' style='overflow:hidden; width:"+scroll_width+"px;'>";
	grid_table += "<table class='header_table' id='header_table'  width='"+contenttable_width+"px' cellspacing='0' cellpadding='0' border='1' align='center'>";
	grid_table += "<tr class='header_table_tr' id='header_table_tr' align='center'>";
	for (i=0, j=0; i<12; i++, j++) {
		grid_table += "<th class='header_cell' id='header_c"+j+"'>"+i+" am</th>";
	}
	for (i=0, j=12; i<12; i++, j++) {
		grid_table += "<th class='header_cell' id='header_c"+j+"'>"+i+" pm</th>";
	}
	grid_table += "</tr></table></div></td></tr>";
	// table body (좌측 레이블및 타임테이블)
	grid_table += "<tr>";
	// body-label (좌측 레이블)
	grid_table += "<td valign='top'><div class='label_div' id='label_div' style='overflow:hidden;'>";
	grid_table += "<table class='label_table' id='label_table' width='"+label_width+"px' cellspacing='0' cellpadding='0' border='1'>";
	for (i=0; i<period; i++) {  // 요일 (월-일)
		var tmp_stamp = start + i*86400000;
		tmp_date.setTime(tmp_stamp);  // JS 에서 사용을 위해 함수에서 전달받은 초단위를 밀리세컨드 단위로 조정
		var yy = tmp_date.getFullYear(); // year
		var mm = tmp_date.getMonth()+1; // month (1-12)
		var dd = tmp_date.getDate(); // date (1-31)
		var wd = tmp_date.getDay(); // weekday (0=Sun... 6=Sat)
		grid_table += "<tr valign='top'>";
		if (yy==today_yy && mm==today_mm && dd==today_dd) {  // today
			grid_table += "<td class='label_td today_bg_color today_label_border' id='label_td_"+mm+"-"+dd+"'>";  // 당일을 배경색, 보더로 강조 위해
		} else {
			grid_table += "<td class='label_td' id='label_td_"+mm+"-"+dd+"'>";
		}

		if(wd == 0) {
			grid_table += "<div class='tt_date_label holiday' id='label_date_"+mm+"-"+dd+"'>"+weekday_label[wd]+" ("+mm+"-"+dd+")</div>";		// 일요일
		}else if (wd == 6) {
			grid_table += "<div class='tt_date_label saturday' id='label_date_"+mm+"-"+dd+"'>"+weekday_label[wd]+" ("+mm+"-"+dd+")</div>";	// 토요일
		}else {
			grid_table += "<div class='tt_date_label weekday' id='label_date_"+mm+"-"+dd+"'>"+weekday_label[wd]+" ("+mm+"-"+dd+")</div>";	// 평일
		}
		//grid_table += "<div class='label underline right'><div>schedule title</div></div>"
		grid_table += "</td>";
		grid_table += "</tr>";
	}
	grid_table += "</table></div></td>";
	// body-content (타임테이블)
	grid_table += "<td valign='top'>";
	grid_table += "<div class='contenttable_div' id='contenttable_div' style='overflow:scroll; width:"+content_div_width+"px; position:relative' onscroll='fnScroll()'>";
	// body-content-background (세로줄: 1-24 시간간격 표시)
	grid_table += "<div style='position:absolute; top:0px; left:0px;'>";
	grid_table += "<table class='contenttable_bg' id='contenttable_bg' width='"+contenttable_width+"px' cellspacing='0' cellpadding='0' border='1'>";
	grid_table += "<tr valign='top'>";
	for (i=0; i<24; i++) {
		if (i==today_hh) {
		grid_table += "<td class='now_bg_color' id='bg_c"+i+"'>&nbsp;</td>";
		} else {
		grid_table += "<td id='bg_c"+i+"'>&nbsp;</td>";
		}
	}
	grid_table += "</tr></table></div>";
	// body-content-container (일정이 기록될 테이블 : 일별로 1줄씩)
	grid_table += "<div style='position:absolute; top:0px; left:0px;'>";
	grid_table += "<table class='contenttable_table' id='contenttable_table' width='"+contenttable_width+"px' cellspacing='0' cellpadding='0' border='1'>";
	for (i=0; i<period; i++) {  // 빈줄
		var tmp_stamp = start + i*86400000;
		tmp_date.setTime(tmp_stamp);
		var yy = tmp_date.getFullYear(); // year
		var mm = tmp_date.getMonth()+1; // month (1-12)
		var dd = tmp_date.getDate(); // date (1-31)
		var wd = tmp_date.getDay(); // weekday (0=Sun... 6=Sat)
		grid_table += "<tr valign='top'>";
		if (yy==today_yy && mm==today_mm && dd==today_dd) {  // today
			grid_table += "<td class='content_td today_content_border' id='content_td_"+mm+"-"+dd+"'>";
		} else {
			grid_table += "<td class='content_td' id='content_td_"+mm+"-"+dd+"'>";
		}
		grid_table += "<div>&nbsp;</div>";	// 좌측 레이블 날자에 해당하는곳에 빈줄 출력
		//grid_table += "<div class='underline'><div><===></div></div>";
		grid_table += "</td>";
		grid_table += "</tr>";
	}
	grid_table += "</table></div>";
	grid_table += "</div></td></tr></table>";
	
	// output
	$(grid_table).appendTo('#Timetable_div');
}); 
}

/* *************************************************************************** */
/* calendar(Time table) - 주단위 render schedule in the time table */
function fnMakeWeeklySchedule(schedule_html_json, dispStart_stamp, dispEnd_stamp){
jQuery(function($){ 

	var	arrayFromPHP = schedule_html_json;  // PHP에서 만든 일정 어레이
	var start = Number(dispStart_stamp);
	var end = Number(dispEnd_stamp);
	//var tmp_date = new Date();
  if (arrayFromPHP)	// 자료가 있으면 실행
  {
	$.each(arrayFromPHP, function (i, elem) {	// 각 일정 마다
		var pln_week = Number(elem.week),		// 주 순서
			pln_weekday = Number(elem.weekday),	// 컬럼 순서(요일아님)
			pln_month = Number(elem.month),		// 일정 월
			pln_date = Number(elem.date),			// 일정 일
			pln_length = Number(elem.length),		// 일정 기간
			pln_segtype = elem.segtype,	// 일정 타입(a=일정, b=휴일, c=기념일)
			pln_stime = elem.pln_stime,	// 일정 시작시간
			pln_etime = elem.pln_etime;	// 일정 종료시간

		if (pln_segtype == 'a') {	//일정
		  for (j=pln_date; j<(pln_date+pln_length); j++)   // 연속일정 처리를 위해(월이 바뀌면 새 일정으로 처리되니 월을 감안할 필요는 없음 )
		  {
			tmp_id = "#label_td_"+pln_month+"-"+j;  // label container
			tmp_fld = "<div class='label underline right'>"+elem.html+"</div>";  //label
			$(tmp_fld).appendTo(tmp_id); // label output
			$(tmp_id+' img').remove();	// img 태그제거
			tmp_fld = null;

			// 시작종료 시각 구하기 (분단위 제외)
			if (pln_stime){ 
				if (pln_stime.substr(0,2)<10){ stime= pln_stime.substr(1,1);} else { stime= pln_stime.substr(0,2);}  // 시작시각(시 단위)
			} else {
				stime = 0; etime = 23;
			}
			if (pln_etime){ 
				if (pln_etime.substr(0,2)<10){ etime= pln_etime.substr(1,1);} else { etime= pln_etime.substr(0,2);}  // 종료시각(시 단위)
				// 종료시각이 정각인경우는 그 앞 시간대 까지를 기간으로 계산(예: 08:00-10:00 은 08:00-09:59 즉 8시- 9시 시간대 까지임.)
				if (pln_etime.substr(3,2) == '00') {  
					etime -= 1;
					if (etime<stime){ etime = stime;}
				}
			}
			//alert(stime+"-"+etime);

			tmp_id = "#bg_c"+stime;		// 시작컬럼 ID
			s_position = $(tmp_id).position();
			s_position_left = s_position.left;  // 시작 위치.
			tmp_id = "#bg_c"+etime;		// 종료컬럼 ID
			e_position = $(tmp_id).position();
			e_position_right = e_position.left + $(tmp_id).width();   // 끝 위치
			tmp_width = e_position_right - s_position_left +1;	// 타임테이블에 표시될 일정 폭(시간)
			// content output
			tmp_id = "#content_td_"+pln_month+"-"+j;	// content row ID
			tmp_fld = "<div class='underline of_hidden'>";
			tmp_fld += "<div style='position:relative; left:"+s_position_left+"px; width:"+tmp_width+"px;'>"+elem.html+"</div>";  // build element
			tmp_fld += "</div>";
			$(tmp_fld).appendTo(tmp_id); //  output content elemwnt
			$(tmp_id+' img').remove();	// remove img tag
		  }	// end for
		} 
		else if (pln_segtype == 'b') {	//휴일
			tmp_id = "#label_date_"+pln_month+"-"+pln_date;
			$(tmp_id).removeClass('saturday weekday');
			$(tmp_id).addClass('holiday');	// red color
			var holiday_str = " " + elem.html.replace(/(<([^>]+)>)/ig,""); //태그제거
			$(tmp_id).append(holiday_str);	//휴일명(v500)
		}
	});
	$('#Timetable_div .schedule_view').css('border','');
  }

}); 
}

/* *************************************************************************** */
/* calendar(Time table) - 주단위 Adjust header size of time table  */
function fnAdjTimeTable(){
jQuery(function($){ 

	var colCount=$('#header_table_tr>th').length; //get total number of column
	var m=0;
	var n=0;
	var brow='mozilla';

	$.each($.browser, function(i, val) {
		if(val==true){
		brow=i.toString();
		}
	});

	$('.header_cell').each(function(i){  // 시간(1-24) 헤더 폭 조정
	if(m<colCount){
		if(brow=='mozilla'){  //mozilla_Firefox 
			$('#header_empty_cell').css("width",$('.lable_td').innerWidth()); //for adjusting empty cell
			$(this).css('width',$('#contenttable_bg td:eq('+m+')').innerWidth()-1);//for assigning width to table Header div
		}
		else if(brow=='msie'){  //MSIE
			$('#header_empty_cell').css("width",$('.lable_td').innerWidth()-1);
			$(this).css('width',$('#contenttable_bg td:eq('+m+')').innerWidth()-1);//In IE there is difference of 2 px
		}
		else if(brow=='safari'){  //Google_Crom & Safari
			$('#header_empty_cell').css("width",$('.lable_td').width()+1);
			$(this).css('width',$('#contenttable_bg td:eq('+m+')').width()+1);;//In Crom there is difference of 1 px
		}
		else{
			$('#header_empty_cell').css("width",$('.lable_td').innerWidth());
			$(this).css('width',$('#contenttable_bg td:eq('+m+')').width());
		}
	}
	m++;
	});
/*
	$('.label_td').each(function(i){	//좌측 레이블(날자) 높이조정 -> 그냥 CSS로 지정하는것으로 재수정함
	if(brow=='mozilla'){
		$(this).css('height',$('#contenttable_table td:eq('+n+')').outerHeight());//for providing height using scrollable table column height
	}else if(brow=='msie'){
		$(this).css('height',$('#contenttable_table td:eq('+n+')').innerHeight()+1);
	}else if(brow=='safari'){
		$(this).css('height',$('#contenttable_table td:eq('+n+')').height()+1);
	}else{
		$(this).css('height',$('#contenttable_table td:eq('+n+')').height());
	}
	n++;
	});
*/
	var scroll_height = 300;  // scroll bar height  (스크롤 영역 놀이를 300px로...)
	$('#contenttable_bg').css('height',$('#contenttable_table').height()); //for providing height of vertical line
	if($('#contenttable_table').height() <= scroll_height){
	    $('#contenttable_div').css('height',$('#contenttable_table').height()+19); //for providing height of content table
	    $('#label_div').css('height',$('#contenttable_table').height()+3); //for providing height of label
	} else {
	    $('#contenttable_div').css('height',scroll_height+19); //for providing height of content table
	    $('#label_div').css('height',scroll_height+2); //for providing height of label
	}

	// 화면에 보일 타임 테이블 시작 위치 조정 (하루전, 1시간전 부터...)
	// column
	var today_date = new Date();  // today
	var today_hh = today_date.getHours(); // 0-23
	if ((today_hh - 1) <= 0  ) { 
		tmp_colposition = $("#bg_c"+0).position();// 보이기 시작할 컬럼 ID (2시 이전은 0시부터)
		$('#contenttable_div').scrollLeft(tmp_colposition.left);
	} else {
		tmp_colposition = $("#bg_c"+ (today_hh-1) ).position();// 1시간 전부터
		$('#contenttable_div').scrollLeft(tmp_colposition.left);
	}
	// row
	var tmp_date = new Date(); // previous day
	tmp_date.setTime(today_date.getTime() - (86400*1000)*1); // 하루전 부터
	//var tmp_date_yy = tmp_date.getFullYear();
	var tmp_date_mm = tmp_date.getMonth()+1;
	var tmp_date_dd = tmp_date.getDate();
	if ($("#content_td_"+tmp_date_mm+"-"+tmp_date_dd).length > 0 ) { 
		tmp_rowposition = $("#content_td_"+tmp_date_mm+"-"+tmp_date_dd).position();
		$('#contenttable_div').scrollTop(tmp_rowposition.top-1);	//(당일 보더로 인해 IE, FF에서 레이블 컬럼 맨윗줄 안보이는것 보이도록 -1 조정)
	}

}); 
}

/* *************************************************************************** */
/* calendar(Time table) - 주단위 function to support scrolling of title and first column */
function fnScroll(){
	jQuery(function($){ 
		$('#header_div').scrollLeft($('#contenttable_div').scrollLeft());	//좌우 스크롤시 헤더(1-24시) 위치 조정
		$('#label_div').scrollTop($('#contenttable_div').scrollTop());	//상하 스크롤시 좌측 레이블 위치 조정
	}); 
}

/* *************************************************************************** */
