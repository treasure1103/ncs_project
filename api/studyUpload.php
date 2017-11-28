<? include '../lib/header.php'; ?>
<?
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
set_time_limit(300);
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
			$serviceType = $objWorksheet->getCell('A' . $i)->getValue(); //서비스구분
			$contentsCode = $objWorksheet->getCell('B' . $i)->getValue(); //과정코드
			$tutor = $objWorksheet->getCell('C' . $i)->getValue(); // 강사아이디
			$userName = $objWorksheet->getCell('D' . $i)->getValue(); // 이름
			$birth = $objWorksheet->getCell('E' . $i)->getValue(); // 생년월일
			$sex = $objWorksheet->getCell('F' . $i)->getValue(); // 성별
			$companyCode= $objWorksheet->getCell('G' . $i)->getValue(); // 사업자번호
			$lectureStart = $objWorksheet->getCell('H' . $i)->getValue(); // 수강시작일
			$lectureEnd = $objWorksheet->getCell('I' . $i)->getValue(); // 수강종료일
			$lectureReStudy = $objWorksheet->getCell('J' . $i)->getValue(); // 복습일
			$price = $objWorksheet->getCell('K' . $i)->getValue(); // 교육비
			$rPrice = $objWorksheet->getCell('L' . $i)->getValue(); // 환급비

			$query = "SELECT userID FROM nynMember WHERE userName='".$userName."' AND sex='".$sex."'";
			$result = mysql_query($query);
			$userID = mysql_result($result,0,'userID');

			$queryQ = " serviceType='".trim($serviceType)."', 
								  contentsCode='".trim($contentsCode)."', 
									tutor=".trim($tutor).", 
									userID='".$userID."', 
									companyCode='".trim($companyCode)."', 
									lectureStart='".trim($lectureStart)."', 
									lectureEnd='".trim($content)."', 
									lectureReStudy='".trim($lectureReStudy)."', 
									price='".trim($price)."', 
									rPrice='".trim($rPrice)."'";

				$query01 = "INSERT INTO nynStudy SET ".$queryQ;
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