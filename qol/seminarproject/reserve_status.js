
//color settings
var selectedColor = "orange";
var occupiedColor = "gray";
var freeColor = "white";
var generalColor = "pink";
var nightColor = "lightcoral";

var data;


//on load
window.onload = function() {
	//populate table
	//question : will this prevent sync ajax raising alerts because it's on main thread?
	(function(){updateTableData();})();
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
			td += ("<td>"+data[i].purpose+"</td>");
			td += ("<td>세미나실</td>");
			td += ("<td></td>");
		}
		$('table.status')[0].innerHTML+=td+"</tr>";
	}

}