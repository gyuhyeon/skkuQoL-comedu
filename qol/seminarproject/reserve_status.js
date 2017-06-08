
//color settings
var selectedColor = "orange";
var occupiedColor = "gray";
var freeColor = "white";
var generalColor = "pink";
var nightColor = "lightcoral";

var data;


//on load
window.onload = function() {
	//crappy security
	var answer=prompt("관리자만 열람할 수 있습니다. 비밀번호 :");
	if(answer=="comedu"){
		var currentDate = new Date();
		var formattedDate = currentDate.getFullYear()+"-"+("0"+(currentDate.getMonth()+1)).slice(-2)+"-"+("0"+currentDate.getDate()).slice(-2);
		$('#date')[0].innerHTML=formattedDate;
		(function(){updateTableData();})();
	}
	else{
		window.close();
	}
	//populate table
	//question : will this prevent sync ajax raising alerts because it's on main thread?
	
	
}

function getCustomDate(){
	var cD = new Date();
	var fD = cD.getFullYear()+"-"+("0"+(cD.getMonth()+1)).slice(-2)+"-"+("0"+cD.getDate()).slice(-2);
	var date=prompt("조회할 일자를 1993-06-07 형식으로 입력해주세요(yyyy-mm-dd): ", fD);
	var formData = {
		'currentdate'      : date
	}
	var data;
	// process the form
	$.ajax({
		type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
		url         : 'reserve_status.php', // the url where we want to POST
		data        : formData,
		dataType    : 'json', // what type of data do we expect back from the server
		encode          : true,
		async       : false  // set async as true, we will use callback to update.
	})
	// using the done promise callback
	.done(function(response) {
		data=response;
		// here we will handle errors and validation messages
	});
	
	if(data.length==0){
		//if there's nothing, show that there's nothing.
		$('#date')[0].innerHTML=date;
		$('table.status')[0].innerHTML="<tr><th>시간</th><th>성명</th><th>학번</th><th>목적</th><th>장소</th><th>서명</th></tr>";
		$('table.status')[0].innerHTML+="<tr><td>신청자 없음</td><td></td><td></td><td></td><td></td><td></td></tr>"
	}
	else if(data[0].length==1){
		if(data[0].response=="날짜형식오류"){
			alert(data[0].response);
		}
		else{
			//data[0].response does exist but it's not the error message? no case exists for this right now.
			//better design in the future would be to always include response success somehow.
		}
	}
	else{
		$('#date')[0].innerHTML=date;
		$('table.status')[0].innerHTML="<tr><th>시간</th><th>성명</th><th>학번</th><th>목적</th><th>장소</th><th>서명</th></tr>";
		$('table.status')[0].innerHTML+="<tr>";
		for(var i=0; i<data.length; ++i){
			var td = "";
			var purpose;
			switch(parseInt(data[i].purpose)){
				case 0:
				purpose="일반 사용";
				break;
				case 1:
				purpose="학생회 사용";
				break;
				case 2:
				purpose="집행부 사용";
				break;
				case 3:
				purpose="소모임 사용";
				break;
				case 4:
				purpose="팀프로젝트 사용";
				break;
			}
			td += ("<td>"+data[i].starttime+":00 ~ "+(parseInt(data[i].endtime)+1)+":00"+"</td>");
			td += ("<td>"+data[i].studentname+"</td>");
			td += ("<td>"+data[i].password+"</td>");
			td += ("<td>"+purpose+"</td>");
			td += ("<td>세미나실</td>");
			td += ("<td></td>");
		}
		$('table.status')[0].innerHTML+=td+"</tr>";
	}
}

function getTableData(){
	//send today's date
	var currentDate = new Date();
	var formattedDate = currentDate.getFullYear()+"-"+("0"+(currentDate.getMonth()+1)).slice(-2)+"-"+("0"+currentDate.getDate()).slice(-2);
	
	var formData = {
		'currentdate'      : formattedDate
	}
	var tableData;
	// process the form
	$.ajax({
		type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
		url         : 'reserve_status.php', // the url where we want to POST
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
	
	if(data.length==0){
		//if there's nothing, show that there's nothing.
		$('table.status')[0].innerHTML+="<tr><td>신청자 없음</td><td></td><td></td><td></td><td></td><td></td></tr>"
	}
	else{
		$('table.status')[0].innerHTML+="<tr>";
		for(var i=0; i<data.length; ++i){
			var td = "";
			var purpose;
			switch(parseInt(data[i].purpose)){
				case 0:
				purpose="일반 사용";
				break;
				case 1:
				purpose="학생회 사용";
				break;
				case 2:
				purpose="집행부 사용";
				break;
				case 3:
				purpose="소모임 사용";
				break;
				case 4:
				purpose="팀프로젝트 사용";
				break;
			}
			td += ("<td>"+data[i].starttime+":00 ~ "+(parseInt(data[i].endtime)+1)+":00"+"</td>");
			td += ("<td>"+data[i].studentname+"</td>");
			td += ("<td>"+data[i].password+"</td>");
			td += ("<td>"+purpose+"</td>");
			td += ("<td>세미나실</td>");
			td += ("<td></td>");
		}
		$('table.status')[0].innerHTML+=td+"</tr>";
	}
	
}