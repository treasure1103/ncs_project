<? include '../include/header.php' ?>
</head>

<body>
<? include '../include/gnb.php' ?>
<div id="wrap" class="<? echo $fileName[1] ?>">
  <? include '../include/lnb_'.$fileName[1].'.php' ?>  
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/title_bg/<? echo $fileName[1] ?>.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2><?=$siteName?><img src="../images/global/icon_triangle.png" alt="▶" />교육안내<img src="../images/global/icon_triangle.png" alt="▶" /><strong>교육진행절차</strong></h2>
      <h1>교육진행절차</h1>
    </div>
    <div class="logic">
      <!-- <img src="../images/eduinfo/img_logic01.png" alt="교육진행절차1" /> -->
      <!--img src="../images/eduinfo/img_logic02.png" alt="교육진행절차2" /-->
      <h1>위탁교육 <span class="titleBlue">진행순서</span></h1>
      <ol>
        <li>수강할 업체 사업자등록증을  이메일(<span><?=$_adminMail?></span>) 또는<br />팩스(<?=$_csFax?>)로 보내 주셔야 합니다.</li>
        <li>학습을 진행할 사이트에서 수강신청을 합니다.</li>
        <li>최종 확정자 명단을 기준으로 위탁계약서를 2부 작성하여<br />1부는 교육기관 이메일로 보내주세요.</li>
        <li>학습자는 수강기간에 맞춰 수강을 진행하며, 귀사는 문자,<br />이메일 등을 통해 학습을 독려합니다.</li>
        <li>수강이 종료되면 첨삭강사가 평가에 대한 첨삭을 진행합니다.</li>
        <li>첨삭이 완료되면 학습자들에게 평가결과를 안내합니다.</li>
        <li>학습자의 평가결과 확인 후 교육기관은 관할 한국산업인력공단에 수료보고를 합니다.</li>
      </ol>
    </div>
    <!-- 동작호출부 -->
    <!-- //동작호출부 -->
  </div>
</div>

<? include '../include/footer.php' ?>