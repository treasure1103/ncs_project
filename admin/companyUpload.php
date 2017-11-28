<? include '../lib/header.php'; ?>
<?
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
			$companyName = $objWorksheet->getCell('A' . $i)->getValue(); // 회사명
			$newCompanyID = $objWorksheet->getCell('B' . $i)->getValue(); // 사업주아이디
			$companyScale = $objWorksheet->getCell('C' . $i)->getValue(); //회사규모
			$companyCode = $objWorksheet->getCell('D' . $i)->getValue(); // 사업자등록번호	
			$hrdCode = $objWorksheet->getCell('E' . $i)->getValue(); // HRD번호
			$ceoName = $objWorksheet->getCell('F' . $i)->getValue(); // 대표자명
			$address01 = $objWorksheet->getCell('G' . $i)->getValue(); // 주소
			$kind = $objWorksheet->getCell('H' . $i)->getValue(); // 업태
			$part = $objWorksheet->getCell('I' . $i)->getValue(); // 업종
			$phone = $objWorksheet->getCell('J' . $i)->getValue(); // 회사 전화번호
			$elecEmail = $objWorksheet->getCell('K' . $i)->getValue(); // 전자계산서 메일
			$managerName = $objWorksheet->getCell('L' . $i)->getValue(); // 교육담당자명
			$managerPhone = $objWorksheet->getCell('M' . $i)->getValue(); // 교육담당자 연락처
			$managerEmail = $objWorksheet->getCell('N' . $i)->getValue(); // 교육담당자 이메일
			$marketerName = $objWorksheet->getCell('O' . $i)->getValue(); // 영업담당자
			$marketerID = $objWorksheet->getCell('P' . $i)->getValue(); // 영업담당자 아이디
			$staffName = $objWorksheet->getCell('Q' . $i)->getValue(); // 운영담당자명 (내부운영자)
			$staffID = $objWorksheet->getCell('R' . $i)->getValue(); // 운영담당자 아이디
			$memo = $objWorksheet->getCell('S' . $i)->getValue(); // 메모
			
			if($companyName){
				$companyCode = str_replace('-','',$companyCode); // "-" 입력시 오류나서 한번 가공함
				$phone = EXPLODE('-',$phone);
				$managerPhone = EXPLODE('-',$managerPhone);
				$elecEmail = EXPLODE('@',$elecEmail);
				$managerEmail = EXPLODE('@',$managerEmail);
				$phone01 = $phone[0]; 
				$phone02 = $phone[1]; 
				$phone03 = $phone[2]; 
				$managerPhone01 = $managerPhone[0]; 
				$managerPhone02 = $managerPhone[1]; 
				$managerPhone03 = $managerPhone[2]; 
				$elecEmail01 = $elecEmail[0];
				$elecEmail02 = $elecEmail[1];
				$managerEmail01 = $managerEmail[0];
				$managerEmail02 = $managerEmail[1];
				
				if(!$phone01) {
					$mobile01 = '010';
				}
				if(!$phone02) {
					$phone02 = '0000';
				}
				if(!$phone03) {
					$phone03 = '0000';
				}
				if(!$managerPhone01) {
					$managerPhone01 = '010';
				}
				if(!$managerPhone02) {
					$managerPhone02 = '0000';
				}
				if(!$managerPhone03) {
					$managerPhone03 = '0000';
				}
				if(!$elecEmail01) {
					$elecEmail01 = "";
				}			
				if(!$elecEmail02) {
					$elecEmail02 = "";
				}
				if(!$address01) {
					$address01 = '';
				}
				if(!$newCompanyID) {
					$newCompanyID = $companyCode;
				}
				if(!$companyScale){
					$companyScale = "C";
				}
				
				//사업주정보등록 중복검사
				$queryA = "SELECT companyCode FROM nynCompany WHERE companyCode='".$companyCode."'";
				$resultA = mysql_query($queryA);
				$count = mysql_num_rows($resultA);
				$rsA = mysql_fetch_array($resultA);

				$queryQ ="companyName='".trim($companyName)."',
									companyScale='".trim($companyScale)."',
									companyCode='".trim($companyCode)."',
									hrdCode='".trim($hrdCode)."',
									ceoName='".trim($ceoName)."',
									address01='".trim($address01)."',
									kind='".trim($kind)."',
									part='".trim($part)."',
									phone01='".trim($phone01)."',
									phone02='".trim($phone02)."',
									phone03='".trim($phone03)."',
									elecEmail01='".trim($elecEmail01)."',
									elecEmail02='".addslashes(trim($elecEmail02))."',
									managerID='".trim($companyCode)."',
									staffID='".trim($staffID)."',
									marketerID='".trim($marketerID)."',
									zipCode='00000',
									memo='".trim($memo)."'";

				if($count > 0) { // 이미 등록된 사업주정보가 있으면 정보를 수정
					$queryMI = "UPDATE nynCompany SET ".$queryQ."	WHERE companyCode='".$companyCode."'";
					$resultMI = mysql_query($queryMI);

				} else { // 사업주 정보가 없으면 등록

					$queryCH = "SELECT companyID, companyName FROM nynCompany WHERE companyID='".trim($newCompanyID)."'";
					$resultCH = mysql_query($queryCH);
					$countCH = mysql_num_rows($resultCH);
					$rsCH = mysql_fetch_array($resultCH);
					$companyNameSame = $rsCH['companyName'];

					if($countCH > 0) {
						echo $companyName." 의 회사아이디가 ".$companyNameSame. " 회사와 동일하여 오류가 발생하였습니다. 다른 회사 아이디로 변경 후 등록해주시기 바랍니다.";
						exit;
					}

					$queryMI = "INSERT INTO nynCompany SET ".$queryQ.", companyID='".trim($newCompanyID)."', inputDate='".$inputDate."'";
					$resultMI = mysql_query($queryMI);
				}

				if($resultMI){
				} else {
					echo $companyName." 에서 오류가 발생하였습니다.";
					exit;
				}

				//교육담당자 검색
				$qureryM = "SELECT userID FROM nynMember WHERE userID='".$companyCode."'";
				$resultM = mysql_query($qureryM);
				$rsM = mysql_fetch_array($resultM);

				if($rsM['userID'] == ""){ // 등록된 아이디가 없으면 아이디 생성, 아이디 : 사업자번호
					//$managerPwd = substr($companyCode,-5);
					$managerPwd = '1111'; // 비번 1111
					$hash = password_hash("$managerPwd", PASSWORD_DEFAULT);

					$qureryMU = " INSERT INTO nynMember 
												SET userID='".$companyCode."', pwd='".$hash."', userName='".$managerName."', birth='010203', 
														mobile01='".$managerPhone01."', mobile02='".$managerPhone02."', mobile03='".$managerPhone03."', 
														email01='".$managerEmail01."', email02='".$managerEmail02."', userLevel='8', companyCode='".$companyCode."', 
														agreement='Y', agreeDate='".$inputDate."', inputDate='".$inputDate."'";
					$resultMU =mysql_query($qureryMU);

					if($resultMU){
					} else {
						echo trim($companyName)." 교육담당자 등록 시 오류가 발생하였습니다.";
						exit;
					}
				}
	
			}
		}
		echo "성공!";
		header("Location: /admin/17_companyUpload.php?locaSel=0203");
		exit;
}

 catch (exception $e) {
    echo '엑셀파일을 읽는도중 오류가 발생하였습니다.';
		exit;
}
		ob_end_flush(); // 버퍼의 내용을 출력한 후 현재 출력 버퍼를 종료 
​?>