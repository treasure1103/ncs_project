<? include '../include/header.php' ?>

</head>

<body>
<? include '../include/gnb.php' ?>
<div id="wrap" class="<? echo $fileName[1] ?>">
  <? include '../include/lnb_'.$fileName[1].'.php' ?>  
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/title_bg/<? echo $fileName[1] ?>.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2>회사소개<img src="../images/global/icon_triangle.png" alt="▶" />회사소개</h2>
      <h1>회사소개</h1>
    </div>
    <div class="designImage"">
      <img src="../images/about/s11img.jpg" alt="소개이미지1" />
    </div>
    <!-- 동작호출부 -->
    <!-- //동작호출부 -->
  </div>
</div>

<? include '../include/footer.php' ?>