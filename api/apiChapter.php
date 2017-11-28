<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$seq = $_POST['seq'];
		$contentsCode = $_POST['contentsCode'];
		$chapter = $_POST['chapter'];
		$chapterName = $_POST['chapterName'];
		$goal = $_POST['goal'];
		$content = $_POST['content'];
		$activity = $_POST['activity'];
		$professor = $_POST['professor'];
		$player = $_POST['player'];
		$chapterPath = $_POST['chapterPath'];
		$chapterSize = $_POST['chapterSize'];
		$chapterMobilePath = $_POST['chapterMobilePath'];
		$chapterMobileSize = $_POST['chapterMobileSize'];
		$mobileDataSize = $_POST['mobileDataSize'];

		if($mobileDataSize) {
			$mobileDataSizeQ = "mobileDataSize = '".$mobileDataSize."', ";
		}

		$queryQ =  "chapter = '".$chapter."',
								chapterName = '".$chapterName."',
								goal = '".addslashes($goal)."',
								content = '".addslashes($content)."',
								activity = '".addslashes($activity)."',
								professor = '".$professor."', 
								player = '".$player."', 
								chapterSize = '".$chapterSize."', 
								chapterPath = '".$chapterPath."', 
								".$mobileDataSizeQ."
								chapterMobilePath = '".$chapterMobilePath."',
								chapterMobileSize = '".$chapterMobileSize."'";
								
		if($seq == "") { // 차시 등록
			$queNum="SELECT MAX(seq) AS seq FROM nynChapter";
			$resultNum = mysql_query($queNum);
			$rsNum = mysql_fetch_assoc($resultNum);
			$seq = $rsNum[seq]+1;

			$query = "INSERT INTO nynChapter SET contentsCode = '".$contentsCode."', " .$queryQ;
			$result = mysql_query($query);

		} else { //차시 수정
			$query = "UPDATE nynChapter SET " .$queryQ. " WHERE seq = '".$seq."'";
			$result = mysql_query($query);
		}

				if($result){
					echo '{"result" : "success"}';
				} else {
					echo '{"result" : "error"}';
				}
			exit;

	} else if($method == "DELETE") { // 차시 삭제
			parse_str(file_get_contents("php://input"), $_DEL);
			$seq = $_DEL['seq'];
			$query = "DELETE FROM nynChapter where seq=".$seq;
			$result = mysql_query($query);

			if($result){
				echo "success";
			} else {
				echo "error";
			}
			exit;

	} else if($method == "GET") { // 차시 json 출력
			$seq = $_GET['seq'];
			$contentsCode = $_GET['contentsCode'];
			$chapter = $_GET['chapter'];

			if($seq != "") {
				$qSeq = " AND seq='".$seq."'";
			}
			if($chapter != "") {
				$qChapter = " AND chapter='".$chapter."'";
			}

			$qSearch = $qSeq.$qChapter;

			$qCon = "SELECT contentsName, sourceType, mobile FROM nynContents WHERE contentsCode='".$contentsCode."'";
			$rCon = mysql_query($qCon);

			$query = "SELECT * FROM nynChapter WHERE contentsCode='".$contentsCode."'".$qSearch." order by chapter";
			$result = mysql_query($query);
			$count = mysql_num_rows($result);

			$a = 0;
			$adminapi = array(); //DB 값이 없는 경우 배열선언 부분

			$adminapi[contentsCode] = $contentsCode;
			$adminapi[contentsName] = mysql_result($rCon,0,'contentsName');
			$adminapi[sourceType] = mysql_result($rCon,0,'sourceType');
			$adminapi[mobile] = mysql_result($rCon,0,'mobile');
			$adminapi[totalCount] = "$count"; //등록된 게시물 수

			while($rs = mysql_fetch_array($result)) {
				$adminapi[chapter][$a][seq] = $rs[seq];
				$adminapi[chapter][$a][chapter] = $rs[chapter];
				$adminapi[chapter][$a][chapterName] = $rs[chapterName];
				$adminapi[chapter][$a][goal] = $rs[goal];
				$adminapi[chapter][$a][content] = $rs[content];
				$adminapi[chapter][$a][activity] = $rs[activity];
				$adminapi[chapter][$a][professor] = $rs[professor];
				$adminapi[chapter][$a][player] = $rs[player];
				$adminapi[chapter][$a][chapterPath] = $rs[chapterPath];
				$adminapi[chapter][$a][chapterSize] = $rs[chapterSize];
				$adminapi[chapter][$a][chapterMobilePath] = $rs[chapterMobilePath];
				$adminapi[chapter][$a][chapterMobileSize] = $rs[chapterMobileSize];
				$adminapi[chapter][$a][mobileDataSize] = $rs[mobileDataSize];
				$a++;
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
?>