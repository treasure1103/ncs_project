<? include '../lib/header.php'; ?>
<?php
		header("Content-Encoding: UTF-8");
		$lectureDay = $_GET['lectureDay'];
		$lectureSE = EXPLODE('~',$lectureDay);
		$companyCode = $_GET['companyCode'];

		if($_SESSION[loginUserLevel] == '8') {
			$qUserList = "AND A.companyCode='".$_SESSION[loginCompanyCode]."'";
		}
		if($lectureDay != "") {
			$qLectureStart = " AND (A.lectureStart='".TRIM($lectureSE[0])."' AND A.lectureEnd='".TRIM($lectureSE[1])."')";
		}	else {
			echo "<script>alert('기간을 선택해주세요!'); window.location.href='./08_monitoring.php?locaSel=0501';</script>";
			exit;
		}
		if($companyCode != "") {
			$qCompanyCode = " AND A.companyCode='".$companyCode."'";
		}

    require_once '../lib/PHPExcel/Classes/PHPExcel.php';
    $objPHPExcel = new PHPExcel(); 

		$sheet = $objPHPExcel->getActiveSheet();
		$sheet->getDefaultStyle()->getFont()->setName('맑은 고딕'); // 폰트
		$sheet->getDefaultStyle()->getFont()->setName('맑은 고딕')->setSize(11); // 폰트 크기
		$sheet->getColumnDimension('A')->setWidth(10);
		$sheet->getColumnDimension('B')->setWidth(12);
		$sheet->getColumnDimension('C')->setWidth(12);
		$sheet->getColumnDimension('D')->setWidth(24);
		$sheet->getColumnDimension('E')->setWidth(15);
		$sheet->getColumnDimension('F')->setWidth(12);
		$sheet->getColumnDimension('G')->setWidth(12);
		$sheet->getColumnDimension('H')->setWidth(15);
		$sheet->getColumnDimension('I')->setWidth(12);
		$sheet->getColumnDimension('J')->setWidth(12);
		$sheet->getColumnDimension('K')->setWidth(12);
		$sheet->getColumnDimension('L')->setWidth(12);
		$sheet->getColumnDimension('M')->setWidth(12);
		$sheet->getColumnDimension('N')->setWidth(12);
		$sheet->getColumnDimension('O')->setWidth(12);
		$sheet->getColumnDimension('P')->setWidth(12);
		$sheet->getColumnDimension('Q')->setWidth(15);
		$sheetIndex = $objPHPExcel->setActiveSheetIndex(0);

		//엑셀 항목 출력
		$sheetIndex ->setCellValue('A1','SEQ')
								->setCellValue('B1','시작')
								->setCellValue('C1','종료')
								->setCellValue('D1','과정')
								->setCellValue('E1','사업주')
								->setCellValue('F1','아이디')
								->setCellValue('G1','이름')
								->setCellValue('H1','소속')
								->setCellValue('I1','진도율')
								->setCellValue('J1','중간평가')
								->setCellValue('K1','최종평가')
								->setCellValue('L1','과제')
								->setCellValue('M1','총점')
								->setCellValue('N1','수료여부')
								->setCellValue('O1','강사명')
								->setCellValue('P1','강사ID')
								->setCellValue('Q1','배정할 강사ID');

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
									WHERE A.serviceType <> 9 ".$qUserList.$qLectureStart.$qCompanyCode;
				$result = mysql_query($query);
				$count = mysql_num_rows($result);
				$b = 2;

				//엑셀 데이터 출력
				while($rs = mysql_fetch_array($result)) {
					$sheetIndex ->setCellValue('A'.$b, $rs[seq])
											->setCellValue('B'.$b, $rs[lectureStart])
											->setCellValue('C'.$b, $rs[lectureEnd])
											->setCellValue('D'.$b, $rs[contentsName])
											->setCellValue('E'.$b, $rs[companyName])
											->setCellValue('F'.$b, $rs[userID])
											->setCellValue('G'.$b, $rs[userName])
											->setCellValue('H'.$b, $rs[department])
											->setCellValue('I'.$b, $rs[progress]);

												if($rs[midStatus] == 'N') {
													$midStatus = '미응시';
												} else if($rs[midStatus] == 'Y') {
													$midStatus = '응시완료';
												} else if($rs[midStatus] == 'C') {
													$midStatus = $rs['midScore'];
												} else {
													$midStatus = '없음';
												}

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

												if($rs[reportStatus] == 'N') {
													$reportStatus = '미응시';
												} else if($rs[reportStatus] == 'Y') {
													$reportStatus = '응시완료';
												} else if($rs[reportStatus] == 'C') {
													$reportStatus = $rs['reportScore'];
												} else {
													$reportStatus = '없음';
												}

					$sheetIndex	->setCellValue('J'.$b, $midStatus)
											->setCellValue('K'.$b, $testStatus)
											->setCellValue('L'.$b, $reportStatus);
				
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

					$sheetIndex ->setCellValue('M'.$b, $totalScore)
											->setCellValue('N'.$b, $passOK)
											->setCellValue('O'.$b, $rs[tutorName])
											->setCellValue('P'.$b, $rs[tutor])
											->setCellValue('Q'.$b, '');
					$b++;
				}

		$fileName = iconv("utf-8","euc-kr","강사배정(".date("Y-m-d").").xls");

    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename='.$fileName);
    header('Cache-Control: max-age=0');
 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter ->save('php://output');
    exit;
?>