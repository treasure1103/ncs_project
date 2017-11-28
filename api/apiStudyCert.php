<?php
		header('Content-Type:application/json; charset=utf-8');
		// NCS 전용 페이지, 평가 후 바로 수료증 출력 가능한 api 20171121 진영하
?>
<? include '../lib/header.php'; ?>
<?
		$seq = $_GET['seq'];
		$print = $_GET['print'];
		$userID = $_SESSION['loginUserID'];
		$userLevel = $_SESSION['loginUserLevel'];

		if(!$userID) { // 세션이 없으면 접근 거부
			exit;
		}

		if($print == "Y") {
			if($userLevel <= 4) {
				$qUserID = "";
				$qUserID2 = "";
			} else {
				$qUserID = "AND userID='".$userID."' ";
				$qUserID2 = "AND A.userID='".$userID."' ";
			}
		} else {
			$qUserID = "AND userID='".$userID."' ";
			$qUserID2 = "AND A.userID='".$userID."' ";
		}

		if($seq != "") {
			$qSeq = " AND A.seq=".$seq;
		}

		$query = "SELECT A.*, B.birth, 
										 IF(ISNULL(B.userName),'입력오류',B.userName) AS userName, 
										 IF(ISNULL(C.userName),'배정오류',C.userName) AS tutorName, 
										 IF(ISNULL(E.contentsName),'입력오류',E.contentsName) AS contentsName, 
										 (SELECT chapter FROM nynProgress 
										 WHERE 1 ".$qUserID." AND contentsCode=A.contentsCode AND lectureOpenSeq=A.lectureOpenSeq 
										 ORDER BY chapter DESC LIMIT 1) AS nowChapter,
 										 E.chapter AS allChapter, E.previewImage, E.mobile, E.contentsTime, F.companyName, F.companyID, F.companyCode
							FROM nynStudy AS A
							LEFT OUTER
							JOIN nynMember AS B ON A.userID=B.userID
							LEFT OUTER
							JOIN nynMember AS C ON A.tutor=C.userID AND C.userLevel=7
							LEFT OUTER
							JOIN nynContents AS E ON A.contentsCode=E.contentsCode 
							LEFT OUTER
							JOIN nynCompany AS F ON A.companyCode=F.companyCode 
							WHERE 1 ".$qUserID2.$qSeq."
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
				$adminapi[study][$a][birth] = $rs[birth];
				$adminapi[study][$a][companyID] = $rs[companyID];
				$adminapi[study][$a][companyCode] = $rs[companyCode];
				$adminapi[study][$a][companyName] = $rs[companyName];
				$adminapi[study][$a][previewImageURL] = "/attach/contents/";
				$adminapi[study][$a][previewImage] = $rs[previewImage];
				$adminapi[study][$a][contentsCode] = $rs[contentsCode];
				$adminapi[study][$a][contentsName] = $rs[contentsName];
				$adminapi[study][$a][contentsTime] = $rs[contentsTime];
				$adminapi[study][$a][tutorID] = $rs[tutor];
				$adminapi[study][$a][tutorName] = $rs[tutorName];
				$adminapi[study][$a][lectureOpenSeq] = $rs[lectureOpenSeq];
				$adminapi[study][$a][serviceType] = $rs[serviceType];
				$adminapi[study][$a][lectureStart] = $rs[lectureStart];
				$adminapi[study][$a][lectureEnd] = $rs[lectureEnd];
				$adminapi[study][$a][lectureReStudy] = $rs[lectureReStudy];
				
				$leftDate = intval((strtotime($rs[lectureReStudy])-strtotime($inputDate)) / 86400); // 나머지 날짜값이 나옵니다.

				$adminapi[study][$a][leftDate] = "$leftDate";
				if($rs[nowChapter] == null) {
					$nowChapter = '0';
				} else {
					$nowChapter = $rs[nowChapter];
				}
				$adminapi[study][$a][nowChapter] = $nowChapter;
				$adminapi[study][$a][allChapter] = $rs[allChapter];
				$adminapi[study][$a][progress] = $rs[progress];
				$adminapi[study][$a][totalScore] = $rs[totalScore];
				$adminapi[study][$a][passOK] = $rs[passOK];
				$adminapi[study][$a][mobile] = $rs[mobile];
				$adminapi[study][$a][resultView] = $rs[resultView];

				if($seq != "") {
					$queryA = "SELECT certificate FROM nynStudyCenter WHERE companyID='".$rs[companyID]."'";
					$resultA = mysql_query($queryA);
					$rsA = mysql_fetch_array($resultA);
					$adminapi[study][$a][certificate] = $rsA[certificate];
				}

				$a++;
			}
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		
	@mysql_close();
?>