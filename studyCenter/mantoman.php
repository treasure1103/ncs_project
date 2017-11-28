<? include '_header.php' ?>
<script type="text/javascript">
var boardType = 'study';
var page = '<?=$_GET[page]; ?>'; //검색 페이지
var seq = '<?=$_GET[seq]; ?>'; //검색 페이지
var modes = '';
</script>
<script type="text/javascript" src="../lib/SmartEditor/js/HuskyEZCreator.js" charset="utf-8"></script>
<script type="text/javascript" src="../frontScript/_global.js"></script>
<script type="text/javascript" src="../frontScript/_category.js"></script>
<script type="text/javascript" src="../frontScript/_sendData.js"></script>
<script type="text/javascript" src="../frontScript/_pager.js"></script>
<script type="text/javascript" src="../frontScript/mantoman.js"></script>
<script type="text/javascript" src="../frontScript/mantomanList.js"></script>
<script type="text/javascript" src="../frontScript/mantomanWrite.js"></script>
<script type="text/javascript" src="../frontScript/mantomanView.js"></script>
</head>

<body onload="snbSel(4)">
<? include '_gnb.php' ?>
<div id="wrap">
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/studycenter/bg_bbs.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2>홈<img src="../images/global/icon_triangle.png" alt="▶" />내 강의실<img src="../images/global/icon_triangle.png" alt="▶" /><strong>상담신청이력</strong></h2>
      <h1>상담신청이력</h1>
    </div>
    <!-- 서브네이게이션 -->
    <? include '_snb_studyroom.php' ?>
    <!-- //서브네비게이션 -->
    <!-- 동작호출부 -->
    <div id="contentsArea" class="BBSWrite">
    </div>
    <!-- //동작호출부 -->
  </div>
</div>

<? include '_footer.php' ?>