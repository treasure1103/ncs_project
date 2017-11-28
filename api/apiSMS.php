<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {

		//예약발송, 임의메시지 설정 추가
		$device = $_POST[device];
		if($device == "") {
			$device = "email";
		}
		$sendType = $_POST[sendType]; //발송목적
		$sendReserve = $_POST[sendReserve];  //예약발송여부
		$messageBox = $_POST[messageBox];
		$sendMessage = $_POST[sendMessage];
		$loopNum = count($_POST['sendType']);

		//회사 기본 정보 가져옴
		$queryA = "SELECT * FROM nynCompany WHERE companyID='".$CompanyID."'";
		$resultA = mysql_query($queryA);
		$rsA = mysql_fetch_array($resultA);

		$companyName = $rsA['companyName'];
		$domain = $rsA['siteURL'];
		$sendPhone = $_smsNumber;

		//문자 메시지 가져옴
		$queryB = " SELECT 
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='start') AS sendStart, 
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='0') AS send0, 
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='30') AS send30, 
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='50') AS send50, 
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='70') AS send70, 
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='final') AS sendFinal,
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='end') AS sendEnd,
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='result') AS sendResult";
		$resultB = mysql_query($queryB);
		$rsB = mysql_fetch_array($resultB);

		$sendStart = $rsB[sendStart];
		$send0 = $rsB[send0];
		$send30 = $rsB[send30];
		$send50 = $rsB[send50];
		$send70 = $rsB[send70];
		$sendFinal = $rsB[sendFinal];
		$sendEnd = $rsB[sendEnd];
		$sendResult = $rsB[sendResult];

		//건별 데이터 확인 후 발송 작업 시작
		for($i=0; $i<$loopNum; $i++) {
			//keyValue[0] = 발송목적, keyValue[1] = 아이디, keyValue[2] = 과정개설차수
			$keyValue = explode('/',$sendType[$i]);
			
			//연락처 및 기간 출력
			$queryC = " SELECT A.lectureStart, A.lectureEnd, A.companyCode, A.contentsCode, B.mobile01, B.mobile02, B.mobile03, B.email01, B.email02, 
									B.userName, B.smsReceive, B.emailReceive, B.agreement,
									C.companyName AS companyBelongName, C.studyEnabled, C.cyberURL, D.contentsName
									FROM nynStudy AS A
									LEFT OUTER
									JOIN nynMember AS B ON A.userID=B.userID
									LEFT OUTER
									JOIN nynCompany AS C ON B.companyCode=C.companyCode
									LEFT OUTER
									JOIN nynContents AS D ON A.contentsCode=D.contentsCode
									WHERE A.userID='".$keyValue[1]."' 
									AND A.lectureOpenSeq='".$keyValue[2]."'";
			$resultC = mysql_query($queryC);
			$rsC = mysql_fetch_array($resultC);

			$lectureStart = $rsC[lectureStart];
			$lectureEnd = $rsC[lectureEnd];
			$companyCode = $rsC[companyCode];
			$contentsCode = $rsC[contentsCode];
			$contentsName = $rsC[contentsName];
			$userName = $rsC[userName];

			$studyEnabled = $rsC[studyEnabled];
			$cyberURL = $rsC[cyberURL];
			$mobile = $rsC[mobile01].$rsC[mobile02].$rsC[mobile03];
			$email = $rsC[email01].'@'.$rsC[email02];

			if($studyEnabled == 'Y') {
				$domain = $cyberURL;
			}
			$receivePhone = $rsC[mobile01].$rsC[mobile02].$rsC[mobile03];
			$receiveEmail = $rsC[email01]."@".$rsC[email02];
			$smsReceive = $rsC[smsReceive];
			$emailReceive = $rsC[emailReceive];
			$agreement = $rsC[agreement];
			$companyBelongName = $rsC[companyBelongName];

			switch ($keyValue[0]) {
					case "start":
							$message = $sendStart;
							break;

					case "0":
							$message = $send0;
							break;

					case "30":
							$message = $send30;
							break;

					case "50":
							$message = $send50;
							break;

					case "70":
							$message = $send70;
							break;

					case "final":
							$message = $sendFinal;
							break;

					case "end":
							$message = $sendEnd;
							break;

					case "result":
							$message = $sendResult;
							$queryX = " SELECT inputDate FROM nynStudyEnd 
													WHERE gubun='resultView' AND lectureStart='".$lectureStart."' AND 
																lectureEnd='".$lectureEnd."' AND companyCode='".$companyCode."'";
							$resultX = mysql_query($queryX);
							$rsX = mysql_fetch_array($resultX);
							$countL = mysql_num_rows($resultX);
							if($countL > 0) {
								$lectureResult00 = date("Y-m-d", strtotime($rsX[inputDate]."+2day"));
								$lectureResult01 = substr($lectureResult00,5,2);
								$lectureResult02 = substr($lectureResult00,8,2);
								$lectureResult = $lectureResult01."/".$lectureResult02."까지";
							} else {
								$lectureResult = "3일내";
							}
							break;
			}

			//발송메시지 내용 변수 replace
			$message = str_replace("{시작}",$lectureStart,$message);
			$message = str_replace("{종료}",$lectureEnd,$message);
			$message = str_replace("{회사명}",$companyName,$message);
			$message = str_replace("{도메인}",$domain,$message);
			$message = str_replace("{아이디}",$keyValue[1],$message);
			$message = str_replace("{이름}",$userName,$message);
			$message = str_replace("{과정명}",$contentsName,$message);
			$message = str_replace("{확인}",$lectureResult,$message);
			$message = str_replace("{소속업체명}",$companyBelongName,$message);

			if($agreement == 'Y') {
				$message = str_replace("{비밀번호}","기존사용비번",$message);
			} else {
				$message = str_replace("{비밀번호}","1111",$message);
			}

			if($sendReserve == "Y") { // 예약 발송이면 시간 받음
				$sendTime = $_POST[reserveTime];
				$sendTimeLog = $_POST[reserveTime];
			} else {
				$sendTimeLog = $inputDate;
			}

			if($messageBox == "Y") { // 직접 작성이면 메시지 받음
				$message = $_POST[sendMessage];
			}

			if($device == "sms") { // 문자 발송
				if($smsReceive != 'N') { // 문자수신거부가 아니면 발송
					insert_emma($receivePhone,$sendPhone,$message,$sendTime);
					$receiveTarget = $receivePhone;
					$sendTarget = $sendPhone;
					$query = " INSERT INTO nynSendLog 
											SET lectureStart='".$lectureStart."', 
													lectureEnd='".$lectureEnd."', 
													companyCode='".$companyCode."',
													contentsCode='".$contentsCode."',
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
					$result = mysql_query($query);
				}

			} else { // 이메일 발송
				if($emailReceive != 'N') { // 메일수신거부가 아니면 발송
					$toMail = $receiveEmail;
					$fromMail = $_adminMail;
					$subject = "[".$_siteName."] 진도현황 안내 드립니다.";
					$content = $message;
					$filepath = $_SERVER["DOCUMENT_ROOT"]."/mail_preset2.html";
					//$filepath = "";
					$var = "";
					mail_fsend($toMail, $fromMail, $subject, $content, '', '', '', $filepath, $var);
					$receiveTarget = $toMail;
					$sendTarget = $fromMail;

					$query = " INSERT INTO nynSendLog 
											SET lectureStart='".$lectureStart."', 
													lectureEnd='".$lectureEnd."', 
													companyCode='".$companyCode."',
													contentsCode='".$contentsCode."',
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
					$result = mysql_query($query);
				}
			}
		}

			if($result) {
				echo '{"result" : "success"}';
			} else {
				if($smsReceive == 'N' || $emailReceive == 'N') {
					echo '{"result" : "success"}';
				} else {
					echo '{"result" : "error"}';
				}
			}

		exit;

	} else if($method == "GET") { // 메시지 미리보기
		$sendType = $_GET[sendType];
		$device = $_GET[device];
		if($device == "") {
			$device = "email";
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
								WHERE device='".$device."' AND sendType='0') AS send0, 
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='30') AS send30, 
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='50') AS send50, 
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='70') AS send70, 
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='final') AS sendFinal,
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='end') AS sendEnd,
								(
								SELECT message
								FROM nynSendMessage
								WHERE device='".$device."' AND sendType='result') AS sendResult";
		$resultB = mysql_query($queryB);
		$rsB = mysql_fetch_array($resultB);

		$sendStart = $rsB[sendStart];
		$send0 = $rsB[send0];
		$send30 = $rsB[send30];
		$send50 = $rsB[send50];
		$send70 = $rsB[send70];
		$sendFinal = $rsB[sendFinal];
		$sendEnd = $rsB[sendEnd];
		$sendResult = $rsB[sendResult];

		$keyValue = explode('/',$sendType);

		//연락처 및 기간 출력
		$queryC = " SELECT A.lectureStart, A.lectureEnd, A.companyCode, B.mobile01, B.mobile02, B.mobile03, B.email01, B.email02, B.userName, B.agreement, 
								C.companyName AS companyBelongName, C.studyEnabled, C.cyberURL, D.contentsName
								FROM nynStudy AS A
								LEFT OUTER
								JOIN nynMember AS B ON A.userID=B.userID
								LEFT OUTER
								JOIN nynCompany AS C ON B.companyCode=C.companyCode
								LEFT OUTER
								JOIN nynContents AS D ON A.contentsCode=D.contentsCode
								WHERE A.userID='".$keyValue[1]."' 
								AND A.lectureOpenSeq='".$keyValue[2]."'";
		$resultC = mysql_query($queryC);
		$rsC = mysql_fetch_array($resultC);

		$lectureStart = $rsC[lectureStart];
		$lectureEnd = $rsC[lectureEnd];
		$userName = $rsC[userName];
		$companyCode = $rsC[companyCode];
		$studyEnabled = $rsC[studyEnabled];
		$contentsCode = $rsC[contentsCode];
		$contentsName = $rsC[contentsName];
		$cyberURL = $rsC[cyberURL];
		$mobile = $rsC[mobile01].$rsC[mobile02].$rsC[mobile03];
		$email = $rsC[email01].'@'.$rsC[email02];
		$agreement = $rsC[agreement];
		$companyBelongName = $rsC[companyBelongName];
		
		if($studyEnabled == 'Y') {
			$domain = $cyberURL;
		}

		switch ($keyValue[0]) {
				case "start":
						$message = $sendStart;
						$typeName = "학습시작";
						break;

				case "0":
						$message = $send0;
						$typeName = "0%미만";
						break;

				case "30":
						$message = $send30;
						$typeName = "30%미만";
						break;

				case "50":
						$message = $send50;
						$typeName = "50%미만";
						break;

				case "70":
						$message = $send70;
						$typeName = "70%미만";
						break;

				case "final":
						$message = $sendFinal;
						$typeName = "최종독려";
						break;

				case "end":
						$message = $sendEnd;
						$typeName = "종강일";
						break;

				case "result":
						$message = $sendResult;
						$typeName = "결과확인";

						$queryX = " SELECT inputDate FROM nynStudyEnd 
												WHERE gubun='resultView' AND lectureStart='".$lectureStart."' AND 
															lectureEnd='".$lectureEnd."' AND companyCode='".$companyCode."'";
						$resultX = mysql_query($queryX);
						$rsX = mysql_fetch_array($resultX);
						$countL = mysql_num_rows($resultX);
						if($countL > 0) {
							$lectureResult00 = date("Y-m-d", strtotime($rsX[inputDate]."+2day"));
							$lectureResult01 = substr($lectureResult00,5,2);
							$lectureResult02 = substr($lectureResult00,8,2);
							$lectureResult = $lectureResult01."/".$lectureResult02."까지";
						} else {
							$lectureResult = "3일내";
						}
						break;
		}

		//발송메시지 내용 변수 replace
		$message = str_replace("{시작}",$lectureStart,$message);
		$message = str_replace("{종료}",$lectureEnd,$message);
		$message = str_replace("{회사명}",$companyName,$message);
		$message = str_replace("{도메인}",$domain,$message);
		$message = str_replace("{아이디}",$keyValue[1],$message);
		$message = str_replace("{이름}",$userName,$message);
		$message = str_replace("{과정명}",$contentsName,$message);
		$message = str_replace("{확인}",$lectureResult,$message);
		$message = str_replace("{소속업체명}",$companyBelongName,$message);

		if($agreement == 'Y') {
			$message = str_replace("{비밀번호}","기존사용비번",$message);
		} else {
			$message = str_replace("{비밀번호}","1111",$message);
		}

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
		
	@mysql_close();
?>