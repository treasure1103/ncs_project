<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") { // 코멘트 쓰기
		$seq = $_POST['seq'];
		$boardCode = $_POST['boardCode'];
		$boardSeq = $_POST['boardSeq'];
		$userID = $_POST['userID'];
		$pwd = $_POST['pwd'];
		$userName = $_POST['userName'];
		$content = $_POST['content'];

		if($_SESSION['loginUserLevel'] == "") {
			$_SESSION['loginUserLevel'] = 10;
		}

		$sqlC = "SELECT commentPermit FROM nynBoardPermit WHERE boardCode='".$boardCode."'";
		$resultC = mysql_query($sqlC);
		$rsC = mysql_fetch_array($resultC);

		if($rsC[commentPermit] < $_SESSION['loginUserLevel']) { //댓글 등록 권한 체크
			echo '{"result" : "error"}';
			exit;
		}

		//비밀번호 수정인 경우 암호화 처리 후 수정 요청
		if($pwd != "") {
			$hash = password_hash($pwd, PASSWORD_DEFAULT);
			$pwdQ= "pwd='".$hash."',";
		} else {
			$pwdQ = "pwd=null, ";
		}

			if($seq == ""){ // 코멘트 쓰기
				$query = "INSERT nynBoardComment SET
										boardSeq='".$boardSeq."',
										userID='".$userID."',
										userName='".$userName."',
										".$pwdQ." 
									  content='".addslashes(trim($content))."',
										inputDate='".$inputDate."',
										userIP='".$userIP."'";
				$result = mysql_query($query);

			} else { // 코멘트 수정
				$qCmt = "SELECT userID, pwd FROM nynBoardComment WHERE seq=".$seq;
				$rsCmt = mysql_query($qCmt);
				$rs = mysql_fetch_array($rsCmt);

				if($rs[userID] == $_SESSION['loginUserID'] || // 작성자 본인인 경우,
					 password_verify($pwd, $rs[pwd])) { // 비밀번호가 일치한 경우에만 수정 가능
							$query = "UPDATE nynBoardComment
												SET content='".$content."',
														userIP='".$userIP."'
												WHERE seq=".$seq;
							$result = mysql_query($query);
							}

			}
				if($result){
					echo '{"result" : "success"}';
				} else {
					echo '{"result" : "error"}';
				}
				exit;

	} else if($method == "DELETE") { // 글 삭제
			parse_str(file_get_contents("php://input"), $_DEL);
			$seq = $_DEL['seq'];
			$pwd = $_DEL['pwd'];

				$qCmt = "SELECT userID, pwd FROM nynBoardComment WHERE seq=".$seq;
				$rsCmt = mysql_query($qCmt);
				$rs = mysql_fetch_array($rsCmt);

				if($_SESSION['loginUserLevel'] > 4 || // 현 로그인 사용자가 관리자인 경우,
					 $rs[userID] == $_SESSION['loginUserID'] || // 작성자 본인인 경우,
					 password_verify($pwd, $rs[pwd])) { // 비밀번호가 일치한 경우에만 수정 가능
							$query = "DELETE FROM nynBoardComment WHERE seq=".$seq;
							$result = mysql_query($query);
					 }

			if($result){
				echo "success";
			} else {
				echo "error";
			}
			exit;

	} else if($method == "GET") {
			$boardSeq = $_GET['boardSeq'];
			$qCmt = "SELECT * FROM nynBoardComment WHERE boardSeq=".$boardSeq." ORDER BY seq asc";
			$rsCmt = mysql_query($qCmt);
			$cmtCnt = mysql_num_rows($rsCmt);
			$b = 0;

			$adminapi[totalCount] = "$cmtCnt";

			while($rsP = mysql_fetch_array($rsCmt)) {
				$adminapi[comment][$b][seq] = $rsP[seq];
				$adminapi[comment][$b][boardSeq] = $rsP[boardSeq];
				$adminapi[comment][$b][userID] = $rsP[userID];
				$adminapi[comment][$b][userName] = $rsP[userName];
				$adminapi[comment][$b][content] = stripslashes($rsP[content]);
				$adminapi[comment][$b][inputDate] = $rsP[inputDate];
				$adminapi[comment][$b][userIP] = $rsP[userIP];
				$b++;
			}			
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
?>