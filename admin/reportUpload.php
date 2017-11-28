<? include '../lib/header.php'; ?>
<?
require_once "../lib/PHPExcel/Classes/PHPExcel.php"; // PHPExcel.php
$objPHPExcel = new PHPExcel();
require_once "../lib/PHPExcel/Classes/PHPExcel/IOFactory.php"; // IOFactory.php
$fileName = $_FILES['uploadFile']['tmp_name'];

try {
  // 업로드 된 엑셀 형식에 맞는 Reader객체를 만든다.
    $objReader = PHPExcel_IOFactory::createReaderForFile($fileName);
    // 읽기전용으로 설정
    $objReader->setReadDataOnly(true);
    // 엑셀파일을 읽는다
    $objExcel = $objReader->load($fileName);
    // 첫번째 시트를 선택
    $objExcel->setActiveSheetIndex(0);
    $objWorksheet = $objExcel->getActiveSheet();
    $rowIterator = $objWorksheet->getRowIterator();

    foreach ($rowIterator as $row) { // 모든 행에 대해서
               $cellIterator = $row->getCellIterator();
               $cellIterator->setIterateOnlyExistingCells(false); 
    }

    $maxRow = $objWorksheet->getHighestRow();
		$k = 1;

		for($i = 2; $i <= $maxRow; $i++) {
			for( $j=1, $a='A'; $j <=7; $j++, $a++) {
				$excelData[$a] = $objWorksheet->getCell($a . $i)->getValue();
			}

			$queryQ = " originCode='".trim($excelData['A'])."', 
									sourceChapter='".trim($excelData['B'])."', 
									examNum='".trim($excelData['C'])."', 
									exam='".addslashes(trim($excelData['D']))."', 
									example='".addslashes(trim($excelData['E']))."', 
									rubric='".addslashes(trim($excelData['F']))."', 
									score='".trim($excelData['G'])."'";

			$queNum="SELECT MAX(seq) AS seq FROM nynReport";
			$resultNum = mysql_query($queNum);
			$rsNum = mysql_fetch_assoc($resultNum);
			$seq = $rsNum[seq]+1;

			if(trim($excelData['A'])) {
				$query01 = "INSERT INTO nynReport SET ".$queryQ;
				$result01 = mysql_query($query01);
			}

			$queNum="SELECT MAX(seq) AS seq FROM nynReport";
			$resultNum = mysql_query($queNum);
			$rsNum = mysql_fetch_assoc($resultNum);
			$reportSeq = $rsNum[seq];

			if(trim($excelData['A'])) {
				$queryM = "INSERT INTO nynReportMapping SET
											contentsCode='".trim($excelData['A'])."', 
											reportSeq=".$reportSeq;
				$resultM = mysql_query($queryM);
			}

			if(!$result01) {
				$queryDel = "DELETE FROM nynReport WHERE originCode='".$originCode."'";
				$resultDel = mysql_query($queryDel);
				$queryDel01 = "DELETE FROM nynReportMapping WHERE contentsCode='".$originCode."'";
				$resultDel01 = mysql_query($queryDel01);
				echo $examNum."번 등록 중 오류가 발생하였습니다.";
				exit;
			}
		}

			echo "등록 성공!";
			header("Location: /admin/05_contents.php?locaSel=0601");
			exit;
}

 catch (exception $e) {
    echo '엑셀파일을 읽는도중 오류가 발생하였습니다.';
		exit;
}
​?>