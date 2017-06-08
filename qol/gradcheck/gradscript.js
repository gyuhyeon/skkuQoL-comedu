/* 드롭다운 리스트 내부의 항목 (교과목) */
var not_selected = "not_selected";
var direct_input = "직접입력";
var subject_section = {
	"전공핵심":not_selected+",기본프로그래밍,컴퓨터교육개론,자료구조,컴퓨터구조,데이타베이스,운영체제,컴퓨터네트워크,프로그래밍언어론",
	"전공일반":not_selected+",상업정보교과논리논술,컴퓨터교과교육론,컴퓨터교재연구및지도법,"+direct_input,
	"교양":not_selected+",인성,리더십,기본영어,전문영어/글로벌문화,의사소통,창의와사유,기초인문사회과학,기초자연과학,인간/문화,사회/역사,자연/과학/기술,기타교양",
	"교직":not_selected+",특수교육학개론,학교폭력예방및학생의이해,교직실무,교육봉사활동,교육실습,"+direct_input
};

/* jQuery event listener */
var LV1_event_listener = "";
var LV2_event_listener = "";
var LV1_function = function() {
	//console.log(111111112313);
	var what_subject = $(this)[0].id;
	//console.log(what_subject);
	var $dropdown = $(this);	
	var key = $dropdown.val();
	var vals = [];
	var subject_name = what_subject[0] + "_NAME_" + what_subject[what_subject.length-1]; 
						
	switch(key) {
		case 'majCore':
			vals = subject_section.전공핵심.split(",");
			break;
		case 'majGen':
			vals = subject_section.전공일반.split(",");
			break;
		case 'liberal':
			vals = subject_section.교양.split(",");
			break;
		case 'pedagogy':
			vals = subject_section.교직.split(",");
			break;
		case 'base':
			vals = ["왼쪽부터 선택."];
			break;
	}
	
	var $LV2_choice = $("#"+what_subject.replace("LV1","LV2"));
	$LV2_choice.empty();
	$.each(vals, function(index, value) {
		$LV2_choice.append("<option>" + value + "</option>");
	});	

	document.getElementById(subject_name).disabled = true;
};
var LV2_function = function() {
	var what_subject = $(this)[0].id;
	var a = $("#"+what_subject.replace("LV2","LV1")).val();
	var b = $(this).val();
	var subject_name = what_subject[0] + "_NAME_" + what_subject[what_subject.length-1]; 

	if(a != "liberal" && b != not_selected && b != direct_input){
		document.getElementById(subject_name).value = b;
	}
	else {
		document.getElementById(subject_name).value = "";
	}

	if(a == "liberal" || b == direct_input){
		document.getElementById(subject_name).disabled = false;
	}
	else {
		document.getElementById(subject_name).disabled = true;
	}
};

//$(LV1_event_listener).change(LV1_function);
//$(LV2_event_listener).change(LV2_function);

/* 과목 추가 및 삭제, 학기 별 학점 계산 파트 */
var semesterN = 1;
var subjectN = [0, 0, 0, 0, 0, 0, 0, 0, 0]; // 기본 8개 학기 + 기타 학기(초과학기 및 계절학기)
var subjectList = [[null],[null],[null],[null],[null],[null],[null],[null],[null]];
var totalN = [0, 0, 0, 0, 0, 0, 0, 0, 0];
var isGraded = false;
var yourGPA;

function checkRedundancy(semester){
	if(isGraded){
		document.getElementById("SM"+String(semester)).removeChild(yourGPA);
		yourGPA = null;
		isGraded = false;
	}
}

function addSubject(semester){ // 절대 건드리지 마세요
	checkRedundancy(semester);
	if(subjectList[semester][0] == null) subjectList[semester][0] = document.createElement('div');
	else subjectList[semester].push(document.createElement('div'));
	document.getElementById("SM"+String(semester)).appendChild(subjectList[semester][subjectList[semester].length-1]);
	subjectList[semester][subjectList[semester].length-1].id=String(semester) + "_" + String(subjectN[semester]);
	subjectList[semester][subjectList[semester].length-1].innerHTML = '<select id="' + String(semester) + '_LV1_' + String(subjectN[semester]) + '"><option selected value="base">선택하세요.</option><option value="majCore">전공핵심</option><option value="majGen">전공일반</option><option value="liberal">교양</option><option value="pedagogy">교직</option></select>' +
'&nbsp;<select id="' + String(semester) + '_LV2_' + String(subjectN[semester]) + '"><option>왼쪽 분류부터 선택하세요.</option></select>' +
'&nbsp;<input id="' + String(semester) + '_NAME_' + String(subjectN[semester]) + '" type="text" size="15" disabled>' +
'&nbsp;<select id="' + String(semester) + '_CRD_' + String(subjectN[semester]) + '"><option selected value="base">...</option><option value="c1">1</option><option value="c2">2</option><option value="c3">3</option></select>' +
'&nbsp;<select id="' + String(semester) + '_GRD_' + String(subjectN[semester]) + '"><option selected value="base">...</option><option value="A+">A+</option><option value="A">A</option><option value="B+">B+</option><option value="B">B</option><option value="C+">C+</option><option value="C">C</option><option value="D+">D+</option><option value="D">D</option><option value="F">F</option><option value="P">P</option><option value="W">W</option></select>' +
'&nbsp;<input type="button" value="삭제" onclick="delSubject(' + "'SM" + String(semester) + "_" + String(subjectN[semester]) + "'" + ')">';
	
	if(LV1_event_listener == ""){
		LV1_event_listener += "#" + String(semester) + "_LV1_" + String(subjectN[semester]);
		LV2_event_listener += "#" + String(semester) + "_LV2_" + String(subjectN[semester]);
	}
	else {
		LV1_event_listener += ",#" + String(semester) + "_LV1_" + String(subjectN[semester]);
		LV2_event_listener += ",#" + String(semester) + "_LV2_" + String(subjectN[semester]);
	}
	subjectN[semester]++;
	totalN[semester]++;

	$(LV1_event_listener).change(LV1_function);
	$(LV2_event_listener).change(LV2_function);
}
addSubject(0); // node 생성이 아닌 html로 만든 div 항목은 삭제가 안됨

function delSubject(semesterDat){
	if(totalN[Number(semesterDat[2])] == 1){
		console.log("학기당 최소 한 과목 있어야 오류 안남");
	}
	else {
		document.getElementById("SM"+semesterDat[2]).removeChild(subjectList[Number(semesterDat[2])][Number(semesterDat.substr(4))]);
		subjectList[Number(semesterDat[2])][Number(semesterDat.substr(4))] = null;
		totalN[Number(semesterDat[2])]--;
	}
}

function calcGPA(semester){
	var a = 0;
	var b = 0;
	checkRedundancy(semester);
	for(var i=0;i<subjectN[semester];i++){
		var grade;
		if(subjectList[semester][i] != null) {
			if(document.getElementById(String(semester) + "_GRD_" + String(i)).value != "base" && 
				document.getElementById(String(semester) + "_CRD_" + String(i)).value != "base"){
				switch(document.getElementById(String(semester) + "_GRD_" + String(i)).value){
					case 'A+':grade=4.5;break;
					case 'A':grade=4;break;
					case 'B+':grade=3.5;break;
					case 'B':grade=3;break;
					case 'C+':grade=2.5;break;
					case 'C':grade=2;break;
					case 'D+':grade=1.5;break;
					case 'D':grade=1;break;
					case 'F':grade=0;break;
					default:grade=0;break;
				}
				a += Number(document.getElementById(String(semester) + "_CRD_" + String(i)).value.slice(1)) * grade;
				b += Number(document.getElementById(String(semester) + "_CRD_" + String(i)).value.slice(1));
			}
		}
	}
	console.log(a, b);
	if(!isGraded) {
		yourGPA = document.createElement('div');
		document.getElementById("SM"+String(semester)).appendChild(yourGPA);
		isGraded = true;
	}

	yourGPA.innerHTML = '너의 학점은. ' + String((a/b/*+0.005*/  ).toFixed(2)); // toFixed 함수가 반올림? 버림?d
}

/* 추가할 내용 */
$("#SM0").hide();
$("#INIT").click(function(){
	$("#SM0").show();
	$("#INIT").hide();
	setInterval(function(){for(var i=0;i<semesterN;i++){calcGPA(i);}},10);
});