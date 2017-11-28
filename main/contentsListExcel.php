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
		$sheet->getColumnDimension('A')->setWidth(9);
		$sheet->getColumnDimension('B')->setWidth(12);
		$sheet->getColumnDimension('C')->setWidth(13);
		$sheet->getColumnDimension('D')->setWidth(83);
		$sheet->getColumnDimension('E')->setWidth(9);
		$sheet->getColumnDimension('F')->setWidth(9);
		$sheet->getColumnDimension('G')->setWidth(8);
		$sheet->getColumnDimension('H')->setWidth(8);
		$sheet->getColumnDimension('I')->setWidth(19);
		$sheet->getColumnDimension('J')->setWidth(19);
		$sheet->getColumnDimension('K')->setWidth(11);
		$sheet->getColumnDimension('L')->setWidth(9);
		$sheetIndex = $objPHPExcel->setActiveSheetIndex(0);

		//엑셀 항목 출력
		$sheetIndex ->setCellValue('A1',$_siteURL.' 교육과정 목록')
								->mergeCells('A1:L1')
								->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('A2','홈페이지 : '.$_siteURL.' / 전화 : '.$_csPhone.' / 팩스 : '.$_csFax.' / 이메일 : '.$_adminMail)
								->mergeCells('A2:L2')
								->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->getCell('A2')
								->getHyperlink()
								->setUrl('http://'.$_siteURL);
		$sheetIndex ->setCellValue('A3','신청번호')
								->mergeCells('A3:A4')
								->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('B3','대분류')
								->mergeCells('B3:B4')
								->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('C3','소분류')
								->mergeCells('C3:C4')
								->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('D3','과정명')
								->mergeCells('D3:D4')
								->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('E3','차시분량')
								->mergeCells('E3:E4')
								->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('F3','교육시간')
								->mergeCells('F3:F4')
								->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('G3','교육비')
								->mergeCells('G3:G4')
								->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('H3','환급비')
								->mergeCells('H3:J3')
								->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('K3','스마트러닝')
								->mergeCells('K3:K4')
								->getStyle('K3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('L3','상세보기')
								->mergeCells('L3:L4')
								->getStyle('L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('H4','우선지원')
								->getStyle('H4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('I4','대규모 1000인 미만')
								->getStyle('I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('J4','대규모 1000인 이상')
								->getStyle('J4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

				$query = "SELECT A.*,B.value02 AS cate01, C.value02 AS cate02
									FROM nynContents AS A
									LEFT OUTER
									JOIN nynCategory AS B ON A.sort01=B.value01
									LEFT OUTER
									JOIN nynCategory AS C ON A.sort02=C.value01
									WHERE A.enabled='Y' ORDER BY A.sort01, A.sort02";
				$result = mysql_query($query);
				$count = mysql_num_rows($result);
				$b = 5;

				//엑셀 데이터 출력
				while($rs = mysql_fetch_array($result)) {
					if($rs[mobile] == 'Y') {
						$mobile = '지원';
					} else {
						$mobile = '';
					}
					$sheetIndex ->setCellValue('A'.$b, $rs[seq])
											->setCellValue('B'.$b, $rs[cate01])
											->getStyle('B'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('C'.$b, $rs[cate02])
											->getStyle('C'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('D'.$b, $rs[contentsName])
											->setCellValue('E'.$b, $rs[chapter])
											->getStyle('E'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('F'.$b, $rs[contentsTime])
											->getStyle('F'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('G'.$b, $rs[price])
											->getStyle('G'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('H'.$b, $rs[rPrice01])
											->getStyle('H'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('I'.$b, $rs[rPrice02])
											->getStyle('I'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('J'.$b, $rs[rPrice03])
											->getStyle('J'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('K'.$b, $mobile)
											->getStyle('K'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('L'.$b, "클릭보기")
											->getCell('L'.$b)
											->getHyperlink()
											->setUrl('http://'.$_siteURL.'/lecture/?seq='.$rs[seq]);
					$sheetIndex ->getStyle('L'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$b++;
				}

		$sheetIndex ->setCellValue('A'.$b,'과정목록은 '.substr($inputDate,0,10).' 기준이며 상황에 따라 변동될 수 있습니다.')
								->mergeCells('A'.$b.':'.'L'.$b)
								->getStyle('A'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A1:L'.$b)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		$fileName = iconv("utf-8","euc-kr",$_siteName."_과정목록(".date("Y-m-d").").xls");
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$fileName);
    header('Cache-Control: max-age=0');
 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter ->save('php://output');
 
    exit;
?>