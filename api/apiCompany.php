<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") { // 회원사 등록 및 수정은 POST로 받는다.
		$seq = $_POST['seq'];
		$companyCode = str_replace("-","",$_POST['companyCode']);
		$companyName = $_POST['companyName'];
		$companyID = $_POST['companyID'];
		$hrdCode = $_POST['hrdCode'];
		$ceoName = $_POST['ceoName'];
		$zipCode = $_POST['zipCode'];
		$address01 = $_POST['address01'];
		$address02 = $_POST['address02'];
		$phone01 = $_POST['phone01'];
		$phone02 = $_POST['phone02'];
		$phone03 = $_POST['phone03'];
		$fax01 = $_POST['fax01'];
		$fax02 = $_POST['fax02'];
		$fax03 = $_POST['fax03'];
		$bank = $_POST['bank'];
		$bankNum = $_POST['bankNum'];
		$kind = $_POST['kind'];
		$part = $_POST['part'];
		$siteURL = $_POST['siteURL'];
		$cyberURL = $_POST['cyberURL'];
		$managerID = $_POST['managerID'];
		$elecEmail01 = $_POST['elecEmail01'];
		$elecEmail02 = $_POST['elecEmail02'];
		$companyScale = $_POST['companyScale'];
		$marketerID = $_POST['marketerID'];
		$studyEnabled = $_POST['studyEnabled'];
		$memo = $_POST['memo'];
		$stamp = $_FILES['stamp'];
		$requestForm = $_FILES['requestForm'];

		if($managerID != "") {
			$managerIDQ = " managerID='".$managerID."', ";
		}
		if($marketerID != "") {
			$marketerIDQ = " marketerID='".$marketerID."', ";
		}
		if($cyberURL) {
			$cyberURL = " cyberURL='".$cyberURL."', ";
		}
		if($studyEnabled == "N") {
			$cyberURL = " cyberURL='".$_siteURL."', ";
		}

		if($stamp) { // 도장 파일이 있으면 서버 업로드
			$attachFile01Name = "stamp.jpg";
			$attachURL = "/attach/studyCenter/";
			$uploadDir = $_SERVER['DOCUMENT_ROOT'].$attachURL;
			$attachFile01Temp = $stamp['tmp_name']; // 업로드 파일 임시저장파일
			$attachFile01Path = $attachURL.$attachFile01Name;
			$attachFile01Save = $uploadDir.$attachFile01Name;
			@move_uploaded_file($attachFile01Temp, $attachFile01Save);
		}

		if($requestForm) { // 도장 파일이 있으면 서버 업로드
			$attachFile02Name = "request_form.zip";
			$attachURL2 = "/attach/docs/";
			$uploadDir2 = $_SERVER['DOCUMENT_ROOT'].$attachURL2;
			$attachFile02Temp = $requestForm['tmp_name']; // 업로드 파일 임시저장파일
			$attachFile02Path = $attachURL2.$attachFile02Name;
			$attachFile02Save = $uploadDir2.$attachFile02Name;
			@move_uploaded_file($attachFile02Temp, $attachFile02Save);
		}


		$queryQ =  "companyName='".$companyName."',
					companyCode='".$companyCode."',
					hrdCode='".$hrdCode."',
					ceoName='".$ceoName."',
					zipCode='".$zipCode."',
					address01='".$address01."',
					address02='".$address02."',
					phone01='".$phone01."',
					phone02='".$phone02."',
					phone03='".$phone03."',
					fax01='".$fax01."',
					fax02='".$fax02."',
					fax03='".$fax03."',
					bank='".$bank."',
					bankNum='".$bankNum."',
					kind='".$kind."',
					part='".$part."',
					siteURL='".$siteURL."',
					elecEmail01='".$elecEmail01."',
					elecEmail02='".$elecEmail02."',
					companyScale='".$companyScale."',
					studyEnabled='".$studyEnabled."', 
					memo='".$memo."'";

				if($studyEnabled == "Y") { // 교육센터를 사용할 경우 studtyCenter 등록 처리
					$queryY = "SELECT * FROM nynStudyCenter WHERE companyID='".$companyID."'";
					$resultY = mysql_query($queryY);
					$countY = mysql_num_rows($resultY);

					if($countY == 0) {
						if($companyID != "") {
							$querySI = "INSERT INTO nynStudyCenter SET companyID='".$companyID."', inputDate='".$inputDate."'";
							mysql_query($querySI);
						}
					}
				}

			if($seq == "") { // 회원사 정보 등록

				if($managerID == "") {
					//교육담당자 검색
					$qureryM = "SELECT userID FROM nynMember WHERE companyCode='".$companyCode."' and userLevel='8'";
					$resultM = mysql_query($qureryM);
					$rsM = mysql_fetch_array($resultM);

					if($rsM['userID'] == ""){ // 등록된 아이디가 없으면 아이디 생성, 아이디 : 사업자번호
						$managerPwd = '1111'; // 비번 1111
						$hash = password_hash("$managerPwd", PASSWORD_DEFAULT);

						$qureryMU = " INSERT INTO nynMember 
													SET userID='".$companyCode."', pwd='".$hash."', userName='".$companyName."', birth='010203', 
															mobile01='010', mobile02='0000', mobile03='0000', 
															email01='', email02='', userLevel='8', companyCode='".$companyCode."', 
															agreement='Y', agreeDate='".$inputDate."', inputDate='".$inputDate."'";
						$resultMU = mysql_query($qureryMU);
						$managerIDQ = " managerID='".$companyCode."', ";
					}
				}

				$query = "INSERT INTO nynCompany SET companyID='".$companyID."', ".$cyberURL.$managerIDQ.$marketerIDQ." inputDate='".$inputDate."', ".$queryQ;
				$result = mysql_query($query);
				$seq = mysql_insert_id();

			} else { // 회원사 정보 수정
				$query = "UPDATE nynCompany SET ".$cyberURL.$managerIDQ.$marketerIDQ." updateDate='".$inputDate."', ".$queryQ." WHERE seq=".$seq;
				$result = mysql_query($query);
			}

				if($result){
					echo $seq;
				} else {
					echo "error";
				}
				exit;

	} else if($method == "PUT") { // 아이디 중복 체크
		parse_str(file_get_contents("php://input"), $_PUT);
		$companyID = $_PUT['companyID'];
		$companyCode = $_PUT['companyCode'];
		if($companyID){
			$query = "SELECT companyID FROM nynCompany where companyID='".$companyID."'";
			$result = mysql_query($query);
			$rs = mysql_fetch_assoc($result);
			$companyID = $rs[companyID];
			if($companyID == ""){
				echo '{"result" : "success"}';
			} else {
				echo '{"result" : "error"}';
			}
			exit;
		}else {
			$query = "SELECT companyCode FROM nynCompany where companyCode='".$companyCode."'";
			$result = mysql_query($query);
			$rs = mysql_fetch_assoc($result);
			$companyCode = $rs[companyCode];
			if($companyCode == ""){
				echo '{"result" : "success"}';
			} else {
				echo '{"result" : "error"}';
			}
			exit;
		}

	} else if($method == "DELETE") { // 회원사 정보 삭제
			parse_str(file_get_contents("php://input"), $_DEL);
			$seq = $_DEL['seq'];
			$query = "DELETE FROM nynCompany WHERE seq=".$seq;
			$result = mysql_query($query);

			if($result){
				echo "success";
			} else {
				echo "error";
			}
			exit;

	} else if($method == "GET") { // 회원사 정보 불러옴
			$list = $_GET['list'];
			$page = $_GET['page'];
			$seq = $_GET['seq'];
			$companyCode = $_GET['companyCode'];
			$companyID = $_GET['companyID'];
			$companyName = $_GET['companyName'];
			$companyScale = $_GET['companyScale'];

			switch($searchType){
				case "companyCode":
					$companyCode = $searchValue;
				break;

				case "companyID":
					$companyID = $searchValue;
				break;

				case "companyName":
					$companyName = $searchValue;
				break;

				case "companyScale":
					$companyScale = $searchValue;
				break;
			}

			if($sortType == "") {
				$sortType = "A.seq";
			}
			if($sortValue == "") {
				$sortValue = "DESC";
			}

			if($list == "") {
				$list = 10;
			}
			if($page == "") {
				$page = 1;
			}
			if($seq != "") {
				$qSeq = " and A.seq='".$seq."'";
			}
			if($companyCode != "") {
				$qCompanyCode = " and A.companyCode='".$companyCode."'";
			}
			if($companyID != "") {
				$qCompanyID = " and A.companyID like '%".$companyID."%'";
			}
			if($companyName != "") {
				$qCompanyName = " and A.companyName like '%".$companyName."%'";
			}
			if($companyScale != "") {
				$qCompanyScale = " and A.companyScale='".$companyScale."'";
			}
			if($_SESSION[loginUserLevel] == '5' || $_SESSION[loginUserLevel] == '6') {
				$qMarketer = "AND A.marketerID='".$_SESSION[loginUserID]."'";
			}

			$qSearch = $qSeq.$qCompanyCode.$qCompanyID.$qCompanyName.$qCompanyScale.$qMarketer;

			$que = "SELECT A.*, 
										 B.userID, 
										 B.userName, 
										 B.mobile01, 
										 B.mobile02, 
										 B.mobile03, 
										 B.email01, 
										 B.email02,
										 C.userName AS marketerName 
							FROM nynCompany A
							LEFT OUTER
							JOIN nynMember B
							ON A.managerID=B.userID
							LEFT OUTER
							JOIN nynMember C
							ON A.marketerID=C.userID
							WHERE A.seq <> 0 ".$qSearch;
			$res = mysql_query($que);
			$allPost = mysql_num_rows($res);
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' limit '.$currentLimit.', '.$list; //limit sql 구문

			$query = "SELECT A.*, 
											 B.userID, 
											 B.userName, 
											 B.mobile01, 
											 B.mobile02, 
											 B.mobile03, 
											 B.email01, 
											 B.email02,
											 C.userName AS marketerName 
								FROM nynCompany A
								LEFT OUTER
								JOIN nynMember B
								ON A.managerID=B.userID
								LEFT OUTER
								JOIN nynMember C
								ON A.marketerID=C.userID
								WHERE A.seq <> 0 ".$qSearch." ORDER BY ".$sortType." ".$sortValue.$sqlLimit;
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			$adminapi[totalCount] = "$allPost"; //총 개시물 수

			while($rs = mysql_fetch_array($result)) {
				$adminapi[company][$a][seq] = $rs[seq];
				$adminapi[company][$a][companyCode] = $rs[companyCode];
				$adminapi[company][$a][companyName] = $rs[companyName];
				$adminapi[company][$a][companyScale] = $rs[companyScale];
				$adminapi[company][$a][hrdCode] = $rs[hrdCode];
				$adminapi[company][$a][zipCode] = $rs[zipCode];
				$adminapi[company][$a][address01] = $rs[address01];
				$adminapi[company][$a][address02] = $rs[address02];
				$adminapi[company][$a][phone01] = $rs[phone01];
				$adminapi[company][$a][phone02] = $rs[phone02];
				$adminapi[company][$a][phone03] = $rs[phone03];
				$adminapi[company][$a][fax01] = $rs[fax01];
				$adminapi[company][$a][fax02] = $rs[fax02];
				$adminapi[company][$a][fax03] = $rs[fax03];
				$adminapi[company][$a][elecEmail01] = $rs[elecEmail01];
				$adminapi[company][$a][elecEmail02] = $rs[elecEmail02];
				$adminapi[company][$a][ceoName] = $rs[ceoName];
				$adminapi[company][$a][bank] = $rs[bank];
				$adminapi[company][$a][bankNum] = $rs[bankNum];
				$adminapi[company][$a][kind] = $rs[kind];
				$adminapi[company][$a][part] = $rs[part];
				$adminapi[company][$a][siteURL] = $rs[siteURL];
				$adminapi[company][$a][cyberURL] = $rs[cyberURL];
				if($rs[managerID] == "" || $rs[managerID] == null){
					$adminapi[company][$a][manager][ID] = "미등록";
					$adminapi[company][$a][manager][name] = "미등록";
					$adminapi[company][$a][manager][mobile] = "미등록";
					$adminapi[company][$a][manager][email] = "미등록";
				} else {
					$adminapi[company][$a][manager][ID] = $rs[managerID];
					$adminapi[company][$a][manager][name] = $rs[userName];
					$adminapi[company][$a][manager][mobile] = $rs[mobile01]."-".$rs[mobile02]."-".$rs[mobile03];
					$adminapi[company][$a][manager][email] = $rs[email01]."@".$rs[email02];
				}
				if($rs[marketerID] == "" || $rs[marketerName] == null){
					$adminapi[company][$a][marketer][ID] = "미등록";
					$adminapi[company][$a][marketer][name] = "미등록";
				} else {
					$adminapi[company][$a][marketer][ID] = $rs[marketerID];
					$adminapi[company][$a][marketer][name] = $rs[marketerName];
				}

				$adminapi[company][$a][studyEnabled] = $rs[studyEnabled];
				$adminapi[company][$a][companyID] = $rs[companyID];
				$adminapi[company][$a][memo] = $rs[memo];
				$adminapi[company][$a][inputDate] = $rs[inputDate];
				$adminapi[company][$a][updateDate] = $rs[updateDate];
				$a++;
			}
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
?>