<?php
	header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$seq = $_POST['seq'];
		$division = $_POST['division'];
		$value01 = $_POST['value01'];
		$value02 = $_POST['value02'];
		$value03 = $_POST['value03'];
		$userID = $_POST['userID'];
		$orderBy = $_POST['orderBy'];
		$enabled = $_POST['enabled'];

		if($_SESSION['loginUserLevel'] > 4) { //관리자만 가능
			echo "error";
			exit;
		}

		$queryQ =  "division='".$division."',
								value01='".$value01."',
								value02='".$value02."',
								value03='".$value03."',
								userID='".$userID."',
								enabled='".$enabled."'";

		if($seq == "") {
			$queryC = "SELECT MAX(orderBy) AS orderBy FROM nynCategory WHERE division=".$division;
			$resultC = mysql_query($queryC);
			$rsC = mysql_fetch_assoc($resultC);
			$orderByMax = $rsC[orderBy];
			
			if($orderByMax == "") {
				$orderBy = "1";
			}

			if($orderBy == "") {
				$orderBy = $orderByMax+1;
			}

			$queryU = "UPDATE nynCategory SET orderBy=orderBy+1 WHERE division=".$division." AND orderBy>=".$orderBy;
			$resultU = mysql_query($queryU);

			$query = "INSERT INTO nynCategory SET orderBy='".$orderBy."', inputDate='".$inputDate."', ".$queryQ;
			$result = mysql_query($query);

		} else {
			$queryC = "SELECT orderBy FROM nynCategory WHERE seq=".$seq;
			$resultC = mysql_query($queryC);
			$rsC = mysql_fetch_assoc($resultC);
			$orderByC = $rsC[orderBy];

			if($orderBy != $orderByC) {
				if($orderBy > $orderByC) { //수정할 순번이 현재 순번보다 큰 경우 -1
					$queryU = "UPDATE nynCategory SET orderBy=orderBy-1 WHERE division=".$division." AND seq <> ".$seq." AND (orderBy>=".$orderByC." AND orderBy <=".$orderBy.")";
					$resultU = mysql_query($queryU);
			
				} else { //수정할 순번이 현재 순번보다 작은 경우 +1
					$queryU = "UPDATE nynCategory SET orderBy=orderBy+1 WHERE division=".$division." AND seq <> ".$seq." AND (orderBy>=".$orderBy." AND orderBy <=".$orderByC.")";
					$resultU = mysql_query($queryU);
				}
			}

			$query = "UPDATE nynCategory SET orderBy='".$orderBy."', ".$queryQ." WHERE seq=".$seq;
			$result = mysql_query($query);
		}

		if($result){
			echo "success";
		} else {
			echo "error";
		}
			exit;

	} else if($method == "DELETE") {
			parse_str(file_get_contents("php://input"), $_DEL);
			$seq = $_DEL['seq'];

			if($_SESSION['loginUserLevel'] > 4) { //관리자만 수정 가능
				echo "error";
				exit;
			}

			//순번 수정 -> 순번이 더 큰 값들을 -1씩 수정
			$query1 = "SELECT division, orderBy FROM nynCategory WHERE seq=".$seq;
			$result1 = mysql_query($query1);
			$orderBy = mysql_result($result1,0,'orderBy');
			$division = mysql_result($result1,0,'division');

			$queryU = "UPDATE nynCategory SET orderBy=orderBy-1 
									WHERE seq <> ".$seq." AND 
									division=".$division." AND
									orderBy>=".$orderBy;
			$resultU = mysql_query($queryU);
			
			$query = "DELETE FROM nynCategory WHERE seq=".$seq;
			$result = mysql_query($query);

			if($result){
				echo "success";
			} else {
				echo "error";
			}
			exit;

	} else if($method == "GET") {
			$seq = $_GET['seq'];
			$value01 = $_GET['value01'];
			$value02 = $_GET['value02'];
			$value03 = $_GET['value03'];
			$divisionValue = $_GET['divisionValue'];
			$companyID = $_GET['companyID'];
			$allType = $_GET['allType'];

			if($seq == "") {
				$seq = 0;
			} 

			if($value01 != "") { // value01로 카테고리명 검색 시 seq 출력
				$queryS1 = "select seq from nynCategory where value01='".$value01."'";
				$result1 = mysql_query($queryS1);
				$seq = mysql_result($result1,0,'seq');
			} 

			if($value03 != "") { // value03 값이 있으면 검색 붙여줌
				$qValue03 = " AND value03='".$value03."'";
			} 

			if($value01 != "" && $divisionValue != "") { // value01 값만 있으면 현재에서 1단계 하위 정보 출력
				$queryS = "select seq from nynCategory where value01='".$value01."'";
				$result = mysql_query($queryS);
				$seq = mysql_result($result,0,'seq');

				$queryS = "select seq from nynCategory where division=".$seq." AND value01='".$divisionValue."'";
				$result = mysql_query($queryS);
				$seq = mysql_result($result,0,'seq');
			}

			$division = 1; // 기본 1회 반복 실행
			$seq2 = $seq;
			$column = "division";
			$location = "<a href='javascript:listAct();'>TOP</a>";
			$i=0;

			// 상단 현재위치 추척 시작
			while($seq2 > 0) {
				$query = "SELECT * FROM nynCategory WHERE ".$column."=".$seq2." ORDER BY orderBy";
				$result = mysql_query($query);
				$count = mysql_num_rows($result);
				$rs = mysql_fetch_array($result);
				$locationP[$i] = $rs[value02];
				$seqP[$i] = $rs[seq];
				$column = "seq";

				if($count == 0) { // 현재위치가 최상단(count가 0일 때) 일때 조회
					$query = "SELECT * FROM nynCategory WHERE ".$column."=".$seq2." ORDER BY orderBy";
					$result = mysql_query($query);
					$rs = mysql_fetch_array($result);
					$locationP[$i] = $rs[value02];
					$seqP[$i] = $rs[seq];
				}
				$seq2 = $rs[division];
				$i++;
			}

			$query = "SELECT * FROM nynCategory WHERE division=".$seq.$qValue03." ORDER BY orderBy";
			$result = mysql_query($query);
			$count = mysql_num_rows($result);

			$c = $i;

			// 현재위치 추척(조회) 값 역 출력
			if($count == 0) {
				for($i--; $i>=0; $i--) {
					$location .= " &gt; <a href='javascript:listAct(".$seqP[$i].");'>".$locationP[$i]."</a>";
				}
			} else {
				for($i--; $i>0; $i--) {
					$location .= " &gt; <a href='javascript:listAct(".$seqP[$i].");'>".$locationP[$i]."</a>";
				}
			}

			$a = 0;
			$adminapi = array();
			$adminapi[location] = "$location";
			$adminapi[division] = "$seq";

			// 값 출력

			if(substr($value01,0,7) == "lecture") { // 과정 카테고리 전용 출력 (value01 = lecture)

				if($value01 != "lectureCode") {
					while($rsP = mysql_fetch_array($result)) {

					  if($allType == "ALL"){
						  $countZ=$count;
					  }else{
								if($companyID) {
									$queryW = "SELECT contentsMapping FROM nynStudyCenter WHERE companyID='".$companyID."'";
									$resultW = mysql_query($queryW);
									$rsW = mysql_fetch_array($resultW);
									$contentsMapping = $rsW[contentsMapping];

									if($contentsMapping == "Y") {
										$queryZ = " SELECT count(DISTINCT(A.contentsCode)) cnt
																FROM nynContents AS A
																RIGHT OUTER
																JOIN nynContentsMapping AS B ON A.contentsCode=B.contentsCode AND B.companyID='".$companyID."'
																WHERE A.sort02='".$rsP[value01]."'";
									} else {
										$queryZ = "SELECT count(DISTINCT(contentsCode)) cnt FROM nynContents WHERE sort02='".$rsP[value01]."'";
									}
								} else {
									$queryZ = "SELECT count(DISTINCT(contentsCode)) cnt FROM nynContents WHERE sort02='".$rsP[value01]."'";
								}
								$resultZ = mysql_query($queryZ);
								//$countZ = mysql_num_rows($resultZ);
								$rsI = mysql_fetch_assoc($resultZ);
								$countZ = $rsI[cnt];
						}

						if($countZ > 0) {
							$adminapi[category][$a][seq] = $rsP[seq];
							$adminapi[category][$a][value01] = $rsP[value01];
							$adminapi[category][$a][value02] = $rsP[value02];
							$adminapi[category][$a][value03] = $rsP[value03];
							$adminapi[category][$a][orderBy] = $rsP[orderBy];
							$adminapi[category][$a][userID] = $rsP[userID];
							$adminapi[category][$a][inputDate] = $rsP[inputDate];
							$adminapi[category][$a][enabled] = $rsP[enabled];
							$a++;
						}
						$adminapi[totalCount] = "$a";
					}

				} else { // value01 = lectureCode 인 경우 모든 카테고리 출력
					while($rsP = mysql_fetch_array($result)) {

					  if($allType=="ALL"){
						  $countZ=1;
					  }else{

						if($companyID) {
							$queryW = "SELECT contentsMapping FROM nynStudyCenter WHERE companyID='".$companyID."'";
							$resultW = mysql_query($queryW);
							$rsW = mysql_fetch_array($resultW);
							$contentsMapping = $rsW[contentsMapping];

							if($contentsMapping == "Y") {
								$queryZ = " SELECT count(DISTINCT(A.contentsCode)) cnt
														FROM nynContents AS A
														RIGHT OUTER
														JOIN nynContentsMapping AS B ON A.contentsCode=B.contentsCode AND B.companyID='".$companyID."'
														WHERE A.sort01='".$rsP[value01]."'";
							} else {
								$queryZ = "SELECT count(DISTINCT(contentsCode))  cnt FROM nynContents WHERE sort01='".$rsP[value01]."'";
							}
						} else {

							$queryZ = "SELECT count(DISTINCT(contentsCode)) cnt FROM nynContents WHERE sort01='".$rsP[value01]."'";
						}
							$resultZ = mysql_query($queryZ);
							//$countZ = mysql_num_rows($resultZ);
							$rsI = mysql_fetch_assoc($resultZ);
							$countZ = $rsI[cnt];
					  }

						if($countZ > 0 ) {
							$adminapi[category][$a][seq] = $rsP[seq];
							$adminapi[category][$a][value01] = $rsP[value01];
							$adminapi[category][$a][value02] = $rsP[value02];
							$adminapi[category][$a][value03] = $rsP[value03];
							$adminapi[category][$a][orderBy] = $rsP[orderBy];
							$adminapi[category][$a][userID] = $rsP[userID];
							$adminapi[category][$a][inputDate] = $rsP[inputDate];
							$adminapi[category][$a][enabled] = $rsP[enabled];

									$queryY = "SELECT * FROM nynCategory WHERE division=".$rsP[seq]." ORDER BY orderBy";
									$resultY = mysql_query($queryY);
									$b = 0;

									while($rsY = mysql_fetch_array($resultY)) {
										if($companyID) {
											$queryX = " SELECT DISTINCT(A.contentsCode)
																	FROM nynContents AS A
																	RIGHT OUTER
																	JOIN nynContentsMapping AS B ON A.contentsCode=B.contentsCode AND B.companyID='".$companyID."'
																	WHERE A.sort02='".$rsY[value01]."'";

										} else {
											$queryX = "SELECT DISTINCT(contentsCode) FROM nynContents WHERE sort02='".$rsY[value01]."'";
										}
											$resultX = mysql_query($queryX);
											//$countX = mysql_num_rows($resultX);
											$rsI = mysql_fetch_assoc($resultX);
											$countX = $rsI[cnt];

										if($countX > 0) {
											$adminapi[category][$a][sub][$b][seq] = $rsY[seq];
											$adminapi[category][$a][sub][$b][value01] = $rsY[value01];
											$adminapi[category][$a][sub][$b][value02] = $rsY[value02];
											$adminapi[category][$a][sub][$b][value03] = $rsY[value03];
											$adminapi[category][$a][sub][$b][orderBy] = $rsY[orderBy];
											$adminapi[category][$a][sub][$b][userID] = $rsY[userID];
											$adminapi[category][$a][sub][$b][inputDate] = $rsY[inputDate];
											$adminapi[category][$a][sub][$b][enabled] = $rsY[enabled];
											$b++;
										}
									}
							$a++;
						}

						$adminapi[totalCount] = "$a";
					}
				}

			} else {
					while($rsP = mysql_fetch_array($result)) {
						$adminapi[category][$a][seq] = $rsP[seq];
						$adminapi[category][$a][value01] = $rsP[value01];
						$adminapi[category][$a][value02] = $rsP[value02];
						$adminapi[category][$a][value03] = $rsP[value03];
						$adminapi[category][$a][orderBy] = $rsP[orderBy];
						$adminapi[category][$a][userID] = $rsP[userID];
						$adminapi[category][$a][inputDate] = $rsP[inputDate];
						$adminapi[category][$a][enabled] = $rsP[enabled];
						$a++;
					}
			}

			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);

		}
	mysql_close();
?>