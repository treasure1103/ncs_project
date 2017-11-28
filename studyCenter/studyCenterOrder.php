<? include '_header.php' ?>
<script type="text/javascript">
var page = '<?=$_GET[page]; ?>'; //검색 페이지
var seq = '<?=$_GET[seq]; ?>'; //검색 페이지
</script>
<script type="text/javascript" src="../frontScript/_global.js"></script>
<script type="text/javascript" src="../frontScript/_pager.js"></script>
<script type="text/javascript" src="../frontScript/userStudyCenterOrder.js"></script>
<script type="text/javascript">
$(document).ready(function(){

})
</script>
</head>

<body onload="snbSel(3)">
<? include '_gnb.php' ?>
<div id="wrap" class="<? echo $fileName[1] ?>">
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/studycenter/bg_lecture.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2>홈<img src="../images/global/icon_triangle.png" alt="▶" />내 강의실<img src="../images/global/icon_triangle.png" alt="▶" /><strong>수강신청내역</strong></h2>
      <h1>수강신청내역</h1>
      <h3 class="study">총 <strong class="blue">3</strong>개의 과정을 신청하셨습니다..</h3>
    </div>
    <!-- 서브네이게이션 -->
    <? include '_snb_studyroom.php' ?>
    <!-- //서브네비게이션 -->
    <!-- 동작호출부 -->
    <div id="contentsArea">
    </div>
    <!-- //동작호출부 -->
  </div>
</div>

  