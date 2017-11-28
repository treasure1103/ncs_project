<? include '_header.php' ?>
<script type="text/javascript">
var page = '<?=$_GET[page]; ?>'; //검색 페이지
var seq = '<?=$_GET[seq]; ?>'; //검색 페이지
var boardCode = '<?=$_GET[boardCode]; ?>'; //검색 페이지

</script>
<script type="text/javascript" src="../lib/SmartEditor/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="../frontScript/_global.js"></script>
<script type="text/javascript" src="../frontScript/_category.js"></script>
<script type="text/javascript" src="../frontScript/_pager.js"></script>
<script type="text/javascript" src="../frontScript/_sendData.js"></script>
<script type="text/javascript" src="../frontScript/board.js"></script>
<script type="text/javascript" src="../frontScript/boardList.js"></script>
<script type="text/javascript" src="../frontScript/boardWrite.js"></script>
<script type="text/javascript" src="../frontScript/boardView.js"></script>
</head>

<body onload="gnbAct()">
<? include '_gnb.php' ?>
<div id="wrap">
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/studycenter/bg_bbs.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2>홈<img src="../images/global/icon_triangle.png" alt="▶" />고객지원<img src="../images/global/icon_triangle.png" alt="▶" /><strong></strong></h2>
      <h1></h1>
    </div>
	<ul id="snb">
	  <li onclick="top.location.href='bbs.php?boardCode=1'">공지사항</li>
	  <li onclick="top.location.href='bbs.php?boardCode=1'">자주묻는질문</li>
	  <li onclick="top.location.href='mantomanWrite.php'">1:1문의</li>
	</ul>
    <!-- 동작호출부 -->
    <div id="contentsArea">
    </div>
    <!-- //동작호출부 -->
  </div>
</div>

<? include '_footer.php' ?>