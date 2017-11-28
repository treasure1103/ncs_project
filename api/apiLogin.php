<?php
//error_reporting(E_ALL & ~E_NOTICE);
//ini_set("display_errors", 1);
  //Parse error: syntax error, unexpected '[' in /home/ncsweb/lib/userAgent/src/Agent.php on line 15
	header('Content-Type:application/json; charset=utf-8');
	include '../lib/header.php';
	require_once '../lib/mobileDetect/Mobile_Detect.php';
	//require_once '../lib/userAgent/src/Agent.php';
	//use Jenssegers\Agent\Agent;
	//$agent = new Agent();
	if($method == "POST") { // 로그인 시 아이디 비번 체크

			$userID = $_POST['userID'];
			$pwd = $_POST['pwd'];

			$queryI = "SELECT A.userID, 
									A.pwd, 
									A.userName, 
									A.userLevel,
									A.companyCode,
									A.agreement,
									B.value02 AS userLevelName, 
									B.value03 AS userType
						FROM nynMember A 
						LEFT OUTER 
						JOIN nynCategory B
						ON A.userLevel=B.value01 
						WHERE A.userID='".$userID."' 
						AND B.division=(SELECT seq FROM nynCategory WHERE value01='userLevel') 
						AND A.userDelete <> 'Y'";
			$resultI = mysql_query($queryI);
			$rsI = mysql_fetch_assoc($resultI);
			$originalPwd = $rsI[pwd];

				if(password_verify($pwd, $originalPwd)){

					$_SESSION['loginUserID'] = $rsI[userID];
					$_SESSION['loginUserName'] = $rsI[userName];
					$_SESSION['loginUserLevel'] = $rsI[userLevel];
					$_SESSION['loginUserLevelName'] = $rsI[userLevelName];
					$_SESSION['loginCompanyCode'] = $rsI[companyCode];
					
					// 로그인 시 중복체크 확인
					// 세션테이블에 다른컴터로 로그인한 정보가 있는지 체크
					 $SSQL = "SELECT count(seq) cnt FROM nynSession WHERE userID='".$userID."' " ;// and userID != 'admin'
					 $SRS  = mysql_query($SSQL);
					 $SR   = mysql_fetch_assoc($SRS);
	
					// 같은 아이디로 세션이 존재 하는 경우
					 if( $SR["cnt"] > 0 ){
						$duplication = "Y";

						//loginUserID|s:5:"seet3";loginUserName|s:9:"강혜림";loginUserLevel|s:1:"2";loginUserLevelName|s:9:"관리자";loginCompanyCode|s:10:"7838700353";
					
						//===========================================================	
						// 2017년 11월 15일 수요일 추가
						// 이전 로그인 정보 무조건 삭제처리
						@mysql_query("DELETE FROM nynSession Where  userID='".$userID."' ");
						//===========================================================	
					 }

						//===========================================================	
						// 2017년 11월 15일 수요일 추가
						// 무조건 insert 한다.

							$expiry = time() + $maxlifetime;

							$sess_data = "loginUserID|s:5:".$_SESSION['loginUserID'].";loginUserName|s:9:".$_SESSION['userName'].";loginUserLevel|s:1:".$_SESSION['userLevel'].";loginUserLevelName|s:9:".$_SESSION['userLevelName'].";loginCompanyCode|s:10:".$_SESSION['companyCode'];

							$ISQL =  "INSERT INTO nynSession (sesskey, expiry, value, userID, security, remoteIP, inputDate) 
										VALUES ('". session_id() ."', '". $expiry ."', '".$sess_data."', '".$_SESSION['loginUserID'] ."', 'O', '$userIP', now())" ;
							mysql_query($ISQL);
						//===========================================================	

						 //history 등록
						 insert_logincheck( $rsI[userID]);
						 /*
						  $deviceCheck = rtn_mobile_chk();
							$queryA = " INSERT INTO nynMemberHistory
													SET userID = '".$rsI[userID]."',
													device = '".$deviceCheck."', 
													OS = '".$agent->platform()."',
													browser = '".$agent->browser()."', 
													browserVersion = '".$agent->version($agent->browser())."', 
													loginTime = now(),
													loginIP = '".$_SERVER["REMOTE_ADDR"]."'";
							$resultA = mysql_query($queryA);
							*/

							$queryJ = " UPDATE nynMember SET loginTime='".$inputDate."', loginIP='".$userIP."'
													WHERE userID='".$userID."'";
							$resultJ = mysql_query($queryJ);
						 if($rsI[agreement] == "Y") {
							 if($duplication == "Y") {
								 echo "duplication";
							 } else {
								 echo "success";
							 }
						 } else {
							 if($duplication == "Y") {
								 echo "agreement2";
							 } else {
								 echo "agreement";
							 }
						 }
						 exit;
	
				} else { 
					echo "error";
				}
				exit;

	} else if($method == "PUT") {
			parse_str(file_get_contents("php://input"),$_PUT);
			$userID = $_PUT['userID'];
			$userName = $_PUT['userName'];
			$birth01 = $_PUT['birth01'];
			$birth02 = $_PUT['birth02'];
			$birth03 = $_PUT['birth03'];
			$birth = $birth01.$birth02.$birth03;
			$mobile01 = $_PUT['mobile01'];
			$mobile02 = $_PUT['mobile02'];
			$mobile03 = $_PUT['mobile03'];

			if($userID != ""){ // 비밀번호 찾기 (받는 값에 아이디가 있으면)
				$query = "SELECT userID, mobile01, mobile02, mobile03
									FROM nynMember 
									WHERE userID='".$userID."' 
									AND userName='".$userName."' 
									AND userDelete='N' 
									AND birth='".$birth."' 
									AND mobile01='".$mobile01."'
									AND mobile02='".$mobile02."'
									AND mobile03='".$mobile03."'";
				$result = mysql_query($query);
				$rs = mysql_fetch_assoc($result);
				$userID = $rs[userID];
				$mobile01 = $rs[mobile01];
				$mobile02 = $rs[mobile02];
				$mobile03 = $rs[mobile03];
				$receivePhone = $mobile01.$mobile02.$mobile03;
				$sendPhone = $_smsNumber;

					if($userID != "") {
						$newPW = generateRenStr(6,'P');
						$hash = password_hash($newPW, PASSWORD_DEFAULT);
						$queryZ = "UPDATE nynMember SET pwd='".$hash."' WHERE userID='".$userID."'";
						$resultZ = mysql_query($queryZ);
/*
						$toMail = $email01."@".$email02;
						$fromMail = "admin@nayanet.kr";
						$subject = "[".$_siteName."] 비밀번호 안내입니다.";
						$content = "안녕하세요 ".$_siteName."입니다.<br />임시 비밀번호가 발급되었으니 로그인 후 비밀번호를 수정하시기 바랍니다.<br />임시비밀번호 : ".$newPW;
						$filepath = "";
						$var = "";
						mail_fsend($toMail, $fromMail, $subject, $content, '', '', '', $filepath, $var);
*/
						$message = "[".$_siteName."] 다음 비밀번호로 로그인하세요 : ".$newPW;
						insert_emma($receivePhone,$sendPhone,$message);
						echo $userID;

					} else {
						echo "error";
					}
					exit;

			} else { // 아이디 찾기 (받는 값에 아이디가 없으면)
				$query = "SELECT userID 
									FROM nynMember 
									WHERE userName='".$userName."' 
									AND userDelete='N' 
									AND birth='".$birth."' 
									AND mobile01='".$mobile01."'
									AND mobile02='".$mobile02."'
									AND mobile03='".$mobile03."'";
				$result = mysql_query($query);
				$rs = mysql_fetch_assoc($result);
				$userID = $rs[userID];

					if($userID != "") {
						echo $userID;
					} else {
						echo "error";
					}
					exit;
			}

	} else if($method == "DELETE") { // 로그아웃 세션종료
			parse_str(file_get_contents("php://input"),$_DEL);
			if($_SESSION['loginUserID']) {
				//$query = "UPDATE nynSession SET security='X' WHERE userID='".$_SESSION['loginUserID']."' AND security='O'";
				$query = "DELETE FROM nynSession WHERE userID='".$_SESSION['loginUserID']."'";
				mysql_query($query);
			}

			$_SESSION['loginUserID'] = "";
			$_SESSION['loginUserName'] = "";
			$_SESSION['loginUserLevel'] =  "";
			$_SESSION['loginUserLevelName'] = "";
			$_SESSION['loginCompanyCode'] = "";
					
			session_unset('loginUserID');
			session_unset('loginUserName');
			session_unset('loginUserLevel');
			session_unset('loginUserLevelName');
			session_unset('loginCompanyCode');
			session_unset();

			session_destroy();
			echo "success";
			exit;

	} else if($method == "GET") { // 아이디 중복 체크
			$userID = $_GET['userID'];
			if($userID == "") {
				echo '{"result" : "error"}';
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
	}


		
		@mysql_close();
?>