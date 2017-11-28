<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") { // 코멘트 쓰기
		$seq = $_POST['seq'];
		$CScenterSeq = $_POST['CScenterSeq'];
		$userID = $_POST['userID'];
		$userName = $_POST['userName'];
		$content = $_POST['content'];

		if($_SESSION['loginUserLevel'] == "") {
			$_SESSION['loginUserLevel'] = 10;
		}

	
			if($seq == ""){ // 코멘트 쓰기
				$query = "INSERT nynCSComment SET
										CScenterSeq='".$CScenterSeq."',
										userID='".$userID."',
										userName='".$userName."',
										".$pwdQ." 
									  content='".addslashes(trim($content))."',
										inputDate='".$inputDate."',
										userIP='".$userIP."'";
				$result = mysql_query($query);

			} else { // 코멘트 수정
				$qCmt = "SELECT userID FROM nynCSComment WHERE seq=".$seq;
				$rsCmt = mysql_query($qCmt);
				$rs = mysql_fetch_array($rsCmt);

				if($rs[userID] == $_SESSION['loginUserID'] ) // 작성자 본인인 경우에만 수정 가능
				{
							$query = "UPDATE nynCSComment
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

				$qCmt = "SELECT userID FROM nynCSComment WHERE seq=".$seq;
				$rsCmt = mysql_query($qCmt);
				$rs = mysql_fetch_array($rsCmt);

				if($_SESSION['loginUserLevel'] < 5 || // 현 로그인 사용자가 관리자인 경우,
					 $rs[userID] == $_SESSION['loginUserID'] ) // 작성자 본인인 경우에만 수정 가능
					{
							$query = "DELETE FROM nynCSComment WHERE seq=".$seq;
							$result = mysql_query($query);
					 }

			if($result){
				echo "success";
			} else {
				echo "error";
			}
			exit;

	} else if($method == "GET") {
			$CScenterSeq = $_GET['CScenterSeq'];
			$qCmt = "SELECT * FROM nynCSComment WHERE CScenterSeq=".$CScenterSeq." ORDER BY seq asc";
			$rsCmt = mysql_query($qCmt);
			$cmtCnt = mysql_num_rows($rsCmt);
			$b = 0;

			$adminapi[totalCount] = "$cmtCnt";

			while($rsP = mysql_fetch_array($rsCmt)) {
				$adminapi[comment][$b][seq] = $rsP[seq];
				$adminapi[comment][$b][CScenterSeq] = $rsP[CScenterSeq];
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
		
	@mysql_close();
?>