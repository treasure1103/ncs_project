<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") { // 등록 및 수정은 POST로 받는다.
		$educationPrice = $_POST['educationPrice']; // 교육비
		$serviceType = $_POST['serviceType']; // 서비스 구분 : 0-사업주개별, 1-사업주, 2-능력개발, 3-일반(비환급)
		$contentsCode = $_POST['contentsCode']; // 신청 과정코드
		$lectureStart = $_POST['lectureStart'];
		$lectureEnd = $_POST['lectureEnd']; 
		$orderNum = date("ymd").substr(time().md5(microtime()),0,23); //주문번호 생성
		$RRN01 = $_POST['RRN01'];
		$RRN02 = $_POST['RRN02'];

		$queryA = "SELECT contentsName FROM nynContents WHERE contentsCode='".$contentsCode."'";
		$resultA = mysql_query($queryA);
		$rsA = mysql_fetch_array($resultA);
		$orderName = $rsA[contentsName];

		if($serviceType == "3") {
			$dDay = $_POST['dDay']; //기간선택 (1~5일)
			$lectureStart = date("Y-m-d", strtotime(subStr($inputDate,0,10)."+".$dDay."Day"));
			$lectureEnd = date("Y-m-d", strtotime($lectureStart."+30Day"));
			$orderType = $_POST['orderType']; // 결제구분 : V-가상계좌, D-무통장, C-카드
			$orderTypeQ = "orderType='".$orderType."', ";

		} else if($serviceType == "0" || $serviceType == "1" ) { // 사업주 훈련과정인 경우 주민등록번호 저장
			$queryZ =  "INSERT INTO nynOrderRRN 
									SET orderNum='".$orderNum."',
											userID='".$_SESSION[loginUserID]."',
											RRN01='".$RRN01."', 
											RRN02='".$RRN02."', 
											inputDate='".$inputDate."'";
			$resultZ = mysql_query($queryZ);

			if(!$resultZ){
					echo "error";
					exit;
			}
		}

		$queryQ =  "orderNum='".$orderNum."', 
								serviceType='".$serviceType."', 
								cancelLimitDate='".$lectureStart."', 
								orderName='".$orderName."', 
								userID='".$_SESSION[loginUserID]."', 
								orderDate='".$inputDate."', 
								contentsCode='".$contentsCode."',
								lectureStart='".$lectureStart."', 
								lectureEnd='".$lectureEnd."', 
								educationPrice='".$educationPrice."', 
								orderTotalPrice='".$educationPrice."', 
								".$orderTypeQ."
								orderStatus='N'";

			if($seq == "") { // 주문 등록
				// 중복신청 검사
				$queryD = "SELECT * FROM nynOrder WHERE lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."' AND contentsCode='".$contentsCode."' AND userID='".$_SESSION[loginUserID]."'";
				$resultD = mysql_query($queryD);
				$countD = mysql_num_rows($resultD);

				if($countD > 0) {
					echo "duplication";
					exit;
				}

				$query = "INSERT INTO nynOrder SET ".$queryQ;
				$result = mysql_query($query);
			}

				if($result){
					echo "success";
				} else {
					echo "error";
				}
				exit;

	} else if($method == "DELETE") { // 주문 삭제
			parse_str(file_get_contents("php://input"), $_DEL);
			$seq = $_DEL['seq'];
			$query = "DELETE FROM nynOrder WHERE seq=".$seq;
			$result = mysql_query($query);

			if($result){
				echo "success";
			} else {
				echo "error";
			}
			exit;

	} else if($method == "GET") { // 주문 정보 불러옴
			$list = $_GET['list'];
			$page = $_GET['page'];
			$seq = $_GET['seq'];
			$companyID = $_GET['companyID'];
			$contentsCode = $_GET['contentsCode']; // 과정코드
			$contentsName = $_GET['contentsName']; // 과정명
			$companyCode = $_GET['companyCode']; // 사업자번호
			$companyName = $_GET['companyName']; // 회사명
			$serviceType = $_GET['serviceType']; // 환급, 능력개발, 일반 등 과정 구분
			$orderType = $_GET['orderType']; // 결제타입
			$orderNum = $_GET['orderNum']; // 결제타입
			$userID = $_GET['userID']; // 훈련생 아이디
			$userName = $_GET['userName']; // 훈련생 이름
			$startDate = $_GET['startDate'];
			$endDate = $_GET['endDate'];
			$lectureStart = $_GET['lectureStart']; // 수강시작일

			switch($searchType){
				case "companyID":
					$companyID = $searchValue;
				break;

				case "userID":
					$userID = $searchValue;
				break;
			}

			if($sortType == "") {
				$sortType = "seq";
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
			if($serviceType != "") {
				$qServiceType = " and serviceType='".$serviceType."'";
			}
			if($orderType != "") {
				$qOrderType = " and orderType='".$orderType."'";
			}
			if($orderStatus != "") {
				$qOrderStatus = " and orderStatus='".$orderStatus."'";
			}
			if($orderNum != "") {
				$qOrderNum = " and orderNum='".$orderNum."'";
			}
			if($startDate != "" && $endDate != "") {
				$startDate = $startDate." 00:00:00";
				$endDate = $endDate." 23:59:59";
				$qOrderDate = " AND (orderDate BETWEEN '".$startDate."' AND '".$endDate."')";
			}
			if($lectureStart) {
				$qLectureStart = "AND lectureStart='".$lectureStart."'";
			}

/*
			if($userName != "") {
				$qUserName = " AND userName like '%".$userName."%'";
			}
			if($companyCode != "") {
				$qCompanyCode = " and A.companyCode='".$companyCode."'";
			}
			if($companyName != "") {
				$qCompanyName = " and A.companyName like '%".$companyName."%'";
			}
*/

			$qSearch = $qSeq.$qServiceType.$qOrderType.$qOrderStatus.$qCompanyID.$qUserID.$qOrderDate.$qLectureStart.$qOrderNum;

			$que = "SELECT * FROM nynOrder WHERE seq <> 0 ".$qSearch;
			$res = mysql_query($que);
			$allPost = mysql_num_rows($res);
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' limit '.$currentLimit.', '.$list; //limit sql 구문

			$query = "SELECT * FROM nynOrder WHERE seq <> 0 ".$qSearch." ORDER BY ".$sortType." ".$sortValue.$sqlLimit;
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			$adminapi[totalCount] = "$allPost"; //총 개시물 수

			while($rs = mysql_fetch_array($result)) {
				$adminapi[order][$a][seq] = $rs[seq];
				$adminapi[order][$a][orderNum] = $rs[orderNum];
				$adminapi[order][$a][serviceType] = $rs[serviceType];

				switch ($rs[serviceType]) {
						case "0":
								$serviceTypeName = "사업주(개별)";
								break;

						case "1":
								$serviceTypeName = "사업주";
								break;

						case "2":
								$serviceTypeName = "능력개발";
								break;

						case "3":
								$serviceTypeName = "일반(비환급)";
								break;
				}

				$adminapi[order][$a][serviceTypeName] = $serviceTypeName;
				$adminapi[order][$a][cancelLimitDate] = $rs[cancelLimitDate];
				$adminapi[order][$a][userID] = $rs[userID];

				if($rs[serviceType] == 2) {
					$queryC = " SELECT B.companyName, B.companyCode, B.companyScale, B.managerID, B.marketerID, B.phone01, B.phone02, B.phone03,
											C.email01, C.email02, C.mobile01, C.mobile02, C.mobile03, C.userName AS mngName, B.marketerID, D.userName AS mktName, E.userName as userName
											FROM nynOrder AS A
											LEFT OUTER
											JOIN nynCompany AS B ON A.userID=B.companyID
											LEFT OUTER
											JOIN nynMember AS C ON B.managerID=C.userID
											LEFT OUTER
											JOIN nynMember AS D ON B.marketerID=D.userID
											LEFT OUTER
											JOIN nynMember AS E ON A.userID=E.userID
										  WHERE A.seq=".$rs[seq];
					$resultC = mysql_query($queryC);
					$rsC = mysql_fetch_array($resultC);

					$adminapi[order][$a][userName] = $rsC[userName];
					$adminapi[order][$a][company][companyName] = $rsC[companyName];
					$adminapi[order][$a][company][companyCode] = $rsC[companyCode];
				
					switch ($rsC[companyScale]) {
							case "A":
									$companyScaleName = "우선지원";
									break;

							case "B":
									$companyScaleName = "대규모 1000인 미만";
									break;

							case "C":
									$companyScaleName = "대규모 1000인 이상";
									break;
					}

					$adminapi[order][$a][company][companyScale] = $rsC[companyScale];
					$adminapi[order][$a][company][companyScaleName] = $companyScaleName;
					$adminapi[order][$a][company][phone] = $rsC[phone01]."-".$rsC[phone02]."-".$rsC[phone03];
					$adminapi[order][$a][company][managerID] = $rsC[managerID];
					$adminapi[order][$a][company][managerName] = $rsC[mngName];
					$adminapi[order][$a][company][managerMobile] = $rsC[mobile01]."-".$rsC[mobile02]."-".$rsC[mobile03];
					$adminapi[order][$a][company][managerEmail] = $rsC[email01]."@".$rsC[email02];
					$adminapi[order][$a][company][marketerID] = $rsC[marketerID];
					$adminapi[order][$a][company][marketerName] = $rsC[mktName];

				} else {
					$queryC = " SELECT B.userName, B.phone01, B.phone02, B.phone03, B.mobile01, B.mobile02, B.mobile03,
											B.email01, B.email02, C.companyCode, C.companyName, C.companyScale
											FROM nynOrder AS A
											LEFT OUTER
											JOIN nynMember AS B ON A.userID=B.userID
											LEFT OUTER
											JOIN nynCompany AS C ON B.companyCode=C.companyCode
											WHERE A.seq=".$rs[seq];
					$resultC = mysql_query($queryC);
					$rsC = mysql_fetch_array($resultC);

					$adminapi[order][$a][userName] = $rsC[userName];
					$adminapi[order][$a][phone] = $rsC[phone01]."-".$rsC[phone02]."-".$rsC[phone03];
					$adminapi[order][$a][mobile] = $rsC[mobile01]."-".$rsC[mobile02]."-".$rsC[mobile03];
					$adminapi[order][$a][email] = $rsC[email01]."@".$rsC[email02];
					$adminapi[order][$a][company][companyName] = $rsC[companyName];
					$adminapi[order][$a][company][companyCode] = $rsC[companyCode];
					$adminapi[order][$a][company][companyScale] = $rsC[companyScale];
				}

				$adminapi[order][$a][orderName] = $rs[orderName];
				$adminapi[order][$a][contentsCode] = $rs[contentsCode];
				$adminapi[order][$a][orderDate] = $rs[orderDate];

				//신청 과정 출력
				$queryOP = "SELECT Y.*, Z.contentsCode, Z.contentsName, 
										Z.price, Z.rPrice01, Z.rPrice02, Z.rPrice03
										FROM nynOrder AS X
										LEFT OUTER
										JOIN nynOrderDetail AS Y
										ON X.orderNum=Y.orderNum
										LEFT OUTER
										JOIN nynContents AS Z
										ON Y.contentsCode=Z.contentsCode 
										WHERE X.orderNum='".$rs[orderNum]."'";
				$resultOP = mysql_query($queryOP);
				$countOP = mysql_num_rows($resultOP);
				$b = 0;
				$adminapi[order][$a][orderCount] = "$countOP";
				
					while($rs2 = mysql_fetch_array($resultOP)) {
						$adminapi[order][$a][detail][$b][contentsCode] = $rs2[contentsCode];
						$adminapi[order][$a][detail][$b][contentsName] = $rs2[contentsName];
						$adminapi[order][$a][detail][$b][price] = $rs2[price];

						switch ($rsC[companyScale]) {
								case "A":
										$rPrice = $rs2[rPrice01];
										break;

								case "B":
										$rPrice = $rs2[rPrice02];
										break;

								case "C":
										$rPrice = $rs2[rPrice03];
										break;
						}

						$adminapi[order][$a][detail][$b][rPrice] = $rPrice;
						$adminapi[order][$a][detail][$b][EA] = $rs2[EA];
						$b++;
					}

				$adminapi[order][$a][recipient][recipientName] = $rs[recipientName];
				$adminapi[order][$a][recipient][recipientPhone01] = $rs[recipientPhone01];
				$adminapi[order][$a][recipient][recipientPhone02] = $rs[recipientPhone02];
				$adminapi[order][$a][recipient][recipientPhone03] = $rs[recipientPhone03];
				$adminapi[order][$a][recipient][recipientMobile01] = $rs[recipientMobile01];
				$adminapi[order][$a][recipient][recipientMobile02] = $rs[recipientMobile02];
				$adminapi[order][$a][recipient][recipientMobile03] = $rs[recipientMobile03];
				$adminapi[order][$a][recipient][recipientZipCode] = $rs[recipientZipCode];
				$adminapi[order][$a][recipient][recipientAddress01] = $rs[recipientAddress01];
				$adminapi[order][$a][recipient][recipientAddress02] = $rs[recipientAddress02];
				$adminapi[order][$a][recipient][recipientMemo] = $rs[recipientMemo];
				$adminapi[order][$a][lectureStart] = $rs[lectureStart];
				$adminapi[order][$a][lectureEnd] = $rs[lectureEnd];
				$adminapi[order][$a][educationPrice] = $rs[educationPrice];
				$adminapi[order][$a][refundPrice] = $rs[refundPrice];
				$adminapi[order][$a][bookPrice] = $rs[bookPrice];
				$adminapi[order][$a][deliveryPrice] = $rs[deliveryPrice];
				$adminapi[order][$a][orderTotalPrice] = $rs[orderTotalPrice];
				
				//결제 타입 한글로 출력
				if($rs[orderType] == "C") {
					$orderTypeName = "카드";
				} else if ($rs[orderType] == "D") {
					$orderTypeName = "무통장";
				} else if ($rs[orderType] == "V") {
					$orderTypeName = "가상계좌";
				} else {
					$orderTypeName = "사업주";
				}

				$adminapi[order][$a][orderType] = $rs[orderType];
				$adminapi[order][$a][orderTypeName] = $orderTypeName;

				if($rs[orderStatus] == "Y") {
					$orderStatusName = "승인";
				} else {
					$orderStatusName = "대기";
				}
				
				$adminapi[order][$a][orderStatus] = $rs[orderStatus];
				$adminapi[order][$a][orderStatusName] = $orderStatusName;
				$adminapi[order][$a][bank] = $rs[bank];
				$adminapi[order][$a][bankNum] = $rs[bankNum];
				$adminapi[order][$a][depositName] = $rs[depositName];
				$adminapi[order][$a][deliveryInvoice] = $rs[deliveryInvoice];
				$adminapi[order][$a][testCompleteDate] = $rs[testCompleteDate];
				$adminapi[order][$a][refundApplyDate] = $rs[refundApplyDate];
				$adminapi[order][$a][refundApplyBranch] = $rs[refundApplyBranch];
				$adminapi[order][$a][refundCompleteDate] = $rs[refundCompleteDate];
				$adminapi[order][$a][refundCompletePrice] = $rs[refundCompletePrice];
				$a++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
		
	@mysql_close();
?>