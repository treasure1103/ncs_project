//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//보드 정보 선언
var sortData = '';
var useApi = '../api/apiMonitoringDetail.php';
//var memberApi = '../api/apiMember.php';
var chainsearchApi = '../api/apiSearch.php';
var seq = seq ? seq : '' ;
userLevel = userLevel ? userLevel :9;
var page = page ? page : 1;
var totalCount = '';
var listCount = 10; //한페이지 게시물 소팅개수
var pagerCount = 10; //페이저카운트

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
    actionArea += '<span>평가시험</span>';
	actionArea += '<select name="testStatus">';
    actionArea += '<option value="">전체</option>';
    actionArea += '<option value="C">응시(채점완료)</option>';
	actionArea += '<option value="Y">응시(채점대기중)</option>';
    actionArea += '<option value="N">미응시</option>';
    actionArea += '</select>&nbsp;';
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
    actionArea += '</select>&nbsp;';
	actionArea += ' <button type="button" onClick="excelB()" >단답형 확인</button> ';
	actionArea += ' <button type="button" onClick="excelAct()" >엑셀 다운로드</button> ';
	actionArea += '<button type="button" onClick="searchAct()">검색</button></form>';
	actionArea += '</form></div>';
	$('#contents > h1').after(actionArea);
	
	//게시물 소팅부분
	var contents = '';
	contents += '<div class="scrollArea">';
	contents += '<table style="min-width:1360px;"><thead><tr>';
	contents += '<th style="width:40px;"><input type="checkbox" id="checkAll" onChange="checkboxAllCheck(\'checkAll\')" /><label for="checkAll"></label></th>';
	contents += '<th style="width:50px;">번호</th>';
	contents += '<th style="width:100px;">ID<br />이름</th>';
	contents += '<th>과정명<br />수강기간</th>';
	contents += '<th style="width:110px;">첨삭기간</th>';
	contents += '<th style="width:100px;">강사ID<br />강사명</th>';
	contents += '<th style="width:100px;">중간 평가<br />첨삭IP</th>';
	contents += '<th style="width:100px;">최종 평가<br />첨삭IP</th>';
	contents += '<th style="width:100px;">과제<br />첨삭IP</th>';
  //contents += '<th style="width:80px;">모사율(%)</th>';
	contents += '<th style="width:80px;">모사의심</th>';
	contents += '<th style="width:80px;">첨삭여부</th>';
	contents += '<th style="width:80px;">수료여부</th>';
	//contents += '<th style="width:80px;">첨삭보기</th>';
	contents += '</tr></thead><tbody>';
	contents += '<tr><td class="notResult" colspan="10">검색값을 선택하세요.</td></tr>';
	contents += '</tbody></table>';
	contents += '</div>'
	$('#contentsArea').removeAttr('class');
	$('#contentsArea').addClass('BBSList');
	$('#contentsArea').html(contents);
	ajaxAct();
	/*
	var thisYear = today.getFullYear();
	var thisMonth = today.getMonth()+1; //January is 0!
	if(thisMonth <= 9){
		thisMonth = '0'+thisMonth;
	}
	var checkDate = thisYear +'-'+thisMonth;
	searchStudy('lectureDay',checkDate)
	*/
}

function ajaxAct(sortDatas){
	sortDatas = sortDatas ? sortDatas : '';
	if(sortDatas != ''){
		sortData = sortDatas
	}
	var listAjax = $.get(useApi,'monitor=Y&page='+page+'&userLevel='+userLevel+'&list='+listCount+sortData,function(data){
		totalCount = data.totalCount;
		//alert(totalCount)
		var lists = '';
		var midStatus = '';
		var testStatus = '';
		var reportStatus = '';
		var midCheckIP = ''; 
		var testCheckIP = ''; 
		var reportCheckIP = '';
		var mosa = '';
		var tutorComplete = '';
		var mosa = '';
		var j = totalCount;
		if(page != 1){
			j = totalCount - ((page-1)*listCount);
		}
		if (totalCount != 0 && loginUserLevel <= userLevel){
			$.each(data.study,  function(){
					lists += '<tr>';
					lists += '<td><input type="checkbox" name="check['+j+']" id="check'+j+'" class="checkAll" /><label for="check'+j+'"></label></td>';
					lists += '<td>'+j+'</td>';
					lists += '<td onClick="globalModalAct(\'memberView\',\'\',\''+this.user.userID+'\')" style="cursor:pointer;">'+this.user.userID+'<br/>';
					lists += this.user.userName+'</td>';
					lists += '<td onClick="globalModalAct(\'contentsView\',\'\',\''+this.contents.contentsCode+'\')" style="cursor:pointer;">'+this.contents.contentsName+'<br/>';
					lists += this.lectureStart+' ~ '+this.lectureEnd+'</td>';
					lists += '<td>'+this.tutorDeadline+' 까지</td>';
					lists += '<td onClick="globalModalAct(\'memberView\',\'\',\''+this.tutor.tutorID+'\')" style="cursor:pointer;">'+this.tutor.tutorID+'<br/>';
					lists += this.tutor.tutorName+'</td>';

					if(this.midCheckIP == null){
						midCheckIP = '-';
					} else {
						midCheckIP = this.midCheckIP;
					}
					if(this.testCheckIP == null){
						testCheckIP = '-';	
					} else {
						testCheckIP = this.testCheckIP;	
					}
					if(this.reportCheckIP == null){
						reportCheckIP = '-';	
					} else {
						reportCheckIP = this.reportCheckIP;	
					}
					if(this.midStatus == 'N') { // 미응시
						midStatus = '<strong class="red">미응시</strong>';
					} else if(this.midStatus == 'Y') { // 응시
						midStatus = '첨삭 대기중';
					} else if(this.testStatus == 'C') { // 채점 완료
						midStatus = this.midScore;
					}
					if(this.testStatus == 'N') { // 미응시
						testStatus = '<strong class="red">미응시</strong>';
					} else if(this.testStatus == 'Y') { // 응시
						testStatus = '첨삭 대기중';
					} else if(this.testStatus == 'C') { // 채점 완료
						testStatus = this.testScore;
					}
					if(this.reportStatus == null) { // 과제 없는 과정
						reportStatus = '과제 없음';
					} else if(this.reportStatus == 'N') { // 미응시
						reportStatus = '<strong class="red">미응시</strong>';
					} else if(this.reportStatus == 'Y') { // 응시
						reportStatus = '첨삭 대기중';
					} else if(this.reportStatus == 'R') { // 반려
						reportStatus = '<strong class="red">반려</strong>';
					} else if(this.reportStatus == 'C') { // 채점 완료
						reportStatus = this.reportScore;
					}
					lists += '<td onClick="globalModalAct(\'testResultView\','+this.lectureOpenSeq+',\''+this.user.userID+'\',\'mid\')" style="cursor:pointer;">'+midStatus+'<br />'+midCheckIP+'</td>';
					lists += '<td onClick="globalModalAct(\'testResultView\','+this.lectureOpenSeq+',\''+this.user.userID+'\',\'final\')" style="cursor:pointer;">'+testStatus+'<br />'+testCheckIP+'</td>';
					lists += '<td onClick="globalModalAct(\'reportResultView\','+this.lectureOpenSeq+',\''+this.user.userID+'\')" style="cursor:pointer;">'+reportStatus+'<br />'+reportCheckIP+'</td>';
				  //lists += '<td>모사율</td>';

					if(this.mosa == 'Y') { // 모사답안
						mosa = '<strong class="red">모사의심</strong>';
					} else {
						mosa = '정상';
					}

					lists += '<td>'+mosa+'</td>';

					if(this.testStatus == 'Y' || this.reportStatus == 'Y') {
						tutorComplete = '첨삭중';
					} else {
						tutorComplete = '완료';
					}

					lists += '<td>'+tutorComplete+'</td>';

					if(this.passOK == 'Y') { // 수료
						passOK = '<strong class="blue">수료</strong>';
					} else if(this.passOK == 'W') { // 진행중
						passOK = '진행중';
					} else { // 미수료
						passOK = '<strong class="red">미수료</strong>';
					}

					lists += '<td>'+passOK+'</td>';
					//lists += '<td>내용보기</td>';
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

function excelAct(){
	searchValue = $('.searchForm').serialize();
	searchValue = '&'+searchValue;
	top.location.href='tutorCheck.php?'+searchValue;
}

function excelB(){
	searchValue = $('.searchForm').serialize();
	searchValue = '&'+searchValue;
	top.location.href='examB.php?'+searchValue;
}