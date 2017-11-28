<? include '_header.php' ?>
<script type="text/javascript" src="../frontScript/studyModal.js"></script>
</head>

<body>
<? include '_gnb.php' ?>
<div id="wrap">
  <div id="contents">
    <div id="titleArea" style="background-image:url(../images/title_bg/<? echo $fileName[1] ?>.png);">
      <!-- 페이지 네비게이션 h2, 페이지 타이틀 h1, 일반 내용출력 h3 -->
      <h2>교육안내<img src="../images/global/icon_triangle.png" alt="▶" /><strong>교육이용 안내</strong></h2>
      <h1>교육이용 안내</h1>
    </div>
		<? include '_snb_eduinfo.php' ?>
    <div class="rule">
      <ol>
        <li>
          자동 수강, 제출 프로그램 방지 <span class="titleBlue">캡챠 시스템</span> 안내
          <ul>
            <li><strong class="blue">첫 차시</strong>와 이후 <strong class="blue">8차시</strong> 단위로 <strong class="blue">캡챠 인증</strong> 필요</li>
            <li><strong class="blue">중간평가</strong>, <strong class="blue">최종평가</strong>, <strong class="blue">과제제출</strong> 페이지 입장 전 <strong class="blue">하루 1번씩 인증</strong> 필요</li>
          </ul>
        </li>
        <li>
          <strong class="titleBlue">본인인증</strong> 안내
          <ul>
            <li>
              본인 인증을 위해 다음과 같은 페이지에서  <strong class="blue">휴대폰인증 혹은 아이핀 인증</strong>을 필요로 합니다.
              <ul>
                <li><span class="skyblue">최초 회원가입 시</span></li>
                <!-- <li>사업장 단체 가입으로 <span class="skyblue">아이디 자동 발급</span>되어 <span class="skyblue">최초 로그인 시</span></li> -->
                <li><span class="skyblue">최초 과정교육 시작 시</span></li>
              </ul>
            </li>
          </ul>
        </li>
        <li>
          사업주 지원 훈련 규정 <span class="titleBlue">일일진도제한</span> 안내
          <ul>
            <li>사업주 지원 훈련 규정 상 하루 <strong class="blue">최대 8차시 까지만 수강이 가능</strong>합니다.</li>
          </ul>
        </li>
        <li>
          강의 진행 시 <span class="titleBlue">주의사항</span>
          <ul>
            <li><strong class="blue">중간평가</strong>는 강의 전체차시 기준 <strong class="blue">50% 이상 되어야 응시가가능</strong>합니다. </li>
            <li>모든 수강과정의 <strong class="blue">최종 평가응시</strong>와 <strong class="blue">과제제출은 진도율이 80% 이상</strong> 되어야 가능합니다.</li>
            <li><strong class="blue">평가(중간/최종)</strong>와 <strong class="blue">과제</strong>는 <strong class="red">1회만</strong> <strong class="blue">응시 가능하며</strong>, <strong class="red">(재응시 불가)</strong> <strong class="blue">최종평가</strong>는 <strong class="red">응시 제한시간</strong>이 있습니다.</li>
						<li><strong class="blue">최종평가</strong>의 경우 <strong class="red">제한시간이 있으며 접속종료 등</strong>의 상태에서도 <strong class="red">중단없이 계속 흘러가게 됩니다.</strong></li>
          </ul>
        </li>
        <li class="list">
          과제 제출시 <span class="titleBlue">모사답안</span> 기준 및 처리 안내
          <ul>
            <li>
              모사답안 기준
              <ul>
                <li>과제에 있어 <span class="skyblue">타인의 답안을 그대로 복사</span>하거나 <span class="skyblue">일부 문구만을 수정</span>하여 <span class="skyblue">제출한 답안</span>을 말한다.<br />(모사답안 적용 대상 : 과제(레포트) )</li>
                <li>
                  모사답안 적용기준
                  <ol>
                    <li>
                      기본 정보나 <span class="skyblue">개요를 묻는 문제</span>, 답안이 동일할 수밖에 없는 <span class="skyblue">계산형, 실습형, 학습내용에서 발췌하는 내용은 적용하지 않는다.</span><br />
                      (단, 문제에서 제시되지 않은 조건 예를 들어, 도형의 위치, 선 굵기 등이 일치하는 경우는 모사답안 처리대상에 포함된다.) 
                    </li>
                    <li>문항별 및 <span class="skyblue">전체 답안이 90%이상 동일</span>한 경우 </li>
                    <li><span class="skyblue">오타, 띄어쓰기, 특수문자</span> 등 <span class="skyblue">내용이 유사하거나 동일</span>한 경우 </li>
                    <li><span class="skyblue">단락의 앞뒤 구성을 바꿔서 동일한 내용</span>의 답안을 제출한 경우 </li>
                    <li><span class="skyblue">사고력 측정형, 사례 제시형, 현업적용형</span>과 같은 문제 유형의 <span class="skyblue">답안이 80%이상 동일</span>한 경우 </li>
                  </ol>
                </li>
              </ul>
            </li>
            <li>
              모사답안 처리 방침
              <ul>
                <li>해당 문항 및 과제 0점 처리하며, <span class="skyblue">제출자와 답안 제공자 모두 0점 처리</span>된다. </li>
                <li>
                  모사답안 처리 절차 
                  <ol>
                    <li><span class="skyblue">모사체크 프로그램을 가동</span>한다. </li>
                    <li>첨삭을 진행할 시 <span class="skyblue">모사율이 90%이상인 학습자를 중점적으로 1차 확인</span>을 한다.</li>
                    <li>모사가 의심되는 학습자의 경우 <span class="skyblue">모사답안의심여부에 체크</span>하고, <span class="skyblue">채점기준에 맞게 첨삭을 진행</span>한 후 점수를 부여한다. </li>
                    <li>첨삭완료 후 <span class="skyblue">교육운영자가 2차 확인</span>을 한다. </li>
                    <li>모사답안이 의심되는 <span class="skyblue">학습자에게 통보</span>하고, <span class="skyblue">모사답안 여부를 최종 확인</span>한다. </li>
                    <li>모사답안인 경우 <span class="skyblue">교육운영자가 해당 문항 및 과제에 대해 0점 처리</span>한다.</li>
                  </ol>
                </li>
              </ul>
            </li>
          </ul>
        </li>
      </ol>
    </div>
    <!-- 동작호출부 -->
    <!-- //동작호출부 -->
  </div>
</div>

<? include '_footer.php' ?>