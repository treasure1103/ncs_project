<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") { // 회원 등록 및 수정은 POST로 받아옴
		$seq = $_POST['seq'];
		$userID = $_POST['userID'];
		$pwd = $_POST['pwd'];
		$userName = $_POST['userName'];
		$birth = $_POST['birth'];
		$sex = $_POST['sex'];
		$companyCode = $_POST['companyCode'];
		$phone01 = $_POST['phone01'];
		$phone02 = $_POST['phone02'];
		$phone03 = $_POST['phone03'];
		$mobile01 = $_POST['mobile01'];
		$mobile02 = $_POST['mobile02'];
		$mobile03 = $_POST['mobile03'];
		$email01 = $_POST['email01'];
		$email02 = $_POST['email02'];
		$zipCode = $_POST['zipCode'];
		$address01 = $_POST['address01'];
		$address02 = $_POST['address02'];
		$userLevel = $_POST['userLevel'];
		$smsReceive = $_POST['smsReceive'];
		$emailReceive = $_POST['emailReceive'];
		$memo = $_POST['memo'];
		$userDelete = $_POST['userDelete'];
		$commission = $_POST['commission'];
		$department = $_POST['department'];
		$bank = $_POST['bank'];
		$bankNum = $_POST['bankNum'];
		$pwdReset = $_POST['pwdReset'];
		$userIDChange = $_POST['userIDChange'];

		if($pwdReset == "Y") { // 비밀번호 초기화
			$hash = password_hash($birth, PASSWORD_DEFAULT);
			$queryR = "UPDATE nynMember SET pwd='".$hash."' WHERE seq=".$seq;
			$resultR = mysql_query($queryR);

			if($resultR){
				echo "success";
			} else {
				echo "error";
			}
			exit;
		}

		//비밀번호 수정인 경우 암호화 처리 후 수정 요청
		if($pwd != "") {
			$hash = password_hash($pwd, PASSWORD_DEFAULT);
			$pwdQ= "pwd='".$hash."', ";
		}
		if(trim($zipCode == "")) {
			$zipCode = "00000";
		}
		if($companyCode != "") {
			$companyCodeQ = " companyCode='".$companyCode."', ";
		}
		if($userLevel != "") {
			$userLevelQ = " userLevel='".$userLevel."', ";
			if($userLevel < 5) {
				$agreememtQ = " agreement='Y', agreeDate='".$inputDate."', ";
			}
		}
		if($memo != "") {
			$memoQ = " memo='".$memo."', ";
		}
		if($commission != "") {
			$commissionQ = " commission='".$commission."', ";
		}
		if($bank != "") {
			$bankQ = " bank='".$bank."', ";
		}
		//if($bankNum != "") {	//2017-07-25 이응민 수정
		//	$bankNumQ = " bankNum='".$bankNum."', ";
		//}
		//if($department != "") {
		//	$departmentQ = " department='".$department."', ";
		//}
		if($smsReceive == "Y") {
			$smsReceiveQ = " smsReceive='Y', ";
		} else {
			$smsReceiveQ = " smsReceive='N', ";
		}
		if($emailReceive == "Y") {
			$emailReceiveQ = " emailReceive='Y', ";
		} else {
			$emailReceiveQ = " emailReceive='N', ";
		}
		$queryQ =		$pwdQ.$userLevelQ.$memoQ.$commissionQ.$bankQ.$bankNumQ.$departmentQ.$smsReceiveQ.$emailReceiveQ." 
								userName='".$userName."',
								birth='".$birth."',
								sex='".$sex."',
								phone01='".$phone01."',
								phone02='".$phone02."',
								phone03='".$phone03."',
								mobile01='".$mobile01."',
								mobile02='".$mobile02."',
								mobile03='".$mobile03."',
								email01='".$email01."',
								email02='".$email02."',
								zipCode='".$zipCode."',
								bankNum='".$bankNum."',			
								department='".$department."',
								address01='".$address01."',
								address02='".$address02."'";	//2017-07-25 이응민 추가(bankNum,department)

		if($seq == "") { // 회원 등록

			//등록 시 아이디 중복 체크
			$queB="SELECT userID FROM nynMember WHERE userID='".$userID."'";
			$resultB = mysql_query($queB);
			$countB = mysql_num_rows($resultB);			

			if($countB > 0) {
				echo "error1";
				exit;
			}

			//회원 등록 처리
			$query = "INSERT INTO nynMember SET userID='".$userID."', inputDate='".$inputDate."', ".$agreememtQ.$companyCodeQ.$queryQ;
			$result = mysql_query($query);
			$seq = mysql_insert_id();

		} else { // 회원 수정

		if($_SESSION['loginUserLevel'] > 4) { // 관리자가 아니면
			if($_SESSION['loginUserID'] != $userID) { // 본인이 아닌경우 수정 불가
				echo "level error";
				exit;
			}
		} 

		//관리자가 직접 회원 탈퇴 처리
		if($_SESSION['loginUserLevel'] < 5) { 
			if($userDelete == 'Y') {

				$query = "UPDATE nynMember SET userDelete='Y' WHERE seq=".$seq;
				$result = mysql_query($query);
				$queryExit = "INSERT INTO nynMemberExit 
											SET userID='".$_SESSION['loginUserID']."',
													memo='관리자가 직접 탈퇴 처리',
													inputDate='".$inputDate."'";
				$resultExit = mysql_query($queryExit);
			}
		}

			//회원 레벨이 변경된 경우 기록을 남김
			$queB="SELECT userLevel FROM nynMember WHERE seq=".$seq;
			$resultB = mysql_query($queB);
			$rsB = mysql_fetch_assoc($resultB);
			$oUserLevel = $rsB[userLevel];

			if($oUserLevel != $userLevel) {
				$queryL = "INSERT INTO nynLevelChange 
									 SET userID='".$userID."', 
											 inputID='".$_SESSION['loginUserID']."',
											 inputDate='".$inputDate."',
											 oldLevel='".$oUserLevel."',
											 newLevel='".$userLevel."'";
				$resultL = mysql_query($queryL);
			}

			//회원 정보 수정
			if($userIDChange){ //2017.05.25 강혜림 추가 (아이디 수정시 다른 테이블에도 동시 ID값 수정가능)
				$qUserID = " userID='".$userIDChange."',";

				$queryB = "UPDATE nynStudy set userID='".$userIDChange."' where userID='".$userID."'";
				$result = mysql_query($queryB);
				$queryC = "UPDATE nynProgress set userID='".$userIDChange."' where userID='".$userID."'";
				$result = mysql_query($queryC);
				$queryD = "UPDATE nynTestAnswer set userID='".$userIDChange."' where userID='".$userID."'";
				$result = mysql_query($queryD);
				$queryF = "UPDATE nynReportAnswer set userID='".$userIDChange."' where userID='".$userID."'";
				$result = mysql_query($queryF);
				$queryE = "UPDATE nynSurveyAnswer set userID='".$userIDChange."' where userID='".$userID."'";
				$result = mysql_query($queryE);
			}

			$query = "UPDATE nynMember SET infoUpdate='".$inputDate."', ".$qUserID.$companyCodeQ.$queryQ." WHERE seq=".$seq;
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
			$userID = $_PUT['userID'];
			if(!$userID) {
				$userID = $_PUT['userIDChange'];
			}

			if(EREG(" ",$userID)) {
				echo '{"result" : "userID empty"}';
				exit;
			}

			$query = "SELECT userID FROM nynMember where userID='".$userID."'";
			$result = mysql_query($query);
			$rs = mysql_fetch_assoc($result);
			$userID = $rs[userID];
			if($userID == ""){
				echo '{"result" : "success"}';
			} else {
				echo '{"result" : "error"}';
			}
			exit;

	} else if($method == "DELETE") { // 본인이 직접 회원 탈퇴
			parse_str(file_get_contents("php://input"), $_DEL);
			//$userID = $_SESSION['loginUserID'];
			//$memo = $_DEL['memo'];

			//if($memo == ""){
			//	echo "error";
			//	exit;
			//}

			//$query = "UPDATE nynMember SET userDelete='Y' WHERE userID='".$userID."'";
			//$result = mysql_query($query);

			//$queryExit = "INSERT INTO nynMemberExit SET
			//								userID='".$userID."',
			//								memo='".$memo."',
			//								inputDate='".$inputDate."'";
			//$resultExit = mysql_query($queryExit);

			$memDel = $_DEL['memDel'];
			$memID = $_DEL['memID'];

			if($memID == ""){
				echo "error";
				exit;
			}

			if($memDel == "Y") {
				$queryC = "SELECT * FROM nynStudy WHERE userID='".$memID."'";
				$resultC = mysql_query($queryC);
				$countC = mysql_num_rows($resultC);
				
				if($countC == 0) { // 수강 내역이 없으면 회원정보 삭제
					$query = "DELETE FROM nynMember WHERE userID='".$memID."'";
					$result = mysql_query($query);

					if($result){
						echo "success";
					} else {
						echo "error";
					}
					exit;

				} else { // 수강 내역이 있으면 삭제 불가
					echo "no";
					exit;
				}
			}

	} else if($method == "GET") { // 회원 정보 불러옴
			$seq = $_GET['seq'];
			$userID = $_GET['userID'];
			$userIDLike = $_GET['userIDLike'];
			$userName = $_GET['userName'];
			$userDelete = $_GET['userDelete'];
			$userLevel = $_GET['userLevel'];
			$birth = $_GET['birth'];
			$sex = $_GET['sex'];
			$email = $_GET['email'];
			$mobile = $_GET['mobile'];
			$companyCode = $_GET['companyCode'];
			$companyName = $_GET['companyName'];
			$marketerName = $_GET['marketerName'];
			$tutorName = $_GET['tutorName'];
			$sqlLimit = $_GET['sqlLimit'];

			if(!$_SESSION['loginUserLevel']){
				$userLevelCheck = 10;
				$userIDCheck = '1';
			} else {
				$userLevelCheck = $_SESSION['loginUserLevel'];
				$userIDCheck = $_SESSION['loginUserID'];
			}

			switch($searchType){
				case "companyName":
					$companyName = $searchValue;
				break;

				case "userID":
					$userID = $searchValue;
				break;

				case "userIDLike":
					$userIDLike = $searchValue;
				break;

				case "userName":
					$userName = $searchValue;
				break;

				case "marketerName":
					$marketerName = $searchValue;
				break;

				case "mobile":
					$mobile = $searchValue;
				break;
			}

			if($sortType == "") {
				$sortType = "A.seq";
			} else {
				$sortType = "A.".$sortType;
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
			if($userDelete == "") {
				$qUserDel = " AND A.userDelete='N'";
			} else if($userDelete == "Y") {
				$qUserDel = " AND A.userDelete='".$userDelete."'";
			}
			if($userLevel != "") {
				$qUserLevel = " AND A.userLevel = ".$userLevel;
			}
			if($userID != "") {
				$qID = " AND A.userID = '".$userID."'";
			}
			if($userIDLike != "") {
				$qIDLink = " AND A.userID LIKE'%".$userIDLike."%'";
			}
			if($companyCode != "") {
				$qCCode = " AND A.companyCode ='".$companyName."'";
			}
			if($companyName != "") {
				$qCName = " AND C.companyName like '%".$companyName."%'";
			}
			if($email != "") {
				$qEmail = " AND A.email like '%".$email."%'";
			}
			if($seq != "") {
				$qSeq = " AND A.seq=".$seq;
			}
			if($userName != "") {
				$qName = " AND A.userName LIKE '%".$userName."%'";
			}
			if($marketerName != "") {
				$qMarketerName = " AND A.userName LIKE '%".$marketerName."%' AND userLevel='5'";
			}
			if($tutorName != "") {
				$qTutorName = " AND A.userName LIKE '%".$tutorName."%' AND userLevel='7'";
			}
			if($mobile != "") {
				$qMobile = " AND (A.mobile01='".$mobile."' OR A.mobile02='".$mobile."' OR A.mobile03='".$mobile."')";
			}
			if($userLevelCheck > 4) {
				$qMobile = " AND A.userID='".$userIDCheck."'";
			}
			
			$qSearch = $qUserDel.$qSeq.$qID.$qIDLink.$qName.$qBirth.$qSex.$qMobile.$qEmail.$qUserLevel.$qCCode.$qCName.$qMarketerName.$qTutorName;

			 $que = "SELECT count(*) cnt
							FROM nynMember A
							WHERE 1=1 ".$qSearch;
			$res = mysql_query($que);
			//$allPost = mysql_num_rows($res);
			$ars = mysql_fetch_assoc($res);
			$allPost = $ars['cnt'];
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지

			if($sqlLimit != "memberSearch"){
				$sqlLimit = ' limit '.$currentLimit.', '.$list; //limit sql 구문
			}else{
				$sqlLimit = '';
			}

			$query = "SELECT A.*, B.value02, C.companyName, C.zipCode AS Czip, C.address01 AS addC01, C.address02 AS addC02, C.companyID
								FROM nynMember A 
								LEFT OUTER 
								JOIN nynCategory B 
								ON A.userLevel=B.value01 AND B.division=
								(SELECT seq FROM nynCategory WHERE value01='userLevel')
								JOIN nynCompany C
								ON A.companyCode=C.companyCode
								WHERE A.seq <> 0 ".$qSearch." 
								ORDER BY ".$sortType." ".$sortValue.$sqlLimit;
			if($_SESSION['loginUserID'] == 'eungmin2'){
				//echo $query;
			}
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분
			$adminapi[totalCount] = "$allPost"; //총 개시물 수

			while($rs = mysql_fetch_array($result)) {
				$adminapi[member][$a][seq] = $rs[seq];
				$adminapi[member][$a][userID] = $rs[userID];
				$adminapi[member][$a][userName] = $rs[userName];
				$adminapi[member][$a][birth] = $rs[birth];
				$adminapi[member][$a][sex] = $rs[sex];

				switch($rs[sex]) {
						case "9":
						case "1":
						case "3":
						case "5":
						case "7":
								$sexName = "남성";
								break;
						case "0":
						case "2":
						case "4":
						case "6":
						case "8":
								$sexName = "여성";
								break;
				}

				$adminapi[member][$a][sexName] = $sexName;
				$adminapi[member][$a][phone01] = $rs[phone01];
				$adminapi[member][$a][phone02] = $rs[phone02];
				$adminapi[member][$a][phone03] = $rs[phone03];
				$adminapi[member][$a][mobile01] = $rs[mobile01];
				$adminapi[member][$a][mobile02] = $rs[mobile02];
				$adminapi[member][$a][mobile03] = $rs[mobile03];
				$adminapi[member][$a][email01] = $rs[email01];
				$adminapi[member][$a][email02] = $rs[email02];
				$adminapi[member][$a][zipCode] = $rs[zipCode];
				$adminapi[member][$a][address01] = $rs[address01];
				$adminapi[member][$a][address02] = $rs[address02];
				$adminapi[member][$a][company][companyCode] = $rs[companyCode];
				$adminapi[member][$a][company][companyName] = $rs[companyName];
				$adminapi[member][$a][company][department] = $rs[department];
				$adminapi[member][$a][company][companyID] = $rs[companyID];
				$adminapi[member][$a][company][address01] = $rs[addC01];
				$adminapi[member][$a][company][address02] = $rs[addC02];
				$adminapi[member][$a][userLevel][userGrade] = $rs[value02];
				$adminapi[member][$a][userLevel][userLevel] = $rs[userLevel];

				$queryLV = "SELECT A.oldLevel, A.inputDate, B.value02
										FROM nynLevelChange A
										LEFT OUTER 
										JOIN nynCategory B
										ON A.oldLevel=B.value01 AND B.division=
										(SELECT seq FROM nynCategory WHERE value01='userLevel')
										WHERE A.userID='".$rs[userID]."' ORDER BY A.seq DESC LIMIT 1";
				$resultLV = mysql_query($queryLV);

				$adminapi[member][$a][userLevel][oldGrade] = mysql_result($resultLV,0,'value02');
				$adminapi[member][$a][userLevel][oldLevel] = mysql_result($resultLV,0,'oldLevel');
				$adminapi[member][$a][userLevel][changeDate] = mysql_result($resultLV,0,'inputDate');
				$adminapi[member][$a][smsReceive] = $rs[smsReceive];
				$adminapi[member][$a][emailReceive] = $rs[emailReceive];
				$adminapi[member][$a][bank] = $rs[bank];
				$adminapi[member][$a][bankNum] = $rs[bankNum];
				$adminapi[member][$a][memo] = $rs[memo];
				if($rs[commission] == null) {
					$commission = '0';
				} else {
					$commission = $rs[commission];
				}
				$adminapi[member][$a][commission] = $commission;
				$adminapi[member][$a][loginIP] = $rs[loginIP];
				$adminapi[member][$a][loginTime] = $rs[loginTime];
				$adminapi[member][$a][infoUpdate] = $rs[infoUpdate];
				$adminapi[member][$a][inputDate] = $rs[inputDate];
				$adminapi[member][$a][agreement] = $rs[agreement];
				$adminapi[member][$a][agreeDate] = $rs[agreeDate];
				$adminapi[member][$a][userDelete][userDelete] = $rs[userDelete];

				if($rs[userDelete] == "Y") { // 탈퇴회원인 경우 탈퇴 사유 출력
					$queryUD = "SELECT memo, inputDate FROM nynMemberExit WHERE userID='".$rs[userID]."'"; 
					$resultUD = mysql_query($queryUD);

					$adminapi[member][$a][userDelete][userDelete] = $rs[userDelete];
					$adminapi[member][$a][userDelete][memo] = mysql_result($resultUD,0,'memo');
					$adminapi[member][$a][userDelete][inputDate] = mysql_result($resultUD,0,'inputDate');
				}

				$a++;
			}
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
		
	@mysql_close();
?>