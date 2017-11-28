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
    actionArea += '<span>수강생</span>';
    actionArea += '<input type="text" style="width:130px;" name="userName">';
    actionArea += '<span>진도율</span>';
    actionArea += '<input type="text" style="width:50px;" name="progress01">% ~ <input type="text" style="width:50px;" name="progress02">%';
    actionArea += '</div><div>';
    actionArea += '<span>평가시험</span>';
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
    actionArea += '<span>평가 모사</span>';
	actionArea += '<select name="testCopy">';
    actionArea += '<option value="">전체</option>';
    actionArea += '<option value="D">모사답안의심</option>';
    actionArea += '<option value="Y">모사답안</option>';
    actionArea += '</select>';
    actionArea += '<span>과제 모사</span>';
	actionArea += '<select name="reportCopy">';
    actionArea += '<option value="">전체</option>';
    actionArea += '<option value="D">모사답안의심</option>';
    actionArea += '<option value="Y">모사답안</option>';
    actionArea += '</select>';
    actionArea += '</div>';
	actionArea += '<button type="button" onClick="searchAct()" class="allWidth">검색</button></form>';
	actionArea += '</form></div>';
	$('#contents > h1').after(actionArea);
	
	//게시물 소팅부분
	var contents = '';
	contents += '<table><thead><tr>';
	contents += '<th style="width:50px;">번호</th>';
	contents += '<th style="width:80px;">ID</th>';
	contents += '<th style="width:80px;">이름</th>';
	contents += '<th style="width:300px;">과정명<br />수강기간</th>';
	contents += '<th style="width:80px;">수강IP</th>';
	contents += '<th style="width:100px;">중간응시IP</th>';
	contents += '<th style="width:100px;">최종응시IP</th>';
	contents += '<th style="width:100px;">과제제출IP</th>';
	contents += '<th style="width:80px;">강사ID</th>';
	contents += '<th style="width:100px;">첨삭IP(평가/과제)</th>';
	contents += '</tr></thead><tbody>'	;
	contents += '<tr><td class="notResult" colspan="10">검색값을 선택하세요.</td></tr>'	;
	contents += '</tbody></table>';
	$('#contentsArea').removeAttr('class');
	$('#contentsArea').addClass('BBSList');
	$('#contentsArea').html(contents);
	ajaxAct();
	var thisYear = today.getFullYear();
	var thisMonth = today.getMonth()+1; //January is 0!
	if(thisMonth <= 9){
		thisMonth = '0'+thisMonth;
	}
	var checkDate = thisYear +'-'+thisMonth;
	searchStudy('lectureDay',checkDate)
}

function ajaxAct(listPage,sortData){
	listPage = listPage ? listPage : page ;
	page = listPage;
	sortData = sortData ? sortData : '';
	var listAjax = $.get(useApi,'page='+page+'&userLevel='+userLevel+'&list='+listCount+sortData,function(data){
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
		var j = totalCount;
		if(page != 1){
			j = (page-1) * listCount
		}
		if (totalCount != 0 && loginUserLevel <= userLevel){
			j = totalCount;
			$.each(data.study,  function(){
				var accessIP = '';
				var midIP = ''; 
				var testIP = ''; 
				var reportIP = '';
				var testCheckIP = ''; 
				var reportCheckIP = '';

				if(this.accessIP == null){
					accessIP = '-';	
				} else {
					accessIP = this.accessIP;	
				}
				if(this.midIP == null){
					midIP = '-';	
				} else {
					midIP = this.midIP;	
				}
				if(this.testIP == null){
					testIP = '-';	
				} else {
					testIP = this.testIP;	
				}
				if(this.reportIP == null){
					reportIP = '-';	
				} else {
					reportIP = this.reportIP;	
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
					lists += '<tr>';
					lists += '<td>'+j+'</td>';
					lists += '<td onClick="globalModalAct(\'memberView\',\'\',\''+this.user.userID+'\')" style="cursor:pointer;">'+this.user.userID+'</td>';
					lists += '<td onClick="globalModalAct(\'memberView\',\'\',\''+this.user.userID+'\')" style="cursor:pointer;">'+this.user.userName+'</td>';
					lists += '<td onClick="globalModalAct(\'contentsView\',\'\',\''+this.contents.contentsCode+'\')" style="cursor:pointer;">'+this.contents.contentsName+'<br/>';
					lists += this.lectureStart+' ~ '+this.lectureEnd+'</td>';
					lists += '<td onClick="globalModalAct(\'progressView\','+this.lectureOpenSeq+',\''+this.user.userID+'\')" style="cursor:pointer;">'+accessIP+'</td>';
					lists += '<td onClick="globalModalAct(\'testResultView\','+this.lectureOpenSeq+',\''+this.user.userID+'\',\'mid\')" style="cursor:pointer;">'+midIP+'</td>';
					lists += '<td onClick="globalModalAct(\'testResultView\','+this.lectureOpenSeq+',\''+this.user.userID+'\',\'final\')" style="cursor:pointer;">'+testIP+'</td>';
					lists += '<td onClick="globalModalAct(\'reportResultView\','+this.lectureOpenSeq+',\''+this.user.userID+'\')" style="cursor:pointer;">'+reportIP+'</td>';
					lists += '<td onClick="globalModalAct(\'memberView\',\'\',\''+this.tutor.tutorID+'\')" style="cursor:pointer;">'+this.tutor.tutorName+'</td>';
					lists += '<td>'+testCheckIP+'<br/>'+reportCheckIP+'</td>';
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
	$('.searchArea div.searchChangeArea').append(chageSearch);
	
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