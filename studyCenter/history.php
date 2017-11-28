<? include '_header.php' ?>
<script type="text/javascript">
if(loginUserID == ''){
	top.location.href = '/main/'
}
var page = '<?=$_GET[page]; ?>'; //검색 페이지
var seq = '<?=$_GET[seq]; ?>'; //검색 페이지
</script>
<script type="text/javascript" src="../frontScript/_global.js"></script>
<script type="text/javascript" src="../frontScript/GNB.js"></script>
<script type="text/javascript" src="../frontScript/userStudyHistory.js"></script>
</head>

<body onload="snbSel(2)">
<? include '_gnb.php' ?>
<div id="wrap" class="study">
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/studycenter/bg_history.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2>홈<img src="../images/global/icon_triangle.png" alt="▶" />내 강의실<img src="../images/global/icon_triangle.png" alt="▶" /><strong>학습종료과정</strong></h2>
      <h1>학습종료과정</h1>
      <h3 class="study"><strong><?=$_SESSION['loginUserName'] ?></strong>님은 총 <strong class="blue">3</strong>개의 강의를 보셨습니다.</h3>
    </div>
    <!-- 서브네이게이션 -->
    <? include '_snb_studyroom.php' ?>
    <!-- //서브네비게이션 -->
    <div class="noticeArea">
      <img src="../images/study/img_notice.png" alt="주의" />
      <h1>안내 사항</h1>
      <p>과정별 복습기간까지 학습을 보실 수 있습니다. <strong>다만, 진도반영은 되지 않습니다.</strong></p>
    </div>
    <!-- 동작호출부 -->
    <div id="contentsArea">
    </div>
    <!-- //동작호출부 -->
  </div>
</div>
<iframe src='http://cont1.esangedu.kr/session/session.php?SESSID="<?=$_COOKIE["PHPSESSID"];?>"' width=0 height=0></iframe>
<? include '_footer.php' ?>