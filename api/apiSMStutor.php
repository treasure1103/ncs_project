<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {

		//예약발송, 임의메시지 설정 추가
		$device = $_POST[device];
		if($device == "") {
			$device = "emailTutor";
		}
		$sendType = $_POST[sendType]; //발송목적
		$sendReserve = $_POST[sendReserve];
		$messageBox = $_POST[messageBox];
		$sendMessage = $_POST[sendMessage];
		$loopNum = count($_POST['sendType']);

		//회사 기본 정보 가져옴
		$queryA = "SELECT * FROM nynCompany WHERE companyID='".$CompanyID."'";
		$resultA = mysql_query($queryA);
		$rsA = mysql_fetch_array($resultA);

		$companyName = $rsA[companyName];
		$domain = $rsA[siteURL];
		$sendPhone = $rsA[phone01].$rsA[phone02].$rsA[phone03];

		//문자 메시지 가져옴
		$queryB = " SELECT 
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='start') AS sendStart, 
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='end') AS sendEnd,
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='push') AS sendPush";
		$resultB = mysql_query($queryB);
		$rsB = mysql_fetch_array($resultB);

		$sendStart = $rsB[sendStart];
		$send0 = $rsB[send0];
		$send30 = $rsB[send30];
		$send50 = $rsB[send50];
		$send79 = $rsB[send79];
		$sendFinal = $rsB[sendFinal];
		$sendEnd = $rsB[sendEnd];

		//건별 데이터 확인 후 발송 작업 시작
		for($i=0; $i<$loopNum; $i++) {
			//keyValue[0] = 발송목적, keyValue[1] = 아이디, keyValue[2] = 과정개설차수
			$keyValue = explode('/',$sendType[$i]);
			
			//연락처 및 기간 출력
			$queryC = " SELECT DISTINCT(A.contentsCode), A.lectureStart, A.lectureEnd, B.mobile01, B.mobile02, B.mobile03, B.email01, B.email02, B.userName
									FROM nynStudy AS A
									LEFT OUTER
									JOIN nynMember AS B ON A.tutor=B.userID
									WHERE A.tutor='".$keyValue[1]."' 
									AND A.lectureOpenSeq='".$keyValue[2]."'";
			$resultC = mysql_query($queryC, $bd);
			$rsC = mysql_fetch_array($resultC);

			$lectureStart = $rsC[lectureStart];
			$lectureEnd = $rsC[lectureEnd];
			$contentsCode = $rsC[contentsCode];
			$receivePhone = $rsC[mobile01].$rsC[mobile02].$rsC[mobile03];
			$receiveEmail = $rsC[email01]."@".$rsC[email02];
			$lectureResult00 = date("Y-m-d", strtotime($lectureEnd."+4day"));
			$lectureResult01 = substr($lectureResult00,5,2);
			$lectureResult02 = substr($lectureResult00,8,2);
			$lectureResult = $lectureResult01."월 ".$lectureResult02."일";

			switch ($keyValue[0]) {
					case "start":
							$message = $sendStart;
							$typeName = "배정안내";
							break;

					case "end":
							$message = $sendEnd;
							$typeName = "첨삭시작";
							break;

					case "push":
							$message = $sendPush;
							$typeName = "미첨삭독려";
							break;
			}

			//발송메시지 내용 변수 replace
			$message = str_replace("{시작}",$lectureStart,$message);
			$message = str_replace("{종료}",$lectureEnd,$message);
			$message = str_replace("{회사명}",$companyName,$message);
			$message = str_replace("{도메인}",$domain,$message);
			$message = str_replace("{아이디}",$keyValue[1],$message);
			$message = str_replace("{완료}",$lectureResult,$message);

			if($sendReserve == "Y") { // 예약 발송이면 시간 받음
				$sendTime = $_POST[reserveTime];
				$sendTimeLog = $_POST[reserveTime];
			} else {
				$sendTimeLog = $inputDate;
			}

			if($messageBox == "Y") { // 직접 작성이면 메시지 받음
				$message = $_POST[sendMessage];
			}

			if($device == "smsTutor") { // 문자 발송
				insert_emma($receivePhone,$sendPhone,$message,$sendTime);
				$receiveTarget = $receivePhone;
				$sendTarget = $sendPhone;

			} else { // 이메일 발송
				$toMail = $receiveEmail;
				$fromMail = "no_reply@nayanet.kr";
				$subject = "[이상에듀] 첨삭 배정 안내 드립니다.";
				$content = $message;
				//$filepath = $_SERVER["DOCUMENT_ROOT"]."/member/join_mail.php";
				$filepath = "";
				$var = "";
				$receiveTarget = $toMail;
				$sendTarget = $fromMail;
				mail_fsend($toMail, $fromMail, $subject, $content, '', '', '', $filepath, $var);
			}

			//발송 내역(log) 저장
			$query = " INSERT INTO nynSendLog 
									SET lectureStart='".$lectureStart."', 
											lectureEnd='".$lectureEnd."', 
											contentsCode='".$contentsCode."',
											companyCode='0000000000',
											receiveTarget='".$receiveTarget."',
											sendTarget='".$sendTarget."',
											message='".$message."', 
											sendDate='".$sendTimeLog."', 
											userID='".$keyValue[1]."', 
											sendID='".$_SESSION[loginUserID]."',
											lectureOpenSeq='".$keyValue[2]."', 
											sendMethod='".$device."', 
											inputDate='".$inputDate."', 
											sendType='".$keyValue[0]."'";
			$result = mysql_query($query, $bd);
		}

			if($result) {
				echo '{"result" : "success"}';
			} else {
				echo '{"result" : "error"}';
			}
			exit;

	} else if($method == "GET") { // 메시지 미리보기
		$sendType = $_GET[sendType];
		$device = $_GET[device];
		if($device == "") {
			$device = "emailTutor";
		}

		//회사 기본 정보 가져옴
		$queryA = "SELECT * FROM nynCompany WHERE companyID='".$CompanyID."'";
		$resultA = mysql_query($queryA);
		$rsA = mysql_fetch_array($resultA);

		$companyName = $rsA[companyName];
		$domain = $rsA[siteURL];
		$phone = $rsA[phone01].$rsA[phone02].$rsA[phone03];

		//문자 메시지 가져옴
		$queryB = " SELECT 
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='start') AS sendStart, 
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='end') AS sendEnd,
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='push') AS sendPush";
		$resultB = mysql_query($queryB);
		$rsB = mysql_fetch_array($resultB);

		$sendStart = $rsB[sendStart];
		$sendEnd = $rsB[sendEnd];
		$sendPush = $rsB[sendPush];

		$keyValue = explode('/',$sendType);

		//연락처 및 기간 출력
		$queryC = " SELECT A.lectureStart, A.lectureEnd, B.mobile01, B.mobile02, B.mobile03, B.email01, B.email02, B.userName
								FROM nynStudy AS A
								LEFT OUTER
								JOIN nynMember AS B ON A.tutor=B.userID
								WHERE A.tutor='".$keyValue[1]."' 
								AND A.lectureOpenSeq='".$keyValue[2]."'";
		$resultC = mysql_query($queryC);
		$rsC = mysql_fetch_array($resultC);

		$lectureStart = $rsC[lectureStart];
		$lectureEnd = $rsC[lectureEnd];
		$userName = $rsC[userName];
		$mobile = $rsC[mobile01].$rsC[mobile02].$rsC[mobile03];
		$email = $rsC[email01].'@'.$rsC[email02];
		$lectureResult00 = date("Y-m-d", strtotime($lectureEnd."+4day"));
		$lectureResult01 = substr($lectureResult00,5,2);
		$lectureResult02 = substr($lectureResult00,8,2);
		$lectureResult = $lectureResult01."월 ".$lectureResult02."일";

		switch ($keyValue[0]) {
				case "start":
						$message = $sendStart;
						$typeName = "배정안내";
						break;

				case "end":
						$message = $sendEnd;
						$typeName = "첨삭시작";
						break;

				case "push":
						$message = $sendPush;
						$typeName = "미첨삭독려";
						break;
		}

		//발송메시지 내용 변수 replace
		$message = str_replace("{시작}",$lectureStart,$message);
		$message = str_replace("{종료}",$lectureEnd,$message);
		$message = str_replace("{회사명}",$companyName,$message);
		$message = str_replace("{도메인}",$domain,$message);
		$message = str_replace("{아이디}",$keyValue[1],$message);
		$message = str_replace("{완료}",$lectureResult,$message);
		$adminapi = array();

		$adminapi[device] = $device;
		$adminapi[typeName] = $typeName;
		$adminapi[message] = $message;
		$adminapi[userID] = $keyValue[1];
		$adminapi[userName] = $userName;
		$adminapi[companyName] = $companyName;
		$adminapi[receiveNum] = $mobile;
		$adminapi[sendNum] = $phone;
		$adminapi[email] = $email;

		$json_encoded = json_encode($adminapi);
		print_r($json_encoded);
	}
?>