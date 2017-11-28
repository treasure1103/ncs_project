<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
  if($method == "DELETE") { // 주문 삭제
			parse_str(file_get_contents("php://input"), $_DEL);
			$seq = $_DEL['seq'];
			if(!$_SESSION[loginUserID]) {
				echo '{"result" : "login error"}';
				exit;
			}
			$query = "DELETE FROM nynOrder WHERE userID='".$_SESSION[loginUserID]."' AND seq=".$seq;
			$result = mysql_query($query);

			if($result){
				echo '{"result" : "success"}';
			} else {
				echo '{"result" : "error"}';
			}
			exit;

	} else if($method == "GET") { // 주문 정보 불러옴
			$list = $_GET['list'];
			$page = $_GET['page'];
			$seq = $_GET['seq'];

			if($list == "") {
				$list = 10;
			}
			if($page == "") {
				$page = 1;
			}
			if($seq != "") {
				$qSeq = " and seq='".$seq."'";
			}

			$qSearch = $qSeq;

			$que = "SELECT A.*, B.previewImage FROM nynOrder AS A LEFT OUTER JOIN nynContents AS B ON A.contentsCode=B.contentsCode  WHERE A.userID='".$_SESSION[loginUserID]."'".$qSearch;
			$res = mysql_query($que);
			$allPost = mysql_num_rows($res);
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' limit '.$currentLimit.', '.$list; //limit sql 구문

			$query = "SELECT A.*, B.previewImage FROM nynOrder AS A LEFT OUTER JOIN nynContents AS B ON A.contentsCode=B.contentsCode WHERE  A.userID='".$_SESSION[loginUserID]."'".$qSearch." ORDER BY seq DESC".$sqlLimit;
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			$previewImageURL = '/attach/contents/';
			$adminapi[totalCount] = "$allPost"; //총 개시물 수
			$adminapi[nowTime] = $inputDate;
			$adminapi[previewImageURL] = $previewImageURL;
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
				$adminapi[order][$a][contentsCode] = $rs[contentsCode];
				$adminapi[order][$a][orderName] = $rs[orderName];
				$adminapi[order][$a][previewImage] = $rs[previewImage];
				$adminapi[order][$a][orderDate] = $rs[orderDate];
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
				} else {
					$orderTypeName = "가상계좌";
				}

				$adminapi[order][$a][orderType] = $rs[orderType];
				$adminapi[order][$a][orderTypeName] = $orderTypeName;

				if($rs[orderStatus] == "Y") {
					$orderStatusName = "결제승인";
				} else {
					$orderStatusName = "결제대기";
				}
				
				$adminapi[order][$a][orderStatus] = $rs[orderStatus];
				$adminapi[order][$a][orderStatusName] = $orderStatusName;
				$adminapi[order][$a][bank] = $rs[bank];
				$adminapi[order][$a][bankNum] = $rs[bankNum];
				$adminapi[order][$a][depositName] = $rs[depositName];
				$a++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
		
	@mysql_close();
?>