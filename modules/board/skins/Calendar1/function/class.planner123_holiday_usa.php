<?php
##
## @Package:    xe_official_planner123 (board skin)
## @File name:	class.planner123_holiday_usa.php
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
##  - Keysung Chung
##  - http://chungfamily.woweb.net/
##
## [changes]
##  - 2014.11.01 : Ver 4.6.0. (음력기념일 함수및 알고리즘 변경)
##  - 2014.01.10 : Ver 4.3.0. (함수이름에 국가코드 추가)
##  - 2013.09.01 : Ver 4.3.0. (대체공휴일)
##  - 2011.08.01 : Ver 4.0.0. (월단위에서 시작 끝이 있는 기간 개념으로 변경)
##  - 2010.10.10 : 미국 휴일및 기념일 함수.
##	 * (class.planner123_main.php 파일에서 휴일과 기념일만 분리한 파일로
##	 * 편리를 위해 분리하나 사용은 class.planner123_main.php 파일과 같이 사용해야됨).
##
//--------------------------------------------------------------------------------

class planner123_holiday_usa extends Object
{

//--------------------------------------------------------------------------------------
    /**
     * @function: fn_HolidayChk($dispStart_stamp, $dispEnd_stamp)
     * @return  : array
     * @brief:    휴일 여부
     **/
Function fn_HolidayChk($dispStart_stamp, $dispEnd_stamp) {
	/******************************************************
	*휴일은 음력에서 1.1(설)/8.15(추석)/4.8(석가탄신일) 이 있으며
	*양력으로 1.1(신정)/3.1(삼일절)/5.5(어린이날)/6.6(현충일)/8.15(광복절)/10.3(개천절)/12.25(성탄절) 이다.
	  (4.5: 2006년부터 식목일은 법정 공휴일에서 법정기념일로 바뀜)
	  (7.17: 2008년 부터 제헌절은 법정 공휴일에서 법정기념일로 바뀜)
	*설과 추석은 앞뒤로 하루씩 휴일이 더해진다.
	*******************************************************/
	$aHoli = null;
		$dispStart_stamp -= 86400 * 2;	//연휴를 고려하여 1일 이전부터 계산
		$dispEnd_stamp += 86400 * 2;	//연휴를 고려하여 1일 이후 까지 계산
		$tmp_sdt = explode("-",date("Y-n-j", $dispStart_stamp));
		$sYear = $tmp_sdt[0];
		$sMonth = $tmp_sdt[1];
		$sDay = $tmp_sdt[2];
		$sMMCount = $sYear*12 + $sMonth;
		$tmp_edt = explode("-",date("Y-n-j", $dispEnd_stamp));
		$eYear = $tmp_edt[0];
		$eMonth = $tmp_edt[1];
		$eDay = $tmp_edt[2];
		$eMMCount = $eYear*12 + $eMonth;
		if(function_exists('gregoriantojd')) {
			$jd_start = gregoriantojd($sMonth, $sDay, $sYear);	// 시작 일자 jd
			$jd_end = gregoriantojd($eMonth, $eDay, $eYear);	// 종료 일자 jd
		} else {
			$jd_start = planner123_main::fn_calcDateToJD($sYear, $sMonth, $sDay);
			$jd_end = planner123_main::fn_calcDateToJD($eYear, $eMonth, $eDay);
		}

//미국 휴일 *********************************************************************
	// 양력 휴일(국경일,기념일중 휴일)
	$aHoli[1][1] .= "New Year's Day ";
	$aHoli[7][4] .= "Independence Day ";
	$aHoli[11][11] .= "Veteran's Day ";
	$aHoli[12][25] .= "Christmas ";

    // 몇월 몇번째 무슨요일 형식 기념일 설정 (예: 상공의날- 3월 셋째 수요일은 ($sYear, 월=3, 일=3, 수=3) 형식으로)
	For($wrkYY = $sYear; $wrkYY <= $eYear; $wrkYY++) {
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 1, 3, 1));  // 1월 3째 월요일
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
			$aHoli[$temp02[1]][$temp02[2]] .= " Martin Luther King's Day";
		}
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 2, 3, 1));  // 2월 3째 월요일
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " President Day";
		}
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 9, 1, 1));  // 9월 1째 월요일
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " Labor Day";
		}
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 10, 2, 1));  // 10월 2째 월요일
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " Columbus Day";
		}
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 11, 4, 4));  // 11월 4째 목요일
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " Thanksgiving Day";
		}
	}

    // 몇월, 끝에서 몇번째 무슨요일 형식 기념일 설정  (예: Victoria Day=끝에서 1번째 월요일)
 	For($wrkYY = $sYear; $wrkYY <= $eYear; $wrkYY++) {
	    $temp02 = explode("-",planner123_main::fn_nslastweekday($wrkYY, 5, 1, 1));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
			$aHoli[$temp02[1]][$temp02[2]] .= " Memorial Day";
		}
	}

    //(부활절)
	For($wrkYY = $sYear; $wrkYY <= $eYear; $wrkYY++) {
	    if (function_exists('easter_days')) {	// 부활절함수 있으면...
			$temp01 = explode("-",planner123_main::fn_easterday($wrkYY));
		} else {
			$temp01 = explode("-",planner123_main::fn_easterday_2($wrkYY));
		}
		$tmp_stamp = mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp01[1]][$temp01[2]] .= " Easter Sunday";
		}
	}

	return $aHoli;
}

//------------------------------------------------------------------------
    /**
     * @function: fn_MemdayChk($dispStart_stamp, $dispEnd_stamp)
     * @return  : boolean
     * @brief:    기념일 여부
     **/
Function fn_MemdayChk($dispStart_stamp, $dispEnd_stamp) {
    /******************************************************
    *법정 기념일과 공휴일이 아닌 국경일
    *음력 기념일 등
    *******************************************************/
    $aHoli = null;
		$dispStart_stamp -= 86400 * 2;	//연휴를 고려하여 1일 이전부터 계산
		$dispEnd_stamp += 86400 * 2;	//연휴를 고려하여 1일 이후 까지 계산
		$tmp_sdt = explode("-",date("Y-n-j", $dispStart_stamp));
		$sYear = $tmp_sdt[0];
		$sMonth = $tmp_sdt[1];
		$sDay = $tmp_sdt[2];
		$sMMCount = $sYear*12 + $sMonth;
		$tmp_edt = explode("-",date("Y-n-j", $dispEnd_stamp));
		$eYear = $tmp_edt[0];
		$eMonth = $tmp_edt[1];
		$eDay = $tmp_edt[2];
		$eMMCount = $eYear*12 + $eMonth;
		if(function_exists('gregoriantojd')) {
			$jd_start = gregoriantojd($sMonth, $sDay, $sYear);	// 시작 일자 jd
			$jd_end = gregoriantojd($eMonth, $eDay, $eYear);	// 종료 일자 jd
		} else {
			$jd_start = planner123_main::fn_calcDateToJD($sYear, $sMonth, $sDay);
			$jd_end = planner123_main::fn_calcDateToJD($eYear, $eMonth, $eDay);
		}

//음력 기념일  $aMoon[월][일][평달=0, 윤달(윤달없으면평달)=1] 형식으로....
//음력은 일년에 같은 월 같은 날이 두번 들어 있을 수 있음.
//  $aMoon[11][9][0] .= "<B>조부기일</B><BR>";  // 음력11월 9일 (평달)
//  $aMoon[8][26][0] .= "조카생일<BR>";         // 음력8월 26일 (평달)
//  $aMoon[5][16][1] .= " <B>**윤달테스트**</B>";    // 음력윤달 (윤달 없으면평달): 2014-07-1 2번타입 1번에 통합
//  $aMoon[11][24][0] .= " <B>**중복테스트**</B>";  // 1년에 두번 예:2008년
//  $aMoon[9][30][1] .= "<B>윤달대체테스트</B><BR>";  // 음력9월 30일 (윤달)

// 개인 기념일(음력)
//

// 공공 기념일(음력)
//

if ( count($aMoon) ) { // 2014-07-01 변경
	// 1)음력30일이 없으면 29일로, 2)윤달없으면 평달로...
	if($sYear == $eYear) {
		$Year1 = $sYear - 1;
		$Year2 = $sYear;
	} else {
		$Year1 = $sYear;
		$Year2 = $eYear;
	}
	$lun2sol[$Year1] = planner123_main::fn_lun2sol_kr($Year1); 
	$lun2sol[$Year2] = planner123_main::fn_lun2sol_kr($Year2); 
	foreach($aMoon as $k1 => $v1) {
		foreach($v1 as $k2 => $v2) {
			$txt = "";
			if($aMoon[$k1][$k2][0] != null) { // 음력(평달) 기념일
				$wrk01 = $lun2sol[$Year1][$k1]['p'][$k2];//전년
				if($wrk01) {
					$wrk02 = $lun2sol[$Year1][$k1]['p'][$k2];
				} elseif(!$wrk01 && $k2 == 30) {
					$wrk02 = $lun2sol[$Year1][$k1]['p'][29];
					$txt = "(29로대체)";
				}
				$wrkdt = explode(",", $wrk02);
				$wrk_jd = planner123_main::fn_calcDateToJD($wrkdt[0], $wrkdt[1], $wrkdt[2]);
				if($wrk_jd >= $jd_start && $wrk_jd <= $jd_end) {
					$aHoli[$wrkdt[1]][$wrkdt[2]] .= $aMoon[$k1][$k2][0].$txt;
				} else {
					$wrk01 = $lun2sol[$Year2][$k1]['p'][$k2];//당년
					if($wrk01) {
						$wrk02 = $lun2sol[$Year2][$k1]['p'][$k2];
					} elseif(!$wrk01 && $k2 == 30) {
						$wrk02 = $lun2sol[$Year1][$k1]['p'][29];
						$txt = "(29로대체)";
					}
					$wrkdt = explode(",", $wrk02);
					$wrk_jd = planner123_main::fn_calcDateToJD($wrkdt[0], $wrkdt[1], $wrkdt[2]);
					if($wrk_jd >= $jd_start && $wrk_jd <= $jd_end) {
						$aHoli[$wrkdt[1]][$wrkdt[2]] .= $aMoon[$k1][$k2][0].$txt;
					}
				}
			} 

			if($aMoon[$k1][$k2][1] != null) { // 음력(윤달) 기념일
				if ( count($lun2sol[$Year1][$k1]['y']) ) { 
					$wrk01 = $lun2sol[$Year1][$k1]['y'][$k2];//전년
					if($wrk01) {
						$wrk02 = $lun2sol[$Year1][$k1]['y'][$k2];
					} elseif(!$wrk01 && $k2 == 30) {
						$wrk02 = $lun2sol[$Year1][$k1]['y'][29];
						$txt = "(29로대체)";
					}
				} else {
					$wrk01 = $lun2sol[$Year1][$k1]['p'][$k2];//전년
					if($wrk01) {
						$wrk02 = $lun2sol[$Year1][$k1]['p'][$k2];
						$txt = "(평달로대체)";
					} elseif(!$wrk01 && $k2 == 30) {
						$wrk02 = $lun2sol[$Year1][$k1]['p'][29];
						$txt = "(평달29로대체)";
					}
				}
				$wrkdt = explode(",", $wrk02);
				$wrk_jd = planner123_main::fn_calcDateToJD($wrkdt[0], $wrkdt[1], $wrkdt[2]);
				if($wrk_jd >= $jd_start && $wrk_jd <= $jd_end) {
					$aHoli[$wrkdt[1]][$wrkdt[2]] .= $aMoon[$k1][$k2][1].$txt;
				} else {
					if ( count($lun2sol[$Year2][$k1]['y']) ) { 
						$wrk01 = $lun2sol[$Year2][$k1]['y'][$k2];//당년
						if($wrk01) {
							$wrk02 = $lun2sol[$Year2][$k1]['y'][$k2];
						} elseif(!$wrk01 && $k2 == 30) {
							$wrk02 = $lun2sol[$Year2][$k1]['y'][29];
							$txt = "(29로대체)";
						}
					} else {
						$wrk01 = $lun2sol[$Year2][$k1]['p'][$k2];//당년
						if($wrk01) {
							$wrk02 = $lun2sol[$Year2][$k1]['p'][$k2];
							$txt = "(평달로대체)";
						} elseif(!$wrk01 && $k2 == 30) {
							$wrk02 = $lun2sol[$Year2][$k1]['p'][29];
							$txt = "(평달29로대체)";
						}
					}
					$wrkdt = explode(",", $wrk02);
					$wrk_jd = planner123_main::fn_calcDateToJD($wrkdt[0], $wrkdt[1], $wrkdt[2]);
					if($wrk_jd >= $jd_start && $wrk_jd <= $jd_end) {
						$aHoli[$wrkdt[1]][$wrkdt[2]] .= $aMoon[$k1][$k2][1].$txt;
					}
				}
			}
		}
	}
}

// 양력기념일 (기념일,국경일,법정기념일 - 휴일아닌경우)
// 개인 기념일(양력)
//  $aHoli[5][29] .= " 큰딸생일";
//  $aHoli[6][9] .= " 작은딸생일";

// 공공 기념일(양력)
    $aHoli[1][13] .= " Korean American Day";
    $aHoli[2][12] .= " Lincoln’s Birthday";
    $aHoli[2][14] .= " St. Valentines Day";
    $aHoli[2][22] .= " Washington's Birth Day";
    $aHoli[3][17] .= " St. Patrick's Day";
    $aHoli[4][1] .= " April Fool's Day";
    $aHoli[10][31] .= " Halloween Day";

    // 몇월 몇번째 무슨요일 형식 기념일 설정 (예: 상공의날- 3월 셋째 수요일은 ($pYear, 월=3, 일=3, 수=3) 형식으로)
	For($wrkYY = $sYear; $wrkYY <= $eYear; $wrkYY++) {
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 5, 2, 0));	// 5월 2째 일요일
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " Mother's Day";
		}
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 6, 3, 0));	// 6월 3째 일요일
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " Father's Day";
		}
		$temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 3, 2, 0));	// 3월 둘째주 일요일
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " <b>DST begins</b>";
		}
		$temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 11, 1, 0));	// 11월 첫째주 일요일
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " <b>DST ends</b>";
		}
	}

/***
    // 몇월 몇번째 무슨요일 형식 기념일 설정 (예: 상공의날- 3월 셋째 수요일은 ($sYear, 월=3, 일=3, 수=3) 형식으로)
	For($wrkYY = $sYear; $wrkYY <= $eYear; $wrkYY++) {
	    $temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, 3, 3, 3));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
			$aHoli[$temp02[1]][$temp02[2]] .= " 상공의날";
		}
	}
***/
/***
    // 몇월, 몇번째 주, 무슨요일 형식 기념일 설정 (예: 10월 4번째주 금요일) -현재 해당 기념일 없음.
    //테스트용 (예: 10월 4쨰주 금요일)
	For($wrkYY = $sYear; $wrkYY <= $eYear; $wrkYY++) {
	    $temp02 = explode("-",planner123_main::fn_nsweeknsweekday($wrkYY, 10, 4, 5));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
			$aHoli[$temp02[1]][$temp02[2]] .= " 현재없음테스트";
		}
	}
***/
/***
    // 매월, 끝에서 몇번째 주, 무슨요일 형식 기념일 설정
    // 테스트용(마지막 주 화요일)
	For($x = $sMMCount; $x <= $eMMCount; $x++) {
		$wrkYY = floor(($x-1)/12);	// 년
		$wrkMM = ($x-1)%12 + 1;	// 월
		$wrkDD = $startDD;	// 일
	    $temp02 = explode("-",planner123_main::fn_nslastweekday($wrkYY, $wrkMM, 1, 2));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
			$aHoli[$temp02[1]][$temp02[2]] .= " 마지막 화요일".$temp02[0].$temp02[1].$temp02[2];
		}
	}
***/
/***
    // 매월, 몇번째 주, 무슨요일 형식 기념일 설정
    // 테스트용( 4번째주 금요일)
	For($x = $sMMCount; $x <= $eMMCount; $x++) {
		$wrkYY = floor(($x-1)/12);	// 년
		$wrkMM = ($x-1)%12 + 1;	// 월
		$wrkDD = $startDD;	// 일
		$temp02 = explode("-",planner123_main::fn_nsweeknsweekday($wrkYY, $wrkMM, 4, 5));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
			$aHoli[$temp02[1]][$temp02[2]] .= " <font color=brown>결산4번째주금</font>";
		}
	}
***/
/***
    // 매월, 몇번째 무슨요일 형식 기념일 설정 (예-옵션만기일: 매월 2번째 목요일)
	For($x = $sMMCount; $x <= $eMMCount; $x++) {
		$wrkYY = floor(($x-1)/12);	// 년
		$wrkMM = ($x-1)%12 + 1;	// 월
		$wrkDD = $startDD;	// 일
		$temp02 = explode("-",planner123_main::fn_nsweekday($wrkYY, $wrkMM, 2, 4));
		$tmp_stamp = mktime(0,0,0,$temp02[1],$temp02[2],$temp02[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp02[1]][$temp02[2]] .= " <font color=brown>옵션만기일</font>";
		}
	}
***/
/***
    // 매월, 음력 날자 끝자리가 같은 형식 (예: 손없는날: 음력일자가 9 또는 0 으로 끝나는날)
	// 2014-07-01 변경
	$sol2lun_arr = planner123_main::fn_sol2lun_kr_period($dispStart_stamp, $dispEnd_stamp); // 출력 기간
	foreach($sol2lun_arr as $k1 => $v1) {
		foreach($v1 as $k2 => $v2) {
			$tmplun = explode(",", $v2);
			if(substr($tmplun[2], -1) == 9 || substr($tmplun[2], -1) == 0) {
				$aHoli[$k1][$k2] .=  " 손없는날";
			}
		}
	}
***/
/***
    //(부활절)
	For($wrkYY = $sYear; $wrkYY <= $eYear; $wrkYY++) {
	    if (function_exists('easter_days')) {	// 부활절함수 있으면...
			$temp01 = explode("-",planner123_main::fn_easterday($wrkYY));
		} else {
			$temp01 = explode("-",planner123_main::fn_easterday_2($wrkYY));
		}
		$tmp_stamp = mktime(0, 0, 0,$temp01[1], $temp01[2], $temp01[0]);
		if($tmp_stamp >= $dispStart_stamp && $tmp_stamp <= $dispEnd_stamp){
		    $aHoli[$temp01[1]][$temp01[2]] .= " 부활절";
		}
	}
***/
/***
// 이슬람력 기념일  $arr_Islam[월][일] 형식으로....
	$arr_Islam[1][1] .= "<font color=blue>Islamic New Year</font><BR>";	// (Islamic New Year)
	$arr_Islam[1][10] .= "<font color=blue>Ashura</font><BR>";  // (10th day of Muharram)
	$arr_Islam[3][12] .= "<font color=blue>Mawlid an Nabi</font><BR>";	// (Muhammad's Birthday)
	$arr_Islam[7][26] .= "<font color=blue>Laylat al Miraj</font><BR>";  //
	$arr_Islam[8][14] .= "<font color=blue>Laylat al Baraat</font><BR>";	//( Night of Emancipation)
	$arr_Islam[9][1] .= "<font color=blue>Ramadan begins</font><BR>";  // (Ramadan begins)
	$arr_Islam[9][26] .= "<font color=blue>Laylat al Qadr</font><BR>";  // (Holy night)
	$arr_Islam[10][1] .= "<font color=blue>Eid al Fitr</font><BR>";	// (Ramadan ends)
	$arr_Islam[12][8] .= "<font color=blue>Hajj days</font><BR>";  // (Hajj days)
	$arr_Islam[12][10] .= "<font color=blue>Eid al Adha</font><BR>";  // (Festival of Sacrifice)

    for($x=$dispStart_stamp; $x <= $dispEnd_stamp; $x +=86400 ) {   // 기간 동안만
		$wrkYY = date("Y", $x);
		$wrkMM = date("n", $x);
		$wrkDD = date("j", $x);
		$islam_date = planner123_main::fn_GregorianToIslamic_ksc($wrkMM, $wrkDD, $wrkYY);
		$wrk_arr = explode("-",$islam_date);
		$islam_yy = $wrk_arr[0];
		$islam_mm = $wrk_arr[1];
		$islam_dd = $wrk_arr[2];
		if($arr_Islam[$islam_mm][$islam_dd]) {
			$aHoli[$wrkMM][$wrkDD] .= $arr_Islam[$islam_mm][$islam_dd];
		}
	}
***/

//대한민국 휴일 *********************************************************************
	//양력 휴일(국경일,기념일중 휴일)
	$aHoli[1][1] .= "신정 ";
	$aHoli[3][1] .= "삼일절 ";
	$aHoli[5][5] .= "어린이날 ";
	$aHoli[6][6] .= "현충일 ";
//	$aHoli[7][17] .= "제헌절 ";	// 국경일이나 휴일아님
	$aHoli[8][15] .= "광복절 ";
	$aHoli[10][3] .= "개천절 ";
	if($sYear >= 2013){
		$aHoli[10][9] .= "한글날(".($sYear-1446).") " ;	// 국경일이나 휴일아님 => 2013년 부터 법정공휴일
	}
	$aHoli[12][25] .= "성탄절 ";

	//음력휴일
	//(석가탄신일)
	$temp01 = null;
	$SeokGa = explode(",",planner123_main::fn_lun2sol_kr($sYear,4,8));
	//$jd_SeokGa = gregoriantojd($SeokGa[1], $SeokGa[2], $SeokGa[0]);  //PHP함수 없는경우 고려하여 
	$jd_SeokGa = planner123_main::fn_calcDateToJD($SeokGa[0], $SeokGa[1], $SeokGa[2]);
	if ($jd_SeokGa >= $jd_start && $jd_SeokGa <= $jd_end) {
		$temp01 = $SeokGa;
	} else {
		$SeokGa = explode(",",planner123_main::fn_lun2sol_kr($eYear,4,8));
		//$jd_SeokGa = gregoriantojd($SeokGa[1], $SeokGa[2], $SeokGa[0]);
		$jd_SeokGa = planner123_main::fn_calcDateToJD($SeokGa[0], $SeokGa[1], $SeokGa[2]);
		if ($jd_SeokGa >= $jd_start && $jd_SeokGa <= $jd_end) {
			$temp01 = $SeokGa;
		}
	}
	if (!empty($temp01)) {
		$aHoli[$temp01[1]][$temp01[2]] .= "석가탄신일";
 	}

	//(설날)
	$temp01 = null;
	$SeolNal = explode(",",planner123_main::fn_lun2sol_kr($sYear,1,1));
	//$jd_SeolNal = gregoriantojd($SeolNal[1], $SeolNal[2], $SeolNal[0]);
	$jd_SeolNal = planner123_main::fn_calcDateToJD($SeolNal[0], $SeolNal[1], $SeolNal[2]);
	if ($jd_SeolNal >= $jd_start && $jd_SeolNal <= $jd_end) {
		$temp01 = $SeolNal;
	} else {
		$SeolNal = explode(",",planner123_main::fn_lun2sol_kr($eYear,1,1));
		//$jd_SeolNal = gregoriantojd($SeolNal[1], $SeolNal[2], $SeolNal[0]);
		$jd_SeolNal = planner123_main::fn_calcDateToJD($SeolNal[0], $SeolNal[1], $SeolNal[2]);
		if ($jd_SeolNal >= $jd_start && $jd_SeolNal <= $jd_end) {
			$temp01 = $SeolNal;
		}
	}
	if (!empty($temp01)) {
		$dup_Seol = 0;
		if ($aHoli[$temp01[1]][$temp01[2]]) {
			$dup_Seol += 1;
		}
		$aHoli[$temp01[1]][$temp01[2]] .= "설날";

		//$SeolNal_pre = explode("/", jdtogregorian($jd_SeolNal-1));  //PHP함수 없는경우 고려하여 
		$SeolNal_pre = explode("/", planner123_main::fn_calcJDToGregorian($jd_SeolNal-1));
		if ($aHoli[$SeolNal_pre[0]][$SeolNal_pre[1]]) {
			$dup_Seol += 1;
		}
		$aHoli[$SeolNal_pre[0]][$SeolNal_pre[1]] .= "설연휴";

		//$SeolNal_nxt = explode("/", jdtogregorian($jd_SeolNal+1));
		$SeolNal_nxt = explode("/", planner123_main::fn_calcJDToGregorian($jd_SeolNal+1));
		if ($aHoli[$SeolNal_nxt[0]][$SeolNal_nxt[1]]) {
			$dup_Seol += 1;
		}
		$aHoli[$SeolNal_nxt[0]][$SeolNal_nxt[1]] .= "설연휴";
	}

	//(추석)
	$temp01 = null;
	$ChuSeok = explode(",",planner123_main::fn_lun2sol_kr($sYear,8,15));
	//$jd_ChuSeok = gregoriantojd($ChuSeok[1], $ChuSeok[2], $ChuSeok[0]);
	$jd_ChuSeok = planner123_main::fn_calcDateToJD($ChuSeok[0], $ChuSeok[1], $ChuSeok[2]);
	if ($jd_ChuSeok >= $jd_start && $jd_ChuSeok <= $jd_end) {
		$temp01 = $ChuSeok;
	} else {
		$ChuSeok = explode(",",planner123_main::fn_lun2sol_kr($eYear,8,15));
		//$jd_ChuSeok = gregoriantojd($ChuSeok[1], $ChuSeok[2], $ChuSeok[0]);
		$jd_ChuSeok = planner123_main::fn_calcDateToJD($ChuSeok[0], $ChuSeok[1], $ChuSeok[2]);
		if ($jd_ChuSeok >= $jd_start && $jd_ChuSeok <= $jd_end) {
			$temp01 = $ChuSeok;
		}
	}
	if (!empty($temp01)) {
		$dup_ChuSeok = 0;
		if ($aHoli[$temp01[1]][$temp01[2]]) {
			$dup_ChuSeok += 1;
		}
		$aHoli[$temp01[1]][$temp01[2]] .= "추석";

		//$ChuSeok_pre = explode("/", jdtogregorian($jd_ChuSeok-1)); //PHP함수 없는경우 고려하여 
		$ChuSeok_pre = explode("/", planner123_main::fn_calcJDToGregorian($jd_ChuSeok-1));  // mm/dd/yy
		if ($aHoli[$ChuSeok_pre[0]][$ChuSeok_pre[1]]) {
			$dup_ChuSeok += 1;
		}
		$aHoli[$ChuSeok_pre[0]][$ChuSeok_pre[1]] .= "추석연휴";

		//$ChuSeok_nxt = explode("/", jdtogregorian($jd_ChuSeok+1));
		$ChuSeok_nxt = explode("/", planner123_main::fn_calcJDToGregorian($jd_ChuSeok+1));
		if ($aHoli[$ChuSeok_nxt[0]][$ChuSeok_nxt[1]]) {
			$dup_ChuSeok += 1;
		}
		$aHoli[$ChuSeok_nxt[0]][$ChuSeok_nxt[1]] .= "추석연휴";
	}

	// 대체휴일:(2013년 10월부터 시행)
	// 설날 연휴와 추석 연휴가 다른 공휴일과 겹치는 경우 그 날 다음의 첫 번째 비공휴일을 공휴일로 하고, 
	// 어린이날이 토요일 또는 다른 공휴일과 겹치는 경우 그 날 다음의 첫 번째 비공휴일을 공휴일로 함 
	if($sMMCount >= 2013*12 + 10){
		if($SeolNal_nxt){
			//$wrk1 = jddayofweek( $jd_SeolNal+1, 0 ); //요일 0=일, 1=월
			$wrk1 = (1+$jd_SeolNal+1)%7; //요일 0=일, 1=월  (함수가 없는 경우를 위해서 대체함)
			if($wrk1 < 3){
				$dup_Seol += 1;
			}
			if($dup_Seol > 0){
				for ($d=1; $d < 5; $d++ ) {
					//$wrk_yoil = jddayofweek($jd_SeolNal+1+$d, 0 ); //요일 0=일, 1=월 
					$wrk_yoil = (1+$jd_SeolNal+1+$d)%7; //요일 0=일, 1=월  (함수가 없는 경우를 위해서 게산으로 대체)
					//$jdDate = jdtogregorian($jd_SeolNal+1+$d);
					$jdDate = planner123_main::fn_calcJDToGregorian($jd_SeolNal+1+$d);
					$temp01 = explode("/",$jdDate);
					if($aHoli[$temp01[0]][$temp01[1]] == "" && $wrk_yoil != 0 ){
						$aHoli[$temp01[0]][$temp01[1]] .= "대체공휴일"; // 설연휴 대체공휴일
						//$dup_Seol -= 1;
						//if($dup_Seol <= 0) {
							break;
						//}
					}
				}
			}
		}
		if($ChuSeok_nxt){
			//$wrk1 = jddayofweek( $jd_ChuSeok+1, 0 ); //요일 0=일, 1=월
			$wrk1 = (1+$jd_ChuSeok+1)%7; //요일 0=일, 1=월
			if($wrk1 < 3){
				$dup_ChuSeok += 1;
			}
			if($dup_ChuSeok > 0){
				for ($d=1; $d < 5; $d++ ) {
					//$wrk_yoil = jddayofweek($jd_ChuSeok+1+$d, 0 ); //요일 0=일, 1=월
					$wrk_yoil = (1+$jd_ChuSeok+1+$d)%7; //요일 0=일, 1=월
					//$jdDate = jdtogregorian($jd_ChuSeok+1+$d);
					$jdDate = planner123_main::fn_calcJDToGregorian($jd_ChuSeok+1+$d);
					$temp01 = explode("/",$jdDate);
					if($aHoli[$temp01[0]][$temp01[1]] == "" && $wrk_yoil != 0 ){
						$aHoli[$temp01[0]][$temp01[1]] .= "대체공휴일"; // 추석연휴 대체공휴일
						//$dup_ChuSeok -= 1;
						//if($dup_ChuSeok <= 0) {
							break;
						//}
					}
				}
			}
		}
		if($aHoli[5][5] != "어린이날 "){
			$dup_Child += 1;
		}
		//$jd_Child = gregoriantojd(5, 5, $sYear);
		$jd_Child = planner123_main::fn_calcDateToJD($sYear, 5, 5);
		//$wrk_yoil = jddayofweek($jd_Child, 0 ); //요일 0=일, 1=월
		$wrk_yoil = (1+$jd_Child)%7; //요일 0=일, 1=월
		if($wrk_yoil == 0 || $wrk_yoil == 6){
			$dup_Child += 1;
		}
		if($dup_Child > 0){
				for ($d=1; $d < 5; $d++ ) {
					//$wrk_yoil = jddayofweek($jd_Child+$d, 0 ); //요일 0=일, 1=월
					$wrk_yoil = (1+$jd_Child+$d)%7; //요일 0=일, 1=월
					//$jdDate = jdtogregorian($jd_Child+$d);
					$jdDate = planner123_main::fn_calcJDToGregorian($jd_Child+$d);
					$temp01 = explode("/",$jdDate);
					if($aHoli[$temp01[0]][$temp01[1]] == "" && $wrk_yoil != 0 ){
						$aHoli[$temp01[0]][$temp01[1]] .= "대체공휴일"; // 어린이날 대체공휴일
						//$dup_Child -= 1;
						//if($dup_Child <= 0) {
							break;
						//}
					}
				}
		}
	} //End 대체휴일 

    return $aHoli;
}
//------------------------------------------------------------------------

} // end of class

?>
