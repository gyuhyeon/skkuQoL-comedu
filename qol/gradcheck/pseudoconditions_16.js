[16�й� ����]

var not_selected = "not_selected";
var direct_input = "�����Է�";
var subject_section = {
	"�����ٽ�":not_selected+",�⺻���α׷���,��ǻ�ͱ�������,�ڷᱸ��,��ǻ�ͱ���,����Ÿ���̽�,�ü��,��ǻ�ͳ�Ʈ��ũ,���α׷��־���",
	"�����Ϲ�":not_selected+",����������������,��ǻ�ͱ���������,��ǻ�ͱ��翬����������,"+direct_input,
	"����":not_selected+",�μ�,������,�⺻����,��������/�۷ι���ȭ,�ǻ����,â�ǿͻ���,�����ι���ȸ����,�����ڿ�����,�ΰ���ȭ,��ȸ����,�ڿ����б��,SW����,��Ÿ����",
	"����":not_selected+",Ư�������а���,�б����¿�����л�������,�����ǹ�,��������Ȱ��,�����ǽ�,"+direct_input
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
{
	for(var j=0;j<11;j++)
Grade += table[ i ][ j ][ 4 ];
}
Grade = Grade / numofSubjects();
if(Grade<2.5)
able=false;
}

//�����ٽ� üũ
function checkCore(count)
{
	var check = true;
	vals = subject_section.�����ٽ�.split(",");
	
	check(vals.length , vals);
}

//�����Ϲ�(42��������) üũ
function checkGeneral ()
{
	var credit=0;
	for ( var i=0; i < 10; i++)
	{
		for(var j=0; j<11 ; j++)
		{
			if( table[ i ][ j ][1] == �������Ϲݡ�)
				credit += table[ i ][ j ][3];
			foreignLang(table[i][j][2]);
		}
	}

	if ( credit < 42)
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
	if(vals[k]==���⺻��� || vals[k]==���ǻ���롯 || vals[k]==��SW���ʡ�)
		if( count < 2)
		able = false;
	else if(vals[k]==�������ڿ����С�)
		if( count < 4)
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
	check( vals.length-1 , vals);
}

//������ ������� �ȵ�������� ���� �Լ�
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





