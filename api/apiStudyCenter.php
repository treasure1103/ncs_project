<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$seq = $_POST['seq'];
		$companyID = $_POST['companyID'];
		$studyLogo = $_POST['studyLogo'];
		$studyColor = $_POST['studyColor'];
		$studyMainImg = $_POST['studyMainImg'];
		$studyFooterImg = $_POST['studyFooterImg'];
		//$studyLecture = $_POST['studyLecture'];
		$studyRequestStart = $_POST['studyRequestStart'];
		$studyRequestEnd = $_POST['studyRequestEnd'];
		$studyRequestLoop = $_POST['studyRequestLoop'];
		$studyStart = $_POST['studyStart'];
		$studyEnd = $_POST['studyEnd'];
		$studyLoop = $_POST['studyLoop'];
		$noticeSetting = $_POST['noticeSetting'];
		$mainContents = $_POST['mainContents'];
		//$mainContents = explode('/',$mainContents);
		$contentsMapping = $_POST['contentsMapping'];

		if(!$studyRequestStart){
			$requestS = "studyRequestStart = NULL,";
		} else {
			$requestS = "studyRequestStart = '".$studyRequestStart."',";
		}
		if(!$studyRequestEnd){
			$requestE = "studyRequestEnd = NULL,";
		} else {
			$requestE = "studyRequestEnd = '".$studyRequestEnd."',";
		}
		if(!$studyStart){
			$qStudyStart = "studyStart = NULL,";
		} else {
			$qStudyStart = "studyStart = '".$studyStart."',";
		}
		if(!$studyEnd){
			$qStudyEnd = "studyEnd = NULL,";
		} else {
			$qStudyEnd = "studyEnd = '".$studyEnd."',";
		}
		if($noticeSetting) {
			$qNoticeSetting = "noticeSetting = '".$noticeSetting."',";
		}
		if($mainContents) {
			$qContents = "mainContents = '".$mainContents."',";
		}
		if($contentsMapping){
			$qMapping = "contentsMapping = '".$contentsMapping."'," ;
		}
		$queryQ = $requestS.$requestE.$qStudyStart.$qStudyEnd.$qContents.$qNoticeSetting.$qMapping."
							studyColor='".$studyColor."'";
		
		$attachURL = "/attach/studyCenter/";
		$uploadDir = $_SERVER['DOCUMENT_ROOT'].$attachURL;
		$uploadDate = date('ymdHis')."_";
		$attachFile01Name = $_FILES['studyLogo']["name"];
		$attachFile02Name = $_FILES['studyMainImg']["name"];
		$attachFile03Name = $_FILES['studyFooterImg']["name"];

		if($attachFile01Name != "") { //로고가 있을 경우 업로드
				$attachFile01Name2 = "logo_".$companyID.".png"; // 파일명은 고정으로 저장한다.
				$attachFile01Tmp = $_FILES['studyLogo']['tmp_name']; // 업로드 파일 임시저장파일
				$attachFile01Save = $uploadDir.$attachFile01Name2;
				@move_uploaded_file($attachFile01Tmp, $attachFile01Save);
				$upAttachFile01 = "studyLogo='Y', ";
			}

		if($attachFile02Name != "") { //메인이미지가 있을 경우 업로드
				$attachFile02Name2 = "image_".$companyID.".jpg"; // 파일명은 고정으로 저장한다.
				$attachFile02Tmp = $_FILES['studyMainImg']['tmp_name']; // 업로드 파일 임시저장파일
				$attachFile02Save = $uploadDir.$attachFile02Name2;
				@move_uploaded_file($attachFile02Tmp, $attachFile02Save);
				$upAttachFile02 = "studyMainImg='Y',	";
		}

		if($attachFile03Name != "") { //footer 이미지가 있을 경우 업로드
				$attachFile03Name2 = "footer_".$companyID.".jpg"; // 파일명은 고정으로 저장한다.
				$attachFile03Tmp = $_FILES['studyFooterImg']['tmp_name']; // 업로드 파일 임시저장파일
				$attachFile03Save = $uploadDir.$attachFile03Name2;
				@move_uploaded_file($attachFile03Tmp, $attachFile03Save);
				$upAttachFile03 = "studyFooterImg='Y',	";
		}

		if($seq == "") { // 정보 등록
			$query = "INSERT INTO nynStudyCenter SET inputDate='".$inputDate."', ".$upAttachFile01.$upAttachFile02.$upAttachFile03.$queryQ;

			$queNum="SELECT MAX(seq) AS seq FROM nynStudyCenter";
			$resultNum = mysql_query($queNum);
			$rsNum = mysql_fetch_assoc($resultNum);
			$seq = $rsNum[seq]+1;

		} else { // 정보 수정
			$query = "UPDATE nynStudyCenter SET ".$upAttachFile01.$upAttachFile02.$upAttachFile03.$queryQ." WHERE seq='".$seq."'";
		}			
			$result = mysql_query($query);

			if($result){
				echo $seq;
			} else {
				echo "error";
			}
			exit;

	} else if($method == "DELETE") { // 정보 삭제
			parse_str(file_get_contents("php://input"), $_DEL);
			$seq = $_DEL['seq'];
			$query = "DELETE FROM nynStudyCenter WHERE seq=".$seq;
			$result = mysql_query($query);

			if($result){
				echo "success";
			} else {
				echo "error";
			}
			exit;

	} else if($method == "GET") { // 정보 불러옴
			$seq = $_GET['seq'];
			$companyID = $_GET['companyID'];
			$attachURL = '/attach/studyCenter/';

			if($seq != "") {
				$qSeq = " and seq='".$seq."'";
			}

			$qSearch = $qSeq;

			$query = "SELECT A.*, B.companyName, B.marketerID, B.phone01, B.phone02, B.phone03, B.fax01, B.fax02, B.fax03
								FROM nynStudyCenter A 
								LEFT OUTER 
								JOIN nynCompany B 
								ON A.companyID=B.companyID 
								WHERE A.companyID='".$companyID."' 
								ORDER BY A.seq DESC ".$sqlLimit;
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			$a = 0;
			$adminapi = array();

			$adminapi['totalCount'] = "$count";
			$adminapi['attachURL'] = $attachURL;			
			
			while($rs = mysql_fetch_array($result)) {
				$adminapi['studyCenter'][$a]['seq'] = $rs['seq'];
				$adminapi['studyCenter'][$a]['attachURL'] = $attachURL;
				$adminapi['studyCenter'][$a]['companyID'] = $rs['companyID'];
				$adminapi['studyCenter'][$a]['companyName'] = $rs['companyName'];
				$adminapi['studyCenter'][$a]['phone'] = $rs['phone01']."-".$rs['phone02']."-".$rs['phone03'];
				$adminapi['studyCenter'][$a]['fax'] = $rs['fax01']."-".$rs['fax02']."-".$rs['fax03'];
				if($rs['studyLogo'] == "Y"){
					$studyLogo = "logo_".$rs['companyID'].".png";
				} else {
					$studyLogo = "N";
				}
				if($rs['studyMainImg'] == "Y"){
					$studyMainImg = "image_".$rs['companyID'].".jpg";
				} else {
					$studyMainImg = "N";
				}
				if($rs['studyFooterImg'] == "Y"){
					$studyFooterImg = "footer_".$rs['companyID'].".jpg";
				} else {
					$studyFooterImg = "N";
				}
				$adminapi['studyCenter'][$a]['studyLogo'] = $studyLogo;
				$adminapi['studyCenter'][$a]['studyColor'] = $rs['studyColor'];
				$adminapi['studyCenter'][$a]['studyMainImg'] = $studyMainImg;
				$adminapi['studyCenter'][$a]['studyFooterImg'] = $studyFooterImg;
				$adminapi['studyCenter'][$a]['studyLecture'] = $rs['studyLecture'];
				
				if($rs['studyRequestStart'] == null || $rs['studyRequestEnd'] == null ){
					$studyRequestStart= "";
					$studyRequestEnd= "";
				} else {
					$studyRequestStart = $rs['studyRequestStart'];
					$studyRequestEnd = $rs['studyRequestEnd'];
				}

				$adminapi['studyCenter'][$a]['studyRequestStart'] = $studyRequestStart;
				$adminapi['studyCenter'][$a]['studyRequestEnd'] = $studyRequestEnd;
				$adminapi['studyCenter'][$a]['studyRequestLoop'] = $rs['studyRequestLoop'];

				if($rs['studyStart'] != null || $rs['studyEnd'] != null ){
					$studyStart = $rs['studyStart'];
					$studyEnd = $rs['studyEnd'];
				} else {					
					$studyStart= "";
					$studyEnd= "";
				}

				$adminapi['studyCenter'][$a]['studyStart'] = $studyStart;
				$adminapi['studyCenter'][$a]['studyEnd'] = $studyEnd;
				$adminapi['studyCenter'][$a]['studyLoop'] = $rs['studyLoop'];
				$adminapi['studyCenter'][$a]['marketerID'] = $rs['marketerID'];
				$adminapi['studyCenter'][$a]['noticeSetting'] = $rs['noticeSetting'];
				$adminapi['studyCenter'][$a]['contentsMapping'] = $rs['contentsMapping'];
				if($rs['mainContents'] != null) {
					$contentsCode = explode(',',$rs['mainContents']);
					$cntCode = count($contentsCode);

					for($i=0; $i < $cntCode; $i++) {
						if($rs['contentsMapping'] == "Y") { // 콘텐츠 매칭을 사용한다면 대표 과정이 매칭목록에 있는지 체크
							$query3 = "SELECT * FROM nynContentsMapping WHERE companyID='".$rs['companyID']."' AND contentsCode='".$rs['mainContents']."'";
							$result3 = mysql_query($query3);
							$count3 = mysql_num_rows($result3);

							if($count3 == 0) { // 없으면 운영자가 체크할 수 있게 노 이미지 출력 
								$adminapi['studyCenter'][$a]['mainContents'][$i]['seq'] = "";
								$adminapi['studyCenter'][$a]['mainContents'][$i]['contentsCode'] = "";
								$adminapi['studyCenter'][$a]['mainContents'][$i]['contentsName'] = "추천 과정이 없습니다.";
								$adminapi['studyCenter'][$a]['mainContents'][$i]['previewImage'] = "noimg.jpg";
								$adminapi['studyCenter'][$a]['mainContents'][$i]['intro'] = "";
								$adminapi['studyCenter'][$a]['mainContents'][$i]['sort01'] = "";
								$adminapi['studyCenter'][$a]['mainContents'][$i]['sort02'] = "";

							} else { // 있으면 정보 출력
								$query2 = "SELECT seq, contentsCode, contentsName, previewImage, intro, sort01, sort02 FROM nynContents WHERE contentsCode='".$contentsCode[$i]."'";
								$result2 = mysql_query($query2);
								$rs2 = mysql_fetch_array($result2);

								$adminapi['studyCenter'][$a]['mainContents'][$i]['seq'] = $rs2['seq'];
								$adminapi['studyCenter'][$a]['mainContents'][$i]['contentsCode'] = $rs2['contentsCode'];
								$adminapi['studyCenter'][$a]['mainContents'][$i]['contentsName'] = $rs2['contentsName'];
								$adminapi['studyCenter'][$a]['mainContents'][$i]['previewImage'] = $rs2['previewImage'];
								$adminapi['studyCenter'][$a]['mainContents'][$i]['intro'] = $rs2['intro'];
								$adminapi['studyCenter'][$a]['mainContents'][$i]['sort01'] = $rs2['sort01'];
								$adminapi['studyCenter'][$a]['mainContents'][$i]['sort02'] = $rs2['sort02'];
							}
						} else {
								$query2 = "SELECT seq, contentsCode, contentsName, previewImage, intro, sort01, sort02 FROM nynContents WHERE contentsCode='".$contentsCode[$i]."'";
								$result2 = mysql_query($query2);
								$rs2 = mysql_fetch_array($result2);

								$adminapi['studyCenter'][$a]['mainContents'][$i]['seq'] = $rs2['seq'];
								$adminapi['studyCenter'][$a]['mainContents'][$i]['contentsCode'] = $rs2['contentsCode'];
								$adminapi['studyCenter'][$a]['mainContents'][$i]['contentsName'] = $rs2['contentsName'];
								$adminapi['studyCenter'][$a]['mainContents'][$i]['previewImage'] = $rs2['previewImage'];
								$adminapi['studyCenter'][$a]['mainContents'][$i]['intro'] = $rs2['intro'];
								$adminapi['studyCenter'][$a]['mainContents'][$i]['sort01'] = $rs2['sort01'];
								$adminapi['studyCenter'][$a]['mainContents'][$i]['sort02'] = $rs2['sort02'];
						}
					}

				} else {
					$adminapi['studyCenter'][$a]['mainContents'] = $rs['mainContents'];
				}
				$adminapi['studyCenter'][$a]['inputDate'] = $rs['inputDate'];
				$a++;
			}
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
?>