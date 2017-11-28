//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//보드 정보 선언
var useApi = '../api/apiEssayForm.php';
var chainsearchApi = '../api/apiSearch.php';
var seq = seq ? seq : '' ;
userLevel = userLevel ? userLevel :9;
var page = page ? page : 1;
var totalCount = '';
var listCount = 10; //한페이지 게시물 소팅개수
var pagerCount = 10; //페이저카운트
var sortData = '';

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
    actionArea += '</select></div>';
    actionArea += '<span>설문내용</span>';
	actionArea += '<select name="surveySeq" onchange="valueAct()">';
    actionArea += '</select>';
	actionArea += '<button type="submit" style="margin-left:10px;">검색</button>';
	actionArea += '<button type="button" onClick="excelAct()" style="margin-left:20px">엑셀 다운로드</button>';
	actionArea += '</form></div>';
	$('#contents > h1').after(actionArea);
	surveySeqPrint();
	
	//게시물 소팅부분
	var contents = '';
	contents += '<table><thead><tr>';
	contents += '<th style="width:30px;">번호</th>';
	contents += '<th style="width:60px;">ID/이름</th>';
	contents += '<th style="width:80px;">소속명</th>';
	contents += '<th style="width:120px;">수강기간/과정명</th>';	
	contents += '<th style="width:30px;">설문번호</th>';	
	contents += '<th style="width:300px;">설문답안</th>';
	contents += '</tr></thead><tbody>'	;
	contents += '<tr><td class="notResult" colspan="6">검색값을 선택하세요.</td></tr>'	;
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

function ajaxAct(sortDatas){
	loadingAct();
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
			j = totalCount - ((page-1)*listCount);
		}
		if (totalCount != 0 && loginUserLevel <= userLevel){
			$.each(data.survey,  function(){
				lists += '<tr>';
				lists += '<td>'+j+'</td>';
				lists += '<td onClick="globalModalAct(\'memberView\',\'\',\''+this.userID+'\')" style="cursor:pointer;">'+this.userID+'<br/>';
				lists += this.userName+'</td>';
				lists += '<td onClick="globalModalAct(\'companyView\',\'\',\''+this.companyCode+'\')" style="cursor:pointer;">'+this.companyName+'</td>';
				lists += '<td onClick="globalModalAct(\'contentsView\',\'\',\''+this.contentsCode+'\')" style="cursor:pointer;">'+this.lectureStart+' ~ '+this.lectureEnd+'<br/>';
				lists += this.contentsName+'</td>';
				lists += '<td>'+this.surveySeq+'</td>';
				lists += '<td>'+this.userTextAnswer+'</td>';
				lists += '</tr>';
				j--;
			})
		}else if(loginUserLevel > userLevel){
			lists += '<tr><td class="notResult" colspan="6">조회 권한이 없습니다.</td></tr>'
		}else{
			lists += '<tr><td class="notResult" colspan="6">검색 결과가 없습니다.</td></tr>'
		}
		$('.BBSList tbody').html(lists);
		pagerAct();
		loadingAct();
	})
}

//검색관련

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

function surveySeqPrint(){
	$.get('../api/apiEssayForm.php?type=survey',function(data){
		var optWrite = '<option value="">설문내용을 선택해주세요.</option>';
		$.each(data.survey, function(){
			optWrite += '<option value="'+this.orderby+'"';
			if(this.orderby == surveySeq){
				optWrite += ' selected="selected"'
			}
			optWrite += '>'+this.exam+'</option>';
		})
		$('.searchArea select[name="surveySeq"]').html(optWrite);
	})
}

function excelAct(){
	searchValue = $('.searchForm').serialize();
	searchValue = '&'+searchValue;
	top.location.href='essayForm.php?'+searchValue;
}