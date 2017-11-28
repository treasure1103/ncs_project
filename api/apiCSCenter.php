<?php
	header('Content-Type:text/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<? //echo "work"; exit;
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
		$categorySeq = $_POST['categorySeq'];
		$userID = $_POST['userID'];
		$userName = $_POST['userName'];
		$phone01 = $_POST['phone01'];
		$phone02 = $_POST['phone02'];
		$phone03 = $_POST['phone03'];
		$email01 = $_POST['email01'];
		$email02 = $_POST['email02'];
		$subject = $_POST['subject'];
		$content = $_POST['content'];
		$addItem01 = $_POST['addItem01'];
		$addItem02 = $_POST['addItem02'];

		if($categorySeq == "") {
			$categorySeq = 0;
		}
		if($replySeq == "") {
			$replySeq = 0;
		}

		$attachURL = "/attach/cscenter/";
		$uploadDir = $_SERVER['DOCUMENT_ROOT'].$attachURL;
		$uploadDate = date('i');
		$attachFile01Name = $_FILES['attachFile01']["name"];
		$delFile01 = $_POST['delFile01'];

		if ($delFile01 == "Y") {
				$upAttachFile01 = "attachFile01=null,	attachFile01Name=null, ";

				//서버에서 파일 삭제
				$query01 = "SELECT attachFile01 FROM nynCScenter WHERE seq=".$seq;
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

		


		$queryQ = "			 categorySeq='".$categorySeq."', 
							 userID='".$userID."', 
							 userName='".$userName."',
							 phone01='".$phone01."',
							 phone02='".$phone02."',
							 phone03='".$phone03."',
							 email01='".$email01."',
							 email02='".$email02."',
							 subject='".$subject."',
							 content='".addslashes(trim($content))."',
							 addItem01='".$addItem01."',
							 addItem02='".$addItem02."'
							 ";

		if($seq == "") { // 글쓰기일 경우 글 저장
			

			 // 일반글이면 num+1
				$queNum="SELECT MAX(num) AS num FROM nynCScenter";
				$resultNum = mysql_query($queNum);
				$rsNum = mysql_fetch_assoc($resultNum);
				$mNum = $rsNum[num]+1;
			

			$query = "INSERT INTO nynCScenter SET
									 num='".$mNum."',
									 inputDate='".$inputDate."', "
									 .$upAttachFile01.$upAttachFile02.$pwdQ.$replyOrderByQ.$queryQ;
			$result = mysql_query($query);

			$queNum02="SELECT MAX(seq) AS seq FROM nynCScenter";
			$resultNum02 = mysql_query($queNum02);
			$rsNum02 = mysql_fetch_assoc($resultNum02);
			$seq = $rsNum02[seq];

		} else { // 글 수정인 경우 addItem01(작성자)
				$qCmt = "SELECT addItem01 FROM nynCScenter WHERE seq=".$seq;
				$rsCmt = mysql_query($qCmt);
				$rs = mysql_fetch_array($rsCmt);

				if($_SESSION['loginUserLevel'] < 5 || // 관리자인 경우,
					 $rs[addItem01] == $_SESSION['loginUserID']) // 작성자 본인인 경우에만 수정 가능 )
			    {
							$query = "UPDATE nynCScenter SET
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

			$queryD = "SELECT addItem01, num  FROM nynCScenter WHERE seq=".$seq;
			$resultD = mysql_query($queryD);
			$rs = mysql_fetch_array($resultD);

				if($_SESSION['loginUserLevel'] < 5 || // 현 로그인 사용자가 관리자인 경우,
					 $rs[addItem01] == $_SESSION['loginUserID']  )// 작성자 본인인 경우에만 수정 가능
					{

							$query = "DELETE FROM nynCScenter WHERE seq=".$seq;
							$result = mysql_query($query);
					 }

		
			if($result){
				echo "success";
			} else {
				echo "error";
			}
			exit;

	} else if($method == "GET") { //게시판 데이터를 json 문서로 출력
			$seq = $_GET['seq'];
			$num = $_GET['num'];
			$categorySeq = $_GET['categorySeq'];
			$userID = $_GET['userID'];
			$userName = $_GET['userName'];
			$subject = $_GET['subject'];
			$content = $_GET['content'];
			$addItem01 = $_GET['addItem01'];
			$addItem02 = $_GET['addItem02'];
			$attachURL = '/attach/cscenter/';

			${$serchType} = $searchValue;

			if($page == "") {
				$page = 1;
			}
			if($list == "") {
				$list = 10;
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
			$que = "SELECT * FROM nynCScenter
								WHERE 1 ".$qSearch;
			$res = mysql_query($que);
			$allPost = mysql_num_rows($res);//
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' LIMIT '.$currentLimit.', '.$list; //limit sql 구문

			$query = "SELECT
								 	 *,
									 (SELECT COUNT(seq) FROM nynCSComment WHERE CScenterSeq=LB.seq) AS commentCount,
									 (SELECT value02 FROM nynCategory WHERE seq=LB.categorySeq) AS categoryName,
									 (SELECT value02 FROM nynCategory WHERE seq=LB.status) AS satatusName
								FROM nynCScenter AS LB
								WHERE 1 ".$qSearch." 
								ORDER BY num DESC
								".$sqlLimit;
							//	echo $query;
			$result = mysql_query($query);
			$a = 0;

			$adminapi[totalCount] = "$allPost";
			$adminapi[attachURL] = $attachURL;

			while($rs = mysql_fetch_array($result)) {
				$adminapi[board][$a][seq] = $rs[seq];
				$adminapi[board][$a][num] = $rs[num];
				$adminapi[board][$a][userID] = $rs[userID];
				$adminapi[board][$a][userName] = $rs[userName];
				$adminapi[board][$a][categorySeq] = $rs[categorySeq];
				$adminapi[board][$a][categoryName] = $rs[categoryName];
				$userID = $rs[userID];
				$password = $rs[pwd];

				$adminapi[board][$a][content] = stripslashes($rs[content]);
				$adminapi[board][$a][phone01] = $rs[phone01];
				$adminapi[board][$a][phone02] = $rs[phone02];
				$adminapi[board][$a][phone03] = $rs[phone03];
				$adminapi[board][$a][email01] = $rs[email01];
				$adminapi[board][$a][email02] = $rs[email02];
				$adminapi[board][$a][userIP] = $rs[userIP];

				// 조회수 +1
				$hits = $rs[hits]+1;
				$queryU = "UPDATE nynCScenter SET hits='".$hits."' WHERE seq=".$rs[seq];
				$resultU = mysql_query($queryU);


				if($rs[attachFile01] == "") { // 첨부파일01이 있을때만 urlencode
					$adminapi[board][$a][attachFile01] = $rs[attachFile01];
				} else {
					$adminapi[board][$a][attachFile01] = $rs[attachFile01];
				}

				$adminapi[board][$a][attachFile01Name] = $rs[attachFile01Name];
				$adminapi[board][$a][addItem01] = $rs[addItem01];
				$adminapi[board][$a][addItem02] = $rs[addItem02];
				$adminapi[board][$a][hits] = $rs[hits];
				$adminapi[board][$a][inputDate] = $rs[inputDate];
				$adminapi[board][$a][commentCount] = $rs[commentCount];
				$a++;
			}
			
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
			exit;
		}
		
		
		@mysql_close();
?>