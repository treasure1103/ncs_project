function openStudyModal(types){
	alert(types)
	//types : 중간평가=test01, 기말평가=test02, 레포트 = report, 시험주의사항=cautionTest01,cautionTest02 , 레포트주의사항=cautionReport,
	var studyModals = '';
	studyModals = '<div id="screenModal" style="display:none;">';
	//타이틀 노출
	studyModals = '<div class="titleArea">';
	studyModals = '<div>';
	if(types == 'cautionTest01' || types=='test01'){
		studyModals = '<img src="../images/study/img_test01.png" />';
		studyModals = '<h1>중간평가</h1>';
	}else if(types == 'cautionTest02' || types=='test02'){
		studyModals = '<img src="../images/study/img_test02.png" />';
		studyModals = '<h1>최종평가</h1>';
	}else{
		studyModals = '<img src="../images/study/img_report.png" />';
		studyModals = '<h1>과제제출</h1>';
	}
	//강의명 호출
	studyModals = '<h2>쉽게 풀어보는 동양고전</h2>';
	
	//타입에 따라 버튼 액션 호출 필요
	studyModals = '<button type="button"><img src="../images/study/btn_modalclose.png" /></button>';
	studyModals = '</div>';
	studyModals = '</div>';	
	
	//주의사항
	if(types == 'cautionTest01' || types == 'cautionTest02' || types == 'cautionReport' ){

		studyModals = '<div class="caution">';
		studyModals = '<img src="../images/study/img_notice_big.png" />';
		//주의사항
		studyModals = '<h1>시험 주의사항</h1>';
		studyModals = '<p>모든 수강과정의 평가응시와 과제제출은 진도율이 <strong>80% 이상</strong> 되어야 가능합니다.<br />교육생님의 부주의로 인한 평가 미응시와 과제 미제출에 관련해서는 재응시, 다시제출이 불가능합니다. </p>';
		studyModals = '<p>모든 수강과정의 평가응시와 과제제출은 진도율이 80% 이상 되어야 가능합니다.<br />교육생님의 부주의로 인한 평가 미응시와 과제 미제출에 관련해서는 재응시, 다시제출이 불가능합니다. </p>';
		studyModals = '<p>모든 수강과정의 평가응시와 과제제출은 진도율이 80% 이상 되어야 가능합니다.<br />교육생님의 부주의로 인한 평가 미응시와 과제 미제출에 관련해서는 재응시, 다시제출이 불가능합니다. </p>';
		studyModals = '</div>';
		studyModals = '<div class="agreeArea">';
		studyModals = '<input type="checkbox" id="agree" />';
		studyModals = '<label for="agree">위 사항을 모두 숙지하였으며, 공정하게 시험에 응시하겠습니다.</label>';
		studyModals = '</div>';
		studyModals = '<div class="btnArea">';
		studyModals = '<button><img src="../images/study/btn_dotest_big.png" /></button>';
		studyModals = '</div>';
		studyModals = '</div>';		
		$('#footer').after(studyModals);
		$('#screenModal').fadeIn('fast');
		
	}else if(types=='test01' || types=='test02'){
		studyModals = '<div class="cautionTest">';
		studyModals = '<div class="timer">';
		studyModals = '<span>남은시간</span>';
		studyModals = '<strong>52:23</strong>';
		studyModals = '</div>';
		studyModals = '<div class="textArea">';
		studyModals = '<h1>평가 주의 사항</h1>';
		studyModals = '<p>모든 수강과정의 평가응시와 과제제출은 <strong>진도율이 80%</strong> 이상 되어야 가능합니다.<br />교육생님의 부주의로 인한 평가 미응시와 과제 미제출에 관련해서는 재응시, 다시제출이 불가능합니다.</p>';
		studyModals = '</div>';
		studyModals = '</div>';
		studyModals = '<div class="testArea">';
		//답안지
		studyModals = '<ul>';
		studyModals = '<li onClick="">문제1<div>1</div></li>';
		studyModals = '<li onClick="">문제2<div>3</div></li>';
		studyModals = '<li onClick="">문제3<div>1</div></li>';
		studyModals = '<li onClick="">문제4<div>3</div></li>';
		studyModals = '<li onClick="">문제5<div>1</div></li>';
		studyModals = '<li onClick="">문제6<div>3</div></li>';
		studyModals = '<li onClick="">문제7<div>답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안</div></li>';
		studyModals = '<li onClick="">문제8<div>답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안</div></li>';
		studyModals = '<li onClick="">문제9<div>1</div></li>';
		studyModals = '<li onClick="">문제10<div>3</div></li>';
		studyModals = '<li onClick="">문제11<div>1</div></li>';
		studyModals = '<li onClick="">문제12<div>3</div></li>';
		studyModals = '<li onClick="">문제13<div>1</div></li>';
		studyModals = '<li onClick="">문제14<div>3</div></li>';
		studyModals = '<li onClick="">문제15<div>답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안</div></li>';
		studyModals = '<li onClick="">문제16<div>답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안</div></li>';
		studyModals = '<li onClick="">문제17<div>1</div></li>';
		studyModals = '<li onClick="">문제18<div>3</div></li>';
		studyModals = '<li onClick="">문제19<div>1</div></li>';
		studyModals = '<li onClick="">문제20<div>3</div></li>';
		studyModals = '<li onClick="">문제21<div>1</div></li>';
		studyModals = '<li onClick="">문제22<div>3</div></li>';
		studyModals = '<li onClick="">문제23<div>답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안</div></li>';
		studyModals = '<li onClick="">문제24<div>답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안</div></li>';
		studyModals = '<li onClick="">문제25<div>답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안답안</div></li>';
		studyModals = '</ul>';
		
		//문제영역
		studyModals = '<div>';
		studyModals = '<h1>문제 22.</h1>';
		studyModals = '<h2>울림엔터테인먼트 소속의 대한민국의 8인조 걸 그룹이다. 2014년 11월 10일 《어제처럼 굿나잇》을 선공개하고 이후 같은 달 12일 데뷔 쇼케이스를 통해 데뷔무대를 가졌다. 17일 첫 번째 정규앨범 《Girls Invasion》을 발매했다. 2015년 3월 3일 정규 1집 리패키지 앨범 《Hi~》을 발매했다. 2015년 9월 14일 《작별하나》를 선공개하고, 10월 1일 첫 번째 미니앨범 《Lovelyz8》을 발매한 가수는?</h2>';
		
		 // 객관식
		studyModals = '<ol>';
		studyModals = '<li><input type="radio" name="ans1" id="ans11" /><label for="ans11">러블리즈</label></li>';
		studyModals = '<li><input type="radio" name="ans1" id="ans12" /><label for="ans12">여자친구</label></li>';
		studyModals = '<li><input type="radio" name="ans1" id="ans13" /><label for="ans13">소녀시대</label></li>';
		studyModals = '<li><input type="radio" name="ans1" id="ans14" /><label for="ans14">AOA</label></li>';
		studyModals = '</ol>';
		  
		// 단답형
		studyModals = '<input type="text" value="정답을 입력해주세요" />';
		  
		// 서술형
		studyModals = '<textarea>정답을 입력해주세요</textarea>';
		studyModals = '</div>';
		//문제종료
		studyModals = '<div>';
		studyModals = '<div class="btnArea">';
		studyModals = '<button type="button" class="fLeft"><img src="../images/study/btn_lastsubmit.png" alt="최종제출" /></button>';
		studyModals = '<button type="button" class="fRight"><img src="../images/study/btn_nextexam.png" alt="다음문제" /></button>';
		studyModals = '<button type="button" class="fRight"><img src="../images/study/btn_prevexam.png" alt="이전문제" /></button>';
		studyModals = '</div>';
		studyModals = '</div>';
		
		$('#footer').after(studyModals);
		$('#screenModal').fadeIn('fast')
		
	}else if(types=='report'){
		studyModals = '<div class="cautionTest">';
		studyModals = '<div class="textArea">';
		studyModals = '<h1>평가 주의 사항</h1>';
		studyModals = '<p>모든 수강과정의 평가응시와 과제제출은 <strong>진도율이 80%</strong> 이상 되어야 가능합니다.<br />교육생님의 부주의로 인한 평가 미응시와 과제 미제출에 관련해서는 재응시, 다시제출이 불가능합니다.</p>';
		studyModals = '</div>';
		studyModals = '</div>';
		
		//문제영역
		studyModals = '<div class="reportArea">';
		studyModals = '<h1>문제.</h1>';
		studyModals = '<h2>울림엔터테인먼트 소속의 대한민국의 8인조 걸 그룹이다. 2014년 11월 10일 《어제처럼 굿나잇》을 선공개하고 이후 같은 달 12일 데뷔 쇼케이스를 통해 데뷔무대를 가졌다. 17일 첫 번째 정규앨범 《Girls Invasion》을 발매했다. 2015년 3월 3일 정규 1집 리패키지 앨범 《Hi~》을 발매했다. 2015년 9월 14일 《작별하나》를 선공개하고, 10월 1일 첫 번째 미니앨범 《Lovelyz8》을 발매한 가수는?</h2>';
		
		//등록문제 다운로드
		studyModals = '<a href="#">레포트문제 다운로드</a>';
		studyModals = '</div>';
		studyModals = '<div class="reportSubmit">';
		studyModals = '<ul>';
		studyModals = '<li class="select"><img src="../images/study/img_submitfile.png" alt="파일제출" /> 파일로 제출하기</li>';
		studyModals = '<li><img src="../images/study/img_sumitwrite.png" alt="직접작성" /> 직접작성하기</li>';
		studyModals = '</ul>';
		studyModals = '<div>';
		studyModals = '<ul>';
		studyModals = '<li class="select"><h1>현재 제출파일</h1><a href="#">답안제출파일</a></li>';
		studyModals = '<li><h1>다시 제출하기</h1><form><button type="button">파일 찾기</button><input type="file" /></form></li>';
		studyModals = '</ul>';
		studyModals = '<div>제출파일은 <strong>마지막에 제출한 파일로 저장되며</strong>, 반드시 모든 확인이 완료되시면 반드시 <strong>최종제출을 눌러주셔야 정상적인 제출</strong>이 완료됩니다.</div>';
		studyModals = '</div>';
		studyModals = '<div>';
		studyModals = '<textarea>입력한 레포트정보</textarea>';
		studyModals = '<div>제출파일은 <strong>마지막에 제출한 파일로 저장되며</strong>, 반드시 모든 확인이 완료되시면 반드시 <strong>최종제출을 눌러주셔야 정상적인 제출</strong>이 완료됩니다.</div>';
		studyModals = '</div>';
		studyModals = '</div>';
		studyModals = '<div class="btnArea">';
		studyModals = '<button><img src="../images/study/btn_save.png" /></button>';
		studyModals = '<button><img src="../images/study/btn_lastsubmit.png" /></button>';
		studyModals = '</div>';
		studyModals = '</div>';
		
		$('#footer').after(studyModals);
		$('#screenModal').fadeIn('fast')
	}
}