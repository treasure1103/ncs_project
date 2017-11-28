<?php
header('Content-Type:text/text; charset=utf-8');
include '../lib/header.php'; ?>
<?php
/*error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
*/
		header("Content-Encoding: UTF-8");

		$lectureDay = $_GET['lectureDay'];
		$lectureSE = EXPLODE('~',$lectureDay);
		$companyCode = $_GET['companyCode'];
		$progress01 = $_GET['progress01'];
		$progress02 = $_GET['progress02'];
		$reportCopy = $_GET['reportCopy'];
		$passOK = $_GET['passOK'];
		$midStatus = $_GET['midStatus'];
		$testStatus = $_GET['testStatus'];
		$reportStatus = $_GET['reportStatus'];
		$correct = $_GET['correct'];

		if($_SESSION[loginUserLevel] == '5' || $_SESSION[loginUserLevel] == '6') { //영업팀장, 영업사원
			$qMarketer = " AND C.marketerID='".$_SESSION[loginUserID]."' ";
		}
		if($_SESSION[loginUserLevel] == '8') { //교육담당자
				$queryM = "SELECT * FROM nynMatching WHERE userID='".$_SESSION[loginUserID]."' AND matchingType='manager'";
				$resultM = mysql_query($queryM);
				$countM = mysql_num_rows($resultM);

				if($countM > 0 ) {
					$qUserList = " AND A.companyCode IN (";
					$m = 1;

					while($rsM = mysql_fetch_array($resultM)) {
						$qUserList .= "'".$rsM['matchingValue']."'";
						if($countM != $m) {
							$qUserList .= ", ";
						}
						$m++;
					}
					$qUserList .= ")";
				} else {
					$qUserList = " AND A.companyCode='".$_SESSION[loginCompanyCode]."'";
				}
		}
		if($lectureDay != "") {
			$qLectureStart = " AND (A.lectureStart='".TRIM($lectureSE[0])."' AND A.lectureEnd='".TRIM($lectureSE[1])."')";
		}		
		if($companyCode != "") {
			$qCompanyCode = " AND A.companyCode='".$companyCode."'";
		}
		if($progress01 != "" && $progress02 != "") {
			$qProgress = " AND (A.progress BETWEEN ".$progress01." AND ".$progress02.")";
		}
		if($reportCopy != "") {
			$qReportCopy = " AND A.reportCopy='".$reportCopy."'";
		}
		if($passOK != "") {
			$qPassOK = " AND A.passOK='".$passOK."'";
		}
		if($midStatus != "") {
			$qMidStatus = " AND A.midStatus='".$midStatus."'";
		}
		if($testStatus != "") {
			$qTestStatus = " AND A.testStatus='".$testStatus."'";
		}
		if($reportStatus != "") {
			$qReportStatus = " AND A.reportStatus='".$reportStatus."'";
		}
		if($monitor == "Y") {
			$qMonitor = " AND (A.testStatus IN ('Y','C') OR A.reportStatus IN ('Y','C'))";
		}
		if($correct == "N")  {
			$qCorrect = " AND (midStatus='Y' OR testStatus='Y' OR reportStatus='Y' OR (testStatus='V' AND NOW() >= testEndTime))";
		}

		$qQuery = $qCorrect.$qMonitor.$qReportStatus.$qTestStatus.$qMidStatus.$qPassOK.$qReportCopy.$qProgress.$qMarketer.$qUserList.$qLectureStart.$qCompanyCode;

		require_once '../lib/PHPExcel/Classes/PHPExcel.php';
		$objPHPExcel = new PHPExcel(); 

		$sheet = $objPHPExcel->getActiveSheet();
		$sheet->getDefaultStyle()->getFont()->setName('맑은 고딕'); // 폰트
		$sheet->getDefaultStyle()->getFont()->setName('맑은 고딕')->setSize(11); // 폰트 크기
		$sheet->getColumnDimension('A')->setWidth(12);
		$sheet->getColumnDimension('B')->setWidth(12);
		$sheet->getColumnDimension('C')->setWidth(26);
		$sheet->getColumnDimension('D')->setWidth(24);
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->getColumnDimension('F')->setWidth(11);
		$sheet->getColumnDimension('G')->setWidth(15);
		$sheet->getColumnDimension('H')->setWidth(10);
		$sheet->getColumnDimension('I')->setWidth(12);
		$sheet->getColumnDimension('J')->setWidth(12);
		$sheet->getColumnDimension('K')->setWidth(12);
		$sheet->getColumnDimension('L')->setWidth(12);
		$sheet->getColumnDimension('M')->setWidth(12);
		$sheet->getColumnDimension('N')->setWidth(12);
		$sheet->getColumnDimension('O')->setWidth(12);
		$sheet->getColumnDimension('P')->setWidth(20);
		$sheet->getColumnDimension('Q')->setWidth(20);
		$sheet->getColumnDimension('R')->setWidth(20);
		$sheet->getColumnDimension('S')->setWidth(12);
		$sheet->getColumnDimension('T')->setWidth(12);
		$sheet->getColumnDimension('U')->setWidth(12);
		$sheet->getColumnDimension('V')->setWidth(12);
		$sheetIndex = $objPHPExcel->setActiveSheetIndex(0);

		//엑셀 항목 출력
		$sheetIndex ->setCellValue('A1','시작')
								->setCellValue('B1','종료')
								->setCellValue('C1','과정')
								->setCellValue('D1','첨삭강사')
								->setCellValue('E1','사업주')
								->setCellValue('F1','아이디')
								->setCellValue('G1','이름')
								->setCellValue('H1','소속')
								->setCellValue('I1','전화번호')
								->setCellValue('J1','이메일')
								->setCellValue('K1','진도율')
								->setCellValue('L1','중간평가')
								->setCellValue('M1','최종평가')
								->setCellValue('N1','과제')
								->setCellValue('O1','모사답안')
								->setCellValue('P1','중간평가 응시일')
								->setCellValue('Q1','최종평가 응시일')
								->setCellValue('R1','과제 응시일')
								->setCellValue('S1','총점')
								->setCellValue('T1','교육비')
								->setCellValue('U1','환급비')
								->setCellValue('V1','수료여부');

				$query = "SELECT A.*, Z.userName AS tutorName, B.userName, B.department, B.mobile01, B.mobile02, B.mobile03, 
									B.email01, B.email02, C.companyName, D.contentsName FROM nynStudy A 
									LEFT OUTER
									JOIN nynMember B
									ON A.userID=B.userID
									LEFT OUTER
									JOIN nynMember Z
									ON A.tutor=Z.userID
									LEFT OUTER
									JOIN nynCompany C
									ON A.companyCode=C.companyCode
									JOIN nynContents D
									ON A.contentsCode=D.contentsCode
									WHERE A.serviceType <> 9 ".$qQuery;
				$result = mysql_query($query);
				$count = mysql_num_rows($result);
				$b = 2;

				//엑셀 데이터 출력
				while($rs = mysql_fetch_array($result)) {
					$sheetIndex ->setCellValue('A'.$b, $rs[lectureStart])
											->setCellValue('B'.$b, $rs[lectureEnd])
											->setCellValue('C'.$b, $rs[contentsName])
											->setCellValue('D'.$b, $rs[tutorName].' ('.$rs[tutor].')')
											->setCellValue('E'.$b, $rs[companyName])
											->setCellValue('F'.$b, $rs[userID])
											->setCellValue('G'.$b, $rs[userName])
											->setCellValue('H'.$b, $rs[department])
											->setCellValue('I'.$b, $rs[mobile01]."-".$rs[mobile02]."-".$rs[mobile03])
											->setCellValue('J'.$b, $rs[email01]."@".$rs[email02])
											->setCellValue('K'.$b, $rs[progress]);

												if($rs[serviceType] == '3') { // 비환급인 경우 평가없음
													$midStatus = '평가없음';
												} else {
													if($rs[midStatus] == 'N') {
														$midStatus = '미응시';
													} else if($rs[midStatus] == 'Y') {
														$midStatus = '응시완료';
													} else if($rs[midStatus] == 'C') {
														$midStatus = $rs['midScore'];
													} else {
														$midStatus = '없음';
													}
												}

												if($rs[serviceType] == '3') { // 비환급인 경우 평가없음
													$testStatus = '평가없음';
												} else {
													if($rs[testStatus] == 'N') {
														$testStatus = '미응시';
													} else if($rs[testStatus] == 'Y') {
														$testStatus = '응시완료';
													} else if($rs[testStatus] == 'C') {
														$testStatus = $rs['testScore'];
													} else if($rs[testStatus] == 'V') { // 채점 완료
														if($inputDate >= $rs[testEndTime]) {
															$testStatus = '응시완료';
														} else {
															$testStatus = '응시중';
														}
													} else {
														$testStatus = '없음';
													}
												}

												if($rs[serviceType] == '3') { // 비환급인 경우 평가없음
													$reportStatus = '과제없음';
												} else {
													if($rs[reportStatus] == 'N') {
														$reportStatus = '미응시';
													} else if($rs[reportStatus] == 'Y') {
														$reportStatus = '응시완료';
													} else if($rs[reportStatus] == 'C') {
														$reportStatus = $rs['reportScore'];
													} else {
														$reportStatus = '없음';
													}
												}

												if($rs[reportCopy] == 'D') {
													$reportCopy = '모사의심';
												} else if($rs[reportCopy] == 'Y') {
													$reportCopy = '모사확정';
												} else {
													$reportCopy = '';
												}

					$sheetIndex	->setCellValue('L'.$b, $midStatus)
											->setCellValue('M'.$b, $testStatus)
											->setCellValue('N'.$b, $reportStatus)
											->setCellValue('O'.$b, $reportCopy);
				
												$sLectureStart = $rs[lectureStart]." 00:00:00";
												$sLectureEnd = $rs[lectureEnd]." 23:59:59";

												if($inputDate >= $sLectureStart && $inputDate <= $sLectureEnd) {
													$totalScore = '진행중';
													$passOK = "진행중";
												} else {
													if($rs[totalScore] == null) {
														$totalScore = '0';
													} else {
														$totalScore = $rs[totalScore];
													}
													$passOK = $rs[passOK];
												}

					$sheetIndex ->setCellValue('P'.$b, $rs[midSaveTime])
											->setCellValue('Q'.$b, $rs[testSaveTime])
											->setCellValue('R'.$b, $rs[reportSaveTime])
											->setCellValue('S'.$b, $totalScore)
											->setCellValue('T'.$b, $rs[price])
											->setCellValue('U'.$b, $rs[rPrice])
											->setCellValue('V'.$b, $passOK);
					$b++;
				}

	//$fileName = iconv("utf-8", "euc-kr", "학습현황(".date("Y-m-d").").xls");

	$fileName = rawurlencode("학습현황(".date("Y-m-d").").xls");
	
	//	$fileName = "학습현황(".date("Y-m-d").").xls";

    header('Content-Type: application/vnd.ms-excel');
    header("Content-Disposition: attachment;filename='$fileName'");
    header('Cache-Control: max-age=0');
 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter ->save('php://output');
    exit;
?>