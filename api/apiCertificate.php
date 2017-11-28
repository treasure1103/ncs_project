<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
		$companyCode =$_GET['companyCode'];
		$lectureStart =$_GET['lectureStart'];
		$lectureEnd =$_GET['lectureEnd'];
		$userID = $_SESSION['loginUserID'];

		if(!$userID) { // 세션이 없으면 접근 거부
			exit;
		}

		$query = "SELECT B.birth, F.companyName, B.department,
							IF(ISNULL(B.userName),'입력오류',B.userName) AS userName, 
							IF(ISNULL(E.contentsName),'입력오류',E.contentsName) AS contentsName, 
							F.companyID, C.certificate
							FROM nynStudy AS A
							LEFT OUTER
							JOIN nynMember AS B ON A.userID=B.userID
							LEFT OUTER
							JOIN nynContents AS E ON A.contentsCode=E.contentsCode 
							LEFT OUTER
							JOIN nynCompany AS F ON A.companyCode=F.companyCode 
							LEFT OUTER
							JOIN nynStudyCenter AS C ON F.companyID=C.companyID
							WHERE A.serviceType='1' AND A.passOK='Y' AND A.companyCode='".$companyCode."'
							AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."'"; 
			$result = mysql_query($query);
			$count = mysql_num_rows($result);

			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분
			$adminapi[totalCount] = "$count"; //총 개시물 수

			while($rs = mysql_fetch_array($result)) {
				$adminapi[certificate] = $rs[certificate]; //수료증 도장을 회원사 전용 도장 사용할건지 Y면 사용
				$adminapi[study][$a][userName] = $rs[userName];
				$adminapi[study][$a][birth] = $rs[birth];
				$adminapi[study][$a][companyID] = $rs[companyID];
				$adminapi[study][$a][companyName] = $rs[companyName];
				$adminapi[study][$a][department] = $rs[department];
				$adminapi[study][$a][contentsName] = $rs[contentsName];
				$adminapi[study][$a][lectureStart] = $lectureStart;
				$adminapi[study][$a][lectureEnd] = $lectureEnd;


				$a++;
			}
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		
	@mysql_close();
?>