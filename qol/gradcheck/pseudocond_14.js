[14학번 기준]

var not_selected = "not_selected";
var direct_input = "직접입력";
var subject_section = {
	"전공핵심":not_selected+",기본프로그래밍,컴퓨터교육개론,자료구조,컴퓨터구조,데이타베이스,운영체제,컴퓨터네트워크,프로그래밍언어론",
	"전공일반":not_selected+",상업정보교과논리논술,컴퓨터교과교육론,컴퓨터교재연구및지도법,"+direct_input,
	"교양":not_selected+",인성,리더십,의사소통,창의와사유,기본영어,전문영어/글로벌문화,기초인문사회과학,기초자연과학,인간문화,사회역사,자연과학기술,기타교양",
	"교직":not_selected+",특수교육학개론,학교폭력예방및학생의이해,교직실무,교육봉사활동,교육실습,"+direct_input
}; 

var vals = [];
var table=[[[]]];
var totalCredit, totalGrade;
var able=true;

//CountSubjects로 들은 과목수 세기
function numofSubjects() {
var CountSubjects = 0;
for(var i=0;i<10;i++)
{
	for(var j=0;j<11;j++)
{	
if( table[ i ][ j ] == undefined)
break;
CountSubjects += 1;
}
}
return CountSubjects;
}

//총 들은 학점이 140 학점이 넘는지 확인
function totalCredit() {
	var Credit=0;
for(var i=0;i<10;i++)
{
		for(var j=0;j<11;j++)
Credit += table[ i ][ j ][ 3 ];
}
if(Credit<140)
able=false;
}

//학점 평균 계산
function avgGrade() {
	var Grade=0;
for(var i=0;i<10;i++)
{
	for(var j=0;j<11;j++)
Grade += table[ i ][ j ][ 4 ];
}
Grade = Grade / numofSubjects();
if(Grade<2.5)
able=false;
}

//전공핵심 체크
function checkCore(count)
{
	var check = true;
	vals = subject_section.전공핵심.split(",");
	
	check(vals.length , vals);
}

//전공일반(42학점인지) 체크
function checkGeneral ()
{
	var credit=0;
	for ( var i=0; i < 10; i++)
	{
		for(var j=0; j<11 ; j++)
		{
			if( table[ i ][ j ][1] == ‘전공일반’)
				credit += table[ i ][ j ][3];
			foreignLang(table[i][j][2]);
		}
	}

	if ( credit < 42)
		able = false;
}

//교양과목 체크
function checkCulture()
{
	vals = subject_section.교양.split(",");

var count = 0;
for(var k=1; k<vals.length-1; k++) {
	count = 0;
	for ( var i=0; i < 10; i++)
	{
		for(var j=0; j<11 ; j++)
		{
			if( table[ i ][ j ][1] == ‘vals[k]’)
				count +=1;	
		}
	}
	if(vals[k]==’기본영어’ || vals[k]==’의사소통’ || vals[k]==’기초인문사회과학’)
		if( count < 2)
		able = false;
	else if(vals[k]==’기초자연과학’)
		if( count < 4)
		able = false;
else
	if( count < 1)
		able = false;
}
}

//교직이론 체크
var cultures=[];
cultures=[‘교육학개론’, ‘교육의역사철학적이해’, ‘교육의사회학적이해’, ‘교육의심리학적이해’, ‘교육과정’,  ‘교육평가’,  ‘교육방법및교육공학’,  ‘교육행정및교육경영’]
function checkTeach()
{
	var count = 0;
	for ( var i=0; i < 10; i++)
		for(var j=0; j<11 ; j++)
			for(var k=0; k<8; k++)
if( table[i][j][1] == cultures[k] )
					count +=1;
	if( count < 6)
	able = false;
}

//교직소양 & 교직 실습 체크
function checkTeach2()
{	
	var check = true;
	vals = subject_section.교직.split(",");
	check( vals.length-1 , vals);
}

//과목을 들었는지 안들었는지에 대한 함수
function check (var len, var subs[])
{
for ( var k=1; k<len; k++) {
		for ( var i=0; i < 10; i++) {
			for(var j=0; j<11 ; j++) 	{
				if( table[ i ][ j ][1] == subs[k]) break;
else check = false;
			}
			if ( table[ i ][ j ][1] == subs[k]) break;
		}
		if ( check == false) {
			able = false;
			break;
		}
}
}

//전공일반과목중 국제어 1과목이상 들었는지 체크
function foreignLang (var subject)
{
	var foreign=[];
var count=0;
foreign=[“컴퓨터수업설계및연습”,”객체지향프로그래밍”,”컴파일러”,”컴퓨터그래픽스”,”논리회로”,”비주얼프로그래밍”,”교육용멀티미디어”,”인터넷서버구축론”,”컴퓨터보안”,”알고리즘”];

for ( var k = 0 ; k < foreign.length ; k ++) {
if(foreign[k]==subject)
count ++;
	}

if(foreign<1)
		able=false;
}



////체크박스////
3품 Y/N
심폐소생술 (재학중 2회이상) Y/N
교직적성인성검사 (재학중 2회 이상) Y/N
자격증 Y/N











var subject_section = { 딕셔너리 };
var vals = [];
var table=[[[]]];
var count = 0;
var totalCredit, totalGrade;
var able=true;
vals = subject_section.전공핵심.split(",");

function numofSubjects()
function totalCredit()
function avgGrade()
function checkCore(count)
function checkGeneral()
function checkCulture()
function checkTeachMajor()

function checkTeach()
function checkTeach2()
function check(var len)
function foreignLang()

