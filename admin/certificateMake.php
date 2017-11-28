<? include '../lib/header.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>교육수료증</title>
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<style type="text/css">
@import url(http://fonts.googleapis.com/earlyaccess/nanummyeongjo.css);

/*
body { overflow:hidden; }
.printArea { margin:1cm; padding:0cm; font-family:'Nanum Myeongjo', serif; page-break-before:always; }
.printArea > h1, .printArea > h2, .printArea > p, .printArea > ul, .printArea > h3 { position:relative; }
.printArea > h1, .printArea > h2, .printArea > p { text-align:center; }
.printArea > h1 { margin-top:3cm; font-size:24pt; text-align:center; }
.printArea > ul, div#printArea > ul li { margin:0; padding:0; font-size:10pt; list-style:none; }
.printArea > ul { margin-top:3cm; margin-left:2.5cm; }
.printArea > ul li h1 { padding-right:5pt; display:inline-block; font-size:10pt; }
.printArea > ul li h1:after { content:':' }
.printArea > p { margin-top:1.5cm; font-size:12pt; line-height:18pt; font-weight:bold; text-align:center; }
.printArea > h2 { margin-top:5cm; font-size:15pt; text-align:center; }
.printArea > h3 { margin-top:1cm; text-align:center; }
.printArea > h3 img { width:6cm; }
.printArea > div { position:absolute; top:0; left:0; z-index:1; width:21cm; height:29.7cm;  }
.printArea > div img { position:absolute;  width:18cm; height:26.7cm; margin:1.5cm; }
*/
body { overflow:hidden; }
.printArea { margin:1cm; padding:0cm; font-family:'Nanum Myeongjo', serif; page-break-before:always; }
.printArea > h1, .printArea > h2, .printArea > p, .printArea > ul, .printArea > h3 { position:relative; }
.printArea > h1, .printArea > h2, .printArea > p { text-align:center; }
.printArea > h1 { margin-top:5cm; font-size:30pt; text-align:center; }
.printArea > ul, div#printArea > ul li { margin:0; padding:0; font-size:14pt; list-style:none; }
.printArea > ul { margin-top:2.5cm; margin-left:2.5cm; }
.printArea > ul li h1 { margin-top:0.05cm; padding-right:5pt; display:inline-block; font-size:14pt; }
.printArea > ul li h1:after { content:':' }
.printArea > p { margin-top:2cm; font-size:18pt; line-height:22pt; font-weight:bold; text-align:center; }
.printArea > h2 { margin-top:3cm; font-size:20pt; text-align:center; }
.printArea > h3 { margin-top:1cm; text-align:center; }
.printArea > h3 img { width:6cm; }
.printArea > img { position:absolute; width:19cm; height:26.7cm; margin-top:-3.5cm; margin-left:-0.2cm; }
</style>
</head>

<body>
<?
		$serviceType = $_GET['serviceType'];
		$companyCode = $_GET['companyCode'];
		$lectureStart = $_GET['lectureStart'];
		$lectureEnd = $_GET['lectureEnd'];
		$userID = $_SESSION['loginUserID'];

		if(!$userID) { // 세션이 없으면 접근 거부
			exit;
		}
		if($serviceType == 3) {
			$serviceTypeQ = "A.serviceType='3'";
			$typeText = "인터넷 훈련과정";
		} else {
			$serviceTypeQ = "A.serviceType='1' AND A.passOK='Y'";
			$typeText = "사업주 직업능력개발 훈련과정(인터넷 원격)";
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
							WHERE ".$serviceTypeQ." AND A.companyCode='".$companyCode."'
							AND lectureStart='".$lectureStart."' AND lectureEnd='".$lectureEnd."' ORDER BY B.userName"; 
			$result = mysql_query($query);
			$count = mysql_num_rows($result);

			$start01 = substr($lectureStart,0,4);
			$start02 = substr($lectureStart,5,2);
			$start03 = substr($lectureStart,8,2);
			$end01 = substr($lectureEnd,0,4);
			$end02 = substr($lectureEnd,5,2);
			$end03 = substr($lectureEnd,8,2);

			//$resultDate00 = date("Y-m-d", strtotime($lectureEnd."+6day"));
			$resultDate00 = substr($inputDate,0,10);
			$resultDate01 = substr($resultDate00,0,4);
			$resultDate02 = substr($resultDate00,5,2);
			$resultDate03 = substr($resultDate00,8,2);
			$resultDate = $resultDate01."년 ".$resultDate02."월 ".$resultDate03."일";

			$basename = array('강그림','권진희','김영선','김정은','노영소','문다슬','민혜영','송은해','신윤희','신희진','오빛나','윤지현','이미애','이수진','임성열','장미애','정신애','정신영','조승완','조예원','하지혜');
			$basebirth = array('910801','711118','800922','890317','870725','980907','790624','670127','920819','840605','930809','830721','660728','920425','650404','660713','701222','870710','850925','960608','870511');

			$rs = mysql_fetch_array($result);
			//while($rs = mysql_fetch_array($result)) {
				$i=0;
			while($i<count($basename)) {

?>
				<div class="printArea">
	                <img src="../images/study/print_img01.jpg" alt=""/>
					<h1>수&nbsp;&nbsp;&nbsp;&nbsp;료&nbsp;&nbsp;&nbsp;&nbsp;증</h1>
					<ul>
						<li><h1>소속회사&nbsp;&nbsp;</h1><?=$rs[companyName];?> <!--(<?=$rs[department];?>)--></li>
						<li><h1>성&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;명&nbsp;&nbsp;</h1><?=$basename[$i];?></li>
						<li><h1>생년월일&nbsp;&nbsp;</h1><?=$basebirth[$i];?></li>
						<li><h1>훈련과정&nbsp;&nbsp;</h1>병원종사자를 위한 핵심 직무(신입직원용)</li>
						<li><h1>훈련직종&nbsp;&nbsp;</h1><?=$typeText;?></li>
						<li><h1>훈련기간&nbsp;&nbsp;</h1><?=$start01;?>년 <?=$start02;?>월 <?=$start03;?>일&nbsp;~&nbsp;<?=$end01;?>년 <?=$end02;?>월 <?=$end03;?>일</li>
					</ul>
					<p>위 사람은 근로자직업훈련촉진법 제 14조의<br />규정에 의하여 위의 직업능력개발훈련과정을<br />수료하였으므로 이 증서를 수여합니다.</p>
					<h2>2016년 12월 16일</h2>
					<h3><img src="../attach/print/print_img02.png" alt="" /></h3>					
				</div>
<? $i++;
			}	
?>
</body>
</html>