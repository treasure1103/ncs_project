<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") { // 회원사 등록 및 수정은 POST로 받는다.
		$seq = $_POST['seq'];
		$matchingType = $_POST['matchingType'];
		$userID = $_POST['userID'];
		$matchingValue = $_POST['matchingValue'];

		if($matchingType != "marketer") {
			$matchingTypeQ = " userID='".$userID."', matchingType='manager'";
		}else{
			$matchingTypeQ = " userID='".$userID."', matchingType='marketer'";
		}
		if($matchingValue) {
			$matchingValueQ = " matchingValue='".$matchingValue."', ";
		}
		
		
		if($seq == "") { // 담당자 등록
			$query = "INSERT INTO nynMatching SET ".$matchingValueQ.$matchingTypeQ;
			$result = mysql_query($query);
			$seq = mysql_insert_id();

		} else { // 담당자 정보 수정
			$query = "UPDATE nynMatching SET ".$matchingValueQ.$matchingTypeQ." WHERE seq=".$seq;
			$result = mysql_query($query);
		}

		if($result){
			echo $seq;
		} else {
			echo "error";
		}
		exit;

	
	} else if($method == "DELETE") { // 정보 삭제
			parse_str(file_get_contents("php://input"), $_DEL);
			$seq = $_DEL['seq'];
			
			$query = "DELETE FROM nynMatching WHERE seq=".$seq;
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
			$matchingType = $_GET['matchingType'];
			$matchingValue = $_GET['matchingValue'];
			$managerName = $_GET['managerName'];
			$marketerName = $_GET['marketerName'];

			switch($searchType){
				case "matchingValue":
					$matchingValue = $searchValue;
				break;

				case "managerName":
					$managerName = $searchValue;
				break;

				case "marketerName":
					$marketerName = $searchValue;
				break;
			}

			if($sortType == "") {
				$sortType = "A.matchingValue ";
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
			if($matchingValue != "") {
				$qMatchingValue = " and A.matchingType='".$matchingValue."'";
			}
			if($managerName){
				$qManagerName = " and B.userName like '%".$managerName."%'";
			}
			if($marketerName){
				$qMarketerName = " and B.userName like '%".$marketerName."%'";
			}
		
			$qSearch = $qSeq.$qManagerName.$qMarketerName;

			$que = "SELECT A.*, B.userName AS managerName
							FROM nynMatching A
							LEFT OUTER
							JOIN nynMember B
							ON A.userID=B.userID
							WHERE matchingType ='".$matchingType."' ".$qSearch;
			$res = mysql_query($que);
			$allPost = mysql_num_rows($res);
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' limit '.$currentLimit.', '.$list; //limit sql 구문

			$query = "SELECT A.*, B.userName
								FROM nynMatching A
								LEFT OUTER
								JOIN nynMember B
								ON A.userID=B.userID
								WHERE matchingType ='".$matchingType."' ".$qSearch." ORDER BY ".$sortType." ".$sortValue.$sqlLimit;
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			$adminapi['totalCount'] = "$allPost"; //총 개시물 수

			while($rs = mysql_fetch_array($result)) {
				$adminapi['overlap'][$a]['seq'] = $rs['seq'];
				$adminapi['overlap'][$a]['matchingType'] = $matchingType;
				$adminapi['overlap'][$a]['matchingValue'] = $rs['matchingValue'];
				if($rs['matchingType'] == 'manager'){
					$matchingType = '교육담당자';
					$queryName = "SELECT comapnyName AS matchingValueName FROM nynCompany WHERE companyCode='".$rs['matchingValue']."'";
					$resultName = mysql_query($queryName);
					$rsName = mysql_fetch_array($resultName);

				}else{
					$matchingType = '영업담당자';
					$queryName = "SELECT userName AS matchingValueName FROM nynMember WHERE userID='".$rs['matchingValue']."'";
					$resultName = mysql_query($queryName);
					$rsName = mysql_fetch_array($resultName);
				}
				$adminapi['overlap'][$a]['matchingValueName'] = $rsName['matchingValueName'];
				$adminapi['overlap'][$a]['userID'] = $rs['userID'];
				$adminapi['overlap'][$a]['userName'] = $rs['userName'];
				$a++;
			}
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
		
	@mysql_close();
?>