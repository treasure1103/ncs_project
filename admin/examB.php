<? include '../lib/header.php'; ?>
<?php

		header("Content-Encoding: utf-8");

		$lectureDay = $_GET['lectureDay'];
		$lectureSE = EXPLODE('~',$lectureDay);
		$companyCode = $_GET['companyCode'];

		if($companyCode != "") {
			$qCompanyCode = " AND A.companyCode='".$companyCode."'";
		}

    require_once '../lib/PHPExcel/Classes/PHPExcel.php';
    $objPHPExcel = new PHPExcel(); 

		$sheet = $objPHPExcel->getActiveSheet();
		$sheet->getDefaultStyle()->getFont()->setName('맑은 고딕'); // 폰트
		$sheet->getDefaultStyle()->getFont()->setName('맑은 고딕')->setSize(11); // 폰트 크기
		$sheet->getColumnDimension('A')->setWidth(30);
		$sheet->getColumnDimension('B')->setWidth(9);
		$sheet->getColumnDimension('C')->setWidth(10);
		$sheet->getColumnDimension('D')->setWidth(12);
		$sheet->getColumnDimension('E')->setWidth(9);
		$sheet->getColumnDimension('F')->setWidth(12);
		$sheet->getColumnDimension('G')->setWidth(9);
		$sheet->getColumnDimension('H')->setWidth(9);
		$sheet->getColumnDimension('I')->setWidth(9);
		$sheet->getColumnDimension('J')->setWidth(9);
		$sheet->getColumnDimension('K')->setWidth(20);
		$sheet->getColumnDimension('L')->setWidth(20);
		$sheetIndex = $objPHPExcel->setActiveSheetIndex(0);

		//엑셀 항목 출력
		$sheetIndex ->setCellValue('A1','과정명')
								->setCellValue('B1','분류')
								->setCellValue('C1','문제번호')
								->setCellValue('D1','출제번호')
								->setCellValue('E1','강사ID')
								->setCellValue('F1','강사명')
								->setCellValue('G1','수강생ID')
								->setCellValue('H1','수강명')
								->setCellValue('I1','총점')
								->setCellValue('J1','점수')
								->setCellValue('K1','작성답안')
								->setCellValue('L1','모범답안');

		$query = "SELECT DISTINCT(A.lectureOpenSeq), A.contentsCode, B.contentsName, B.test02EA FROM nynStudy AS A
							LEFT OUTER
							JOIN nynContents AS B
							ON A.contentsCode=B.contentsCode
							WHERE (A.lectureStart='".TRIM($lectureSE[0])."' AND A.lectureEnd='".TRIM($lectureSE[1])."')".$qCompanyCode;
		$result = mysql_query($query);
		$b = 2;

				//엑셀 데이터 출력
				while($rs = mysql_fetch_array($result)) {

					if($rs[test02EA] > 0) { // 단답형 출력
						$queryA = "SELECT seq FROM nynTest where originCode='".$rs[contentsCode]."' AND testType='final' AND examType='B'";
						$resultA = mysql_query($queryA);
						
						while($rsA = mysql_fetch_array($resultA)) {
							$queryB = " SELECT B.userID, D.userName, B.score, B.orderBy, B.userTextAnswer, A.tutor, A.totalScore, C.userName AS tutorName, E.examNum, E.answerText FROM nynStudy AS A
													LEFT OUTER
													JOIN nynTestAnswer AS B
													ON A.userID=B.userID
													LEFT OUTER
													JOIN nynMember AS C
													ON A.tutor=C.userID
													LEFT OUTER
													JOIN nynMember AS D
													ON A.userID=D.userID
													LEFT OUTER
													JOIN nynTest AS E
													ON B.testSeq=E.seq
													WHERE A.lectureOpenSeq='".$rs[lectureOpenSeq]."' AND A.contentsCode='".$rs[contentsCode]."' AND B.testSeq='".$rsA[seq]."' AND B.lectureOpenSeq='".$rs[lectureOpenSeq]."' 
													ORDER BY tutorName, E.examNum";
							$resultB = mysql_query($queryB);
							$countB = mysql_num_rows($resultB);

							while($rsB = mysql_fetch_array($resultB)) {
								$sheetIndex ->setCellValue('A'.$b, $rs[contentsName])
														->setCellValue('B'.$b, '단답형')
														->setCellValue('C'.$b, $rsB[examNum])
														->setCellValue('D'.$b, $rsB[orderBy])
														->setCellValue('E'.$b, $rsB[tutor])
														->setCellValue('F'.$b, $rsB[tutorName])
														->setCellValue('G'.$b, $rsB[userID])
														->setCellValue('H'.$b, $rsB[userName])
														->setCellValue('I'.$b, $rsB[totalScore])
														->setCellValue('J'.$b, $rsB[score])
														->setCellValueExplicit('K'.$b, $rsB[userTextAnswer])
														->setCellValueExplicit('L'.$b, $rsB[answerText]);
								$b++;
							}
						}
					}
				}
		
		$fileName = iconv("utf-8","euc-kr","단답형확인(".date("Y-m-d").").xls");

    header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$fileName);
    header('Cache-Control: max-age=0');
 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter ->save('php://output');
    exit;
?>