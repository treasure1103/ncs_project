<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$seq = $_POST['seq'];
		$companyName = $_POST['companyName']; //회사명
		$companyID = $_POST['companyID'];     //사업자 아이디
		$companyScale = $_POST['companyScale']; //회사규모
		$companyCode = $_POST['companyCode']; //사업자등록번호
		$hrdCode = $_POST['hrdCode'];		//hrd번호
		$ceoName = $_POST['ceoName'];		//대표자명
		$address01 = $_POST['address01'];		//주소
		$kind = $_POST['kind'];		//업태
		$part = $_POST['part'];		//업종
		$phone = $_POST['phone'];		//회사전화번호
		$elecEmail = $_POST['elecEmail'];		//전자계산서 메일
		$managerName = $_POST['managerName'];		//교육담당자명
		$managerPhone = $_POST['managerPhone'];		//교육담당자 연락처
		$managerEmail = $_POST['managerEmail'];		//교육담당자 이메일
		$marketerName = $_POST['marketerName'];		//영업자명
		$marketerID = $_POST['marketerID'];				//영업자아이디
		$staffName = $_POST['staffName'];		//운영담당자명
		$staffID = $_POST['staffID'];		//운영담당자 아이디		
		$memo = $_POST['memo'];		//메모

		$queryQ =  "companyName='".$companyName."',
								companyID='".$companyID."',								
								companyScale='".$companyScale."',
								companyCode='".$companyCode."',
								hrdCode='".$hrdCode."',								
								ceoName='".$ceoName."',
								address01='".$address01."',
								kind='".$kind."',
								part='".$part."',
								phone='".$phone."',
								elecEmail='".$elecEmail."',
								managerName='".$managerName."',
								managerPhone='".$managerPhone."',
								managerEmail='".$managerEmail."',
								staffName='".$staffName."',
								staffID='".$staffID."',
								marketerName='".$marketerName."',
								marketerID='".$marketerID."',
								memo='".$memo."'";

		$query = "UPDATE nynTempCompany SET ".$queryQ." WHERE seq=".$seq;
		$result = mysql_query($query);

		if($result) {
			echo '{"result" : "success"}';
		} else {
			echo '{"result" : "error"}';
		}
		exit;

	}  else if($method == "GET") {
			$list = $_GET['list'];
			$page = $_GET['page'];

			if($list == "") {
				$list = 10;
			}
			if($page == "") {
				$page = 1;
			}

			$query = "SELECT A.*, B.contentsName, C.userName AS tutorName, D.companyName
								FROM nynTempCompany AS A
								LEFT OUTER							
								JOIN nynMember AS B ON A.managerID=B.userID
								LEFT OUTER
								JOIN nynCompany AS D ON A.companyCode=D.companyCode
								ORDER BY A.seq";
			$result = mysql_query($query);
			$count = mysql_num_rows($result);

			$a = 0;
			$c = 0;
			$adminapi = array();
			$adminapi[totalCount] = "$count";

			//
			$que = "SELECT COUNT(A.companyCode) AS CNT, B.companyName
							FROM nynTempCompany AS A
							LEFT OUTER
							JOIN nynCompany AS B ON A.companyCode=B.companyCode
							GROUP BY A.companyCode";
			$res = mysql_query($que);

			while($rss = mysql_fetch_array($res)) {
				$adminapi[company][$c][name] = $rss[companyName];
				$adminapi[company][$c][count] = $rss[CNT];
				$c++;
			}

			while($rs = mysql_fetch_array($result)) {
				$adminapi[study][$a][seq] = $rs[seq];
				$adminapi[study][$a][companyName] = $rs[companyName];
				$adminapi[study][$a][companyID] = $rs[companyID];
				$adminapi[study][$a][companyScale] = $rs[companyScale];
				$adminapi[study][$a][companyCode] = $rs[companyCode];
				$adminapi[study][$a][hrdCode] = $rs[hrdCode];
				$adminapi[study][$a][ceoName] = $rs[ceoName];
				$adminapi[study][$a][address01] = $rs[address01];
				$adminapi[study][$a][kind] = $rs[kind];
				$adminapi[study][$a][part] = $rs[part];
				$adminapi[study][$a][phone] = $phone;
				$adminapi[study][$a][email] = $elecEmail;
				$adminapi[study][$a][managerName] = $rs[managerName];
				$adminapi[study][$a][managerPhone] = $managerPhone;
				$adminapi[study][$a][managerEmail] = $managerEmail;
				$adminapi[study][$a][marketerName] = $rs[marketerName];
				$adminapi[study][$a][marketerID] = $rs[marketerID];
				$adminapi[study][$a][staffName] = $rs[staffName];
				$adminapi[study][$a][staffaID] = $rs[staffaID];
				$adminapi[study][$a][memo] = $rs[memo];
				$a++;
			}
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
		
	@mysql_close();
?>