//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//리스트 소팅
function listAct(page){
	
	//상단 액션 부분	
	var actionArea = '';
	var today = new Date();
	var thisYear = today.getFullYear();
	var thisMonth = today.getMonth()+1; //January is 0!
	var thisDate = today.getDate();
	actionArea += '<div class="searchArea"><form class="searchForm" action="javascript:searchAct()">';
	actionArea += '<div class="searchChangeArea">'
    actionArea += '<input type="radio" name="selectSearch" id="searchDate" value="searchDate" checked="checked" onChange="searchTypeSelect(this.value)" /><label for="searchDate">기간검색</label>&nbsp;&nbsp;&nbsp;'

	if(loginUserLevel != '7') {
	    actionArea += '|&nbsp;<input type="radio" name="selectSearch" id="searchCompany" value="searchCompany" id="searchCompany" onChange="searchTypeSelect(this.value)" /><label for="searchCompany">사업주검색</label>&nbsp;&nbsp;&nbsp;&nbsp;';
	}

	actionArea += '<select name="searchYear" onchange="searchStudy(\'lectureDay\')">';
	var i= '';
	var thisYear = today.getFullYear();
	for(i= 2015; i <= thisYear; i++){
		if(i != thisYear){
			actionArea += '<option>'+i+'년</option>';
		}else{
			actionArea += '<option selected="selected">'+i+'년</option>';
		}
		
	}
    actionArea += '</select>';
	actionArea += '<select name="searchMonth" onchange="searchStudy(\'lectureDay\')">';
	var h= '';
	var thisMonth = today.getMonth()+1; //January is 0!
	for(h = 1; h <= 12; h++){
		if(h != thisMonth){
			actionArea += '<option>'+h+'월</option>';
		}else{
			actionArea += '<option selected="selected">'+h+'월</option>';
		}
		
	}
    actionArea += '</select>';
	actionArea += '</div><div>';
    actionArea += '<span>수강생</span>';
    actionArea += '<input type="text" style="width:100px;" name="userName">';
    actionArea += '<span>진도율</span>';
    actionArea += '<input type="text" style="width:50px;" name="progress01">% ~ <input type="text" style="width:50px;" name="progress02">%';
    actionArea += '<span>평가시험</span>';
	actionArea += '<select name="testStatus">';
    actionArea += '<option value="">전체</option>';
    actionArea += '<option value="C">응시(채점완료)</option>';
	actionArea += '<option value="Y">응시(채점대기중)</option>';
    actionArea += '<option value="N">미응시</option>';
    actionArea += '</select>';
	actionArea += '</div><div>';
    actionArea += '<span>과제</span>';
	actionArea += '<select name="reportStatus">';
    actionArea += '<option value="">전체</option>';
    actionArea += '<option value="C">응시(채점완료)</option>';
	actionArea += '<option value="Y">응시(채점대기중)</option>';
    actionArea += '<option value="N">미응시</option>';
    actionArea += '<option value="R">반려</option>';
    actionArea += '</select>&nbsp;';
    actionArea += '<span>모사답안</span>';
	actionArea += '<select name="mosa">';
    actionArea += '<option value="">전체</option>';
    actionArea += '<option value="Y">모사답안의심</option>';
    actionArea += '</select>';
	actionArea += '<span>페이지 수</span>';
	actionArea += '<select name="listCount" onchange="listCountUpdate(this.value)">';
    actionArea += '<option value="100">100개</option>';
    actionArea += '<option value="150">150개</option>';
    actionArea += '<option value="200">200개</option>';
    actionArea += '<option value="300">300개</option>';
    actionArea += '</select>';
    actionArea += '</div>';
	actionArea += '<button type="button" onClick="searchAct()" class="allWidth">검색</button></form>';
	actionArea += '</form>';
	actionArea += '</div>';
	

	actionArea += '<div class="smsSend">';
	actionArea += '<form class="sendSMSForm" method="post" action="javascript:sendMessege()">';
	actionArea += '<div>';
    actionArea += '<span>발송시간</span>';
	actionArea += '<select name="sendReserve">';
    actionArea += '<option value="N">즉시발송</option>';
    actionArea += '<option value="Y">시간예약</option>';
    actionArea += '</select><br />';
	/*
	actionArea += '<span>전송방법</span>';
	actionArea += '<select name="device">';
	actionArea += '<option value="sms">SMS발송</option>';
	actionArea += '<option value="email">Email발송</option>';
	actionArea += '</select><br />'
	*/
    actionArea += '<span>예약시간</span>';
    actionArea += '<select name="reserveTimeYear">';
	for(i=0;i<3;i++){
		actionArea += '<option value="'+(thisYear+i)+'">'+(thisYear+i)+'</option>';
	}
	actionArea += '</select>&nbsp;';
    actionArea += '<select name="reserveTimeMonth">';
	for(i=1;i<=12;i++){
		actionArea += '<option value="'+i+'" ';
		if(i==thisMonth){
			actionArea += 'selected="selected"'
		}
		actionArea += '>'+i+'월</option>';
	}
	actionArea += '</select>&nbsp;';
    actionArea += '<select name="reserveTimeDay">';
	for(i=1;i<=31;i++){
		actionArea += '<option value="'+i+'" ';
		if(i==thisDate){
			actionArea += 'selected="selected"'
		}
		actionArea += '>'+i+'일</option>';
	}
	actionArea += '</select>&nbsp;|&nbsp;';
    actionArea += '<select name="reserveTimeHour">';
	for(i=0;i<=23;i++){
		actionArea += '<option value="'+i+'">'+i+'시</option>';
	}
	actionArea += '</select>&nbsp;';
    actionArea += '<select name="reserveTimeMinute">';
	for(i=0;i<=5;i++){
		actionArea += '<option value="'+(i*10)+'">'+(i*10)+'분</option>';
	}
    actionArea += '<input type="hidden" name="reserveTime"><br />';;
	actionArea += '<span>직접작성</span>';
	actionArea += '<input type="checkbox" id="messageBox" name="messageBox" value="Y"><label for="messageBox"></label>';
	actionArea += '<input type="text" style="width:50px;" name="messagebyte" value="0" size="1" maxlength="2" readonly> / 2000 byte/ ';
	actionArea += '<input type="text" style="width:50px;" name="messageType" value="SMS" size="1" maxlength="3" readonly><br />';
	actionArea += '<span>.</span>';
	actionArea += '90byte를 초과하면 LMS(장문)으로 발송됩니다.';
	actionArea += '</div>';
	actionArea += '<div>';
	actionArea += '<textarea id="sendMessage" name="sendMessage" onFocus="clearMessage(this);" onKeyUp="checkByte(this);">메세지를 입력해주세요</textarea>';
	actionArea += '</div>';
	actionArea += '<div>';
	actionArea += '<div class="pager">';
	actionArea += '</div>';
	actionArea += '<button type="button" onClick="sendMessege(\'sms\')">SMS 발송</button>';
	actionArea += '<button type="button" onClick="sendMessege(\'email\')">Email 발송</button>';
	actionArea += '</div>';
	actionArea += '</form>';
	actionArea += '</div>';



	$('#contents > h1').after(actionArea);

	//게시물 소팅부분
	var contents = '';
	contents += '<form class="sendSMS" method="post">';
	contents += '<div class="scrollArea">';
	contents += '<table style="min-width:1420px;"><thead><tr>';
	contents += '<th style="width:50px;">번호</th>';
	contents += '<th style="width:100px;">ID<br />이름</th>';
	contents += '<th style="width:300px;">과정명<br />수강기간</th>';
	contents += '<th style="width:70px;">진도율</th>';
	contents += '<th style="width:90px;">중간평가<br />최종평가<br />과제</th>';
	contents += '<th style="width:105px;"><input type="checkbox" id="sendStart" onChange="checkboxAllCheck(\'sendStart\')" /><label for="sendStart">학습시작</label></th>';
	contents += '<th style="width:105px;"><input type="checkbox" id="send0" onChange="checkboxAllCheck(\'send0\')" /><label for="send0">0% 미만</label></th>';
	contents += '<th style="width:105px;"><input type="checkbox" id="send30" onChange="checkboxAllCheck(\'send30\')" /><label for="send30">30% 미만</label></th>';
	contents += '<th style="width:105px;"><input type="checkbox" id="send50" onChange="checkboxAllCheck(\'send50\')" /><label for="send50">50% 미만</label></th>';
	contents += '<th style="width:105px;"><input type="checkbox" id="send70" onChange="checkboxAllCheck(\'send70\')" /><label for="send70">70% 미만</label></th>';
	contents += '<th style="width:105px;"><input type="checkbox" id="sendFinal" onChange="checkboxAllCheck(\'sendFinal\')" /><label for="sendFinal">최종 독려</label></th>';
	contents += '<th style="width:105px;"><input type="checkbox" id="sendEnd" onChange="checkboxAllCheck(\'sendEnd\')" /><label for="sendEnd">수강 종료</label></th>';
	contents += '<th style="width:105px;"><input type="checkbox" id="sendEnd" onChange="checkboxAllCheck(\'sendEnd\')" /><label for="sendEnd">결과 확인</label></th>';
	contents += '<th style="width:150px;">사업주</th>';
	contents += '</tr></thead><tbody>'	;
	contents += '</tbody></table>';
	contents += '</div>'
	contents += '</form>';
    contents += '<div class="pager">';
	contents += '</div>';
	contents += '<div class="btnArea">';
	contents += '<button type="button" onClick="sendMessege(\'sms\')">SMS 발송</button>';
	contents += '<button type="button" onClick="sendMessege(\'email\')">Email 발송</button>';
	contents += '</div>';
	$('#contentsArea').removeAttr('class');
	$('#contentsArea').addClass('BBSList');
	$('#contentsArea').html(contents);
	//ajaxAct();
	var thisYear = today.getFullYear();
	var thisMonth = today.getMonth()+1; //January is 0!
	if(thisMonth <= 9){
		thisMonth = '0'+thisMonth;
	}
	var checkDate = thisYear +'-'+thisMonth;
	searchStudy('lectureDay',checkDate)
}

function ajaxAct(sortDatas){
	top.location.href='#';
	sortDatas = sortDatas ? sortDatas : '';
	if(sortDatas != ''){
		sortData = sortDatas
	}	
	var listAjax = $.get(useApi,'page='+page+'&userLevel='+userLevel+'&list='+listCount+sortData,function(data){ //+listCount
		totalCount = data.totalCount;
		var lists = '';
		var midStatus = '';
		var testStatus = '';
		var reportStatus = '';
		var i = totalCount;
		if(page != 1){
			i = totalCount - ((page-1)*listCount);
		}
		if (totalCount != 0 && loginUserLevel <= userLevel){
			$.each(data.study,  function(){
					lists += '<tr>';
					lists += '<td>'+i+'</td>';
					lists += '<td onClick="globalModalAct(\'memberView\',\'\',\''+this.user.userID+'\')" style="cursor:pointer;">'+this.user.userID+'<br/>';
					lists += this.user.userName+'</td>';
					if(this.user.smsReceive == 'N') {
						var smsReceive = '<img src="../images/global/qicon.jpg" style="width:15px;" title="선택하여 발송해도 실제로는 발송되지 않습니다."><strong class="red">(문자수신거부)</strong> ';
					} else {
						var smsReceive = '';
					}
					if(this.user.emailReceive == 'N') {
						var emailReceive = '<img src="../images/global/qicon.jpg" style="width:15px;" title="선택하여 발송해도 실제로는 발송되지 않습니다."><strong class="red">(메일수신거부)</strong> ';
					} else {
						var emailReceive = '';
					}
					lists += '<td onClick="globalModalAct(\'contentsView\',\'\',\''+this.contents.contentsCode+'\')" style="cursor:pointer;">'+this.contents.contentsName+'<br/>';
					lists += this.lectureStart+' ~ '+this.lectureEnd+'<br />'+smsReceive+emailReceive+'</td>';
					lists += '<td onClick="globalModalAct(\'progressView\','+this.lectureOpenSeq+',\''+this.user.userID+'\')" style="cursor:pointer;">'+this.progress+'%</td>';

					if(this.serviceType == '3') { // 비환급인 경우 평가없음
						midStatus = '평가없음';
					} else {
						if(this.midStatus == 'N' || this.midStatus == 'V') { // 미응시
							midStatus = '<strong class="red">미응시</strong>';
						} else if(this.midStatus == 'Y') { // 응시
							midStatus = '채점 대기중';
						} else if(this.midStatus == 'C') { // 채점 완료
							midStatus = this.midScore;
						} else {
							midStatus = "평가 없음";
						}
					}

					if(this.serviceType == '3') { // 비환급인 경우 평가없음
						testStatus = '평가없음';
					} else {
						if(this.testStatus == 'N') { // 미응시
							testStatus = '<strong class="red">미응시</strong>';
						} else if(this.testStatus == 'Y' || this.testStatus == 'V') { // 응시
							testStatus = '채점 대기중';
						} else if(this.testStatus == 'C') { // 채점 완료
							testStatus = this.testScore;
						} else {
							testStatus = "평가 없음";
						}
					}

					if(this.serviceType == '3') { // 비환급인 경우 평가없음
						reportStatus = '평가없음';
					} else {
						if(this.reportStatus == null) { // 과제 없는 과정
							reportStatus = '과제 없음';
						} else if(this.reportStatus == 'N') { // 미응시
							reportStatus = '<strong class="red">미응시</strong>';
						} else if(this.reportStatus == 'Y') { // 응시
							reportStatus = '채점 대기중';
						} else if(this.reportStatus == 'R') { // 반려
							reportStatus = '<strong class="red">반려</strong>';
						} else if(this.reportStatus == 'C') { // 채점 완료
							reportStatus = this.reportScore;
						}
					}

					lists += '<td>'+midStatus+'<br />'+testStatus+'<br />'+reportStatus+'</td>';
					lists += '<td><input type="checkbox" id="sendStart_'+this.seq+'" name="sendType[]" class="sendStart" value="start/'+this.user.userID+'/'+this.lectureOpenSeq+'" /><label for="sendStart_'+this.seq+'">선택하기</label><br /><button type="button" onclick="viewMessage(\'start/'+this.user.userID+'/'+this.lectureOpenSeq+'\')">내용보기</button></td>';
					lists += '<td><input type="checkbox" id="send0_'+this.seq+'" name="sendType[]" class="send0" value="0/'+this.user.userID+'/'+this.lectureOpenSeq+'" /><label for="send0_'+this.seq+'">선택하기</label><br /><button type="button"  onclick="viewMessage(\'0/'+this.user.userID+'/'+this.lectureOpenSeq+'\')">내용보기</button></td>';
					lists += '<td><input type="checkbox" id="send30_'+this.seq+'" name="sendType[]" class="send30" value="30/'+this.user.userID+'/'+this.lectureOpenSeq+'" /><label for="send30_'+this.seq+'">선택하기</label><br /><button type="button"  onclick="viewMessage(\'30/'+this.user.userID+'/'+this.lectureOpenSeq+'\')">내용보기</button></td>';
					lists += '<td><input type="checkbox" id="send50_'+this.seq+'" name="sendType[]" class="send50" value="50/'+this.user.userID+'/'+this.lectureOpenSeq+'" /><label for="send50_'+this.seq+'">선택하기</label><br /><button type="button"  onclick="viewMessage(\'50/'+this.user.userID+'/'+this.lectureOpenSeq+'\')">내용보기</button></td>';
					lists += '<td><input type="checkbox" id="send70_'+this.seq+'" name="sendType[]" class="send70" value="70/'+this.user.userID+'/'+this.lectureOpenSeq+'" /><label for="send70_'+this.seq+'">선택하기</label><br /><button type="button"  onclick="viewMessage(\'70/'+this.user.userID+'/'+this.lectureOpenSeq+'\')">내용보기</button></td>';
					lists += '<td><input type="checkbox" id="sendFinal_'+this.seq+'" name="sendType[]" class="sendFinal" value="final/'+this.user.userID+'/'+this.lectureOpenSeq+'" /><label for="sendFinal_'+this.seq+'">선택하기</label><br /><button type="button" onclick="viewMessage(\'final/'+this.user.userID+'/'+this.lectureOpenSeq+'\')">내용보기</button></td>';
					lists += '<td><input type="checkbox" id="sendEnd_'+this.seq+'" name="sendType[]" class="sendEnd" value="end/'+this.user.userID+'/'+this.lectureOpenSeq+'" /><label for="sendEnd_'+this.seq+'">선택하기</label><br /><button type="button"  onclick="viewMessage(\'end/'+this.user.userID+'/'+this.lectureOpenSeq+'\')">내용보기</button></td>';
					lists += '<td><input type="checkbox" id="sendResult_'+this.seq+'" name="sendType[]" class="sendResult" value="result/'+this.user.userID+'/'+this.lectureOpenSeq+'" /><label for="sendResult'+this.seq+'">선택하기</label><br /><button type="button"  onclick="viewMessage(\'result/'+this.user.userID+'/'+this.lectureOpenSeq+'\')">내용보기</button></td>';
					lists += '<td onClick="globalModalAct(\'companyView\',\'\','+this.company.companyCode+')" style="cursor:pointer;">'+this.company.companyName+'</td>';
					lists += '</tr>';
					lists += '<input type="hidden name="userID" value="'+this.user.userID+'">';
					i--;
			})
		}else if(loginUserLevel > userLevel){
			lists += '<tr><td class="notResult" colspan="14">조회 권한이 없습니다.</td></tr>'
		}else{
			lists += '<tr><td class="notResult" colspan="14">검색 결과가 없습니다.</td></tr>'
		}
		$('.BBSList tbody').html(lists);
		pagerAct();
	})
}

//검색관련

function searchTypeSelect(types){
	$('.searchArea div.searchChangeArea select, .searchArea div.searchChangeArea input[type="text"]').remove();
	var chageSearch =''
	if(types == 'searchDate'){
		chageSearch += '<select name="searchYear" onchange="searchStudy(\'lectureDay\')">';
		var today = new Date();
		var i= '';
		var thisYear = today.getFullYear();
		for(i= 2015; i <= thisYear; i++){
			if(i != thisYear){
				chageSearch += '<option>'+i+'년</option>';
			}else{
				chageSearch += '<option selected="selected">'+i+'년</option>';
			}
			
		}
		chageSearch += '</select>';
		chageSearch += '<select name="searchMonth" onchange="searchStudy(\'lectureDay\')">';
		var h= '';
		var thisMonth = today.getMonth()+1; //January is 0!
		for(h = 1; h <= 12; h++){
			if(h != thisMonth){
				chageSearch += '<option>'+h+'월</option>';
			}else{
				chageSearch += '<option selected="selected">'+h+'월</option>';
			}
			
		}
		chageSearch += '</select>';	
	}else if(types == 'searchCompany'){
		chageSearch += '<input type="text" name="searchCompany" onkeyup="searchStudy(\'company\',this)">';	
	}
	$('.searchArea div.searchChangeArea').append(chageSearch)
	
	if(types == 'searchDate'){
		var thisYear = today.getFullYear();
		var thisMonth = today.getMonth()+1; //January is 0!
		if(thisMonth <= 9){
			thisMonth = '0'+thisMonth;
		}
		var checkDate = thisYear +'-'+thisMonth;
		searchStudy('lectureDay',checkDate)
	}
	//actionArea += '<input type="text" name="searchCompany" onkeyup="searchStudy(\'company\',this)" />'
}

function searchStudy(types,vals){
	if(types=='lectureDay'){
		$('select[name="lectureDay"], strong.noticeSearch, select[name="companyCode"]').remove();
		var dateChain = ''
		dateChain += $('select[name="searchYear"]').val().replace('년','') +'-';
		if(eval($('select[name="searchMonth"]').val().replace('월','')) < 10){
			dateChain += '0'+$('select[name="searchMonth"]').val().replace('월','');
		}else{
			dateChain += $('select[name="searchMonth"]').val().replace('월','');
		}
		$.get(chainsearchApi,{'searchMode':types, 'searchDay':dateChain},function(data){
			var selectWrite = ''
			if(data.totalCount !=0){
				selectWrite += '<select name="lectureDay" onChange="searchStudy(\'printCompany\',this);searchAct()">';
				selectWrite += '<option value="">기간을 선택해주세요</option>'
				$.each(data.searchResult,function(){
					selectWrite += '<option value="'+this.lectureStart+'~'+this.lectureEnd+'">'+this.lectureStart+'~'+this.lectureEnd+'</option>';
				})
				selectWrite += '</select>'	
			}else{
				selectWrite += '<strong class="noticeSearch price">&nbsp;&nbsp;&nbsp;검색결과가 없습니다.</option>'
			}		
			$('select[name="searchMonth"]').after(selectWrite)
		})
	}else if(types=='company'){
		$('select[name="companyCode"], strong.noticeSearch').remove();
		var searchName = vals.value
		if( searchName != ''&& searchName != ' ' ){
			$.get(chainsearchApi,{'searchMode':types, 'searchName':searchName},function(data){
				var selectWrite = ''
				if(data.totalCount !=0){
					selectWrite += '<select name="companyCode" onChange="searchStudy(\'printDate\',this);searchAct()">';
					selectWrite += '<option value="">사업주를 선택하세요</option>'
					$.each(data.searchResult, function(){
						selectWrite += '<option value="'+this.searchCode+'">'+this.searchName+'&nbsp;|&nbsp;'+this.searchCode+'</option>';
					})
					selectWrite += '</select>'	
				}else{
					selectWrite += '<strong class="noticeSearch price">&nbsp;&nbsp;&nbsp;검색결과가 없습니다.</option>'
				}
				$('input[name="searchCompany"]').after(selectWrite)
	
			})
		}else{
			$('.searchChangeArea select, strong.noticeSearch').remove();
		}
	}else if(types=='printCompany'){
		$('strong.noticeSearch, select[name="companyCode"]').remove();
		var searchDate = vals.value
		$.get(chainsearchApi,{'searchMode':'study', 'lectureDay':searchDate},function(data){
			var selectWrite = ''
			if(data.totalCount !=0){
				selectWrite += '<select name="companyCode" onChange="searchAct()">';
				selectWrite += '<option value="">사업주를 선택하세요</option>'
				$.each(data.searchResult,function(){
					selectWrite += '<option value="'+this.companyCode+'">'+this.companyName+'&nbsp;|&nbsp;'+this.companyCode+'</option>';
				})
				selectWrite += '</select>'	
			}else{
				selectWrite += '<strong class="noticeSearch price">&nbsp;&nbsp;&nbsp;검색결과가 없습니다.</option>'
			}
			$('select[name="lectureDay"]').after(selectWrite)
		})
	}else if(types=='printDate'){
		$('select[name="lectureDay"], strong.noticeSearch').remove();
		var companyCode = vals.value;
		$.get(chainsearchApi,{'searchMode':'study', 'companyCode':companyCode},function(data){
			var selectWrite = ''
			if(data.totalCount !=0){
				selectWrite += '<select name="lectureDay" onChange="searchAct()">';
				selectWrite += '<option value="">기간을 선택해주세요</option>'
				$.each(data.searchResult,function(){
					selectWrite += '<option value="'+this.lectureStart+'~'+this.lectureEnd+'">'+this.lectureStart+'~'+this.lectureEnd+'</option>';
				})
				selectWrite += '</select>'	
			}else{
				selectWrite += '<strong class="noticeSearch price">&nbsp;&nbsp;&nbsp;검색결과가 없습니다.</option>'
			}		
			$('select[name="companyCode"]').after(selectWrite)
		})
	}
}

function viewMessage(values){
	var modalWrite =''
	modalWrite +='<div id="modal">';
	modalWrite += '<div class="messageView">';
	modalWrite += '<h1>발송메세지 확인<button type="button" onClick="modalClose()"><img src="../images/admin/btn_close.png" alt="닫기" /></button></h1>';
	modalWrite += '<div class="BBSWrite">'
	modalWrite += '<h1>메세지 구분'
	modalWrite += '<select onchange="viewMessageType(\''+values+'\',this)">';
	modalWrite += '<option value="sms" selected="selected">문자메시지</option>'
	modalWrite += '<option value="email">이메일</option>'
	modalWrite += '</select>';
	modalWrite += '</h1>'
	modalWrite += '<ul>'
	modalWrite += '</ul>'
	modalWrite += '<div class="btnArea">'
	modalWrite += '<button type="button" onclick="sendMessege(\'modal\')">메세지 보내기</button>'
	modalWrite += '<button type="button" onclick="modalClose()">취소</button>'
	modalWrite += '</div>'
	modalWrite +='</div>';	
	$('#contents').after(modalWrite)
	modalAlign();
	viewMessageType(values)
}
function viewMessageType(values,obj){
	obj = obj ? obj : ''
	var types = ''
	if(obj != ''){
		types = obj.options[obj.selectedIndex].value;
	}else{
		types = 'sms'
	}
	var messageWrite = ''
	$.get(smsApi,{'sendType':values,'device':types},function(data){
		messageWrite += '<li><h1>발송 대상</h1>'+data.userName+' <span>('+data.userID+')</span></li>'
		if(types == 'sms'){
			messageWrite += '<li><h1>연락처</h1>'+data.receiveNum;
		}else{
			messageWrite += '<li><h1>이메일</h1>'+data.email;
		}
		messageWrite += '<li><h1>전송타입</h1>'+data.typeName;
		messageWrite += '<li>'
		messageWrite += '<form class="smsSendModal">'
		messageWrite += '<input type="hidden" name="sendType[]" value="'+values+'" />'
		messageWrite += '<input type="hidden" name="device" value="'+types+'" />'
		messageWrite += '<input type="hidden" name="messageBox" value="Y" />'
		messageWrite += '<textarea id="sendMessage"  name="sendMessage" onKeyUp="checkByte(this);">'+data.message+'</textarea><br />';
		messageWrite += '<input type="text" name="messagebyte" value="0" size="1" maxlength="2" readonly> / 2000 byte / ';
		messageWrite += '<input type="text" style="width:50px;" name="messageType" value="SMS" size="1" maxlength="3" readonly><br />';
		messageWrite += '</form>'
		messageWrite += '</li>'
		$('#modal div.BBSWrite ul').html(messageWrite)
	})
}

function sendMessege(types){
	types = types ? types : '';
	var sendData = ''
	if(types == 'email' || types == 'sms' || types == ''){
		sendData = $('form.sendSMS').serialize();
		if(types == 'email'){
			sendData += '&device=email'
		}else if(types == 'sms'){
			sendData += '&device=sms'
		}
		var reservTime = '';
		reservTime += $('.sendSMSForm select[name="reserveTimeYear"] option:selected').val() + '-';
		reservTime += $('.sendSMSForm select[name="reserveTimeMonth"] option:selected').val() + '-';
		reservTime += $('.sendSMSForm select[name="reserveTimeDay"] option:selected').val() + ' ';
		reservTime += $('.sendSMSForm select[name="reserveTimeHour"] option:selected').val() + ':';
		reservTime += $('.sendSMSForm select[name="reserveTimeMinute"] option:selected').val() + ':00';
		$('.sendSMSForm input[name="reserveTime"]').val(reservTime)
		sendData += '&'
		sendData += $('.sendSMSForm').serialize();
	}else if(types == 'modal'){
		sendData = $('.smsSendModal').serialize();
	}
	if(confirm('발송하시겠습니까?') == true){
		loadingAct();
		$.post(smsApi,sendData,function(data){
			if(data.result == 'success'){
				alert('발송이 완료되었습니다.')
				loadingAct();
			}else{
				alert('발송이 되지 않았습니다.')
				loadingAct();
			}
		})
	}
}



var clearChk=true;
var limitByte = 2000; //바이트의 최대크기, limitByte 를 초과할 수 없슴
// textarea에 마우스가 클릭되었을때 초기 메시지를 클리어
function clearMessage(frm){
	frm = $(frm);
	var frmCHK = $(frm).html();
	if(frmCHK == '메세지를 입력해주세요'){
		frm.html('');
	}else if(frmCHK == '' || frmCHK == ' '){
		frm.html('메세지를 입력해주세요');
	}
	clearChk=false;

}

// textarea에 입력된 문자의 바이트 수를 체크
function checkByte(frm) {
	var messageType;
	var totalByte = 0;
	var message = frm.value;
	for(var i =0; i < message.length; i++) {
		var currentByte = message.charCodeAt(i);
		if(currentByte > 128) totalByte += 2;
		else totalByte++;
		if(totalByte > 90) {
			messageType = 'LMS';
		} else {
			messageType = 'SMS';
		}
		
		$('.sendSMSForm input[name="messagebyte"]').val(totalByte)
		$('.sendSMSForm input[name="messageType"]').val(messageType)
        //frm.messagebyte.value = totalByte;

        if(totalByte > limitByte) {
            alert( limitByte+"바이트까지 전송가능합니다.");
			frm.sendMessage.value = message.substring(0,limitByte);
		}
    }
}