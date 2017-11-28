<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
		$contentsCode = $_GET['contentsCode'];
		$chapter = $_GET['chapter'];

		// 1차시(맛보기) 접근이 아닐때 관리자 아니면 막음
		if($chapter != 1) {
			if(!$_SESSION[loginUserLevel]) { // 세션레벨 체크
				echo '{"result" : "접근 권한이 없습니다."}';
				exit;
			} else { // 세션레벨이 있을때 관리자인지 체크
				$queryZ = " SELECT * FROM nynStudy WHERE userID='".$_SESSION[loginUserID]."' AND contentsCode='".$contentsCode."'";
				$resultZ = mysql_query($queryZ);
				$count = mysql_num_rows($resultZ);
				
				if($count == 0) {
					if($_SESSION[loginUserLevel] > 8) {
						echo '{"result" : "접근 권한이 없습니다."}';
						exit;
					}
				}
			}
		}

		$query = "SELECT contentsName, contentsCode, professor, sourceType From nynContents WHERE contentsCode='".$contentsCode."'";
		$result = mysql_query($query);
		$rs = mysql_fetch_array($result);

		$adminapi = array(); //DB 값이 없는 경우 배열선언 부분
		$adminapi[contentsName] = $rs[contentsName];
		$adminapi[contentsCode] = $rs[contentsCode];
		$adminapi[sourceType] = $rs[sourceType];
		
		$queryA = " SELECT * FROM nynChapter WHERE contentsCode='".$contentsCode."' AND chapter='".$chapter."'";
		$resultA = mysql_query($queryA);

		$a = 0;
		while($rsA = mysql_fetch_array($resultA)) {
			$adminapi[progress][$a][chapter] = $rsA[chapter];
			$adminapi[progress][$a][chapterName] = $rsA[chapterName];
			$adminapi[progress][$a][chapterPath] = $rsA[chapterPath];
			$adminapi[progress][$a][chapterMobilePath] = $rsA[chapterMobilePath];
			$adminapi[progress][$a][chapterSize] = $rsA[chapterSize];
			$adminapi[progress][$a][chapterMobileSize] = $rsA[chapterMobileSize];
			$adminapi[progress][$a][goal] = $rsA[goal];
			$adminapi[progress][$a][content] = $rsA[content];
			$adminapi[progress][$a][activity] = $rsA[activity];
			$adminapi[progress][$a][professor] = $rs[professor];
			$a++;
		}

		$json_encoded = json_encode($adminapi);
		print_r($json_encoded);
?>