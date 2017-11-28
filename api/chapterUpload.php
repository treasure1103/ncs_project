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

		for($i = 2; $i <= $maxRow; $i++) {
			$contentsCode = $objWorksheet->getCell('A' . $i)->getValue(); //과정 코드
			$chapter = $objWorksheet->getCell('B' . $i)->getValue(); //출처 차시
			$chapterName = $objWorksheet->getCell('C' . $i)->getValue(); // 문항 번호
			$goal = $objWorksheet->getCell('D' . $i)->getValue(); // 문제
			$content = $objWorksheet->getCell('E' . $i)->getValue(); // 보기1
			$activity = $objWorksheet->getCell('F' . $i)->getValue(); // 보기2
			$professor = $objWorksheet->getCell('G' . $i)->getValue(); // 보기3
			$chapterPath = $objWorksheet->getCell('H' . $i)->getValue(); // 보기4
			$chapterSize = $objWorksheet->getCell('I' . $i)->getValue(); // 정답

			$queryQ = " contentsCode='".trim($contentsCode)."', 
								  chapter=".trim($chapter).", 
									chapterName='".addslashes(trim($chapterName))."', 
									goal='".addslashes(trim($goal))."', 
									content='".addslashes(trim($content))."', 
									activity='".addslashes(trim($activity))."', 
									professor='".trim($professor)."',
									chapterPath='".trim($chapterPath)."',
									chapterSize='".trim($chapterSize)."',
									chapterMobilePath='".trim($chapterMobilePath)."'";

				$query01 = "INSERT INTO nynChapter SET ".$queryQ;
				$result01 = mysql_query($query01);
				$a = 1;
		}
			echo "등록 성공!";
			exit;
}

 catch (exception $e) {
    echo '엑셀파일을 읽는도중 오류가 발생하였습니다.';
		exit;
}
		
	@mysql_close();
​?>