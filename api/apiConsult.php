<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") { // 글 쓰기와 글 수정은 POST로 받음
		$seq = $_POST['seq'];
		$boardType = $_POST['boardType'];
		$userName = $_POST['userName'];
		$phone01 = $_POST['phone01'];
		$phone02 = $_POST['phone02'];
		$phone03 = $_POST['phone03'];
		$email01 = $_POST['email01'];
		$email02 = $_POST['email02'];
		$subject = $_POST['subject'];
		$content = $_POST['content'];
		$reply = $_POST['reply'];
		$status = $_POST['status'];
		$userID = $_SESSION[loginUserID];
		if(!$userID) {
			$userID = 'guest';
		}

		if(!$userName) {
			$userName = '비회원';
		}

		$queryQ = "boardType='".$boardType."', 
							 userID='".$userID."',
							 userName='".$userName."',
							 phone01='".$phone01."',
							 phone02='".$phone02."',
							 phone03='".$phone03."',
							 email01='".$email01."',
							 email02='".$email02."',
							 subject='".$subject."',
							 content='".$content."',
							 userIP='".$userIP."'";

		if($boardType == "study") {
			$addItem01 = $_POST['addItem01'];
			$addItem01Q = " , addItem01='".$addItem01."' ";
		}

		if($seq == "") { // 글쓰기일 경우 글 저장
			$query = "INSERT INTO nynConsult SET inputDate='".$inputDate."',".$queryQ.$addItem01Q;
			$result = mysql_query($query);

			$queNum="SELECT MAX(seq) AS seq FROM nynConsult";
			$resultNum = mysql_query($queNum);
			$rsNum = mysql_fetch_assoc($resultNum);
			$seq = $rsNum[seq];

			//insert_emma('01000000000',$_smsNumber,'('.$_siteName.') 1:1 문의글이 등록되었습니다.',$sendTime);

		} else { // 글 수정인 경우

			if($reply != "") { // 답변글 달기
				if($_SESSION['loginUserLevel'] > 8) { //관리자만 수정 가능
					echo "error";
					exit;
				}

				$query = "UPDATE nynConsult 
									SET replyDate='".$inputDate."',
											replyID='".$_SESSION[loginUserID]."',
											reply='".$reply."',
											status='".$status."'
									WHERE seq=".$seq;
				$result = mysql_query($query);

				if($status == "C") { //처리 완료 상태라면 메일 발송함.
					$queryE = "SELECT phone01, phone02, phone03, email01, email02, boardType FROM nynConsult WHERE seq=".$seq;
					$resultE = mysql_query($queryE);
					$rs = mysql_fetch_array($resultE);

					$mobile = $rs[phone01].$rs[phone02].$rs[phone03];
					insert_emma($mobile,$_smsNumber,'['.$_siteName.'] 1:1문의 답변이 등록되었습니다. 확인 부탁드립니다. (내 강의실-상담신청내역)',$sendTime);

					$toMail = $rs[email01]."@".$rs[email02];
					$fromMail = $_adminMail;

					$subject = "[".$_siteName."] 문의 답변입니다.";
					$content = $reply;
					//$filepath = $_SERVER["DOCUMENT_ROOT"]."/member/join_mail.php";
					$filepath = "";
					$var = "";

					mail_fsend($toMail, $fromMail, $subject, $content, '', '', '', $filepath, $var);
				}
			} else { // 작성자 글 수정
				$query = "UPDATE nynConsult 
									SET lastUpdate='".$inputDate."',
											subject='".$_SESSION[loginUserID]."',
											content='".$reply."'
									WHERE userID='".$_SESSION[loginUserID]."' AND seq=".$seq;
				$result = mysql_query($query);
			}
		}

			if($result){
				echo $seq;
			} else {
				echo "error";
			}
			exit;

	} else if($method == "DELETE") { //글 삭제 시
			parse_str(file_get_contents("php://input"), $_DEL);
			$seq = $_DEL['seq'];

			if($_SESSION['loginUserLevel'] > 4) {
				$qDel = " AND userID='".$_SESSION['loginUserID']."'";
			} else {
				$qDel = "";
			}

			$query = "DELETE FROM nynConsult WHERE seq=".$seq.$qDel;
			$result = mysql_query($query);
			if($result){
				echo "success";
			} else {
				echo "error";
			}
			exit;

	} else if($method == "GET") { //게시판 데이터를 json 문서로 출력

			if(!$_SESSION['loginUserLevel']){
				$userLevelCheck = 10;
			} else {
				$userLevelCheck = $_SESSION['loginUserLevel'];
			}

			$seq = $_GET['seq'];
			$userName = $_GET['userName'];
			$subject = $_GET['subject'];
			$content = $_GET['content'];
			$boardType = $_GET['boardType'];
			$viewType = $_GET['viewType'];
	
			if($boardType == "consult") {
				$attachURL = '/attach/consult/';
			}

			switch($searchType){
				case "subject":
					$subject = $searchValue;
				break;

				case "userName":
					$userName = $searchValue;
				break;
			}

			if($sortType == "") {
				$sortType = "A.seq";
			} else {
				$sortType = "A.".$sortType;
			}
			if($sortValue == "") {
				$sortValue = "DESC";
			}

			if($page == "") {
				$page = 1;
			}
			if($list == "") {
				$list = 10;
			}
			if($seq != "") {
				$qSeq = " AND A.seq='".$seq."'";
			}
			if($userName != "") {
				$qName = " AND A.userName LIKE '%".$userName."%'";
			}
			if($subject != "") {
				$qSubject = " AND A.subject LIKE '%".$subject."%'";
			}
			if($boardType != "") {
				$qBoardType = " AND A.boardType = '".$boardType."'";
			}
			if($viewType == "admin") {
				if($_SESSION['loginUserLevel'] > 8) {
					echo '{"result" : "access error"}';
					exit;
				}
			} else {
				$qViewType = " AND A.userID='".$_SESSION[loginUserID]."'";
			}

			if($userLevelCheck == 7) {
				$qTutor = " AND A.boardType='study'";
			}

			$qSearch = $qSeq.$qID.$qName.$qSubject.$qContent.$qViewType.$qTutor.$qBoardType;

			$que = "SELECT A.*, B.userName AS replyName 
							FROM nynConsult A
							LEFT OUTER 
							JOIN nynMember B
							ON A.replyID=B.userID 
							WHERE A.seq <> 0 ".$qSearch;
			$res = mysql_query($que);
			$allPost = mysql_num_rows($res);
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' LIMIT '.$currentLimit.', '.$list; //limit sql 구문

			$query = "SELECT A.*, B.userName AS replyName 
								FROM nynConsult A
								LEFT OUTER 
								JOIN nynMember B
								ON A.replyID=B.userID 
								WHERE A.seq <> 0 ".$qSearch." ORDER BY ".$sortType." ".$sortValue.$sqlLimit;
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;
			$adminapi[totalCount] = "$allPost";
			$adminapi[attachURL] = $attachURL;

			while($rs = mysql_fetch_array($result)) {
				$adminapi[consult][$a][seq] = $rs[seq];
				$adminapi[consult][$a][boardType] = $rs[boardType];
				$adminapi[consult][$a][userID] = $rs[userID];
				$adminapi[consult][$a][userName] = $rs[userName];
				$adminapi[consult][$a][phone01] = $rs[phone01];
				$adminapi[consult][$a][phone02] = $rs[phone02];
				$adminapi[consult][$a][phone03] = $rs[phone03];
				$adminapi[consult][$a][email01] = $rs[email01];
				$adminapi[consult][$a][email02] = $rs[email02];
				$adminapi[consult][$a][attachFile01] = $rs[attachFile01];
				$adminapi[consult][$a][inputDate] = $rs[inputDate];
				$adminapi[consult][$a][subject] = $rs[subject];
				$adminapi[consult][$a][content] = $rs[content];
				$adminapi[consult][$a][replyID] = $rs[replyID];
				$adminapi[consult][$a][replyName] = $rs[replyName];
				$adminapi[consult][$a][reply] = $rs[reply];
				$adminapi[consult][$a][replyDate] = $rs[replyDate];
				$adminapi[consult][$a][status] = $rs[status];
				$adminapi[consult][$a][userIP] = $rs[userIP];
				$a++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
			exit;
		}
?>