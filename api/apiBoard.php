<?php
	header('Content-Type:text/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
/*
	boardMode 구분
	1 = 일반
	2 = 갤러리
	3 = 뉴스레터
	4 = FAQ
	5 = 포트폴리오
*/

	if($method == "POST") { // 글 쓰기와 글 수정은 POST로 받음
		$seq = $_POST['seq'];
		$boardCode = $_POST['boardCode'];
		$categorySeq = $_POST['categorySeq'];
		$userID = $_POST['userID'];
		$pwd = $_POST['pwd'];
		$userName = $_POST['userName'];
		$replySeq = $_POST['replySeq'];
		$phone01 = $_POST['phone01'];
		$phone02 = $_POST['phone02'];
		$phone03 = $_POST['phone03'];
		$email01 = $_POST['email01'];
		$email02 = $_POST['email02'];
		$subject = $_POST['subject'];
		$content = $_POST['content'];
		$addItem01 = $_POST['addItem01'];
		$addItem02 = $_POST['addItem02'];
		$secret = $_POST['secret'];
		$top = $_POST['top'];

			// 게시판 형식 체크
			$sqlB = "SELECT boardMode, imageW, imageH FROM nynBoardInfo WHERE seq='".$boardCode."'";
			$resultB = mysql_query($sqlB);
			$rsB = mysql_fetch_array($resultB);
			$boardMode = $rsB[boardMode];
			$imageW = $rsB[imageW];
			$imageH = $rsB[imageH];

			// 게시판 접근 권한 체크
			$sqlC = "SELECT * FROM nynBoardPermit WHERE boardCode='".$boardCode."'";
			$resultC = mysql_query($sqlC);
			$rsC = mysql_fetch_array($resultC);

			if($_SESSION['loginUserLevel'] == "") { // 세션이 없으면 레벨 10
				$_SESSION['loginUserLevel'] = 10;
			}

			if($rsC[writePermit] < $_SESSION['loginUserLevel']) { // 쓰기 접근 권한 체크
				echo '{"result" : "error"}';
				exit;
			}

			if($replySeq != "") { // 답글 접근 권한 체크
				if($rsC[replyPermit] < $_SESSION['loginUserLevel']) {
					echo '{"result" : "error"}';
					exit;
				}
			}

			if($secret == "Y") { // 비밀글 쓰기 권한 체크
				if($rsC[secretPermit] < $_SESSION['loginUserLevel']) {
					echo '{"result" : "error"}';
					exit;
				}
			}

			if($top == "Y") { // 상단공지 쓰기 권한 체크
				if($rsC[topPermit] < $_SESSION['loginUserLevel']) { 
					echo '{"result" : "error"}';
					exit;
				}
			}

		if($secret == "") {
			$secret = "N";
		}
		if($top == "") {
			$top = "N";
		}
		if($categorySeq == "") {
			$categorySeq = 0;
		}
		if($replySeq == "") {
			$replySeq = 0;
		}

		$attachURL = "/attach/board/";
		$uploadDir = $_SERVER['DOCUMENT_ROOT'].$attachURL;
		$uploadDate = date('i');
		$attachFile01Name = $_FILES['attachFile01']["name"];
		$attachFile02Name = $_FILES['attachFile02']["name"];
		$delFile01 = $_POST['delFile01'];
		$delFile02 = $_POST['delFile02'];

		if ($delFile01 == "Y") {
				$upAttachFile01 = "attachFile01=null,	attachFile01Name=null, ";

				//서버에서 파일 삭제
				$query01 = "SELECT attachFile01 FROM nynBoard WHERE seq=".$seq;
				$result01 = mysql_query($query01);
				$dImage01 = mysql_result($result01,0,'attachFile01');

				$delS01 = $_SERVER['DOCUMENT_ROOT'].$attachURL.$dImage01;
				UNLINK($delS01);
		}

			if($attachFile01Name != "") { //첨부파일01이 있을 경우 업로드
					$attachFile01Temp = $_FILES['attachFile01']['tmp_name']; // 업로드 파일 임시저장파일
					$attachFile01Path = $attachURL.$attachFile01Name;
					$attachFile01Save = $uploadDir.$attachFile01Name;

					$nameOK=1;
					$i=1;
					while($nameOK > 0){
						if(file_Exists($attachFile01Save)) { // 같은 파일명이 존재한다면
							$attachFile01Name = $uploadDate.$i."_".$_FILES['attachFile01']["name"];
							$attachFile01Path = $attachURL.$attachFile01Name;
							$attachFile01Save = $uploadDir.$attachFile01Name; // 파일명 앞에 시간을 붙임.
							$i++;
						} else {
							$nameOK = 0;
						}
					}

					@move_uploaded_file($attachFile01Temp, $attachFile01Save);
					$upAttachFile01 = "attachFile01='".$attachFile01Name."',	attachFile01Name='".$_FILES['attachFile01']["name"]."', ";

					if($boardMode == "2" || $boardMode == "3") { // 갤러리, 뉴스레터 형식일때 썸네일 추출
						make_thumbnail($attachFile01C, $imageW, $imageH, $attachFile01C);
					}
				}

		if ($delFile02 == "Y") {
				$upAttachFile02 = "attachFile02=null,	attachFile02Name=null, ";

				//서버에서 파일 삭제
				$query02 = "SELECT attachFile02 FROM nynBoard WHERE seq=".$seq;
				$result02 = mysql_query($query02);
				$dImage02 = mysql_result($result02,0,'attachFile02');

				$delS02 = $_SERVER['DOCUMENT_ROOT'].$attachURL.$dImage02;
				UNLINK($delS02);
		}

			if($attachFile02Name != "") { //첨부파일02이 있을 경우 업로드
					$attachFile02Temp = $_FILES['attachFile02']['tmp_name']; // 업로드 파일 임시저장파일
					$attachFile02Path = $attachURL.$attachFile02Name;
					$attachFile02Save = $uploadDir.$attachFile02Name;

					$nameOK=1;
					$i=1;
					while($nameOK > 0){
						if(file_Exists($attachFile02Save)) { // 같은 파일명이 존재한다면
							$attachFile02Name = $uploadDate.$i."_".$_FILES['attachFile02']["name"];
							$attachFile02Path = $attachURL.$attachFile02Name;
							$attachFile02Save = $uploadDir.$attachFile02Name; // 파일명 앞에 시간을 붙임.
							$i++;
						} else {
							$nameOK = 0;
						}
					}

					@move_uploaded_file($attachFile02Temp, $attachFile02Save);
					$upAttachFile02 = "attachFile02='".$attachFile02Name."',	attachFile02Name='".$_FILES['attachFile02']["name"]."', ";
			}

		$queryQ = "boardCode='".$boardCode."', 
							 categorySeq='".$categorySeq."', 
							 userID='".$userID."', 
							 userName='".$userName."',
							 replySeq='".$replySeq."',
							 phone01='".$phone01."',
							 phone02='".$phone02."',
							 phone03='".$phone03."',
							 email01='".$email01."',
							 email02='".$email02."',
							 subject='".$subject."',
							 content='".addslashes(trim($content))."',
							 userIP='".$userIP."',
							 addItem01='".$addItem01."',
							 addItem02='".$addItem02."',
							 secret='".$secret."',
							 top='".$top."'";

		if($seq == "") { // 글쓰기일 경우 글 저장
			
			if($pwd != "") { // 비번 있으면 암호화 처리
				$hash = password_hash($pwd, PASSWORD_DEFAULT);
				$pwdQ= "pwd='".$hash."', ";
			}

			if($replySeq != 0) { // 답글(reply)이면 원본글과 동일한 num 값 저장
				$queryD = "SELECT pwd, num, replyOrderBy FROM nynBoard WHERE seq=".$replySeq;
				$resultD = mysql_query($queryD);
				$rsD = mysql_fetch_array($resultD);

				$mNum = $rsD[num];
				$replyOrderBy = $rsD[replyOrderBy]+1;
				$replyOrderByQ = "replyOrderBy = '".$replyOrderBy."', ";

				if($secret == "Y") { // 답글이 비밀글이면
					if($rsD[pwd] != "") { // 원본글에 비번이 있을때만 그대로 가져옴
						$pwdQ= "pwd='".$rsD[pwd]."', ";
					}
				}

				$query = "UPDATE nynBoard 
									SET replyOrderBy = replyOrderBy+1
									WHERE num=".$mNum." AND replyOrderBy>=".$replyOrderBy;
				$result = mysql_query($query);

			} else { // 일반글이면 num+1
				$queNum="SELECT MAX(num) AS num FROM nynBoard";
				$resultNum = mysql_query($queNum);
				$rsNum = mysql_fetch_assoc($resultNum);
				$mNum = $rsNum[num]+1;
			}

			$query = "INSERT INTO nynBoard SET
									 num='".$mNum."',
									 inputDate='".$inputDate."', "
									 .$upAttachFile01.$upAttachFile02.$pwdQ.$replyOrderByQ.$queryQ;
			$result = mysql_query($query);

			$queNum02="SELECT MAX(seq) AS seq FROM nynBoard";
			$resultNum02 = mysql_query($queNum02);
			$rsNum02 = mysql_fetch_assoc($resultNum02);
			$seq = $rsNum02[seq];

		} else { // 글 수정인 경우
				$qCmt = "SELECT userID, pwd FROM nynBoard WHERE seq=".$seq;
				$rsCmt = mysql_query($qCmt);
				$rs = mysql_fetch_array($rsCmt);

				if($_SESSION['loginUserLevel'] < 5 || // 관리자인 경우,
					 $rs[userID] == $_SESSION['loginUserID'] || // 작성자 본인인 경우,
					 password_verify($pwd, $rs[pwd])) { // 비밀번호가 일치한 경우에만 수정 가능
							$query = "UPDATE nynBoard SET
													 updateDate='".$inputDate."',"
													 .$upAttachFile01.$upAttachFile02.$queryQ."
												WHERE seq=".$seq;
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
			$pwd = $_DEL['pwd'];

			$queryD = "SELECT userID, pwd, num, replyOrderBy FROM nynBoard WHERE seq=".$seq;
			$resultD = mysql_query($queryD);
			$rs = mysql_fetch_array($resultD);

				if($_SESSION['loginUserLevel'] > 4 || // 현 로그인 사용자가 관리자인 경우,
					 $rs[userID] == $_SESSION['loginUserID'] || // 작성자 본인인 경우,
					 password_verify($pwd, $rs[pwd])) { // 비밀번호가 일치한 경우에만 수정 가능
							$query = "DELETE FROM nynBoard WHERE seq=".$seq;
							$result = mysql_query($query);
					 }

			// 답글을 삭제한 경우 순번 수정
			$query = "UPDATE nynBoard 
								 SET replyOrderBy=replyOrderBy-1 
								 WHERE num=".$rs[num]." AND replyOrderBy > ".$rs[replyOrderBy];
			$result = mysql_query($query);

			if($result){
				echo "success";
			} else {
				echo "error";
			}
			exit;

	} else if($method == "GET") { //게시판 데이터를 json 문서로 출력
			$seq = $_GET['seq'];
			$num = $_GET['num'];
			$boardCode = $_GET['boardCode'];
			$categorySeq = $_GET['categorySeq'];
			$userID = $_GET['userID'];
			$userName = $_GET['userName'];
			$subject = $_GET['subject'];
			$content = $_GET['content'];
			$pwd = $_GET['pwd'];
			$addItem01 = $_GET['addItem01'];
			$addItem02 = $_GET['addItem02'];
			$attachURL = '/attach/board/';

			// 게시판 형식 체크
			$sqlB = "SELECT boardMode FROM nynBoardInfo WHERE seq='".$boardCode."'";
			$resultB = mysql_query($sqlB);
			$rsB = mysql_fetch_array($resultB);
			$boardMode = $rsB[boardMode];

			// 게시판 접근 권한 체크
			$sqlC = "SELECT * FROM nynBoardPermit WHERE boardCode='".$boardCode."'";
			$resultC = mysql_query($sqlC);
			$rsC = mysql_fetch_array($resultC);

			if($_SESSION['loginUserLevel'] == "") {
				$_SESSION['loginUserLevel'] = 10;
			}

			if($rsC[listPermit] < $_SESSION['loginUserLevel']) { // 목록 접근 권한 체크
				echo '{"result" : "error"}';
				exit;
			}

			if($seq != "") { // 내용 접근 권한 체크
				if($rsC[viewPermit] < $_SESSION['loginUserLevel']) {
					echo '{"result" : "error"}';
					exit;
				}
			}

			switch($searchType){
				case "userID":
					$userID = $searchValue;
				break;

				case "userName":
					$userName = $searchValue;
				break;

				case "subject":
					$subject = $searchValue;
				break;

				case "content":
					$content = $searchValue;
				break;
			}

			if($page == "") {
				$page = 1;
			}
			if($list == "") {
				$list = 10;
			}

			if($seq == "") { // 리스트 불러올때만 상단 공지 불러옴
				$sqlTop = "SELECT
											 *,
											 (SELECT COUNT(seq) FROM nynBoardComment WHERE boardSeq=LB.seq) AS commentCount,
											 (SELECT value02 FROM nynCategory WHERE seq=LB.categorySeq) AS categoryName
									 FROM nynBoard AS LB
									 WHERE top='Y' AND boardCode = ".$boardCode;
				$rsTop = mysql_query($sqlTop);
				$topCnt = mysql_num_rows($rsTop);
				$t = 0;

				$adminapi[topCount] = "$topCnt";

				while($rsT = mysql_fetch_array($rsTop)) {
					$adminapi[boardTop][$t][seq] = $rsT[seq];
					$adminapi[boardTop][$t][num] = $rsT[num];
					$adminapi[boardTop][$t][userID] = $rsT[userID];
					$adminapi[boardTop][$t][userName] = $rsT[userName];
					$adminapi[boardTop][$t][replySeq] = $rsT[replySeq];
					$adminapi[boardTop][$t][categorySeq] = $rsT[categorySeq];
					$adminapi[boardTop][$t][categoryName] = $rsT[categoryName];
					$adminapi[boardTop][$t][subject] = $rsT[subject];

					if($rsT[attachFile01] == "") { // 첨부파일01이 있을때만 urlencode 적용
						$adminapi[boardTop][$t][attachFile01] = $rsT[attachFile01];
					} else {
						$adminapi[boardTop][$t][attachFile01] = $rsT[attachFile01];
					}

					$adminapi[boardTop][$t][attachFile01Name] = $rsT[attachFile01Name];

					if($rsT[attachFile02] == "") { // 첨부파일02이 있을때만 urlencode 적용
						$adminapi[boardTop][$t][attachFile02] = $rsT[attachFile02];
					} else {
						$adminapi[boardTop][$t][attachFile02] = $rsT[attachFile02];
					}

					$adminapi[boardTop][$t][attachFile02Name] = $rsT[attachFile02Name];
					$adminapi[boardTop][$t][hits] = $rsT[hits];
					$adminapi[boardTop][$t][inputDate] = $rsT[inputDate];
					$adminapi[boardTop][$t][secret] = $rsT[secret];
					$adminapi[boardTop][$t][commentCount] = $rsT[commentCount];
					$t++;
				}
			}

			if($seq != "") {
				$qSeq = " AND seq='".$seq."'";
			}
			if($categorySeq != "") {
				$qCate = " AND categorySeq=".$categorySeq;
			}
			if($userID != "") {
				$qID = " AND userID LIKE '%".$userID."%'";
			}
			if($userName != "") {
				$qName = " AND userName LIKE '%".$userName."%'";
			}
			if($subject != "") {
				$qSubject = " AND subject LIKE '%".$subject."%'";
			}
			if($content != "") {
				$qContent = " AND content LIKE '%".$content."%'";
			}
			if($addItem01 != "") {
				$qAddItem01 = " AND addItem01='".$addItem01."'";
			}
			if($addItem02 != "") {
				$qAddItem02 = " AND addItem02='".$addItem02."'";
			}

			$qSearch = $qSeq.$qID.$qName.$qSubject.$qContent.$qCate.$qAddItem01.$qAddItem02;
			$que = "SELECT
								 	 *,
									 (SELECT COUNT(seq) FROM nynBoardComment WHERE boardSeq=LB.seq) AS commentCount,
									 (SELECT value02 FROM nynCategory WHERE seq=LB.categorySeq) AS categoryName
								FROM nynBoard AS LB
								WHERE boardCode = ".$boardCode.$qSearch;
			$res = mysql_query($que);
			$allPost = mysql_num_rows($res);
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' LIMIT '.$currentLimit.', '.$list; //limit sql 구문

			$query = "SELECT
								 	 *,
									 (SELECT COUNT(seq) FROM nynBoardComment WHERE boardSeq=LB.seq) AS commentCount,
									 (SELECT value02 FROM nynCategory WHERE seq=LB.categorySeq) AS categoryName
								FROM nynBoard AS LB
								WHERE boardCode = ".$boardCode.$qSearch." 
								ORDER BY num DESC, replyOrderBy
								".$sqlLimit;
			$result = mysql_query($query);
			$a = 0;

			$adminapi[totalCount] = "$allPost";
			$adminapi[attachURL] = $attachURL;

			while($rs = mysql_fetch_array($result)) {
				$adminapi[board][$a][seq] = $rs[seq];
				$adminapi[board][$a][num] = $rs[num];
				$adminapi[board][$a][userID] = $rs[userID];
				$adminapi[board][$a][userName] = $rs[userName];
				$adminapi[board][$a][replySeq] = $rs[replySeq];
				$adminapi[board][$a][replyOrderBy] = $rs[replyOrderBy];
				$adminapi[board][$a][categorySeq] = $rs[categorySeq];
				$adminapi[board][$a][categoryName] = $rs[categoryName];
				$adminapi[board][$a][subject] = $rs[subject];

				if($seq != "" || $rs[boardCode] == 3) { // 글 내용 호출
					$view = "N";
					$userID = $rs[userID];
					$password = $rs[pwd];

				 if($rs[secret] == "Y") { // 비밀글이면 아래 조건에 맞을 때 출력함

						if($replySeq > 0) { // 답글이 비밀글일때
							$queryD = "SELECT userID, pwd, num, replyOrderBy FROM nynBoard WHERE seq=".$rs[replySeq];
							$resultD = mysql_query($queryD);
							$rsD = mysql_fetch_array($resultD);
							$userID = $rsD[userID];
    					$password = $rsD[pwd];
						}

							if($_SESSION['loginUserLevel'] > 4 || // 현 로그인 사용자가 관리자인 경우,
								 $userID == $_SESSION['loginUserID'] || // 작성자 본인인 경우,
								 password_verify($pwd, $password)) { // 비밀번호가 일치한 경우.
								 $view = "Y";
							 }

				  }	else {
					  $view = "Y";
					} 

					if($view == "Y") {
						$adminapi[board][$a][content] = stripslashes($rs[content]);
						$adminapi[board][$a][phone01] = $rs[phone01];
						$adminapi[board][$a][phone02] = $rs[phone02];
						$adminapi[board][$a][phone03] = $rs[phone03];
						$adminapi[board][$a][email01] = $rs[email01];
						$adminapi[board][$a][email02] = $rs[email02];
						$adminapi[board][$a][userIP] = $rs[userIP];

						// 조회수 +1
						$hits = $rs[hits]+1;
						$queryU = "UPDATE nynBoard SET hits='".$hits."' WHERE seq=".$rs[seq];
						$resultU = mysql_query($queryU);

					} else {
						echo '{"result" : "error"}';
						exit;
					}
				} else {
					if($boardMode == "3") { // 게시판 형식이 뉴스레터일때 순수 텍스트 내용만 출력
						$cont = explode('<',$rs[content]);
						for($c=0; $c < count($cont);$c++){
							$cont2[$c] = explode('>',$cont[$c]);
							$contenttxt .= str_replace("&nbsp;"," ",$cont2[$c][1]);
						}
						$adminapi[board][$a][content] = $contenttxt;
					}
				}

				if($rs[attachFile01] == "") { // 첨부파일01이 있을때만 urlencode
					$adminapi[board][$a][attachFile01] = $rs[attachFile01];
				} else {
					$adminapi[board][$a][attachFile01] = $rs[attachFile01];
				}

				$adminapi[board][$a][attachFile01Name] = $rs[attachFile01Name];

				if($rs[attachFile02] == "") { // 첨부파일02이 있을때만 urlencode
					$adminapi[board][$a][attachFile02] = $rs[attachFile02];
				} else {
					$adminapi[board][$a][attachFile02] = $rs[attachFile02];
				}
			
				$adminapi[board][$a][attachFile02Name] = $rs[attachFile02Name];
				$adminapi[board][$a][addItem01] = $rs[addItem01];
				$adminapi[board][$a][addItem02] = $rs[addItem02];
				$adminapi[board][$a][hits] = $rs[hits];
				$adminapi[board][$a][inputDate] = $rs[inputDate];
				$adminapi[board][$a][secret] = $rs[secret];
				$adminapi[board][$a][top] = $rs[top];
				$adminapi[board][$a][commentCount] = $rs[commentCount];
				$a++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
			exit;
		}
	
		
	@mysql_close();
?>