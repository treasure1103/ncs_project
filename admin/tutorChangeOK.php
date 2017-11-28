<? include '../lib/header.php'; ?>
<?
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
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

			//중복 등록 확인
		for($i = 2; $i <= $maxRow; $i++) {
			$seq = $objWorksheet->getCell('A' . $i)->getValue(); // seq
			$tutor = $objWorksheet->getCell('Q' . $i)->getValue(); // 강사ID

			$query01 = "UPDATE nynStudy SET tutor='".$tutor."' WHERE seq='".$seq."'";
			$result01 = mysql_query($query01);

				if(!$result01) {
					echo "<script>alert('".$i."줄에서 오류가 발생하였습니다.'); window.location.href='./08_monitoring.php?locaSel=0501';</script>";
					exit;
				}
		}
			echo "<script>alert('재배정 처리되었습니다.'); window.location.href='./08_monitoring.php?locaSel=0501';</script>";
			exit;
}

 catch (exception $e) {
		echo "<script>alert('엑셀파일을 읽는도중 오류가 발생하였습니다..'); window.location.href='./08_monitoring.php?locaSel=0501';</script>";
		exit;
}
​?>