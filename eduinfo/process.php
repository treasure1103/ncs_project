<? include '../include/header.php' ?>
</head>

<body>
<? include '../include/gnb.php' ?>
<div id="wrap" class="<? echo $fileName[1] ?>">
  <? include '../include/lnb_'.$fileName[1].'.php' ?>  
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/title_bg/<? echo $fileName[1] ?>.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2><?=$siteName?><img src="../images/global/icon_triangle.png" alt="▶" />교육안내<img src="../images/global/icon_triangle.png" alt="▶" /><strong>과정개발절차</strong></h2>
      <h1>과정개발절차</h1>
    </div>
    <div class="designImage">
      <img src="../images/eduinfo/img_process01.png" alt="과정개발절차1" />
      <img src="../images/eduinfo/img_process02.png" alt="과정개발절차2" />
    </div>
    <!-- 동작호출부 -->
    <!-- //동작호출부 -->
  </div>
</div>

<? include '../include/footer.php' ?>