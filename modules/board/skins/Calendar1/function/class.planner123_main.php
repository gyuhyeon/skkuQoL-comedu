<?php
/**
## @Package:    xe_official_planner123 (board skin)
## @File name:	class.planner123_main.php
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
## [author]
##  - Keysung Chung, 2009, 07, 28
##  - http://chungfamily.woweb.net/
## [changes]
##  - 2014.11.01 : Ver 4.6.0. (음력기념일 함수및 알고리즘 변경)
##  - 2011.08.01 : Ver 4.0.0. (월단위에서 시작 끝이 있는 기간 개념으로 변경)
##  - 2010.10.15 : Solar class 제거.
##  - 2010.10.10 : Class로 변경.
##  - 2010.09.10 : 휴일및 기념일 함수 분리함.
##  - 2009.12.20 : new build
**/

class planner123_main extends Object
{

//--------------------------------------------------------------------------------
    /**
     * @function: fn_leapmonth($pYear)
     * @return:   boolean
     * @brief:    그해의 윤달 여부 (윤년)
     **/
function fn_leapmonth($pYear){

    /*연도를 100으로 나눠떨어지지 않으면서 4로 나누어 떨어지면 윤달있음.
     *또는
     *연도를 100으로 나눠떨어지는 경우는 연도를 400으로 나눠떨어지면 윤달있음.
    **/

    if (($pYear % 100 <> 0 && $pYear % 4 == 0) or $pYear % 400 == 0) {
        $fn_leapmonth = true;
    }
    else {
        $fn_leapmonth = false;
    }
    return $fn_leapmonth;

    // 또는 단순히 date()를 이용 하거나....("L" : 윤년여부 윤년엔 1, 그 외엔 0)
    // return date("L", mktime(0, 0, 0, $pMonth, 1, $pYear));
}

//--------------------------------------------------------------------------------

    /**
     * @function: fn_firstweek($pYear,$pMonth)
     * @return  : integer
     * @brief:    해당년/월의 첫번째일의 위치를 반환 ("w" : 0=일요일, 6=토요일)
     **/
function fn_firstweek($pYear,$pMonth) {
    return date("w", mktime(0, 0, 0,$pMonth, 1, $pYear));
}

//--------------------------------------------------------------------------------

    /**
     * @function: fn_nowweek($pYear,$pMonth,$pDay)
     * @return  : integer
     * @brief:    해당년/월/일의 요일 위치를 반환 ("w" : 0=일요일, 6=토요일)
     **/
function fn_nowweek($pYear,$pMonth,$pDay) {
    return date("w", mktime(0, 0, 0,$pMonth, $pDay, $pYear));
}

//------------------------------------------------------------------------------
    /**
     * @function: fn_lastweek($pYear,$pMonth)
     * @return  : integer
     * @brief:    해당년/월의 마지막날 위치를 반환  ("w" : 0=일요일, 6=토요일)
     **/
function fn_lastweek($pYear,$pMonth) {
	$d =  date("t", mktime(0, 0, 0,$pMonth, 1, $pYear));
    return date("w", mktime(0, 0, 0,$pMonth, $d, $pYear));
}
//-----------------------------------------------------------------------------------
    /**
     * @function: fn_blankweekfirst($pYear,$pMonth)
     * @return  : integer
     * @brief:    해당년/월의 첫번째주 빈값을 구한다.
     * @brief:    해당 년/월의 일수시작이 수요일(3) 이라면 일(0)/월(1)/화(2) 즉 3개는 빈값이다.
     **/
function fn_blankweekfirst($pYear,$pMonth) {
    return planner123_main::fn_firstweek($pYear,$pMonth);
}

//--------------------------------------------------------------------------------------

    /**
     * @function: fn_blankweeklast($pYear,$pMonth)
     * @return  : integer
     * @brief:    해당년/월의 마지막주 빈값을 구한다.
     * @brief:    해당 년/월의 일수끝이 목요일(4) 이라면 금(5)/토(6) 즉 2개는 빈값이다.
     **/
function fn_blankweeklast($pYear,$pMonth) {
    return 6 - planner123_main::fn_lastweek($pYear,$pMonth);
}

//--------------------------------------------------------------------------------------

    /**
     * @function: fn_weekcountofmonth($pYear,$pMonth,$pDay)
     * @return  : integer
     * @brief:    해당 년/월/일이 당월 몇번째 주에 해당 되는지를 구한다.
     * @brief:    해당 년/월/일이 당월 2번째 주에 포함된다면 2를 반환.
     **/
function fn_weekcountofmonth($pYear,$pMonth,$pDay) {
    $wrkday = $pDay + date("w", mktime(0, 0, 0,$pMonth, 1, $pYear)); //1일의 요일번호(일=0, 토=6)
    $weekcount = floor($wrkday/7);  //소숫점이하 절사
    if ( $wrkday % 7 > 0 ) {
        $weekcount = $weekcount + 1;
    }
    return $weekcount;      // n번째 주
}

//--------------------------------------------------------------------------------------

    /**
     * @function: fn_weekdaycountofmonth($pYear,$pMonth,$pDay)
     * @return  : integer
     * @brief:    해당 년/월/일의 요일이 당월 몇번째 요일에 해당되는지 구한다.
     * @brief:    해당 년/월/일의 요일이 당월 2번째요일 이면 2를 반환.
     **/
function fn_weekdaycountofmonth($pYear,$pMonth,$pDay) {
    $k=0;       // 카운터
    $pYoil = date("w", mktime(0, 0, 0,$pMonth, $pDay, $pYear)); //해당일의 요일번호(일=0, 토=6)

    for ($i=1; $i<=$pDay; $i++) {                               // 1일 부터 말일까지 수행
        $wrk1 = date("w", mktime(0, 0, 0,$pMonth, $i, $pYear));
        if ($wrk1 == $pYoil) {              // 요일 일치
            $k=$k+1;
        }
    }
    return $k;      // n번째 요일
}

//--------------------------------------------------------------------------------------

    /**
     * @function: fn_nsweekday($pYear, $pMonth, $pCount, $pYoil)
     * @return  : string
     * @brief:    해당년/월 n번째 x요일의 일자를 구한다
     * @brief:     pCount: 숫자, pYoil: 숫자 (일요일(0) ... 토요일(6)).
     **/
function fn_nsweekday($pYear, $pMonth, $pCount, $pYoil) {
    $k=0;       // 카운터
    $j = date("t", mktime(0, 0, 0, $pMonth, 1, $pYear));    // 해당월의 날자수(말일) 값
    for ($i=1; $i<=$j; $i++) {                              // 1일 부터 말일까지 수행
        $wrk1 = date("w", mktime(0, 0, 0,$pMonth, $i, $pYear));
        if ($wrk1 == $pYoil) {              // 요일 일치
            $k=$k+1;
            if ($k == $pCount) {            // 횟수 일치
                $wrkYmd =date("Y-n-j", mktime(0, 0, 0,$pMonth, $i, $pYear));
            }
        }
    }
    return $wrkYmd;
}

//--------------------------------------------------------------------------------------

    /**
     * @function: fn_nsweeknsweekday($pYear, $pMonth, $pCount, $pYoil)
     * @return  : string
     * @brief:    해당년/월 n번째 주 x요일의 일자를 구한다
     * @brief:    pCount: 숫자, pYoil: 숫자 (일요일(0) ... 토요일(6)).
     **/
function fn_nsweeknsweekday($pYear, $pMonth, $pCount, $pYoil) {
    $k=1;       //  주 카운터
    $j = date("t", mktime(0, 0, 0, $pMonth, 1, $pYear));    // 해당월의 날자수(말일) 값
    for ($i=1; $i<=$j; $i++) {                              // 1일 부터 말일까지 수행
        $wrk1 = date("w", mktime(0, 0, 0,$pMonth, $i, $pYear)); // 요일
        if ($i != 1 && $wrk1==0) {         // 첫날이 아니면서 일요일 이면 주 카운터 증가
            $k = $k + 1;
        }
        if ($wrk1 == $pYoil) {              // 요일 일치
            if ($k == $pCount) {            // 횟수 일치
                $wrkYmd =date("Y-n-j", mktime(0, 0, 0,$pMonth, $i, $pYear));
            }
        }
    }
    return $wrkYmd;
}

//--------------------------------------------------------------------------------------

    /**
     * @function: fn_weekdaycountofmonth_end($pYear,$pMonth,$pDay)
     * @return  : integer
     * @brief:    해당 년/월/일의 요일이 당월 끝에서 몇번째 요일에 해당되는지 요일차를 구한다.
     * @brief:    해당 년/월/일의 요일이 당월 끝에서 2번째요일 이면 2를 반환.
     **/
function fn_weekdaycountofmonth_end($pYear,$pMonth,$pDay) {
    $k=0;       // 카운터
    $pYoil = date("w", mktime(0, 0, 0,$pMonth, $pDay, $pYear)); //해당일의 요일번호(일=0, 토=6)
    $j = date("t", mktime(0, 0, 0, $pMonth, 1, $pYear));    // 해당월의 날자수(말일) 값
    for ($i=$j; $i>=$pDay; $i--) {                          // 말일 부터 당일까지 수행
        $wrk1 = date("w", mktime(0, 0, 0,$pMonth, $i, $pYear));
        if ($wrk1 == $pYoil) {              // 요일 일치
            $k=$k+1;
        }
    }
    return $k;      // n번째 요일
}

//--------------------------------------------------------------------------------------

    /**
     * @function: fn_nslastweekday($pYear, $pMonth, $pCount, $pYoil)
     * @return  : string
     * @brief:    해당년/월 끝에서 n번째 x요일의 일자를 구한다
     * @brief:     pCount: 숫자, pYoil: 숫자 (일요일(0) ... 토요일(6)).
     **/
function fn_nslastweekday($pYear, $pMonth, $pCount, $pYoil) {
    $k=0;       //  주 카운터
    $j = date("t", mktime(0, 0, 0, $pMonth, 1, $pYear));    // 해당월의 날자수(말일) 값
    for ($i=$j; $i>=1; $i--) {                              // 말일 부터 1일까지 수행
        $wrk1 = date("w", mktime(0, 0, 0,$pMonth, $i, $pYear)); // 요일
        if ($wrk1 == $pYoil) {              // 요일 일치
            $k = $k + 1;
            if ($k == $pCount) {            // 횟수 일치
                $wrkYmd =date("Y-n-j", mktime(0, 0, 0,$pMonth, $i, $pYear));
            }
        }
    }
    return $wrkYmd;
}

//--------------------------------------------------------------------------------
    /**
     * @function: fn_CalMain($dispStart_stamp, $dispEnd_stamp, $firstDayOfWeek = 0)
     * @return  : array
     * @brief:    주어진 년/월의 달력을 만든다.
     * @brief:     2차원배열을 사용하여 틀을 만든다.
     * @brief:     가로(1주)는 무조건 7이 되므로 세로값만 알면 된다.
     * @brief:     빈칸은 NULL 값으로한다
     * @brief:     형태예제
     * @brief:     |일|월|화|수|목|금|토|
     * @brief:     | n| n| n| n| n| n| 1|
     * @brief:     | 2| 3| 4| 5| 6| 7| 8|
     * @brief:     | 9|10|11|12|13|14|15|
     * @brief:     |16|17|18|19|20|21|22|
     * @brief:     |23|24|25|26|27|28|29|
     * @brief:     |30|31| n| n| n| n| n|
     **/
function fn_CalMain($dispStart_stamp,$dispEnd_stamp, $firstDayOfWeek = 0) {
	// $firstDayOfWeek // 일주일 시작 요일(0=일, 1=월, 2=화... 6=토)
	$s_date = date("Y-n-j", $dispStart_stamp);
	$arr_sdate = explode("-",$s_date);
	$s_JD = planner123_main::fn_calcDateToJD($arr_sdate[0], $arr_sdate[1], $arr_sdate[2]);
	$e_date = date("Y-n-j", $dispEnd_stamp);
	$arr_edate = explode("-",$e_date);
	$e_JD = planner123_main::fn_calcDateToJD($arr_edate[0], $arr_edate[1], $arr_edate[2]);
	$period = $e_JD - $s_JD + 1; // 1970-1-1 ~30:(30-1+1))=30

	//$blank_first = date("w", $dispStart_stamp);
	$blank_first = ( date("w", $dispStart_stamp) + (7-$firstDayOfWeek) ) % 7; // 시작요일 반영(v480)
	//$blank_last = 6-date("w", $dispEnd_stamp);
	$blank_last = ( (6 + $firstDayOfWeek) - date("w", $dispEnd_stamp) ) % 7;
	$days = $period + $blank_first + $blank_last;
	$weeks = $days/7;
	$tmp_JD = $s_JD - $blank_first;
    for ($i=0; $i<$weeks; $i++) {
		for ($j=0; $j<7; $j++) {
			if($tmp_JD < $s_JD || $tmp_JD > $e_JD) {
				$aCal[$i][$j] = "*"; // 일자가 해당 기간밖에 있으면 별표
			} else {
				$tmp_01 = planner123_main::fn_calcJDToGregorian($tmp_JD);  // mm/dd/yy
				$arr_01 = explode("/",$tmp_01);
				$aCal[$i][$j] =$arr_01[2]."-".$arr_01[0]."-".$arr_01[1];  // 일자가 해당 기간안에 있으면 날자
			}
			$tmp_JD += 1;
		}
	}

    return $aCal;
}

//--------------------------------------------------------------------------------------
    /**
     * @function: fn_smallcalendar()
     * @return  : string
     * @brief:    소형 당월 칼런다 HTML코드 출력
     * @brief:
     **/

function fn_smallcalendar(){
    $year = date("Y");
    $month = date("n");
    $day = date("d");
    $day_max = date("t",mktime(0,0,0,$month,1,$year));
    $week_start = date("w",mktime(0,0,0,$month,1,$year));
    $i = 0;
    $j = 0;
    $html = "<div class='calendar_box'><div class='calendar_title B'>".sprintf("%d-%02d-%02d",$year,$month,$day)."</div>";
    while ($j<$day_max){
        if ($i<$week_start) {
            $html .= "<div class='calendar_text'>·</div>";
        } else {
            if ($i%7==0) $font_color = " RED";
            else if ($i%7==6) $font_color = " BLUE";
            else $font_color = "";
            if ($day == ($j+1)) $font_weight = " B"; else $font_weight = "";
            $html .= "<div class='calendar_text$font_color$font_weight'>" . ($j+1) . "</div>";
            $j ++;
        }
        $i ++;
    }
    while ($i%7!==0){
        $html .= "<div class='calendar_text'>·</div>";
        $i ++;
    }
    $html .= "<div class='calendar_tail'></div></div>";
    return $html;
}


//------------------------------------------------------------------------
    /**
     * @function: fn_ganji_ary($pYear, $pMonth, $pDay)
     * @return  : array
     * @brief:    간지가 새로 시작되는 새해 시작일을 입력받아 양력에 대응되는 세차, 일진 어레이 리턴
     * @brief:    유효기간 1902 - 2037
     **/
Function fn_ganji_ary($pYear, $pMonth, $pDay) {
    //*****************************************************
    // 년간 양력 일자에 대응되는 간지 어레이 (세차, 일진) 계산
    //
    // 새해의 시작을 구분 할때 사람에 따라 이론이 있으니 아래 내용 참고 하세요.
    //
    // "주역을 하시는 분들은 새해의 시작을 동지로 보고,
    // 명리학이나 사주를 보는 분들은 새해의 시작을 입춘으로 보는것 같습니다만,
    // 새해의 시작은 정월 초하루입니다."  - (한국천문연구원 홈페이지내 질문답변 게시판에서 발췌)
    //
    // 위와 같이 새해 시작점은 사람에 따라 기준점이 다르다고 합니다.
    //
    // 그러나, 기준일이 바뀌면 일자의 간지(일진)는 변하지 않지만 년의 간지(세차)와 월의 간지(월권)는
    // 변경이 되니 얼마나 난감한 일인지 모르겠습니다.
    // 사실 어느 기준을 적용하느냐에 따라 달라지는 자료(팔자)를 이용해 흔히들 사주를 본다거나 하는 일이
    // 조금은 황당하다는 생각도 듭니다.
    //
    // 그러나 간지는 우리 선조들이 계속 사용해 왔고 그중 일진은 과거 수천년 동안 그 주기가 변하지 않고
    // 계속 이어져 내려 왔다고 하니 나름 매우 중요한 자료라고 생각 합니다.
    //
    // 본 함수는 날짜를 고정 하지 않고 호출시 넘어온 일자를 새해 시작일로 하여 세차와 일진을 계산 합니다.
    // 넘어온 날이 8월 보다 적을 경우는 설날과, 입춘이라고 가정 했습니다.
    //
    // 또 고려 할 다른 문제점은 입춘이나 동지를 시작일로 할경우는 입춘이나 동지 일자를 강제로 지정하지 않고
    // 계산을 할 경우(본 함수) 그 계산이 정확해야 되는데 정확한 24절기 계산이 쉬운일이 아니라고 합니다.
    // 현재 구할 수 있는 24절기 계산 함수들은 천체 운동 계산에 약간의 오차가 있는듯 하며,
    // 그 결과 24절기가 간혹 하루 정도 차이가 나는 경우가 있음을 염두에 두시고 이용 하시기 바랍니다.
    //(실제 계산상 차이가 몇 시간 이라고 해도 이것이 24:00시 기준 전날인지 다음날 인지에 따라서도 날자가 바뀜니다.)
    //
    //참고로 한국천문연구원이 게시한 24절기는 http://www.kasi.re.kr/Knowledge/almanac.aspx 에서 확인할 수 있습니다.
    //******************************************************

    if ($pYear<1902 || $pYear>2037) {       // 유효기간 1902-2037
        return;
    }

	$aHoli = NULL;
	$arr_gan = array("甲","乙","丙","丁","戊","己","庚","辛","壬","癸");
	$arr_ji =  array("子","丑","寅","卯","辰","巳","午","未","申","酉","戌","亥");
	$arr_ganji =  array('甲子','乙丑','丙寅','丁卯','戊辰','己巳','庚午','辛未','壬申','癸酉','甲戌','乙亥',
                '丙子','丁丑','戊寅','己卯','庚辰','辛巳','壬午','癸未','甲申','乙酉','丙戌','丁亥',
                '戊子','己丑','庚寅','辛卯','壬辰','癸巳','甲午','乙未','丙申','丁酉','戊戌','己亥',
                '庚子','辛丑','壬寅','癸卯','甲辰','乙巳','丙午','丁未','戊申','己酉','庚戌','辛亥',
                '壬子','癸丑','甲寅','乙卯','丙辰','丁巳','戊午','己未','庚申','辛酉','壬戌','癸亥');

	// 월건을 위해 세차의 간지중 간만 출력을 위해
	$arr_ganji_WG =  array('甲','乙','丙','丁','戊','己','庚','辛','壬','癸','甲','乙',
                '丙','丁','戊','己','庚','辛','壬','癸','甲','乙','丙','丁',
                '戊','己','庚','辛','壬','癸','甲','乙','丙','丁','戊','己',
                '庚','辛','壬','癸','甲','乙','丙','丁','戊','己','庚','辛',
                '壬','癸','甲','乙','丙','丁','戊','己','庚','辛','壬','癸');

    $baseYear = 1902;  // 1902년 1월 1일: 세차-"임인壬寅",  일진-"갑신甲申" 인데
                       // 어레이 arr_ganji 값으로 세차는 "38, 일진은 "20"에 해당

    $base_date = "1902-1-1";

	// ---세차계산 (절기의 새해 시작점)---
    $k = ($pYear - $baseYear+38) % 60 ; // 60으로 나눈 나머지
                                        // 해당년의 세차를 arr_ganji 어레이에 맞게 조정 (38)

    if ($pMonth < 8 ) {             // 기준월 이 8월 보다 작은경우(설날과 입춘이 기준일경우)
        if ($k-1 < 0 ) {
            $Secha1=$arr_ganji_WG[59]."-".$arr_ganji[59];       // 기준일 이전의 세차
        }
        else {
            $Secha1=$arr_ganji_WG[$k-1]."-".$arr_ganji[$k-1];
        }

        $Secha2 =$arr_ganji_WG[$k]."-".$arr_ganji[$k];      // 기준일 부터의 세차

    }
    else {                              // 동지일 경우
        if ($k+1 > 59 ) {
            $Secha2=$arr_ganji_WG[0]."-".$arr_ganji[0];     // 기준일 부터의 세차
        }
        else {
            $Secha2=$arr_ganji_WG[$k+1]."-".$arr_ganji[$k+1];
        }

        $Secha1 = $arr_ganji_WG[$k]."-".$arr_ganji[$k];     // 기준일 이전의 세차

    }

	// ---일진 추가 ---
    for ($i=1; $i < 13; $i++ ) { // 입력받은 월 일은 새해 시작일임.
		$month_end = date('t', mktime(0, 0, 0, $i, 1, $pYear));
        for ($j=1; $j <= $month_end; $j++ ) {
			$startdate = date("Y-n-j", mktime(0, 0, 0, $i, $j, $pYear));
			$pastdays = round((strtotime($startdate)-strtotime($base_date))/86400);//1902-1-1 부터 해당년 1월 1일 직전 까지 경과일
			$k = ($pastdays+20) % 60;// 해당일의 일진을 arr_ganji 어레이에 맞게 조정 (20)

			if ($i < $pMonth || $i == $pMonth && $j < $pDay) {
				$aHoli[$i][$j] = $Secha1."年-".$arr_ganji[$k]."日";
			} else {
				$aHoli[$i][$j] = $Secha2."年-".$arr_ganji[$k]."日";
			}
        }
    }
    return $aHoli;
}

//------------------------------------------------------------------------
    /**
     * @function: fn_jeolki_ganji_ary($pYear,$pMonth,$pGanjioption)
     * @return  : array
     * @brief:    년간 양력 일자에 대응되는 24절기, 일진 등을 입력한 어레이 리턴
     * @modify:   V220에서 $pMonth 추가, V320에서 class.solar.php 함수 제거.
     **/
Function fn_jeolki_ganji_ary($pYear,$pMonth,$pGanjioption) {
    /******************************************************
    * 년간 양력 일자에 대응되는 절기, 일진 어레이
    *******************************************************/
    $aHoli = NULL;
	$pYear = date("Y", mktime(0,0,0,$pMonth,1,$pYear));
	$pMonth = date("n", mktime(0,0,0,$pMonth,1,$pYear));

	$aHoli = $jeolki = planner123_main::fn_get_term24($pYear);

// 간지 시작 기준날자 설정 : 입력 받은 option에 의해.
    switch ($pGanjioption) {
      case (1):
        $ganjioption = "설날";        // 설날을 새해 첫날로 간주.
        break;
      case (2):
        $ganjioption = "입춘";        // 명리학, 사주 위주 (입춘을 새해 첫날로 간주).
        break;
      case (3):
        $ganjioption = "동지";        // 주자학 위주 (동지를 새해 첫날로 간주).
        break;
      default:                        // option 없을때
        $ganjioption = "설날";        // 설날을 새해 첫날로 간주.
        break;
    }

// 음력 1월 1일  양력날자 구하기  (세차 계산을 위해)------------------------
    $lunfirstday = explode(",",planner123_main::fn_lun2sol_kr($pYear,1,1));
    $SeolMM = $lunfirstday[1];      // 세차(년 간지)가 바뀌는 일자 월  (음력 1월 1일)
    $SeolDD = $lunfirstday[2];      // 세차(년 간지)가 바뀌는 일자 일  (음력 1월 1일)

// 24 절기 및 주요날자 얻기-----------------------
    foreach($jeolki as $key1 => $value1) {
		foreach($value1 as $key2 => $value2) {
            // 콤멘트 추가
            switch ($value2) {
               case ("입춘"):
                $IpchunMM = $key1;     // 입춘 일자 월
                $IpchunDD = $key2;     // 입춘 일자 일
                break;
               case ("춘분"):
                $ChunbunMM = $key1;    // 춘분 일자 월
                $ChunbunDD = $key2;    // 춘분 일자 일
                break;
               case ("하지"):
                $HajiMM = $key1;       // 하지 일자 월
                $HajiDD = $key2;       // 하지 일자 일
                break;
               case ("입추"):
                $IpchuMM = $key1;      // 입추 일자 월
                $IpchuDD = $key2;      // 입추 일자 일
                break;
               case ("추분"):
                $ChubunMM = $key1;     // 추분 일자 월
                $ChubunDD = $key2;     // 추분 일자 일
                break;
               case ("동지"):
                $DongjiMM = $key1;     // 동지 일자 월
                $DongjiDD = $key2;     // 동지 일자 일
                break;
            }
        }
    }
// ---- 간지 시작일 을 조건에 따라 설정 ----
    if ($ganjioption == "설날"):
        $GanjiStartMM = $SeolMM;
        $GanjiStartDD = $SeolDD;
    elseif ($ganjioption == "입춘"):
        $GanjiStartMM = $IpchunMM;
        $GanjiStartDD = $IpchunDD;
    elseif ($ganjioption == "동지"):
        $GanjiStartMM = $DongjiMM;
        $GanjiStartDD = $DongjiDD;
    else:
        $GanjiStartMM = $SeolMM;        // 설날로 설정
        $GanjiStartDD = $SeolDD;
    endif;

// --세차와 일진을 구한다------------------------------
    $arr_Secha = planner123_main::fn_ganji_ary($pYear, $GanjiStartMM, $GanjiStartDD);


// --- 초복, 중복, 말복 계산(하지, 추분 및 일진을 조합하여 계산-------------
// 초복, 중복: 하지 로부터 세 번째(초복), 네번째(중복) 돌아오는 경일
// 말복      : 입추로부터 첫 번째 경일
// 하지 일자 월, 일: $HajiMM  $HajiDD
// 입추 일자 월, 일: $IpchuMM $IpchuDD
// 경일:'庚午','庚辰','庚寅','庚子','庚戌','庚申'  // 문자열 자르기가 안되서 비교로 처리함

//-- 초복, 중복 --------
    $k = 0;
    For ($i = $HajiMM; $i<10; $i++) {
        For ($j = 1; $j <32; $j++ ) {
            if ($i > $HajiMM || $i == $HajiMM && $j >= $HajiDD ) {

            $temp01 = explode("-",$arr_Secha[$i][$j]);
            $wrkfld01 = $temp01[2];
            if($wrkfld01 == "庚午日" || $wrkfld01 == "庚辰日" || $wrkfld01 == "庚寅日" || $wrkfld01 == "庚子日" ||  $wrkfld01 == "庚戌日" || $wrkfld01 == "庚申日") {
                $k = $k + 1;
                if ($k == 3) {
                    $aHoli[$i][$j] = $aHoli[$i][$j]."초복";
                }
                if ($k == 4) {
                    $aHoli[$i][$j] = $aHoli[$i][$j]."중복";
                    break;
                }
            }

            }
        }
    }

// -- 말복 ---
    $k = 0;
    For ($i = $IpchuMM; $i<10; $i++) {
        For ($j = 1; $j <32; $j++ ) {
            if ($i > $IpchuMM || $i == $IpchuMM && $j >= $IpchuDD ) {

            $temp01 = explode("-",$arr_Secha[$i][$j]);
            $wrkfld01 = $temp01[2];
            if($wrkfld01 == "庚午日" || $wrkfld01 == "庚辰日" || $wrkfld01 == "庚寅日" || $wrkfld01 == "庚子日" ||  $wrkfld01 == "庚戌日" || $wrkfld01 == "庚申日") {
                $k = $k + 1;
                if ($k == 1) {
                    $aHoli[$i][$j] = $aHoli[$i][$j]."말복";
                    break;
                }
            }

            }
        }
    }

// 기타 음력절기 상 특별한날 V320에서 변경------------------------
	//(단오)
	$temp01 = explode(",",planner123_main::fn_lun2sol_kr($pYear,5,5));
	$iLunYmd =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]));
	$temp02 = explode("-",$iLunYmd);
	$aHoli[$temp02[1]][$temp02[2]] .= "단오";
	//(칠석)
	$temp01 = explode(",",planner123_main::fn_lun2sol_kr($pYear,7,7));
	$iLunYmd =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]));
	$temp02 = explode("-",$iLunYmd);
	$aHoli[$temp02[1]][$temp02[2]] .= "칠석";
	//(백중)
	$temp01 = explode(",",planner123_main::fn_lun2sol_kr($pYear,7,15));
	$iLunYmd =date("Y-n-j", mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]));
	$temp02 = explode("-",$iLunYmd);
	$aHoli[$temp02[1]][$temp02[2]] .= "백중";

//---- 한식일자 구하기: V320에서 변경
// 한식은 전년도 동지에서 105일째 되는 날
	if($pYear >=1903 && $pYear <=2037) {
		$ind50 = planner123_main::fn_get_dongji($pYear-1)+86400*105;
		$ind51 = date('n', $ind50);
		$ind52 = date('j', $ind50);
		$aHoli[$ind51][$ind52] .= "한식";
	}

//---- 절기와 간지 합하여 어레이 리턴---
	if($pMonth + 4 > 12) {
		$tmp_end_month = 12;
	} else {
		$tmp_end_month = $pMonth + 4;
	}
//  For ($i = 1; $i<13; $i++) {	// V220,V400 변경
    For ($i = $pMonth; $i <= $tmp_end_month; $i++) {
        For ($j = 1; $j <32; $j++ ) {
            $aHoli[$i][$j] = $arr_Secha[$i][$j]."-".$arr_WolGeon[$i][$j]."-".$aHoli[$i][$j];
        }
    }
    return $aHoli;

}

//-----------------------------------------------------------------------------------
     /**
     * @function: fn_easterday($pYear)
     * @return  : string
     * @brief:    해당년 부활절 일자를 구한다
     **/
function fn_easterday($pYear) {
    $k=easter_days($pYear);
    if ($k>10) {
        $wrkYmd =date("Y-n-j", mktime(0, 0, 0,4, $k-10, $pYear));
    }
    else   {
        $wrkYmd =date("Y-n-j", mktime(0, 0, 0,3, $k+21, $pYear));
    }
    return $wrkYmd;
}

//-----------------------------------------------------------------------------------
	 /**
     * @function: fn_easterday_2($pYear)
     * @return  : string
     * @brief:    해당년 부활절 일자를 구한다 (가우스공식이용) - 간혹 PHP에서 부활절함수 지원 안될때 이용
     **/
function fn_easterday_2($pYear) {
 $M = 24; // 1900-2099 년도분만 사용
 $N = 5;  // 1900-2099 년도분만 사용
    $A = $pYear % 19;
    $B = $pYear % 4;
    $C = $pYear % 7;
    $D = (19 * $A + $M) % 30;
    $E = ((2 * $B) + (4 * $C) + (6 * $D) + $N) % 7;
    $Tag1 = (22 + $D + $E);
    $Tag2 = ($D + $E - 9);

 if($Tag1 <= 31 ) {
   $easterday = mktime(0, 0, 0, 3, $Tag1, $pYear); // 3월 부활절 일자 타임스탬프
   $wrkYmd =date("Y-n-j", $easterday);
  }
  else {
   $easterday = mktime(0, 0, 0, 4, $Tag2, $pYear); // 4월 부활절 일자 타임스탬프
   $wrkYmd =date("Y-n-j", $easterday);
  }

    return $wrkYmd;
}
//--------------------------------------------------------------------------------------

//--------------------------------------------------------------------------------------
    /**
     * @function: fn_HolidayChk($pYear, $pMonth)
     * @function: fn_MemdayChk($pYear, $pMonth)
     * @brief:    휴일 기념일 함수는 별도로 분리함
     **/
//------------------------------------------------------------------------

//------------------------------------------------------------------------
    /**
     * @function: fn_repeat_schedule($dispStart_stamp, $dispEnd_stamp, $plan_start, $plan_end, $plan_repeat_cycle, $plan_repeat_unit, $Holiday)
     * @return  : array
     * @brief:    반복일정이 적용되는 양력일자 어레이 리턴, V430:$Holiday추가 
     **/
Function fn_repeat_schedule($dispStart_stamp, $dispEnd_stamp, $plan_start, $plan_end, $plan_repeat_cycle, $plan_repeat_unit, $Holiday) {
	/******************************************************
	* 반복일정이 적용되는 일자에 "년도" 삽입
	* 반복일정은 일정시작일을 기준으로 반복되며, 모든 반복일정은 일정 자체의 기간은 1일 간으로한다.
	* 그렇지 않을경우 일정자체의 기간을 지정할 2개의 추가 확장변수가 필요하게 되어 실익이 없다.
	*******************************************************/
	$aHoli = NULL;
	$dispStart_stamp -= 86400*3; // 8번 조정을 위해
	if ($plan_start == NULL ) {
		return $aHoli;
	}

	$startYY = substr($plan_start,0,4);
	$startMM = ltrim(substr($plan_start,4,2), "0" );	//  앞의 "0" 제거
	$startDD = ltrim(substr($plan_start,6,2), "0" );	//  앞의 "0" 제거
	$plan_startdate_stamp = mktime(0, 0, 0, $startMM, $startDD, $startYY);	// 일정시작 일자 타임스탬프
	$plan_startMM_cnt = $startYY*12 + $startMM;

	$endYY = substr($plan_end,0,4);
	$endMM = ltrim(substr($plan_end,4,2), "0" );	// 일자 앞의 "0" 제거
	$endDD = ltrim(substr($plan_end,6,2), "0" );	// 일자 앞의 "0" 제거
	$plan_enddate_stamp = mktime(0, 0, 0, $endMM, $endDD, $endYY); // 일정종료 일자 타임스탬프

	$dsp_startYY = date("Y", $dispStart_stamp);
	$dsp_startMM = date("n", $dispStart_stamp);
	$dsp_startDD = date("j", $dispStart_stamp);
	//$dsp_startYMD = date("Ymd", $dispStart_stamp);
	$dsp_startMM_cnt = $dsp_startYY*12 + $dsp_startMM;
	$dsp_endYY = date("Y", $dispEnd_stamp);
	$dsp_endMM = date("n", $dispEnd_stamp);
	$dsp_endDD = date("j", $dispEnd_stamp);
	//$dsp_endYMD = date("Ymd", $dispEnd_stamp);
	$dsp_endMM_cnt = $dsp_endYY*12 + $dsp_endMM;

	if(function_exists('gregoriantojd')) {
		$plan_start_jd = gregoriantojd($startMM, $startDD, $startYY);	// 일정시작 일자 jd
		$plan_end_jd = gregoriantojd($endMM, $endDD, $endYY);			// 일정종료 일자 jd
		$dsp_start_jd = gregoriantojd($dsp_startMM, $dsp_startDD, $dsp_startYY);// 출력시작 일자 jd
		$dsp_end_jd = gregoriantojd($dsp_endMM, $dsp_endDD, $dsp_endYY);		// 출력종료 일자 jd
	} else {
		$plan_start_jd = planner123_main::fn_calcDateToJD($startYY, $startMM, $startDD);	// 일정시작 일자 jd
		$plan_end_jd = planner123_main::fn_calcDateToJD($endYY, $endMM, $endDD);			// 일정종료 일자 jd
		$dsp_start_jd = planner123_main::fn_calcDateToJD($dsp_startYY, $dsp_startMM, $dsp_startDD);// 출력시작 일자 jd
		$dsp_end_jd = planner123_main::fn_calcDateToJD($dsp_endYY, $dsp_endMM, $dsp_endDD);		// 출력종료 일자 jd
	}

if ($plan_start_jd <= $dsp_end_jd && $plan_end_jd >= $dsp_start_jd) { // 기간에 포함될때
	$wrk_repeat_unit = explode('.', $plan_repeat_unit);

	// plan_repeat_cycle 또는 plan_repeat_unit 값이 NULL 일때********************
	if (($plan_repeat_unit == NULL || $plan_repeat_cycle == NULL) ) {
		For	($x = $dsp_start_jd; $x <= $dsp_end_jd; $x++) {
			if($x >= $plan_start_jd && $x <= $plan_end_jd) {
				if(function_exists('jdtogregorian')) {
					$wrk_date = jdtogregorian($x);
				} else {
					$wrk_date = planner123_main::fn_calcJDToGregorian($x);
				}
				$wrk_arr = explode('/', $wrk_date);
				$aHoli[$wrk_arr[0]][$wrk_arr[1]] =  $wrk_arr[2];
			}
		}
	} else {
		switch($wrk_repeat_unit[0]) {
		// unit 값이 1.일(간격) : 몇일 간결으로 반복***********************************
		case '1':
		For	($x = $dsp_start_jd; $x <= $dsp_end_jd; $x++) {
			if((($x - $plan_start_jd) % $plan_repeat_cycle) == 0) {
			if($x >= $plan_start_jd && $x <= $plan_end_jd) {
				if(function_exists('jdtogregorian')) {
					$wrk_date = jdtogregorian($x);
				} else {
					$wrk_date = planner123_main::fn_calcJDToGregorian($x);
				}
				$wrk_arr = explode('/', $wrk_date);
				$aHoli[$wrk_arr[0]][$wrk_arr[1]] =  $wrk_arr[2];
			}
			}
		}
		break;
		// 2.개월(날자): 반복월 같은 날자**********************************************
		case '2':
		For	($x = $dsp_startMM_cnt; $x <= $dsp_endMM_cnt; $x++) {
			if(!(($x-$plan_startMM_cnt)%$plan_repeat_cycle)) {	// 해당월
				$wrkYY = floor(($x-1)/12);	// 년
				$wrkMM = ($x-1)%12 + 1;	// 월
				$wrkDD = $startDD;	// 일
				$wrk_jd = planner123_main::fn_calcDateToJD($wrkYY, $wrkMM, $wrkDD);// 일자 jd
				if($wrk_jd >= $plan_start_jd && $wrk_jd <= $plan_end_jd) {
					$aHoli[$wrkMM][$wrkDD] =  $wrkYY;
				}
			}
		}
		break;
		// 3.개월(요일): 반복월 같은번째 요일*******************************************
		case '3':
		$pYoil = date("w", $plan_startdate_stamp);	//해당일의 요일번호(일=0, 토=6)
		$yoilcount = planner123_main::fn_weekdaycountofmonth($startYY, $startMM, $startDD); // n번째 요일 숫자
		For	($x = $dsp_startMM_cnt; $x <= $dsp_endMM_cnt; $x++) {
			if(!(($x-$plan_startMM_cnt)%$plan_repeat_cycle)) {	// 해당월
				$wrkYY = floor(($x-1)/12);	// 년
				$wrkMM = ($x-1)%12 + 1;	// 월
				$wrkDD = $startDD;	// 일
				$temp01 = explode("-", planner123_main::fn_nsweekday($wrkYY, $wrkMM, $yoilcount, $pYoil));	// 해당n번째요일에 대응되는 일자 얻기
				$wrk_jd = planner123_main::fn_calcDateToJD($temp01[0], $temp01[1], $temp01[2]);// 일자 jd
				if($wrk_jd >= $plan_start_jd && $wrk_jd <= $plan_end_jd) {
					$aHoli[$temp01[1]][$temp01[2]] =  $wrkYY;
				}
			}
		}
		break;
		// 4.개월(주): 반복월 같은번째 주 같은요일**************************************
		case '4':
		$pYoil = date("w", mktime(0, 0, 0,$startMM, $startDD, $startYY));	//해당일의 요일번호(일=0, 토=6)
		$weekcount = planner123_main::fn_weekcountofmonth($startYY, $startMM, $startDD);			//n번째 주 숫자
		For	($x = $dsp_startMM_cnt; $x <= $dsp_endMM_cnt; $x++) {
			if(!(($x-$plan_startMM_cnt)%$plan_repeat_cycle)) {	// 해당월
				$wrkYY = floor(($x-1)/12);	// 년
				$wrkMM = ($x-1)%12 + 1;	// 월
				$wrkDD = $startDD;	// 일
				$temp01 = explode("-", planner123_main::fn_nsweeknsweekday($wrkYY, $wrkMM, $weekcount, $pYoil));// 해당주/요일에 대응되는 일자 얻기
				$wrk_jd = planner123_main::fn_calcDateToJD($temp01[0], $temp01[1], $temp01[2]);// 일자 jd
				if($wrk_jd >= $plan_start_jd && $wrk_jd <= $plan_end_jd) {
					$aHoli[$temp01[1]][$temp01[2]] =  $wrkYY;
				}
			}
		}
		break;
		// 5.개월(말일): 반복월 말일****************************************************
		case '5':
		For	($x = $dsp_startMM_cnt; $x <= $dsp_endMM_cnt; $x++) {
			if(!(($x-$plan_startMM_cnt)%$plan_repeat_cycle)) {	// 해당월
				$wrkYY = floor(($x-1)/12);	// 년
				$wrkMM = ($x-1)%12 + 1;	// 월
				$wrkDD = $startDD;	// 일
				$wrklastday= date("t", mktime(0, 0, 0, $wrkMM, 1, $wrkYY));	// 반복될 마지막 날자
				$wrk_jd = planner123_main::fn_calcDateToJD($wrkYY, $wrkMM, $wrklastday);// 일자 jd
				if($wrk_jd >= $plan_start_jd && $wrk_jd <= $plan_end_jd) {
					$aHoli[$wrkMM][$wrklastday] =  $wrkYY;
				}
			}
		}
		break;
		// 6.개월(월말부터요일차): 반복월 끝에서부터 같은번째 요일****************************************************
		case '6':
        $pYoil = date("w", mktime(0, 0, 0,$startMM, $startDD, $startYY));    //해당일의 요일번호(일=0, 토=6)
        $yoilcount = planner123_main::fn_weekdaycountofmonth_end($startYY, $startMM, $startDD); //해당일의 말일에서부터의 n번째 요일 숫자
		For	($x = $dsp_startMM_cnt; $x <= $dsp_endMM_cnt; $x++) {
			if(!(($x-$plan_startMM_cnt)%$plan_repeat_cycle)) {	// 해당월
				$wrkYY = floor(($x-1)/12);	// 년
				$wrkMM = ($x-1)%12 + 1;	// 월
				$wrkDD = $startDD;	// 일
	            $temp01 = explode("-", planner123_main::fn_nslastweekday($wrkYY, $wrkMM, $yoilcount, $pYoil)); //끝에서 n번째요일에 대응되는 일자 얻기
				$wrk_jd = planner123_main::fn_calcDateToJD($temp01[0], $temp01[1], $temp01[2]);// 일자 jd
				if($wrk_jd >= $plan_start_jd && $wrk_jd <= $plan_end_jd) {
					$aHoli[$temp01[1]][$temp01[2]] =  $wrkYY;
				}
			}
		}
		break;
		// 7.개월(음력날자): 음력으로 반복월이며 같은 음력날자(2014-07-01 음력함수변경)*************************
		case '7':
		$lun_arr = planner123_main::fn_sol2lun_kr_period($dispStart_stamp, $dispEnd_stamp);  // 양력대응 음력일자 [월][일]
		$wrk_a01 = explode(',', planner123_main::fn_sol2lun_kr($startYY, $startMM, $startDD));  // 일정의 음력날자
		For	($x = $dsp_start_jd; $x <= $dsp_end_jd; $x++) {	// 출력 기간
		  if($x >= $plan_start_jd && $x <= $plan_end_jd) {
			if(function_exists('jdtogregorian')) {
				$wrk_date = jdtogregorian($x);
			} else {
				$wrk_date = planner123_main::fn_calcJDToGregorian($x);
			}
			$wrk_arr = explode('/', $wrk_date);
			$wrk03_mm = $wrk_arr[0];
			$wrk03_dd = $wrk_arr[1];
			$wrk_a02 = explode(',', $lun_arr[$wrk03_mm][$wrk03_dd]);  // 각 출력일의 음력날자
			// 음력상 반복월이며 같은날자
			if((($wrk_a02[0]*12 + $wrk_a02[1]) - ($wrk_a01[0]*12 + $wrk_a01[1])) % $plan_repeat_cycle == 0 && $wrk_a01[2] == $wrk_a02[2]) {
				$aHoli[$wrk_arr[0]][$wrk_arr[1]] =  $wrk_arr[2];
			}
		  }
		}
		break;
		// 8.개월(음력날자): 음력반복월이며 음력으로 같은번째 요일(2014-07-01 음력함수변경)********************
		case '8':
		$lun_arr = planner123_main::fn_sol2lun_kr_period($dispStart_stamp, $dispEnd_stamp);  // 양력대응 음력일자 [월][일]
		$wrk_a01 = explode(',', planner123_main::fn_sol2lun_kr($startYY, $startMM, $startDD)); // 일정의 음력날자  
		$wrk01 = date("w", mktime(0, 0, 0, $startMM, $startDD ,$startYY));	// 요일
		$wrk02 = ceil(($wrk_a01[2] + 6 - $wrk01) / 7);  // n번째주
		For	($x = $dsp_start_jd; $x <= $dsp_end_jd; $x++) {	// 출력 기간
		  if($x >= $plan_start_jd && $x <= $plan_end_jd) {
			if(function_exists('jdtogregorian')) {
				$wrk_date = jdtogregorian($x);
			} else {
				$wrk_date = planner123_main::fn_calcJDToGregorian($x);
			}
			$wrk_arr = explode('/', $wrk_date);
			$wrk03_mm = $wrk_arr[0];
			$wrk03_dd = $wrk_arr[1];
			$tmp_a01 = explode(',', $lun_arr[$wrk03_mm][$wrk03_dd]);  // 각 출력일의 음력날자
			$tmp01 = date("w", mktime(0, 0, 0, $wrk_arr[0], $wrk_arr[1], $wrk_arr[2]));	// 요일
			$tmp02 = ceil(($tmp_a01[2] + 6 - $tmp01) / 7);  // n번째주
			// 음력상 반복월이며 같은번째주 같은요일
			if((($tmp_a01[0]*12 + $tmp_a01[1])-($wrk_a01[0]*12 + $wrk_a01[1])) % $plan_repeat_cycle == 0 && $wrk01 == $tmp01 && $wrk02 == $tmp02) {
				$aHoli[$wrk_arr[0]][$wrk_arr[1]] =  $wrk_arr[2];
			}
		  }
		}
		break;
		// 9.개월(날자-휴일이면다음근무일): 반복월 같은 날자, 단 토,일,휴일이면 다음 근무일(공과금 납부)**
		case '9':
		For	($x = $dsp_startMM_cnt; $x <= $dsp_endMM_cnt; $x++) {
			if(!(($x-$plan_startMM_cnt)%$plan_repeat_cycle)) {	// 해당월
				$wrkYY = floor(($x-1)/12);	// 년
				$wrkMM = ($x-1)%12 + 1;	// 월
				$wrkDD = $startDD;	// 일
				$wrk_jd = planner123_main::fn_calcDateToJD($wrkYY, $wrkMM, $wrkDD);// 일자 jd
				if($wrk_jd >= $plan_start_jd && $wrk_jd <= $plan_end_jd) {
					// $wrk_holiday = planner123_holiday::fn_HolidayChk($dispStart_stamp, $dispEnd_stamp); // V430에서 수정
					$wrk_holiday = $Holiday;
					for ($x_8 = 0; $x_8<7; $x_8++) {
						$wrk_stmp_8 = mktime(0, 0, 0, $wrkMM, $wrkDD+$x_8 ,$wrkYY);
						$wrkYY_8 = date("Y", $wrk_stmp_8);	// 일자-년
						$wrkMM_8 = date("n", $wrk_stmp_8);	// 일자-월
						$wrkDD_8 = date("j", $wrk_stmp_8);	// 일자-일
						$wrk_yoil_8 = date("w", $wrk_stmp_8);
						if($wrk_yoil_8 != 0 && $wrk_yoil_8 != 6 && !$wrk_holiday[$wrkMM_8][$wrkDD_8] ) {
							$aHoli[$wrkMM_8][$wrkDD_8] =  $wrkYY_8;
							break;
						}
					}
				}
			}
		}
		break;
		} // end switch
	} // unit, cycle 있을때 끝
}	// 기간에 포함될때 끝

	return $aHoli;
}

//------------------------------------------------------------------------
    /**
     * @brief XE에 설정된 타임존을 반영한 시간값을 구함
     * @param none,  XE함수 zgap() 사용
     * @return int
     **/
function fn_xetimestamp() {
    $localtimestamp = mktime(date("H"), date("i"), date("s")+zgap(), date("m"), date("d"), date("Y"));
    return $localtimestamp;
}

//------------------------------------------------------------------------
    /**
     * @brief Array sort by multi column
     * @param non  부를때만 예($sortArr = fn_array_orderby(array, 'column1', 'SORT_ASC','column2','SORT_DESC')
     * @return array
     **/
function fn_array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
            }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}

//------------------------------------------------------------------------
    /**
     * @brief Display small calendar
     * @param Year, month, day
     * @return string
     **/
function fn_smallcalendar_ymd($pYear, $pMonth, $pDay, $url, $ind_reservation, $manager, $document_srl, $extra_vars1){
	$today_stmp = mktime(0,0,0,date('m'),date('d'),date('Y'));
	$today_mm = date('m');
    $year = date("Y",mktime(0,0,0,$pMonth,1,$pYear));
    $month_mm = date("m",mktime(0,0,0,$pMonth,1,$pYear));
	if($month_mm == $today_mm) {
		$calendar_title = 'calendar_title_this_month';
	} else {
		$calendar_title = 'calendar_title';
	}
    $month = date("n",mktime(0,0,0,$pMonth,1,$pYear));
    if ($pDay != 0){
		$day = date("d",mktime(0,0,0,$pMonth,$pDay,$pYear));
    }
    $day_max = date("t",mktime(0,0,0,$month,1,$year));
    $week_start = date("w",mktime(0,0,0,$month,1,$year));

	$allow_chg_date = "";;
	if($ind_reservation != "N") {
		if($document_srl && $manager || $document_srl && $extra_vars1 > date('Ymd') || !$document_srl) {
			$allow_chg_date = "Y";;
		}
	}

    $i = 0;
    $j = 0;
    $html = "<div class='calendar_box'>\n<div class='$calendar_title'>".sprintf("%d-%02d",$year,$month)."</div>\n<ul>";
    while ($j<$day_max){
        if ($i<$week_start) {
            $html .= "<li class='calendar_text'>*</li>";
        } else {
            if ($i%7==0){
                $html .= "</ul><ul>\n";
                $font_color = " ssunday";
            }
            else if ($i%7==6) $font_color = " saterday";
            else $font_color = " normalday";
            if ($month==$pMonth and $day == ($j+1)) $font_weight = " stoday";
            else $font_weight = "";
            $html .= "<li class='calendar_text$font_color$font_weight'>";
			if ($url && $allow_chg_date == "Y") {
				$tmp_stmp = mktime(0,0,0,$month_mm,$j+1,$year);
				$day_dd = date('d', $tmp_stmp);
				if ($tmp_stmp >= $today_stmp){
					$html .= "<a class='".$font_color.$font_weight."' href=".$url."&extra_vars1=".$year.$month_mm.$day_dd.">".($j+1)."</a></li>";
				} else {
					$html .= ($j+1)."</li>";
				}
			} else {
				$html .= ($j+1)."</li>";
			}
            $j ++;
        }
        $i ++;
    }
    while ($i%7!==0){
        $html .= "<li class='calendar_text'>*</li>";
        $i ++;
    }
     $html .= "</ul>\n</div>\n";
    return $html;
}

//------------------------------------------------------------------------
    /**
     * @brief Get an array of file names in directory.
     * @param file path
     * @return array
     **/
// Get an array of file names in directory.
function fn_readFolderDirectory($dir) {
	$listDir = array();
	if($handler = opendir($dir)) {
		while (($sub = readdir($handler)) !== FALSE) {
			if ($sub != "." && $sub != ".." && $sub != "Thumb.db" && $sub != "Thumbs.db") {
				if(is_file($dir."/".$sub)) {
					$listDir[] = $sub;
                }
			}
		}
		closedir($handler);
	}
	sort($listDir);
	return $listDir;
}

//--------------------------------------------------------------------------------------
    /**
     * @function: fn_WeekOfYear($month, $day, $year)
     * @return  : integer
     * @brief:    당일의 년초부터 일요일 기준 주간 갯수구하기 : 1월 1일 금요일인 경우 1일-2일 =1주, 3일-9일=2주)
     **/
function fn_WeekOfYear($month, $day, $year) { // week count(일요일 부터)
	$day_of_year = date('z', mktime(0, 0, 0, $month, $day, $year));
	/* Days in the week before Jan 1. If you want weeks to start on Monday make this (x + 6) % 7 */
	$days_before_year = date('w', mktime(0, 0, 0, 1, 1, $year));
	$days_left_in_week = 7 - date('w', mktime(0, 0, 0, $month, $day, $year));
	/* find the number of weeks by adding the days in the week before the start of the year, days up to $day, and the days left in this week, then divide by 7 */
	return ($days_before_year + $day_of_year + $days_left_in_week) / 7;
}

//--------------------------------------------------------------------------------------
    /**
     * @function: fn_getTextFile($skin_path, $num = 2)
     * @return  : array
     * @brief:    text file 읽기. (경구 출력을 위하여...)
     **/
function fn_getTextFile($skin_path, $num = 2){
	$file_path = $skin_path."text/epigrams.txt";
	if(!file_exists($file_path)) return 'error1 '.$skin_path;
	$fp = fopen($file_path, "r");	// 파일 열기
	if(!$fp) return 'error2';
	while(!feof($fp)) $array[] = fgets($fp, 1024);	// 파일 읽기
	fclose($fp);	// 파일 닫기
	shuffle($array);	// 배열 섞기

	if(count($array) < $num) $num = count($array);
	for($i=0; $i<$num; $i++){	// 배열에서 2개글 뽑기
		$tmp = trim($array[$i]);
		if($tmp){
		$tmp_arr = explode('||', $tmp);
		$list[$i] = $tmp_arr[1];
		}
	}
	return $list;
}

//--------------------------------------------------------------------------------------
    /**
     * @function: fn_getClientOffsetTime()
     * @return  : string
     * @brief:    client의 timezone offset 값을 서버에 넘기기위한 작업
     **/
function fn_getClientOffsetTime(){
	parse_str($_SERVER['QUERY_STRING'], $query_srt);
	if ($query_srt[offset] == NULL) {
		$rurl = $_SERVER['QUERY_STRING'];  //참고: $rurl = urlencode($_SERVER['PHP_SELF']."?".$_SERVER['QUERY_STRING']);
		$html = "<script type='text/javascript'>";
		$html .= "function tz_offset() {";
		$html .= "var current_date = new Date( );";
		$html .= "var client_offset = current_date.getTimezoneOffset( )*60*(-1);";
		$html .= "location.href='?$rurl&offset=' + client_offset;";
		$html .= "}";
		$html .= "onload = tz_offset;";
		$html .= "</script>";
	}
	return $html;
}

//------------------------------------------------------------------------
    /**
     * @brief Get full name of holiday function file.
     * @param file path, country-ID
     * @return string
     **/
// Check holiday file is exist in directory and return full name of holiday function file.
function fn_getHolidayFileName($skinpath,$country_id) {
	$filename = "class.planner123_holiday_";
	$ind_01 = is_file($skinpath.$filename.$country_id.".php");
	if($ind_01) {
		$filename .= $country_id.".php";
	} else {
		$filename .= "default".".php";
	}
		return $filename;
}

//------------------------------------------------------------------------
    /**
     * @brief Get holiday data by country.
     * @param $skinpath, $country_id, $dispStart_stamp, $dispEnd_stamp
     * @return array
     **/
//  Get holiday data by country.
function fn_getHolidayByCountry($skinpath, $country_id, $dispStart_stamp, $dispEnd_stamp) {
	$filename = planner123_main::fn_getHolidayFileName($skinpath,$country_id);
	if (!class_exists('planner123_holiday_'.'$country_id')) {
		require_once ($skinpath.$filename);
	}
	if ($country_id == "kor") {
		$Holiday_arr = planner123_holiday_kor::fn_HolidayChk($dispStart_stamp, $dispEnd_stamp);// 휴일
	} else if ($country_id == "usa") {
		$Holiday_arr = planner123_holiday_usa::fn_HolidayChk($dispStart_stamp, $dispEnd_stamp);
	} else if ($country_id == "chn") {
		$Holiday_arr = planner123_holiday_chn::fn_HolidayChk($dispStart_stamp, $dispEnd_stamp);
	} else if ($country_id == "jpn") {
		$Holiday_arr = planner123_holiday_jpn::fn_HolidayChk($dispStart_stamp, $dispEnd_stamp);
	} else if ($country_id == "can") {
		$Holiday_arr = planner123_holiday_can::fn_HolidayChk($dispStart_stamp, $dispEnd_stamp);
	} else if ($country_id == "vnm") {
		$Holiday_arr = planner123_holiday_vnm::fn_HolidayChk($dispStart_stamp, $dispEnd_stamp);
	} else if ($country_id == "tur") {
		$Holiday_arr = planner123_holiday_tur::fn_HolidayChk($dispStart_stamp, $dispEnd_stamp);
	} else if ($country_id == "user") {
		$Holiday_arr = planner123_holiday_user::fn_HolidayChk($dispStart_stamp, $dispEnd_stamp);
	} else if ($country_id == "default") {
		$Holiday_arr = planner123_holiday_default::fn_HolidayChk($dispStart_stamp, $dispEnd_stamp);
	}
		return $Holiday_arr;
}

//------------------------------------------------------------------------
    /**
     * @brief Get memorialday data by countrry.
     * @param $skinpath, $country_id, $dispStart_stamp, $dispEnd_stamp
     * @return array
     **/
//  Get memorialday data by countrry.
function fn_getMemdayByCountry($skinpath, $country_id, $dispStart_stamp, $dispEnd_stamp) {
	$filename = planner123_main::fn_getHolidayFileName($skinpath,$country_id);
	if (!class_exists('planner123_holiday_'.'$country_id')) {
		require_once ($skinpath.$filename);
	}
	if ($country_id == "kor") {
		$Holiday_arr = planner123_holiday_kor::fn_MemdayChk($dispStart_stamp, $dispEnd_stamp);// 기념일
	} else if ($country_id == "usa") {
		$Holiday_arr = planner123_holiday_usa::fn_MemdayChk($dispStart_stamp, $dispEnd_stamp);
	} else if ($country_id == "chn") {
		$Holiday_arr = planner123_holiday_chn::fn_MemdayChk($dispStart_stamp, $dispEnd_stamp);
	} else if ($country_id == "jpn") {
		$Holiday_arr = planner123_holiday_jpn::fn_MemdayChk($dispStart_stamp, $dispEnd_stamp);
	} else if ($country_id == "can") {
		$Holiday_arr = planner123_holiday_can::fn_MemdayChk($dispStart_stamp, $dispEnd_stamp);
	} else if ($country_id == "vnm") {
		$Holiday_arr = planner123_holiday_vnm::fn_MemdayChk($dispStart_stamp, $dispEnd_stamp);
	} else if ($country_id == "tur") {
		$Holiday_arr = planner123_holiday_tur::fn_MemdayChk($dispStart_stamp, $dispEnd_stamp);
	} else if ($country_id == "user") {
		$Holiday_arr = planner123_holiday_user::fn_MemdayChk($dispStart_stamp, $dispEnd_stamp);
	} else if ($country_id == "default") {
		$Holiday_arr = planner123_holiday_default::fn_MemdayChk($dispStart_stamp, $dispEnd_stamp);
	}
		return $Holiday_arr;
}

//------------------------------------------------------------------------
    /**
     * @function: fn_term03_data()
     * @return  : array
     * @brief:    get 24terms delta-t
     **/
function fn_term03_data() {
	return "1791,2832,2498,2259,2167,2236,2497,2944,3578,2933,2419,3435,3046,2649,3622,3053,2326,2875,3225,3395,3377,3215,2921,2555,2143,3193,2851,2620,2519,2594,2846,3298,3925,3285,2767,3785,3396,2998,3975,3401,2682,3223,3581,3743,3733,3561,3275,2900,2497,3537,3204,2964,1431,1498,1758,2202,2838,2189,1681,2691,2311,1909,2891,2316,1598,2140,2495,2659,2645,2476,2185,1814,1407,2452,2115,1881,1785,1857,2114,2563,3194,2551,2033,3051,2660,2265,3237,2668,1941,2490,2839,3008,2989,2825,2530,2163,1753,2803,2464,2234,2136,2212,2467,2919,3548,2905,2389,3401,3015,2612,3591,3013,2296,2835,3195,3354,3347,3173,2889,2513,2111,3150,2818,2578,2487,2553,2814,3257,3893,3243,2733,3743,3359,2958,3936,3363,2642,3189,3542,3711,3696,3532,3239,2871,2461,3508,3167,2934,1393,1467,1719,2171,2798,2158,1639,2659,2268,1874,2846,2277,1552,2098,2450,2616,2602,2434,2143,1773,1365,2411,2072,1838,1740,1813,2069,2517,3150,2505,1994,3005,2624,2220,3202,2623,1906,2444,2803,2962,2953,2780,2494,2119,1718,2759,2427,2188,2096,2163,2423,2865,3499,2850,2336,3348,2961,2563,3537,2967,2242,2790,3141,3311,3293,3130,2837,2471,2061,3111,2770,2540,2438,2514,2764,3216,3840,3198,2678,3695,3305,2908,3884,3313,2593,3137,3495,3658,3647,3476,3187,2813,2407,3449,3113,2875,1341,1409,1668,2112,2747,2097,1587,2596,2216,1813,2797,2221,1505,2048,2406,2570,2558,2388,2099,1724,1318,2359,2022,1784,1689,1758,2015,2462,3094,2449,1933,2949,2559,2163,3135,2568,1842,2392,2743,2914,2897,2735,2441,2074,1662,2711,2369,2138,2035,2110,2361,2813,3440,2797,2280,3295,2907,2507,3485,2909,2192,2733,3094,3257,3251,3080,2797,2422,2020,3059,2725,2483,2388,2451,2709,3148,3782,3130,2620,3629,3247,2846,3827,3255,2537,3083,3440,3609,3597,3433,3144,2775,2367,3413,3074,2838,1297,1366,1617,2064,2689,2045,1525,2544,2153,1761,2735,2168,1445,1994,2347,2517,2502,2337,2046,1678,1269,2317,1977,1744,1644,1717,1969,2417,3045,2398,1883,2894,2510,2107,3090,2513,1799,2340,2702,2863,2857,2685,2401,2025,1624,2664,2333,2092,2001,2065,2325,2765,3398,2745,2231,3239,2852,2451,3427,2857,2135,2685,3040,3212,3198,3038,2746,2381,1971,3020,2679,2447,2345,2419,2668,3118,3742,3099,2576,3593,3200,2804,3778,3208,2487,3035,3393,3561,3551,3385,3097,2727,2320,3364,3026,2789,1251,1319,1575,2019,2651,2001,1490,2499,2118,1715,2698,2121,1406,1948,2309,2472,2465,2295,2010,1637,1233,2274,1940,1700,1605,1671,1928,2372,3004,2356,1841,2855,2466,2070,3043,2475,1749,2299,2650,2822,2805,2644,2351,1987,1577,2628,2286,2056,1953,2028,2278,2728,3352,2710,2190,3206,2817,2419,3397,2824,2106,2649,3009,3172,3165,2995,2710,2336,1934,2974,2640,2399,2304,2368,2625,3065,3698,3045,2534,3542,3162,2760,3744,3171,2457,3003,3363,3530,3520,3353,3064,2693,2285,3328,2989,2751,1212,1280,1533,1978,2605,1960,1441,2459,2069,1677,2652,2088,1365,1918,2272,2444,2429,2266,1973,1605,1193,2240,1896,1663,1559,1632,1882,2331,2957,2312,1796,2810,2425,2024,3007,2433,1720,2263,2627,2791,2786,2615,2332,1956,1554,2592,2258,2014,1919,1981,2238,2676,3308,2654,2141,3150,2765,2364,3344,2774,2055,2606,2964,3138,3127,2967,2678,2313,1904,2951,2610,2374,2270,2339,2586,3031,3653,3007,2484,3502,3110,2716,3691,3125,2405,2956,3315,3486,3476,3314,3026,2658,2251,3296,2956,2719,1177,1244,1494,1936,2563,1912,1397,2406,2024,1622,2607,2033,1321,1865,2229,2394,2389,2220,1937,1563,1162,2202,1868,1626,1532,1594,1851,2290,2920,2267,1750,2760,2371,1973,2948,2381,1659,2212,2567,2741,2727,2568,2276,1912,1502,2553,2211,1979,1876,1949,2197,2645,3267,2622,2098,3112,2719,2322,3297,2726,2008,2555,2917,3086,3080,2914,2630,2259,1855,2897,2560,2320,2222,2286,2540,2979,3609,2955,2441,3448,3065,2661,3645,3070,2357,2903,3266,3435,3429,3264,2980,2609,2205,3246,2909,2668,1129,1193,1446,1888,2515,1866,1347,2362,1972,1578,2551,1986,1262,1815,2169,2343,2329,2170,1878,1514,1103,2152,1809,1576,1471,1543,1790,2238,2861,2216,1697,2711,2324,1925,2905,2332,1617,2161,2523,2688,2683,2513,2231,1857,1456,2496,2163,1921,1826,1887,2143,2580,3210,2554,2041,3047,2664,2262,3243,2672,1956,2505,2865,3036,3026,2864,2576,2209,1802,2848,2508,2272,2170,2237,2486,2930,3552,2904,2381,3397,3005,2612,3587,3024,2304,2858,3215,3389,3377,3215,2924,2557,2146,3192,2849,2613,1069,1137,1386,1831,2456,1807,1290,2301,1918,1517,2503,1930,1220,1766,2132,2298,2294,2125,1842,1466,1063,2101,1765,1520,1424,1485,1741,2179,2810,2157,1642,2652,2266,1866,2845,2277,1559,2112,2470,2646,2635,2476,2186,1821,1411,2458,2115,1879,1773,1843,2088,2534,3155,2510,1986,3003,2611,2217,3192,2625,1908,2459,2821,2993,2988,2826,2542,2173,1767,2810,2470,2229,2126,2188,2437,2875,3501,2846,2331,3339,2958,2556,3543,2971,2262,2809,3176,3345,3343,3178,2897,2526,2123,3164,2827,2583,1044,1103,1354,1790,2416,1763,1244,2256,1868,1474,2451,1888,1169,1725,2082,2259,2246,2089,1797,1434,1023,2073,1729,1496,1390,1460,1705,2150,2769,2122,1599,2613,2223,1826,2805,2236,1523,2072,2438,2607,2604,2437,2156,1784,1382,2423,2088,1846,1749,1810,2063,2499,3126,2468,1952,2956,2571,2167,3150,2578,1866,2416,2781,2955,2951,2790,2506,2139,1734,2778,2440,2200,2098,2162,2411,2851,3473,2822,2299,3312,2918,2524,3498,2935,2215,2771,3130,3308,3298,3141,2852,2489,2079,3127,2783,2547,1000,1068,1314,1757,2379,1730,1210,2222,1836,1435,2418,1846,1135,1681,2048,2216,2214,2047,1767,1394,994,2033,1699,1454,1358,1417,1671,2106,2736,2080,1565,2572,2186,1785,2765,2195,1478,2029,2389,2563,2554,2395,2107,1743,1336,2384,2043,1808,1704,1772,2018,2462,3081,2433,1908,2924,2530,2137,3111,2546,1827,2380,2740,2914,2907,2746,2460,2093,1686,2731,2390,2151,2048,2112,2360,2799,3423,2769,2251,3258,2875,2474,3460,2889,2181,2728,3097,3265,3264,3097,2816,2442,2040,3078,2742,2496,957,1016,1269,1704,2332,1677,1160,2170,1783,1387,2366,1802,1085,1641,2000,2178,2166,2008,1717,1353,941,1988,1642,1407,1299,1368,1612,2057,2676,2030,1506,2522,2131,1736,2715,2148,1434,1985,2351,2523,2519,2356,2073,1702,1298,2339,2000,1757,1655,1715,1964,2399,3024,2367,1851,2856,2473,2069,3055,2483,1773,2323,2691,2864,2863,2702,2421,2053,1650,2692,2353,2109,2006,2065,2312,2748,3369,2715,2192,3204,2813,2420,3397,2836,2118,2676,3036,3216,3206,3051,2762,2400,1989,3038,2692,2456,907,973,1215,1656,2274,1623,1100,2112,1724,1327,2311,1742,1033,1583,1952,2122,2121,1955,1675,1303,902,1941,1605,1361,1262,1320,1572,2005,2632,1972,1456,2459,2074,1672,2654,2085,1372,1925,2290,2466,2461,2302,2017,1651,1245,2291,1950,1712,1608,1673,1919,2359,2978,2327,1800,2814,2419,2024,2999,2435,1717,2275,2637,2816,2810,2654,2368,2004,1595,2641,2297,2058,1951,2015,2258,2697,3318,2664,2143,3151,2765,2364,3350,2778,2071,2620,2992,3163,3165,3000,2722,2350,1950,2988,2651,2404,864,920,1171,1603,2230,1572,1055,2063,1678,1279,2260,1694,979,1535,1895,2074,2065,1909,1622,1259,850,1898,1554,1318,1210,1276,1518,1961,2578,1930,1404,2420,2028,1634,2612,2047,1332,1886,2250,2424,2420,2259,1976,1608,1204,2248,1909,1668,1564,1625,1872,2307,2929,2271,1752,2756,2373,1970,2957,2385,1678,2228,2599,2771,2771,2609,2329,1959,1558,2598,2262,2017,1916,1974,2223,2656,3278,2622,2100,3109,2719,2325,3304,2743,2027,2588,2949,3131,3122,2966,2677,2314,1902,2950,2603,2366,816,882,1123,1565,2182,1533,1008,2022,1632,1237,2219,1654,945,1498,1868,2041,2042,1878,1597,1225,822,1861,1522,1276,1174,1232,1482,1915,2541,1882,1366,2370,1986,1583,2568,1998,1289,1842,2210,2387,2386,2227,1945,1579,1174,2217,1877,1634,1529,1589,1834,2270,2889,2236,1711,2724,2331,1938,2913,2352,1635,2195,2557,2740,2734,2581,2296,1935,1526,2573,2227,1988,1877,1939,2178,2616,3232,2578,2054,3064,2677,2279,3265,2697,1991,2543,2916,3088,3092,2929,2652,2281,1882,2921,2584,2337,796,849,1098,1527,2151,1489,971,1976,1592,1192,2176,1611,899,1456,1821,2000,1995,1838,1553,1189,782,1828,1486,1247,1140,1204,1446,1886,2501,1850,1322,2335,1941,1548,2524,1962,1247,1806,2171,2349,2346,2189,1905,1540,1134,2179,1837,1597,1491,1552,1796,2231,2850,2192,1669,2673,2287,1883,2869,2297,1592,2143,2516,2690,2695,2534,2257,1888,1488,2527,2190,1943,1841,1896,2144,2575,3197,2537,2016,3022,2633,2235,3214,2652,1937,2498,2861,3043,3037,2884,2597,2236,1826,2874,2527,2289,737,802,1040,1481,2095,1445,919,1933,1541,1147,2127,1562,851,1406,1774,1949,1949,1788,1508,1139,736,1778,1438,1194,1090,1148,1394,1826,2449,1789,1271,2275,1891,1488,2474,1903,1195,1746,2116,2291,2291,2131,1851,1483,1081,2123,1785,1541,1438,1496,1741,2174,2793,2137,1612,2622,2230,1836,2814,2253,1537,2099,2461,2644,2637,2484,2197,1835,1425,2472,2125,1886,1774,1838,2076,2514,3128,2475,1948,2959,2571,2174,3160,2595,1890,2444,2818,2993,2996,2834,2555,2183,1781,2819,2480,2231,688,741,988,1417,2041,1379,862,1866,1482,1082,2068,1503,795,1352,1721,1901,1899,1742,1458,1092,685,1728,1384,1141,1032,1092,1333,1770,2386,1733,1206,2220,1827,1435,2412,1853,1139,1701,2067,2250,2247,2094,1810,1447,1039,2085,1740,1498,1387,1446,1685,2118,2733,2076,1551,2557,2171,1770,2757,2188,1485,2038,2414,2590,2598,2438,2164,1796,1397,2436,2099,1849,1745,1796,2041,2467,3087,2423,1902,2906,2519,2121,3104,2543,1833,2395,2762,2946,2942,2790,2506,2145,1737,2785,2439,2199,648,709,946,1382,1994,1341,811,1824,1430,1038,2018,1458,748,1308,1678,1858,1858,1701,1421,1055,651,1694,1353,1110,1004,1062,1305,1737,2356,1694,1172,2173,1787,1383,2370,1800,1095,1649,2023,2200,2205,2047,1770,1403,1003,2044,1706,1460,1358,1413,1659,2089,2708,2048,1523,2529,2136,1740,2717,2156,1442,2005,2370,2557,2554,2404,2120,1761,1351,2399,2052,1813,1699,1761,1997,2435,3047,2393,1865,2876,2484,2088,3070,2506,1799,2356,2730,2907,2912,2754,2477,2109,1708,2748,2409,2161,616,669,914,1342,1964,1302,783,1787,1403,1002,1988,1420,713,1268,1639,1817,1818,1661,1381,1016,612,1656,1315,1071,965,1022,1265,1698,2314,1659,1132,2144,1751,1359,2337,1778,1063,1625,1989,2172,2168,2016,1731,1370,962,2010,1665,1426,1314,1375,1612,2047,2660,2002,1475,2483,2094,1695,2681,2115,1411,1966,2342,2517,2524,2363,2088,1718,1318,2356,2019,1770,1667,1718,1964,2390,3010,2346,1825,2828,2443,2044,3029,2467,1760,2321,2691,2874,2872,2718,2433,2069,1660,2705,2358,2116,564,624,862,1298,1910,1257,728,1742,1349,958,1937,1380,669,1232,1602,1785,1785,1630,1348,982,575,1617,1271,1027,916,973,1213,1645,2262,1602,1079,2084,1698,1296,2284,1715,1013,1567,1944,2121,2129,1970,1696,1327,928,1966,1627,1377,1272,1322,1566,1992,2610,1947,1424,2430,2040,1644,2625,2065,1354,1918,2286,2474,2472,2324,2040,1682,1273,2320,1971,1729,1613,1671,1904,2337,2945,2290,1759,2770,2378,1986,2969,2409,1704,2265,2639,2820,2825,2669,2392,2025,1623,2664,2322,2075,526,578,819,1244,1861,1196,674,1676,1292,891,1880,1314,611,1168,1544,1724,1728,1572,1294,927,525,1567,1227,980,874,928,1169,1598,2213,1553,1025,2033,1639,1245,2223,1666,953,1519,1887,2075,2073,1924,1641,1282,873,1921,1574,1334,1219,1279,1512,1946,2555,1897,1366,2372,1980,1581,2565,2000,1297,1855,2233,2413,2423,2266,1994,1627,1228,2267,1928,1678,1572,1621,1864,2288,2906,2240,1718,2718,2333,1931,2917,2352,1647,2208,2581,2765,2767,2615,2336,1973,1568,2612,2268,2023,472,528,765,1196,1808,1152,622,1634,1240,848,1827,1270,558,1122,1491,1677,1677,1525,1244,883,476,1522,1177,935,822,880,1117,1549,2161,1501,975,1979,1592,1190,2178,1610,907,1462,1840,2017,2025,1866,1593,1225,828,1867,1530,1281,1177,1228,1471,1896,2514,1848,1324,2327,1939,1541,2524,1963,1255,1819,2189,2376,2375,2226,1942,1582,1174,2220,1872,1630,1516,1574,1808,2241,2850,2194,1662,2674,2281,1889,2871,2314,1608,2173,2547,2731,2735,2581,2302,1936,1531,2572,2227,1980,429,483,722,1149,1766,1103,580,1583,1200,798,1788,1222,522,1080,1458,1638,1646,1489,1214,845,444,1482,1141,891,784,834,1076,1502,2119,1457,932,1939,1549,1155,2136,1579,868,1435,1805,1994,1994,1847,1564,1207,798,1846,1496,1254,1137,1194,1424,1856,2463,1805,1273,2282,1890,1495,2479,1918,1215,1777,2155,2338,2348,2194,1921,1556,1157,2197,1857,1606,1497,1545,1784,2206,2821,2152,1629,2629,2245,1844,2834,2271,1570,2131,2508,2692,2697,2544,2267,1903,1500,2543,2200,1953,402,455,691,1119,1730,1069,538,1547,1153,762,1743,1188,479,1047,1418,1607,1608,1459,1177,817,409,1456,1108,867,752,810,1044,1475,2084,1424,893,1897,1506,1106,2092,1527,826,1384,1765,1945,1956,1800,1528,1161,763,1802,1464,1213,1107,1156,1398,1820,2437,1769,1244,2244,1856,1454,2439,1876,1171,1735,2109,2297,2301,2153,1874,1514,1107,2152,1805,1560,1444,1499,1732,2162,2770,2112,1579,2590,2195,1804,2784,2228,1520,2086,2460,2648,2653,2503,2225,1863,1458,2502,2156,1909,355,408,643,1070,1682,1019,493,1496,1111,710,1699,1133,432,989,1369,1548,1558,1401,1128,761,362,1401,1062,811,705,753,994,1417,2032,1367,841,1846,1456,1060,2043,1485,776,1343,1713,1902,1902,1754,1472,1114,706,1755,1407,1165,1048,1105,1335,1765,2370,1711,1176,2185,1791,1397,2380,1822,1118,1683,2061,2246,2254,2101,1826,1462,1060,2100,1758,1508,1397,1447,1684,2106,2720,2051,1526,2526,2141,1740,2731,2167,1469,2031,2411,2595,2603,2449,2173,1807,1404,2443,2100,1849,298,348,585,1011,1623,960,431,1439,1046,654,1636,1082,374,944,1316,1508,1510,1364,1082,723,314,1360,1009,766,647,703,933,1364,1970,1311,778,1785,1393,995,1981,1418,717,1278,1659,1843,1856,1702,1432,1066,668,1707,1367,1115,1006,1052,1290,1709,2323,1653,1129,2128,1742,1341,2329,1766,1064,1628,2006,2194,2202,2054,1778,1418,1014,2058,1712,1465,1349,1400,1631,2057,2663,2001,1467,2476,2081,1691,2673,2120,1414,1984,2358,2550,2554,2407,2128,1769,1363,2409,2062,1817,260,314,545,971,1579,915,385,1388,1000,600,1590,1026,328,888,1271,1453,1465,1310,1038,671,273,1311,973,721,614,661,902,1323,1938,1269,743,1743,1354,955,1940,1381,676,1243,1618,1809,1813,1667,1388,1030,624,1671,1323,1079,962,1016,1246,1675,2279,1618,1082,2091,1694,1301,2282,1725,1021,1588,1967,2156,2166,2018,1743,1382,980,2023,1678,1429,1315,1364,1598,2021,2632,1964,1438,2437,2052,1650,2641,2077,1379,1940,2322,2506,2518,2365,2093,1727,1328,2366,2025,1773,223,270,507,929,1541,876,348,1354,963,570,1552,998,290,860,1233,1425,1427,1282,1000,644,235,1283,933,691,572,628,857,1286,1890,1230,696,1703,1310,915,1899,1340,638,1201,1581,1766,1777,1624,1352,987,588,1628,1288,1037,927,975,1212,1632,2245,1574,1048,2047,1661,1260,2250,1688,989,1553,1934,2122,2131,1981,1705,1342,938,1979,1634,1383,1269,1318,1551,1975,2582,1918,1386,2394,2000,1610,2592,2041,1336,1909,2285,2479,2484,2338,2058,1699,1289,2334,1983,1736,176,229,457,885,1491,829,298,1303,914,516,1505,944,247,810,1195,1379,1393,1239,969,602,203,1239,898,643,533,577,814,1233,1846,1176,651,1651,1265,866,1853,1294,592,1160,1538,1730,1738,1593,1316,959,553,1598,1250,1002,883,933,1159,1584,2185,1522,985,1993,1597,1206,2188,1635,932,1503,1882,2075,2085,1940,1666,1307,904,1949,1602,1354,1235,1284,1512,1933,2538,1868,1338,2337,1950,1550,2542,1981,1286,1849,2235,2420,2435,2282,2012,1647,1249,2287,1946,1692,142,186,422,839,1449,779,249,1250,859,464,1448,894,191,763,1139,1334,1339,1196,916,560,152,1199,850,606,486,541,768,1195,1796,1134,596,1601,1204,809,1791,1233,531,1099,1480,1670,1683,1535,1264,902,502,1544,1201,951,838,885,1119,1538,2148,1476,948,1944,1556,1152,2142,1578,880,1444,1828,2017,2031,1883,1612,1249,849,1889,1545,1293,1179,1224,1457,1877,2484,1817,1285,2290,1896,1504,2486,1933,1228,1801,2176,2372,2378,2235,1957,1601,1194,2241,1890,1645,84,136,362,789,1391,729,195,1201,809,413,1400,840,141,704,1088,1273,1286,1134,864,499,101,1140,800,547,437,481,717,1135,1747,1075,549,1547,1162,761,1751,1191,491,1058,1437,1627,1636,1489,1213,853,450,1493,1148,899,782,831,1060,1483,2085,1420,884,1890,1495,1104,2086,1536,832,1406,1784,1980,1988,1844,1567,1209,802,1847,1497,1250,1130,1180,1407,1830,2434,1767,1235,2236,1848,1450,2442,1882,1189,1754,2142,2329,2345,2192,1922,1555,1155,2190,1848,1591,39,81,317,733,1345,674,147,1148,760,364,1352,797,97,670,1049,1245,1253,1110,832,475,67,1112,761,513,391,442,667,1092,1693,1030,492,1500,1104,712,1695,1141,439,1011,1393,1587,1600,1455,1184,825,424,1466,1120,869,751,797,1025,1443,2048,1376,846,1843,1457,1055,2048,1487,793,1359,1746,1935,1953,1804,1536,1173,775,1813,1471,1215,1101,1142,1373,1788,2394,1722,1190,2192,1800,1408,2393,1843,1142,1718,2097,2295,2303,2162,1885,1530,1123,2170,1819,1573,11,62,285,710,1308,644,106,1111,717,322,1308,751,54,622,1008,1198,1214,1064,795,432,33,1073,731,478,365,409,643,1059,1669,995,466,1461,1074,672,1662,1101,405,972,1357,1549,1563,1417,1146,787";   // 분: 1902-2037
}

//------------------------------------------------------------------------
    /**
     * @function: fn_get_term24($pYear)
     * @return  : array
     * @brief:    get date of 24terms
     **/
function fn_get_term24($pYear) {
    $aHoli = NULL;
	static $terms_name = array
    ('소한','대한','입춘','우수','경칩','춘분','청명','곡우','입하','소만','망종','하지',
	 '소서','대서','입추','처서','백로','추분','한로','상강','입동','소설','대설','동지'); //절기이름
	$arr_term00 = array
    ('0105','0119','0203','0218','0305','0320','0404','0419','0504','0520','0605','0620',
	 '0706','0722','0806','0822','0907','0922','1007','1022','1106','1121','1206','1221'); //기준월일
	$arr_term03 = explode(",",planner123_main::fn_term03_data());
	$j = ($pYear-1902)*24;
	for($k=$j, $t=0; $t<24; $k++, $t++){
		$term_stamp =  mktime(0, $arr_term03[$k], 0, substr($arr_term00[$t], 0, 2), substr($arr_term00[$t], -2), $pYear);
		$id_mm= date("n",$term_stamp+date('Z')+zgap());
		$id_dd= date("j",$term_stamp+date('Z')+zgap());
		$aHoli[$id_mm][$id_dd] .= $terms_name[$t];
	}
	return $aHoli;
}

//------------------------------------------------------------------------
    /**
     * @function: fn_get_dongji($pYear)
     * @return  : int
     * @brief:    get dongji time stamp
     **/
function fn_get_dongji($pYear) {
	if($pYear >=1903 && $pYear <=2037) {
		$arr_term03 = explode(",",planner123_main::fn_term03_data());
		$j = ($pYear-1902)*24+23;
		$out = mktime(0, $arr_term03[$j], 0, 12, 21, $pYear) + date('Z') + zgap();
	}
	return $out;
}

/* ---------------------------------------------------------------------------------------------------- */
	/**
     * @function: fn_GregorianToIslamic_ksc($GMonth, $GDay, $GYear)
	 * @return: string (YYYY-MM-DD-월이름)
	 * @brief: Convert from Gregorian to Islamic date. - (합삭 다음을 1일로하여 계산, 즉 음력보다 하루 늦음)
	 * @이슬람력: 태음력. 무함마드가 메카에서 메디나로 이주한 '거룩한 도망'을 히지라(Hijri)라고 하며,
	 * @그 날짜인 622년 7월 16일을 히지라 원년 1월 1일로 삼았다.
	 **/
function fn_GregorianToIslamic_ksc($GMonth, $GDay, $GYear) {
	$pYear = date("Y", mktime(0, 0, 0, $GMonth, $GDay-1, $GYear));
	$pMonth = date("n", mktime(0, 0, 0, $GMonth, $GDay-1, $GYear));
	$pDay = date("j", mktime(0, 0, 0, $GMonth, $GDay-1, $GYear));
	$lunar_day = planner123_main::fn_sol2lun_kr($pYear,$pMonth,$pDay);
	$arr_lunar_day = explode(",", $lunar_day);
	$HDay = $arr_lunar_day[2];

	$TDays = floor(($GYear-622)*365.2564)+198-58.3759;  // 622년 (+139.6241은 년도 조정을 위한 숫자 ->(7/16?))
	$TDays += (mktime(0, 0, 0, $GMonth, $GDay, $GYear)-mktime(0, 0, 0, 1, 1, $GYear))/(60*60*24);
	$Tdays_yearbase = $TDays;
	$Tdays_monthbase = $TDays+29.6; //(+29.6 : 월 조정을위한 숫자-> 1달?)

	$HMonths = floor( $Tdays_monthbase / 29.530588 );
	$HDay = $arr_lunar_day[2];
	if ($HDay<=15) {
		$HMon = floor(($Tdays_monthbase+10)/29.530588) % 12 ;
	} else {
		$HMon = floor(($Tdays_monthbase-10)/29.530588 ) % 12 ;
	}
	if($HMon == 0) {
		$HMon=12;
	}

	if($HMon == 1 && $HDay<=15) {
		$HYear = floor( floor(($Tdays_yearbase+30)/29.530588) / 12);
	}elseif($HMon == 12 && $HDay>=15){
		$HYear = floor( floor(($Tdays_yearbase-30)/29.530588) / 12);
	}else{
		$HYear = floor($HMonths / 12);
	}

	$Hmonthname = array("Muharram","Safar","Rabi`ul Awal","Rabi`ul Akhir","Jamadil Awal","Jamadil Akhir","Rajab","Sha`ban","Ramadhan","Shawwal","Zul Qida","Zul Hijja");
	$islamic_day .= $HYear."-".$HMon."-".$arr_lunar_day[2]."-".$Hmonthname[$HMon-1].",";

	return $islamic_day;
}

/* ---------------------------------------------------------------------------------------------------- */
	/**
     * @function:  fn_getIslamic_ary($dispStart_stamp, $dispEnd_stamp)
	 * @return: array
	 * @brief: get one month Islamic date.
	 **/
function fn_getIslamic_ary($dispStart_stamp, $dispEnd_stamp) {
	if(!$dispEnd_stamp){ $dispEnd_stamp = $dispStart_stamp; }
    for ($x=$dispStart_stamp; $x <= $dispEnd_stamp; $x +=86400 ) {   //당월만
		$pYear = date("Y", $x);
		$pMonth = date("n", $x);
		$pDay = date("j", $x);
		$out_array[$pMonth][$pDay] = planner123_main::fn_GregorianToIslamic_ksc($pMonth, $pDay, $pYear);
	}
	return $out_array;
}

function test_GregorianToIslamic($GMonth, $GDay, $GYear) {
	$in_y = date("Y", mktime(0, 0, 0, $GMonth, $GDay, $GYear));
	$in_m = date("n", mktime(0, 0, 0, $GMonth, $GDay, $GYear));
	$in_d = date("j", mktime(0, 0, 0, $GMonth, $GDay, $GYear));

	for($i=0; $i<365; $i++){
	$y = date("Y", mktime(0, 0, 0, $in_m, $in_d+$i, $in_y));
	$m = date("n", mktime(0, 0, 0, $in_m, $in_d+$i, $in_y));
	$d = date("j", mktime(0, 0, 0, $in_m, $in_d+$i, $in_y));
		$out .= "(".$y."-".$m."-".$d.") = ".planner123_main::fn_GregorianToIslamic_ksc($m, $d, $y)."<br />";
	}
	return $out;
}

/* ---------------------------------------------------------------------------------------------------- */

	/**
     * @function:  fn_calcDateToJD( $year, $month, $day, $hours=0, $mins=0, $secs=0 )
	 * @return: int
	 * @brief: get juliandays: php에서 지원되는 함수이나 PHP컴파일시 calender관련 함수들을 누락시킨 경우를 위해...
	 * @input paramater $year, $month, $day, $hours=0, $mins=0, $secs=0
	 **/
function fn_calcDateToJD( $year, $month, $day, $hours=0, $mins=0, $secs=0 ) {
	$a = 0;
	$b = 0;
	$jd = 0;
	$temp = $year + $month/100.0 + $day/10000.0;

	if ($month <= 2) {
		$month += 12;
		$year  -= 1;
	}

	if ($temp >= 1582.1015) {/* gregorian correction */
		$a = (int)($year / 100);
		$b = 2 - $a + floor($a / 4);
	}

	$jd  = floor(365.25*$year) + floor(30.6001*($month + 1)) + $day + $hours/24.0 + $mins / 1440.0 + $secs / 86400.0;
	return ($jd + 1720994.5 + $b) + 0.5; //PHP함수와 값을 맞추기위해 0.5추가
}
/* ---------------------------------------------------------------------------------------------------- */

	/**
     * @function:  fn_calcJDToGregorian($julian)
	 * @return: int
	 * @brief: juliandays를 날자로 변환: php에서 지원되는 함수이나 PHP컴파일시 calender관련 함수를을 누락시킨 경우를 위해...
	 * @input paramater juliandays
	 **/
function fn_calcJDToGregorian($julian) {
	$z = $julian;
	$f =  .5;
	$f = 0.0; // PHP함수와 결과값 맞추기 위해 0.5일 조정함
	if($z < 2299161) {
        $a = $z;
        } else {
        $alpha = floor(($z - 1867216.25) / 36524.25);
        $a = $z + 1 + $alpha - floor($alpha / 4);
    }
	$b = $a + 1524;
	$c = floor( ($b - 122.1) / 365.25);
	$d = floor( 365.25 * $c);
	$e = floor( ($b - $d) / 30.6001);
	$dd = floor( $b - $d - floor(30.6001 * $e) + $f);

	if($e < 13.5) {
        $mm = $e - 1;
    } else {
        $mm = $e - 13;
	}

	if($mm < 2.5 ) {
        $yy = $c - 4715;
    } else {
        $yy = $c - 4716;
    }

	return "$mm/$dd/$yy";
}
/* ---------------------------------------------------------------------------------------------------- */
	/**
     * @function:  fn_get_USER_AGENT()
	 * @return: string
	 * @brief: get IE version.
	 **/
function fn_get_USER_AGENT(){
	if ( stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 6.') )  {
		return ('IE6'); }

	elseif ( stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 7.') ) {
		return ('IE7'); }

	elseif ( stristr($_SERVER['HTTP_USER_AGENT'], 'MSIE 8.') ) {
		return ('IE8'); }

	else {
		return (NULL); }
}

/* ---------------------------------------------------------------------------------------------------- */
	/**
     * @function:  fn_install_extra_keys($module_srl)
	 * @return: true/false
	 * @brief: install extra keys for plannerXe123.
	 **/
function fn_install_extra_keys($module_srl){
	$args->module_srl = $module_srl;
	$args->var_idx = 1;
    $output = executeQuery('document.getDocumentExtraKeys', $args);
	if($output->data->module_srl){
		return false; //extra key exist
	} else {
	$tmp_arr[0] = array($module_srl,1,'일정시작','date','Y','N',NULL,'<font color=red ><b>날자는 필수, 시간은 선택항목입니다.</b></font>','ext_plan_start');
	$tmp_arr[1] = array($module_srl,2,'일정종료','date','N','N',NULL,'선택항목: 당일로 종료되는 일정은 넣지 않아도 됩니다.(지울때는 Ctrl + End)','ext_plan_end');
 	$tmp_arr[2] = array($module_srl,3,'배경색상','text','N','N','#77CC00','선택항목: 일정배경에 하이라이트 표시','ext_plan_color');
 	$tmp_arr[3] = array($module_srl,4,'일정확인','checkbox','N','N',NULL,'선택항목: 일정앞에 아이콘 표시.(일정확인, 중요도 분류등의 용도로 사용.)','ext_plan_icon');
 	$tmp_arr[4] = array($module_srl,5,'반복주기','select','N','N',',1,2,3,4,5,6,7,8,9,10,12,14,15,21,24,28','선택항목: 반복일정의 주기(cycle)','ext_plan_cycle');
 	$tmp_arr[5] = array($module_srl,6,'반복단위','select','N','N',',1.일(단위),2.월(같은날),3.월(n번째요일),4.월(n주차 x요일),5.월(말일),6.월(말일부터 n번째요일),7.월(음력: 같은날자),8.월(음력: n주차 x요일),9.월(같은날:세금납부-토요일 일요일이면 다음날)','선택항목: 반복주기가 적용될 단위.<br/> (예: 일주일 = 7+일(단위), 격주=14+일(단위), 분기=3+월(같은날)... )','ext_plan_unit');
 	$tmp_arr[6] = array($module_srl,7,'선택시간','checkbox','N','N',NULL,'선택항목: 필요시 시작과 종료 시간을 선택합니다.','ext_plan_time');
 	$tmp_arr[7] = array($module_srl,8,'공개그룹','checkbox','N','N',NULL,'선택사항: 일정을 공개할 그룹을 선택합니다.','ext_plan_group');
	foreach($tmp_arr as $key => $value) {
		$args->module_srl = $value[0];
		$args->var_idx = $value[1];
		$args->var_name = $value[2];
		$args->var_type = $value[3];
		$args->var_is_required = $value[4];
		$args->var_search = $value[5];
		$args->var_default = $value[6];
		$args->var_desc = $value[7];
		$args->eid = $value[8];
        $output = executeQuery('document.insertDocumentExtraKey', $args);
	}
	return true;
	}
}

//------------------------------------------------------------------------
    /**
     * @function: fn_sol2lun_kr_period($dispStart_stamp,$dispEnd_stamp)
     * @return  : array
     * @brief:    특정기간의 월별 양력 일자에 대응되는 음력일자 어레이 리턴 - [월][일]
     **/
Function fn_sol2lun_kr_period($dispStart_stamp,$dispEnd_stamp) {
    /******************************************************
    *특정기간의 월별 양력 일자에 대응되는 음력일자 (예:2009,7,15,윤달)
    *******************************************************/
    $aHoli = NULL;
	$sYear = date("Y", $dispStart_stamp);
	$sMonth = date("m", $dispStart_stamp);
    $lun_arr[$sYear] = planner123_main::fn_sol2lun_kr($sYear);
	if($dispEnd_stamp){ 
		$eYear = date("Y", $dispEnd_stamp);
		$eMonth = date("m", $dispEnd_stamp);
		$lun_arr[$eYear] = planner123_main::fn_sol2lun_kr($eYear);
	} else {
		$eYear = $sYear;
		$eMonth = $sMonth;
	}
	foreach($lun_arr as $key_yy => $val1) {
        if ($key_yy >= $sYear && $key_yy <= $eYear) {
			foreach($val1 as $key_mm => $val2) {
				$tem_mm = substr("0".$key_mm, -2);
				if ($key_yy."-".$tem_mm >= $sYear."-".$sMonth && $key_yy."-".$tem_mm <= $eYear."-".$eMonth) {
					$aHoli[$key_mm] = $val2;
				}
			}
		}
    }
    return $aHoli;
}

//------------------------------------------------------------------------
    /**
     * @function: fn_sol2lun_kr($pYear, $pMonth=NULL, $pDay=NULL)
     * @return  : array
     * @brief:    conversion solar to lunar (2014-7-1: 새로작성)
     * @brief:    양력->음력 (한국음력)
     **/
Function fn_sol2lun_kr($pYear, $pMonth = NULL, $pDay = NULL) {
	if ($pYear < 1882 || $pYear > 2050) {
		return false;
	}
	$days_arr = planner123_main::fn_MonthlyDays($pYear);
	$moon = planner123_main::fn_sol2lun_base_kr($pYear);
	foreach($moon as $key => $value) {
		$tmp_arr = explode(",", $value);
		$s_yy = $tmp_arr[0];
		$s_mm = $tmp_arr[1];
		$s_dd = $tmp_arr[2];
		$mos_end = $days_arr[$tmp_arr[1]];
		for($i = 1; $i <= $tmp_arr[3]; $i++){
			$out[$s_yy][$s_mm][$s_dd] = $tmp_arr[4].",".$tmp_arr[5].",".$i.",".$tmp_arr[7];
			$s_dd++;
			if($s_dd > $mos_end) {
				$s_mm++;
				$s_dd = 1;
				$mos_end = $days_arr[$s_mm];
				if($s_mm > 12) {
					$s_yy++;
					$s_mm = 1;
					if($s_yy == 0) {
						$s_yy = 1;
					}
					$mos_end = $days_arr[$s_mm];
				}
			}
		}
	}
	if(!$pMonth && !$pDay) {
		return $out[$pYear];
	} elseif($pMonth && !$pDay) {
		return $out[$pYear][$pMonth];
	} else {
		return $out[$pYear][$pMonth][$pDay];
	}
}

//------------------------------------------------------------------------

    /**
     * @function: fn_lun2sol_kr($pYear, $pMonth=NULL, $pDay=NULL, $pYoun=NULL)
     * @return  : array
     * @brief:    conversion lunar to solar(2014-7-1: 새로작성)
     * @brief:    음력->양력 (한국음력)
     **/
Function fn_lun2sol_kr($pYear, $pMonth = NULL, $pDay = NULL, $pYoun = NULL) {
	if ($pYear < 1882 || $pYear > 2050) {
		return false;
	}
	$days_arr = planner123_main::fn_MonthlyDays($pYear);
	$moon = planner123_main::fn_sol2lun_base_kr($pYear);
	$leap_label = "윤달";
	foreach($moon as $key => $value) {
		$tmp_arr = explode(",", $value);
		$s_yy = $tmp_arr[0];
		$s_mm = $tmp_arr[1];
		$s_dd = $tmp_arr[2];
		$mos_end = $days_arr[$tmp_arr[1]];
		for($i = 1; $i <= $tmp_arr[3]; $i++){
			if($tmp_arr[7] != '윤달') {
				$out[$tmp_arr[4]][$tmp_arr[5]]['p'][$i] = $s_yy.",".$s_mm.",".$s_dd.",";
			} else {
				$out[$tmp_arr[4]][$tmp_arr[5]]['y'][$i] = $s_yy.",".$s_mm.",".$s_dd.",".$leap_label;
			}
			$s_dd++;
			if($s_dd > $mos_end) {
				$s_mm++;
				$s_dd = 1;
				$mos_end = $days_arr[$s_mm];
				if($s_mm > 12) {
					$s_yy++;
					$s_mm = 1;
					if($s_yy == 0) {
						$s_yy = 1;
					}
					$mos_end = $days_arr[$s_mm];
				}
			}
		}
	}
	if(!$pMonth && !$pDay) {
		return $out[$pYear];
	} elseif($pMonth && !$pDay) {
		return $out[$pYear][$pMonth];
	} else {
		if($pYoun) {
			return $out[$pYear][$pMonth]['y'][$pDay];
		}else {
			return $out[$pYear][$pMonth]['p'][$pDay];
		}
	} 
}
//------------------------------------------------------------------------

	/**
     * @function:  fn_MonthlyDays($year)
	 * @return: array
	 * @brief: 매월 날자수(말일) (2014-7-1: 새로작성)
	 **/
function fn_MonthlyDays($year) {
	$days_arr = array(31,31,28,31,30,31,30,31,31,30,31,30,31); // Dec ~ Dec
	if($year > 1582) {
		if( $year%4 == 0 && $year%100 > 0 || $year%400 == 0){	$days_arr[2] = 29; }
	} elseif($year >= 0) {
		if( $year%4 == 0){ $days_arr[2] = 29; }
	} elseif($year < 0) {
		if( ($year + 1)%4 == 0 ){ $days_arr[2] = 29; } //BC1, BC5년이 윤년
	}
	return $days_arr;
}
//------------------------------------------------------------------------

    /**
     * @function: fn_sol2lun_base_kr($year)
     * @return  : array
     * @brief:    basics for Korean lunar calendar(2014-7-1: 새로작성)
     * @brief:    한국음력
     **/
function fn_sol2lun_base_kr($year) {
	if ($year < 1882 || $year > 2050) {
		return false;
	}
	$this_year = (int)$year;
	$prev_year = $this_year-1;
	
	$tmp_data = planner123_main::fn_lunar_data_kr($this_year);
	$tmp_arr = explode(",",$tmp_data);
	$year_month[$prev_year] = $tmp_arr[0];
	$year_month[$this_year] = $tmp_arr[1];
	$year_jd[$prev_year] = $tmp_arr[2];
	$year_jd[$this_year] = $tmp_arr[3];

	foreach($year_month as $key => $value) {
		$start_jd = $year_jd[$key]+2229156;
		$month_arr = str_split($value);
		$tmp_days = 0;
		$lmm = 0;
		foreach($month_arr as $key2 => $value2) {
			if($value2 == 1) {
				$yun = "";
				$tmp_days = 29;
			} elseif($value2 == 2) {
				$yun = "";
				$tmp_days = 30;
			} elseif($value2 == 3) {
				$yun = "윤달";
				$tmp_days = 29;
			} elseif($value2 == 4) {
				$yun = "윤달";
				$tmp_days = 30;
			} elseif($value2 == 0) {
				$tmp_days = 0;
			}
			$wrk_date = planner123_main::fn_calcJDToGregorian($start_jd);
			$start_jd += $tmp_days; // for next month

			$date_arr = explode("/", $wrk_date); //mmddyy
			$sola_date = $date_arr[2].",".$date_arr[0].",".$date_arr[1]; //date: yyyy,mm,dd
			if(!$yun && $value2 != 0) {
				$lmm++;
			}
			if($value2 != 0) {
				if($date_arr[2] >= $this_year || $date_arr[2] == $prev_year && $date_arr[0] == 12) {
					$out[] = $sola_date.",".$tmp_days.",".$key.",".$lmm.","."1".",".$yun;
				}
			}
		}
	}
	return $out;
}
//------------------------------------------------------------------------
	/**
     * @function: fn_jeolki_ganji_ary2($pYear, $pMonth, $pGanjioption)
     * @return  : array
     * @brief   : 한국음력 데이터(2014-7-1: 새로작성)
     **/
Function fn_lunar_data_kr($year) {
	$lunaCalkr_data = "
	1212122322121,1212121221220,1121121222120,2112132122122,2112112121220,2121211212120,2212321121212,2122121121210,2122121212120,1232122121212,1212121221220,1121123221222,1121121212220,1212112121220,2121231212121,2221211212120,1221212121210,2123221212121,2121212212120,1211212232212,1211212122210,2121121212220,1212132112212,2212112112210,2212211212120,1221412121212,1212122121210,2112212122120,1231212122212,1211212122210,2121123122122,2121121122120,2212112112120,2212231212112,2122121212120,1212122121210,2132122122121,2112121222120,1211212322122,1211211221220,2121121121220,2122132112122,1221212121120,2121221212110,2122321221212,1121212212210,2112121221220,1231211221222,1211211212220,1221123121221,2221121121210,2221212112120,1221241212112,1212212212120,1121212212210,2114121212221,2112112122210,2211211412212,2211211212120,2212121121210,2212214112121,2122122121120,1212122122120,1121412122122,1121121222120,2112112122120,2231211212122,2121211212120,2212121321212,2122121121210,2122121212120,1212142121212,1211221221220,1121121221220,2114112121222,1212112121220,2121211232122,1221211212120,1221212121210,2121223212121,2121212212120,1211212212210,2121321212221,2121121212220,1212112112220,1223211211221,2212211212120,1221212321212,1212122121210,2112212122120,1211232122212,1211212122210,2121121122210,2212312112212,2212112112120,2212121232112,2122121212110,2212122121210,2112124122121,2112121221220,1211211221220,2121321122122,2121121121220,2122112112322,1221212112120,1221221212110,2122123221212,1121212212210,2112121221220,1211231221222,1211211212220,1221121121220,1223212112121,2221212112120,1221221232112,1212212122120,1121212212210,2112132212221,2112112122210,2211211212210,2221321121212,2212121121210,2212212112120,1232212121212,1212122122110,2121212322122,1121121222120,2112112122120,2211231212122,2121211212120,2122121121210,2124212112121,2122121212120,1212121223212,1211212221210,2121121221220,1212132121222,1212112121220,2121211212120,2122321121212,1221212121210,2121221212120,1232121221212,1211212212210,2121123212221,2121121212220,1212112112220,1221231211221,2212211211220,1212212121210,2123212212121,2112122122120,1211212122232,1211212122210,2121121122120,2212114112212,2212112112120,2212121211210,2212232121211,2122122121210,2112122122120,1231212122122,1211211221220,2121121321222,2121121121220,2122112112120,2122141211212,1221221212110,2121221221210,2114121221221,
	"; // 1881-2050 lunar calendar - korean(month size and leap month)
	$lunaCalkr = explode(",", $lunaCalkr_data);

	$lunarCalKr_jd_data = "
	178955,179339,179694,180048,180432,180786,181140,181524,181878,182233,182617,182972,183356,183710,184064,184447,184802,185156,185540,185895,186279,186633,186988,187371,187725,188080,188464,188818,189173,189557,189911,190295,190649,191003,191387,191742,192096,192480,192835,193219,193573,193927,194311,194665,195019,195404,195758,196113,196497,196851,197234,197588,197943,198327,198682,199036,199420,199774,200158,200512,200866,201250,201605,201960,202344,202698,203052,203436,203790,204174,204528,204883,205267,205622,205976,206360,206714,207098,207452,207806,208190,208545,208899,209283,209638,209992,210375,210730,211114,211468,211823,212207,212561,212915,213299,213653,214037,214391,214746,215130,215485,215839,216223,216577,216961,217315,217669,218054,218408,218763,219147,219501,219855,220238,220593,220977,221332,221686,222070,222424,222778,223162,223516,223871,224255,224609,224994,225348,225702,226086,226440,226794,227178,227533,227917,228271,228626,229010,229364,229718,230102,230456,230811,231195,231549,231933,232288,232642,233025,233380,233734,234118,234473,234857,235211,235565,235949,236303,236657,237041,237396,237751,238135,238489,238873,239227,239581,239965,240319,240674,
	"; // 1881-2050 lunar calendar - korean(first new moon days. count from 1391year first new moon)
	$lunarCalKr_jd = explode(",", $lunarCalKr_jd_data);

	$this_year = $year-1881;
	$prev_year = $this_year-1;
	$out = $lunaCalkr[$prev_year].",".$lunaCalkr[$this_year].",".$lunarCalKr_jd[$prev_year].",".$lunarCalKr_jd[$this_year];
	return $out;
}
//------------------------------------------------------------------------
    /**
     * @function: fn_get_skinVer($path, $skin= '', $dir = '')
     * @return  : text
     * @brief:    get planner skin version
     **/
function fn_get_skinVer($path, $skin = '', $dir = 'dir') {
	$oModuleModel = getModel('module');
	$skin_info = $oModuleModel->loadSkinInfo($path, $skin, $dir);
	return $skin_info->version;
}

//------------------------------------------------------------------------

    /**
     * @function: fn_get_time_interval($module_info, $category_list)
     * @return  : array
     * @brief:    get time interval
     **/
function fn_get_time_interval($module_info, $category_list) {
	if($module_info->time_start == NULL) {
		$working_start = $working_start_2 = '08:00';// 시작시간 기본값 08:00 부터
	} else {
		$working_start = $working_start_2 = $module_info->time_start;// 게시판관리 설정된 시작시간
	}
	if($module_info->time_end == NULL) {
		$working_end = $working_end_2 = '20:00';// 종료시간 기본값 20:00 까지
	} else {
		$working_end = $working_end_2 = $module_info->time_end;// 게시판관리 설정된 종료시간
	}
	if($module_info->time_interval == NULL) {
		$time_interval = $time_interval = '15';// 시간간격 15분
	} else {
		$time_interval = $module_info->time_interval;// 시간간격 설정값
	}

	$ind_reservation = $module_info->reservation;

	$slt_s_stamp = mktime(substr($working_start,0,2), substr($working_start,3,2), 0, 0, 0, 0);// 시작시간 스탬프
	$slt_e_stamp = mktime(substr($working_end,0,2), substr($working_end,3,2), 0, 0, 0, 0);// 종료시간 스탬프
	$i = 0;
	while($slt_tmp_stamp < $slt_e_stamp) {
		$slt_tmp_stamp = $slt_s_stamp + ($i * $time_interval * 60);
		if ($slt_tmp_stamp >= mktime(23, 59, 0, 0, 0, 0) ) {
			$slt_arr[$i] = '23:59';
		} else {
			$slt_arr[$i] = date('H', $slt_tmp_stamp).':'.date('i',$slt_tmp_stamp);
		}
		$i++;
	}
	return $slt_arr;
}

//------------------------------------------------------------------------

//=========================================================================
} // end of class
?>