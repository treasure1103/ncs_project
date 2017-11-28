var modalMemberApi = '../api/apiMember.php';
var modalCompanyApi = '../api/apiCompany.php';
var modalCompanyStudyStatsApi = '../api/apiCompanyStudyStats.php';
var modalStudyApi = '../api/apiStudy.php';
var modalProgressApi = '../api/apiProgress.php';
var modalTestResultApi = '../api/apiStudyTestResult.php';
var modalReportResultApi = '../api/apiStudyReportResult.php';
var modalContentsApi = '../api/apiContents.php';
var modalOrderApi = '../api/apiOrder.php';
var modalSurveyApi = '../api/apiSurveyAnswer.php';
var modalLoginHistoryApi = '../api/apiLoginHistory.php';	//로그인 정보 api

function globalModalAct(types,modalSeq,eachID,option){
	var modalWrite =''
	modalWrite +='<div id="modal">';

	//회원정보 조회
	if(types == 'memberView'){
		var loginTime = '';
		modalWrite += '<div class="memberView">';
		modalWrite += '<h1><strong>회원정보</strong><button type="button" onClick="modalClose()"><img src="../images/admin/btn_close.png" alt="닫기" /></button></h1>';
		$.get(modalMemberApi,{'seq':modalSeq,'userID':eachID},function(data){
			$.each(data.member,function(){
				modalWrite +='<div>';
				modalWrite +='<div class="BBSWrite">';
				modalWrite +='<h1>기본정보</h1>';				
				//수험생정보				
				modalWrite +='<ul>';
				
				modalWrite +='<li>';
				//이름
				modalWrite +='<div class="halfDiv"><h1>이름</h1>';
				modalWrite += this.userName;
				modalWrite += '/ ID : '+this.userID;
				modalWrite +='</div>';
				//생년월일,성별
				modalWrite +='<div class="halfDiv"><h1>생년월일/성별</h1>';
				modalWrite += this.birth+'&nbsp;/&nbsp;';
				if(this.sex==1){
					modalWrite += '남'
				}else{
					modalWrite += '여'
				}
				modalWrite +='</div>';
				modalWrite +='</li>';
				
				modalWrite +='<li>';
				//최근로그인				
				if(this.loginTime==null){
					loginTime = '로그인 기록이 없습니다.';
				}else{
					loginTime = this.loginTime;
				}
				modalWrite +='<div class="halfDiv"><h1>최근로그인</h1>';
				modalWrite += loginTime;
				modalWrite +='</div>';
				//회원가입일
				modalWrite +='<div class="halfDiv"><h1>회원가입일</h1>';
				modalWrite += this.inputDate;
				modalWrite +='</div>';
				modalWrite +='</li>';
				
				modalWrite +='<li>';
				//휴대폰		
				modalWrite +='<div class="halfDiv"><h1>휴대폰</h1>';
				modalWrite += this.mobile01+'&nbsp;-&nbsp;'+this.mobile02+'&nbsp;-&nbsp;'+this.mobile03;
				if(this.smsReceive=='Y'){
					modalWrite += '&nbsp;(동의)'
				}else{
					modalWrite += '&nbsp;(거부)'
				}
				modalWrite +='</div>';
				//회원가입일
				modalWrite +='<div class="halfDiv"><h1>email</h1>';
				modalWrite += this.email01+'@'+this.email02;
				if(this.emailReceive=='Y'){
					modalWrite += '&nbsp;(동의)'
				}else{
					modalWrite += '&nbsp;(거부)'
				}
				modalWrite +='</div>';
				modalWrite +='</li>';
				
				//주소
				if(this.address01 == null) {
					var address01 = '';
				} else {
					var address01 = this.address01;
				}
				if(this.address02 == null) {
					var address02 = '';
				} else {
					var address02 = this.address02;
				}
				modalWrite +='<li><h1>주소</h1><div class="normalText">';
				modalWrite += this.zipCode+'&nbsp;)&nbsp;'+address01+'<br />'+address02;
				modalWrite +='</div></li>';
				modalWrite +='</ul>';
				
				//수험생정보
				modalWrite +='<h1>소속정보</h1>';
				modalWrite +='<ul>';
				if(this.company.companyCode == 0000000000){
					modalWrite +='<li style="text-align:center">일반회원입니다.</li>';
				}else{
					//회사명 사업자번호
					modalWrite +='<li><div class="halfDiv">';
					modalWrite +='<h1>회사명</h1>';
					modalWrite +=this.company.companyName +'&nbsp;/&nbsp;'+ this.company.companyID;
					modalWrite +='</div>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>사업자번호</h1>';
					modalWrite +=this.company.companyCode;
					modalWrite +='</div>';
					modalWrite +='</li>';
					//주소
					modalWrite +='<li><h1>주소</h1><div class="normalText">';
					modalWrite += /*this.comapny.zipCode+'&nbsp;)&nbsp;'+*/this.company.address01+'<br />'+this.company.address02;
					modalWrite +='</div></li>';
				}
				modalWrite +='</ul>';
				//modalWrite +='</div>';
				
				if(this.userLevel.userLevel != '7') {
					modalWrite +='<div class="BBSList scrollDiv" style="height:370px; margin:10px 0;">';				
					modalWrite +='<table><thead><tr>';
					modalWrite +='<th style="width:130px">기간</th>';
					modalWrite +='<th>과정명</th>';
					modalWrite +='<th style="width:80px;">인증</th>';
					modalWrite +='<th style="width:60px;">진도율</th>';
					modalWrite +='<th style="width:120px;">중간/최종/과제</th>';
					modalWrite +='<th style="width:80px;">수료여부</th>';
					modalWrite +='</tr></thead><tbody>';
					modalWrite +='</tbody></table>';
					modalWrite +='</div>';
				}

				//20170428 이응민 추가 ->
				modalWrite +='<h1>로그인 이력 정보</h1>';
				modalWrite +='<div class="BBSLoginHistory scrollDiv" style="height:370px; margin:10px 0;">';

				modalWrite +='<table><thead>';
				modalWrite +='<tr>';
				modalWrite +='<th>번호</th>';
				modalWrite +='<th>로그인 시간</th>';
				modalWrite +='<th>IP</th>';
				/*
				modalWrite +='<th>디바이스</th>';
				modalWrite +='<th>OS</th>';
				modalWrite +='<th>브라우저</th>';
				modalWrite +='<th>브라우저버전</th>';
				*/
				modalWrite +='</tr></thead>';
				modalWrite +='<tbody></tbody></table>';
				modalWrite +='</div>';
			})
			modalWrite +='</div></div></div></div></div>';
			$('#contents').after(modalWrite)
			modalAlign()
		})
		.done(function(data){
			$.each(data.member, function(){
				$.get(modalStudyApi,{'userID':this.userID},function(data){
					var listView = '';
					if(data.totalCount != 0){
						$.each(data.study,function(){
							listView +='<tr>';
							listView +='<td>'+this.lectureStart+'&nbsp;~<br/>'+this.lectureEnd+'</td>';
							if(this.serviceType == '1') {
								var serviceType = '사업주';
								if(this.certPass == 'Y') {
									var certPass = '인증받음';
								} else {
									var certPass = '미인증<br /><button type="button" onClick="certPassOK(\''+this.user.userID+'\',\''+this.lectureStart+'\',\''+loginUserID+'\')">인증제외</button>';
								}
							} else if(this.serviceType == '3') {
								var serviceType = '비환급';
								var certPass = '해당없음';
							} else if(this.serviceType == '9') {
								var serviceType = '테스트';
								var certPass = '해당없음';
							} else {
								var serviceType = '?';
								var certPass = '해당없음';
							}
							listView +='<td>'+this.contents.contentsName+'<br / >('+serviceType+' 과정)</td>';
							listView +='<td>'+certPass+'</td>';
							listView +='<td>'+this.progress+'%</td>';
							listView +='<td>';

							if(this.serviceType == '3'){
								listView +='<strong >평가 없음</strong><br />';
								listView +='<strong >평가 없음</strong><br />';
								listView +='<strong >과제 없음</strong>';
							} else {
								if(this.midStatus == 'Y'){
									listView +='<strong>채점 대기중</strong><br />';
								} else if(this.midStatus == 'C') {
									listView +='<strong class="price">'+this.midScore+'</strong><br />';
								} else {
									listView +='<strong class="price">미응시</strong><br />';
								}
								if(this.testStatus == 'Y'){
									listView +='<strong>채점 대기중</strong><br />';
								} else if(this.testStatus == 'C') {
									listView +='<strong class="price">'+this.testScore+'</strong><br />';
								} else {
									listView +='<strong class="price">미응시</strong><br />';
								}
								if(this.reportStatus == 'Y'){
									listView +='<strong>채점 대기중</strong>';
								}else if(this.reportStatus == 'R'){
									listView +='<strong class="price">과제 반려</strong>';
								}else if(this.reportStatus == 'C'){
									listView +='<strong class="price">'+this.reportScore+'</strong>';
								}else if(this.reportStatus == 'N'){
									listView +='<strong class="price">미제출</strong>';
								}else{
									listView +='<strong class="price">과제 없음</strong>';
								}
							}

							listView +='</td>';
							listView +='<td>';
							if(this.serviceType == '3'){
								if(this.progress >= 80) { // 수료
									listView +='<strong>수료</strong><br />';
								} else {
									listView +='<strong class="price">미수료</strong><br />';
								}
							} else {
								if(this.passOK == 'Y'){
									listView +='<strong>수료</strong><br />';
								}else if(this.passOK == 'W'){
									listView +='<strong class="price">진행중</strong><br />';
								}else{
									listView +='<strong class="price">미수료</strong><br />';
								}
							}
							//listView +='<td><button type="button">인증대상</button></td>';
							listView +='</td>';
							listView +='</tr>';
						})						
					}else{
						listView +='<tr><td colspan="20">수강중인 과정이 없습니다.</td></tr>';
					}
					$('#modal .BBSList tbody').html(listView)
				})
			})
		})
		.always(function(data){
			//로그인 이력 정보
			$.get(modalLoginHistoryApi,{'userID':eachID},function(data){
				var historyNum = data.totalCount;
				var modalLoginWrite = ''
				//modalLoginWrite +='<ul>';
				if(data.totalCount != 0){
					$.each(data.loginHistory,function(){
						//로그인 시간
						modalLoginWrite +='<tr>';
						modalLoginWrite +='<td>'+historyNum+'</td>';
						modalLoginWrite +='<td>'+this.loginTime+'</td>';
						modalLoginWrite +='<td>'+this.loginIP+'</td>';
						/*
						if(this.device == '' || this.device == null) {
							var device = '-';
						} else {
							var device = this.device;
						}
						if(this.os == '' || this.os == null) {
							var os = '-';
						} else {
							var os = this.os;
						}
						if(this.browser == '' || this.browser == null) {
							var browser = '-';
						} else {
							var browser = this.browser;
						}
						if(this.browserVersion == '' || this.browserVersion == null) {
							var browserVersion = '-';
						} else {
							var browserVersion = this.browserVersion;
						}
						modalLoginWrite +='<td>'+device+'</td>';
						modalLoginWrite +='<td>'+os+'</td>';
						modalLoginWrite +='<td>'+browser+'</td>';
						modalLoginWrite +='<td>'+browserVersion+'</td>';
						*/
						modalLoginWrite +='</tr>';
						historyNum --;
					})
				}else{
					modalLoginWrite +='<tr><td colspan="3">로그인 정보가 없습니다.</td></tr>';
				}
				$('#modal .BBSLoginHistory tbody').after(modalLoginWrite)
			})
		})
	}
	
	//회사정보 조회
	else if(types == 'companyView'){
		modalWrite += '<div class="memberView">';
		modalWrite += '<h1><strong>사업주 정보</strong><button type="button" onClick="modalClose()"><img src="../images/admin/btn_close.png" alt="닫기" /></button></h1>';
		$.get(modalCompanyApi,{'seq':modalSeq,'companyCode':eachID},function(data){

			if(data.totalCount != 0){
				$.each(data.company,function(){
					modalWrite +='<div>';
					modalWrite +='<h1>기본정보</h1>';				
					modalWrite +='<div class="BBSWrite">';					
					//수험생정보
					modalWrite +='<ul>';

					modalWrite +='<li>';
					modalWrite +='<h1>회사명</h1>';
					modalWrite += this.companyName + '&nbsp;(&nbsp;ID&nbsp;:&nbsp;'+this.companyID+'&nbsp;)';
					modalWrite +='</li>';
					
					//회사규모 사이버교육센터정보
					modalWrite +='<li>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>회사규모</h1>';
					if(this.companyScale == 'A'){
						modalWrite +='대규모 1000인 이상'
					}else if(this.companyScale == 'B'){
						modalWrite +='대규모 1000인 미만'
					}else if(this.companyScale == 'C'){
						modalWrite +='우선지원대상'
					}
					modalWrite +='</div>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>사이버 교육센터</h1>';
					if(this.studyEnabled == 'Y'){
						modalWrite +='사용&nbsp;(&nbsp;주소&nbsp;:&nbsp;<a href="http://'+this.companyID+'.ncscenter.kr" target="_blank">'+this.companyID+'.ncscenter.kr</a>&nbsp;)'
					}else{
						modalWrite +='사용하지 않음'
					}
					modalWrite +='</div>';
					modalWrite +='</li>';
					
					//사업자 번호
					modalWrite +='<li>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>사업자 번호</h1>'
					modalWrite +=this.companyCode;
					if(this.hrdCode != '' && this.hrdCode != null ){
						modalWrite +='&nbsp;(&nbsp;HRD 번호&nbsp;:&nbsp;'+this.hrdCode+'&nbsp;)';
					}
					modalWrite +='</div>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>업태/업종</h1>';
					modalWrite += this.kind + '&nbsp;/&nbsp;'+this.part;
					modalWrite +='</div>';
					modalWrite +='</li>';
					
					//대표자명
					modalWrite +='<li>';
					modalWrite +='<h1>대표자명</h1>';
					modalWrite +=this.ceoName;
					modalWrite +='</li>';
					
					//주소
					if(this.zipCode == '' || this.zipCode == null) {
						var zipCode = '';
					} else {
						var zipCode = this.zipCode;
					}

					if(this.address01 == '' || this.address01 == null) {
						var address01 = '';
					} else {
						var address01 = this.address01;
					}

					if(this.address02 == '' || this.address02 == null) {
						var address02 = '';
					} else {
						var address02 = this.address02;
					}
					modalWrite +='<li><h1>주소</h1><div class="normalText">';
					modalWrite += zipCode+'&nbsp;)&nbsp;'+address01+'<br />'+address02;
					modalWrite +='</div></li>';
					
					//계좌정보,홈페이지
					if(this.bank == '' || this.bank == null) {
						var bank = '';
					} else {
						var bank = this.bank;
					}

					if(this.bankNum == '' || this.bankNum == null) {
						var bankNum = '';
					} else {
						var bankNum = this.bankNum;
					}

					if(this.siteURL == '' || this.siteURL == null) {
						var siteURL = '';
					} else {
						var siteURL = this.siteURL;
					}
					modalWrite +='<li>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>계좌번호</h1>'
					modalWrite +='[&nbsp;'+bank+'&nbsp;]&nbsp;'+bankNum;
					modalWrite +='</div>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>홈페이지</h1>'
					modalWrite +='<a href="'+siteURL+'" target="_blank">'+siteURL+'</a>';
					modalWrite +='</div>';
					modalWrite +='</li>';
					
					//담당자명,담당자연락처
					modalWrite +='<li>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>담당자명</h1>'
					if(this.manager.ID != '' && this.manager.ID != null){
						modalWrite +=this.manager.name+'&nbsp;(&nbsp;ID&nbsp;:&nbsp;'+this.manager.ID+'&nbsp;)';
					}else{
						modalWrite +='미등록';
					}
					modalWrite +='</div>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>담당자 연락처</h1>'
					modalWrite +=this.manager.mobile;
					modalWrite +='</div>';
					modalWrite +='</li>';
					
					//email관련
					modalWrite +='<li>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>담당자 email</h1>'
					modalWrite +=this.manager.email;
					modalWrite +='</div>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>계산서 email</h1>'
					modalWrite +=this.elecEmail01+'@'+this.elecEmail02;
					modalWrite +='</div>';
					modalWrite +='</li>';

					//영업담당자
					modalWrite +='<li>';
					modalWrite +='<h1>영업담당자</h1>';
					if(this.marketer.name != '' && this.marketer.name != null){
						modalWrite +=this.marketer.name+'&nbsp;(&nbsp;ID&nbsp;:&nbsp;'+this.marketer.name+'&nbsp;)';
					}else{
						modalWrite +='미등록';
					}
					modalWrite +='</li>';
					
					//메모
					modalWrite +='<li><h1>메모</h1><div class="normalText">';
					modalWrite += this.memo;
					modalWrite +='</div></li>';
					modalWrite +='</ul>';
					modalWrite +='</div>';

					modalWrite +='<div class="BBSList" style="height:332px; margin:10px 0;">';				
					modalWrite +='<table><thead><tr>';
					modalWrite +='<th>기간</th>';
					modalWrite +='<th style="width:180px">수강/수료</th>';
					modalWrite +='<th style="width:100px;">수료율</th>';
					modalWrite +='<th style="width:120px;">교육비</th>';
					modalWrite +='<th style="width:120px;">환급비</th>';
					modalWrite +='</tr></thead><tbody>';
					modalWrite +='</tbody></table>';

					modalWrite +='</div></div></div>';
					$('#contents').after(modalWrite);
					modalAlign();
				})

			} else {
				//일반회원 정보 없음
				modalWrite +='<div class="BBSWrite">';
				modalWrite +='<h1>기본정보</h1>';				
				modalWrite +='<ul>';
				modalWrite +='<li style="text-align:center">';
				modalWrite +='일반 회원 이거나 검색 값이 없습니다.';
				modalWrite +='</li>';
				modalWrite +='</div>';
			}
		})
		.done(function(data){
			$.each(data.company, function(){
				$.get(modalCompanyStudyStatsApi,{'companyCode':this.companyCode},function(data){
					var listView = '';
					var totalReturnPrice = '';
					if(data.totalCount != 0) {
						$.each(data.companyStudyStats,function() {
							if(this.totalReturnPrice == null) {
								totalReturnPrice = '-';
							} else {
								totalReturnPrice = this.totalReturnPrice;
							}
							listView +='<tr>';
							listView +='<td>'+this.lectureStart+' ~ '+this.lectureEnd+'</td>';
							listView +='<td>'+this.totalStudy+'/'+this.totalPassOK+'</td>';
							listView +='<td>'+this.totalPassRate+'%</td>';
							listView +='<td>'+toPriceNum(this.totalPrice)+'</td>';
							listView +='<td>'+toPriceNum(totalReturnPrice)+'</td>';
							listView +='</tr>';
						})
					} else {
						listView +='<tr><td colspan="20">수강한 과정이 없습니다.</td></tr>';
					}
					$('#modal .BBSList tbody').html(listView);
				})
			})
		})
	}

	//차시 진도 조회
	else if(types == 'progressView'){
		modalWrite += '<div class="memberView">';
		modalWrite += '<h1><strong>차시별 진도율</strong><button type="button" onClick="modalClose()"><img src="../images/admin/btn_close.png" alt="닫기" /></button></h1>';
		modalWrite += '<div>';
		$.get(modalProgressApi,{'lectureOpenSeq':modalSeq,'userID':eachID},function(data){
  	        modalWrite +='<div class="BBSWrite">';
			modalWrite +='<h1>기본정보</h1>';				
			modalWrite +='<ul>';
			modalWrite +='<li>';

			//아이디(이름)
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>아이디(이름)</h1>'+data.userID+'('+data.userName+')';
			modalWrite +='</div>';

			//회사명
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>회사명</h1>'+data.companyName;
			modalWrite +='</div>';
			modalWrite +='</li>';
			modalWrite +='<li>';

			//진도율(전체)
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>진도율(전체)</h1>'+data.totalProgress+' %';
			modalWrite +='</div>';

			//수강기간
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>수강기간</h1>'+data.lectureStart+' ~ '+data.lectureEnd;
			modalWrite +='</div>';
			modalWrite +='</li>';
			modalWrite +='</ul>';
			modalWrite +='</div>';

			//차시 이력
			modalWrite +='<div class="BBSList">';
			modalWrite +='<h1>'+data.contentsName+'</h1>';
			modalWrite +='<table><thead><tr>';
			modalWrite +='<th style="width:60px;">차시번호</th>';
			modalWrite +='<th>차시명</th>';
			modalWrite +='<th style="width:60px;">진도율</th>';
			modalWrite +='<th style="width:140px;">수강시간/접속IP</th>';
			modalWrite +='<th style="width:100px;">총학습시간</th>';
			modalWrite +='</tr></thead><tbody>';

			var time ='';
			var hour = '';
			var min = '';
			var sec = '';
			
			if(data.totalCount != 0){
				$.each(data.progress,function(){
					time = this.totalTime;
					hour = parseInt(time/3600);
					min = parseInt((time%3600)/60);
					sec = time%60;
					modalWrite +='<tr>';
					modalWrite +='<td>'+this.chapter+'</td>';
					modalWrite +='<td>'+this.chapterName+'</td>';
					modalWrite +='<td>'+this.progress+'</td>';
					modalWrite +='<td>'+this.endTime+'<br />'+this.studyIP+'</td>';
					modalWrite +='<td>'+hour+'시간'+min+ '분' + sec+ '초</td>';
					modalWrite +='</tr>';
				})

			}else{
				modalWrite +='<tr><td colspan="20">수강이력이 없습니다.</td></tr>';
			}

			modalWrite +='</tbody></table>';
			modalWrite += '<div>';
			modalWrite +='</div></div>';
			$('#contents').after(modalWrite);
			modalAlign();
		})
	}

	//평가 결과조회
	else if(types == 'testResultView'){
		var i = 1;
		var submitIP = '';
		var saveTime = '';
		var CheckTime = '';
		var submitScore = '';
		var submitStatus = '';
		var statusText = '';
		var testCopy = '';
		var testCopyCheck = '';
		var testCheckTime = '';

		modalWrite += '<div class="memberView">';
		modalWrite += '<h1><strong>평가 결과</strong><button type="button" onClick="modalClose()"><img src="../images/admin/btn_close.png" alt="닫기" /></button></h1>';
		modalWrite += '<div>';

		$.get(modalTestResultApi,{'lectureOpenSeq':modalSeq,'userID':eachID,'testType':option,'admin':'Y'},function(data){
			var today = data.nowTime.substr(0,10);
  	        modalWrite +='<div class="BBSWrite">';
			modalWrite +='<h1>기본정보</h1>';
			modalWrite +='<ul>';
			modalWrite +='<li>';
			modalWrite +='<h1>과정명</h1>'+data.contentsName;
			modalWrite +='</li>';
			modalWrite +='<li>';
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>아이디(이름)</h1>'+data.userID+'('+data.userName+')';
			modalWrite +='</div>';
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>회사명</h1>'+data.companyName;
			modalWrite +='</div>';
			modalWrite +='</li>';
			modalWrite +='<li>';
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>진도율(전체)</h1>'+data.progress+' %';
			modalWrite +='</div>';
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>수강기간</h1>'+data.lectureStart+' ~ '+data.lectureEnd;
			modalWrite +='</div>';
			modalWrite +='</li>';

            if(data.testType == 'final') {
                modalWrite +='<li>';
                modalWrite +='<h1>응시가능시간</h1>'+data.testStartTime+' ~ '+data.testEndTime;
                modalWrite +='</li>';
            }

			modalWrite +='<li>';
			if(option == 'mid') {
				saveTime = data.midSaveTime;
				submitIP = data.midIP;
				checkTime = data.midCheckTime;
				submitScore = data.midScore;
				submitStatus = data.midStatus;
			} else {
				saveTime = data.testSaveTime;
				submitIP = data.testIP;
				checkTime = data.testCheckTime;
				submitScore = data.testScore;
				submitStatus = data.testStatus;
			}
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>응시일</h1>'+data.saveTime;
			modalWrite +='</div>';
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>응시한 IP</h1>'+data.submitIP;
			modalWrite +='</div>';
			modalWrite +='</li>';
			modalWrite +='<li>';
			modalWrite +='<div class="halfDiv">';

			if(data.status == 'C') {
				statusText = '채점 완료';
			} else if(data.status == 'N') {
				statusText = '미응시';
			} else if(data.status == 'Y') {
				statusText = '채점 대기중';
			}

			modalWrite +='<h1>진행상황</h1>'+statusText;
			modalWrite +='</div>';
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>채점시간</h1>'+data.checkTime;
			modalWrite +='</div>';
			modalWrite +='</li>';
			if(data.checkTime == null) {
				checkTime = '채점 대기중';
			}
			modalWrite +='<li>';
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>유형별 획득점수</h1>';
			if(data.dTypeEA != '0') {
				modalWrite +='진위형:'+data.dTypeTotalScore;
			}
			if(data.aTypeEA != '0') {
				modalWrite +=' 객관식:'+data.aTypeTotalScore;
			}
			if(data.bTypeEA != '0') {
				modalWrite +=' 단답형:'+data.bTypeTotalScore;
			}
			if(data.cTypeEA != '0') {
				modalWrite +=' 서술형:'+data.cTypeTotalScore;
			}
			modalWrite +='</div>';
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>총점</h1>'+data.userScore;
			modalWrite +='</div>';
			modalWrite +='</li>';
			modalWrite +='</ul>';
			modalWrite +='</div>';
			modalWrite +='<div class="BBSList">';
			modalWrite +='<form class="tutorGrade" method="post">';
			modalWrite +='<input type="hidden" name="testType" value="'+data.testType+'">';
			modalWrite +='<input type="hidden" name="lectureOpenSeq" value="'+data.lectureOpenSeq+'">';
			modalWrite +='<input type="hidden" name="contentsCode" value="'+data.contentsCode+'">';
			modalWrite +='<input type="hidden" name="userID" value="'+data.userID+'">';

			// 진위형
			if(data.dTypeEA != 0){
				modalWrite +='<h1 class="modalTitle">진위형 ('+data.dTypeEA+'문항 / '+data.dTypeScore+'점)</h1>';
				$.each(data.dType,function(){
					var answerOK = '';
					var rightAnswer = '';
					var userAnswerCheck01 = '';
					var userAnswerCheck02 = '';
					var answerCheck01 = '';
					var answerCheck02 = '';

					if(this.userAnswer == this.answer) {
						answerOK = ' - 정답';
						rightAnswer = 'Y';
					} else {
						answerOK = ' - 오답';
						rightAnswer = 'N';
					}

					switch(this.userAnswer) {
						case '1' : 
							userAnswerCheck01 = ' - 제출답';
							break;
						case '2' : 
							userAnswerCheck02 = ' - 제출답';
							break;
					}

					switch(this.answer) {
						case '1' : 
							answerCheck01 = ' - 정답';
							break;
						case '2' : 
							answerCheck02 = ' - 정답';
							break;
					}
					modalWrite +='<input type="hidden" name="seq[]" value="'+this.seq+'">';
					modalWrite +='<input type="hidden" name="testSeq[]" value="'+this.testSeq+'">';
					modalWrite +='<table style="margin-bottom:5px;"><thead><tr>';
					modalWrite +='<th style="width:50px;">'+i+'</th>';
					modalWrite +='<th class="left">'+this.exam.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;')+answerOK+'</th>';
					modalWrite +='</thead></tr>';
					modalWrite +='<input type="hidden" name="rightAnswer[]" value="'+rightAnswer+'">';
					modalWrite +='<tbody><tr>';
					modalWrite +='<td>1</td>';
					modalWrite +='<td class="left">'+this.example01+userAnswerCheck01+answerCheck01+'</td>';
					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>2</td>';
					modalWrite +='<td class="left">'+this.example02+userAnswerCheck02+answerCheck02+'</td>';
					modalWrite +='</tr>';

					modalWrite +='<tr>';
					modalWrite +='<td>해설</td>';
					modalWrite +='<td class="left">'+this.commentary+'</td>';
					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>정보</td>';
					modalWrite +='<td class="left">출처 차시 : '+this.sourceChapter+', 문제 ID : '+this.testSeq+'</td>';
					modalWrite +='</tr>';
					modalWrite +='</tbody></table>';
					i++;
				})
			}

			// 객관식
			if(data.aTypeEA != 0){
				modalWrite +='<br /><br /><h1 class="modalTitle">객관식 ('+data.aTypeEA+'문항 / '+data.aTypeScore+'점)</h1>';
				$.each(data.aType,function(){
					var answerOK = '';
					var rightAnswer = '';
					var userAnswerCheck01 = '';
					var userAnswerCheck02 = '';
					var userAnswerCheck03 = '';
					var userAnswerCheck04 = '';
					var userAnswerCheck05 = '';
					var answerCheck01 = '';
					var answerCheck02 = '';
					var answerCheck03 = '';
					var answerCheck04 = '';
					var answerCheck05 = '';

					if(this.userAnswer == this.answer) {
						answerOK = ' - 정답';
						rightAnswer = 'Y';
					} else {
						answerOK = ' - 오답';
						rightAnswer = 'N';
					}

					switch(this.userAnswer) {
						case '1' : 
							userAnswerCheck01 = ' - 제출답';
							break;
						case '2' : 
							userAnswerCheck02 = ' - 제출답';
							break;
						case '3' : 
							userAnswerCheck03 = ' - 제출답';
							break;
						case '4' : 
							userAnswerCheck04 = ' - 제출답';
							break;
						case '5' : 
							userAnswerCheck05 = ' - 제출답';
							break;
					}

					switch(this.answer) {
						case '1' : 
							answerCheck01 = ' - 정답';
							break;
						case '2' : 
							answerCheck02 = ' - 정답';
							break;
						case '3' : 
							answerCheck03 = ' - 정답';
							break;
						case '4' : 
							answerCheck04 = ' - 정답';
							break;
						case '5' : 
							answerCheck05 = ' - 정답';
							break;
					}
					modalWrite +='<input type="hidden" name="seq[]" value="'+this.seq+'">';
					modalWrite +='<input type="hidden" name="testSeq[]" value="'+this.testSeq+'">';
					modalWrite +='<table style="margin-bottom:5px;"><thead><tr>';
					modalWrite +='<th style="width:50px;">'+i+'</th>';
					modalWrite +='<th class="left">'+this.exam.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;')+answerOK+'</th>';
					modalWrite +='</thead></tr>';
					modalWrite +='<input type="hidden" name="rightAnswer[]" value="'+rightAnswer+'">';
					modalWrite +='<tbody><tr>';
					modalWrite +='<td>1</td>';
					modalWrite +='<td class="left">'+this.example01+userAnswerCheck01+answerCheck01+'</td>';
					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>2</td>';
					modalWrite +='<td class="left">'+this.example02+userAnswerCheck02+answerCheck02+'</td>';
					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>3</td>';
					modalWrite +='<td class="left">'+this.example03+userAnswerCheck03+answerCheck03+'</td>';
					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>4</td>';
					modalWrite +='<td class="left">'+this.example04+userAnswerCheck04+answerCheck04+'</td>';
					modalWrite +='</tr>';

					if(this.example05 != undefined){
						modalWrite +='<tr>';
						modalWrite +='<td>5</td>';
						modalWrite +='<td>'+this.example05+userAnswerCheck05+answerCheck05+'</td>';
						modalWrite +='</tr>';
					}

					modalWrite +='<tr>';
					modalWrite +='<td>해설</td>';
					modalWrite +='<td class="left">'+this.commentary+'</td>';
					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>정보</td>';
					modalWrite +='<td class="left">출처 차시 : '+this.sourceChapter+', 문제 ID : '+this.testSeq+'</td>';
					modalWrite +='</tr>';
					modalWrite +='</tbody></table>';
					i++;
				})
			}

			// 단답형
			if(data.bTypeEA != 0){
				modalWrite +='<br /><br /><h1 class="modalTitle">단답형 ('+data.bTypeEA+'문항 / '+data.bTypeScore+'점)</h1>';

				$.each(data.bType,function(){
					var answerOK = '';
					var selectedY = '';
					var selectedN = '';
					var selectedNone = '';


					if(this.score > 0) {
						answerOK = ' - 정답';
						selectedY = 'selected="seleted"';
					} else if(this.score == null) {
						answerOK = '';
						selectedNone = 'selected="seleted"';
					} else {
						answerOK = ' - 오답';
						selectedN = 'selected="seleted"';
					}

					modalWrite +='<input type="hidden" name="seq[]" value="'+this.seq+'">';
					modalWrite +='<input type="hidden" name="testSeq[]" value="'+this.testSeq+'">';
					modalWrite +='<table><thead><tr>';
					modalWrite +='<th style="width:50px;">'+i+'</th>';
					modalWrite +='<th class="left">'+this.exam.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;')+answerOK+'</th>';
					modalWrite +='</thead></tr>';
					modalWrite +='<tbody>';

					if(loginUserLevel == '7') {
						modalWrite +='<tr>';
						modalWrite +='<td>채점</td>';
						modalWrite +='<td class="left">';
						modalWrite +='<select name="rightAnswer[]">';
						modalWrite +='<option value="" '+selectedNone+'>선택하세요.</option>';
						modalWrite +='<option value="Y" '+selectedY+'>정답</option>';
						modalWrite +='<option value="N" '+selectedN+'>오답</option>';
						modalWrite +='</select>';
						modalWrite +='</td>';
						modalWrite +='</tr>';
					}

					if(this.userAnswer == null) {
						var bTypeAnswer = '미작성';
					} else {
						var bTypeAnswer = this.userAnswer;
					}

					modalWrite +='<tr>';
					modalWrite +='<td>제출답</td>';
					modalWrite +='<td class="left">'+bTypeAnswer+'</td>';
					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>정답</td>';
					modalWrite +='<td class="left">'+this.answer+'</td>';
					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>해설</td>';
					modalWrite +='<td class="left">'+this.commentary+'</td>';
					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>정보</td>';
					modalWrite +='<td class="left">출처 차시 : '+this.sourceChapter+', 문제 ID : '+this.testSeq+'</td>';
					modalWrite +='</tr>';
					modalWrite +='</tbody></table>';
					i++;
				})
			}

			// 서술형
			if(data.cTypeEA != 0){
				var selectedY = '';
				var selectedN = '';

				modalWrite +='<br /><br /><h1 class="modalTitle">서술형 ('+data.cTypeEA+'문항 / '+data.cTypeScore+'점)</h1>';

				$.each(data.cType,function(){
					modalWrite +='<input type="hidden" name="seq[]" value="'+this.seq+'">';
					modalWrite +='<input type="hidden" name="testSeq[]" value="'+this.testSeq+'">';
					modalWrite +='<table><thead><tr>';
					modalWrite +='<th style="width:50px;">'+i+'</th>';
					modalWrite +='<th class="left">'+this.exam.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;')+'</th>';
					modalWrite +='</thead></tr>';
					modalWrite +='<tbody><tr>';
					modalWrite +='<td>점수</td>';

					if(this.score == null) {
						var cTypeScore = '';
					} else {
						var cTypeScore = this.score;
					}

					if(loginUserLevel == '7') {
						var c = 0;
						modalWrite +='<td class="left">';
						modalWrite +='<select name="cTypeScore[]">';
						
						for(c=this.baseScore; c>=0; c--) {
							if(cTypeScore == c) {
								selectedY = 'selected="seleted"';
							} else {
								if(cTypeScore == '') {
									selectedN = 'selected="seleted"';
								} else {
									selectedY = '';
								}
							}
							modalWrite +='<option value="'+c+'" '+selectedY+'>'+c+' 점</option>';
						}
						modalWrite +='<option value="" '+selectedN+'>선택하세요.</option>';
						modalWrite +='</select>  / (배점 : '+this.baseScore+'점)</td>';
					} else {
						modalWrite +='<td class="left">'+cTypeScore+'</td>';
					}
					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>제출답</td>';
					if(this.userAnswer == null) {
						var cTypeAnswer = '미작성';
					} else {
						var cTypeAnswer = this.userAnswer.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;');
					}
					modalWrite +='<td class="left">'+cTypeAnswer+'</td>';
					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>채점기준</td>';
					modalWrite +='<td class="left">'+this.commentary.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;')+'</td>';
					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>모범답안</td>';
					modalWrite +='<td class="left">'+this.answer.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;')+'</td>';
					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>첨삭지도</td>';

					if(this.correct == null) {
						var correct = '';
					} else {
						//var correct = this.correct.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;');
						var correct = this.correct;
					}

					if(loginUserLevel == '7') {
						modalWrite +='<td class="left"><textarea name="correct[]" style="width:500px; height:100px;" oncontextmenu="return false" onkeydown="keyCheck(event)">'+correct+'</textarea></td>';
					} else {
						modalWrite +='<td class="left">'+correct+'</td>';
					}

					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>모사답안</td>';
					modalWrite +='<td class="left">';
					if(data.testCopy == 'D' || data.testCopy == 'Y') {
						testCopyCheck = 'checked="checked"';
					}
					if(loginUserLevel == '7') {
						var testCopyValue = "value=D";
					} else {
						var testCopyValue = "value=Y";
					}
					modalWrite +='<input type="checkbox" name="testCopy" '+testCopyCheck+' id="testCopy" '+testCopyValue+'><label for="testCopy">모사의심</label>&nbsp;';
					modalWrite +='<button type="button" class="btnTestCopy" onclick="mosaCheckTest('+this.seq+')">모사율 조회하기</button>';
					if(loginUserLevel <= '4') {
						if(this.testCopy == 'D') {
							var testCopy = "N";
							var testCopyM = "모사답안 의심 취소";
						} else if(data.testCopy == 'Y') {
							var testCopy = "N";
							var testCopyM = "모사답안 확정 취소";
						} else {
							var testCopy = "Y";
							var testCopyM = "모사답안 확정";
						}
						modalWrite +='<input type="hidden" name="testCopy" value="'+testCopy+'" id="testCopy">';
						modalWrite +='&nbsp;&nbsp;|&nbsp;&nbsp;<button type="button" onClick="tempGrade(\''+modalTestResultApi+'\')">'+testCopyM+'</button>';
					}

					modalWrite +='</td></tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>정보</td>';
					modalWrite +='<td class="left">출처 차시 : '+this.sourceChapter+', 문제 ID : '+this.testSeq+'</td>';
					modalWrite +='</tr>';
					modalWrite +='</tbody></table>';
					i++;
				})
			}
			if(loginUserLevel == '7') {
				if(data.status == 'C') {
					modalWrite +='<div class="btnArea"><strong class="blue">채점이 완료 되었습니다.</strong></div>';
				} else {
					modalWrite +='<div class="btnArea">'
					modalWrite +='<button type="button" onClick="tempGrade(\''+modalTestResultApi+'\')">임시저장</button>';
					modalWrite +='<button type="button" onClick="tutorGrade(\''+modalTestResultApi+'\',\'tests\',\''+data.lectureEnd+'\',\''+today+'\')">채점완료</button>';
					modalWrite +='</div>'
				}
			} else if(loginUserLevel <= '4') {
				if(data.status == 'C') {
					modalWrite +='<div class="btnArea">'
					if(data.studyEnd == 'Y') {
						modalWrite +='수강이 마감된 과정입니다.';
					} else {
						modalWrite +='<button type="button" onClick="reScore(\''+modalTestResultApi+'\')">채점 완료 취소</button>';
					}
					modalWrite +='</div>'
				}
			}
			modalWrite +='</form>';
			modalWrite +='</div></div></div>';
			$('#contents').after(modalWrite);
			modalAlign();
		})
	}

	//과제 결과조회
	else if(types == 'reportResultView'){
		var i = 1;
		var reportStatus = '';
		var reportScore = '';
		var answerType = '';
		var reportCopy = '';
		var reportCopyCheck = '';
		var returnCheck = '';
		var reportCheckTime = '';
		var comment = '';

		modalWrite += '<div class="memberView">';
		modalWrite += '<h1><strong>과제 결과</strong><button type="button" onClick="modalClose()"><img src="../images/admin/btn_close.png" alt="닫기" /></button></h1>';
		modalWrite += '<div>';
		$.get(modalReportResultApi,{'lectureOpenSeq':modalSeq,'userID':eachID, 'admin':'Y'},function(data){
			var today = data.nowTime.substr(0,10);
  	        modalWrite +='<div class="BBSWrite">';
			modalWrite +='<h1 class="modalTitle">기본정보</h1>';
			modalWrite +='<ul>';
			modalWrite +='<li>';
			modalWrite +='<h1>과정명</h1>'+data.contentsName;
			modalWrite +='</li>';
			modalWrite +='<li>';
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>아이디(이름)</h1>'+data.userID+'('+data.userName+')';
			modalWrite +='</div>';
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>회사명</h1>'+data.companyName;
			modalWrite +='</div>';
			modalWrite +='</li>';
			modalWrite +='<li>';
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>진도율(전체)</h1>'+data.progress+' %';
			modalWrite +='</div>';
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>수강기간</h1>'+data.lectureStart+' ~ '+data.lectureEnd;
			modalWrite +='</div>';
			modalWrite +='</li>';
			modalWrite +='<li>';
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>응시일</h1>'+data.reportSaveTime;
			modalWrite +='</div>';
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>응시한 IP</h1>'+data.reportIP;
			modalWrite +='</div>';
			modalWrite +='</li>';
			if(data.reportCheckTime == null) {
				reportCheckTime = '채점 대기중';
			} else {
				reportCheckTime = data.reportCheckTime;
			}
			modalWrite +='<li>';
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>채점시간</h1>'+reportCheckTime;
			modalWrite +='</div>';
			if(data.reportScore == null) {
				reportScore = '채점 대기중';
			} else {
				reportScore = data.reportScore;
			}
			modalWrite +='<div class="halfDiv">';
			modalWrite +='<h1>점수</h1>'+reportScore;
			modalWrite +='</div>';
			modalWrite +='</li>';
			modalWrite +='<li>';
			modalWrite +='<div class="halfDiv">';

			if(data.reportStatus == 'C') {
				reportStatus = '채점완료';
			} else if(data.reportStatus == 'N') {
				reportStatus = '미응시';
			} else if(data.reportStatus == 'Y') {
				reportStatus = '채점 대기중';
			} else if(data.reportStatus == 'R') {
				reportStatus = '반려';
			}

			modalWrite +='<h1>진행상황</h1>'+reportStatus;
			modalWrite +='</div>';
			modalWrite +='<div class="halfDiv">';

			if(data.reportCopy == 'Y') {
				reportCopy = '모사답안';
			} else if(data.reportCopy == 'D') {
				reportCopy = '모사답안 의심';
			} else {
				reportCopy = '정상';
			}

			modalWrite +='<h1>모사답안의심</h1>'+reportCopy;
			modalWrite +='</div>';
			modalWrite +='</li>';
			modalWrite +='</ul>';
			modalWrite +='</div>';
			modalWrite +='<div class="BBSList" style="height:440px;">';
			modalWrite +='<form class="tutorGrade" method="post">';
			modalWrite +='<input type="hidden" name="lectureOpenSeq" value="'+data.lectureOpenSeq+'">';
			modalWrite +='<input type="hidden" name="contentsCode" value="'+data.contentsCode+'">';
			modalWrite +='<input type="hidden" name="userID" value="'+data.userID+'">';
			modalWrite +='<h1 class="modalTitle">과제 ('+data.reportEA+'문항 / 총 '+data.totalPassReport+'점)</h1>';

			if(data.totalCount != 0){
				$.each(data.reportResult,function(){
					modalWrite +='<table><thead><tr>';
					modalWrite +='<th style="width:100px;">문제</th>';
					modalWrite +='<th class="left">'+this.exam;

					if(this.examAttach != null) {
						modalWrite +='<br /><br /><a href="fileDownLoad.php?fileName='+this.examAttach+'&link='+data.examAttachURL+this.examAttach+'" target="_blank">첨부파일 : '+this.examAttach;
					}

					modalWrite +='</th></thead></tr>';

					modalWrite +='<tbody>';
					modalWrite +='<tr>';
					modalWrite +='<td>채점기준</td>';
					modalWrite +='<td class="left">'+this.rubric;
					if(this.rubricAttach != '') {
						modalWrite +='<br /><a href="fileDownLoad.php?fileName='+this.rubricAttach+'&link='+this.rubricAttachLink+'" target="_blank">첨부파일 : '+this.rubricAttach+'</td>';
					}
					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>제출답안</td>';

					if(this.answerType == 'text') {
						answerType = this.answerText.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;'); 
					} else {
						answerType += '링크 1 : <a href="fileDownLoad.php?fileName='+this.answerAttach+'&link='+this.attachLink+'" target="_blank">'+this.answerAttach+'</a><br />';
						answerType += '링크 2 : <a href="'+this.attachLink+'" target="_blank">'+this.answerAttach+'</a><br />';
						answerType += '(링크 1,2 둘다 다운로드시 파일이 깨지는 경우 마우스 우클릭 > "다른이름으로 대상 저장" 하시기 바랍니다.)';
					}

					modalWrite +='<td class="left">'+answerType+'</td>';
					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>모범답안</td>';
					modalWrite +='<td class="left">'+this.example.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;');

					if(this.exampleAttach != '' && this.exampleAttach != null) {
						modalWrite +='<br /><a href="fileDownLoad.php?fileName='+this.exampleAttach+'&link='+this.exampleAttachLink+'" target="_blank">첨부파일 : '+this.exampleAttach+'</a><br />(다운로드시 파일이 깨지는 경우 마우스 우클릭 > "다른이름으로 대상 저장" 하시기 바랍니다.)';
					}

					modalWrite +='</td></tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>획득점수</td>';

					if(this.score == null) {
						var reportScore = '';
					} else {
						var reportScore = this.score;
					}

					if(loginUserLevel == '7') {
						var c = '';
						var selectedY = '';
						var selectedN = '';
						modalWrite +='<td class="left">';
						modalWrite +='<select name="reportScore">';
						for(c=this.baseScore; c>=0; c--) {
							if(reportScore == c) {
								selectedY = 'selected="seleted"';
							} else {
								if(reportScore == '') {
									selectedN = 'selected="seleted"';
								} else {
									selectedY = '';
								}
							}
							modalWrite +='<option value="'+c+'" '+selectedY+'>'+c+' 점</option>';
						}
						modalWrite +='<option value="" '+selectedN+'>선택하세요.</option>';
						modalWrite +='</select>  / (배점 : '+this.baseScore+'점)</td>';

					} else {
						if(reportScore == '') {
							modalWrite +='<td class="left">미채점 / (배점 '+this.baseScore+'점)</td>';
						} else {
							modalWrite +='<td class="left">'+reportScore+' 점 / (배점 '+this.baseScore+'점)</td>';
						}
					}

					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>첨삭지도</td>';

					if(this.comment == null) {
						comment = '';
					} else {
						comment = this.comment;
					}

					if(loginUserLevel == '7') {
						modalWrite +='<td class="left"><textarea style="width:600px; height:100px;" name="comment" oncontextmenu="return false" onkeydown="keyCheck(event)">'+comment+'</textarea></td>';
					} else {
						modalWrite +='<td class="left">'+comment.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;')+'</td>';
					}

					modalWrite +='</tr>';

					modalWrite +='<tr>';
					modalWrite +='<td>반려</td>';

					if(data.reportStatus == 'R') {
						returnCheck = 'checked="checked"';
					}

					selectedY = 'selected="seleted"';
					modalWrite +='<td class="left"><input type="checkbox" name="return" id="return" value="Y" '+returnCheck+'/><label for="return"> 반려처리 (반드시 반려사유를 상단에 작성해주세요.)</label></td>';
					modalWrite +='</tr>';
					modalWrite +='<tr>';
					modalWrite +='<td>모사답안</td>';
					
					if(data.reportCopy == 'D' || data.reportCopy == 'Y') {
						reportCopyCheck = 'checked="checked"';
					}

					if(loginUserLevel == '7') {
						var reportCopyValue = "value=D";
					} else {
						var reportCopyValue = "value=Y";
					}
					modalWrite +='<td class="left">';
					modalWrite +='<input type="checkbox" name="copyCheck">';
					modalWrite +='<input type="checkbox" name="reportCopy" '+reportCopyCheck+' id="reportCopy" '+reportCopyValue+'><label for="reportCopy">모사의심</label>';
					modalWrite +='&nbsp;&nbsp;|&nbsp;&nbsp;<button type="button" class="btnReportCopy" onclick="mosaCheck('+this.seq+')">모사율 조회하기</button>';
					if(loginUserLevel <= '4') {
						if(this.reportCopy == 'D') {
							var reportCopy = "N";
							var reportCopyM = "모사답안 의심 취소";
						} else if(data.reportCopy == 'Y') {
							var reportCopy = "N";
							var reportCopyM = "모사답안 확정 취소";
						} else {
							var reportCopy = "Y";
							var reportCopyM = "모사답안 확정";
						}
						modalWrite +='<input type="hidden" name="reportCopy" value="'+reportCopy+'" id="reportCopy">';
						modalWrite +='&nbsp;&nbsp;|&nbsp;&nbsp;<button type="button" onClick="tempGrade(\''+modalReportResultApi+'\')">'+reportCopyM+'</button>';
					}
					modalWrite +='</td></tr>';
					//}

					modalWrite +='<tr>';
					modalWrite +='<td>정보</td>';
					modalWrite +='<td class="left">출처 차시 : '+this.sourceChapter+', 문제 ID : '+this.reportSeq+'</td>';
					modalWrite +='</tr>';
					modalWrite +='</tbody></table><br />';
					modalWrite +='<input type="hidden" name="seq" value="'+this.seq+'">';
					i++;
				})
			}
			if(loginUserLevel == '7') {
				if(data.reportStatus == 'C') {
					modalWrite +='<div class="btnArea"><strong class="blue">채점이 완료 되었습니다.</strong></div>';
				} else {
					modalWrite +='<div class="btnArea">'
					modalWrite +='<button type="button" onClick="tempGrade(\''+modalReportResultApi+'\')">임시저장</button>';
					modalWrite +='<button type="button" onClick="tutorGrade(\''+modalReportResultApi+'\',\'reports\',\''+data.lectureEnd+'\',\''+today+'\')">채점완료</button>';
					modalWrite +='</div>'
				}
			} else if(loginUserLevel <= '4') {
				if(data.reportStatus == 'C') {
					modalWrite +='<div class="btnArea">'
					if(data.studyEnd == 'Y') {
						modalWrite +='수강이 마감된 과정입니다.';
					} else {
						modalWrite +='<button type="button" onClick="reScore(\''+modalReportResultApi+'\')">채점 완료 취소</button>';
					}
					modalWrite +='</div>'
				}
			}
			modalWrite +='</form>';
			modalWrite +='</div></div></div>';
			$('#contents').after(modalWrite);
			modalAlign();
		})
	}

	else if(types == 'contentsView'){
		var i = 1;
		var reportStatus = '';
		var answerType = '';
		var returnCheck = '';
		var passCode = '';

		modalWrite += '<div class="memberView">';
		modalWrite += '<h1><strong>과정 정보</strong><button type="button" onClick="modalClose()"><img src="../images/admin/btn_close.png" alt="닫기" /></button></h1>';
		modalWrite += '<div>'
		$.get(modalContentsApi,{'contentsCode':eachID},function(data){
			$.each(data.contents,function(){
				modalWrite +='<div class="BBSWrite">';
				modalWrite +='<h1>'+this.contentsName+'</h1>';
				modalWrite +='<ul>';
				modalWrite +='<li>';
				modalWrite +='<h1>수강 등록수</h1><b>'+this.studyCount+'</b> (현재까지 사업주(환급)으로 등록된 총 과정 수)';
				modalWrite +='</li>';
				modalWrite +='<li>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>등급/과정코드</h1>'+this.contentsGrade+' / '+this.contentsCode;
				modalWrite +='</div>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>사용여부</h1>'+this.enabled.replace('Y','사용').replace('N','미사용');
				modalWrite +='</div>';
				modalWrite +='</li>';
				modalWrite +='<li>';
				if(this.passCode == null) {
					passCode = '미입력';
				} else {
					passCode = this.passCode;
				}

				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>심사코드</h1>'+passCode;
				modalWrite +='</div>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>내용전문가</h1>'+this.professor;
				modalWrite +='</div>';
				modalWrite +='</li>';
				modalWrite +='<li>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>과정분류</h1>'+this.sort01;
				modalWrite +='</div>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>수강구분</h1>'+this.serviceType;
				modalWrite +='</div>';
				modalWrite +='</li>';
				modalWrite +='<li>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>차시수</h1>'+this.chapter;
				modalWrite +='</div>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>교육시간</h1>'+this.contentsTime;
				modalWrite +='</div>';
				modalWrite +='</li>';
				modalWrite +='<li>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>교육비용</h1>'+this.price;
				modalWrite +='</div>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>우선지원</h1>'+this.rPrice01;
				modalWrite +='</div>';
				modalWrite +='</li>';
				modalWrite +='<li>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>대규모 1000인미만</h1>'+this.rPrice02;
				modalWrite +='</div>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>대규모 1000인이상</h1>'+this.rPrice03;
				modalWrite +='</div>';
				modalWrite +='</li>';
				if(this.contentsPeriod == null){
					var contentsPeriod = '';
				} else {
					var contentsPeriod = this.contentsPeriod;
				}
				if(this.contentsExpire == null){
					var contentsExpire = '';
				} else {
					var contentsExpire = this.contentsExpire;
				}
				modalWrite +='<li>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>콘텐츠 유효기간</h1>'+contentsPeriod;
				modalWrite +='</div>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>인정만료일</h1>'+contentsExpire;
				modalWrite +='</div>';
				modalWrite +='</li>';
				modalWrite +='<li>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>공급사</h1>'+this.cp;
				modalWrite +='</div>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>수수료</h1>'+this.commission;
				modalWrite +='</div>';
				modalWrite +='</li>';
				modalWrite +='<li>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>모바일지원</h1>'+this.mobile;
				modalWrite +='</div>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>플레이방식</h1>'+this.sourceType;
				modalWrite +='</div>';
				modalWrite +='</li>';
				modalWrite +='<li>';
				modalWrite +='<h1>참고도서명</h1>'+this.bookIntro;
				modalWrite +='</li>';
				modalWrite +='<li>';
				modalWrite +='<h1>중간평가</h1>';
				modalWrite +='진위형 : '+this.mid04EA+'문항 (배점:'+this.mid04Score+'),';
				modalWrite +='객관식 : '+this.mid01EA+'문항 (배점:'+this.mid01Score+'),';
				modalWrite +='단답형 : '+this.mid02EA+'문항 (배점:'+this.mid02Score+'),';
				modalWrite +='서술형 : '+this.mid03EA+'문항 (배점:'+this.mid03Score+')';
				modalWrite +='</li>';
				modalWrite +='<li>';
				modalWrite +='<h1>최종평가</h1>';
				modalWrite +='진위형 : '+this.test04EA+'문항 (배점:'+this.test04Score+'),';
				modalWrite +='객관식 : '+this.test01EA+'문항 (배점:'+this.test01Score+'),';
				modalWrite +='단답형 : '+this.test02EA+'문항 (배점:'+this.test02Score+'),';
				modalWrite +='서술형 : '+this.test03EA+'문항 (배점:'+this.test03Score+') | ';
				modalWrite +='시간 : '+this.testTime+'분';
				modalWrite +='</li>';
				modalWrite +='<li>';
				modalWrite +='<h1>과제</h1> '+this.reportEA+'문항 (배점:'+this.reportScore+')';
				modalWrite +='</li>';
				modalWrite +='<li>';
				modalWrite +='<h1>수료기준</h1><div class="normalText">'+this.passProgress+'% 이상, 총점 '+this.passScore+' 점 이상<br />';
				modalWrite +='총점 반영 비율: 중간평가 '+this.midRate+'%, 최종평가 '+this.testRate+'%, 과제 '+this.reportRate+'%<br />';
				modalWrite +='최종평가 : '+this.totalPassTest+'점 중 최소 '+this.passTest+'점 이상<br />';
				modalWrite +='과제 : '+this.totalPassReport+' 점 중 최소 '+this.passReport+'점 이상';
				modalWrite +='</div></li>';
				modalWrite +='<li>';
				modalWrite +='<h1>과정소개</h1><div class="normalText">'+this.intro;
				modalWrite +='</div></li>';
				modalWrite +='<li>';
				modalWrite +='<h1>교육대상</h1><div class="normalText">'+this.target;
				modalWrite +='</div></li>';
				modalWrite +='<li>';
				modalWrite +='<h1>교육목표</h1><div class="normalText">'+this.goal;
				modalWrite +='</div></li>';
				modalWrite +='</ul>';
				modalWrite +='</div>';
				modalWrite +='</div>';
				modalWrite +='</div>';
			})
			$('#contents').after(modalWrite);
			modalAlign();
		})
	}

	else if(types == 'surveyView'){ //설문상세내용
		var reportStatus = '';
		var answerType = '';
		var returnCheck ='';

		modalWrite += '<div class="serveyModal">';
		modalWrite += '<h1><strong>설문 조사</strong><button type="button" onClick="modalClose()"><img src="../images/admin/btn_close.png" alt="닫기" /></button></h1>';

		$.get(modalSurveyApi,{'lectureOpenSeq':modalSeq,'userID':eachID,'contentsCode':option},function(data){
			$.each(data.survey,function(){
				modalWrite +='<div class="BBSWrite">';
				modalWrite +='<h1>'+this.contentsName+'</h1>';
				modalWrite +='<ul>';
				modalWrite +='<li>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>아이디/이름</h1>'+this.userID+' / '+this.userName;
				modalWrite +='</div>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>수강기간</h1>'+this.lectureStart+' ~ '+this.lectureEnd;
				modalWrite +='</div>';
				modalWrite +='</li>';
				modalWrite +='</ul>';
				modalWrite +='<ol>';
				$.each(this.answer,function(){
					var userAnswer = this.userAnswer;
					modalWrite +='<li>';
					$.each(this.exam,function(){
						modalWrite +='<h1>'+this.exam.replace(/\n/g,'<br />').replace(/  /g,'&nbsp;&nbsp;')+'</h1>';
						var i = 1;
						if(this.surveyType == 'A'){
							var maxNum = 4;
							if(this.example05 != undefined){
								maxNum = 5;
							}
							for(i=1; i<=maxNum; i++ ){
								if(i == this.userAnswer){
									modalWrite += '<p>'
									modalWrite += i+'번 : '+eval('this.example0'+i)
									modalWrite += '</p>'
								}
							}
							modalWrite += '</li>'
						} else {
							modalWrite +='<p>'+this.userAnswer+'</p>';
							modalWrite += '</li>'
						}
						i++;
					})
				})
				modalWrite +='</ol>';
				modalWrite +='</div></div>';				
			})
			$('#contents').after(modalWrite);
			modalAlign();
		})
	}

	//수강 신청 현황
	else if(types == 'order'){
		var i = 1;
		var totalEA = '';
		var eaTotal = 0;
		var priceTotal = 0;
		var rPriceTotal = 0;
		var lectureStart = '';
		var lectureEnd = '';

		modalWrite += '<div class="orderView">';
		modalWrite += '<h1><strong>신청 내역</strong><button type="button" onClick="modalClose()"><img src="../images/admin/btn_close.png" alt="닫기" /></button></h1>';
		modalWrite += '<div>'
		modalWrite += '<form class="paymentApproval" method="post" action="javascript:paymentApprovals()">';

		$.get(modalOrderApi,{'orderNum':eachID},function(data){
			$.each(data.order,function(){
				lectureStart = this.lectureStart;
				lectureEnd = this.lectureEnd;

				modalWrite +='<div class="BBSList">';
				modalWrite +='<h1>신청 과정</h1>';
				modalWrite +='<table><thead><tr>';
				modalWrite +='<th style="width:50px;">번호</th>';
				modalWrite +='<th >신청과정명</th>';
				modalWrite +='<th style="width:180px;">수강기간</th>';
				//modalWrite +='<th style="width:80px;">수강인원</th>';
				modalWrite +='<th style="width:80px;">교육비</th>';
				//modalWrite +='<th style="width:80px;">환급비</th>';
				modalWrite +='</thead></tr>';
				modalWrite +='<tbody>';
				modalWrite +='<input type="hidden" name="orderNum" value="'+this.orderNum+'">';
				modalWrite +='<input type="hidden" name="serviceType" value="'+this.serviceType+'">';
				modalWrite +='<input type="hidden" name="userID" value="'+this.userID+'">';
				modalWrite +='<input type="hidden" name="lectureStart" value="'+lectureStart+'">';
				modalWrite +='<input type="hidden" name="lectureEnd" value="'+lectureEnd+'">';

				modalWrite +='<tr>';
				modalWrite +='<td>'+i+'</td>';
				modalWrite +='<td>'+this.orderName+'</td>';
				modalWrite +='<td>'+lectureStart+' ~ '+lectureEnd+'</td>';
				//modalWrite +='<td>'+this.EA+'</td>';
				modalWrite +='<td>'+this.educationPrice+'</td>';
				//modalWrite +='<td>'+this.rPrice+'</td>';
				modalWrite +='</tr>';
				modalWrite +='<input type="hidden" name="contentsCode[]" value="'+this.contentsCode+'">';
				/*
				if(data.orderCount != 0){
					$.each(this.detail,function(){
						modalWrite +='<tr>';
						modalWrite +='<td>'+i+'</td>';
						modalWrite +='<td>'+this.ㅐㄱ+'</td>';
						modalWrite +='<td>'+lectureStart+' ~ '+lectureEnd+'</td>';
						//modalWrite +='<td>'+this.EA+'</td>';
						modalWrite +='<td>'+this.price+'</td>';
						//modalWrite +='<td>'+this.rPrice+'</td>';
						modalWrite +='</tr>';
						modalWrite +='<input type="hidden" name="contentsCode[]" value="'+this.contentsCode+'">';
						eaTotal = parseInt(eaTotal) + parseInt(this.EA);
						priceTotal = parseInt(priceTotal) + parseInt(this.price);
						rPriceTotal = parseInt(rPriceTotal) + parseInt(this.rPrice);
						i++;
					})
				} else {
						modalWrite +='<tr>';
						modalWrite +='<td colspan="5">신청 내역이 없습니다.</td>';
						modalWrite +='</tr>';
				}
				

				modalWrite +='<tr>';
				modalWrite +='<td colspan="3">합계</td>';
				//modalWrite +='<td>'+eaTotal+'</td>';
				modalWrite +='<td>'+priceTotal+'</td>';
				//modalWrite +='<td>'+rPriceTotal+'</td>';
				modalWrite +='</tr>';
				*/
				modalWrite +='</tbody></table>';
				modalWrite +='</div>';

				if(this.serviceType == 2){ // 사업주 개별 , 사업주
					modalWrite +='<div class="BBSWrite">';
					modalWrite +='<h1>신청자 정보</h1>';
					modalWrite +='<ul>';
					modalWrite +='<li>';
					modalWrite +='<h1>신청구분</h1>';
					modalWrite +='<div class="normalText">';
					modalWrite +=this.serviceTypeName;
					modalWrite +='</div>';
					modalWrite +='</li>';
					modalWrite +='<li>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>신청기관</h1>'+this.company.companyName+' ('+this.userID+')';
					modalWrite +='</div>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>신청일</h1>'+this.orderDate;
					modalWrite +='</div>';
					modalWrite +='</li>';
					modalWrite +='<li>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>사업주규모</h1>'+this.company.companyScale;
					modalWrite +='</div>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>교육담당자</h1>'+this.company.managerName+' ('+this.company.managerID+')';
					modalWrite +='</div>';
					modalWrite +='</li>';
					modalWrite +='<li>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>담당자 연락처</h1>'+this.company.managerPhone;
					modalWrite +='</div>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>담당자 휴대폰</h1>'+this.company.managerMobile;
					modalWrite +='</div>';
					modalWrite +='</li>';
					modalWrite +='<li>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>담당자 이메일</h1>'+this.company.managerEmail;
					modalWrite +='</div>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>영업담당자</h1>'+this.company.marketerName+' ('+this.company.marketerID+')';
					modalWrite +='</div>';
					modalWrite +='</li>';
					modalWrite +='</ul>';
					modalWrite +='</div>';

				} else { // 능력 개발, 일반(비환급)
					modalWrite +='<div class="BBSWrite">';
					modalWrite +='<h1>신청자 정보</h1>';
					modalWrite +='<ul>';
					modalWrite +='<li>';
					modalWrite +='<h1>신청구분</h1>';
					modalWrite +='<div class="normalText">';
					modalWrite +=this.serviceTypeName;
					modalWrite +='</div>';
					modalWrite +='</li>';
					modalWrite +='<li>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>신청자명</h1>'+this.userName+' ('+this.userID+')';
					modalWrite +='</div>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>신청일</h1>'+this.orderDate;
					modalWrite +='</div>';
					modalWrite +='</li>';
					modalWrite +='<li>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>사업주</h1>'+this.company.companyName;
					modalWrite +='</div>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>휴대폰</h1>'+this.mobile;
					modalWrite +='</div>';
					modalWrite +='</li>';
					modalWrite +='<li>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>이메일</h1>'+this.email;
					modalWrite +='</div>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1></h1>';
					modalWrite +='</div>';
					modalWrite +='</li>';
/*
					// 배송 정보
					modalWrite +='<div class="BBSWrite">';
					modalWrite +='<h1>배송 정보</h1>';
					modalWrite +='<ul>';
					modalWrite +='<li>';
					modalWrite +='<h1>받는분</h1>';
					modalWrite +='<div class="normalText">';
					modalWrite +=this.recipient.recipientName;
					modalWrite +='</div>';
					modalWrite +='</li>';
					modalWrite +='<li>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>연락처</h1>'+this.recipient.recipientPhone01+'-'+this.recipient.recipientPhone02+'-'+this.recipient.recipientPhone03;
					modalWrite +='</div>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>휴대폰</h1>'+this.recipient.recipientMobile01+'-'+this.recipient.recipientMobile02+'-'+this.recipient.recipientMobile03;
					modalWrite +='</div>';
					modalWrite +='</li>';
					modalWrite +='<li>';
					modalWrite +='<h1>주소</h1>';
					modalWrite +='<div class="normalText">';
					modalWrite +='('+this.recipient.recipientZipCode+') '+this.recipient.recipientAddress01+' '+this.recipient.recipientAddress02;
					modalWrite +='</div>';
					modalWrite +='</li>';
					modalWrite +='<li>';
					modalWrite +='<h1>요청사항</h1>';
					modalWrite +='<div class="normalText">';
					modalWrite +=this.recipient.recipientMemo;
					modalWrite +='</div>';
					modalWrite +='</li>';
*/
					modalWrite +='</ul>';
					modalWrite +='</div>';
				}

				modalWrite +='<div class="BBSWrite">';
				modalWrite +='<h1>결제 정보</h1>';
				modalWrite +='<ul>';
				modalWrite +='<li>';
				modalWrite +='<h1>결제상태</h1>';
				modalWrite +='<div class="normalText">';
				//modalWrite +='<form class="paymentApproval" method="post" action="javascript:paymentApprovals()">';
				modalWrite +=this.orderStatusName;
				if(loginUserLevel < '5') {				
					if(this.serviceType != '1'){
						if(this.orderStatus == 'Y'){
							modalWrite +='&nbsp;&nbsp;<button type="submit">결제취소</button>';
							modalWrite +='<input type="hidden" name="orderStatus" value="N">';
						} else {
							modalWrite +='&nbsp;&nbsp;<button type="submit">결제승인</button>';
							modalWrite +='<input type="hidden" name="orderStatus" value="Y">';
						}
					}
				}

				modalWrite +='</div>';
				modalWrite +='</li>';
				modalWrite +='<li>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>결제방식</h1>'+this.orderTypeName;
				modalWrite +='</div>';
				if(this.depositName == null){
					var depositName = '-';
				} else {
					var depositName = this.depositName;
				}
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>입금자명</h1>'+depositName;
				modalWrite +='</div>';
				modalWrite +='</li>';
				modalWrite +='<li>';
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>결제금액</h1>'+this.orderTotalPrice;
				modalWrite +='</div>';
				if(this.deliveryInvoice == null){
					var deliveryInvoice = '-';
				} else {
					var deliveryInvoice = this.deliveryInvoice;
				}
				modalWrite +='<div class="halfDiv">';
				modalWrite +='<h1>송장번호</h1>'+deliveryInvoice;
				modalWrite +='</div>';
				modalWrite +='</li>';
				modalWrite +='</ul>';
				modalWrite +='</div>';

				if(this.serviceType == '2'){ // 사업주 개별 , 사업주
					modalWrite +='<div class="BBSWrite">';
					modalWrite +='<h1>환급 정보</h1>';
					modalWrite +='<ul>';
					modalWrite +='<li>';
					modalWrite +='<h1>채점마감</h1>';
					modalWrite +='<div class="normalText">';
					modalWrite +=this.testCompleteDate;
					modalWrite +='</div>';
					modalWrite +='</li>';
					modalWrite +='<li>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>환급신청일</h1>'+this.refundApplyDate;
					modalWrite +='</div>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>환급신청지점</h1>'+this.refundApplyBranch;
					modalWrite +='</div>';
					modalWrite +='</li>';
					modalWrite +='<li>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>환급완료일</h1>'+this.refundCompleteDate;
					modalWrite +='</div>';
					modalWrite +='<div class="halfDiv">';
					modalWrite +='<h1>실 환급금액</h1>'+this.refundCompletePrice;
					modalWrite +='</div>';
					modalWrite +='</li>';
					modalWrite +='</ul>';
					modalWrite +='</div>';
				}
			})

			modalWrite +='</div></form></div></div>';
				

			$('#contents').after(modalWrite);
			modalAlign();
		})
	}
}

function reScore(apiName){
	if(confirm("첨삭강사가 다시 채점을 할 수 있도록 하시겠습니까?")){
		var sendSerial = $('form.tutorGrade').serialize();
			$.ajax({
				url: apiName,
				type:'POST',
				data:sendSerial+'&reScore=Y',
				dataType:'text',
				success:function(data){
					alert('재채점 처리 되었습니다.');
					modalClose();
					ajaxAct();
				},
				fail:function(){
					alert('오류가 발생하였습니다.')
				}
			})
	}
}

function tempGrade(apiName,types){
	var sendSerial = $('form.tutorGrade').serialize();
		$.ajax({
			url: apiName,
			type:'POST',
			data:sendSerial+'&temp=Y',
			dataType:'text',
			success:function(data){
				alert('저장 되었습니다.');
				modalClose();
				ajaxAct();
			},
			fail:function(){
				alert('저장 오류가 발생하였습니다.')
			}
		})
}

function tutorGrade(apiName,types,lectureEnd,today){
	var sendSerial = $('form.tutorGrade').serialize();
	/*if(lectureEnd >= today) {
		alert('채점완료는 '+lectureEnd+' 이후 가능합니다. 임시저장 하시기 바랍니다.');
		return;
	}*/
	if($('input[name="copyCheck"]').prop('checked')==true || types == 'tests'){
		var bTypeCnt = 0;
		var cTypeCnt = 0;
		var cTypeMsg = '';

		if(types == 'reports') { // 과제 첨삭 검사
			if($('input[name="return"]').prop('checked')==true){
				alert('반려 상태에서는 채점완료 하실 수 없습니다. 임시저장 하시기 바랍니다.');
				return;
			}else if($('select[name="reportScore"]').val() == ''){
				alert('점수를 입력해 주시기 바랍니다.');
				return;
			}else if($('textarea[name="comment"]').val() == ''){
				alert('첨삭지도를 작성해 주시기 바랍니다.');
				return;
			}else if($('textarea[name="comment"]').val().length < 30){
				alert('첨삭지도를 더 자세하게 작성해 주시기 바랍니다.');
				return;
			}

		} else { // 중간, 최종평가 첨삭 검사
			$('#modal select[name="rightAnswer[]"]').each(function(){
				if(($(this).length) != 0 && $(this).val() == ''){
					bTypeCnt ++
				}
			})
			$('#modal select[name="cTypeScore[]"]').each(function(){
				var textareaSelect = $(this).parent('td').parent('tr').parent('tbody').find('textarea[name="correct[]"]')
				if(($(this).length) != 0 && (textareaSelect.val() == '' || textareaSelect.val().length < 30)){
					cTypeCnt ++
				}
				if($(this).val() == ''){
					cTypeMsg = '서술형 점수를 입력해 주세요.';
				}else if(textareaSelect.val() == ''){
					cTypeMsg = '첨삭지도를 작성해 주시기 바랍니다.';
				}else if(textareaSelect.val().length < 30){
					cTypeMsg = '첨삭지도를 더 자세하게 작성해 주시기 바랍니다.';
				}
			})
		}
		if(bTypeCnt == 0 && cTypeCnt == 0){
			if(confirm("채점완료 하시겠습니까? 완료 후에는 수정하실 수 없습니다.")){
				$.ajax({
					url: apiName,
					type:'POST',
					data:sendSerial,
					dataType:'text',
					success:function(data){
						alert('채점 반영되었습니다.');
						modalClose();
						ajaxAct();
					},
					fail:function(){
						alert('채점에 실패하였습니다.')
					}
				})
			}
		}else{
			if(bTypeCnt != 0){
				alert('단답형 채점이 안된 항목이 있습니다.')
			}else if(cTypeCnt != 0){
				alert(cTypeMsg)
			}
		}
	}else{
		alert('모사율 조회하기 실행 후 첨삭완료가 가능합니다.')
	}
}

function paymentApprovals(){
	var sendSerial = $('form.paymentApproval').serialize();
	if(confirm("결제상태를 변경하시겠습니까?")){
		$.ajax({
			url: '/api/apiStudy.php',
			type:'POST',
			data:sendSerial,
			dataType:'text',
			success:function(){
				alert('변경되었습니다.');
				modalClose();
				ajaxAct();
			},
			fail:function(){
				alert('변경실패.');
			}
		})
	}
}


function keyCheck(event){
	if (event.keyCode == 86 && event.ctrlKey){
		alert('붙여넣기는 불가능합니다.\r\r(산업인력공단 규정내용 : 교강사가 첨삭을 진행할 때 복사 및 붙여넣기 기능이 동작되지 않도록 유도)');
		event.returnValue = false;
	}
}

function mosaCheckTest(seq){
	$.ajax({
		type:"POST",
		url:'../api/apiTestCopyCheck.php',
		dataType:'JSON',
		data:{'seq':seq},
		success: function(data){
			alert(data.result);
		},
		error: function(xhr, status) {
			XMLLoader.error(xhr, status)
		}
    });
}


function mosaCheck(seq){
	$.ajax({
		type:"POST",
		url:'../api/apiReportCopyCheck.php',
		dataType:'JSON',
		data:{'seq':seq},
		success: function(data){
			alert(data.result);
			$('input[name="copyCheck"]').prop('checked',true);
		},
		error: function(xhr, status) {
			XMLLoader.error(xhr, status)
		}
    });
}

function certPassOK(userID, lectureStart, adminID){
	if(confirm('인증제외처리 하시겠습니까? \r\r사유가 될 수 있는 자료를 보관하시기 바랍니다.')) {
		$.ajax({
			url:'../api/apiStudyChapter.php',
			type:'POST',
			data:{'userID':userID,'lectureStart':lectureStart,'certPass':'Y','adminID':adminID},
			success:function(){
				alert('인증처리 되었습니다.');
				modalClose();
			}
		})
	}
}