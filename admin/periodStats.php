<? include '../lib/header.php'; ?>
<?php
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
		header("Content-Encoding: utf-8");

    require_once '../lib/PHPExcel/Classes/PHPExcel.php';
    $objPHPExcel = new PHPExcel();

		$sheet = $objPHPExcel->getActiveSheet();
		$sheet->getDefaultStyle()->getFont()->setName('맑은 고딕'); // 폰트
		$sheet->getDefaultStyle()->getFont()->setName('맑은 고딕')->setSize(11); // 폰트 크기
		$sheet->getColumnDimension('A')->setWidth(8);
		$sheet->getColumnDimension('B')->setWidth(24);
		$sheet->getColumnDimension('C')->setWidth(13);
		$sheet->getColumnDimension('D')->setWidth(13);
		$sheet->getColumnDimension('E')->setWidth(13);
		$sheet->getColumnDimension('F')->setWidth(13);
		$sheet->getColumnDimension('G')->setWidth(13);
		$sheet->getColumnDimension('H')->setWidth(13);
		$sheet->getColumnDimension('I')->setWidth(13);
		$sheet->getColumnDimension('J')->setWidth(13);
		$sheet->getColumnDimension('K')->setWidth(13);
		$sheet->getColumnDimension('L')->setWidth(13);
		$sheet->getColumnDimension('M')->setWidth(13);
		$sheet->getColumnDimension('N')->setWidth(13);
		$sheet->getColumnDimension('O')->setWidth(13);
		$sheet->getColumnDimension('P')->setWidth(13);
		$sheet->getColumnDimension('Q')->setWidth(13);
		$sheet->getColumnDimension('R')->setWidth(13);
		$sheet->getColumnDimension('S')->setWidth(13);
		$sheetIndex = $objPHPExcel->setActiveSheetIndex(0);

		//엑셀 항목 출력
		$sheetIndex ->setCellValue('A1','번호')
								->mergeCells('A1:A2')
								->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('B1','수강기간')
								->mergeCells('B1:B2')
								->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('C1','환급 과정')
								->mergeCells('C1:H1')
								->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->getStyle('C1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB("FF4169E1");
		$sheetIndex ->getStyle('C1')->getFont()->getColor()->setARGB("FFFFFFFF");
		$sheetIndex ->setCellValue('I1','비환급 과정')
								->mergeCells('I1:M1')
								->getStyle('I1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->getStyle('I1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB("FF4169E1");
		$sheetIndex ->getStyle('I1')->getFont()->getColor()->setARGB("FFFFFFFF");
		$sheetIndex ->setCellValue('N1','총계')
								->mergeCells('N1:S1')
								->getStyle('N1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->getStyle('N1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB("FF4169E1");
		$sheetIndex ->getStyle('N1')->getFont()->getColor()->setARGB("FFFFFFFF");
		$sheetIndex ->setCellValue('C2','수강인원')
								->getStyle('C2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('D2','수료인원')
								->getStyle('D2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('E2','미수료인원')
								->getStyle('E2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('F2','수료율')
								->getStyle('F2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('G2','교육비')
								->getStyle('G2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('H2','환급액')
								->getStyle('H2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('I2','수강인원')
								->getStyle('I2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('J2','수료인원')
								->getStyle('J2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('K2','미수료인원')
								->getStyle('K2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('L2','수료율')
								->getStyle('L2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('M2','교육비')
								->getStyle('M2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('N2','수강인원')
								->getStyle('N2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('O2','수료인원')
								->getStyle('O2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('P2','미수료인원')
								->getStyle('P2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('Q2','수료율')
								->getStyle('Q2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('R2','교육비')
								->getStyle('R2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('S2','환급액')
								->getStyle('S2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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
					FROM nynStudy WHERE 1 ".$qSearch.$qGroupBy." ORDER BY lectureStart, lectureEnd DESC";

		$result   = mysql_query($query);
		$count = mysql_num_rows($result);
		$b = 3;
		$number=1;

		//엑셀 데이터 출력
		while($rs = mysql_fetch_array($result)) {
			if ($selectSearch != 'searchMonth') {
				$lectureDate = $rs['lectureStart']." ~ ".$rs['lectureEnd'];
			} else {
				$lectureDate = $rs['lectureMonth'];
			}
			if ($rs['totalStudy1'] != 0) {
				$percent1     = $rs['totalPassOk1'] / $rs['totalStudy1'] * 100;			//수료율 계산(환급)
			} else {
				$percent1     = 0;
			}
			if ($rs['totalStudy2'] != 0) {
				$percent2     = $rs['totalPassOk2'] / $rs['totalStudy2'] * 100;			//수료율 계산(비환급)
			} else {
				$percent2     = 0;
			}
			$totalStudy1  = $rs['totalStudy1']  ? $rs['totalStudy1']  : 0;			//수강인원(환급)
			$totalStudy2  = $rs['totalStudy2']  ? $rs['totalStudy2']  : 0;			//수강인원(비환급)
			$totalPassOk1 = $rs['totalPassOk1'] ? $rs['totalPassOk1'] : 0;			//수료인원(환급)
			$totalPassOk2 = $rs['totalPassOk2'] ? $rs['totalPassOk2'] : 0;			//수료인원(비환급)
			$totalPassNo1 = $rs['totalPassNo1'] ? $rs['totalPassNo1'] : 0;			//미수료인원(환급)
			$totalPassNo2 = $rs['totalPassNo2'] ? $rs['totalPassNo2'] : 0;			//미수료인원(비환급)
			$totalPrice1  = $rs['totalPrice1']  ? $rs['totalPrice1']  : 0;			//교육비(환급)
			$totalPrice2  = $rs['totalPrice2']  ? $rs['totalPrice2']  : 0;			//교육비(비환급)
			$totalrPrice  = $rs['totalrPrice']  ? $rs['totalrPrice']  : 0;			//환급액
			$percent1     = sprintf('%.1f',$percent1);								//수료율(환급)
			$percent2     = sprintf('%.1f',$percent2);								//수료율(비환급)

			//환급
			$sheetIndex ->setCellValue('A'.$b, $number)->getStyle('A'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex ->setCellValue('B'.$b, $lectureDate)->getStyle('B'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex ->setCellValue('C'.$b, $totalStudy1)->getStyle('C'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex	->setCellValue('D'.$b, $totalPassOk1)->getStyle('D'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex ->setCellValue('E'.$b, $totalPassNo1)->getStyle('E'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex ->setCellValue('F'.$b, round($percent1."%"))->getStyle('F'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex ->setCellValue('G'.$b, $totalPrice1)->getStyle('G'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex	->getStyle('G'.$b)->getNumberFormat()->setFormatCode("#,##0");
			$sheetIndex ->setCellValue('H'.$b, $totalrPrice)->getStyle('H'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex	->getStyle('H'.$b)->getNumberFormat()->setFormatCode("#,##0");

			//비환급
			$sheetIndex ->setCellValue('I'.$b, $totalStudy2)->getStyle('I'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex	->setCellValue('J'.$b, $totalPassOk2)->getStyle('J'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex ->setCellValue('K'.$b, $totalPassNo2)->getStyle('K'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex ->setCellValue('L'.$b, round($percent2."%"))->getStyle('L'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex ->setCellValue('M'.$b, $totalPrice2)->getStyle('M'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex	->getStyle('M'.$b)->getNumberFormat()->setFormatCode("#,##0");

			$totalStudyAll  = $totalStudy1  + $totalStudy2;
			$totalPassOkAll = $totalPassOk1 + $totalPassOk2;
			$totalPassNoAll = $totalPassNo1 + $totalPassNo2;
			$totalPriceAll  = $totalPrice1  + $totalPrice2;
			$totalrPriceAll = $totalrPrice;
			if ($totalStudyAll != 0) {
				$totalPercent = $totalPassOkAll / $totalStudyAll * 100;
			} else {
				$totalPercent = 0;
			}

			//총계
			$sheetIndex ->setCellValue('N'.$b, $totalStudyAll)->getStyle('N'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex	->setCellValue('O'.$b, $totalPassOkAll)->getStyle('O'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex ->setCellValue('P'.$b, $totalPassNoAll)->getStyle('P'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex ->setCellValue('Q'.$b, round($totalPercent."%"))->getStyle('Q'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex ->setCellValue('R'.$b, $totalPriceAll)->getStyle('R'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex	->getStyle('R'.$b)->getNumberFormat()->setFormatCode("#,##0");
			$sheetIndex ->setCellValue('S'.$b, $totalrPriceAll)->getStyle('S'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
			$sheetIndex	->getStyle('S'.$b)->getNumberFormat()->setFormatCode("#,##0");
			$b ++;
			$number ++;
		}
		$b = $b-1;
		$sheet->getStyle('A1:S'.$b)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		$fileName = iconv("utf-8","euc-kr","수강통계(".date("Y-m-d").").xls");

    	header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$fileName);
    	header('Cache-Control: max-age=0');

    	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    	$objWriter ->save('php://output');

    	exit;
?>