<? include '_header.php' ?>
<script type="text/javascript">
var page = '<?=$_GET[page]; ?>';
var seq = '<?=$_GET[seq]; ?>';
var sort01 = '<?=$_GET[sort01]; ?>';
var sort02 = '<?=$_GET[sort02]; ?>';
var contentsCode = '<?=$_GET[contentsCode]; ?>';
</script>
<script type="text/javascript" src="../frontScript/_global.js"></script>
<script type="text/javascript" src="../frontScript/_pager.js"></script>
<script type="text/javascript" src="../frontScript/_sendData.js"></script>
<script type="text/javascript" src="../frontScript/userContents.js"></script>
</head>

<body>
<? include '_gnb.php' ?>
<div id="wrap">
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/studycenter/bg_lecture.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2>홈<img src="../images/global/icon_triangle.png" alt="▶" />교육과정안내<img src="../images/global/icon_triangle.png" alt="▶" /><strong></strong></h2>
      <h1></h1>
      <h3>과정신청은 신청기간에만 가능합니다. 상세보기를 누르시면 상세한 내용을 보실 수 있습니다.</h3>
    </div>
    <!-- 동작호출부 -->
    <div id="contentsArea">
    </div>
    <!-- //동작호출부 -->
  </div>
</div>

<? if($subDomain[0] == "mooyoungcm"){ ?>
		<div style="margin-top:20px;"></div>
		</body>
	</html>
<? } else { 
	include '_footer.php' ;
} ?>