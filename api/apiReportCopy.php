<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
			$userID = $_GET['userID'];
			$userName = $_GET['userName'];

			if($_SESSION['loginUserLevel'] > 7) {
				echo "error";
				exit;
			}
			if($list == "") {
				$list = 10;
			}
			if($page == "") {
				$page = 1;
			}
			if($userID != "") {
				$qID = " AND A.userID like '%".$userID."%'";
			}
			if($userName != "") {
				$qName = " AND A.userName LIKE '%".$userName."%'";
			}

			$qSearch = $qID.$qName;

			$que = "select A.*,B.*,C.lectureStart,C.lectureEnd,D.userID,D.userName,E.contentsName from tb_copykiller_copyratio AS A
							LEFT OUTER 
							JOIN nynReportAnswer AS B
							ON A.uri=B.seq
							LEFT OUTER 
							JOIN nynStudy AS C
							ON B.userID=C.userID AND B.lectureopenSeq=C.lectureOpenSeq
							LEFT OUTER 
							JOIN nynMember AS D
							ON B.userID=D.userID
							LEFT OUTER 
							JOIN nynContents AS E
							ON C.contentsCode=E.contentsCode".$qSearch;
			$res = mysql_query($que);
			$allPost = mysql_num_rows($res);
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' limit '.$currentLimit.', '.$list; //limit sql 구문

			$query = "select A.*,B.*,C.lectureStart,C.lectureEnd,D.userID,D.userName,E.contentsName from tb_copykiller_copyratio AS A
								LEFT OUTER 
								JOIN nynReportAnswer AS B
								ON A.uri=B.seq
								LEFT OUTER 
								JOIN nynStudy AS C
								ON B.userID=C.userID AND B.lectureopenSeq=C.lectureOpenSeq
								LEFT OUTER 
								JOIN nynMember AS D
								ON B.userID=D.userID
								LEFT OUTER 
								JOIN nynContents AS E
								ON C.contentsCode=E.contentsCode".$qSearch." 
								ORDER BY C.lectureStart Desc, C.lectureEnd Desc ".$sqlLimit;
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분
			$adminapi[totalCount] = "$allPost"; //총 개시물 수

			while($rs = mysql_fetch_array($result)) {
				$adminapi[reportCopy][$a][userID] = $rs[userID];
				$adminapi[reportCopy][$a][userName] = $rs[userName];
				$adminapi[reportCopy][$a][lectureOpenSeq] = $rs[lectureOpenSeq];
				$adminapi[reportCopy][$a][contentsCode] = $rs[contentsCode];
				$adminapi[reportCopy][$a][contentsName] = $rs[contentsName];
				$adminapi[reportCopy][$a][completeStatus] = $rs[complete_status];
				$adminapi[reportCopy][$a][completeDate] = $rs[complete_date];
				$adminapi[reportCopy][$a][copyRatio] = $rs[disp_total_copy_ratio];
				$adminapi[reportCopy][$a][lectureStart] = $rs[lectureStart];
				$adminapi[reportCopy][$a][lectureEnd] = $rs[lectureEnd];
				$a++;
			}
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
?>