<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {

		if($_SESSION['loginUserLevel'] > 4) { // 관리자 외 수정불가
			echo "level error";
			exit;
		}

		$seq = $_POST['seq'];
		$contentsName = $_POST['contentsName'];
		$chapter = $_POST['chapter'];
		$sourceType = $_POST['sourceType'];
		$progressCheck = $_POST['progressCheck'];
		$commission = $_POST['commission'];
		$cp = $_POST['cp'];
		$contentsTime = $_POST['contentsTime'];
		$limited = $_POST['limited'];
		$price = $_POST['price'];
		$rPrice01 = $_POST['rPrice01'];
		$rPrice02 = $_POST['rPrice02'];
		$rPrice03 = $_POST['rPrice03'];
		$intro = $_POST['intro'];
		$target = $_POST['target'];
		$goal = $_POST['goal'];
		$bookIntro = $_POST['bookIntro'];
		$bookPrice = $_POST['bookPrice'];
		$professor = $_POST['professor'];
		$passCode = $_POST['passCode'];
		$passProgress = $_POST['passProgress'];
		$totalPassMid = $_POST['totalPassMid'];
		$totalPassTest = $_POST['totalPassTest'];
		$totalPassReport = $_POST['totalPassReport'];
		$passTest = $_POST['passTest'];
		$passReport = $_POST['passReport'];
		$passScore = $_POST['passScore'];
		$contentsPeriod = $_POST['contentsPeriod'];
		$contentsExpire = $_POST['contentsExpire'];
		$contentsGrade = $_POST['contentsGrade'];
		$sort01 = $_POST['sort01'];
		$sort02 = $_POST['sort02'];
		$mobile = $_POST['mobile'];
		$serviceType = $_POST['serviceType'];
		$testTime = $_POST['testTime'];
		$test01EA = $_POST['test01EA'];
		$test02EA = $_POST['test02EA'];
		$test03EA = $_POST['test03EA'];
		$test04EA = $_POST['test04EA'];
		$reportEA = $_POST['reportEA'];
		$test01Score = $_POST['test01Score']; //객관식
		$test02Score = $_POST['test02Score']; //단답형
		$test03Score = $_POST['test03Score']; //서술형
		$test04Score = $_POST['test04Score']; //진위형
		$reportScore = $_POST['reportScore']; //과제
		$midRate = $_POST['midRate'];
		$testRate = $_POST['testRate'];
		$reportRate = $_POST['reportRate'];
		$mid01EA = $_POST['mid01EA'];
		$mid02EA = $_POST['mid02EA'];
		$mid03EA = $_POST['mid03EA'];
		$mid04EA = $_POST['mid04EA'];
		$mid01Score = $_POST['mid01Score'];
		$mid02Score = $_POST['mid02Score'];
		$mid03Score = $_POST['mid03Score'];
		$mid04Score = $_POST['mid04Score'];
		$memo = $_POST['memo'];
		$enabled = $_POST['enabled'];
		$main = $_POST['main'];
		$midTestChapter = $_POST['midTestChapter'];
		$midTestProgress = $_POST['midTestProgress'];

		if($midTestChapter == '') {
			if($chapter == '' || $chapter == 0) {
				$midTestChapter = 0;
			} else {
				$midTestChapter = round($chapter/2);
			}
		}

		if($midTestProgress == '') {
			$midTestProgress = '50';
		}

		if($main=='Y') { // 대표과정 설정인 경우
			$queryM = "SELECT mainContents FROM nynContents WHERE mainContents='Y'";
			$resultM = mysql_query($queryM);
			$countM = mysql_num_rows($resultM);

			$queryC = "SELECT mainOrderBy FROM nynContents WHERE seq=".$seq;
			$resultC = mysql_query($queryC);
			$rsC = mysql_fetch_assoc($resultC);
			$mainOrderByC = $rsC[mainOrderBy];

			$queryC = "UPDATE nynContents SET mainContents='N', mainOrderBy=null WHERE mainOrderBy=1";
			$resultC = mysql_query($queryC);

			$queryC = "UPDATE nynContents SET mainOrderBy=mainOrderBy-1 WHERE seq <> ".$seq." AND mainContents='Y'";
			$resultC = mysql_query($queryC);

			$query = "UPDATE nynContents SET mainContents='Y', mainOrderBy='".$_mainContentsEA."' WHERE seq=".$seq;
			$result = mysql_query($query);

			if($result){
				echo '{"result" : "선정되었습니다. 사이트메인에 보여지게 되며 순번은 가장 마지막으로 위치합니다."}';
			} else {
				echo "error";
			}
			exit;
		}

		$chapter = $chapter ?: '0';
		$commission = $commission ?: '0';
		$contentsTime = $contentsTime ?: '0';
		$limited = $limited ?: '0';
		$price = $price ?: '0';
		$rPrice01 = $rPrice01 ?: '0';
		$rPrice02 = $rPrice02 ?: '0';
		$rPrice03 = $rPrice03 ?: '0';
		$bookPrice = $bookPrice ?: '0';
		$passProgress = $passProgress ?: '0';
		$totalPassMid = $totalPassMid ?: '0';
		$totalPassTest = $totalPassTest ?: '0';
		$totalPassReport = $totalPassReport ?: '0';
		$passTest = $passTest ?: '0';
		$passReport = $passReport ?: '0';
		$passScore = $passScore ?: '0';
		$testTime = $testTime ?: '0';
		$test01EA = $test01EA ?: '0';
		$test02EA = $test02EA ?: '0';
		$test03EA = $test03EA ?: '0';
		$test04EA = $test04EA ?: '0';
		$reportEA = $reportEA ?: '0';
		$test01Score = $test01Score ?: '0';
		$test02Score = $test02Score ?: '0';
		$test03Score = $test03Score ?: '0';
		$test04Score = $test04Score ?: '0';
		$reportScore = $reportScore ?: '0';
		$midRate = $midRate ?: '0';
		$testRate = $testRate ?: '0';
		$reportRate = $reportRate ?: '0';
		$mid01EA = $mid01EA ?: '0';
		$mid02EA = $mid02EA ?: '0';
		$mid03EA = $mid03EA ?: '0';
		$mid04EA = $mid04EA ?: '0';
		$mid01Score = $mid01Score ?: '0';
		$mid02Score = $mid02Score ?: '0';
		$mid03Score = $mid03Score ?: '0';
		$mid04Score = $mid04Score ?: '0';

		$uploadDate = date('i');
		$contentsURL = '/attach/contents/';
		$uploadDir = $_SERVER['DOCUMENT_ROOT'].$contentsURL;
		$previewImage = $_FILES['previewImage']["name"];
		$bookImage = $_FILES['bookImage']["name"];
		$attachFile = $_FILES['attachFile']["name"];

		if ($previewImage != "") { //첨부파일이 있을 경우 업로드
			$previewImageTmp = $_FILES['previewImage']['tmp_name']; // 업로드 파일 임시저장파일
			$previewImage = $_FILES['previewImage']['name']; // 업로드 파일명
			$previewImageSave = $uploadDir.$previewImage;

			$nameOK=1;
			$i=1;
			while($nameOK > 0){
				if(file_Exists($previewImageSave)) { // 같은 파일명이 존재한다면
					$previewImage = $uploadDate.$i."_".$_FILES['previewImage']["name"];
					//$previewImagePath = $contentsURL.$previewImage;
					$previewImageSave = $uploadDir.$previewImage; // 파일명 앞에 시간을 붙임.
					$i++;
				} else {
					$nameOK = 0;
				}
			}
			@move_uploaded_file($previewImageTmp, $previewImageSave);
			make_thumbnail($previewImageSave, 512, 384, $previewImageSave);
			$upPreviewImage = "previewImage='".$previewImage."', ";
		}

		if ($attachFile != "") { //첨부파일이 있을 경우 업로드
			$attachFileTmp = $_FILES['attachFile']['tmp_name']; // 업로드 파일 임시저장파일
			$attachFile = $_FILES['attachFile']['name']; // 업로드 파일명
			$attachFileSave = $uploadDir.$attachFile;

			$nameOK=1;
			$i=1;
			while($nameOK > 0){
				if(file_Exists($attachFileSave)) { // 같은 파일명이 존재한다면
					$attachFile = $uploadDate.$i."_".$_FILES['attachFile']["name"];
					//$attachFilePath = $contentsURL.$bookImage;
					$attachFileSave = $uploadDir.$attachFile; // 파일명 앞에 시간을 붙임.
					$i++;
				} else {
					$nameOK = 0;
				}
			}

			@move_uploaded_file($attachFileTmp, $attachFileSave);
			$upAttachFile = "attachFile='".$attachFile."', ";
		}

		$bookURL = '/attach/book/';
		$uploadDir = $_SERVER['DOCUMENT_ROOT'].$bookURL;

		if ($bookImage != "") { //첨부파일이 있을 경우 업로드
			$bookImageTmp = $_FILES['bookImage']['tmp_name']; // 업로드 파일 임시저장파일
			$bookImage = $_FILES['bookImage']['name']; // 업로드 파일명
			$newPath02 = $uploadDir.$bookImage;

			$nameOK=1;
			$i=1;
			while($nameOK > 0){
				if(file_Exists($newPath02)) { // 같은 파일명이 존재한다면
					$bookImage = $uploadDate.$i."_".$_FILES['bookImage']["name"];
					$bookImagePath = $bookURL.$bookImage;
					$bookImageSave = $uploadDir.$bookImage; // 파일명 앞에 시간을 붙임.
					$i++;
				} else {
					$nameOK = 0;
				}
			}

			@move_uploaded_file($bookImageTmp, $bookImageSave);
			$upBookImage = "bookImage='".$bookImage."', ";
		}

		if($contentsPeriod) {
			$contentsPeriodQ = "contentsPeriod = '".$contentsPeriod."',";
		}
		if($contentsExpire) {
			$contentsExpireQ = "contentsExpire = '".$contentsExpire."',";
		}
		if($passCode) {
			$passCodeQ = "passCode = '".$passCode."',";
		}
		if($sort01) {
			$sort01Q = "sort01 = '".$sort01."',";
		}
		if($sort02) {
			$sort02Q = "sort02 = '".$sort02."',";
		}

			$queryQ =  "contentsName = '".$contentsName."',
									chapter = '".$chapter."',
									sourceType = '".$sourceType."',
									progressCheck = '".$progressCheck."',
									commission = '".$commission."',
									cp = '".$cp."',
									contentsTime = '".$contentsTime."',
									limited = '".$limited."',
									price = '".$price."',
									rPrice01 = '".$rPrice01."',
									rPrice02 = '".$rPrice02."',
									rPrice03 = '".$rPrice03."',
									intro = '".addslashes(trim($intro))."',
									target = '".addslashes(trim($target))."',
									goal = '".addslashes(trim($goal))."',
									professor = '".$professor."',
									passProgress = '".$passProgress."',
									passTest = '".$passTest."',
									passReport = '".$passReport."',
									totalPassMid = '".$totalPassMid."',
									totalPassTest = '".$totalPassTest."',
									totalPassReport = '".$totalPassReport."', 
									passScore = '".$passScore."',
									".$contentsPeriodQ.$contentsExpireQ.$passCodeQ.$sort01Q.$sort02Q."
									contentsGrade = '".$contentsGrade."',
									mobile = '".$mobile."',
									serviceType = '".$serviceType."',
									testTime = '".$testTime."',
									test01EA = '".$test01EA."',
									test02EA = '".$test02EA."',
									test03EA = '".$test03EA."',
									test04EA = '".$test04EA."',
									reportEA = '".$reportEA."',
									test01Score = '".$test01Score."',
									test02Score = '".$test02Score."',
									test03Score = '".$test03Score."',
									test04Score = '".$test04Score."',
									midRate = '".$midRate."',
									testRate = '".$testRate."',
									reportRate = '".$reportRate."',	
									mid01EA = '".$mid01EA."',
									mid02EA = '".$mid02EA."',
									mid03EA = '".$mid03EA."',
									mid04EA = '".$mid04EA."',
									mid01Score = '".$mid01Score."',
									mid02Score = '".$mid02Score."',
									mid03Score = '".$mid03Score."',
									mid04Score = '".$mid04Score."',
									reportScore = '".$reportScore."',
									bookIntro = '".addslashes(trim($bookIntro))."',
									bookPrice = '".$bookPrice."',
									memo = '".$memo."',
									midTestChapter = '".$midTestChapter."',
									midTestProgress = '".$midTestProgress."',
									enabled = '".$enabled."'";

		if($seq == "") { // 콘텐츠 정보 등록
			$contentsCodeY = 1;
			while($contentsCodeY > 0){ // contentsCode 6자리 임의 생성 후 중복 검사
				$contentsCode = generateRenStr(6,C);
				$codeCheck = "SELECT contentsCode FROM nynContents WHERE contentsCode = '".$contentsCode."'";
				$resultCheck = mysql_query($codeCheck);
				$contentsCodeY = mysql_num_rows($resultCheck);
			}
			
			if($contentsName) {
				$query = "INSERT INTO nynContents SET contentsCode = '".$contentsCode."', ".$upPreviewImage.$upAttachFile.$upBookImage.$queryQ;
				$result = mysql_query($query);
				$seq = mysql_insert_id();
			}

		} else { // 콘텐츠 정보 수정
			$query = "UPDATE nynContents SET ".$upPreviewImage.$upAttachFile.$upBookImage.$queryQ.", lastUpdate='".$inputDate."' WHERE seq = ".$seq;
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

			if($_SESSION['loginUserLevel'] > 4) { // 관리자 외 삭제불가
				echo "level error";
				exit;
			}

			$seq = $_DEL['seq'];

			$query = "DELETE FROM nynContents WHERE seq=".$seq;
			$result = mysql_query($query);

			if($result){
				echo "success";
			} else {
				echo "error";
			}
			exit;

	} else if($method == "GET") { // 콘텐츠 정보 json 출력
			$list = $_GET['list'];
			$page = $_GET['page'];
			$seq = $_GET['seq'];
			$contentsCode = $_GET['contentsCode'];
			$contentsName = $_GET['contentsName'];
			$serviceType = $_GET['serviceType'];
			$sourceType = $_GET['sourceType'];
			$progressCheck = $_GET['progressCheck'];
			$mobile = $_GET['mobile'];
			$sort01 = $_GET['sort01'];
			$sort02 = $_GET['sort02'];
			$previewImageURL = '/attach/contents/';
			$bookImageURL = '/attach/book/';
			$companyID = $_GET['companyID'];
			$enabled = $_GET['enabled'];
			$enabled2 = $_GET['enabled2'];
			$main = $_GET['main'];

			if(!$_SESSION['loginUserLevel']){
				$userLevelCheck = 10;
			} else {
				$userLevelCheck = $_SESSION['loginUserLevel'];
			}

			if($list == "") {
				$list = 10;
			}
			if($page == "") {
				$page = 1;
			}

			switch($searchType){
				case "contentsCode":
					$contentsCode = $searchValue;
				break;

				case "contentsName":
					$contentsName = $searchValue;
				break;

				case "cp":
					$cp = $searchValue;
				break;
			}

			if($sortType == "") {
				$sortType = "A.seq";
			}
			if($sortValue == "") {
				$sortValue = "DESC";
			}

			if($seq != "") {
				$qSeq = " AND A.seq='".$seq."'";
			}
			if($contentsCode != "") {
				$qcontentsCode = " AND A.contentsCode LIKE '%".$contentsCode."%'";
			}
			if($contentsName != "") {
				$qcontentsName = " AND A.contentsName LIKE '%".$contentsName."%'";
			}
			if($cp != "") {
				$qCP = "  AND A.cp LIKE '%".$cp."%'";
			}
			if($serviceType != "") {
				$qServiceType = " AND A.serviceType='".$serviceType."'";
			}
			if($sourceType != "") {
				$qSourceType = " AND A.sourceType='".$sourceType."'";
			}
			if($progressCheck != "") {
				$qProgressCheck = " AND A.progressCheck='".$progressCheck."'";
			}
			if($mobile != "") {
				$qMobile = " AND A.mobile='".$mobile."'";
			}
			if($sort01 != "") {
				$qSort01 = " AND A.sort01='".$sort01."'";
			}
			if($sort02 != "") {
				$qSort02 = " AND A.sort02='".$sort02."'";
			}
			if($enabled != "") {
				$qEnabled = " AND A.enabled='".$enabled."'";
			}
			if($enabled2 != "") { // B2C용 : 서브카테고리의 사용여부가 'N'이면 콘텐츠도 노출안됨
				$qEnabled2 = " AND C.enabled='".$enabled2."'";
			}
			if($main != "") {
				$qMain = " AND A.mainContents='".$main."'";
			}

			$qSearch = $qSeq.$qcontentsCode.$qcontentsName.$qCP.$qServiceType.$qSourceType.$qProgressCheck.$qMobile.$qSort01.$qSort02.$qEnabled.$qMain;

			if($companyID != "") {
				$sql = "SELECT contentsMapping FROM nynStudyCenter where companyID='".$companyID."'";
				$resSQL = mysql_query($sql);
				$rsSQL = mysql_fetch_array($resSQL);
				$contentsMapping = $rsSQL['contentsMapping'];
			} else {
				$contentsMapping = 'N';
			}

			if($userLevelCheck == "7") {  // 교강사인 경우 배정된 과정만 열람
				$que = "SELECT A.*, B.value02 AS sort01Name, C.value02 AS sort02Name
								FROM nynContents AS A
								LEFT OUTER
								JOIN nynCategory AS B ON A.sort01 <> '' AND A.sort01=B.value01
								LEFT OUTER
								JOIN nynCategory AS C ON A.sort02 <> '' AND A.sort02=C.value01
								LEFT OUTER
								JOIN (
								SELECT DISTINCT(contentsCode)
								FROM nynStudy
								WHERE tutor='".$_SESSION['loginUserID']."') AS D ON A.contentsCode=D.contentsCode
								WHERE A.contentsCode=D.contentsCode ".$qSearch;
				$res = mysql_query($que);
				$allPost = mysql_num_rows($res);
				$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
				$sqlLimit = ' limit '.$currentLimit.', '.$list; //limit sql 구문

				$query = "SELECT A.*, B.value02 AS sort01Name, C.value02 AS sort02Name
									FROM nynContents AS A
									LEFT OUTER
									JOIN nynCategory AS B ON A.sort01 <> '' AND A.sort01=B.value01
									LEFT OUTER
									JOIN nynCategory AS C ON A.sort02 <> '' AND A.sort02=C.value01
									LEFT OUTER
									JOIN (
									SELECT DISTINCT(contentsCode)
									FROM nynStudy
									WHERE tutor='".$_SESSION['loginUserID']."') AS D ON A.contentsCode=D.contentsCode
									WHERE A.contentsCode=D.contentsCode ".$qSearch." ORDER BY ".$sortType." ".$sortValue.$sqlLimit;
				$result = mysql_query($query);
				$count = mysql_num_rows($result);
				$a = 0;
				$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			} else if($contentsMapping == "Y") {	// 사이버교육센터 콘텐츠 매핑 사용한 경우
				$que = "SELECT A.*, B.value02 AS sort01Name, C.value02 AS sort02Name
								FROM nynContents AS A
								LEFT OUTER
								JOIN nynCategory AS B ON A.sort01 <> '' AND A.sort01=B.value01
								LEFT OUTER
								JOIN nynCategory AS C ON A.sort02 <> '' AND A.sort02=C.value01
								RIGHT OUTER
								JOIN nynContentsMapping AS D ON A.contentsCode=D.contentsCode AND D.companyID='".$companyID."'
								WHERE 1 ".$qSearch;
				$res = mysql_query($que);
				$allPost = mysql_num_rows($res);
				$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
				$sqlLimit = ' LIMIT '.$currentLimit.', '.$list; //limit sql 구문

				$query = "SELECT A.*, B.value02 AS sort01Name, C.value02 AS sort02Name
									FROM nynContents AS A
									LEFT OUTER
									JOIN nynCategory AS B ON A.sort01 <> '' AND A.sort01=B.value01
									LEFT OUTER
									JOIN nynCategory AS C ON A.sort02 <> '' AND A.sort02=C.value01
									RIGHT OUTER
									JOIN nynContentsMapping AS D ON A.contentsCode=D.contentsCode AND D.companyID='".$companyID."'
									WHERE 1 ".$qSearch." ORDER BY ".$sortType." ".$sortValue.$sqlLimit;
				$result = mysql_query($query);
				$count = mysql_num_rows($result);
				$a = 0;
				$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			} else {
					if($userLevelCheck < 8) { //관리자인 경우 nynStudy join (심사용 아이디 생성여부 확인을 위함)
							$que = "SELECT A.*, B.value02 AS sort01Name, C.value02 AS sort02Name, D.lectureStart
											FROM nynContents AS A
											LEFT OUTER
											JOIN nynCategory AS B ON A.sort01 <> '' AND A.sort01=B.value01
											LEFT OUTER
											JOIN nynCategory AS C ON A.sort02 <> '' AND A.sort02=C.value01
											LEFT OUTER
											JOIN nynStudy AS D ON D.serviceType='9' AND D.userID= CONCAT(A.contentsCode,'1')
											WHERE 1 <> 0 ".$qSearch." ORDER BY ".$sortType." ".$sortValue;
							$res = mysql_query($que);
							$allPost = mysql_num_rows($res);
							$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
							$sqlLimit = ' limit '.$currentLimit.', '.$list; //limit sql 구문

							$query = "SELECT A.*, B.value02 AS sort01Name, C.value02 AS sort02Name, D.lectureStart
												FROM nynContents AS A
												LEFT OUTER
												JOIN nynCategory AS B ON A.sort01 <> '' AND A.sort01=B.value01
												LEFT OUTER
												JOIN nynCategory AS C ON A.sort02 <> '' AND A.sort02=C.value01
												LEFT OUTER
												JOIN nynStudy AS D ON D.serviceType='9' AND D.userID= CONCAT(A.contentsCode,'1')
												WHERE 1 ".$qSearch." ORDER BY ".$sortType." ".$sortValue.$sqlLimit;
							$result = mysql_query($query);
							$count = mysql_num_rows($result);
							$a = 0;
							$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

					} else {  // 일반 유저인 경우
							$que = "SELECT A.*, B.value02 AS sort01Name, C.value02 AS sort02Name
											FROM nynContents AS A
											LEFT OUTER
											JOIN nynCategory AS B ON A.sort01 <> '' AND A.sort01=B.value01
											LEFT OUTER
											JOIN nynCategory AS C ON A.sort02 <> '' AND A.sort02=C.value01
											WHERE 1 ".$qSearch.$qEnabled2;
							$res = mysql_query($que);
							$allPost = mysql_num_rows($res);
							$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
							$sqlLimit = ' limit '.$currentLimit.', '.$list; //limit sql 구문

							$query = "SELECT A.*, B.value02 AS sort01Name, C.value02 AS sort02Name
												FROM nynContents AS A
												LEFT OUTER
												JOIN nynCategory AS B ON A.sort01 <> '' AND A.sort01=B.value01
												LEFT OUTER
												JOIN nynCategory AS C ON A.sort02 <> '' AND A.sort02=C.value01
												WHERE 1 ".$qSearch.$qEnabled2." ORDER BY ".$sortType." ".$sortValue.$sqlLimit;
							$result = mysql_query($query);
							$count = mysql_num_rows($result);
							$a = 0;
							$adminapi = array(); //DB 값이 없는 경우 배열선언 부분
					}

			}

			$adminapi[totalCount] = "$allPost"; //총 개시물 수
			$adminapi[previewImageURL] = $previewImageURL;
			$adminapi[bookImageURL] = $bookImageURL;
			$adminapi[nowTime] = $inputDate;

			while($rs = mysql_fetch_array($result)) {
				$querySC = "SELECT COUNT(*) AS studyCount
										FROM nynStudy AS A
										LEFT OUTER
										JOIN nynContents AS B ON A.contentsCode=B.contentsCode
										WHERE A.serviceType=1 AND A.contentsCode='".$rs[contentsCode]."'";
				$resultSC = mysql_query($querySC);
				$rsSC = mysql_fetch_array($resultSC);

				$adminapi[contents][$a][seq] = $rs[seq];
				$adminapi[contents][$a][studyCount] = $rsSC[studyCount];
				$adminapi[contents][$a][contentsCode] = $rs[contentsCode];
				$adminapi[contents][$a][contentsName] = $rs[contentsName];
				$adminapi[contents][$a][previewImage] = $rs[previewImage];
				$adminapi[contents][$a][chapter] = $rs[chapter];
				$adminapi[contents][$a][contentsTime] = $rs[contentsTime];
				$adminapi[contents][$a][limited] = $rs[limited];
				$adminapi[contents][$a][price] = $rs[price];
				$adminapi[contents][$a][rPrice01] = $rs[rPrice01];
				$adminapi[contents][$a][rPrice02] = $rs[rPrice02];
				$adminapi[contents][$a][rPrice03] = $rs[rPrice03];
				$adminapi[contents][$a][intro] = stripslashes($rs[intro]);
				$adminapi[contents][$a][target] = stripslashes($rs[target]);
				$adminapi[contents][$a][target02] = stripslashes($rs[target02]);
				$adminapi[contents][$a][goal] = stripslashes($rs[goal]);
				$adminapi[contents][$a][professor] = $rs[professor];
				$adminapi[contents][$a][passCode] = $rs[passCode];
				$adminapi[contents][$a][passProgress] = $rs[passProgress];
				$adminapi[contents][$a][passTest] = $rs[passTest];
				$adminapi[contents][$a][passReport] = $rs[passReport];
				$adminapi[contents][$a][totalPassMid] = $rs[totalPassMid];
				$adminapi[contents][$a][totalPassTest] = $rs[totalPassTest];
				$adminapi[contents][$a][totalPassReport] = $rs[totalPassReport];
				$adminapi[contents][$a][passScore] = $rs[passScore];
				$adminapi[contents][$a][midRate] = $rs[midRate];
				$adminapi[contents][$a][testRate] = $rs[testRate];
				$adminapi[contents][$a][reportRate] = $rs[reportRate];
				$adminapi[contents][$a][contentsPeriod] = $rs[contentsPeriod];
				$adminapi[contents][$a][contentsExpire] = $rs[contentsExpire];
				$adminapi[contents][$a][contentsGrade] = $rs[contentsGrade];
				$adminapi[contents][$a][sort01] = $rs[sort01];
				$adminapi[contents][$a][sort02] = $rs[sort02];
				$adminapi[contents][$a][sort01Name] = $rs[sort01Name];
				$adminapi[contents][$a][sort02Name] = $rs[sort02Name];
				$adminapi[contents][$a][bookImage] = $rs[bookImage];
				$adminapi[contents][$a][bookIntro] = $rs[bookIntro];
				$adminapi[contents][$a][bookPrice] = $rs[bookPrice];
				$adminapi[contents][$a][mobile] = $rs[mobile];
				$adminapi[contents][$a][serviceType] = $rs[serviceType];
				$adminapi[contents][$a][sourceType] = $rs[sourceType];
				$adminapi[contents][$a][progressCheck] = $rs[progressCheck];
				$adminapi[contents][$a][cp] = $rs[cp];
				$adminapi[contents][$a][commission] = $rs[commission];
				$adminapi[contents][$a][testTime] = $rs[testTime];
				$adminapi[contents][$a][mid01EA] = $rs[mid01EA];
				$adminapi[contents][$a][mid02EA] = $rs[mid02EA];
				$adminapi[contents][$a][mid03EA] = $rs[mid03EA];
				$adminapi[contents][$a][mid04EA] = $rs[mid04EA];
				$adminapi[contents][$a][mid01Score] = $rs[mid01Score];
				$adminapi[contents][$a][mid02Score] = $rs[mid02Score];
				$adminapi[contents][$a][mid03Score] = $rs[mid03Score];
				$adminapi[contents][$a][mid04Score] = $rs[mid04Score];
				$adminapi[contents][$a][test01EA] = $rs[test01EA];
				$adminapi[contents][$a][test02EA] = $rs[test02EA];
				$adminapi[contents][$a][test03EA] = $rs[test03EA];
				$adminapi[contents][$a][test04EA] = $rs[test04EA];
				$adminapi[contents][$a][reportEA] = $rs[reportEA];
				$adminapi[contents][$a][test01Score] = $rs[test01Score];
				$adminapi[contents][$a][test02Score] = $rs[test02Score];
				$adminapi[contents][$a][test03Score] = $rs[test03Score];
				$adminapi[contents][$a][test04Score] = $rs[test04Score];
				$adminapi[contents][$a][reportScore] = $rs[reportScore];
				$testAvailable = ROUND($rs[chapter]*(4/5));
				$adminapi[contents][$a][testAvailable] = "$testAvailable"; //최종평가 응시 가능 차시수
				$adminapi[contents][$a][lectureStart] = $rs[lectureStart]; //관리자 심사 아이디 체크용
				$adminapi[contents][$a][attachFile] = $rs[attachFile];
				$adminapi[contents][$a][mainContents] = $rs[mainContents];
				$adminapi[contents][$a][mainOrderBy] = $rs[mainOrderBy];
				$adminapi[contents][$a][memo] = $rs[memo];
				$adminapi[contents][$a][midTestChapter] = $rs[midTestChapter];
				$adminapi[contents][$a][midTestProgress] = $rs[midTestProgress];
				$adminapi[contents][$a][enabled] = $rs[enabled];
				$a++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
	mysql_close();
?>