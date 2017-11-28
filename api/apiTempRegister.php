<?php
		header('Content-Type:application/json; charset=utf-8');
?>
<? include '../lib/header.php'; ?>
<?
	if($method == "POST") {
		$seq = $_POST['seq'];
		$userID = $_POST['userID'];
		$userName = $_POST['userName'];
		$birth = $_POST['birth'];
		$sex = $_POST['sex'];
		$mobile01 = $_POST['mobile01'];
		$mobile02 = $_POST['mobile02'];
		$mobile03 = $_POST['mobile03'];
		$email01 = $_POST['email01'];
		$email02 = $_POST['email02'];
		$companyCode = $_POST['companyCode'];
		$lectureStart = $_POST['lectureStart'];
		$lectureEnd = $_POST['lectureEnd'];
		$contentsCode = $_POST['contentsCode'];
		$tutor = $_POST['tutor'];
		$price = $_POST['price'];
		$rPrice = $_POST['rPrice'];
		$serviceType = $_POST['serviceType'];

		$queryQ =  "userID='".$userID."',
								userName='".$userName."', 
								birth='".$birth."',
								sex='".$sex."',
								mobile01='".$mobile01."',
								mobile02='".$mobile02."',
								mobile03='".$mobile03."',
								email01='".$email01."',
								email02='".$email02."',
								companyCode='".$companyCode."',
								lectureStart='".$lectureStart."',
								lectureEnd='".$lectureEnd."',
								contentsCode='".$contentsCode."',
								tutor='".$tutor."',
								price='".$price."',
								rPrice='".$rPrice."',
								serviceType='".$serviceType."'";

		$query = "UPDATE nynTempRegister SET ".$queryQ." WHERE seq=".$seq;
		$result = mysql_query($query);

		if($result) {
			echo '{"result" : "success"}';
		} else {
			echo '{"result" : "error"}';
		}
		exit;

	} else if($method == "PUT") { // 수강 등록
			parse_str(file_get_contents("php://input"), $_PUT);
			$allSubmit = $_PUT['allSubmit'];

			if($allSubmit == 'Y') { // nynMember, nynStudy 등록
				$query = "SELECT * FROM nynTempRegister";
				$result = mysql_query($query);

				while($rs = mysql_fetch_array($result)) {
					//nynMember
					$memberCheck = "SELECT userID FROM nynMember WHERE userID='".$rs[userID]."'";
					$rstMemberCheck = mysql_query($memberCheck);
					$count = mysql_num_rows($rstMemberCheck);

					if($count > 0) { // 기존회원이면 수정
						$member = " UPDATE nynMember 
												SET userName='".$rs[userName]."', 
														birth='".$rs[birth]."', 
														sex='".$rs[sex]."', 
														mobile01='".$rs[mobile01]."', 
														mobile02='".$rs[mobile02]."', 
														mobile03='".$rs[mobile03]."', 
														email01='".$rs[email01]."', 
														email02='".$rs[email02]."', 
														companyCode='".$rs[companyCode]."', 
														writerID
														department='".$rs[department]."'
												 WHERE userID='".$rs[userID]."'";
						$rstMember = mysql_query($member);

					} else { // 신규회원이면 등록
						$member = " INSERT INTO nynMember 
												SET userID='".$rs[userID]."', 
														userLevel='9', 
														pwd='".$rs[pwd]."', 
														userName='".$rs[userName]."', 
														birth='".$rs[birth]."', 
														sex='".$rs[sex]."', 
														mobile01='".$rs[mobile01]."', 
														mobile02='".$rs[mobile02]."', 
														mobile03='".$rs[mobile03]."', 
														email01='".$rs[email01]."', 
														email02='".$rs[email02]."', 
														companyCode='".$rs[companyCode]."', 
														department='".$rs[department]."',
														inputDate='".$inputDate."'";
						$rstMember = mysql_query($member);
					}

					//nynContents - 평가 유무 추적
					$queryC = " SELECT totalPassMid, totalPassTest, totalPassReport
											FROM nynContents WHERE contentsCode='".$rs[contentsCode]."'";
					$resultC = mysql_query($queryC);
					$rsC = mysql_fetch_assoc($resultC);
					$totalPassMid = $rsC[totalPassMid];
					$totalPassTest = $rsC[totalPassTest];
					$totalPassReport = $rsC[totalPassReport];

					if($totalPassMid > 0 ) {
						$midStatusQ = "midStatus='N', ";
					} else {
						$midStatusQ = "midStatus=null, ";
					}

					if($totalPassTest > 0 ) {
						$testStatusQ = "testStatus='N', ";
					} else {
						$testStatusQ = "testStatus=null, ";
					}

					if($totalPassReport > 0 ) {
						$reportStatusQ = "reportStatus='N', ";
					} else {
						$reportStatusQ = "reportStatus=null, ";
					}

					//nynLectureOpen - 과정 개설
					$queryL = "SELECT seq 
										 FROM nynLectureOpen 
										 WHERE lectureStart='".$rs[lectureStart]."' 
										 AND lectureEnd='".$rs[lectureEnd]."' 
										 AND contentsCode='".$rs[contentsCode]."'";
					$resultL = mysql_query($queryL);
					$rsL = mysql_fetch_assoc($resultL);
					$countL = mysql_num_rows($resultL);

					if($countL == 0) { // 처음 개설한다면 추가.
						$sql="INSERT INTO nynLectureOpen 
									SET lectureStart='".$rs[lectureStart]."',
											lectureEnd='".$rs[lectureEnd]."',
											contentsCode='".$rs[contentsCode]."',
											serviceType='".$rs[serviceType]."'";
						$resultL2 = mysql_query($sql);
						$lectureOpenSeq = mysql_insert_id();

					} else { // 이미 개설되어 있으면 값을 불러온다.
						$lectureOpenSeq = $rsL[seq];
					}

					//nynStudy - 수강 등록
					$study = "INSERT INTO nynStudy 
										SET contentsCode='".$rs[contentsCode]."', 
												tutor='".$rs[tutor]."', 
												userID='".$rs[userID]."', 
												companyCode='".$rs[companyCode]."', 
												lectureOpenSeq='".$lectureOpenSeq."', 
												lectureStart='".$rs[lectureStart]."', 
												lectureEnd='".$rs[lectureEnd]."', 
												lectureReStudy='".$rs[lectureReStudy]."', 
												".$midStatusQ."
												".$testStatusQ."
												".$reportStatusQ."
												price='".$rs[price]."', 
												rPrice='".$rs[rPrice]."', 
												writerID='".$_SESSION[loginUserID]."',
												writeDate='".$inputDate."',
												serviceType='".$rs[serviceType]."'";
					$rstStudy = mysql_query($study);

					if($rstStudy) {  // 등록 성공이면 임시등록 삭제
						$queryD = "DELETE FROM nynTempRegister WHERE seq=".$rs[seq];
						$resultD = mysql_query($queryD);
					} else {
						echo '{"result" : "delete error"}';
						exit;
					}					

				}

				if($resultD) {
					echo '{"result" : "success"}';
				} else {
					echo '{"result" : "error"}';
				}
				exit;
			}

	} else if($method == "DELETE") { // 수강 삭제
			parse_str(file_get_contents("php://input"), $_DEL);
			$seq = $_DEL['seq'];
			$allDelete = $_DEL['allDelete'];

			if($allDelete == 'Y') {
				$query = "DELETE FROM nynTempRegister";
			} else {
				$query = "DELETE FROM nynTempRegister WHERE seq=".$seq;
			}
				$result = mysql_query($query);

			if($result) {
				echo '{"result" : "success"}';
			} else {
				echo '{"result" : "error"}';
			}
			exit;

	} else if($method == "GET") {
			$list = $_GET['list'];
			$page = $_GET['page'];

			if($list == "") {
				$list = 10;
			}
			if($page == "") {
				$page = 1;
			}
/*
			$que = "SELECT A.*, B.contentsName, C.userName AS tutorName, D.companyName
							FROM nynTempRegister AS A
							LEFT OUTER
							JOIN nynContents AS B ON A.contentsCode=B.contentsCode
							LEFT OUTER
							JOIN nynMember AS C ON A.tutor=C.userID
							LEFT OUTER
							JOIN nynCompany AS D ON A.companyCode=D.companyCode";
			$res = mysql_query($que);
			$allPost = mysql_num_rows($res);
			$currentLimit = ($list * $page) - $list; //몇 번째의 글부터 가져오는지
			$sqlLimit = ' LIMIT '.$currentLimit.', '.$list; //limit sql 구문
*/

			$query = "SELECT A.*, B.contentsName, C.userName AS tutorName, D.companyName,
								(SELECT count(*) FROM nynStudy WHERE userID=A.userID AND contentscode=A.contentsCode) AS lectureEA 
								FROM nynTempRegister AS A
								LEFT OUTER
								JOIN nynContents AS B ON A.contentsCode=B.contentsCode
								LEFT OUTER
								JOIN nynMember AS C ON A.tutor=C.userID
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
							FROM nynTempRegister AS A
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
				$adminapi[study][$a][userID] = $rs[userID];
				$adminapi[study][$a][userName] = $rs[userName];
				$adminapi[study][$a][birth] = $rs[birth];
				$adminapi[study][$a][sex] = $rs[sex];
				$adminapi[study][$a][mobile01] = $rs[mobile01];
				$adminapi[study][$a][mobile02] = $rs[mobile02];
				$adminapi[study][$a][mobile03] = $rs[mobile03];
				$adminapi[study][$a][email01] = $rs[email01];
				$adminapi[study][$a][email02] = $rs[email02];
				$adminapi[study][$a][companyCode] = $rs[companyCode];
				$adminapi[study][$a][companyName] = $rs[companyName];
				$adminapi[study][$a][department] = $rs[department];
				$adminapi[study][$a][lectureStart] = $rs[lectureStart];
				$adminapi[study][$a][lectureEnd] = $rs[lectureEnd];
				$adminapi[study][$a][lectureReStudy] = $rs[lectureReStudy];
				$adminapi[study][$a][contentsCode] = $rs[contentsCode];
				$adminapi[study][$a][contentsName] = $rs[contentsName];
				$adminapi[study][$a][tutor] = $rs[tutor];
				if($rs[tutorName] == null) {
					$tutorName = '';
				} else {
					$tutorName = $rs[tutorName];
				}
				$adminapi[study][$a][tutorName] = $tutorName;
				$adminapi[study][$a][price] = $rs[price];
				$adminapi[study][$a][rPrice] = $rs[rPrice];
				$adminapi[study][$a][serviceType] = $rs[serviceType];
				$adminapi[study][$a][inputDate] = $rs[inputDate];
				$adminapi[study][$a][lectureEA] = $rs[lectureEA];
				$a++;
			}
			$json_encoded = json_encode($adminapi);
			print_r($json_encoded);
		}
?>