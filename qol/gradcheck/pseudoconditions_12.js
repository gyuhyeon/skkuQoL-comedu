[12�й� ����]

var not_selected = "not_selected";
var direct_input = "�����Է�";
var subject_section =  { "�������":not_selected+",�⺻���α׷���,��ǻ�ͱ�������,�ڷᱸ��,���ͳ�����,��ȸ��,��ü�������α׷���,Unix�Թ�,���������ͳ�,����������������,��ǻ�ͱ��翬����������", "������ȭ":not_selected+",��ǻ�ͱ���������,"+direct_input, "����":not_selected+",���л�����ġ��,�ǻ����,â�ǿͻ���,������,�⺻����,��������۷ι���ȭ,�ΰ���ȭ,��ȸ����,�ڿ����б��,�����ι���ȸ����,�����ڿ�����,��Ÿ����", "����":not_selected+",Ư�������а���,�����ǹ�,��������Ȱ��,�����ǽ�,"+direct_input
}; 

var vals = [];
var table=[[[]]];
var totalCredit, totalGrade;
var able=true;

//CountSubjects�� ���� ����� ����
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

//�� ���� ������ 140 ������ �Ѵ��� Ȯ��
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

//���� ��� ���
function avgGrade() {
	var Grade=0;
for(var i=0;i<10;i++)
	for(var j=0;j<11;j++)
Grade += table[ i ][ j ][ 4 ];
Grade = Grade / numofSubjects();
if(Grade<2.5)
able=false;
}

//�������(24��������) üũ
function checkCore(count)
{
	var credit=0;
	for ( var i=0; i < 10; i++)
		for(var j=0; j<11 ; j++)
			if( table[ i ][ j ][1] == ��������ݡ�)
				credit += table[ i ][ j ][3];
	if ( credit < 24)
		able = false;
}

//������ȭ(39��������) üũ
function checkGeneral ()
{
	var credit=0;
	for ( var i=0; i < 10; i++)
		for(var j=0; j<11 ; j++) {
			if( table[ i ][ j ][1] == ��������ȭ��)
				credit += table[ i ][ j ][3];
			foreignLang(table[i][j][2]);
		}

	if ( credit < 39)
		able = false;
}

//������� üũ
function checkCulture()
{
	vals = subject_section.����.split(",");

var count = 0;
for(var k=1; k<vals.length-1; k++) {
	count = 0;
	for ( var i=0; i < 10; i++)
	{
		for(var j=0; j<11 ; j++)
		{
			if( table[ i ][ j ][1] == ��vals[k]��)
				count +=1;	
		}
	}
	if(vals[k]==���⺻��� || vals[k]==���ǻ���롯 || vals[k]==�������ι���ȸ���С�)
		if( count < 2)
		able = false;
	else if(vals[k]==�������ڿ����С�)
		if( count < 6)
		able = false;
else
	if( count < 1)
		able = false;
}
}

//�����̷� üũ
var cultures=[];
cultures=[�������а��С�, �������ǿ���ö�������ء�, �������ǻ�ȸ�������ء�, �������ǽɸ��������ء�, ������������,  �������򰡡�,  ����������ױ������С�,  �����������ױ����濵��]
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

//�����Ҿ� & ���� �ǽ� üũ
function checkTeach2()
{	
	var check = true;
	vals = subject_section.����.split(",");
	
for ( var k=1; k<vals.length-1; k++) {
		for ( var i=0; i < 10; i++) {
			for(var j=0; j<11 ; j++) 	{
				if( table[ i ][ j ][1] == vals[k]) break;
else check = false;
			}
			if ( table[ i ][ j ][1] == vals[k]) break;
		}
		if ( check == false) {
			able = false;
			break;
		}
}
}

//�����Ϲݰ����� ������ 1�����̻� ������� üũ
function foreignLang (var subject)
{
	var foreign=[];
var count=0;
foreign=[����ǻ�ͼ�������׿�����,����ü�������α׷��֡�,�������Ϸ���,����ǻ�ͱ׷��Ƚ���,����ȸ�Ρ�,�����־����α׷��֡�,���������Ƽ�̵�,�����ͳݼ�������С�,����ǻ�ͺ��ȡ�,���˰���];

for ( var k = 0 ; k < foreign.length ; k ++) {
if(foreign[k]==subject)
count ++;
	}

if(foreign<1)
		able=false;
}



////üũ�ڽ�////
3ǰ Y/N
����һ��� (������ 2ȸ�̻�) Y/N
���������μ��˻� (������ 2ȸ �̻�) Y/N
�ڰ��� Y/N


var foreign=[];
foreign=[����ǻ�ͼ�������׿�����,����ü�������α׷��֡�,�������Ϸ���,����ǻ�ͱ׷��Ƚ���,����ȸ�Ρ�,�����־����α׷��֡�,���������Ƽ�̵�,�����ͳݼ�������С�,����ǻ�ͺ��ȡ�,���˰���];





var subject_section = { ��ųʸ� };
var vals = [];
var table=[[[]]];
var count = 0;
var totalCredit, totalGrade;
var able=true;
vals = subject_section.�����ٽ�.split(",");

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

