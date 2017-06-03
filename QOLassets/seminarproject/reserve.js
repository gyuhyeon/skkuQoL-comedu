
//color settings
var selectedColor = "orange";
var occupiedColor = "gray";
var freeColor = "white";

//table color update
function updateTableColor() {
	var d = document.getElementsByName("day")[0];
	var st = document.getElementsByName("start_time")[0];
	var et = document.getElementsByName("end_time")[0];

	//change background for cells with reservation
	var td = document.getElementById('schedule').getElementsByTagName('td');
	for (var i = 0; i < td.length; ++i) {
		if (td[i].textContent != "") {
			td[i].style.backgroundColor = occupiedColor;
		} else {
			td[i].style.backgroundColor = freeColor;
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
		alert("예약할 수 없는 시간입니다 :(");
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
			'password'           : $('input[name="password"]').val()
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
				if(data['response'].length()<10){
					location.reload();
				}
                // here we will handle errors and validation messages
            });

        // stop the form from submitting the normal way and refreshing the page
        event.preventDefault();
    });

	//placeholder
	placeholdergenerator();
});

function getTableData(){
	var currentDate = new Date();
	var formattedDate = currentDate.getFullYear()+"-"+("0"+(currentDate.getMonth()+1)).slice(-2)+"-"+("0"+currentDate.getDate()).slice(-2);

	var formData = {
		'currentdate'      : formattedDate
	}

		// process the form
    $.ajax({
        type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
        url         : 'loadstatus.php', // the url where we want to POST
        data        : formData,
        dataType    : 'json', // what type of data do we expect back from the server
                        encode          : true
    })
            // using the done promise callback
        .done(function(data) {
            return data;

            // here we will handle errors and validation messages
        });
}

function updateTableData(){
	jsondata = getTableData();

}

//temporary function to test without fetching date info from server
function placeholdergenerator(){
	var day = new Date();
	var formattedDate = day.getFullYear()+"-"+("0"+(day.getMonth()+1)).slice(-2)+"-"+("0"+day.getDate()).slice(-2);
	for(var i=1; i<=7; ++i){
		$("select[name='day'] > .D"+i)[0].innerText = formattedDate;
		day.setDate(day.getDate()+1);
		formattedDate = day.getFullYear()+"-"+("0"+(day.getMonth()+1)).slice(-2)+"-"+("0"+day.getDate()).slice(-2);
	}
}