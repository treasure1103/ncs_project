<? include '../lib/header.php'; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv='cache-control' content='no-cache'>
<meta http-equiv='expires' content='0'>
<meta http-equiv='pragma' content='no-cache'>
<?
		$lectureDay = $_GET['lectureDay'];
		$lectureSE = EXPLODE('~',$lectureDay);
		$contentsCode = $_GET['contentsCode'];
		$companyCode = $_GET['companyCode'];

		$queryC = "select companyName from nynCompany where companyCode='".$companyCode."'";
		$resultC = mysql_query($queryC);
		$companyName2 = mysql_result($resultC,0,'companyName');
?>
<title><?=$companyName2?>_<?=$lectureSE[0]?>_설문조사 결과</title>
<script type="text/javascript" src="../js/jquery-1.11.2.min.js"></script>
<!-- JQ-PLOT의 기본 설정 -->  
<script type="text/javascript" src="../jqplot/jquery.jqplot.js"></script>
<!-- 파이차트 관련 -->
<script type="text/javascript" src="../jqplot/jqplot.pieRenderer.js"></script>
<script type="text/javascript" src="../jqplot/jqplot.donutRenderer.js"></script>
<!-- 바차트 관련 -->
<script type="text/javascript" src="../jqplot/jqplot.barRenderer.js"></script>
<script type="text/javascript" src="../jqplot/jqplot.categoryAxisRenderer.js"></script>
<!-- 포인트,라벨 관련 -->
<script type="text/javascript" src="../jqplot/jqplot.pointLabels.js"></script>
<script type="text/javascript" src="../jqplot/jqplot.canvasAxisLabelRenderer.js"></script>
<script type="text/javascript" src="../jqplot/jqplot.canvasTextRenderer.js"></script>
<link rel="stylesheet" href="../css/userStyle.css" />

<?
	$resultDate00 = substr($lectureSE[0],0,10);
	$resultDate01 = substr($resultDate00,0,4);
	$resultDate02 = substr($resultDate00,5,2);
	$resultDate03 = substr($resultDate00,8,2);
	$resultDate = $resultDate01."년 ".$resultDate02."월 ".$resultDate03."일자";

	if($lectureSE) {
		$qPlus .= " AND C.lectureStart='".$lectureSE[0]."' AND C.lectureEnd='".$lectureSE[1]."'";
		$qPlus2 .= " AND lectureStart='".$lectureSE[0]."' AND lectureEnd='".$lectureSE[1]."'";
	}

	if($contentsCode) {
		$qPlus .= " AND C.contentsCode='".$contentsCode."'";
		$qPlus2 .= " AND contentsCode='".$contentsCode."'";
	}

	if($companyCode) {
		$qPlus .= " AND C.companyCode='".$companyCode."'";
		$qPlus2 .= " AND companyCode='".$companyCode."'";
	}

	$queryA = " SELECT A.surveySeq,A.userAnswer, COUNT(A.userAnswer) AS userAnswerC, D.companyName FROM nynSurveyAnswer AS A
							LEFT OUTER
							JOIN nynLectureOpen AS B
							ON A.lectureOpenSeq=B.seq
							LEFT OUTER 
							JOIN nynStudy AS C
							ON A.lectureOpenSeq=C.lectureOpenSeq AND A.userID=C.userID 
							LEFT OUTER
							JOIN nynCompany AS D ON D.companyCode='".$companyCode."'
							WHERE 1 ".$qPlus."
							GROUP BY A.surveySeq,A.userAnswer HAVING A.userAnswer > 0 ORDER BY A.surveySeq, A.userAnswer";
	$resultA = mysql_query($queryA);
	$totalCount = 0;

	while($rsA = mysql_fetch_array($resultA)) {
		$companyName = $rsA['companyName'];
		if($rsA['surveySeq'] == 1) {
			$totalCount = $totalCount + $rsA['userAnswerC'];
		}

		${"data0".$rsA['surveySeq']."0".$rsA['userAnswer']} = $rsA['userAnswerC'];
	}

	$data0101 = ($data0101) ? $data0101 : 0;
	$data0102 = ($data0102) ? $data0102 : 0;
	$data0103 = ($data0103) ? $data0103 : 0;
	$data0104 = ($data0104) ? $data0104 : 0;
	$data0105 = ($data0105) ? $data0105 : 0;

	$data0201 = ($data0201) ? $data0201 : 0;
	$data0202 = ($data0202) ? $data0202 : 0;
	$data0203 = ($data0203) ? $data0203 : 0;
	$data0204 = ($data0204) ? $data0204 : 0;
	$data0205 = ($data0205) ? $data0205 : 0;

	$data0301 = ($data0301) ? $data0301 : 0;
	$data0302 = ($data0302) ? $data0302 : 0;
	$data0303 = ($data0303) ? $data0303 : 0;
	$data0304 = ($data0304) ? $data0304 : 0;
	$data0305 = ($data0305) ? $data0305 : 0;

	$data0401 = ($data0401) ? $data0401 : 0;
	$data0402 = ($data0402) ? $data0402 : 0;
	$data0403 = ($data0403) ? $data0403 : 0;
	$data0404 = ($data0404) ? $data0404 : 0;
	$data0405 = ($data0405) ? $data0405 : 0;

	$s1 = $data0101.",".$data0201.",".$data0301.",".$data0401;
	$s2 = $data0102.",".$data0202.",".$data0302.",".$data0402;
	$s3 = $data0103.",".$data0203.",".$data0303.",".$data0403;
	$s4 = $data0104.",".$data0204.",".$data0304.",".$data0404;
	$s5 = $data0105.",".$data0205.",".$data0305.",".$data0405;

	$b1 = $data0101.",".$data0102.",".$data0103.",".$data0104.",".$data0105;
	$c1 = $data0201.",".$data0202.",".$data0203.",".$data0204.",".$data0205;
	$d1 = $data0301.",".$data0302.",".$data0303.",".$data0304.",".$data0305;
	$e1 = $data0401.",".$data0402.",".$data0403.",".$data0404.",".$data0405;

	$queryT = "SELECT (
							SELECT COUNT(*)
							FROM nynStudy
							WHERE serviceType='1' ".$qPlus2.") AS totalStudy, (
							SELECT COUNT(*)
							FROM nynStudy
							WHERE serviceType='1' AND passOK='Y' ".$qPlus2.") AS totalPassOK";
	$resultT = mysql_query($queryT);
	$rsT = mysql_fetch_array($resultT);
	$passOK = ROUND((($rsT['totalPassOK']/$rsT['totalStudy'])*100),1);
	$passNO = 100 - $passOK;

	if($passOK > 98 && $passOK < 100) { // 98 이상이면 이상하게 오류가 남
		$passOK = 98;
		$passNO = 2;
	}
?>

<script type="text/javascript">
$(document).ready(function(){
	//파이차트 데이터
	var pieData = [['수료', <?=$passOK?>],['미수료', <?=ROUND($passNO,1)?>]];
	
	//혼합차트 데이터
	var s1 = [<?=$s1?>];
	var s2 = [<?=$s2?>];
	var s3 = [<?=$s3?>];
	var s4 = [<?=$s4?>];
	var s5 = [<?=$s5?>];
	
	//일반 차트1
	var b1 = [<?=$b1?>];
	//일반 차트2
	var c1 = [<?=$c1?>];
	//일반 차트3
	var d1 = [<?=$d1?>];
	//일반 차트4
	var e1 = [<?=$e1?>];
	
	
	//파이차트

	var plot1 = jQuery.jqplot ('piechart', [pieData], { 
		seriesDefaults: {
			renderer: jQuery.jqplot.PieRenderer, 
			rendererOptions: {
				showDataLabels: true,
				sliceMargin: 8
			}
		}, 
		legend: { show:true, location: 'e' }
		}
	);
	var ticks = ['매우그렇다', '그렇다', '보통이다', '그렇지 않다' , '매우 그렇지 않다'];
	
	//바챠트1
	var mixTicks = ['만족도', '학습 후<br />업무 도움', '사례 적절성', '교육서비스'];
	 
	var plot2 = $.jqplot('barchart01', [s1, s2, s3, s4, s5], {
		seriesDefaults:{
			renderer:$.jqplot.BarRenderer,
			pointLabels: { show: true }
		},
		axes: {
			xaxis: {
				renderer: $.jqplot.CategoryAxisRenderer,
				ticks: mixTicks
			}
		},
		highlighter: { show: false },
		legend: {
			show:true,
			renderer: $.jqplot.EnhancedLegendRenderer,
			labels: ticks
		}
	});

	//바차트 공통
	$.jqplot.config.enablePlugins = true;

	//바차트2
	 
	var plot3 = $.jqplot('barchart02', [b1], {
		seriesColors:['#85802b'],
		seriesDefaults:{
			renderer:$.jqplot.BarRenderer,
			pointLabels: { show: true }
		},
		axes: {
			xaxis: {
				renderer: $.jqplot.CategoryAxisRenderer,
				ticks: ticks
			}
		},
		highlighter: { show: false },
		trendline: { color: '#ff0000'}
	});
	
	//바차트3
	var plot4 = $.jqplot('barchart03', [c1], {
		seriesColors:['#00749F'],
		seriesDefaults:{
			renderer:$.jqplot.BarRenderer,
			pointLabels: { show: true }
		},
		axes: {
			xaxis: {
				renderer: $.jqplot.CategoryAxisRenderer,
				ticks: ticks
			}
		},
		highlighter: { show: false }
	})
	
	//바차트4
	var plot5 = $.jqplot('barchart04', [d1], {
		seriesColors:['#C7754C'],
		seriesDefaults:{
			renderer:$.jqplot.BarRenderer,
			pointLabels: { show: true }
		},
		axes: {
			xaxis: {
				renderer: $.jqplot.CategoryAxisRenderer,
				ticks: ticks
			}
		},
		highlighter: { show: false }
	})
	
	//바차트4
	var plot6 = $.jqplot('barchart05', [e1], {
		seriesColors:['#17BDB8'],
		seriesDefaults:{
			renderer:$.jqplot.BarRenderer,
			pointLabels: { show: true }
		},
		axes: {
			xaxis: {
				renderer: $.jqplot.CategoryAxisRenderer,
				ticks: ticks
			}
		},
		highlighter: { show: false }
	})
	
});
</script>
<!-- JQ-PLOT의 CSS를 설정 -->  
<link rel="stylesheet" type="text/css" href="../jqplot/jquery.jqplot.css"/>  
<style type="text/css">
  body { width:19cm; margin:0 auto; }
  body > h1, body > h2, body > h3, body > ul, body > ul li { overflow:hidden; margin:0; padding:0; text-align:center; }
  body > h1 { margin:15pt 0; font-size:20pt; }
  body > h2 { margin-bottom:6pt; font-size:14pt; }
  body > h3 { margin-bottom:20pt; font-size:16pt; }
  body > ul li { overflow:hidden; float:left; width:48%; margin:0 1% 15pt; }
  body > ul li > div { height:5.6cm; }
  body > ul li > h1 { overflow:hidden; height:30pt; margin:8pt 5pt 2pt; font-size:10pt; line-height:15pt; }
</style>
</head>
<body style="background:#fff;">
  <h1><?=$companyName2?></h1>
  <h2><?=$resultDate?> 수강 설문조사 결과</h2>
  <h3>[설문참여자 <?=$totalCount?>명]</h3>
  <ul>
    <li>
      <h1>수료율 원형통계</h1>
      <div id="piechart"></div>
    </li>
    <li>
      <h1>유형별 만족도</h1>
      <div id="barchart01"></div>
    </li>
    <li>
      <h1>1. 본 과정의 전반적인 학습내용에 만족하십니까?</h1>
      <div id="barchart02"></div>
    </li>
    <li>
      <h1>2. 본 과정의 학습내용이 본인의 업무 활용에<br />도움이 되십니까?</h1>
      <div id="barchart03"></div>
    </li>
    <li>
      <h1>3. 본 과정의 차시에서 제공되는 보기와<br />사례 구성이 적절합니까?</h1>
      <div id="barchart04"></div>
    </li>
    <li>
      <h1>4. 학습 시 교육에 대한 안내, 질의 응답, 학습 독려 등이<br />적절하게 이루어졌다고 생각하십니까?</h1>
      <div id="barchart05"></div>
    </li>
  </ul>
</div>
</body>