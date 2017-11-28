<? include '../lib/header.php'; ?>
<?
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
require_once "../lib/PHPExcel/Classes/PHPExcel.php"; // PHPExcel.php
$objPHPExcel = new PHPExcel();
require_once "../lib/PHPExcel/Classes/PHPExcel/IOFactory.php"; // IOFactory.php
$fileName = $_FILES['uploadFile2']['tmp_name'];

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

			for( $j=1, $a='A'; $j <=12; $j++, $a++) {
				$excelData[$a] = $objWorksheet->getCell($a . $i)->getValue();
			}

				$contentsCode = trim($excelData['A']);

				//integer형 데이터 항목에 값이 없는 경우 0으로 변경함
				if(trim($excelData['B'] != "")) {
					$contentsGrade = "contentsGrade='".trim($excelData['B'])."', ";
				} else {
					$contentsGrade = "contentsGrade='', ";
				}
				if(trim($excelData['C'] != "")) {
					$contentsTime = "contentsTime='".trim($excelData['C'])."', ";
				} else {
					$contentsTime = "contentsTime='0', ";
				}
				if(trim($excelData['D'] != "")) {
					$price = "price='".trim($excelData['D'])."', ";
				} else {
					$price = "price='0', ";
				}
				if(trim($excelData['E'] != "")) {
					$rPrice01 = "rPrice01='".trim($excelData['E'])."', ";
				} else {
					$rPrice01 = "rPrice01='0', ";
				}
				if(trim($excelData['F'] != "")) {
					$rPrice02 = "rPrice02='".trim($excelData['F'])."', ";
				} else {
					$rPrice02 = "rPrice02='0', ";
				}
				if(trim($excelData['G'] != "")) {
					$rPrice03 = "rPrice03='".trim($excelData['G'])."', ";
				} else {
					$rPrice03 = "rPrice03='0', ";
				}
				if(trim($excelData['H'] != "")) {
					$contentsPeriod = "contentsPeriod='".trim($excelData['H'])."', ";
				} else {
					$contentsPeriod = "contentsPeriod='0', ";
				}
				if(trim($excelData['I'] != "")) {
					$passCode = "passCode='".trim(str_replace("-","",$excelData['I']))."', ";
				} else {
					$passCode = "passCode='', ";
				}
				if(trim($excelData['J'] != "")) {
					$limited = "limited='".trim($excelData['J'])."', ";
				} else {
					$limited = "limited='0', ";
				}
				if(trim($excelData['K'] != "")) {
					$serviceType = "serviceType='".trim($excelData['K'])."', ";
				} else {
					$serviceType = "";
				}
				if(trim($excelData['L'] != "")) {
					$contentsName = "contentsName='".trim($excelData['L'])."' ";
				} else {
					$contentsName = "";
				}

				$queryQ =  $contentsGrade.$contentsTime.$price.$rPrice01.$rPrice02.$rPrice03.$contentsPeriod.$passCode.$limited.$serviceType.$contentsName;

				$query01 = "UPDATE nynContents SET ".$queryQ." WHERE contentsCode='".$contentsCode."'";
				$result01 = mysql_query($query01);

				if($result01) {
					$k = $k+1;
				} else {
					echo $k."줄 과정 데이터에 오류가 있습니다.(이전 데이터까지 수정완료)";
					exit;
				}
		}
			echo $k."과정 결과 정보 수정 성공!";
			header("Location: /admin/05_contents.php?locaSel=0601");
			exit;
}

 catch (exception $e) {
    echo '엑셀파일을 읽는도중 오류가 발생하였습니다?!';
		exit;
}
​?>