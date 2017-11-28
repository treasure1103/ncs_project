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
		$sheet->getColumnDimension('D')->setWidth(10);
		$sheet->getColumnDimension('E')->setWidth(83);
		$sheet->getColumnDimension('F')->setWidth(9);
		$sheet->getColumnDimension('G')->setWidth(9);
		$sheet->getColumnDimension('H')->setWidth(8);
		$sheet->getColumnDimension('I')->setWidth(8);
		$sheet->getColumnDimension('J')->setWidth(19);
		$sheet->getColumnDimension('K')->setWidth(19);
		$sheet->getColumnDimension('L')->setWidth(11);
		$sheet->getColumnDimension('M')->setWidth(10);
		$sheet->getColumnDimension('N')->setWidth(10);
		$sheetIndex = $objPHPExcel->setActiveSheetIndex(0);

		//엑셀 항목 출력
		$sheetIndex ->setCellValue('A1','이상에듀 교육과정 목록')
								->mergeCells('A1:N1')
								->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('A2','홈페이지 : http://esangedu.kr / 전화 : 02-6494-2010 / 팩스 : 02-6008-2012 / 이메일 : study@nayanet.kr')
								->mergeCells('A2:N2')
								->getStyle('A2')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->getCell('A2')
								->getHyperlink()
								->setUrl('http://esangedu.kr');
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
		$sheetIndex ->setCellValue('D3','과정코드')
								->mergeCells('D3:D4')
								->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('E3','과정명')
								->mergeCells('E3:E4')
								->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('F3','차시분량')
								->mergeCells('F3:F4')
								->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('G3','교육시간')
								->mergeCells('G3:G4')
								->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('H3','교육비')
								->mergeCells('H3:H4')
								->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('I3','환급비')
								->mergeCells('I3:K3')
								->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('L3','교재비')
								->mergeCells('L3:L4')
								->getStyle('L3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('M3','스마트러닝')
								->mergeCells('M3:M4')
								->getStyle('M3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('N3','상세보기')
								->mergeCells('N3:N4')
								->getStyle('N3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER)
								->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
		$sheetIndex ->setCellValue('I4','우선지원')
								->getStyle('I4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('J4','대규모 1000인 미만')
								->getStyle('J4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheetIndex ->setCellValue('K4','대규모 1000인 이상')
								->getStyle('K4')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

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
					$sheetIndex ->setCellValue('D'.$b, $rs[contentsCode])
											->getStyle('D'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex	->setCellValue('E'.$b, $rs[contentsName])
											->setCellValue('F'.$b, $rs[chapter])
											->getStyle('F'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('G'.$b, $rs[contentsTime])
											->getStyle('G'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('H'.$b, $rs[price])
											->getStyle('H'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('I'.$b, $rs[rPrice01])
											->getStyle('I'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('J'.$b, $rs[rPrice02])
											->getStyle('J'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('K'.$b, $rs[rPrice03])
											->getStyle('K'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('L'.$b, $rs[bookPrice])
											->getStyle('L'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('M'.$b, $mobile)
											->getStyle('M'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$sheetIndex ->setCellValue('N'.$b, "클릭보기")
											->getCell('N'.$b)
											->getHyperlink()
											->setUrl('http://esangedu.kr/lecture/?seq='.$rs[seq]);
					$sheetIndex ->getStyle('L'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
					$b++;
				}

		$sheetIndex ->setCellValue('A'.$b,'과정목록은 '.substr($inputDate,0,10).' 기준이며 상황에 따라 변동될 수 있습니다.')
								->mergeCells('A'.$b.':'.'N'.$b)
								->getStyle('A'.$b)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle('A1:N'.$b)->getBorders()->getAllBorders()->setBorderStyle(PHPExcel_Style_Border::BORDER_THIN);

		$fileName = iconv("utf-8","euc-kr","과정목록(".date("Y-m-d").").xls");

    header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename='.$fileName);
    header('Cache-Control: max-age=0');
 
    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
    $objWriter ->save('php://output');
 
    exit;
?>