<? include '_header.php' ?>
<?
if ($_GET['contentSel']) {
	$s1 = 0;
	$s2 = 0;
	$s3 = 0;
	$s4 = 0;
	$s5 = 0;
	$contentArr      = array();
	$userNameArr     = array();
	$inputDateArr    = array();
	$userIpArr       = array();
	$userIdArr       = array();
	$addItem02Arr    = array();
	$contentsCode = $_GET['contentSel'];

	$query = "select
					A.addItem02, B.contentsName, C.lectureStart, A.content, A.userID, A.userName, A.inputDate, A.userIP
				from
					nynBoard A
					left join nynContents B on A.addItem01 = B.contentsCode
					left join nynStudy C on C.contentsCode = A.addItem01
				where
					A.boardCode = '3' and A.addItem01='".$contentsCode."' group by A.seq";

	$result = mysql_query($query);

	while ($res = mysql_fetch_array($result)) {
		if($res['addItem02'] == 1) $s1 = $s1 + 1;
		else if($res['addItem02'] == 2) $s2 = $s2 + 1;
		else if($res['addItem02'] == 3) $s3 = $s3 + 1;
		else if($res['addItem02'] == 4) $s4 = $s4 + 1;
		else if($res['addItem02'] == 5) $s5 = $s5 + 1;

		array_push($contentArr,$res['content']);
		array_push($userNameArr,$res['userName']);
		array_push($inputDateArr,$res['inputDate']);
		array_push($userIpArr,$res['userIP']);
		array_push($userIdArr,$res['userID']);
		array_push($addItem02Arr,$res['addItem02']);
	}
}

?>
<script type="text/javascript">

var contentSel = '<?=$_GET[contentSel] ?>'
var userLevel = '<?=$_GET[userLevel]; ?>';
var serviceType = '<?=$_GET[serviceType]; ?>';
var page = '<?=$_GET[page]; ?>'; //검색 페이지
var seq = '<?=$_GET[seq]; ?>'; //검색 페이지
var s1 = "<?=$s1?>";
var s2 = "<?=$s2?>";
var s3 = "<?=$s3?>";
var s4 = "<?=$s4?>";
var s5 = "<?=$s5?>";
var contentArr   = eval(<?echo json_encode($contentArr)?>);
var userNameArr  = eval(<?echo json_encode($userNameArr)?>);
var inputDateArr = eval(<?echo json_encode($inputDateArr)?>);
var userIpArr    = eval(<?echo json_encode($userIpArr)?>);
var userIdArr    = eval(<?echo json_encode($userIdArr)?>);
var addItem02Arr = eval(<?echo json_encode($addItem02Arr)?>);


</script>
<script type="text/javascript" src="../frontScript/_sendData.js"></script>
<script type="text/javascript" src="../frontScript/_global.js"></script>
<script type="text/javascript" src="../frontScript/_category.js"></script>
<script type="text/javascript" src="../frontScript/_pager.js"></script>
<script type="text/javascript" src="../frontScript/_globalModal.js"></script>
<!--<script type="text/javascript" src="../frontScript/studyStatList.js"></script>-->
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
<script type="text/javascript" src="../frontScript/studyStat.js"></script>

</head>

<body>
<? include '_gnb.php' ?>
<div id="contents">
  <h1></h1>
  <div id="contentsArea">
  </div>
</div>
<? include '_footer.php' ?>