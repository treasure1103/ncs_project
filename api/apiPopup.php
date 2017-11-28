<?php
  if (preg_match('/(?i)msie [6-9]/', $_SERVER['HTTP_USER_AGENT'])) {
		header('Content-Type:text/json; charset=utf-8');
	} else {
		header('Content-Type:application/json; charset=utf-8');
	}
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$seq = $_POST['seq'];
		$popupType = $_POST['popupType'];
		$width = $_POST['width'];
		$height = $_POST['height'];
		$subject = $_POST['subject'];
		$startDate = $_POST['startDate'];
		$endDate = $_POST['endDate'];
		$popupURL = $_POST['popupURL'];
		$popupTarget = $_POST['popupTarget'];
		$enabled = $_POST['enabled'];
		$delFile01 = $_POST['delFile01'];

		if($_SESSION['loginUserLevel'] > 4) { // 관리자만 접근 가능
			echo "error";
			exit;
		}

		$imageURL = "/attach/popup/";
		$uploadDate = date('ymdHis')."_";
		$uploadDir = $_SERVER['DOCUMENT_ROOT'].$imageURL;
		$attachFileName = $_FILES['attachFile']["name"];

/*
		if ($popupURL != "") {
			$popupURL = "popupURL='".$popupURL."', ";
			$popupTarget = "popupTarget = '".$popupTarget."', ";
		} else {
			$popupTarget = "popupTarget = '".$popupTarget."', ";
		}
*/

		if ($delFile01 == "Y") { // 첨부 파일 삭제 요청
				$upAttachFile = "attachFile = null, ";

				//서버에서 파일 삭제
				$query01 = "SELECT attachFile FROM nynPopup WHERE seq=".$seq;
				$result01 = mysql_query($query01);
				$dImage01 = mysql_result($result01,0,'attachFile');
				
				$delS01 = $_SERVER['DOCUMENT_ROOT'].$imageURL.$dImage01;
				UNLINK($delS01);
		}

			if ($attachFileName != "") { //첨부파일이 있을 경우 업로드
				$attachFileTmp = $_FILES['attachFile']['tmp_name']; // 업로드 파일 임시저장파일
				$newPath = $uploadDir.$attachFileName;

					if(file_Exists($newPath)) { // 같은 파일명이 존재한다면
						$attachFileName = $uploadDate.$attachFile;
						$newPath = $uploadDir.$attachFileName; // 파일명 앞에 시간을 붙임.
					}

				@move_uploaded_file($attachFileTmp, $newPath);
				$upAttachFile = "attachFile = '".$attachFileName."', ";
			}

			$queryQ =  "popupType = '".$popupType."',
									width = '".$width."',
									height = '".$height."',
									subject = '".$subject."',
									startDate = '".$startDate."',
									endDate = '".$endDate."',
									popupURL='".$popupURL."',
									popupTarget = '".$popupTarget."', 
									enabled = '".$enabled."'";

		if($seq == "") { // 팝업 등록
			$query = "INSERT INTO nynPopup SET ".$upAttachFile.$queryQ;
			$result = mysql_query($query);

			$queNum="SELECT MAX(seq) AS SEQ FROM nynPopup";
			$resultNum = mysql_query($queNum);
			$rsNum = mysql_fetch_assoc($resultNum);
			$seq = $rsNum[seq];

		} else { // 콘텐츠 정보 수정
			$query = "UPDATE nynPopup SET ".$upAttachFile.$queryQ." WHERE seq = ".$seq;
			$result = mysql_query($query);
		}

			if($result){
				echo $seq;
			} else {
				echo "error";
			}
			exit;

	} else if($method == "DELETE") { // 콘텐츠 정보 삭제
			parse_str(file_get_contents("php://input"), $_DEL);
			$seq = $_DEL['seq'];

			if($_SESSION['loginUserLevel'] > 4) { // 관리자만 접근 가능
				echo "error";
				exit;
			}

			$query = "DELETE FROM nynPopup WHERE seq=".$seq;
			$result = mysql_query($query);

			if($result){
				echo $seq;
			} else {
				echo "error";
			}
			exit;

	} else if($method == "GET") { // 콘텐츠 정보 json 출력
			$seq = $_GET['seq'];
			$imageURL = "/attach/popup/";

			switch($searchType){
				case "subject":
					$subject = $searchValue;
				break;

				case "popupType":
					$popupType = $searchValue;
				break;
			}

			if($sortType == "") {
				$sortType = "seq";
			}
			if($sortValue == "") {
				$sortValue = "DESC";
			}

			if($list == "") {
				$list = 10;
			}
			if($page == "") {
				$page = 1;
			}
			if($seq != "") {
				$qSeq = " AND seq='".$seq."'";
			}
			if($subject != "") {
				$qSubject = " AND subject LIKE '%".$subject."%'";
			}
			if($popupType != "") {
				$qPopupType = " AND popupType LIKE '%".$popupType."%'";
			}

			$qSearch = $qSeq.$qSubject.$qPopupType;

			$que = "SELECT * FROM nynPopup WHERE seq <> 0 ".$qSearch;
			$res = mysql_query($que);
			$allPost = mysql_num_rows($res);
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' limit '.$currentLimit.', '.$list; //limit sql 구문

			$query = "SELECT * FROM nynPopup WHERE seq <> 0 ".$qSearch." ORDER BY ".$sortType." ".$sortValue.$sqlLimit;
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			$adminapi[totalCount] = "$allPost"; //총 개시물 수
			$adminapi[imageURL] = $imageURL; //총 개시물 수

			while($rs = mysql_fetch_array($result)) {
				$adminapi[popup][$a][seq] = $rs[seq];
				$adminapi[popup][$a][popupType] = $rs[popupType];
				$adminapi[popup][$a][width] = $rs[width];
				$adminapi[popup][$a][height] = $rs[height];
				$adminapi[popup][$a][subject] = $rs[subject];
				$adminapi[popup][$a][startDate] = $rs[startDate];
				$adminapi[popup][$a][endDate] = $rs[endDate];
				$adminapi[popup][$a][popupURL] = $rs[popupURL];
				$adminapi[popup][$a][attachFile] = $rs[attachFile];
				$adminapi[popup][$a][popupTarget] = $rs[popupTarget];
				$adminapi[popup][$a][enabled] = $rs[enabled];
				$a++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
?>