<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") { // 공휴일 등록 및 수정은 POST로 받아옴
		$seq = $_POST['seq'];
		$name = $_POST['name'];
		$holiday = $_POST['holiday'];
		$holidayType = $_POST['holidayType'];
		$enabled = $_POST['enabled'];

		if($_SESSION['loginUserLevel'] > 4) { //관리자만 접근 가능
			echo "error";
			exit;
		}

		$queryQ = " holiday='".$holiday."',
								name='".$name."',
								enabled='".$enabled."',
								holidayType='".$holidayType."'";

		if($seq == "") { // 공휴일 등록
			$query = "INSERT INTO nynHoliday SET ".$queryQ;
			$result = mysql_query($query);

		} else { // 공휴일 수정
			$query = "UPDATE nynHoliday SET ".$queryQ." WHERE seq=".$seq;
			$result = mysql_query($query);
		}

			if($result){
				echo "success";
			} else {
				echo "error";
			}
			exit;

	} else if($method == "DELETE") {
			parse_str(file_get_contents("php://input"), $_DEL);
			$seq = $_DEL['seq'];

			if($_SESSION['loginUserLevel'] > 4) { //관리자만 접근 가능
				echo "error";
				exit;
			}

			$query = "DELETE FROM nynHoliday WHERE seq=".$seq;
			$result = mysql_query($query);

			if($result){
				echo "success";
			} else {
				echo "error";
			}
			exit;

	} else if($method == "GET") { // 공휴일 정보 불러옴
			$seq = $_GET['seq'];
			$year = $_GET['year'];
			$month = $_GET['month'];
			$name = $_GET['name'];
			
			if($year == "") {
				$year = date('Y');
			}

			$yearStart = $year."-01-01";
			$yearEnd = $year."-12-31";
			
			if($month != "") {
				$yearStart = $year."-".$month."-01";
				$yearEnd = $year."-".$month."-31";
			}
			if($list == "") {
				$list = 100;
			}
			if($page == "") {
				$page = 1;
			}
			if($seq != "") {
				$qSeq = " AND seq=".$seq."";
			}
			if($holiday != "") {
				$qHoliday = " AND holiday='".$holiday."'";
			}
			if($name != "") {
				$qName = " AND name like '%".$name."%'";
			}

			$qSearch = $qSeq.$qHoliday.$qName;

			$que = "SELECT * FROM nynHoliday WHERE holiday BETWEEN '".$yearStart."' AND '".$yearEnd."' ".$qSearch;
			$res = mysql_query($que);
			$allPost = mysql_num_rows($res);
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' limit '.$currentLimit.', '.$list; //limit sql 구문

			$query = "SELECT * FROM nynHoliday WHERE holiday BETWEEN '".$yearStart."' AND '".$yearEnd."' ".$qSearch." ORDER BY holiday ".$sqlLimit;
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			$adminapi[totalCount] = "$allPost"; //총 개시물 수

			while($rs = mysql_fetch_array($result)) {
				$adminapi[holiday][$a][seq] = $rs[seq];
				$adminapi[holiday][$a][holiday] = $rs[holiday];
				$adminapi[holiday][$a][name] = $rs[name];
				$adminapi[holiday][$a][holidayType] = $rs[holidayType];
				$adminapi[holiday][$a][enabled] = $rs[enabled];
				$a++;
			}
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
		
	@mysql_close();
?>