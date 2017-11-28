var useApi = '../api/apiStudyRoom.php';
var chapterApi = '../api/apiStudyChapter.php';
var resultApi = '../api/apiResultMessage.php';
var studySeq = '';
var progressTime = null;
//var contentsCode = '';
//var lectureOpenSeq = '';
var userID = '';
	
function listAct(){
	var contents = '';
	contents += '<ul>';
	contents += '</ul>';
	$('#contentsArea').html(contents);
	ajaxAct();
}

function viewAct(){
	listAct();
}

function ajaxAct(){
	var listAjax = $.get(useApi,function(data){
		var totalCount = data.totalCount;
		var lists = '';
		if(totalCount != 0){
			$.each(data.study, function(){
				lists += '<li class="list'+this.seq+'">';
				lists += '<div class="summuryArea" onclick="viewStudyDetail('+this.seq+',\''+this.contentsCode+'\','+this.lectureOpenSeq+')">';
				lists += '<ul>';
				lists += '<li><h1>남은 수강일</h1><strong>'+this.leftDate+'</strong>일</li>';
				lists += '<li><h1>강의 진도</h1><strong>'+this.nowChapter+'</strong>/'+this.allChapter+'</li>';
				lists += '<li><h1>진도율</h1><strong class="totlaProgress01">'+this.progress+'</strong>%</li>';
				lists += '</ul>';
				lists += '<div></div>';
				lists += '<img src="'+this.previewImageURL+this.previewImage+'" alt="강의 이미지" />';
				lists += '<h1>'+this.contentsName+'</h1><br />';
				lists += '<h2>수강기간 : '+this.lectureStart+' ~ '+this.lectureEnd+'</h2><br />';
				lists += '<h3>첨삭강사 : '+this.tutorName
				if(this.mobile=='Y'){
					lists += '<strong>모바일 학습 가능</strong>'
				}
				lists += '</h3>';
				lists += '</div>';
				lists += '<button type="button" onclick="viewStudyDetail('+this.seq+',\''+this.contentsCode+'\','+this.lectureOpenSeq+')"></button>';
				lists += '</li>';
			})
		}else{
			lists += '<li class="noList">강의가 없습니다.</li>';
		}
		$('#contentsArea > ul').html(lists);
		$('#titleArea h3 strong.blue').html(totalCount);
		/*
		if(totalCount == 1){					
			viewStudyDetail(data.study[0].seq, data.study[0].contentsCode, data.study[0].lectureOpenSeq)
		}
		*/
	})
	.done(function(){
		if(lectureOpenSeq != ''){
			viewStudyDetail(seq, contentsCode, lectureOpenSeq)
		}
	})
}

function viewStudyDetail(studySeq,contentsCode,lectureOpenSeq,renew){
	$('#contentsArea > ul > li > ul, #contentsArea > ul > li > table').remove();
	$('#contentsArea > ul > li').removeClass('openClass');
	
	studySeq = studySeq ? studySeq : '';
	contentsCode = contentsCode ? contentsCode : '';
	lectureOpenSeq = lectureOpenSeq ? lectureOpenSeq : '';

	var studyBlock = $('.list'+studySeq);
	
	if (studyBlock.has('table').length != 0 && renew != 'Y'){
		studyBlock.children('ul,table').remove();
		studyBlock.removeClass('openClass');
	}else{
		var studyDetails = $.get(chapterApi,'contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq,function(data){
			var lectureOpenSeq = data.lectureOpenSeq;
			var lectureStart = data.lectureStart;
			var certPass = data.certPass
			
			if(certPass == 'Y' || data.serviceType == '3' || data.serviceType == '9' ){
				
				var today = data.nowTime.substr(0,10)
				/*
				var today = new Date(data.nowTime);
				var dd = today.getDate();
				if(dd <= 9){ dd = '0'+dd }
				var mm = today.getMonth()+1; //January is 0!
				if(mm <= 9){ mm = '0'+mm }
				var yy = today.getFullYear();
				today = yy+'-' + mm+'-'+dd;
				*/
				
				var totalCount = data.totalCount;
				var ContentsCode = data.contentsCode;				
				var studyDetails = '';
				var useMidTest = data.midStatus;
				var useFinTest = data.testStatus;
				var useReport = data.reportStatus;
				var sourceType = data.sourceType;
				
				if (totalCount != 0){
				
				/*수료기준, 평가응시항목 출력 */
				if (data.serviceType == 1 || data.serviceType == 9){
					studyDetails += '<table class="passCheck"><tr>';
					studyDetails += '<td colspan="6" class="title">수료기준</td>'
					studyDetails += '</tr>';
					studyDetails += '<th>수강정원</th>';
					studyDetails += '<th>총 진도율</th>';
					studyDetails += '<th>중간평가</th>';
					studyDetails += '<th>최종평가</th>';
					studyDetails += '<th>과제</th>';
					studyDetails += '</tr><tr>';
					studyDetails += '<td rowspan="2"><strong>'+data.limited+'</strong>명</td>';
					studyDetails += '<td rowspan="2"><strong>'+data.passProgress+'</strong>% 이상</td>';
					studyDetails += '<td>총&nbsp;<strong>';
					if(data.totalPassMid != 0){
						studyDetails += data.totalPassMid+'</strong>점 / <strong>'+data.midRate+'</strong>% 반영';
					}
					studyDetails += '</td>';
					studyDetails += '<td>총&nbsp;<strong>';
					if(data.totalPassTest != 0){
						studyDetails += data.totalPassTest+'</strong>점 / <strong>'+data.testRate+'</strong>% 반영';
					}
					studyDetails += '</td><td>';
					if(data.totalPassReport != 0){
						studyDetails += '총&nbsp;<strong>'+data.totalPassReport+'</strong>점 / <strong>'+data.reportRate+'</strong>% 반영';
					} else {
						studyDetails += '과제 없음';
					}
					studyDetails += '</td>';
					studyDetails += '</tr><tr>';
					if(data.totalPassReport != 0){
						studyDetails += '<td colspan="3">반영된 평가, 과제 점수 합산 <strong>'+data.passScore+'</strong>점 이상 (평가와 과제는 40점 미만 시 과락 적용)</td>';
					} else {
						studyDetails += '<td colspan="3">반영된 평가 합산 <strong>'+data.passScore+'</strong>점 이상 (평가 40점 미만 시 과락 적용)</td>';
					}
					studyDetails += '</tr></table>';
		
					//평가관련
					studyDetails += '<ul>';
					  //중간평가
					  var midStatus = '';
					  if(data.totalProgress <= 49 && data.midStatus != null){
						  midStatus = '<strong class="red">진도부족</strong>'
						  studyDetails += '<li class="middleTest" onClick="alert(\'진도율 50% 이상 응시 가능합니다.\')"><h1>중간평가</h1>';
						  studyDetails += midStatus;
						  studyDetails += '<br /><span>진도율 50%부터 응시가능</span>';
						  studyDetails += '</li>';
					  }else if(data.midStatus == null){
						  midStatus = '<strong>평가 없음</strong>'
						  studyDetails += '<li class="middleTest" onClick="alert(\'평가가 없습니다.\')"><h1>중간평가</h1>';
						  studyDetails += midStatus;
						  studyDetails += '<br /><span>평가가 없는 과정</span>';
						  studyDetails += '</li>';
					  }else{
						  var midLink ='';
						  var midComment = '';
						  if(data.midStatus == 'N' || data.midStatus == 'V') {
							  if( data.midCaptchaTime != null){							  
								  if(data.midCaptchaTime.substr(0,10) == today){
									  midLink = 'onClick="openStudyModal(\'mid\',\''+contentsCode+'\','+lectureOpenSeq+')"';
								  }else{
									  var captchaLink = 'captcha_re.php?type=mid&contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq;
									  midLink = 'onclick="openPopup(\''+captchaLink+'\')"';
								  }
							  }else if(data.serviceType != 1){
								  midLink = 'onClick="openStudyModal(\'mid\',\''+contentsCode+'\','+lectureOpenSeq+')"';
							  }else{
								  var captchaLink = 'captcha_re.php?type=mid&contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq;
								  midLink = 'onclick="openPopup(\''+captchaLink+'\')"';
							  }
							  if(data.midStatus == 'N'){
								  midStatus = '<strong class="blue">응시하기</strong>'
								  midComment = '<br /><span>응시마감 : '+data.lectureEnd.substr(0,10)+' 23:50</span>';
							  }else{
								  midStatus = '<strong class="blue">응시중</strong>'
								  midComment = '<br /><span>응시마감 : '+data.lectureEnd.substr(0,10)+' 23:50</span>';
							  }
						  } else if(data.midStatus == 'Y' || data.midStatus == 'C') {
							  midLink = 'onClick="resultAct(\'test\',\''+contentsCode+'\','+lectureOpenSeq+',\'mid\')"';
							  if(data.midStatus == 'Y'){
								  midStatus = '<strong class="red">응시완료</strong>'
							  }else if (data.midStatus == 'C'){
								  midStatus = '<strong class="red">'+data.midScore+'</strong>점';
							  }
							  midComment = '<br /><span>응시일 : '+data.midSaveTime.substr(0,10)+'</span>';
						  } else {
							  midStatus = '중간평가없음';
						  }
						  studyDetails += '<li class="middleTest" '+midLink+'><h1>중간평가</h1>';
						  studyDetails += midStatus;
						  studyDetails += midComment;
						  studyDetails += '</li>';
					  }
					  
					  //최종평가
					  var testStatus = '';
					  if(data.totalProgress <= 79 && data.testStatus != null){
						  testStatus = '<strong class="red">진도부족</strong>'
						  studyDetails += '<li class="lastTest" onClick="alert(\'진도율 80% 이상 응시 가능합니다.\')"><h1>최종평가</h1>';
						  studyDetails += testStatus;
						  studyDetails += '<br /><span>진도율 80%부터 응시가능</span>';
						  studyDetails += '</li>';
					  }else if(data.testStatus == null){
						  testStatus = '<strong>평가 없음</strong>'
						  studyDetails += '<li class="middleTest" onClick="alert(\'평가가 없습니다.\')"><h1>최종평가</h1>';
						  studyDetails += testStatus;
						  studyDetails += '<br /><span>평가가 없는 과정</span>';
						  studyDetails += '</li>';
					  }else{
						  var testLink = '';
						  var testComment = '';
						  if(data.testStatus == 'N') {
							  if( data.testCaptchaTime != null){
								  if(data.testCaptchaTime.substr(0,10) == today){
									  testLink = 'onClick="openStudyModal(\'final\',\''+contentsCode+'\','+lectureOpenSeq+')"';
								  }else{
									  var captchaLink = 'captcha_re.php?type=final&contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq;
									  testLink = 'onclick="openPopup(\''+captchaLink+'\')"';
								  }
							  }else if(data.serviceType != 1){
								  testLink = 'onClick="openStudyModal(\'final\',\''+contentsCode+'\','+lectureOpenSeq+')"';
							  }else{
								  var captchaLink = 'captcha_re.php?type=final&contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq;
								  testLink = 'onclick="openPopup(\''+captchaLink+'\')"';
							  }
							  //testLink = 'onClick="openStudyModal(\'test\',\''+contentsCode+'\','+lectureOpenSeq+')"';
							  testStatus = '<strong class="blue">응시하기</strong>'
							  testComment = '<br /><span>제한시간 : '+data.testTime+'분</span>'
						  } else if(data.testStatus == 'V') {
							  if(data.nowTime >= data.testEndTime) {
								  testLink = 'onClick="resultAct(\'test\',\''+contentsCode+'\','+lectureOpenSeq+',\'final\')"';
								  testStatus = '<strong class="red">응시완료</strong>'
								  testComment = '<br /><span>시간초과로 인한 응시종료</span>';
							  } else {
								  if(data.testCaptchaTime != null){
									  if( data.testCaptchaTime.substr(0,10) == today ){
										  testLink = 'onClick="openStudyModal(\'final\',\''+contentsCode+'\','+lectureOpenSeq+')"';
									  }else{
										  var captchaLink = 'captcha_re.php?type=final&contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq;
  										  testLink = 'onclick="openPopup(\''+captchaLink+'\')"';
									  }
								  }else if(data.serviceType != 1){
									  testLink = 'onClick="openStudyModal(\'final\',\''+contentsCode+'\','+lectureOpenSeq+')"';
								  }else{
									  var captchaLink = 'captcha_re.php?type=final&contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq;
									  testLink = 'onclick="openPopup(\''+captchaLink+'\')"';
								  }
								  testStatus = '<strong class="blue">응시중</strong>'
								  testComment = '<br /><span>'+data.testEndTime+' 까지</span>';
							  }
						  } else if(data.testStatus == 'Y' || data.testStatus == 'C') {
							  testLink = 'onClick="resultAct(\'test\',\''+contentsCode+'\','+lectureOpenSeq+',\'final\')"';
							  if(data.testStatus == 'Y'){
								  testStatus = '<strong class="red">응시완료</strong>'
							  }else if(data.testStatus == 'C'){
								  testStatus = '<strong class="red">'+data.testScore+'</strong>점';
							  }
							  testComment = '<br /><span>응시완료 : '+data.testSaveTime.substr(0,10)+'</span>';
						  } else {
							  testStatus = '최종평가없음';
						  }
						  //최종평가
						  studyDetails += '<li class="lastTest" '+testLink+'><h1>최종평가</h1>';
						  studyDetails += testStatus;
						  studyDetails += testComment;
						  studyDetails += '</li>';
					  }
					  
					  //과제제출
					  var reportStatus = '';
					  if(data.totalProgress <= 79 && data.reportStatus != null){
						  reportStatus = '<strong class="red">진도부족</strong>'
						  studyDetails += '<li class="report" onClick="alert(\'진도율 80% 이상 응시가능합니다.\')"><h1>과제제출</h1>';
						  studyDetails += reportStatus;
						  studyDetails += '<br /><span>진도율 80%부터 제출가능</span>';
						  studyDetails += '</li>';
					  }else if(data.reportStatus == null){
						  reportStatus = '<strong>과제 없음</strong>'
						  studyDetails += '<li class="report" onClick="alert(\'과제가 없습니다.\')"><h1>과제제출</h1>';
						  studyDetails += reportStatus;
						  studyDetails += '<br /><span>평가가 없는 과정</span>';
						  studyDetails += '</li>';
					  }else{
						  var reportComment = '';
						  if(data.reportStatus == 'N') {
							  if(data.reportCaptchaTime != null){
								  if( data.reportCaptchaTime.substr(0,10) == today ){
									  reportLink = 'onClick="openStudyModal(\'report\',\''+contentsCode+'\','+lectureOpenSeq+')"';
								  }else{
									  var captchaLink = 'captcha_re.php?type=report&contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq;
									  reportLink = 'onClick="openPopup(\''+captchaLink+'\')"';
								  }
							  }else if(data.serviceType != 1){
								  reportLink = 'onClick="openStudyModal(\'report\',\''+contentsCode+'\','+lectureOpenSeq+')"';
							  }else{
								  var captchaLink = 'captcha_re.php?type=report&contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq;
								  reportLink = 'onclick="openPopup(\''+captchaLink+'\')"';
							  }
							  reportStatus = '<strong class="blue">제출하기</strong>'
						  } else if(data.reportStatus == 'Y') {
							  reportLink = 'onClick="resultAct(\'report\',\''+contentsCode+'\','+lectureOpenSeq+',\'\')"';
							  reportStatus = '<strong class="red">제출완료</strong>'
							  reportComment = '<br /><span>제출일 : '+data.reportSaveTime.substr(0,10)+'</span>';
						  } else if(data.reportStatus == 'C') {
							  reportLink = 'onClick="resultAct(\'report\',\''+contentsCode+'\','+lectureOpenSeq+',\'\')"';
							  reportStatus = '<strong class="red">'+data.reportScore+'</strong>점';
							  reportComment = '<br /><span>제출일 : '+data.reportSaveTime.substr(0,10)+'</span>';
						  } else {
							  reportLink = '';
							  reportStatus = '과제없음';
							  reportComment += '<br /><span>평가가 없는 과정</span>';
						  }
						  studyDetails += '<li class="report" '+reportLink+'><h1>과제제출</h1>';
						  studyDetails += reportStatus;
						  studyDetails += reportComment;
						  studyDetails += '</li>';
					  }
					//
	
					studyDetails += '</ul>';

				} else {
					studyDetails += '<br />';
				}
					//포스트를 위한 변경
					var contentsData1 = 'seq='+studySeq+'&subDomains='+subDomain+'&sourceType='+sourceType+'&contentsCode='+ContentsCode+'&lectureOpenSeq='+lectureOpenSeq;
					var contentsData2 = studySeq+','+ContentsCode+','+lectureOpenSeq;
					//
					
					
					studyDetails += '<table>';
					studyDetails += '<colgroup><col width="90px" /><col width="*" /><col width="90px" /><col width="92px" /><col width="92px" /></colgroup>';
					var midTerm = Math.ceil(Number(totalCount)/2);
					//강의 활성용 오늘날짜 호출
	
					var todayCount = 0;
					var btnUse = 'Y';
					var alertMessage = '';
					for(i=0;i<totalCount;i++){					
						if(i != midTerm){
							if(data.progress[i].endTime != null){
								if(data.progress[i].endTime.substr(0,10) == today){
									todayCount ++;
								}
							}
							studyDetails += '<tr>';
							studyDetails += '<td>'+data.progress[i].chapter+'차시</td>';
							studyDetails += '<th><strong>'+data.progress[i].chapterName+'</strong><br />';
							if(data.progress[i].endTime != null){
								studyDetails += '교육이수 시간 : '+data.progress[i].endTime+'<br />';
								studyDetails += '접속아이피 : '+data.progress[i].studyIP+'</th>';
							}
							studyDetails += '<td>'+data.progress[i].progress+'%</td>';
							if(data.serviceType == 1 ){ // 사업주 규정 적용 대상자인 경우
								if(data.progress[i].progress != 0 && btnUse != 'N'){  // 이어보기 버튼
									studyDetails += '<td><button type="button" title="이어보기" ';
									if(i%8 == 0 || i == 0){ // 8차시마다 캡챠인증
										var captchaLink = 'captcha_re.php?player='+data.progress[i].player+'&type=study&chapter='+data.progress[i].chapter+'&contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq+'&studySeq='+studySeq+'&subDomain='+subDomain;
										studyDetails += 'onclick="openPopup(\''+captchaLink+'\')"';
										studyDetails += '"><img src="../images/study/btn_continuestudy.png" /></button>';
										studyDetails += '<button type="button" style="display:none;" class="runbutton'+studySeq+(i+1)+'Con" onclick="studyPop(this,\''+contentsData1+'\',\''+contentsData2+'\',\''+data.progress[i].player+'\',\''+sourceType+'\','+data.progress[i].chapter+',\'\')';
									}else{
										studyDetails += 'onclick="studyPop(this,\''+contentsData1+'\',\''+contentsData2+'\',\''+data.progress[i].player+'\',\''+sourceType+'\','+data.progress[i].chapter+',\'\')';
									}
									studyDetails += '"><img src="../images/study/btn_continuestudy.png" /></button></td>';
								}else{
									studyDetails += '<td>-</td>';
								}
								//if(data.progress[i].startTime != null && btnUse != 'N'){
								if(btnUse != 'N'){ // 수강하기 버튼
									studyDetails += '<td><button type="button" title="수강하기"';
									if(i%8 == 0 || i == 0){  // 8차시마다 캡챠인증
										var captchaLink = 'captcha_re.php?player='+data.progress[i].player+'&type=study&chapter='+data.progress[i].chapter+'&contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq+'&studySeq='+studySeq+'&subDomain='+subDomain;
										studyDetails += 'onclick="openPopup(\''+captchaLink+'\')"';
										studyDetails += '"><img src="../images/study/btn_study.png" /></button>';
										studyDetails += '<button type="button" style="display:none;" class="runbutton'+data.seq+(i+1)+'Con" onclick="studyPop(this,\''+contentsData1+'\',\''+contentsData2+'\',\''+data.progress[i].player+'\',\''+sourceType+'\','+data.progress[i].chapter+',\'new\')';
									}else{
										studyDetails += 'onclick="studyPop(this,\''+contentsData1+'\',\''+contentsData2+'\',\''+data.progress[i].player+'\',\''+sourceType+'\','+data.progress[i].chapter+',\'new\')';
									}
									studyDetails += '"><img src="../images/study/btn_study.png" /></button></td>';
								}else{
									/*
									if(data.progress[i].progress == '100') {
										studyDetails += '<td><button type="button" title="수강하기" onclick="studyPop(\''+data.progress[i].player+'\',\''+studySeq+'\',\''+ContentsCode+'\',\''+data.progress[i].chapter+'\',\''+lectureOpenSeq+'\',\'new\',\''+subDomain+'\',\''+sourceType+'\')"><img src="../images/study/btn_study.png" /></button></td>';
									} else {
									*/
										studyDetails += '<td><button type="button" title="수강하기" onclick="alert(\''+alertMessage+'\')"><img src="../images/study/btn_study.png" /></button></td>';
									//}
								}
							}else{ // 심사 또는 테스트, 비환급 수강인 경우 캡차 인증 제외
								if(data.progress[i].progress != 0 && btnUse != 'N'){  // 이어보기 버튼
									studyDetails += '<td><button type="button" title="이어보기" ';
									studyDetails += 'onclick="studyPop(this,\''+contentsData1+'\',\''+contentsData2+'\',\''+data.progress[i].player+'\',\''+sourceType+'\','+data.progress[i].chapter+',\'\')';
									studyDetails += '"><img src="../images/study/btn_continuestudy.png" /></button></td>';
								}else{
									studyDetails += '<td>-</td>';
								}
								if(btnUse != 'N'){ // 수강하기 버튼
									studyDetails += '<td><button type="button" title="수강하기"';
									studyDetails += 'onclick="studyPop(this,\''+contentsData1+'\',\''+contentsData2+'\',\''+data.progress[i].player+'\',\''+sourceType+'\','+data.progress[i].chapter+',\'new\')';
									studyDetails += '"><img src="../images/study/btn_study.png" /></button></td>';
								}else{
									if(data.progress[i].progress == '100') {
										studyDetails += '<td><button type="button" title="수강하기" onclick="studyPop(this,\''+contentsData1+'\',\''+contentsData2+'\',\''+data.progress[i].player+'\',\''+sourceType+'\','+data.progress[i].chapter+',\'new\')';
									} else {
										studyDetails += '<td><button type="button" title="수강하기" onclick="alert(\''+alertMessage+'\')"><img src="../images/study/btn_study.png" /></button></td>';
									}
								}
							}
							
							studyDetails += '</tr>';
							if(Number(data.progress[i].progress) < 80){
								btnUse = 'N';
								alertMessage = '전 차시의 진도율이 부족합니다. (80% 이상)';
							}else if(todayCount >= 8){
								btnUse = 'N';
								alertMessage = '사업주 직업능력개발훈련 지원규정 1일 학습 가능한 차시(8차시)를 초과하였습니다.';
							}else{
								btnUse = 'Y';
							}
						//}else if (i == midTerm && useMidTest != null){
						} else {
							if (data.serviceType == 1 || data.serviceType == 9){  // 환급 과정일때만 평가 항목 출력
							  studyDetails += '<tr class="testLine">';
							  studyDetails += '<td>[평가]</td>';
							  studyDetails += '<th class="blue"><strong>중간평가</strong>';

								if(data.midStatus == 'N' || data.midStatus == 'V'){
									studyDetails += '</th>';
								} else {
									studyDetails += '<br />평가응시 시간 : '+data.midSaveTime+'<br />';
									studyDetails += '접속아이피 : '+data.midIP+'</th>';
								}

							  studyDetails += '<td>'+midStatus+'</td>';
							  studyDetails += '<td>-</td>';
							  if(data.totalProgress <= 49 || data.midStatus == null){
								  studyDetails += '<td>-</td>';
							  }else {						  
								  studyDetails += '<td><button type="button" '+midLink+' title="평가응시">';
								  if(data.midStatus == 'N' || data.midStatus == 'V') {
									  studyDetails += '<img src="../images/study/btn_dotest.png" />';
								  }else{
									  studyDetails += '<img src="../images/study/btn_resuttest.png" />';
								  }
								  studyDetails += '</button></td>';
							  }
							  studyDetails += '</tr>';
							  midTerm = null;
							  if(data.midStatus == 'Y' || data.midStatus == 'C' ){
								if(todayCount >= 8){
									btnUse = 'N';
									alertMessage = '사업주 직업능력개발훈련 지원규정 1일 학습 가능한 차시(8차시)를 초과하였습니다.';
								}else{
									btnUse = 'Y';
								}
							  }else{
								  btnUse = 'N';
								  alertMessage = '중간평가 응시완료 후 다음 차시 학습이 가능합니다.';
							  }
							  i= i-1;
							} else {
								studyDetails += '<tr>';
								studyDetails += '<td>'+data.progress[i].chapter+'차시</td>';
								studyDetails += '<th><strong>'+data.progress[i].chapterName+'</strong><br />';
								if(data.progress[i].endTime != null){
									studyDetails += '교육이수 시간 : '+data.progress[i].endTime+'<br />';
									studyDetails += '접속아이피 : '+data.progress[i].studyIP+'</th>';
								}
								studyDetails += '<td>'+data.progress[i].progress+'%</td>';
								if(data.progress[i].progress != 0 && btnUse != 'N'){  // 이어보기 버튼
									studyDetails += '<td><button type="button" title="이어보기" ';
									studyDetails += 'onclick="studyPop(this,\''+contentsData1+'\',\''+contentsData2+'\',\''+data.progress[i].player+'\',\''+sourceType+'\','+data.progress[i].chapter+',\'\')';
									studyDetails += '"><img src="../images/study/btn_continuestudy.png" /></button></td>';
								}else{
									studyDetails += '<td>-</td>';
								}
								if(btnUse != 'N'){ // 수강하기 버튼
									studyDetails += '<td><button type="button" title="수강하기"';
									studyDetails += 'onclick="studyPop(this,\''+contentsData1+'\',\''+contentsData2+'\',\''+data.progress[i].player+'\',\''+sourceType+'\','+data.progress[i].chapter+',\'\')';
									studyDetails += '"><img src="../images/study/btn_study.png" /></button></td>';
								}else{
									if(data.progress[i].progress == '100') {
										studyDetails += '<td><button type="button" title="수강하기" onclick="studyPop(this,\''+contentsData1+'\',\''+contentsData2+'\',\''+data.progress[i].player+'\',\''+sourceType+'\','+data.progress[i].chapter+',\'new\')';
										studyDetails += '"><img src="../images/study/btn_study.png" /></button></td>';
									} else {
										studyDetails += '<td><button type="button" title="수강하기" onclick="alert(\''+alertMessage+'\')"><img src="../images/study/btn_study.png" /></button></td>';
									}
								}
									studyDetails += '</tr>';
									if(Number(data.progress[i].progress) < 80){
										btnUse = 'N';
										alertMessage = '전 차시의 진도율이 부족합니다. (80% 이상)';
									}else if(todayCount >= 8){
										btnUse = 'N';
										alertMessage = '사업주 직업능력개발훈련 지원규정 1일 학습 가능한 차시(8차시)를 초과하였습니다.';
									}else{
										btnUse = 'Y';
									}
							}
						}
					}

					if (data.serviceType == 1 || data.serviceType == 9){  // 환급 과정일때만 평가 항목 출력

						if(useFinTest != null){
							studyDetails += '<tr class="testLine">';
							studyDetails += '<td>[평가]</td>';
							studyDetails += '<th class="blue"><strong>최종평가</strong>';
								if(data.testStatus == 'N' || data.testStatus == 'V'){
									studyDetails += '</th>';
								} else {
									studyDetails += '<br />평가응시 시간 : '+data.testSaveTime+'<br />';
									studyDetails += '접속아이피 : '+data.testIP+'</th>';
								}
							studyDetails += '<td>'+testStatus+'</td>';
							studyDetails += '<td>-</td>';
							if(data.totalProgress <= 79 || data.testStatus == null){
								studyDetails += '<td>-</td>';
							}else{
								//studyDetails += '<td><button type="button" onClick="openStudyModal(\'final\',\''+contentsCode+'\','+lectureOpenSeq+')" title="평가응시"><img src="../images/study/btn_dotest.png" /></button></td>';
								studyDetails += '<td><button type="button" '+testLink+' title="평가응시">';
								if(data.testStatus == 'N' || data.testStatus == 'V') {
									studyDetails += '<img src="../images/study/btn_dotest.png" />';
								}else{
									studyDetails += '<img src="../images/study/btn_resuttest.png" />';
								}
								studyDetails += '</button></td>';
								//						
							}
							studyDetails += '</tr>';
						}
						if(useReport != null){
							studyDetails += '<tr class="testLine">';
							studyDetails += '<td>[과제]</td>';
							studyDetails += '<th class="blue"><strong>과제제출</strong>';
								if(data.reportStatus == 'N' || data.reportStatus == 'V'){
									studyDetails += '</th>';
								} else {
									studyDetails += '<br />과제제출 시간 : '+data.reportSaveTime+'<br />';
									studyDetails += '접속아이피 : '+data.reportIP+'</th>';
								}
							studyDetails += '<td>'+reportStatus+'</td>';
							studyDetails += '<td>-</td>';
							if(data.totalProgress <= 79 || data.reportStatus == null){
								studyDetails += '<td>-</td>';
							}else{
								//studyDetails += '<td><button type="button" onClick="openStudyModal(\'report\',\''+contentsCode+'\','+lectureOpenSeq+')" title="평가응시"><img src="../images/study/btn_dotest.png" /></button></td>';
								studyDetails += '<td><button type="button" '+reportLink+' title="과제제출">';
								if(data.reportStatus == 'N' || data.reportStatus == 'V') {
									studyDetails += '<img src="../images/study/btn_doreport.png" />';
								}else{
									studyDetails += '<img src="../images/study/btn_resutreport.png" />';
								}
								studyDetails += '</button></td>';
								//
							}
							studyDetails += '</tr>';
						}
					}

				}else{
					studyDetails += '<tr><td colspan="5">차시정보가 없습니다.</td></tr>';
				}
				studyDetails += '</table>';			
	
				if (renew == 'Y'){
					studyBlock.children('ul,table').remove();
				}
				studyBlock.addClass('openClass');
				studyBlock.children('div').after(studyDetails);
				
				//특수차시
				var prologue = ''
				var epilogue = ''
				
				var preTable = '<table><colgroup><col width="90px" /><col width="*" /><col width="160px" /><col width="92px" /></colgroup><tr>';
				var apTable = '</tr></table>'
				$.each(data.progress,function(){
					if(this.chapter >= 100 && this.chapter <= 109){
						prologue += '<tr>';
						prologue += '<td>-</td>';
						prologue += '<th><strong>'+this.chapterName+'</strong><br />';
						prologue += '<td class="Left"><strong class="red">진도율에 포함되지<br /> 않는 차시입니다.</strong></td>';
						prologue += '<td>';
						prologue += '<button type="button" title="수강하기" onclick="studyPop(this,\''+contentsData1+'\',\''+contentsData2+'\',\''+data.progress[i].player+'\',\''+sourceType+'\','+data.progress[i].chapter+',\'new\')"><img src="../images/study/btn_study.png" /></button>';
						prologue += '</td>';
						prologue += '</tr>';
					}else if(this.chapter >= 110 && this.chapter <= 119){
						epilogue += '<tr>';
						epilogue += '<td>-</td>';
						epilogue += '<th><strong>'+this.chapterName+'</strong><br />';
						epilogue += '<td class="Left"><strong class="red">진도율에 포함되지<br /> 않는 차시입니다.</strong></td>';
						epilogue += '<td>';
						epilogue += '<button type="button" title="수강하기" onclick="studyPop(this,\''+contentsData1+'\',\''+contentsData2+'\',\''+data.progress[i].player+'\',\''+sourceType+'\','+data.progress[i].chapter+',\'new\')"><img src="../images/study/btn_study.png" /></button>';
						epilogue += '</td>';
						epilogue += '</tr>';
					}
				})
				if(prologue != ''){
					studyBlock.children('ul').after(preTable + prologue + apTable);
				}
				if(epilogue != ''){
					studyBlock.children('button').before(preTable + epilogue + apTable);
				}
			}else{ //인증 받기 전
				certPassModal(lectureStart,studySeq,contentsCode,lectureOpenSeq);
			}
		})
  	}
}
/* 구형 get스터디
function studyPop(player,studySeq,contentsCode,chapter,lectureOpenSeq,types,subDomains,sourceType){
	popupAddress = player+'/player/popupStudy.php?seq='+studySeq+'&contentsCode='+contentsCode+'&chapter='+chapter+'&lectureOpenSeq='+lectureOpenSeq+'&types='+types+'&subDomain='+subDomains+'&sourceType='+sourceType;
	playerPop = window.open(popupAddress,"학습창","top=0,left=0,location=yes,menubar=no,status=no,titlebar=no,toolbar=no,scrollbar=no,resizeable=no","esangedu")
	playerPop.focus();
}
*/
function studyPop(obj,sendData1,sendData2,players,sourceType,chapter,types){
	$('.check').removeClass('check')
	var objLine = $(obj).parent('td').parent('tr')
	objLine.addClass('check');
	var checkChapter = Number(objLine.children('td').eq(0).html().replace('차시',''));
	var types = types ? types : '';	
	
	sendData1 += '&chapter='+chapter;
	//sendData1 += '&page='+page;
	if(types != ''){
		sendData1 += '&types='+types;
	}
	var player = window.open("","studyWindow","top=0,left=0,location=yes,menubar=no,status=no,titlebar=no,toolbar=no,scrollbar=no,resizeable=no","esangedu")

	var sendForm = '';
	//player += '';텍스트 변환식, player *= '';넘버 변환식
	players = String(players);

	sendForm += '<form method="post" style="display:none" action="'+players+'/player/popupStudy2.php" id="sendBookForm" target="studyWindow">';
	sendDatas = sendData1.split('&');
	for(var i in sendDatas){
		var sendDataInput = sendDatas[i].split('=');
		sendForm += '<input type="hidden" name="'+sendDataInput[0]+'" value="'+sendDataInput[1]+'" />';
	}		
	sendForm += '</form>';
	$('body').append(sendForm);
	$('#sendBookForm').submit();
	$('#sendBookForm').remove();
	progressCheck(sendData2,checkChapter)
	
}

function resultAct(types,contentsCode,lectureOpenSeq,testType){
	if(testType == '') {
		testType = 'report';
	}
	$.get(resultApi,'lectureOpenSeq='+lectureOpenSeq+'&testType='+testType,function(data){
		if(testType == 'mid') {
			alert('출제된 '+data.totalCount+'문항 모두 응시 완료하였습니다. 결과 보기는 학습이 종료된 이후 점수 확인 기간(1주일 이내)에 가능합니다.');
		} else if(testType == 'final') {
			if(data.totalCount == data.userCount) {
				alert('출제된 '+data.totalCount+'문항 모두 응시 완료하였습니다. 결과 보기는 학습이 종료된 이후 점수 확인 기간(1주일 이내)에 가능합니다.');
			} else {
				alert('출제된 '+data.totalCount+'문항 중 '+data.userCount+'문항 응시 완료하였습니다. 결과 보기는 학습이 종료된 이후 점수 확인 기간(1주일 이내)에 가능합니다.');
			}
		} else {
			alert('과제제출을 완료하였습니다. 결과 보기는 학습이 종료된 이후 점수 확인 기간(1주일 이내)에 가능합니다. ');
		}
	})
}

function openPopup(urlLink){
	popOpen = window.open(urlLink,"captcha","top=0,left=0,menubar=no,status=no,titlebar=no,toolbar=no,scrollbar=no,resizeable=no","esangStudy");
	popOpen.focus();
}

//post관련 업데이트
//1-진도변경
function progressCheck(checkData,checkChapter){
	clearInterval(progressTime);
	var checkData = checkData.split(',');
	var progressCheckTime = 10000; //진도체크시간
	//진도보내기 시간별
	progressTime = setInterval(function(){progressChange(checkData[0],checkData[1],checkData[2],checkChapter)},progressCheckTime)	
	//progressChange(checkData[0],checkData[1],checkData[2],checkChapter)  
}
function progressChange(studySeq,contentsCode,lectureOpenSeq,checkChapter){
	var check= $.get(chapterApi,'contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq,function(data){
		$('.list'+studySeq+' .totlaProgress01').html(data.totalProgress);
		var chapterProgress = data.progress[(checkChapter-1)].progress+'%'		
		$('.check td').eq(1).html(chapterProgress)
	})
}
//2-캡챠 완료시 강의 실행
function runStudy(runBtn,con){
	con = con ? con : '';
	var btnTarget = $('.runbutton'+runBtn);
	if(con!='new'){
		btnTarget = $('.runbutton'+runBtn+'Con')
	}
	btnTarget.click();
}