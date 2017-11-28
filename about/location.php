<? include '../include/header.php' ?>
</head>

<body>
<? include '../include/gnb.php' ?>
<div id="wrap" class="<? echo $fileName[1] ?>">
  <? include '../include/lnb_'.$fileName[1].'.php' ?>  
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/title_bg/<? echo $fileName[1] ?>.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2>회사소개<img src="../images/global/icon_triangle.png" alt="▶" /><strong>찾아오시는 길</strong></h2>
      <h1>찾아오시는 길</h1>
    </div>
    <div class="location">
      <h1>location <span class="titleBlue">Map</span></h1>
      <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3164.2418392485533!2d126.85847221578095!3d37.52579607980549!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x357c9dd32d7caafb%3A0xf56683dbe52a1c99!2z7ISc7Jq47Yq567OE7IucIOyWkeyynOq1rCDsmKTrqqnroZwgMjA5!5e0!3m2!1sko!2skr!4v1506930408132" width="900" height="450" frameborder="0" style="border:0" allowfullscreen></iframe>
      <h1>location <span class="titleBlue">Information</span></h1>
      <div>
        <h1><strong class="titleBlue">address</strong><br />주소</h1>
        <img src="../images/about/bg_location_line.png" alt="선" />
        <p><strong>서울시 양천구 오목로 209 401호 (신정동, 신정빌딩)</strong><br /><?=$_siteName?></p>
      </div>
      <div>
        <h1><strong class="titleBlue">subway</strong><br />지하철 이용</h1>
        <img src="../images/about/bg_location_line.png" alt="선" />
        <p><strong>5호선 목동역에서 하차 1,8번 출구에서 걸어서 5분 거리<br />5호선 신정역에서 하차 2,3번 출구에서 걸어서 10분 거리</strong></p>
      </div>
      <div>
        <h1><strong class="titleBlue">tel number</strong><br />전화번호</h1>
        <img src="../images/about/bg_location_line.png" alt="선" />
        <p><strong>TEL : <?=$_csPhone?></strong><br />FAX : <strong><?=$_csFax?></strong><br /></p>
      </div>
      <div>
        <h1><strong class="titleBlue">email</strong><br />e메일</h1>
        <img src="../images/about/bg_location_line.png" alt="선" />
        <p><strong><?=$_adminMail?></strong></p>
      </div>
    </div>
    <!-- 동작호출부 -->
    <!-- //동작호출부 -->
  </div>
</div>

<? include '../include/footer.php' ?>
