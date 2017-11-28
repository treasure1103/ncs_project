<? include '../include/header.php' ?>
<script type="text/javascript">
  $.get('../api/apiSiteInfo.php',function(data){
	  $('.textArea').html(data.agreement);
  })
</script>
</head>

<body>
<? include '../include/gnb.php' ?>
<div id="wrap" class="<? echo $fileName[1] ?>">
  <? include '../include/lnb_'.$fileName[1].'.php' ?>  
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/title_bg/<? echo $fileName[1] ?>.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2><?=$siteName?><img src="../images/global/icon_triangle.png" alt="▶" />이용약관<img src="../images/global/icon_triangle.png" alt="▶" /><strong>서비스이용약관</strong></h2>
      <h1>서비스 이용약관</h1>
    </div>
    <div class="textArea">
      
    </div>
    <!-- 동작호출부 -->
    <!-- //동작호출부 -->
  </div>
</div>

<? include '../include/footer.php' ?>