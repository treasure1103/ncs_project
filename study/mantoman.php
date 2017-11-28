<? include '../include/header.php' ?>
<script type="text/javascript">
//var boardType = 'study';
var boardType = '';
var page = '<?=$_GET[page]; ?>'; //검색 페이지
var seq = '<?=$_GET[seq]; ?>'; //검색 페이지
var modes = '';
$(document).ready(function(){
	GNBAct('userGNB');  
});
</script>
<script type="text/javascript" src="../frontScript/GNB.js"></script>
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

<body>
<? include '../include/gnb.php' ?>
<div id="wrap" class="<? echo $fileName[1] ?>">
  <? include '../include/lnb_'.$fileName[1].'.php' ?>  
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/title_bg/<? echo $fileName[1] ?>.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2><?=$_siteName?><img src="../images/global/icon_triangle.png" alt="▶" />내 강의실<img src="../images/global/icon_triangle.png" alt="▶" /><strong>상담신청이력</strong></h2>
      <h1>상담신청내역</h1>
    </div>
    <!-- 동작호출부 -->
    <div id="contentsArea" class="BBSWrite">
    </div>
    <!-- //동작호출부 -->
  </div>
</div>

<? include '../include/footer.php' ?>