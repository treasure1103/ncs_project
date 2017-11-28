<?php
  header("Content-Type: application/json; charset=UTF-8;");
?>
<? include '../lib/header.php'; ?>
<?
		$query = "SELECT A.*, 
										 IF(ISNULL(B.userName),'입력오류',B.userName) AS userName, 
										 IF(ISNULL(C.userName),'배정오류',C.userName) AS tutorName, 
										 IF(ISNULL(E.contentsName),'입력오류',E.contentsName) AS contentsName, 
										 (SELECT chapter FROM nynProgress 
										 WHERE userID='".$_SESSION['loginUserID']."' AND contentsCode=A.contentsCode AND lectureOpenSeq=A.lectureOpenSeq 
										 ORDER BY chapter DESC LIMIT 1) AS nowChapter,
 										 E.chapter AS allChapter, E.previewImage, E.sourceType, E.mobile
							FROM nynStudy AS A
							LEFT OUTER
							JOIN nynMember AS B ON A.userID=B.userID
							LEFT OUTER
							JOIN nynMember AS C ON A.tutor=C.userID AND C.userLevel=7
							LEFT OUTER
							JOIN nynContents AS E ON A.contentsCode=E.contentsCode 
							WHERE A.userID='".$_SESSION['loginUserID']."' AND CONCAT(lectureStart,' 00:00:00') <= now() AND CONCAT(lectureEnd,' 23:59:59') >= now()
							ORDER BY A.lectureStart, A.lectureEnd";
			$result = mysql_query($query);
			$count = mysql_num_rows($result);

			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분
			$adminapi[totalCount] = "$count"; //총 개시물 수

			while($rs = mysql_fetch_array($result)) {
				$adminapi[study][$a][seq] = $rs[seq];
				$adminapi[study][$a][userID] = $rs[userID];
				$adminapi[study][$a][userName] = $rs[userName];
				$adminapi[study][$a][previewImageURL] = "/attach/contents/";
				$adminapi[study][$a][previewImage] = $rs[previewImage];
				$adminapi[study][$a][contentsCode] = $rs[contentsCode];
				$adminapi[study][$a][contentsName] = $rs[contentsName];
				$adminapi[study][$a][tutorID] = $rs[tutor];
				$adminapi[study][$a][tutorName] = $rs[tutorName];
				$adminapi[study][$a][lectureOpenSeq] = $rs[lectureOpenSeq];
				$adminapi[study][$a][serviceType] = $rs[serviceType];
				$adminapi[study][$a][lectureStart] = $rs[lectureStart];
				$adminapi[study][$a][lectureEnd] = $rs[lectureEnd];
				$adminapi[study][$a][lectureReStudy] = $rs[lectureReStudy];
				
				$leftDate = intval((strtotime($rs[lectureEnd])-strtotime($inputDate)) / 86400)+1; // 나머지 날짜값이 나옵니다.

				$adminapi[study][$a][leftDate] = "$leftDate";
				if($rs[nowChapter] == null) {
					$nowChapter = '0';
				} else {
					$nowChapter = $rs[nowChapter];
				}
				$adminapi[study][$a][nowChapter] = $nowChapter;
				$adminapi[study][$a][allChapter] = $rs[allChapter];
				$adminapi[study][$a][progress] = $rs[progress];
				$adminapi[study][$a][sourceType] = $rs[sourceType];
				$adminapi[study][$a][mobile] = $rs[mobile];

				$a++;
			}
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		
	@mysql_close();
?>