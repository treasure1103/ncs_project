<? include '../lib/header.php'; ?>
<?
error_reporting(E_ALL & ~E_NOTICE);
ini_set("display_errors", 1);
// 로딩 이미지 출력 
$loading_html = "<div class='loadingScreen'><img src='../images/global/loading.gif' alt='loading'></div>";
echo $loading_html; 
ob_start(); 
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
			$tutor = $objWorksheet->getCell('C' . $i)->getValue(); //첨삭 강사
			$userName = $objWorksheet->getCell('D' . $i)->getValue(); // 문항 번호
			$birth = $objWorksheet->getCell('E' . $i)->getValue(); // 생년월일
			$sex = $objWorksheet->getCell('F' . $i)->getValue(); // 성별
			$mobile = $objWorksheet->getCell('G' . $i)->getValue(); // 휴대폰
			$email = $objWorksheet->getCell('H' . $i)->getValue(); // 이메일
			$companyCode = $objWorksheet->getCell('I' . $i)->getValue(); // 사업자 번호
			$department = $objWorksheet->getCell('K' . $i)->getValue(); // 소속
			$lectureStart = $objWorksheet->getCell('L' . $i)->getValue(); // 시작
			$lectureEnd = $objWorksheet->getCell('M' . $i)->getValue(); // 종료
			$lectureReStudy = $objWorksheet->getCell('N' . $i)->getValue(); // 복습
			$price = $objWorksheet->getCell('O' . $i)->getValue(); // 교육비
			$rPrice = $objWorksheet->getCell('P' . $i)->getValue(); // 환급비
			$serviceType = $objWorksheet->getCell('Q' . $i)->getValue(); // 서비스 구분
			$newUserID = $objWorksheet->getCell('R' . $i)->getValue(); // 아이디 직접 지정 값
			$newPwd = $objWorksheet->getCell('S' . $i)->getValue(); // 비밀번호 직접 지정 값
			$mobile00 = EXPLODE('-',$mobile);
			$email00 = EXPLODE('@',$email);
			$mobile01 = $mobile00[0]; 
			$mobile02 = $mobile00[1];
			$mobile03 = $mobile00[2];
			$email01 = $email00[0];
			$email02 = $email00[1];


			if($contentsCode){
				if($newPwd != '') { // 비밀번호 직접 지정 값
					$pwd = $newPwd;
				} else { // 기본값은 생일4자리
					$pwd = '1111';
				}
				$hash = password_hash("$pwd", PASSWORD_DEFAULT);
				$lectureStart = PHPExcel_Style_NumberFormat::toFormattedString($lectureStart, PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
				$lectureEnd = PHPExcel_Style_NumberFormat::toFormattedString($lectureEnd, PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);
				$lectureReStudy = PHPExcel_Style_NumberFormat::toFormattedString($lectureReStudy, PHPExcel_Style_NumberFormat::FORMAT_DATE_YYYYMMDD2);

				if(!$mobile01) {
					$mobile01 = '010';
				}
				if(!$mobile02) {
					$mobile02 = '0000';
				}
				if(!$mobile01) {
					$mobile03 = '0000';
				}
				if(!$tutor) {
					$tutor = 'tutor';
				}			
				if(!$price) {
					$price = '0';
				}
				if(!$rPrice) {
					$rPrice = '0';
				}

				//회원사 등록 검사
				$queryC = "SELECT companyID FROM nynCompany WHERE companyCode='".$companyCode."'";
				$resultC = mysql_query($queryC);
				$rsC = mysql_fetch_array($resultC);
				$comCnt = mysql_num_rows($resultC);

				if($comCnt == 0) { // 신규아이디도 중복이면 $a 값을 붙임.
					echo "회원사 정보를 찾을 수 없습니다. 회원사 등록이 되었는지 확인하시기 바랍니다.";
					exit;
				} else {
					$comID = $rsC[companyID];
				}

				//회원등록 중복검사
				$queryA = "SELECT userID, agreement, mobile01, mobile02, mobile03, email01, email02 FROM nynMember WHERE companyCode='".$companyCode."' AND userName='".$userName."' AND birth='".$birth."' AND userLevel='9' AND sex='".$sex."'";
				$resultA = mysql_query($queryA);
				$rsA = mysql_fetch_array($resultA);
				$idCheck = $rsA[userID];
				$agreement = $rsA[agreement];

				if($idCheck) { // 기존회원이면 아이디 가져옴
					$userID = $idCheck;

					if($agreement == 'Y') {
						$mobile01 = $rsA[mobile01];
						$mobile02 = $rsA[mobile02];
						$mobile03 = $rsA[mobile03];
						if($rsA[email01] != '') {
							$email01 = $rsA[email01];
							$email02 = $rsA[email02];
						}
					}

				} else { // 신규회원이면
					if($newUserID != '') { // 아이디를 직접 지정했다면
						$userID = $newUserID;
						$userIDY = 1;

					} else { // 중복조회 후 동일인인지 확인하여 기존 아이디 값 사용
						$queryZ = "SELECT userID FROM nynTempRegister WHERE companyCode='".$companyCode."' and userName='".$userName."' AND birth='".$birth."' AND sex='".$sex."'";
						$resultZ = mysql_query($queryZ);
						$rsZ = mysql_fetch_array($resultZ);

						if($rsZ['userID']){ // 중복조회 후 동일인인지 확인하여 기존 아이디 값 사용
							$userID = $rsZ['userID'];
							$userIDY = 0;

						} else { // 동일인이 아닌 경우 신규 아이디 생성
							$userID = $comID.trim($birth);  // companyID + 생년월일
							//회사아이디 앞1자리 삭제 : 2017-02-08
							//$userID = trim($birth).$mobile00[2];  // 생년월일 + 휴대폰 뒷 4자리 + 숫자 조합으로 아이디 생성
							$userIDY = 1;
						}
					}

					$a = 0;

					while($userIDY > 0){ //신규발급 아이디 중복검사 시작
						$queryZ = "SELECT userID FROM nynMember WHERE userID='".$userID."'";
						$resultZ = mysql_query($queryZ);
						$rsZ = mysql_num_rows($resultZ);

						if($rsZ == 0){
							$queryB = "SELECT userID FROM nynTempRegister WHERE userID='".$userID."'";
							$resultB = mysql_query($queryB);
							$userIDY = mysql_num_rows($resultB);

							if($userIDY == 1) { // 신규아이디도 중복이면 $a 값을 붙임.
								//$userID = $comID.trim($birth).$mobile00[2].$a;
								//회사아이디 앞1자리 삭제 : 2017-02-08
								$userID = $comID.trim($birth).$a;
							}
						} else {
						//	$a = 1;
							$userID = $comID.trim($birth).$a;
						}		
						$a++;
					}
				}

				$queryQ = " contentsCode='".trim($contentsCode)."', 
										tutor='".trim($tutor)."', 
										userName='".trim($userName)."', 
										birth='".trim($birth)."', 
										sex='".trim($sex)."', 
										userID='".trim($userID)."', 
										pwd='".$hash."', 
										mobile01='".$mobile01."', 
										mobile02='".$mobile02."', 
										mobile03='".$mobile03."', 
										email01='".$email01."', 
										email02='".$email02."', 
										companyCode='".$companyCode."', 
										department='".trim($department)."', 
										lectureStart='".trim($lectureStart)."', 
										lectureEnd='".trim($lectureEnd)."', 
										lectureReStudy='".trim($lectureReStudy)."',
										price='".trim($price)."',
										rPrice='".trim($rPrice)."',
										serviceType='".trim($serviceType)."',
										inputDate='".$inputDate."'";

				$queryM = " INSERT INTO nynTempRegister
										SET ".$queryQ;
				
				$resultM = mysql_query($queryM);
			}
		}
		echo "성공!";
		header("Location: /admin/04_study_input.php?locaSel=0403");
		exit;
}

 catch (exception $e) {
    echo '엑셀파일을 읽는도중 오류가 발생하였습니다.';
		exit;
}
		ob_end_flush(); // 버퍼의 내용을 출력한 후 현재 출력 버퍼를 종료 
​?>