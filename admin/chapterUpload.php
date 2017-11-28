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
			$contentsCode = $objWorksheet->getCell('A' . 2)->getValue(); //과정 코드
			$queryCheck = "SELECT * FROM nynChapter WHERE contentsCode='".$contentsCode."'";
			$resultCheck = mysql_query($queryCheck);
			$count = mysql_num_rows($resultCheck);
			if($count > 0) {
				echo "<script>alert('이미 등록된 과정입니다.'); window.location.href='./05_contents.php?locaSel=0601';</script>";
				exit;
			}

		for($i = 2; $i <= $maxRow; $i++) {
			$contentsCode = $objWorksheet->getCell('A' . $i)->getValue(); //과정 코드
			$chapter = $objWorksheet->getCell('B' . $i)->getValue(); //차시
			$chapterName = $objWorksheet->getCell('C' . $i)->getValue(); // 차시명
			$goal = $objWorksheet->getCell('D' . $i)->getValue(); // 목표
			$content = $objWorksheet->getCell('E' . $i)->getValue(); // 내용
			$activity = $objWorksheet->getCell('F' . $i)->getValue(); // 주요활동
			$chapterSize = $objWorksheet->getCell('G' . $i)->getValue(); // 차시분량(프레임수)
			$chapterMobileSize = $objWorksheet->getCell('H' . $i)->getValue(); // 모바일 차시분량(프레임수)
			$player = $objWorksheet->getCell('I' . $i)->getValue(); // 플레이어 경로
			$chapterPath = $objWorksheet->getCell('J' . $i)->getValue(); // 차시 경로
			$chapterMobilePath = $objWorksheet->getCell('K' . $i)->getValue(); // 모바일 경로

			if(!$chapterMobilePath) {
				$mobileQ = '';
			} else {
				$mobileQ = ", chapterMobilePath='".trim($chapterMobilePath)."', chapterMobileSize='".trim($chapterMobileSize)."'";
			}

			$queryQ = " contentsCode='".trim($contentsCode)."', 
								  chapter=".trim($chapter).", 
									chapterName='".addslashes(trim($chapterName))."', 
									goal='".addslashes(trim($goal))."', 
									content='".addslashes(trim($content))."', 
									activity='".addslashes(trim($activity))."', 
									professor='".trim($professor)."',
									player='".trim($player)."',
									chapterPath='".trim($chapterPath)."',
									chapterSize='".trim($chapterSize)."'".$mobileQ;

				$query01 = "INSERT INTO nynChapter SET ".$queryQ;
				$result01 = mysql_query($query01);

				if(!$result01) {
					$queryDel = "DELETE FROM nynChapter WHERE contentsCode='".$contentsCode."'";
					$resultDel = mysql_query($queryDel);
					echo "<script>alert('".$chapter."차시 등록 중 오류가 발생하였습니다.'); window.location.href='./05_contents.php?locaSel=0601';</script>";
					exit;
				}
		}

			echo "<script>alert('차시가 등록되었습니다.'); window.location.href='./05_contents.php?locaSel=0601';</script>";
			exit;
}

 catch (exception $e) {
    echo '엑셀파일을 읽는도중 오류가 발생하였습니다.';
		exit;
}
​?>