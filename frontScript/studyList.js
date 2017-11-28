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
	actionArea += '<option value="">전체</option>';
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
	actionArea += '</div>';
	actionArea += '<div>';
	actionArea += '<span>이름,ID</span>';
	actionArea += '<select name="searchType">';
	actionArea += '<option value="searchUserName">수강생</option>';
	actionArea += '<option value="searchMarketer">영업담당자</option>';
	actionArea += '<option value="searchTutor">교강사</option>';
	actionArea += '</select>';
	actionArea += '<input type="text" style="width:100px;margin-left:5px;" name="searchValue">';
    actionArea += '<span>진도율</span>';
    actionArea += '<input type="text" style="width:50px;" name="progress01">% ~ <input type="text" style="width:50px;" name="progress02">%';
    actionArea += '<span>첨삭정렬</span>';
	actionArea += '<select name="correct">';
    actionArea += '<option value="">전체</option>';
    actionArea += '<option value="N">미첨삭만 보기</option>';
    actionArea += '</select>';
    actionArea += '<span>수료여부</span>';
	actionArea += '<select name="passOK">';
    actionArea += '<option value="">전체</option>';
    actionArea += '<option value="Y">수료</option>';
	actionArea += '<option value="N">미수료</option>';
    actionArea += '</select>';
    actionArea += '<span>환급여부</span>';
	actionArea += '<select name="serviceType">';
    actionArea += '<option value="">전체</option>';
    actionArea += '<option value="1">환급(사업주)</option>';
	actionArea += '<option value="3">비환급(일반)</option>';
    actionArea += '</select>'; 
	actionArea += '</div>';
	actionArea += '<div>';
	actionArea += '<span>과정명</span>';
    actionArea += '<input type="text" style="width:100px;" name="contentsName">';
    actionArea += '<span>중간평가</span>';
	actionArea += '<select name="midStatus">';
    actionArea += '<option value="">전체</option>';
    actionArea += '<option value="C">응시(채점완료)</option>';
	actionArea += '<option value="Y">응시(채점대기중)</option>';
    actionArea += '<option value="N">미응시</option>';
    actionArea += '</select>';
    actionArea += '<span>최종평가</span>';
	actionArea += '<select name="testStatus">';
    actionArea += '<option value="">전체</option>';
    actionArea += '<option value="C">응시(채점완료)</option>';
	actionArea += '<option value="Y">응시(채점대기중)</option>';
    actionArea += '<option value="N">미응시</option>';
    actionArea += '</select>';
    actionArea += '<span>과제</span>';
	actionArea += '<select name="reportStatus">';
    actionArea += '<option value="">전체</option>';
    actionArea += '<option value="C">응시(채점완료)</option>';
	actionArea += '<option value="Y">응시(채점대기중)</option>';
    actionArea += '<option value="N">미응시</option>';
    actionArea += '<option value="R">반려</option>';
    actionArea += '</select>';

	actionArea += '<span>모사답안</span>';
	actionArea += '<select name="reportCopy">';
    actionArea += '<option value="">전체</option>';
    actionArea += '<option value="D">모사답안의심</option>';
    actionArea += '<option value="Y">모사답안</option>';
    actionArea += '</select>';
	actionArea += ' <button type="button" onClick="excelAct()" >엑셀 다운로드</button>';
	actionArea += '</div>';
	actionArea += '<button type="submit" style="width:100%">검색</button></form>';
	actionArea += '</form></div>';
	$('#contents > h1').after(actionArea);
	
	//게시물 소팅부분
	var contents = '';
	contents += '<table><thead><tr>';
	contents += '<th style="width:50px;">번호/구분</th>';
	contents += '<th style="width:80px;">ID<br />이름</th>';
	contents += '<th style="width:300px;">과정명<br />수강기간</th>';
	contents += '<th style="width:80px;">진도율</th>';
	contents += '<th style="width:150px;">중간평가<br />응시일</th>';
	contents += '<th style="width:150px;">최종평가<br />응시일</th>';
	contents += '<th style="width:150px;">과제<br />제출일</th>';
	contents += '<th style="width:80px;">총점<br />수료여부</th>';
	if(loginUserLevel < '5') {
		contents += '<th style="width:80px;">교ㆍ강사</th>';
	}
	contents += '<th style="width:180px;">사업주<br />(현재소속과 다를 수 있음)</th>';
	if(loginUserLevel < '5') {
		contents += '<th style="width:80px;">재응시처리</th>';
		contents += '<th style="width:80px;">등록자<br />등록일</th>';
		contents += '<th style="width:80px;">삭제</th>';
	}
	contents += '</tr></thead><tbody>'	;
	contents += '<tr><td class="notResult" colspan="100%">검색값을 선택하세요.</td></tr>'	;
	contents += '</tbody></table>';
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

function excelAct(){
	searchValue = $('.searchForm').serialize();
	searchValue = '&'+searchValue;
	top.location.href='progress.php?'+searchValue;
}

function ajaxAct(sortDatas){
	loadingAct();
	sortDatas = sortDatas ? sortDatas : '';
	if(sortDatas != ''){
		sortData = sortDatas
	}
	var listAjax = $.get(useApi,'page='+page+'&list='+listCount+sortData,function(data){
		totalCount = data.totalCount;
		//alert(totalCount)
		var lists = '';
		var midStatus = '';
		var testStatus = '';
		var reportStatus = '';
		var totalScore = '';
		var testCopy = '';
		var reportCopy = '';
		var passOK = '';
		var serviceType = '';
		var j = totalCount;
		if(page != 1){
			j = totalCount - ((page-1)*listCount);
		}
		if (totalCount != 0 && loginUserLevel <= userLevel){
			$.each(data.study,  function(){
				if(this.serviceType == '1') {
					serviceType = '사업주';
				} else if(this.serviceType == '3') {
					serviceType = '비환급';
				} else {
					serviceType = '테스트';
				}
					lists += '<tr>';
					lists += '<td>'+j+'<br />'+serviceType+'</td>';
					lists += '<td onClick="globalModalAct(\'memberView\',\'\',\''+this.user.userID+'\')" style="cursor:pointer;">'+this.user.userID+'<br/>';
					lists += this.user.userName+'</td>';
					if(loginUserLevel == '8') {
						lists += '<td>'+this.contents.contentsName+'<br/>';
					} else {
						lists += '<td onClick="globalModalAct(\'contentsView\',\'\',\''+this.contents.contentsCode+'\')" style="cursor:pointer;">'+this.contents.contentsName+'<br/>';
					}
					lists += this.lectureStart+' ~ '+this.lectureEnd+'<br />첨삭완료 : '+this.tutorDeadline+' 까지</td>';
					lists += '<td onClick="globalModalAct(\'progressView\','+this.lectureOpenSeq+',\''+this.user.userID+'\')" style="cursor:pointer;">'+this.progress+'%</td>';

					if(this.midTutorTempSave == 'Y') {
						if(loginUserLevel == '7') {
							var midTutorTempSave = '(임시저장)';
						} else {
							var midTutorTempSave = '</strong><br />가채점 : '+this.tempMidScore;
						}
					} else {
						var midTutorTempSave = '';
					}

					if(this.serviceType == '3') { // 비환급인 경우 평가없음
						midStatus = '평가없음';
					} else {
						if(this.midStatus == 'Y') { // 응시
							midStatus = '<strong class="blue">채점 대기중 '+midTutorTempSave+'</strong><br />'+this.midSaveTime;
						} else if(this.midStatus == 'C') {
							midStatus = this.midScore+'<br />'+this.midSaveTime;
						} else if(this.midStatus == 'V') {
							midStatus = '<strong class="red">미응시</strong>';
						} else if(this.midStatus == 'N') {
							midStatus = '<strong class="red">미응시</strong>';
						} else { // 채점 완료
							midStatus = '평가 없음';
						}
					}

					if(loginUserLevel == '8') {
						lists += '<td>'+midStatus+'</td>';
					} else {
						lists += '<td onClick="globalModalAct(\'testResultView\','+this.lectureOpenSeq+',\''+this.user.userID+'\',\'mid\')" style="cursor:pointer;">'+midStatus+'</td>';
					}

					if(this.testTutorTempSave == 'Y') {
						if(loginUserLevel == '7') {
							var testTutorTempSave = '(임시저장)';
						} else {
							var testTutorTempSave = '</strong><br />가채점 : '+this.tempTestScore;
						}
					} else {
						var testTutorTempSave = '';
					}

					if(this.serviceType == '3') { // 비환급인 경우 평가없음
						testStatus = '평가 없음';
					} else {
						if(this.testStatus == 'N') { // 미응시
							testStatus = '<strong class="red">미응시</strong>';
						} else if(this.testStatus == 'Y') { // 응시
							testStatus = '<strong class="blue">채점 대기중 '+testTutorTempSave+'</strong><br />'+this.testSaveTime;
						} else if(this.testStatus == 'C') { // 채점 완료
							testStatus = this.testScore+'<br />'+this.testSaveTime;
						} else if(this.testStatus == 'V') { // 채점 완료
							if(data.nowTime >= this.testEndTime) {
								testStatus = '<strong class="blue">채점 대기중 '+testTutorTempSave+'</strong><br />'+this.testSaveTime;
							} else {
								testStatus = '<strong class="red">미응시</strong>';
							}
						} else {
							testStatus = '평가 없음';
						}
					}
					
					if(loginUserLevel == '8') {
						lists += '<td>'+testStatus+'</td>';
					} else {
						lists += '<td onClick="globalModalAct(\'testResultView\','+this.lectureOpenSeq+',\''+this.user.userID+'\',\'final\')" style="cursor:pointer;">'+testStatus+'</td>';
					}

					if(this.reportTutorTempSave == 'Y') {
						if(loginUserLevel == '7') {
							var reportTutorTempSave = '(임시저장)';
						} else {
							var reportTutorTempSave = '</strong><br />가채점 : '+this.tempReportScore;
						}
					} else {
						var reportTutorTempSave = '';
					}

					if(this.serviceType == '3') { // 비환급인 경우 평가없음
						reportStatus = '과제 없음';
					} else {
						if(this.reportStatus == null) { // 과제 없는 과정
							reportStatus = '과제 없음';
						} else if(this.reportStatus == 'N') { // 미응시
							reportStatus = '<strong class="red">미응시</strong>';
						} else if(this.reportStatus == 'Y') { // 응시
							reportStatus = '<strong class="blue">채점 대기중 '+reportTutorTempSave+'</strong><br />'+this.reportSaveTime;
						} else if(this.reportStatus == 'R') { // 반려
							reportStatus = '<strong class="red">과제 반려</strong>';
						} else if(this.reportStatus == 'C') { // 채점 완료
							reportStatus = this.reportScore+'<br />'+this.reportSaveTime;
						} else {
							reportStatus = '과제 없음';
						}
					}

					if(loginUserLevel == '8') {
						lists += '<td>'+reportStatus+'</td>';
					} else {
						lists += '<td onClick="globalModalAct(\'reportResultView\','+this.lectureOpenSeq+',\''+this.user.userID+'\')" style="cursor:pointer;">'+reportStatus+'</td>';
					}

					if(this.serviceType == '3') { // 비환급인 경우 평가없음
						totalScore = '-';
					} else {
						if(this.totalScore == null) { // 총점이 null인 경우 0
							totalScore = 0;
						} else {
							totalScore = this.totalScore;
						}
					}
					
					if(this.testCopy == 'Y') { // 모사답안
						testCopy = '<br /><strong class="red">평가모사</strong>';
					} else if(this.testCopy == 'D') {
						testCopy = '<br /><strong class="blue">평가 모사의심</strong>';
					} else {
						testCopy = '';
					}

					if(this.reportCopy == 'Y') { // 모사답안
						reportCopy = '<br /><strong class="red">과제 모사확정</strong>';
					} else if(this.reportCopy == 'D') {
						reportCopy = '<br /><strong class="blue">과제 모사의심</strong>';
					} else {
						reportCopy = '';
					}

					if(this.serviceType == '3') { // 비환급인 경우 진도율 80%이면 수료
						if(this.progress >= 80) { // 수료
							if(loginUserLevel != '7'){
								if(this.contents.sort01 == 'lecture03'){
									passOK = '<strong class="blue" style="cursor:pointer;" onclick="printPop2('+this.seq+');">수료</strong>';
								} else {
									passOK = '<strong class="blue" style="cursor:pointer;" onclick="printPop('+this.seq+');">수료</strong>';
								}	
							}else{
								passOK = '<strong class="blue">수료</strong>';
							}
													
						} else { // 미수료
							passOK = '<strong class="red">미수료</strong>';
						}
					} else {
						if(this.passOK == 'Y') { // 수료
							if(loginUserLevel != '7'){
								if(this.contents.sort01 == 'lecture03'){
									passOK = '<strong class="blue" style="cursor:pointer;" onclick="printPop2('+this.seq+');">수료</strong>';
								} else {
									passOK = '<strong class="blue" style="cursor:pointer;" onclick="printPop('+this.seq+');">수료</strong>';
								}	
							}else{
								passOK = '<strong class="blue">수료</strong>';
							}
						} else if(this.passOK == 'W') { // 진행중
							passOK = '진행중';
						} else { // 미수료
							passOK = '<strong class="red">미수료</strong>';
						}
					}

					lists += '<td>'+totalScore+testCopy+reportCopy+'<br />'+passOK+'</td>';
					if(loginUserLevel < '5') {
						lists += '<td>'+this.tutor.tutorName+'</td>';
					}
					lists += '<td onClick="globalModalAct(\'companyView\',\'\','+this.company.companyCode+')" style="cursor:pointer;">'+this.company.companyName+'</td>';
					if(loginUserLevel < '5') {
						lists += '<td>';
						if ((this.midStatus == "V" || this.midStatus == "Y" || this.midStatus == "C") && this.serviceType != '3' && this.serviceType != '8'){						
							lists += '<button type="button" onClick="retaken(\''+this.user.userID+'\',\''+this.seq+'\',\''+this.lectureOpenSeq+'\',\''+this.contents.contentsCode+'\',\'mid\',\''+this.testStatus+'\','+this.serviceType+')">중간</button>';
						}
						if ( (this.testStatus == "V" || this.testStatus == "Y" || this.testStatus == "C" ) && this.serviceType != '3' && this.serviceType != '8'){						
							lists += '&nbsp;<button type="button" onClick="retaken(\''+this.user.userID+'\',\''+this.seq+'\',\''+this.lectureOpenSeq+'\',\''+this.contents.contentsCode+'\',\'test\')">최종</button>';
						}
						lists += '</td>';
						if(this.writerID == null) {
							lists += '<td>-<br />-</td>';
						} else {
							lists += '<td>'+this.writerID+'<br />'+this.writeDate+'</td>';
						}
						lists += '<td><button type="button" onClick="deleteData(useApi,'+this.seq+')">삭제</button></td>';
					}
					lists += '</tr>';
					j--;
			})
		}else if(loginUserLevel > userLevel){
			lists += '<tr><td class="notResult" colspan="10">조회 권한이 없습니다.</td></tr>'
		}else{
			lists += '<tr><td class="notResult" colspan="10">검색 결과가 없습니다.</td></tr>'
		}
		$('.BBSList tbody').html(lists);
		pagerAct();
		loadingAct();
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
					$('select[name="companyCode"]').remove();
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

function printPop(popseq){
	popupAddress = '../study/print.html?seq='+popseq;
	window.open(popupAddress,"결과보기","width=600, height=700, menubar=no, status=no, titlebar=no, toolbar=no, scrollbars=yes, resizeable=no","printPop")
}

function printPop2(popseq){
	popupAddress = '../study/print.html?seq='+popseq;
	window.open(popupAddress,"결과보기","width=600, height=700, menubar=no, status=no, titlebar=no, toolbar=no, scrollbars=yes, resizeable=no","printPop")
}

function retaken(userID,seq,lectureOpenSeq,cotentsCode,testType,testStatus,serviceType){
	if (testType == 'mid') {
		var testTxt = '중간평가';
		if ((testStatus != 'null' && testStatus != 'N' && serviceType != '9' && serviceType != '8') || (serviceType == '9' && testStatus != 'null' && testStatus != 'N') ) {					
			alert('최종평가를 먼저 재응시 처리 해야 합니다.');
			return false;
		}
	} else {
		var testTxt = '최종평가';
	}
	if(confirm('콘텐츠코드: '+cotentsCode+' / 과정개설번호: '+lectureOpenSeq+' / 수강생ID: '+userID+' \n '+testTxt+' 재응시를 요청하시겠습니까?')) {
		$.ajax({
			url:'../api/apiStudy.php',
			type:'POST',
			data:{'userID':userID,'seq':seq,'retaken':'Y','testType':testType},
			dataType:"text",
			success:function(){
				alert('재응시 요청처리 되었습니다.');
				ajaxAct();
			},
			fail:function(){
				alert('변경실패.');
			}
		})
	}
}