<? include '../lib/header.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>수료결과서</title>
<style type="text/css">
@import url(http://fonts.googleapis.com/earlyaccess/nanumgothic.css);
/* font-family:'Nanum gothic', serif; */
@media print { 
	.a4 { page-break-after: always; }
	body { border:0; margin:0; padding:0;  }
}
body { margin:0 auto; padding:0; font-family:'Nanum gothic', serif; font-size:12px; }
h1, h2, div, p, img { margin:0; padding:0;}
table { width:750px; border-collapse:collapse; border-spacing:0px; border:1px solid #000;}
th, td { padding:5px 8px; border:1px solid #000; text-align:center;}
#wrap {  width:800px; margin:0 auto;}
#wrap > h1 { margin:60px 0;  text-align:center; }
#wrap > h2 { margin:20px 20px 20px 25px; font-size:13px; font-weight:900;}
#wrap > div { margin-top:30px;}
#wrap > div table { margin:0 auto;}
#wrap > div h1 { margin:6px 6px 6px 25px; font-size:13px;}
#table03 { padding-top:25px;}
#footer { margin:0 auto; padding-top:40px; width:750px; font-weight:900; text-align:center;}
#footer img { margin:0 auto; padding-top:50px; width:180px; height:70px;  }
.table03 { margin-top:20px; border:2px solid #000;}
</style>
</head>

<?
	$companyCode = $_GET['companyCode'];
	$serviceType = $_GET['serviceType'];
	/*
	$lectureDay = $_GET['lectureDay']; // 수강일
	$lectureSE = EXPLODE('~',$lectureDay);
	$lectureStart = $lectureSE[0];
	$lectureEnd = $lectureSE[1];
	*/
	$lectureStart = $_GET['lectureStart'];
	$lectureEnd = $_GET['lectureEnd'];

	if(!$_SESSION[loginUserID]) { // 세션이 없으면 접근 거부
		echo "error";
		exit;
	}

	if($serviceType == 1) {
		$qServiceType = "AND A.serviceType=1";
		$qServiceType2 = "AND serviceType=1";
	} else if($serviceType == 3) {
		$qServiceType = "AND A.serviceType=3";
		$qServiceType2 = "AND serviceType=3";
	} else {
		$qServiceType = "AND A.serviceType IN (1,3)";
		$qServiceType2 = "AND serviceType IN (1,3)";
	}
?>

<body>
  <div id="wrap">
    <h1> 사업주 직업능력개발 인터넷 원격훈련 수료 결과서</h1>
    <h2>학습기간: <?=$lectureStart?> ~ <?=$lectureEnd?></h2>
		<? //리스트 출력
			$query = "SELECT DISTINCT(A.contentsCode), B.contentsName, C.companyName FROM nynStudy AS A
								LEFT OUTER
								JOIN nynContents AS B
								ON A.contentsCode=B.contentsCode
								LEFT OUTER
								JOIN nynCompany AS C
								ON A.companyCode=C.companyCode
								WHERE A.companyCode='".$companyCode."' AND A.lectureStart='".$lectureStart."' 
								AND A.lectureEnd='".$lectureEnd."' ".$qServiceType."
								ORDER BY B.contentsName"; 
			$result = mysql_query($query);
			$a = 1;

			while($rs = mysql_fetch_array($result)) {
				$companyName = $rs[companyName];
		?>
    <div>
      <h1>과정명 : <?=$rs[contentsName]?></h1>
      <table class="table01">
        <tr>
          <th>번호</th>
          <th>이름</th>
					<th>생년월일</th>
          <th>진도율</th>
          <th>중간평가</th>
          <th>최종평가</th>
          <th>과제</th>
          <th>총점</th>
          <th>수료여부</th>
          <th>교육비</th>
          <th>환급액</th>
        </tr>

		<?
			$queryA = "SELECT A.*, B.userName, B.birth, C.contentsName, C.reportEA FROM nynStudy AS A
								LEFT OUTER 
								JOIN nynMember AS B
								ON A.userID=B.userID
								LEFT OUTER 
								JOIN nynContents AS C
								ON A.contentsCode=C.contentsCode
								WHERE A.companyCode='".$companyCode."' AND A.lectureStart='".$lectureStart."' 
								AND A.lectureEnd='".$lectureEnd."' AND C.contentsCode='".$rs[contentsCode]."'
								".$qServiceType."
								ORDER BY A.passOK, B.userName"; 
			$resultA = mysql_query($queryA);
			$b = 1;
			
			while($rsA = mysql_fetch_array($resultA)) {

				if($rsA[midScore] == null) {
					$midScore = "0";
				} else {
					$midScore = $rsA[midScore];
				}
				if($rsA[testScore] == null) {
					$testScore = "0";
				} else {
					$testScore = $rsA[testScore];
				}
				if($rsA[reportEA] > 0) {
					if($rsA[reportScore] == null) {
						$reportScore = "0";
					} else {
						$reportScore = $rsA[reportScore];
					}
				} else {
					$reportScore = '없음';
				}
				if($rsA[totalScore] == null) {
					$totalScore = "0";
				} else {
					$totalScore = $rsA[totalScore];
				}
				if($rsA[passOK] == 'Y') {
					$passOK = "수료";
					$rPrice = number_format($rsA[price]);
				} else {
					$passOK = "미수료";
					$rPrice = 0;
				}
		?>
        <tr>
					<td><?=$b?></td>
					<td><?=$rsA[userName]?></td>
					<td><?=$rsA[birth]?></td>
					<td><?=$rsA[progress]?></td>
					<td><?=$midScore?></td>
					<td><?=$testScore?></td>
					<td><?=$reportScore?></td>
					<td><?=$totalScore?></td>
					<td><?=$passOK?></td>
					<td><?=number_format($rsA[price])?></td>
					<??>
					<td><?=$rPrice?></td>    
        </tr>
			<? $b++; } $a++;?>    
      </table>
    </div>
		<? 
			}	
			$queryS = "SELECT
								(SELECT COUNT(*) FROM nynStudy WHERE companyCode='".$companyCode."' AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."' ".$qServiceType2.") AS totalCount,
								(SELECT COUNT(*) FROM nynStudy WHERE companyCode='".$companyCode."' AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."' ".$qServiceType2." AND passOK='Y') AS passOK,
								(SELECT SUM(price) FROM nynStudy WHERE companyCode='".$companyCode."' AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."' ".$qServiceType2.") AS totalPrice,
								(SELECT SUM(rPrice) FROM nynStudy WHERE companyCode='".$companyCode."' AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."' ".$qServiceType2." AND passOK='Y') AS totalRprice"; 
			$resultS = mysql_query($queryS);
			$rsS = mysql_fetch_array($resultS);
			//$resultDate00 = date("Y-m-d", strtotime($lectureEnd."+6day"));
			$resultDate00 = substr($inputDate,0,10);
			$resultDate01 = substr($resultDate00,0,4);
			$resultDate02 = substr($resultDate00,5,2);
			$resultDate03 = substr($resultDate00,8,2);
			$resultDate = $resultDate01."년 ".$resultDate02."월 ".$resultDate03."일";
		?>

    <div id="table03">
      <table class="table03">
        <tr>
					<th>총 수강인원</td>
					<td><?=$rsS[totalCount]?>명</td>
					<th>수료인원</td>
					<td><?=$rsS[passOK]?>명</td>
					<th>미수료인원</td>
					<td><?=$rsS[totalCount]-$rsS[passOK]?>명</td>
					<th>교육비</td>
					<td><?=number_format($rsS[totalPrice])?>원</td>
					<th>수료 환급액</td>
					<td><?=number_format($rsS[totalRprice])?>원</td>
        </tr>
      </table>
    </div>
    <div id="footer">
      위의 내용은 <?=$companyName?>의 사업주 직업능력개발 인터넷 원격훈련 수료 결과가 틀림없음을 증명합니다.<br /><br /><br /><br /><br />
      <?=$resultDate?><br />
      <img src="../images/admin/print_img.png" alt="이상에듀" />
    </div>
  </div>
</body>
</html>
