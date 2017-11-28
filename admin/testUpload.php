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

			//중복 등록 확인
			$originCodeD = $objWorksheet->getCell('A' . 2)->getValue();
			$testType = $objWorksheet->getCell('N' . 2)->getValue(); // 중간,기말
			$queryCheck = "SELECT * FROM nynTest WHERE originCode='".$originCodeD."' AND testType='".$testType."'";
			$resultCheck = mysql_query($queryCheck);
			$count = mysql_num_rows($resultCheck);
			if($count > 0) {
				echo "이미 등록되어 있습니다. 재등록을 원하는 경우 전체 삭제 후 재등록 하시기 바랍니다.";
				exit;
			}

		for($i = 2; $i <= $maxRow; $i++) {
			$originCode = $objWorksheet->getCell('A' . $i)->getValue(); //과정 코드
			$originCode = trim($originCode);
			//$originCode = explode('\n', trim($originCode)); 

			if($originCode != '') { // 과정코드가 있을 경우만 등록
			$sourceChapter = $objWorksheet->getCell('B' . $i)->getValue(); //출처 차시
			$examNum = $objWorksheet->getCell('C' . $i)->getValue(); // 문항 번호
			$examNum = trim($examNum);
			$exam = $objWorksheet->getCell('D' . $i)->getValue(); // 문제
			$example01 = $objWorksheet->getCell('E' . $i)->getValue(); // 보기1
			$example02 = $objWorksheet->getCell('F' . $i)->getValue(); // 보기2
			$example03 = $objWorksheet->getCell('G' . $i)->getValue(); // 보기3
			$example04 = $objWorksheet->getCell('H' . $i)->getValue(); // 보기4
			$example05 = $objWorksheet->getCell('I' . $i)->getValue(); // 보기5
			$answer = $objWorksheet->getCell('J' . $i)->getValue(); // 정답
			$commentary = $objWorksheet->getCell('K' . $i)->getValue(); // 설명
			$score = $objWorksheet->getCell('L' . $i)->getValue(); // 배점
			$score = trim($score);
			$examType = $objWorksheet->getCell('M' . $i)->getValue(); // 유형
			$testType = $objWorksheet->getCell('N' . $i)->getValue(); // 중간,기말

			if(strlen($originCode) != 6) {
				$queryDel = "DELETE FROM nynTest WHERE originCode='".$originCode."'";
				$resultDel = mysql_query($queryDel);
				$queryDel02 = "DELETE FROM nynTestExample WHERE originCode='".$originCode."'";
				$resultDel02 = mysql_query($queryDel02);
				$queryDel03 = "DELETE FROM nynTestMapping WHERE contentsCode='".$originCode."'";
				$resultDel03 = mysql_query($queryDel03);
				echo "과정코드값 자리수가 잘못되었습니다. 앞뒤로 공백이 있는지 확인 바랍니다.<br /><br />평가구분: ".$testType.", 문항번호 ".$examNum."번 과정코드";
				exit;
			}

			$queryQ = " originCode='".$originCode."', 
						sourceChapter='".addslashes(trim($sourceChapter))."', 
						examNum=".$examNum.", 
						exam='".addslashes(trim($exam))."', 
						commentary='".addslashes(trim($commentary))."', 
						score=".$score.", 
						testType='".trim($testType)."',
						examType='".trim($examType)."'";

			if($examType == "A" || $examType == "D") { // 객관식, 진위형 저장
				$query01 = "INSERT INTO nynTest SET
											answer=".$answer.", "
											.$queryQ;
				$result01 = mysql_query($query01);
				$testSeq = mysql_insert_id();

				if(!$result01) {
					$queryDel = "DELETE FROM nynTest WHERE originCode='".$originCode."'";
					$resultDel = mysql_query($queryDel);
					$queryDel02 = "DELETE FROM nynTestExample WHERE originCode='".$originCode."'";
					$resultDel02 = mysql_query($queryDel02);
					$queryDel03 = "DELETE FROM nynTestMapping WHERE contentsCode='".$originCode."'";
					$resultDel03 = mysql_query($queryDel03);
					echo "등록 중 오류가 발생하였습니다.<br /><br />문제유형: ".$testType.", 문항번호 ".$examNum."번 지문";
					exit;
				}
				$a = 1;

				//$loopNum = $_POST['loopNum']; // 보기문항 수
				if($examType == "D") {
					$loopNum = 2;
				} else if($examType == "A") {
					if($example05 != "") {
						$loopNum = 5;
					} else {
						$loopNum = 4;
					}
				}

				while($a<=$loopNum && $loopNum>0) { //보기문항 저장
					$example = ${"example0".$a};
					$query02 = "INSERT INTO nynTestExample SET
												testSeq=".$testSeq.", 
												originCode='".trim($originCode)."', 
												exampleNum=".$a.", 
												example='".addslashes(trim($example))."'";
					$result02 = mysql_query($query02);

					if(!$result02) {
						$queryDel = "DELETE FROM nynTest WHERE originCode='".$originCode."'";
						$resultDel = mysql_query($queryDel);
						$queryDel02 = "DELETE FROM nynTestExample WHERE originCode='".$originCode."'";
						$resultDel02 = mysql_query($queryDel02);
						$queryDel03 = "DELETE FROM nynTestMapping WHERE contentsCode='".$originCode."'";
						$resultDel03 = mysql_query($queryDel03);
						echo "등록 중 오류가 발생하였습니다.<br /><br />평가구분: ".$testType.", 문항번호 ".$examNum."번 보기항목 ".$a;
						exit;
					}
					$a++;
				}

			} else { // 단답형이나 서술형 저장
				$query03 = "INSERT INTO nynTest set
											answerText='".addslashes(trim($answer))."', "
											.$queryQ;
				$result03 = mysql_query($query03);
				$testSeq = mysql_insert_id();
				if(!$result03) {
					$queryDel = "DELETE FROM nynTest WHERE originCode='".$originCode."'";
					$resultDel = mysql_query($queryDel);
					$queryDel02 = "DELETE FROM nynTestExample WHERE originCode='".$originCode."'";
					$resultDel02 = mysql_query($queryDel02);
					$queryDel03 = "DELETE FROM nynTestMapping WHERE contentsCode='".$originCode."'";
					$resultDel03 = mysql_query($queryDel03);
					echo "등록 중 오류가 발생하였습니다.<br /><br />평가구분: ".$testType.", 문항번호 ".$examNum."번 지문";
					exit;
				}
			}
			
			$queryM = "INSERT INTO nynTestMapping SET
										contentsCode='".$originCode."', 
										testType='".$testType."', 
										testSeq=".$testSeq;
			$resultM = mysql_query($queryM);


			}
		}
		echo "문제 등록 성공!";
		header("Location: /admin/05_contents.php?locaSel=0601");
		exit;
}

 catch (exception $e) {
    echo '엑셀파일을 읽는도중 오류가 발생하였습니다.';
		exit;
}
​?>