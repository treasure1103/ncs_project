<? include '../include/header.php' ?>
</head>

<body>
<? include '../include/gnb.php' ?>
<div id="wrap" class="<? echo $fileName[1] ?>">
  <? include '../include/lnb_'.$fileName[1].'.php' ?>  
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/title_bg/<? echo $fileName[1] ?>.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2>회사소개<img src="../images/global/icon_triangle.png" alt="▶" /><strong>튜터모집</strong></h2>
      <h1>튜터모집</h1>
    </div>
    <div class="designImage">
      <img src="../images/about/s18img.jpg" alt="튜터모집" />
    </div>
  </div>
</div>

<? include '../include/footer.php' ?>