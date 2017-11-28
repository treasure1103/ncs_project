$(document).ready(function(){
	ajaxAct();
})

function ajaxAct(){
	var listAjax = $.get(useApi,function(data){
		var totalCount = data.totalCount;
		var lists = '';
		if (totalCount != 0){
			$.each(data.study, function(){
				lists += '<article id="list'+this.seq+'">';			

				//상단 타이틀
				lists += '<div ';
				if(this.mobile == 'Y'){
					lists += 'onclick="viewStudyDetail('+this.seq+',\''+this.contentsCode+'\','+this.lectureOpenSeq+')"';
				}else{
					lists += 'class="notMobile" onclick="alert(\'모바일을 지원하지 않는 과정입니다.\\n\\nPC로 수강하시기 바랍니다.\')"';
				}
				lists += '>';
				lists += '<button type="button" title="버튼"></button>';
				lists += '<h1>'+this.contentsName+'</h1>';
				lists += '<h2>수강기간 : '+this.lectureStart+' ~ '+this.lectureEnd+'</h2>';
				lists += '<ul>';
				lists += '<li>남은 수강일 <strong>'+this.leftDate+'</strong>일</li>';
				lists += '<li>강의 진도 <strong>'+this.nowChapter+'</strong>/'+this.allChapter+'</li>';
				lists += '<li>진도율 <strong>'+this.progress+'</strong>%</li>';
				lists += '</ul>';
				lists += '</div>';
				lists += '</article>';
			})
		} else {
			lists += '<article class="noList">강의가 없습니다.</article>';
		}
		$('#studyPage > section').html(lists);
		$('#studyPage hgroup h1 strong').html(totalCount);
		if(contentsCode != ''){
			viewStudyDetail(studySeq,contentsCode,lectureOpenSeq)
			top.location.href='#list'+studySeq;
		}
	})
}

function viewStudyDetail(studySeq,contentsCode,lectureOpenSeq,renew){
	
	studySeq = studySeq ? studySeq : '';
	contentsCode = contentsCode ? contentsCode : '';
	lectureOpenSeq = lectureOpenSeq ? lectureOpenSeq : '';
	var studyBlock = $('#list'+studySeq);
	
	if (studyBlock.has('ol').length != 0 && renew != 'Y'){
		studyBlock.children('ol').remove();
		studyBlock.removeClass('openStudy');
	}else{
		var studyDetails = $.get(chapterApi,'contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq,function(data){
			var totalCount = data.totalCount;
			var ContentsCode = data.contentsCode;
			var lectureOpenSeq = data.lectureOpenSeq;
			var studyDetails = '';
			var useMidTest = data.midStatus;
			var useFinTest = data.testStatus;
			var useReport = data.reportStatus;
			var sourceType = data.sourceType;
			var midTerm = Math.ceil(Number(totalCount)/2);
			if (totalCount != 0){
				if(data.certPass == 'Y' || data.serviceType == '3' || data.serviceType == '9' ){
					studyDetails += '<ol>';
					
					//강의 활성용 오늘날짜 호출
					var today = new Date(data.nowTime);
					var today2 = data.nowTime;
					var dd = today.getDate();
					if(dd <= 9){ dd = '0'+dd }
					var mm = today.getMonth()+1; //January is 0!
					if(mm <= 9){ mm = '0'+mm }
					var yy = today.getFullYear();
					today = yy+'-' + mm+'-'+dd;
					var todayCount = 0;
					var btnUse = 'Y';
					//var i = 0;
					var Sid = data.contentsCode + data.lectureStart.substr(2,2) + data.lectureStart.substr(5,2) + data.lectureStart.substr(8,2) + data.lectureEnd.substr(8,2);

					for (i = 0 ; i<totalCount; i++){
						if(data.progress[i].endTime != null){
							if(data.progress[i].endTime.substr(0,10) == today){
								todayCount ++;
							}
						}

						if(data.progress[i].lastPage == null) {
							var lastPage = 1;
						} else {
							var lastPage = data.progress[i].lastPage;
						}
						if(data.progress[i].mobileLastPage == null) {
							var mobileLastPage = 1;
						} else {
							var mobileLastPage = data.progress[i].mobileLastPage;
						}

						if (data.serviceType == 1 || data.serviceType == 9){  // 환급 과정일때만 평가 항목 출력
							if(i == midTerm){
								if(data.midStatus != 'Y'){
									btnUse = 'N'
								}
							}

							if( btnUse == 'Y' && todayCount <= 8 ){
								if(i%8 == 0 || i == 0){
									if(today2 >= '2016-12-16 23:00:00' && today2 <= '2016-12-17 15:00:00') {
										studyDetails += '<li onclick="studyPlay(\''+studySeq+'\',\''+ContentsCode+'\',\''+data.progress[i].chapter+'\',\''+lectureOpenSeq+'\',\'check\',\''+sourceType+'\',\''+Sid+'\',\''+lastPage+'\',\''+mobileLastPage+'\')">';
									} else {
										studyDetails += '<li onclick="top.location.href=\'captcha.php?type=study&chapter='+data.progress[i].chapter+'&contentsCode='+contentsCode+'&lectureOpenSeq='+lectureOpenSeq+'&studySeq='+studySeq+'\'">';
									}
								} else {
									if(data.progress[i].progress != 0){
										studyDetails += '<li onclick="studyPlay(\''+studySeq+'\',\''+ContentsCode+'\',\''+data.progress[i].chapter+'\',\''+lectureOpenSeq+'\',\'check\',\''+sourceType+'\',\''+Sid+'\',\''+lastPage+'\',\''+mobileLastPage+'\')">';
									}else{
										studyDetails += '<li onclick="studyPlay(\''+studySeq+'\',\''+ContentsCode+'\',\''+data.progress[i].chapter+'\',\''+lectureOpenSeq+'\',\'\',\''+sourceType+'\',\''+Sid+'\',\''+lastPage+'\',\''+mobileLastPage+'\')">';
									}
								}
								studyDetails += '<button type="button"><img src="../images/mobile/btn_play.png"></button>';

							}else{
								if(i == midTerm){
									if(data.midStatus != 'Y'){
										studyDetails += '<li onclick="alert(\'중간평가 응시완료 후 다음 차시 학습이 가능합니다. PC에서만 응시 가능합니다.\')">';
										studyDetails += '<button type="button"><img src="../images/mobile/btn_play.png"></button>';
									}
								} else {
									if(todayCount >= 8) { 
										studyDetails += '<li onclick="alert(\'사업주 직업능력개발훈련 지원규정 1일 학습 가능한 차시(8차시)를 초과하였습니다.\')">';
										studyDetails += '<button type="button"><img src="../images/mobile/btn_play.png"></button>';
									} else {
										studyDetails += '<li onclick="alert(\'전 차시의 진도율이 부족합니다. (80% 이상).\')">';
										studyDetails += '<button type="button"><img src="../images/mobile/btn_play.png"></button>';
									}
								}
							}
							studyDetails += '<h3><strong>'+data.progress[i].chapter+'</strong>차시<br /><span>'+data.progress[i].progress+'</span>%</h3>';
							studyDetails += '<h1>'+data.progress[i].chapterName+'</h1>';
							if(data.progress[i].endTime != null){
								studyDetails += '<h2>교육이수 시간 : '+data.progress[i].endTime+'</h2>';
							}
						} else { // 비환급
							if(btnUse == 'Y' && todayCount <= 8){
								if(data.progress[i].progress != 0){
									studyDetails += '<li onclick="studyPlay(\''+studySeq+'\',\''+ContentsCode+'\',\''+data.progress[i].chapter+'\',\''+lectureOpenSeq+'\',\'check\',\''+sourceType+'\',\''+Sid+'\',\''+lastPage+'\',\''+mobileLastPage+'\')">';
								}else{
									studyDetails += '<li onclick="studyPlay(\''+studySeq+'\',\''+ContentsCode+'\',\''+data.progress[i].chapter+'\',\''+lectureOpenSeq+'\',\'\',\''+sourceType+'\',\''+Sid+'\',\''+lastPage+'\',\''+mobileLastPage+'\')">';
								}
								studyDetails += '<button type="button"><img src="../images/mobile/btn_play.png"></button>';
							}else{
								if(todayCount >= 8) { 
									studyDetails += '<li onclick="alert(\'사업주 직업능력개발훈련 지원규정 1일 학습 가능한 차시(8차시)를 초과하였습니다.\')">';
									studyDetails += '<button type="button"><img src="../images/mobile/btn_play.png"></button>';
								} else {
									studyDetails += '<li onclick="alert(\'전 차시의 진도율이 부족합니다. (80% 이상).\')">';
									studyDetails += '<button type="button"><img src="../images/mobile/btn_play.png"></button>';
								}
							}
							studyDetails += '<h3><strong>'+data.progress[i].chapter+'</strong>차시<br /><span>'+data.progress[i].progress+'</span>%</h3>';
							studyDetails += '<h1>'+data.progress[i].chapterName+'</h1>';
							if(data.progress[i].endTime != null){
								studyDetails += '<h2>교육이수 시간 : '+data.progress[i].endTime+'</h2>';
							}
						}
						studyDetails += '</li>';
						if(data.progress[i].progress <= 79){
							btnUse = 'N'
						}else{
							//if (data.serviceType == 1 || data.serviceType == 9){  // 환급 과정일때만 평가 항목 출력
								if(todayCount >= 8){
									btnUse = 'N'
								} else {
									btnUse = 'Y'
								}
							//}
						}
					}
					studyDetails += '</ol>';
				} else {
					alert('최초 수강 시 본인인증절차를 거쳐야 합니다. 본인인증은 PC로만 가능합니다. PC로 접속하여 본인인증을 진행해주시기 바랍니다.');
				}
			}else{
				alert('로그아웃 상태입니다. 다시 로그인 해주세요.');
				location.href="/m/login.html";
			}
			if (renew == 'Y'){
				studyBlock.children('ol').remove();
			}
			studyBlock.addClass('openStudy');
			studyBlock.children('div').after(studyDetails);
			//특수차시
			var prologue = ''
			var epilogue = ''

			$.each(data.progress,function(){
				if(this.chapter >= 100 && this.chapter <= 109){
					prologue += '<li onclick="studyPlay(\''+studySeq+'\',\''+ContentsCode+'\',\''+this.chapter+'\',\''+lectureOpenSeq+'\',\'check\',\''+sourceType+'\',\''+Sid+'\',\''+data.progress[i].lastPage+'\')">';
					prologue += '<button type="button"><img src="../images/mobile/btn_play.png"></button>';
					prologue += '<h3><strong>-</strong><br /><span>'+this.progress+'</span>%</h3>';
					prologue += '<h1>'+this.chapterName+'</h1>';
					prologue += '<h2>교육이수 시간 : '+this.endTime+'</h2>';
					prologue += '</li>';
				}else if(this.chapter >= 110 && this.chapter <= 119){
					prologue += '<li onclick="studyPlay(\''+studySeq+'\',\''+ContentsCode+'\',\''+this.chapter+'\',\''+lectureOpenSeq+'\',\'check\',\''+sourceType+'\',\''+Sid+'\',\''+data.progress[i].lastPage+'\')">';
					epilogue += '<button type="button"><img src="../images/mobile/btn_play.png"></button>';
					epilogue += '<h3><strong>-</strong><br /><span>'+this.progress+'</span>%</h3>';
					epilogue += '<h1>'+this.chapterName+'</h1>';
					epilogue += '<h2>교육이수 시간 : '+this.endTime+'</h2>';
					epilogue += '</li>';
				}
			})
			if(prologue != ''){
				studyBlock.children('ol').prepend(prologue);
			}
			if(epilogue != ''){
				studyBlock.children('ol').append(epilogue);
			}
		})
	}
}

function studyPlay(studySeq, contentsCode, chapter, lectureOpenSeq, check, sourceType, Sid, MovePage, Page){
	$.get('../api/apiLoginUser.php',function(data){
		if(data.userID == '') {
			alert('로그아웃 상태입니다. 다시 로그인 해주세요.');
			location.href="/m/login.html";

		} else {
			if(sourceType=='book') {
				if(check != ''){
					if(confirm('3G/4G 환경에서는 데이터 요금이 발생할 수 있습니다.')==true){
						check = check
					}else{
						check = ''
						return;
					}
				}

				var Chasi = chapter < 10 ? '0' + chapter : chapter;
				var linkAddress = '/viewer/index.html?Sid='+Sid+'&Code='+contentsCode+'&Chasi='+Chasi+'&Page='+Page+'&MovePage='+MovePage+'&LectureOpenSeq='+lectureOpenSeq+'&PreView=N';
				top.location.href = linkAddress;

			} else if(sourceType=='html5') {
				if(check != ''){
					if(confirm('3G/4G 환경에서는 데이터 요금이 발생할 수 있습니다.\n\n이어보기를 하시겠습니까?')==true){
						check = check
					}else{
						check = ''
					}
					
				}
				var linkAddress = 'study_html.html?studySeq='+studySeq+'&contentsCode='+contentsCode+'&chapter='+chapter+'&lectureOpenSeq='+lectureOpenSeq+'&check='+check;
				top.location.href = linkAddress;

			} else {
				if(check != ''){
					if(confirm('3G/4G 환경에서는 데이터 요금이 발생할 수 있습니다.\n\n이어보기를 하시겠습니까?')==true){
						check = check
					}else{
						check = ''
					}
					
				}
				var linkAddress = 'study_view.html?studySeq='+studySeq+'&contentsCode='+contentsCode+'&chapter='+chapter+'&lectureOpenSeq='+lectureOpenSeq+'&check='+check;
				top.location.href = linkAddress;
			}
		}
	})
}