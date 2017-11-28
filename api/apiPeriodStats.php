<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	$selectSearch = $_GET['selectSearch'];
	$searchYear  = STR_REPLACE('년','',$_GET['searchYear']);
	$searchMonth = STR_REPLACE('월','',$_GET['searchMonth']);
	if (STRLEN($searchMonth)==1) {
		$searchMonth = "0".$searchMonth;
	}
	$companyCode = $_GET['companyCode'];
	$lectureDay  = $_GET['lectureDay'];
	$lectureSE   = EXPLODE('~',$lectureDay);

	switch ($selectSearch) {
		case 'searchDate':
			//기간검색
			if (!$lectureDay) {
				if ($searchMonth == '00') {
					$qLectureStart =  " AND YEAR(lectureStart) = '".$searchYear."'";
				} else {
					$qLectureStart =  " AND YEAR(lectureStart) = '".$searchYear."' AND MONTH(lectureStart) = '".$searchMonth."'";
				}				
				$qGroupBy      = " GROUP BY lectureStart";
			} else {
				$qLectureStart = " AND lectureStart='".TRIM($lectureSE[0])."' AND lectureEnd='".TRIM($lectureSE[1])."'";
				$qGroupBy      = " GROUP BY lectureStart";
			}
			break;

		case 'searchMonth':
			//월별검색
			$qLectureStart =  " AND YEAR(lectureStart) = '".$searchYear."'";
			$qGroupBy      = " GROUP BY MONTH(lectureStart)";
			break;

		case 'searchCompany':
			//사업주검색
			if (!$lectureDay) {
				$qCompanyCode = " AND companyCode='".$companyCode."'";
				$qGroupBy     = " GROUP BY lectureStart";
			} else {
				$qLectureStart = " AND lectureStart='".TRIM($lectureSE[0])."' AND lectureEnd='".TRIM($lectureSE[1])."' AND companyCode='".$companyCode."'";
				$qGroupBy      = " GROUP BY lectureStart";
			}
			break;
	}

	$qSearch = $qCompanyCode.$qLectureStart;
	$query   = "SELECT lectureStart, lectureEnd, LEFT(lectureStart,7) AS lectureMonth,
					SUM(IF(serviceType='1',1,0)) AS totalStudy1,
					SUM(IF(serviceType != '1',1,0)) AS totalStudy2,
					SUM(IF(serviceType='1' AND passOK='Y',1,0)) AS totalPassOk1,
					SUM(IF(serviceType != '1' AND passOK='Y',1,0)) AS totalPassOk2,
					SUM(IF(serviceType='1' AND passOK='N',1,0)) AS totalPassNo1,
					SUM(IF(serviceType != '1' AND passOK='N',1,0)) AS totalPassNo2,
					SUM(IF(serviceType = '1',price,0)) AS totalPrice1,
					SUM(IF(serviceType != '1',price,0)) AS totalPrice2,
					SUM(IF(serviceType='1' AND passOK='Y',rPrice,0)) AS totalrPrice
				FROM nynStudy WHERE 1".$qSearch.$qGroupBy." ORDER BY lectureStart, lectureEnd DESC";

	$result   = mysql_query($query);
	$count    = mysql_num_rows($result);
	$a        = 0;
	$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

	//$adminapi[companyCode] = $companyCode;
	$adminapi['totalCount'] = $count;

	while ($rs = mysql_fetch_array($result)) {
		if ($selectSearch != 'searchMonth') {
			$adminapi['periodStats'][$a]['lectureDay'] = $rs['lectureStart']." ~ ".$rs['lectureEnd'];
		} else {
			$adminapi['periodStats'][$a]['lectureDay'] = $rs['lectureMonth'];
		}
		if ($rs['totalStudy1'] == 0) {
			$percent1 = 0;
		} else {
			$percent1 = $rs['totalPassOk1'] / $rs['totalStudy1'] * 100;				//수료율 계산(환급)
		}
		if ($rs['totalStudy2'] == 0) {
			$percent2 = 0;
		} else {
			$percent2 = $rs['totalPassOk2'] / $rs['totalStudy2'] * 100;				//수료율 계산(비환급)
		}
		$adminapi['periodStats'][$a]['totalStudy1']  = $rs['totalStudy1']  ? $rs['totalStudy1']  : 0;			//수강인원(환급)
		$adminapi['periodStats'][$a]['totalStudy2']  = $rs['totalStudy2']  ? $rs['totalStudy2']  : 0;			//수강인원(비환급)
		$adminapi['periodStats'][$a]['totalPassOk1'] = $rs['totalPassOk1'] ? $rs['totalPassOk1'] : 0;			//수료인원(환급)
		$adminapi['periodStats'][$a]['totalPassOk2'] = $rs['totalPassOk2'] ? $rs['totalPassOk2'] : 0;			//수료인원(비환급)
		$adminapi['periodStats'][$a]['totalPassNo1'] = $rs['totalPassNo1'] ? $rs['totalPassNo1'] : 0;			//미수료인원(환급)
		$adminapi['periodStats'][$a]['totalPassNo2'] = $rs['totalPassNo2'] ? $rs['totalPassNo2'] : 0;			//미수료인원(비환급)
		$adminapi['periodStats'][$a]['totalPrice1']  = $rs['totalPrice1']  ? $rs['totalPrice1']  : 0;			//교육비(환급)
		$adminapi['periodStats'][$a]['totalPrice2']  = $rs['totalPrice2']  ? $rs['totalPrice2']  : 0;			//교육비(비환급)
		$adminapi['periodStats'][$a]['totalrPrice']  = $rs['totalrPrice']  ? $rs['totalrPrice']  : 0;			//환급액
		$adminapi['periodStats'][$a]['percent1']     = sprintf('%.1f',$percent1);	//수료율(환급)
		$adminapi['periodStats'][$a]['percent2']     = sprintf('%.1f',$percent2);	//수료율(비환급)
		$a++;
	}

	$json_encoded = json_encode($adminapi);
	print_r($json_encoded);
?>