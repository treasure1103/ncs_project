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

		for($i = 2; $i <= $maxRow; $i++) {

			for( $j=1, $a='A'; $j <=51; $j++, $a++) {
				$excelData[$a] = $objWorksheet->getCell($a . $i)->getValue();
			}

			if(trim($excelData['AY']) != "") {
				$contentsCode = trim($excelData['AY']);
			} else {
				$contentsCodeY = 1;
				while($contentsCodeY > 0){ // contentsCode 6자리 임의 생성 후 중복 검사
					$contentsCode = generateRenStr(6,'C');
					$codeCheck = "SELECT contentsCode FROM nynContents WHERE contentsCode = '".$contentsCode."'";
					$resultCheck = mysql_query($codeCheck);
					$contentsCodeY = mysql_num_rows($resultCheck);
				}
			}

				//integer형 데이터 항목에 값이 없는 경우 0으로 변경함
				if(trim($excelData['F'] != "")) {
					$chapter = "chapter='".trim($excelData['F'])."', ";
				} else {
					$chapter = "chapter='0', ";
				}
				if(trim($excelData['G'] != "")) {
					$contentsTime = "contentsTime='".trim($excelData['G'])."', ";
				} else {
					$contentsTime = "contentsTime='0', ";
				}
				if(trim($excelData['H'] != "")) {
					$price = "price='".trim($excelData['H'])."', ";
				} else {
					$price = "price='0', ";
				}
				if(trim($excelData['I'] != "")) {
					$rPrice01 = "rPrice01='".trim($excelData['I'])."', ";
				} else {
					$rPrice01 = "rPrice01='0', ";
				}
				if(trim($excelData['J'] != "")) {
					$rPrice02 = "rPrice02='".trim($excelData['J'])."', ";
				} else {
					$rPrice02 = "rPrice02='0', ";
				}
				if(trim($excelData['K'] != "")) {
					$rPrice03 = "rPrice03='".trim($excelData['K'])."', ";
				} else {
					$rPrice03 = "rPrice03='0', ";
				}
				if(trim($excelData['M'] != "")) {
					$mid01EA = "mid01EA='".trim($excelData['M'])."', ";
				} else {
					$mid01EA = "mid01EA='0', ";
				}
				if(trim($excelData['N'] != "")) {
					$mid01Score = "mid01Score='".trim($excelData['N'])."', ";
				} else {
					$mid01Score = "mid01Score='0', ";
				}
				if(trim($excelData['O'] != "")) {
					$mid02EA = "mid02EA='".trim($excelData['O'])."', ";
				} else {
					$mid02EA = "mid02EA='0', ";
				}
				if(trim($excelData['P'] != "")) {
					$mid02Score = "mid02Score='".trim($excelData['P'])."', ";
				} else {
					$mid02Score = "mid02Score='0', ";
				}
				if(trim($excelData['Q'] != "")) {
					$mid03EA = "mid03EA='".trim($excelData['Q'])."', ";
				} else {
					$mid03EA = "mid03EA='0', ";
				}
				if(trim($excelData['R'] != "")) {
					$mid03Score = "mid03Score='".trim($excelData['R'])."', ";
				} else {
					$mid03Score = "mid03Score='0', ";
				}
				if(trim($excelData['S'] != "")) {
					$mid04EA = "mid04EA='".trim($excelData['S'])."', ";
				} else {
					$mid04EA = "mid04EA='0', ";
				}
				if(trim($excelData['T'] != "")) {
					$mid04Score = "mid04Score='".trim($excelData['T'])."', ";
				} else {
					$mid04Score = "mid04Score='0', ";
				}
				if(trim($excelData['U'] != "")) {
					$test01EA = "test01EA='".trim($excelData['U'])."', ";
				} else {
					$test01EA = "test01EA='0', ";
				}
				if(trim($excelData['V'] != "")) {
					$test01Score = "test01Score='".trim($excelData['V'])."', ";
				} else {
					$test01Score = "test01Score='0', ";
				}
				if(trim($excelData['W'] != "")) {
					$test02EA = "test02EA='".trim($excelData['W'])."', ";
				} else {
					$test02EA = "test02EA='0', ";
				}
				if(trim($excelData['X'] != "")) {
					$test02Score = "test02Score='".trim($excelData['X'])."', ";
				} else {
					$test02Score = "test02Score='0', ";
				}
				if(trim($excelData['Y'] != "")) {
					$test03EA = "test03EA='".trim($excelData['Y'])."', ";
				} else {
					$test03EA = "test03EA='0', ";
				}
				if(trim($excelData['Z'] != "")) {
					$test03Score = "test03Score='".trim($excelData['Z'])."', ";
				} else {
					$test03Score = "test03Score='0', ";
				}
				if(trim($excelData['AA'] != "")) {
					$test04EA = "test04EA='".trim($excelData['AA'])."', ";
				} else {
					$test04EA = "test04EA='0', ";
				}
				if(trim($excelData['AB'] != "")) {
					$test04Score = "test04Score='".trim($excelData['AB'])."', ";
				} else {
					$test04Score = "test04Score='0', ";
				}
				if(trim($excelData['AC'] != "")) {
					$reportEA = "reportEA='".trim($excelData['AC'])."', ";
				} else {
					$reportEA = "reportEA='0', ";
				}
				if(trim($excelData['AD'] != "")) {
					$reportScore = "reportScore='".trim($excelData['AD'])."', ";
				} else {
					$reportScore = "reportScore='0', ";
				}
				if(trim($excelData['AE'] != "")) {
					$midRate = "midRate='".trim($excelData['AE'])."', ";
				} else {
					$midRate = "midRate='0', ";
				}
				if(trim($excelData['AF'] != "")) {
					$testRate = "testRate='".trim($excelData['AF'])."', ";
				} else {
					$testRate = "testRate='0', ";
				}
				if(trim($excelData['AG'] != "")) {
					$reportRate = "reportRate='".trim($excelData['AG'])."', ";
				} else {
					$reportRate = "reportRate='0', ";
				}
				if(trim($excelData['AH'] != "")) {
					$passProgress = "passProgress='".trim($excelData['AH'])."', ";
				} else {
					$passProgress = "passProgress='0', ";
				}
				if(trim($excelData['AI'] != "")) {
					$totalPassMid = "totalPassMid='".trim($excelData['AI'])."', ";
				} else {
					$totalPassMid = "totalPassMid='0', ";
				}
				if(trim($excelData['AJ'] != "")) {
					$totalPassTest = "totalPassTest='".trim($excelData['AJ'])."', ";
				} else {
					$totalPassTest = "totalPassTest='0', ";
				}
				if(trim($excelData['AK'] != "")) {
					$passTest = "passTest='".trim($excelData['AK'])."', ";
				} else {
					$passTest = "passTest='0', ";
				}
				if(trim($excelData['AL'] != "")) {
					$totalPassReport = "totalPassReport='".trim($excelData['AL'])."', ";
				} else {
					$totalPassReport = "totalPassReport='0', ";
				}
				if(trim($excelData['AM'] != "")) {
					$passReport = "passReport='".trim($excelData['AM'])."', ";
				} else {
					$passReport = "passReport='0', ";
				}
				if(trim($excelData['AN'] != "")) {
					$passScore = "passScore='".trim($excelData['AN'])."', ";
				} else {
					$passScore = "passScore='0', ";
				}
				if(trim($excelData['AO'] != "")) {
					$testTime = "testTime='".trim($excelData['AO'])."', ";
				} else {
					$testTime = "testTime='0', ";
				}
				if(trim($excelData['AT'] != "")) {
					$commission = "commission='".trim($excelData['AT'])."', ";
				} else {
					$commission = "commission='0', ";
				}
				if(trim($excelData['AU'] != "")) {
					$mobile = "mobile='".trim($excelData['AU'])."', ";
				} else {
					$mobile = "mobile='N', ";
				}

				$queryQ =  $chapter.$contentsTime.$price.$rPrice01.$rPrice02.$rPrice03.$mid01EA.$mid01Score.$mid02EA.$mid02Score.$mid03EA.$mid03Score.$mid04EA.$mid04Score;
				$queryQ .= $test01EA.$test01Score.$test02EA.$test02Score.$test03EA.$test03Score.$test04EA.$test04Score.$reportEA.$reportScore.$midRate.$testRate.$reportRate;
				$queryQ .= $passProgress.$totalPassMid.$totalPassTest.$passTest.$totalPassReport.$passReport.$passScore.$testTime.$commission.$mobile;

				$queryQ .= "serviceType='".trim($excelData['A'])."', 
										contentsName='".addslashes(trim($excelData['B']))."', 
										sort01='".trim($excelData['C'])."', 
										sort02='".trim($excelData['D'])."', 
										professor='".trim($excelData['L'])."', 
										intro='".addslashes(trim($excelData['AP']))."', 
										target='".addslashes(trim($excelData['AQ']))."', 
										goal='".addslashes(trim($excelData['AR']))."', 
										cp='".trim($excelData['AS'])."', 
										sourceType='".trim($excelData['AV'])."', 
										bookIntro='".trim($excelData['AW'])."', 
										enabled='".trim($excelData['AX'])."'";

				if(trim($excelData['AY']) != "") {
					$query01 = "UPDATE nynContents SET ".$queryQ." WHERE contentsCode='".$contentsCode."'";
				} else {
					if(trim($excelData['B'])) {
						$query01 = "INSERT INTO nynContents SET contentsCode='".$contentsCode."', ".$queryQ;
					}
				}
				$result01 = mysql_query($query01);

				$test01EA.$test01Score.$test02EA.$test02Score.$test03EA.$test03Score.$reportEA.$reportScore;

				if($result01) {
					$k = $k+1;
				} else {
					echo $k."줄 과정 데이터에 오류가 있습니다.(이전 데이터까지 등록완료)";
					exit;
				}
		}
			echo $k."과정 등록 성공!";
			header("Location: /admin/05_contents.php?locaSel=0601");
			exit;
}

 catch (exception $e) {
    echo '엑셀파일을 읽는도중 오류가 발생하였습니다.';
		exit;
}
​?>