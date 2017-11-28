<? include '../include/header.php' ?>
<script type="text/javascript">
var page = '<?=$_GET[page]; ?>'; //검색 페이지
var seq = '<?=$_GET[seq]; ?>'; //검색 페이지
$(document).ready(function(){
	GNBAct('userGNB');  
});
</script>
<script type="text/javascript" src="../frontScript/GNB.js"></script>
<script type="text/javascript" src="../frontScript/_global.js"></script>
<script type="text/javascript" src="../frontScript/_pager.js"></script>
<script type="text/javascript" src="../frontScript/userStudyOrder.js"></script>
</head>

<body>
<? include '../include/gnb.php' ?>
<div id="wrap" class="<? echo $fileName[1] ?>">
  <? include '../include/lnb_'.$fileName[1].'.php' ?>  
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/title_bg/study.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2><?=$_siteName?><img src="../images/global/icon_triangle.png" alt="▶" />내 강의실<img src="../images/global/icon_triangle.png" alt="▶" /><strong>수강신청내역</strong></h2>
      <h1>수강신청내역</h1>
      <h3 class="study">총 <strong class="blue">3</strong>개의 과정을 신청하셨습니다.</h3>
    </div>
    <!-- 동작호출부 -->
    <div id="contentsArea">
    </div>
    <!-- //동작호출부 -->
  </div>
</div>

<? include '../include/footer.php' ?>