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
		$sheet->getColumnDimension('B')->setWidth(15);
		$sheet->getColumnDimension('C')->setWidth(11);
		$sheet->getColumnDimension('D')->setWidth(30);
		$sheet->getColumnDimension('E')->setWidth(30);
		$sheet->getColumnDimension('F')->setWidth(50);
		$sheet->getColumnDimension('G')->setWidth(9);
		$sheet->getColumnDimension('H')->setWidth(80);
		$sheetIndex = $objPHPExcel->setActiveSheetIndex(0);
		$sheet	->getStyle('A1:H1')->getFont()->getColor()->setARGB("FFFFFFFF");
		$sheet	->getStyle('A1:H1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB("FF4169E1");
		

		//엑셀 항목 출력
		$sheetIndex ->setCellValue('A1','번호')
								->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);				
		$sheetIndex ->setCellValue('B1','학습자ID')
								->getStyle('B1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('C1','학습자명')
								->getStyle('C1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('D1','소속명')
								->getStyle('D1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('E1','수강기간')
								->getStyle('E1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('F1','과정명')
								->getStyle('F1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('G1','설문번호')
								->getStyle('G1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('H1','설문답안')
								->getStyle('H1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		

		$searchYear = STR_REPLACE('년','',$_GET['searchYear']);
		$searchMonth = STR_REPLACE('월','',$_GET['searchMonth']);
		if(STRLEN($searchMonth)==1) { 
			$searchMonth = "0".$searchMonth;
		}
		$companyCode = $_GET['companyCode'];
		$surveySeq = $_GET['surveySeq'];
		$lectureDay = $_GET['lectureDay'];
		$lectureSE = EXPLODE('~',$lectureDay);

		if($page == "") {
			$page = 1;
		}
		if($list == "") {
			$list = 10;
		}
		if($surveySeq != "") {
			$queryQ = " AND A.surveySeq=".$surveySeq;
		}else {
			$queryQ = " AND A.surveySeq in ('5','6')";
		}
		if($companyCode != "") {
			$queryQ .= " AND D.companyCode='".$companyCode."'";
		}
		if(!$lectureDay) {
			if($searchMonth == "0") {
				$qSearchYM =  " AND LEFT(D.lectureStart,4)='".$searchYear."'";
			} else {
				$qSearchYM =  " AND LEFT(D.lectureStart,7)='".$searchYear."-".$searchMonth."'";
			}
		} else {
			$qSearchYM =  " AND (D.lectureStart='".TRIM($lectureSE[0])."' AND D.lectureEnd='".TRIM($lectureSE[1])."')";
		}

		$query = "SELECT A.userID, C.userName, A.contentsCode, D.lectureStart, D.lectureEnd, D.lectureEnd, A.surveySeq, A.userTextAnswer, B.contentsName, E.companyName, E.companyCode
							FROM nynSurveyAnswer AS A
							LEFT OUTER
							JOIN nynContents AS B ON A.contentsCode=B.contentsCode
							LEFT OUTER
							JOIN nynMember AS C ON A.userID=C.userID
							LEFT OUTER
							JOIN nynStudy AS D ON A.lectureOpenSeq=D.lectureOpenSeq AND A.userID=D.userID
							LEFT OUTER
							JOIN nynCompany AS E ON D.companyCode=E.companyCode
							WHERE A.seq <> 0 ".$queryQ.$qSearchYM." ORDER BY A.surveySeq, D.lectureStart, C.userName";
		$result = mysql_query($query);
		$count = mysql_num_rows($result);
		$b = 2;
		$number=1;
		
		//엑셀 데이터 출력
		while($rs = mysql_fetch_array($result)) {
			$sheetIndex ->setCellValue('A'.$b, $number)
									->getStyle('A'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
									->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheetIndex ->setCellValue('B'.$b, $rs['userID'])
									->getStyle('B'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
									->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheetIndex ->setCellValue('C'.$b, $rs['userName'])
									->getStyle('C'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
									->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheetIndex ->setCellValue('D'.$b, $rs['companyName'])
									->getStyle('D'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
									->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheetIndex ->setCellValue('E'.$b, $rs['lectureStart']."~".$rs['lectureEnd'])
									->getStyle('E'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
									->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheetIndex ->setCellValue('F'.$b, $rs['contentsName'])
									->getStyle('F'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
									->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheetIndex ->setCellValue('G'.$b, $rs['surveySeq'])
									->getStyle('G'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
									->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
			$sheetIndex ->setCellValue('H'.$b, $rs['userTextAnswer'])
									->getStyle('H'.$b)->getAlignment()->setWrapText(true);

			$b++;
			$number++;
		}
		
		$b = $b - 1 ;
		$sheet->getStyle('A1:H'.$b)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		$fileName = iconv("utf-8","euc-kr","설문조사서술형(".date("Y-m-d").").xls");

    header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$fileName);
    header('Cache-Control: max-age=0');
 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter ->save('php://output');
 
    exit;
?>