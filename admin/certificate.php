<? include '../lib/header.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
		$serviceType = $_GET['serviceType'];
		$companyCode = $_GET['companyCode'];
		$lectureStart = $_GET['lectureStart'];
		$lectureEnd = $_GET['lectureEnd'];
		$allPass = $_GET['allPass'];
		$userID = $_SESSION['loginUserID'];
		$sort02 = $_GET['sort02'];
		$contentsName = $_GET['contentsName'];
		$serviceTypeB = $_GET['serviceTypeB'];

		if(!$userID) { // 세션이 없으면 접근 거부
			exit;
		}
		
		$queryC = "select companyName from nynCompany where companyCode='".$companyCode."'";
		$resultC = mysql_query($queryC);
		$companyName2 = mysql_result($resultC,0,'companyName');
?>
<title><?=$companyName2?>_<?=$lectureStart?>_<?=$title?></title>
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<style type="text/css">
@import url(http://fonts.googleapis.com/earlyaccess/nanummyeongjo.css);
/*
body { margin:0 0 0 0.2cm; padding:0; }
.printArea { height:100%; padding:0cm; font-family:'Nanum Myeongjo', serif; page-break-before:always; }
.printArea > h1, .printArea > h2, .printArea > p, .printArea > ul, .printArea > h3 { position:relative; }
.printArea > h1, .printArea > h2, .printArea > p { text-align:center; }
.printArea > h1 { margin:0; padding-top:2.5cm; font-size:30pt; text-align:center; }
.printArea > ul, div#printArea > ul li { margin:0; padding:0; font-size:14pt; list-style:none; }
.printArea > ul { overflow:hidden; height:6cm; margin-top:2cm; margin-left:3cm; }
.printArea > ul li h1 { margin-top:0.05cm; padding-right:5pt; display:inline-block; font-size:14pt; }
.printArea > ul li h1:after { content:':' }
.printArea > p { margin-top:2cm; font-size:18pt; line-height:30pt; font-weight:bold; text-align:center; }
.printArea > h2 { margin-top:3cm; font-size:17t; }
.printArea > h3 { margin-top:1cm; text-align:center; }
.printArea > h3 img { width:6cm; }
.printArea > img { position:absolute; width:19cm; margin-left:-0.2cm; }*/

body, @page { size:A4; margin:0 auto; background:#fff; font-size:9pt; overflow:hidden; }
.printArea + div { page-break-before:always; }
.printArea { padding:0cm; font-family:'Nanum Myeongjo', serif; }
.printArea > h1, .printArea > h2, .printArea > p, .printArea > ul, .printArea > h3 { position:relative; box-sizing:border-box; }
.printArea > h1, .printArea > h2, .printArea > p { text-align:center; }
.printArea > h1 { margin:0; padding-top:15%; font-size:260%; text-align:center; }
.printArea > ul, div#printArea > ul li { margin:0; padding:0; font-size:100%; list-style:none; }
.printArea > ul { overflow:hidden; height:25%; margin-top:12%; margin-left:12%; z-index:10;}
.printArea > ul li h1 { margin-top:1%; padding-right:1%; display:inline-block; font-size:100%; }
.printArea > ul li h1:after { content:':' }
.printArea > p { margin-top:8%; font-size:140%; line-height:150%; font-weight:bold; text-align:center; }
.printArea > h2 { margin-top:12%; padding:0 11%; text-align:center; font-size:18pt; z-index:999; }
.printArea > h3 { margin-top:8%; text-align:center; }
.printArea > h3 img { width:38%; }
.printArea > img { position:absolute; width:96%; margin:0 2%; }

</style>
</head>

<body>

<?

		$query = "SELECT B.birth, F.companyName, F.marketerID, B.department,
							IF(ISNULL(B.userName),'입력오류',B.userName) AS userName, 
							IF(ISNULL(E.contentsName),'입력오류',E.contentsName) AS contentsName, E.contentsTime, 
							F.companyID, C.certificate, E.chapter
							FROM nynStudy AS A
							LEFT OUTER
							JOIN nynMember AS B ON A.userID=B.userID
							LEFT OUTER
							JOIN nynContents AS E ON A.contentsCode=E.contentsCode 
							LEFT OUTER
							JOIN nynCompany AS F ON A.companyCode=F.companyCode 
							LEFT OUTER
							JOIN nynStudyCenter AS C ON F.companyID=C.companyID
							WHERE 1 AND A.passOK='Y' AND A.companyCode='".$companyCode."'
							AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."' ORDER BY B.userName"; 
			$result = mysql_query($query);
			$count = mysql_num_rows($result);
			
			if($count == 0){ ?> 
				<script>
				alert('교육인원 중 수료자가 없습니다.');
				window.close();
				</script>
			<? exit;
			} else { ?> 
				<script>
					alert('총 <?=$count?> 건 / 인쇄 미리보기로 여백 조정 후 출력하시기 바랍니다.');
				</script>
			<?
			}


			$start01 = substr($lectureStart,0,4);
			$start02 = substr($lectureStart,5,2);
			$start03 = substr($lectureStart,8,2);
			$end01 = substr($lectureEnd,0,4);
			$end02 = substr($lectureEnd,5,2);
			$end03 = substr($lectureEnd,8,2);

			$resultDate = date("Y-m-d");
			$resultDate = explode('-',$resultDate);
			$resultDate = $resultDate[0].". ".$resultDate[1].". ".$resultDate[2];

			$companyCode01 = substr($companyCode,0,3);
			$companyCode02 = substr($companyCode,3,2);
			$companyCode03 = substr($companyCode,5,5);

		while($rs = mysql_fetch_array($result)) { ?>
			<div class="printArea">
				<img src="../images/study/print_img03.jpg" alt=""/>
				<h1>수&nbsp;&nbsp;&nbsp;&nbsp;료&nbsp;&nbsp;&nbsp;&nbsp;증</h1>
				<ul>
					<li><h1>성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명&nbsp;&nbsp;</h1><?=$rs['userName'];?></li>
					<li><h1>생년월일&nbsp;&nbsp;</h1><?=$rs['birth'];?></li>
					<li><br></li>
					<li><h1>소&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;속&nbsp;&nbsp;</h1><?=$rs['companyName'];?><span style="margin-left:5%">(사업자등록번호<?=$companyCode01?>-<?=$companyCode02?>-<?=$companyCode03?>)</span></li>
					<li><h1>훈련과정&nbsp;&nbsp;</h1><?=$rs['contentsName'];?></li>
					<li><h1>훈련기간&nbsp;&nbsp;</h1><?=$start01;?>.<?=$start02;?>.<?=$start03;?>&nbsp;~&nbsp;<?=$end01;?>.<?=$end02;?>.<?=$end03;?> (<?=$rs['contentsTime']?>H)
					<li><h1>교육장소&nbsp;&nbsp;</h1>인터넷 원격교육</li>
				</ul>
				<p>위 사람은 근로자 직업능력개발법<br /> 제 20조 및 24조의 규정에 의하여 본 교육원이<br /> 실시한 아래의 교육을 위 기간 동안에 성실히 수행하였기에<br />  본 증서를 수여합니다.</p>
				<h2><?=$resultDate?></h2>
				<h3><img src="../attach/print/print_stamp.png" alt="NCS이러닝센터" /></h3>					
			</div>
<?
			}	
?>
</body>
</html>