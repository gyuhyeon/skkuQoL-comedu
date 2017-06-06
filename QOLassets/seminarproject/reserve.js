
//color settings
var selectedColor = "orange";
var occupiedColor = "gray";
var freeColor = "white";
var generalColor = "pink";
var nightColor = "lightcoral";

var data;

//table color update
function updateTableColor() {
	var d = document.getElementsByName("day")[0];
	var st = document.getElementsByName("start_time")[0];
	var et = document.getElementsByName("end_time")[0];

	var td = document.getElementById('schedule').getElementsByTagName('td');

	//color everything as white first
	for (var i = 0; i < td.length; ++i) {
		if (td[i].textContent == "") {
			td[i].style.backgroundColor = freeColor;
		}
	}

	// coloring 일반 users
	for ( i = 0; i < data.length; ++i ){
		if ( true ){
			for(var j=data[i].starttime; j<=data[i].endtime; ++j){
				//이부분은 수정 필요. 공휴일일 때는 18부터가 아니라 그냥 무조건 표기해야 됨. 진한 분홍(lightcoral)이 조교님께 철야신청임을 보여줌.
				if(j>=18){
					for(var k=18; k<=22;++k){
						$("#"+data[i].reservedate.slice(-5)+" > td[name="+k+"]")[0].style.backgroundColor=nightColor;
					}
					break; //logic : if j is ever over 18, paint everything from there til the end.
				}
				else{
					$("#"+data[i].reservedate.slice(-5)+" > td[name="+j+"]")[0].style.backgroundColor=generalColor;
				}
			}
		}
	}
	//change background for cells with reservation
	for (var i = 0; i < td.length; ++i) {
		if (td[i].textContent != "") {
			td[i].style.backgroundColor = occupiedColor;
		}
	}

	//change background for selected cells
	if (d.value != "선택") {
		if (st.value != "선택") {
			$("[name='" + d.selectedOptions[0].className + "'] > [name='" + st.value.slice(0, 2) + "']")[0].style.backgroundColor = selectedColor;
		}
		if (st.value != "선택" && et.value != "선택") {
			for (var i = parseInt(st.value.slice(0, 2)); i <= parseInt(et.value.slice(0, 2)); ++i) {
				$("[name='" + d.selectedOptions[0].className + "'] > [name='" + i + "']")[0].style.backgroundColor = selectedColor;
			}
		}
	}
}

//when loading for the first time, update table color(although this should be called again after loading info by POST to loadstatus.php)
//when loading for the first time, show default page(세미나실) by calling appropriate functions that request to loadstatus.php
window.onload = function() {

	updateTableColor();
}

function clickToReserve(day, time) {
	//don't have to use jquery all the time especially for names
	var d = document.getElementsByName("day")[0];
	var st = document.getElementsByName("start_time")[0];
	var et = document.getElementsByName("end_time")[0];


	//refuse if the time is unavailable
	if ($("[name='" + day + "'] > [name='" + time + "']")[0].innerText != "") {
		alert("예약할 수 없는 시간입니다.");
	}
	// case when the click needs to register as start time
	// conditions:
	// A. There were no selections at all
	// B. Selection date has changed
	// C. start_time is bigger than time(parameter passed to current function) to register
	// D. end_time is already set(improve user experience by resetting)
	else if (d.value == "선택" || st.value == "선택" || day != d.selectedOptions[0].className || parseInt(st.value.slice(0, 2)) > time || et.value != "선택") {
		$("[name='day'] > ." + day)[0].selected=true; // set date(요일)
		st.value = time + ":00"; // set time(start time)
		et.value = "선택"; // reset endtime selection for the user
	}
	// case when click needs to register as end time (end)
	else {
		et.value = time + ":00";
	}
	validateSelection();
	updateTableColor();
}

function validateSelection() {
	var d = document.getElementsByName("day")[0];
	var st = document.getElementsByName("start_time")[0];
	var et = document.getElementsByName("end_time")[0];

	//endtime is earlier than starttime
	if (st.value != "선택" && et.value != "선택" && parseInt(st.value.slice(0, 2)) > parseInt(et.value.slice(0, 2))) {
		et.value = "선택";
		alert("시작시간 이후의 종료시간을 선택해주세요 :(");
	}
	//day is set, and starttime is occupied
	else if (d.value != "선택" && st.value != "선택" && parseInt(st.value.slice(0, 2)) && $("[name='" + d.selectedOptions[0].className + "'] > [name='" + st.value.slice(0, 2) + "']")[0].innerText != "") {
		st.value = "선택";
		alert("해당 시작시간은 이미 예약되어 있습니다 :(");
	}
	//day is set, and endtime is occupied
	else if (d.value != "선택" && et.value != "선택" && parseInt(st.value.slice(0, 2)) && $("[name='" + d.selectedOptions[0].className + "'] > [name='" + et.value.slice(0, 2) + "']")[0].innerText != "") {
		et.value = "선택";
		alert("해당 시작시간은 이미 예약되어 있습니다 :(");
	}
	//day is set, both starttime and endtime is specified, which means we can safely loop within it. check if occupied.
	else if (d.value != "선택" && st.value != "선택" && et.value != "선택") {
		for (var i = parseInt(st.value.slice(0, 2)); i <= parseInt(et.value.slice(0, 2)); ++i) {
			if ($("[name='" + d.selectedOptions[0].className + "'] > [name='" + i + "']")[0].innerText != "") {
				et.value = "선택";
				alert("중간에 예약되어 있는 시간이 있습니다 :(");
			}
		}
	}

	var checkdate = new Date();
	var formattedDate = checkdate.getFullYear()+"-"+("0"+(checkdate.getMonth()+1)).slice(-2)+"-"+("0"+checkdate.getDate()).slice(-2);

	if (d.value == formattedDate){
		alert("당일 예약은 불가합니다!");
		d.value = "선택";		
	}
	updateTableColor();
}

//when form is resetted, call validate selection which cleans potential issues and repaints cells.
$("#reserveform").on('reset', function(e) {
	setTimeout(function() {
		validateSelection();
	});
});

//submit reservation form
//$("#reserveform").ajaxForm({url: "reserve.php", type: "POST"}); //when submit button is pressed

//education purpose note : alternatives
//$("#reserveform").ajaxSubmit({url: "reserve.php", type: "POST"}); this submits instantly without submit button pressing
//OR $.post('reserve.php', $('#reserveform').serialize());
//OR $.get('reserve.php?' + $('#reserveform').serialize());

$(document).ready(function() {

    // process the form
    $('#reserveform').submit(function(event) {

        // get the form data
        // there are many ways to get this data using jQuery (you can use the class or id also)
        var formData = {
            'purpose'            : $('select[name="purpose"]').val(),
            'day'                : $('select[name="day"]').val(),
            'start_time'         : parseInt($('select[name="start_time"]')[0].value.slice(0,2)),
            'end_time'           : parseInt($('select[name="end_time"]')[0].value.slice(0,2)),
            'groupsize'          : $('select[name="groupsize"]').val(),
			'reservename'        : $('input[name="reservename"]').val(),
			'password'           : $('input[name="password"]').val(),
			'g-recaptcha-response': grecaptcha.getResponse()
        };

        // process the form
        $.ajax({
            type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url         : 'reserve.php', // the url where we want to POST
            data        : formData, // our data object
            dataType    : 'json', // what type of data do we expect back from the server
                        encode          : true
        })
            // using the done promise callback
            .done(function(data) {

                // log data to the console so we can see
                //console.log(data); 

				//show as alert
				alert(data['response']);
				//force client reload on success
				if(data['response'].length<10){
					location.reload();
				}
                // here we will handle errors and validation messages
            });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });

	//placeholder
	placeholdergenerator();
	updateTableData();
	updateTableColor();
});


function getTableData(){
	var currentDate = new Date();
	var formattedDate = currentDate.getFullYear()+"-"+("0"+(currentDate.getMonth()+1)).slice(-2)+"-"+("0"+currentDate.getDate()).slice(-2);

	var formData = {
		'currentdate'      : formattedDate
	}
	var tableData;
		// process the form
    $.ajax({
        type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url         : 'loadstatus.php', // the url where we want to POST
        data        : formData,
        dataType    : 'json', // what type of data do we expect back from the server
                        encode          : true,
		async       : false // set async as false so we can actually return the data to getTableData()
    })
            // using the done promise callback
        .done(function(data) {
            // return data; return data doesn't work; ajax creates another thread, while getTableData() exits normally.
			// however, workaround can be done by setting async false.
			tableData = data;
            // here we will handle errors and validation messages
        });
	return tableData;
}

function updateTableData(){

	data = getTableData();

	//purpose가 0이 아니면 이름 출력
	for(var i =0; i<data.length ; i++)
	{
		if(data[i].purpose != 0)
		{
			for(var j=data[i].starttime; j<=data[i].endtime; j++)
			{
				$("#"+data[i].reservedate.slice(-5)+" > td[name="+j+"]")[0].innerText = data[i].studentname;
			}
		}
		else
		{
			for(var j=data[i].starttime; j<=data[i].endtime; j++)
			{
				//purpose가 0, 즉 개인 목적이면 mouseovertext인 title로만 표기.
				$("#"+data[i].reservedate.slice(-5)+" > td[name="+j+"]")[0].title += data[i].studentname+" ";
			}


		}
	}

}

//temporary function to test without fetching date info from server
function placeholdergenerator(){
	var day = new Date();
	var formattedDate = day.getFullYear()+"-"+("0"+(day.getMonth()+1)).slice(-2)+"-"+("0"+day.getDate()).slice(-2);
	for(var i=1; i<=7; ++i){
		//표에 보여지는 날짜 포맷만 예쁘게 바꾸는 것 예시
		//innerText는 사용자에게 보여지는 것인데, 실제로 위에서 json으로 보내는 부분에서 .val()을 할 때 value가 미설정일 경우 innerText값을 사용해버림
		//현재 DB에는 0000-00-00 형태로 보내지며, 해당 형태에서 벗어날 경우 형식 미준수로 에러 발생
		//만약 사용자에게 보여지는 것만 00-00 으로 해주고, 실제 보내는 것은 그대로 0000-00-00로 유지하려면, value와 innerText를 구분해줘야 함
		//하단 코드 참고
		$("select[name='day'] > .D"+i)[0].innerText = formattedDate.slice(-5);
		$("th.D"+i)[0].innerText = formattedDate.slice(-5);
		
		$("tr[name=D"+i+"]")[0].id = formattedDate.slice(-5);	//tr id 만들기

		//value는 그대로 유지!
		$("select[name='day'] > .D"+i)[0].value = formattedDate;
		$("th.D"+i)[0].value = formattedDate;

		//하루 증가시킴(이건 placeholder generator라 있는 부분)
		day.setDate(day.getDate()+1);
		//format 준수. 날짜 포맷.
		formattedDate = day.getFullYear()+"-"+("0"+(day.getMonth()+1)).slice(-2)+"-"+("0"+day.getDate()).slice(-2);
	}

}