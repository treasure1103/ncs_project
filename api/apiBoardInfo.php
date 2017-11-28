<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$seq = $_POST['seq'];
		$boardName = $_POST['boardName'];
		$boardMode = $_POST['boardMode'];
		$useName = $_POST['useName'];
		$useEmail = $_POST['useEmail'];
		$usePhone = $_POST['usePhone'];
		$usePassword = $_POST['usePassword'];
		$useSecret = $_POST['useSecret'];
		$useTop = $_POST['useTop'];
		$useCategory = $_POST['useCategory'];
		$useReply = $_POST['useReply'];
		$useComment = $_POST['useComment'];
		$useFile = $_POST['useFile'];
		$useSearch = $_POST['useSearch'];
		$useDateView = $_POST['useDateView'];
		$useHitView = $_POST['useHitView'];
		$orderBy = $_POST['orderBy'];
		$userID = $_POST['userID'];
		$memo = $_POST['memo'];

		if($_SESSION['loginUserLevel'] > 4) { //관리자만 수정 가능
			echo "error";
			exit;
		}

		if($useName != "") {

				if($useName == "") {
					$useName = "N";
				}
				if($useEmail == "") {
					$useEmail = "N";
				}
				if($usePhone == "") {
					$usePhone = "N";
				}
				if($usePassword == "") {
					$usePassword = "N";
				}
				if($useSecret == "") {
					$useSecret = "N";
				}
				if($useTop == "") {
					$useTop = "N";
				}
				if($useCategory == "") {
					$useCategory = "N";
				}
				if($useReply == "") {
					$useReply = "N";
				}
				if($useComment == "") {
					$useComment = "N";
				}
				if($useFile == "") {
					$useFile = "0";
				}
				if($useSearch == "") {
					$useSearch = "N";
				}
				if($useDateView == "") {
					$useDateView = "N";
				}
				if($useHitView == "") {
					$useHitView = "N";
				}

				$queryQ =  "boardMode='".$boardMode."',
										useName='".$useName."',
										useEmail='".$useEmail."',
										usePhone='".$usePhone."',
										usePassword='".$usePassword."',
										useSecret='".$useSecret."',
										useTop='".$useTop."',
										useCategory='".$useCategory."',
										useReply='".$useReply."',
										useComment='".$useComment."',
										useFile='".$useFile."',
										useSearch='".$useSearch."',
										useDateView='".$useDateView."',
										useHitView='".$useHitView."',
										userID='".$userID."'";

		} else {
				$queryQ =  "memo='".$memo."',
										boardName='".$boardName."',
										userID='".$userID."'";
		}

				if($seq == "") {
					$queryC = "SELECT MAX(orderBy) AS orderBy FROM nynBoardInfo";
					$resultC = mysql_query($queryC);
					$rsC = mysql_fetch_assoc($resultC);
					$orderByMax = $rsC[orderBy];
					
					if($orderByMax == "") {
						$orderBy = "1";
					}

					if($orderBy == "") {
						$orderBy = $orderByMax+1;
					}

					$queryU = "UPDATE nynBoardInfo SET orderBy=orderBy+1 WHERE orderBy>=".$orderBy;
					$resultU = mysql_query($queryU);

					$query = "INSERT INTO nynBoardInfo SET inputDate='".$inputDate."', orderBy='".$orderBy."', ".$queryQ;
					$result = mysql_query($query);

					$queNum="SELECT MAX(seq) AS seq FROM nynBoardInfo";
					$resultNum = mysql_query($queNum);
					$rsNum = mysql_fetch_assoc($resultNum);
					$seqN = $rsNum[seq];

					//게시판 권한 생성
					$queryI = "INSERT INTO nynBoardPermit SET 
											boardCode='".$seqN."'";
					$resultI = mysql_query($queryI);
					
					//게시판 카테고리 생성
					$queryCm = "SELECT seq FROM nynCategory WHERE value01='BoardCategory'";
					$resultCm = mysql_query($queryCm);
					$ctSeq = mysql_result($resultCm,0,'seq');

					$queryO = "SELECT MAX(orderBy) AS orderBy FROM nynCategory WHERE division='".$ctSeq."' ORDER BY orderBy LIMIT 1";
					$resultO = mysql_query($queryO);
					$orderByO = mysql_result($resultO,0,'orderBy');

					if($orderByO != "") {
						$orderByO = $orderByO+1;
					} else {
						$orderByO = 1;
					}

					$queryCt = "INSERT INTO nynCategory SET
												division=".$ctSeq.",
												value01='".$seqN."',
												value02='".$boardName."',
												value03='',
												userID='".$_SESSION['loginUserID']."',
												enabled='Y',
												inputDate='".$inputDate."',
												orderBy='".$orderByO."'";
					$resultCt = mysql_query($queryCt);

				} else {
					$queryC = "SELECT orderBy FROM nynBoardInfo WHERE seq=".$seq;
					$resultC = mysql_query($queryC);
					$rsC = mysql_fetch_assoc($resultC);
					$orderByC = $rsC[orderBy];

					if($orderBy != $orderByC) {
						if($orderBy > $orderByC) { //수정할 순번이 현재 순번보다 큰 경우 -1
							$queryU = "UPDATE nynBoardInfo SET orderBy=orderBy-1 WHERE seq <> ".$seq." AND (orderBy>=".$orderByC." AND orderBy <=".$orderBy.")";
							$resultU = mysql_query($queryU);
					
						} else { //수정할 순번이 현재 순번보다 작은 경우 +1
							$queryU = "UPDATE nynBoardInfo SET orderBy=orderBy+1 WHERE seq <> ".$seq." AND (orderBy>=".$orderBy." AND orderBy <=".$orderByC.")";
							$resultU = mysql_query($queryU);
						}
					}

				if($useName == "") {
					$orderByQ = "orderBy='".$orderBy."', ";
				}

					$query = "UPDATE nynBoardInfo SET ".$orderByQ.$queryQ." WHERE seq=".$seq;
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

			if($_SESSION['loginUserType'] != "admin") { //관리자만 수정 가능
				echo "error";
				exit;
			}

			//순번 수정 -> 순번이 더 큰 값들을 -1씩 수정
			$query1 = "SELECT orderBy FROM nynBoardInfo WHERE seq=".$seq;
			$result1 = mysql_query($query1);
			$orderBy = mysql_result($result1,0,'orderBy');

			$queryU = "UPDATE nynBoardInfo SET orderBy=orderBy-1 WHERE seq <> ".$seq." AND orderBy>=".$orderBy;
			$resultU = mysql_query($queryU);

			//보드 정보 삭제
			$queryI = "DELETE FROM nynBoardInfo WHERE seq=".$seq;
			$resultI = mysql_query($queryI);

			//보드 권한 삭제
			$query = "DELETE FROM nynBoardPermit WHERE boardCode=".$seq;
			$result = mysql_query($query);

			//보드 카테고리 삭제
			$queryCm = "SELECT seq FROM nynCategory WHERE value01='BoardCategory'";
			$resultCm = mysql_query($queryCm);
			$ctSeq = mysql_result($resultCm,0,'seq');

			$queryCt = "SELECT seq FROM nynCategory WHERE division=".$ctSeq." AND value01=".$seq;
			$resultCt = mysql_query($queryCt);
			$ctSeq2 = mysql_result($resultCt,0,'seq');

			$queryD = "DELETE FROM nynCategory WHERE division=".$ctSeq2;
			$resultD = mysql_query($query);

			$queryD2 = "DELETE FROM nynCategory WHERE division=".$ctSeq." AND value01=".$seq;
			$resultD2 = mysql_query($queryD2);

			if($result){
				echo "success";
			} else {
				echo "error";
			}
			exit;

	} else if($method == "GET") {
			$seq = $_GET['seq'];
			$boardName = $_GET['boardName'];
			$boardMode = $_GET['boardMode'];

			if($seq != "") {
				$qSeq = " AND seq=".$seq;
			}
			if($boardName != "") {
				$qBN = " AND boardName like '%".$boardName."%'";
			}
			if($boardMode != "") {
				$qBM = " AND boardMode=".$boardMode;
			}

			$qSearch = $qSeq.$qBC.$qBN.$qBM;

			$query = "SELECT * FROM nynBoardInfo WHERE seq <> 0 ".$qSearch." ORDER BY orderBy ";
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			while($rs = mysql_fetch_array($result)) {
				$adminapi[boardInfo][$a][seq] = $rs[seq];
				$adminapi[boardInfo][$a][boardName] = $rs[boardName];
				$adminapi[boardInfo][$a][boardMode] = $rs[boardMode];
				$adminapi[boardInfo][$a][useName] = $rs[useName];
				$adminapi[boardInfo][$a][useEmail] = $rs[useEmail];
				$adminapi[boardInfo][$a][usePhone] = $rs[usePhone];
				$adminapi[boardInfo][$a][usePassword] = $rs[usePassword];
				$adminapi[boardInfo][$a][useSecret] = $rs[useSecret];
				$adminapi[boardInfo][$a][useTop] = $rs[useTop];
				$adminapi[boardInfo][$a][useCategory] = $rs[useCategory];
				$adminapi[boardInfo][$a][useReply] = $rs[useReply];
				$adminapi[boardInfo][$a][useComment] = $rs[useComment];
				$adminapi[boardInfo][$a][useFile] = $rs[useFile];
				$adminapi[boardInfo][$a][useSearch] = $rs[useSearch];
				$adminapi[boardInfo][$a][useDateView] = $rs[useDateView];
				$adminapi[boardInfo][$a][useHitView] = $rs[useHitView];
				$adminapi[boardInfo][$a][orderBy] = $rs[orderBy];
				$adminapi[boardInfo][$a][inputDate] = $rs[inputDate];
				$adminapi[boardInfo][$a][userID] = $rs[userID];
				$adminapi[boardInfo][$a][memo] = $rs[memo];
				$a++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
		
	@mysql_close();
?>