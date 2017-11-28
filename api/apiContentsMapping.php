<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") { // 등록 및 수정
		$contentsCode = $_POST['contentsCode'];
		$companyID = $_POST['companyID'];
		$add = $_POST['add'];
		$del = $_POST['del'];

		if($add == "Y") {
			$query = " INSERT INTO nynContentsMapping 
					 			  SET companyID = '".$companyID."',
											contentsCode = '".$contentsCode."'";
			$result = mysql_query($query);

		} else if($del == "Y") {
			$query = " DELETE FROM nynContentsMapping 
					 			  WHERE companyID = '".$companyID."'
									AND contentsCode = '".$contentsCode."'";
			$result = mysql_query($query);
		}

		if($result) {
			echo '{"result" : "success"}';
		} else {
			echo '{"result" : "error"}';
		}
		exit;

	} else if($method == "GET") { // json 출력
		$companyID = $_GET['companyID'];
		$query = "SELECT A.*, B.contentsName, B.sort01, B.sort02, B.enabled, C.value02 AS sort01Name, D.value02 AS sort02Name
							FROM nynContentsMapping AS A
							LEFT OUTER
							JOIN nynContents AS B ON A.contentsCode = B.contentsCode
							LEFT OUTER
							JOIN nynCategory AS C ON B.sort01 = C.value01
							LEFT OUTER
							JOIN nynCategory AS D ON B.sort02 = D.value01
							WHERE A.companyID='".$companyID."'
							ORDER BY A.orderBy, A.seq";
		$result = mysql_query($query);
		$count = mysql_num_rows($result);
		$a = 0;
		$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

		$adminapi[companyID] = $companyID;
		$adminapi[totalCount] = "$count"; //총 개시물 수

		while($rs = mysql_fetch_array($result)) {
			$adminapi[contentsMapping][$a][seq] = $rs['seq'];
			$adminapi[contentsMapping][$a][contentsCode] = $rs['contentsCode'];
			$adminapi[contentsMapping][$a][contentsName] = $rs['contentsName'];
			$adminapi[contentsMapping][$a][sort01] = $rs['sort01'];
			$adminapi[contentsMapping][$a][sort02] = $rs['sort02'];
			$adminapi[contentsMapping][$a][sort01Name] = $rs['sort01Name'];
			$adminapi[contentsMapping][$a][sort02Name] = $rs['sort02Name'];
			$adminapi[contentsMapping][$a][enabled] = $rs['enabled'];
			$a++;
		}
		
		$json_encoded = json_encode($adminapi);
		print_r($json_encoded);
	}

	mysql_close();
?>