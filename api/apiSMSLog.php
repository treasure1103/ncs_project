<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
		if(!$_SESSION['loginUserLevel']){
			$userLevelCheck = 10;
		} else {
			$userLevelCheck = $_SESSION['loginUserLevel'];
		}

		if($userLevelCheck >= 7) {
			echo "error";
			exit;
		}

		$userName = $_GET['userName'];
		$searchDate = $_GET['searchDate'];
		if(!$searchDate) {
			$searchDate = substr($inputDate,0,10);
		}
		$messageType = $_GET['messageType'];
		$years = substr($searchDate,0,4);
		$month = substr($searchDate,5,2);
		if($month == null){
			$month = date("m");
		}
		if($list == "") {
			$list = 1000;
		}
		if($page == "") {
			$page = 1;
		}
		if(!$messageType) {
			$messageType = "smt";
		}

		if($userName) {
			$query = "SELECT A.*, B.userName FROM emma.em_".$messageType."_log_".$years.$month." AS A
								LEFT OUTER
								JOIN nynMember AS B
								ON A.recipient_num=CONCAT(B.mobile01,B.mobile02,B.mobile03) COLLATE utf8_unicode_ci
								WHERE mt_report_code_ib <> 1000 AND reg_date_tran LIKE '".$searchDate."%' AND userName='".$userName."'";
			$result = mysql_query($query);
			$allPost = mysql_num_rows($result);

		} else {
			$que = "SELECT * FROM emma.em_".$messageType."_log_".$years.$month." WHERE reg_date_tran LIKE '".$searchDate."%' AND mt_report_code_ib <> 1000".$qSearch;
			$res = mysql_query($que);
			$allPost = mysql_num_rows($res);
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' LIMIT '.$currentLimit.', '.$list; //limit sql 구문

			$query = "SELECT * FROM emma.em_".$messageType."_log_".$years.$month." WHERE reg_date_tran LIKE '".$searchDate."%' AND mt_report_code_ib <> 1000 ".$qSearch.$sortValue.$sqlLimit;
			$result = mysql_query($query);
		}

		$adminapi = array();
		$a=0;

		$adminapi[totalCount] = "$allPost"; //총 개시물 수

		while($rs = mysql_fetch_array($result)) {
				if(strlen($rs["recipient_num"]) <= 10){
					$mobile01=substr($rs["recipient_num"],0,3);
					$mobile02=substr($rs["recipient_num"],3,3);
					$mobile03=substr($rs["recipient_num"],-4);
				}else {
					$mobile01=substr($rs["recipient_num"],0,3);
					$mobile02=substr($rs["recipient_num"],3,4);
					$mobile03=substr($rs["recipient_num"],-4);
				}			

				$queM = " SELECT A.userName, B.companyName 
									FROM nynMember AS A
									LEFT OUTER
									JOIN nynCompany AS B ON A.companyCode=B.companyCode
									where A.mobile01='".$mobile01."' AND A.mobile02='".$mobile02."' AND A.mobile03='".$mobile03."'";
				$reM = mysql_query($queM);
				$rsM = mysql_fetch_array($reM);

				switch ($rs['mt_report_code_ib']) {
						case "2000":
							$errorMessage = "전송 시간 초과";
						break;

						case "2001":
							$errorMessage = "전송 실패 (무선망단)";
						break;

						case "2002":
							$errorMessage = "전송 실패 (무선망 -> 단말기단)";
						break;

						case "2003":
							$errorMessage = "단말기 전원 꺼짐";
						break;

						case "2004":
							$errorMessage = "단말기 메시지 버퍼 풀";
						break;

						case "2005":
							$errorMessage = "음영지역";
						break;

						case "2006":
							$errorMessage = "메시지 삭제됨";
						break;

						case "2007":
							$errorMessage = "일시적인 단말 문제";
						break;

						case "3000":
							$errorMessage = "전송할 수 없음";
						break;

						case "3001":
							$errorMessage = "가입자 없음";
						break;

						case "3002":
							$errorMessage = "성인 인증 실패";
						break;

						case "3003":
							$errorMessage = "수신번호 형식 오류";
						break;

						case "3004":
							$errorMessage = "단말기 서비스 일시 정지";
						break;

						case "3005":
							$errorMessage = "단말기 호 처리 상태";
						break;

						case "3006":
							$errorMessage = "착신 거절";
						break;

						case "3007":
							$errorMessage = "Callback URL을 받을 수 없는 폰";
						break;

						case "3008":
							$errorMessage = "기타 단말기 문제";
						break;

						case "3009":
							$errorMessage = "메시지 형식 오류";
						break;

						case "3010":
							$errorMessage = "MMS 미지원 단말";
						break;

						case "3011":
							$errorMessage = "서버 오류";
						break;

						case "3012":
							$errorMessage = "스팸";
						break;

						case "3013":
							$errorMessage = "서비스 거부";
						break;

						case "3014":
							$errorMessage = "기타";
						break;

						case "3015":
							$errorMessage = "전송 경로 없음";
						break;

						case "3016":
							$errorMessage = "첨부파일 사이즈 제한 실패";
						break;

						case "3017":
							$errorMessage = "발신번호 변작방지 세칙위반";
						break;

						case "3018":
							$errorMessage = "휴대폰 가입 이동통신사를 통해 발신번호 변작방지 부가 서비스에 가입된 번호를 발신번호로 사용한 MT 전송 시";
						break;

						case "3019":
							$errorMessage = "KISA or 미래부에서 모든 고객사에 대하여 차단 처리 요청 번호를 발신 번호로 사용한 MT 전송시";
						break;

						case "3022":
							$errorMessage = "Charset conversion error";
						break;

						case "1001":
							$errorMessage = "ID 존재하지 않음";
						break;

						case "1002":
							$errorMessage = "인증 오류";
						break;

						case "1003":
							$errorMessage = "서버 내부 오류 (DB 접속 실패 등)";
						break;

						case "1004":
							$errorMessage = "클라이언트 패스워드 틀림";
						break;

						case "1005":
							$errorMessage = "공개키가 이미 등록 되어 있음";
						break;

						case "1006":
							$errorMessage = "클라이언트 공개키 중복";
						break;

						case "1007":
							$errorMessage = "IP Address 인증 실패";
						break;

						case "1008":
							$errorMessage = "MAC Address 인증 실패";
						break;

						case "1009":
							$errorMessage = "서비스 거부 됨 (고객 접속 금지)";
						break;

						case "1010":
							$errorMessage = "CONTENT 없음";
						break;

						case "1011":
							$errorMessage = "CALLBACK 없음";
						break;

						case "1012":
							$errorMessage = "RECIPIENT_INFO 없음";
						break;

						case "1013":
							$errorMessage = "SUBJECT 없음";
						break;

						case "1014":
							$errorMessage = "첨부 파일 KEY 없음";
						break;

						case "1015":
							$errorMessage = "첨부 파일 NAME 없음";
						break;

						case "1016":
							$errorMessage = "첨부 파일 크기 없음";
						break;

						case "1017":
							$errorMessage = "첨부 파일 Content 없음";
						break;

						case "1018":
							$errorMessage = "전송 권한 없음";
						break;

						case "1019":
							$errorMessage = "TTL 초과";
						break;

						case "1020":
							$errorMessage = "charset conversion error";
						break;

						case "1022":
							$errorMessage = "발신번호 사전등록제 관련 미등록 발신번호 사용";
						break;

						case "E900":
							$errorMessage = "전송키가 없는 경우";
						break;

						case "E901":
							$errorMessage = "수신번호가 없는 경우";
						break;

						case "E902":
							$errorMessage = "(동보인 경우) 수신번호순번이 없는 경우";
						break;

						case "E903":
							$errorMessage = "제목 없는 경우";
						break;

						case "E904":
							$errorMessage = "메시지가 없는 경우";
						break;

						case "E905":
							$errorMessage = "회신번호가 없는 경우";
						break;

						case "E906":
							$errorMessage = "메시지키가 없는 경우";
						break;

						case "E907":
							$errorMessage = "동보 여부가 없는 경우";
						break;

						case "E908":
							$errorMessage = "서비스 타입이 없는 경우";
						break;

						case "E909":
							$errorMessage = "전송요청시각이 없는 경우";
						break;

						case "E910":
							$errorMessage = "TTL 타임이 없는 경우";
						break;

						case "E911":
							$errorMessage = "서비스 타입이 MMS MT인 경우, 첨부파일 확장자가 없는 경우";
						break;

						case "E912":
							$errorMessage = "서비스 타입이 MMS MT인 경우, attach_file 폴더에 첨부파일이 없는 경우";
						break;

						case "E913":
							$errorMessage = "서비스 타입이 MMS MT인 경우, 첨부파일 사이즈가 0인 경우";
						break;

						case "E914":
							$errorMessage = "서비스 타입이 MMS MT인 경우, 메시지 테이블에는 파일그룹키가 있는데 파일 테이블에 데이터가 없는 경우";
						break;

						case "E915":
							$errorMessage = "중복메시지";
						break;

						case "E916":
							$errorMessage = "인증서버 차단번호";
						break;

						case "E917":
							$errorMessage = "고객DB 차단번호";
						break;

						case "E918":
							$errorMessage = "USER CALLBACK FAIL";
						break;

						case "E919":
							$errorMessage = "발송 제한 시간인 경우, 메시지 재발송 처리가 금지 된 경우";
						break;

						case "E920":
							$errorMessage = "서비스 타입이 LMS MT인 경우, 메시지 테이블에 파일그룹키가 있는 경우";
						break;

						case "E921":
							$errorMessage = "서비스 타입이 MMS MT인 경우, 메시지 테이블에 파일그룹키가 없는 경우";
						break;

						case "E922":
							$errorMessage = "동보단어 제약문자 사용 오류";
						break;

						case "E999":
							$errorMessage = "기타오류";
						break;

						default :
							$errorMessage = "명시되어 있지 않은 오류";
						break;
			}
	
			$adminapi['sendLog'][$a]['mt_pr'] = $rs['mt_pr'];
			if($rsM['userName'] == null || $rsM['companyName'] == null){
				$userName = '일치정보없음';
				$companyName= '일치정보없음';
			} else {
				$userName = $rsM['userName'];
				$companyName=$rsM['companyName'] ;
			}
			$adminapi['sendLog'][$a]['userName'] = $userName;
			$adminapi['sendLog'][$a]['companyName'] = $companyName;
			$adminapi['sendLog'][$a]['date_client_req'] = $rs['date_client_req']; // 전송예약시간
			$adminapi['sendLog'][$a]['date_rslt'] = $rs['date_rslt']; // 단말기 도착시간
			$adminapi['sendLog'][$a]['content'] = $rs['content'];
			$adminapi['sendLog'][$a]['recipient_num'] = $rs['recipient_num'];
			$adminapi['sendLog'][$a]['date_rslt'] = $rs['date_rslt'];
			$adminapi['sendLog'][$a]['mt_report_code_ib'] = $rs['mt_report_code_ib'];
			$adminapi['sendLog'][$a]['errorMessage'] = $errorMessage;
			$adminapi['sendLog'][$a]['reg_date'] = $rs['reg_date']; // 데이터 등록시간
			$a++;
		}

		$json_encoded = json_encode($adminapi);
		print_r($json_encoded);
?>