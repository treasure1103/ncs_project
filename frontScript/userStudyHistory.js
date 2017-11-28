var useApi = '../api/apiStudyHistory.php';
var chapterApi = '../api/apiStudyChapter.php';
var studySeq = '';
var contentsCode = '';
var lectureOpenSeq = '';
var userID = '';

function listAct(){
	var contents = '';
	contents += '<ul>';
	contents += '</ul>';
	$('#contentsArea').html(contents);
	ajaxAct();
}

function ajaxAct(){
	var listAjax = $.get(useApi,function(data){
		var totalCount = data.totalCount;
		var totalScore = '';
		var passOK = '';
		var lists = '';
		if (totalCount != 0){
			$.each(data.study, function(){
				lists += '<li class="list'+this.seq+'">';
				lists += '<div class="summuryArea">';
				lists += '<ul>';

				if(this.totalScore == null) {
					totalScore = '0점';
				} else {
					totalScore = this.totalScore+'점';
				}				
				if(this.resultView == 'Y') {
					if(this.passOK == 'Y') {
						passOK = '<img src="/images/study/img_success.png" onclick="printPop('+this.seq+');" style="width:71px; height:60px; margin:6px 15px;" />';
					} else {
						passOK = '<strong class="red" style="font-size:15px;">미수료</strong>';
					}
				} else {
					passOK = '<strong class="red" style="font-size:15px;">채점중</strong>'
					totalScore ='채점중'; 
				}

				if (this.serviceType == 1 || this.serviceType == 9){ // 환급 또는 테스트과정인 경우
					lists += '<li><h1>수료여부</h1>'+passOK+'</li>';
					//lists += '<li><h1>복습가능일</h1><strong>'+this.leftDate+'</strong>일</li>';
					lists += '<li class="smallText"><h1>점수/진도율</h1><strong>'+totalScore+'</strong> /<br /><strong>'+this.progress+'</strong>%</li>';
					lists += '<li><h1>수강후기</h1><button type="button" class="epilogue" onClick="reviewPop(\''+this.contentsCode+'\')">작성하기</button></li>';
				} else {
					if(this.progress > 79) {  // 비환급 과정인 경우 진도율이 80%이상이면 수료
						passOK = '<img src="/images/study/img_success.png" onclick="printPop('+this.seq+');" style="width:71px; height:60px; margin:6px 15px;" />';
					} else {
						passOK = '<strong class="red" style="font-size:15px;">미수료</strong>';
					}
					lists += '<li><h1>수료여부</h1>'+passOK+'</li>';
					lists += '<li><h1>최종진도율</h1><strong>'+this.progress+'</strong>%</li>';
					lists += '<li><h1>수강후기</h1><button type="button" class="epilogue" onClick="reviewPop(\''+this.contentsCode+'\')">작성하기</button></li>';
					//lists += '<li><h1>복습가능일</h1><strong>'+this.leftDate+'</strong>일</li>';
				}
				lists += '</ul>';
				lists += '<div></div>';
				lists += '<img src="'+this.previewImageURL+this.previewImage+'" alt="강의 이미지" />';
				lists += '<h1 onclick="viewStudyDetail('+this.seq+',\''+this.contentsCode+'\','+this.lectureOpenSeq+')">'+this.contentsName+'</h1><br />';
				lists += '<h2>수강기간 : '+this.lectureStart+' ~ '+this.lectureEnd+'&nbsp;/&nbsp;복습기간 : '+this.lectureReStudy+'까지</h2><br />';
				lists += '<h3>첨삭강사 : '+this.tutorName
				if(this.mobile=='Y'){
					lists += '<strong>모바일 학습 가능</strong>'
				}
				lists += '</h3>';
				lists += '</div>';
				lists += '<button type="button" onclick="viewStudyDetail('+this.seq+',\''+this.contentsCode+'\','+this.lectureOpenSeq+')"></button>';
				lists += '</li>';
			})
		} else {
			lists += '<li class="noList">강의가 없습니다.</li>';
		}
		$('#contentsArea > ul').html(lists);
		$('#titleArea h3 strong.blue').html(totalCount);
	})
}

function viewStudyDetail(studySeq,contentsCode,lectureOpenSeq,renew){
	studySeq = studySeq ? studySeq : '';
	contentsCode = contentsCode ? contentsCode : '';
	lectureOpenSeq = lectureOpenSeq ? lectureOpenSeq : '';
	
	var studyBlock = $('.list'+studySeq);
	
	if (studyBlock.has('table').length != 0){
		studyBlock.children('ul,table').remove();
		studyBlock.removeClass('openClass');
	}else{
		var studyDetails = $.get(chapterApi,'contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq,function(data){
			var totalCount = data.totalCount;
			var ContentsCode = data.contentsCode;
			var lectureOpenSeq = data.lectureOpenSeq;
			var studyDetails = '';
			var useMidTest = data.midStatus;
			var useFinTest = data.testStatus;
			var useReport = data.reportStatus;
			
			var today = new Date(data.nowTime);
			var dd = today.getDate();
			if(dd <= 9){ dd = '0'+dd }
			var mm = today.getMonth()+1; //January is 0!
			if(mm <= 9){ mm = '0'+mm }
			var yy = today.getFullYear();
			today = yy+'-' + mm+'-'+dd;
			
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
					if(this.totalPassMid != 0){
						studyDetails += data.totalPassMid+'</strong>점 / <strong>'+data.midRate+'</strong>% 반영';
					}
					studyDetails += '</td>';
					studyDetails += '<td>총&nbsp;<strong>';
					if(this.totalPassTest != 0){
						studyDetails += data.totalPassTest+'</strong>점 / <strong>'+data.testRate+'</strong>% 반영';
					}
					studyDetails += '</td>';
					studyDetails += '<td>총&nbsp;<strong>';
					if(this.totalPassReport != 0){
						studyDetails += data.totalPassReport+'</strong>점 / <strong>'+data.reportRate+'</strong>% 반영';
					}
					studyDetails += '</td>';
					studyDetails += '</tr><tr>';
					studyDetails += '<td colspan="3">반영된 평가, 과제 점수 합산 <strong>'+data.passScore+'</strong>점 이상</td>';
					studyDetails += '</tr></table>';

					//평가관련
					studyDetails += '<ul>';
					
					//중간평가
					var midStatus = '';
					if(data.midStatus == null){
						midStatus = '<strong>평가 없음</strong>'
						studyDetails += '<li class="middleTest" onClick="alert(\'평가가 없습니다.\')"><h1>중간평가</h1>';
						studyDetails += '<strong class="red">평가없음</strong>'
						studyDetails += '<br /><span>평가가 없는 과정</span>';
						studyDetails += '</li>';
					}else{
						var midLink ='';
						var midComment = '';
							if(data.midStatus == 'Y' || data.midStatus == 'C' || data.midStatus == 'V'){
								if(data.midStatus == 'Y' || data.midStatus == 'V'){
									midLink = 'onClick="alert(\'채점완료가 되면 결과를 확인하실 수 있습니다.\')"';
									midStatus = '<strong class="red">응시완료</strong><br />채점 전';
								} else if( data.midStatus == 'C' ){ 
									if(data.resultView == 'Y') { // 채점 완료 상태에서만 결과 보여줌
										midLink = 'onClick="resultAct(\'test\',\''+contentsCode+'\','+lectureOpenSeq+',\'mid\')"';
										midStatus = '<strong class="red">'+data.midScore+'</strong>점<br />반영점수 : '+data.conversionMid+'점';
									} else {
										midLink = 'onClick="alert(\'채점완료가 되면 결과를 확인하실 수 있습니다.\')"';
										midStatus = '<strong class="red">응시완료</strong><br />채점 전';
									}
								}
							}else if(data.midStatus == 'N'){
								midStatus = '<strong class="red">미응시</strong>'
								midComment = '<br /><span>응시기록없음</span>';
							}
						studyDetails += '<li class="middleTest" '+midLink+'><h1>중간평가</h1>';
						studyDetails += midStatus;
						studyDetails += midComment;
						studyDetails += '</li>';
					}
					
					//최종평가
					var testStatus = '';
					if(data.testStatus == null){
						testStatus = '<strong>평가 없음</strong>'
						studyDetails += '<li class="middleTest" onClick="alert(\'평가가 없습니다.\')"><h1>최종평가</h1>';
						studyDetails += '<strong class="red">평가없음</strong>'
						studyDetails += '<br /><span>평가가 없는 과정</span>';
						studyDetails += '</li>';
					}else{
						var testLink = '';
						var testComment = '';
						if(data.testStatus == 'Y' || data.testStatus == 'C' || data.testStatus == 'V'){
							if(data.testStatus == 'Y' || data.testStatus == 'V'){
								testStatus = '<strong class="red">응시완료</strong><br />채점 전'
							}else if (data.testStatus == 'C'){
								if(data.resultView == 'Y') { // 채점 완료 상태에서만 결과 보여줌
									testLink = 'onClick="resultAct(\'test\',\''+contentsCode+'\','+lectureOpenSeq+',\'final\')"';
									testStatus = '<strong class="red">'+data.testScore+'</strong>점<br />반영점수 : '+data.conversionTest+'점';
								} else { 
									testLink = 'onClick="alert(\'채점완료가 되면 결과를 확인하실 수 있습니다.\')"';
									testStatus = '<strong class="red">응시완료</strong><br />채점 전';
								}
							}
						}else if(data.testStatus == 'N'){
							testStatus = '<strong class="red">미응시</strong>'
							testComment = '<br /><span>응시기록없음</span>';
						}
						
						//최종평가
						studyDetails += '<li class="lastTest" '+testLink+'><h1>최종평가</h1>';
						studyDetails += testStatus;
						studyDetails += testComment;
						studyDetails += '</li>';
					}
					
					//과제제출
					var reportStatus = '';
					var reportLink = '';
					var reportComment = '';
					if(data.reportStatus == null){
						reportStatus = '<strong>과제 없음</strong>'
						studyDetails += '<li class="report" onClick="alert(\'과제가 없습니다.\')"><h1>과제제출</h1>';
						studyDetails += '<strong class="red">과제없음</strong>'
						studyDetails += '<br /><span>과제가 없는 과정</span>';
						studyDetails += '</li>';
					}else{
						if(data.reportStatus == 'Y') {
							reportStatus = '<strong class="red">응시완료</strong>';
							reportComment = '<br /><span>채점 전</span>';

						} else if(data.reportStatus == 'C') { 
							if(data.resultView == 'Y') { // 채점 완료 상태에서만 결과 보여줌
								reportLink = 'onClick="resultAct(\'report\',\''+contentsCode+'\','+lectureOpenSeq+',\'\')"';
								reportStatus = '<strong class="red">'+data.reportScore+'</strong>점';
								if(data.reportCopy == 'Y') {
									reportStatus += ' - 모사답안';
								}
								reportComment = '<br />반영점수 : '+data.conversionReport+'점';
							} else { 
								reportLink = 'onClick="alert(\'채점완료가 되면 결과를 확인하실 수 있습니다.\')"';
								reportStatus = '<strong class="red">응시완료</strong>';
								reportComment = '<br /><span>채점 전</span>';
							}

						} else if(data.reportStatus == 'N') {
							reportStatus = '<strong class="red">미제출</strong>'
							reportComment = '<br /><span>제출기록없음</span>';
						}
						studyDetails += '<li class="report" '+reportLink+'><h1>과제제출</h1>';
						studyDetails += reportStatus;
						studyDetails += reportComment;
						studyDetails += '</li>';
					}
					studyDetails += '</ul>';
				
				} else {
					studyDetails += '<br />';
				}
				
				studyDetails += '<table>';
				studyDetails += '<colgroup><col width="90px" /><col width="*" /><col width="90px" /><col width="92px" /></colgroup>';
				//강의 활성용 오늘날짜 호출
				$.each(data.progress,function(){
					if(totalCount != 0){
						if(this.chapter <= 99){
							studyDetails += '<tr>';
							studyDetails += '<td>'+this.chapter+'차시</td>';
							studyDetails += '<th><strong>'+this.chapterName+'</strong><br />';
							if(this.endTime != null){
								studyDetails += '교육이수 시간 : '+this.endTime+'<br />';
								studyDetails += '접속아이피 : '+this.studyIP+'</th>';
							}
							studyDetails += '<td>'+this.progress+'%</td>';
							studyDetails += '<td>';
							
							if(data.nowTime.substr(0,10) > data.lectureReStudy){
								studyDetails += '<button type="button" title="수강하기" onclick="alert(\'복습기간이 지났습니다.\')"><img src="../images/study/btn_study.png" /></button>';
							} else {
								studyDetails += '<button type="button" title="수강하기" onclick="studyPop(\''+this.player+'\',\''+ContentsCode+'\',\''+this.chapter+'\')"><img src="../images/study/btn_study.png" /></button>';
							}
							studyDetails += '</td>';
							studyDetails += '</tr>';
						}
					}else{
						studyDetails += '<tr colspan="6"><td>교육과정이 없습니다.</td></tr>';
					}
				})
				studyDetails += '</table>';
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
						prologue += '<button type="button" title="수강하기" onclick="studyPop(\''+data.progress[i].player+'\',\''+ContentsCode+'\',\''+this.chapter+'\')"><img src="../images/study/btn_study.png" /></button>';
						prologue += '</td>';
						prologue += '</tr>';
					}else if(this.chapter >= 110 && this.chapter <= 119){
						epilogue += '<tr>';
						epilogue += '<td>-</td>';
						epilogue += '<th><strong>'+this.chapterName+'</strong><br />';
						epilogue += '<td class="Left"><strong class="red">진도율에 포함되지<br /> 않는 차시입니다.</strong></td>';
						epilogue += '<td>';
						epilogue += '<button type="button" title="수강하기" onclick="studyPop(\''+data.progress[i].player+'\',\''+ContentsCode+'\',\''+this.chapter+'\')"><img src="../images/study/btn_study.png" /></button>';
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
			}			
		})
  	}
}

function studyPop(player,contentsCode,chapter){
	popupAddress = player+'/player/popupConfirm.php?contentsCode='+contentsCode+'&chapter='+chapter;
	window.open(popupAddress,"학습창","top=0,left=0,menubar=no,status=no,titlebar=no,toolbar=no,scrollbar=no,resizeable=no","esangStudy")
}

function resultAct(types,contentsCode,lectureOpenSeq,testType){
	popupAddress = 'popupResult.php?types='+types+'&contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq+'&testType='+testType;
	window.open(popupAddress,"결과보기","menubar=no, status=no, titlebar=no, toolbar=no, scrollbars=yes, resizeable=no","previewContents")
}

function printPop(popseq){
	popupAddress = '../study/print.html?seq='+popseq;
	window.open(popupAddress,"결과보기","width=600, height=700, menubar=no, status=no, titlebar=no, toolbar=no, scrollbars=yes, resizeable=no","printPop")
}

function reviewPop(contentsCode){
	popupAddress = '../study/review.html?contentsCode='+contentsCode;
	window.open(popupAddress,"결과보기","top=0, left=0, width=480, height=500, menubar=no, status=no, titlebar=no, toolbar=no, scrollbars=yes, resizeable=no","reviewPop")
}