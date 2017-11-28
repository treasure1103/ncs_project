<? include '../include/header.php' ?>
</head>

<body>
<? include '../include/gnb.php' ?>
<div id="wrap" class="<? echo $fileName[1] ?>">
  <? include '../include/lnb_'.$fileName[1].'.php' ?>  
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/title_bg/<? echo $fileName[1] ?>.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2><?=$siteName?><img src="../images/global/icon_triangle.png" alt="▶" />교육안내<img src="../images/global/icon_triangle.png" alt="▶" /><strong>위탁교육안내</strong></h2>
      <h1>위탁교육안내</h1>
    </div>
    <div class="eduinfoArea">
      <!--    
      <img src="../images/eduinfo/img_eduinfo00.png" alt="교육소개0" />
      <img src="../images/eduinfo/img_eduinfo01.png" alt="교육소개1" />
      <img src="../images/eduinfo/img_eduinfo02.png" alt="교육소개2" />
      <img src="../images/eduinfo/img_eduinfo03.png" alt="교육소개3" />
      <img src="../images/eduinfo/img_eduinfo04.png" alt="교육소개4" />
      -->
      <h1 style="margin:44px 0 0 44px">사업주 <span class="titleBlue">위탁교육</span>이란?</h1>
      <h2>사업주가 훈련비용을 부담하여 재직근로자, 채용예정자를 다른 훈련기관에 위탁하고 해당 훈련기관이 훈련실시,<br />훈련생관리 등을 직접 수행하는 직업능력개발훈련 입니다.</h2>
      <ol>
        <li>
          훈련<span class="titleBlue">대상</span>
          <ul>
            <li>고용보험 피보험자</li>
            <li>고용보험 피보험자가 아닌 자로서 해당 사업주에게 고용된 자</li>
            <li>해당 사업이나 그 사업과 관련되는 사업에서 고용하려는자(채용예정자)</li>
            <li>직업안정기관에 구직 등록한 자 </li>
          </ul>
        </li>
        <li>
          <span class="titleBlue">지원</span>절차
          <h2>사업주가 훈련비용을 부담하며 재직근로자 등을 대상으로 직업훈련을 실시하는 경우 고용노동부에서 훈련비 등을 지원합니다.</h2>
          <img src="../images/eduinfo/img_info01.png" alt="지원절차"  class="imgInfo01"/>
        </li>
        <li>
          <span class="titleBlue">지원</span>대상
          <h2>고용보험에 가입한 사업주로서 소속 근로자 등에게 사전에 고용노동부장관으로부터<br />과정인정을 받아 교육훈련을 직접 또는 위탁하여 실시하는 사업주</h2>
        </li>
        <li>
          사업주 훈련 <span class="titleBlue">지원내용</span>
          <p>
            ※ 훈련내용 및 대상에 따라 표준훈련비 비율 조정<br />
            ※ 단, 고시일 이전 인정받은 훈련과정은 종전 규정에 따름
          </p>
          <table>
            <colgroup>
              <col style="width:139px;"/>
              <col style="width:161px"/>
              <col />
            </colgroup>
            <tr>
              <th colspan="2">구분</th>
              <th>지원내용</th>
            </tr>
            <tr>
              <td rowspan="3">훈련비</td>
              <td>우선지원기업</td>
              <td class="tableLeft">훈련에 소요된 비용의 일부 지원<br />표준훈련비 : 훈련직종단가×조정계수×훈련시간×훈련인원×120%</td>
            </tr>
            <tr>
              <td>중견기업<br />(1000인 미만)</td>
              <td class="tableLeft">훈련에 소요된 비용의 일부 지원<br />표준훈련비 : 훈련직종단가×조정계수×훈련시간×훈련인원×80%</td>
            </tr>
            <tr>
              <td>대기업<br />(1000인 이상)</td>
              <td class="tableLeft">
                훈련에 소요된 비용의 일부 지원<br />표준훈련비 : 훈련직종단가×조정계수×훈련시간×훈련인원×50%
                <p>※ 단, 고용노동부장관이 고시한 금액을 초과할 수 없음</p>
              </td>
            </tr>
          </table>
        </li>
      </ol>
      <h1>사업주 훈련 <span class="titleBlue">지원한도</span></h1>
      <img src="../images/eduinfo/img_info02.png" alt="우선지원대상 기업 = 고용안정, 직업능력 개발사업 납부 보혐료 * 240%" class="imgInfo02"/><br />
      <img src="../images/eduinfo/img_info03.png" alt="대규모 기업 = 고용안정, 직업능력 개발사업 납부 보혐료 * 100%" class="imgInfo03"/>
    </div>
    <!-- 동작호출부 -->
    <!-- //동작호출부 -->
  </div>
</div>

<? include '../include/footer.php' ?>