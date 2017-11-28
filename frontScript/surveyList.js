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
    actionArea += '<input type="radio" name="selectSearch" id="searchDate" value="searchDate" checked="checked" onChange="searchTypeSelect(this.value)" /><label for="searchDate">기간검색</label>&nbsp;&nbsp;&nbsp;'

	if(loginUserLevel != '7') {
	    actionArea += '|&nbsp;<input type="radio" name="selectSearch" id="searchContents" value="searchContents" id="searchContents" onChange="searchTypeSelect(this.value)" /><label for="searchContents">과정명 검색</label>&nbsp;&nbsp;&nbsp;&nbsp;';
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
	actionArea += '</form></div>';
	$('#contents > h1').after(actionArea);
	
	//게시물 소팅부분
	var contents = '';
	contents += '<table><thead><tr>';
	contents += '<th style="width:50px;">번호</th>';
	contents += '<th style="width:120px;">ID / 이름</th>';
	contents += '<th style="width:300px;">과정명 / 수강기간</th>';
	contents += '<th style="width:100px;">만족도 (만점:5)</th>';
	contents += '<th style="width:100px;">답변시간</th>';
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

function ajaxAct(sortDatas){
	sortDatas = sortDatas ? sortDatas : '';
	if(sortDatas != ''){
		sortData = sortDatas
	}
	var listAjax = $.get(useApi,'page='+page+'&userLevel='+userLevel+'&list='+listCount+sortData,function(data){
		totalCount = data.totalCount;
		//alert(totalCount)
		var lists = '';
		var midStatus = '';
		var testStatus = '';
		var reportStatus = '';
		var totalScore = '';
		var mosa = '';
		var passOK = '';
		var grade = '';
		var j = totalCount;
		if(page != 1){
			j = (page-1) * listCount
		}
		if (totalCount != 0 && loginUserLevel <= userLevel){
			j = totalCount;
			$.each(data.survey,  function(){
					lists += '<tr>';
					lists += '<td>'+j+'</td>';
					lists += '<td onClick="globalModalAct(\'memberView\',\'\',\''+this.userID+'\')" style="cursor:pointer;">'+this.userID+'<br/>';
					lists += this.userName+'</td>';
					lists += '<td onClick="globalModalAct(\'contentsView\',\'\',\''+this.contentsCode+'\')" style="cursor:pointer;">'+this.contentsName+'<br/>';
					lists += this.lectureStart+' ~ '+this.lectureEnd+'</td>';
					lists += '<td>';
					if(this.answer[0].exam[0].userAnswer == 1){
						grade = 5;
					} else if (this.answer[0].exam[0].userAnswer == 2){
						grade = 4;
					} else if (this.answer[0].exam[0].userAnswer == 3){
						grade = 3;
					} else if (this.answer[0].exam[0].userAnswer == 4){
						grade = 2;
					} else if (this.answer[0].exam[0].userAnswer == 5){
						grade = 1;
					}
					lists += grade+'점<br />';
					lists += '<button type="button" onclick="globalModalAct(\'surveyView\',\''+this.lectureOpenSeq+'\',\''+this.userID+'\',\''+this.contentsCode+'\')">세부내용보기</button></td>';
					lists += '<td>'+this.inputDate+'</td>';
					lists += '</tr>';
					j--;
			})
		}else if(loginUserLevel > userLevel){
			lists += '<tr><td class="notResult" colspan="4">조회 권한이 없습니다.</td></tr>'
		}else{
			lists += '<tr><td class="notResult" colspan="4">검색 결과가 없습니다.</td></tr>'
		}
		$('.BBSList tbody').html(lists);
		pagerAct();
	})
}

//검색관련

function searchTypeSelect(types){
	$('.searchArea select, .searchArea input[type="text"], .searchArea strong').remove();
	var chageSearch ='';
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
	}else if(types == 'searchContents'){
		chageSearch += '<input type="text" name="searchContents" onkeyup="searchStudy(\'contents\',this)">';	
	}
	$('.searchArea').append(chageSearch)
}

function searchStudy(types,vals){
	if(types=='lectureDay'){
		$('select[name="lectureDay"], strong.noticeSearch, select[name="contentsCode"]').remove();
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
				selectWrite += '<select name="lectureDay" onChange="searchStudy(\'printContents\',this);searchAct()">';
				selectWrite += '<option value="">기간을 선택해주세요</option>'
				$.each(data.searchResult,function(){
					selectWrite += '<option value="'+this.lectureStart+'~'+this.lectureEnd+'">'+this.lectureStart+'~'+this.lectureEnd+'</option>';
				})
				selectWrite += '</select>'	
			}else{
				selectWrite += '<strong class="noticeSearch price">&nbsp;&nbsp;&nbsp;검색결과가 없습니다.</option>'
			}
			$('select[name="searchMonth"]').after(selectWrite);
		})		
	}else if(types=='contents'){
		$('select[name="contentsCode"], strong.noticeSearch').remove();
		var searchName = vals.value
		if( searchName != ''&& searchName != ' ' ){
			$.get(chainsearchApi,{'searchMode':types, 'searchName':searchName},function(data){
				var selectWrite = ''
				if(data.totalCount !=0){
					$('strong.noticeSearch').remove();
					selectWrite += '<select name="contentsCode" onChange="searchStudy(\'printDate\',this);searchAct()">';
					selectWrite += '<option value="">과정을 선택하세요</option>'
					$.each(data.searchResult, function(){
						selectWrite += '<option value="'+this.searchCode+'">'+this.searchName+'&nbsp;|&nbsp;'+this.searchCode+'</option>';
					})
					selectWrite += '</select>'	
				}else{
					selectWrite += '<strong class="noticeSearch price">&nbsp;&nbsp;&nbsp;검색결과가 없습니다.</strong></option>'
				}
				$('input[name="searchContents"]').after(selectWrite)
	
			})
		}else{
			$('.searchChangeArea select, strong.noticeSearch').remove();
		}
	}else if(types=='printContents'){
		$('strong.noticeSearch, select[name="contentsCode"]').remove();
		var searchDate = vals.value
		$.get(chainsearchApi,{'searchMode':'study', 'lectureDay':searchDate, 'request':'contents'},function(data){
			var selectWrite = ''
			if(data.totalCount !=0){
				selectWrite += '<select name="contentsCode" onChange="searchAct()">';
				selectWrite += '<option value="">과정을 선택하세요</option>'
				$.each(data.searchResult,function(){
					selectWrite += '<option value="'+this.contentsCode+'">'+this.contentsName+'&nbsp;|&nbsp;'+this.contentsCode+'</option>';
				})
				selectWrite += '</select>'	
			}else{
				selectWrite += '<strong class="noticeSearch price">&nbsp;&nbsp;&nbsp;검색결과가 없습니다.</option>'
			}
			$('select[name="lectureDay"]').after(selectWrite)
		})
	}else if(types=='printDate'){
		$('select[name="lectureDay"], strong.noticeSearch').remove();
		var contentsCode = vals.value;
		$.get(chainsearchApi,{'searchMode':'study', 'contentsCode':contentsCode},function(data){
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
			$('select[name="contentsCode"]').after(selectWrite)
		})
	}
}