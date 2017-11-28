//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//보드 정보 선언
var useApi = '../api/apiPeriodStats.php';
//var useApi = '../api/apiExpense.php';
var chainsearchApi = '../api/apiSearch.php';
var seq = seq ? seq : '' ;
userLevel = userLevel ? userLevel :9;
var page = page ? page : 1;
var totalCount = '';
var listCount = 10; //한페이지 게시물 소팅개수
var pagerCount = 10; //페이저카운트

//리스트 소팅
function listAct(page) {

	//상단 액션 부분
	var actionArea = '';
	var today = new Date();

	actionArea += '<div class="searchArea"><form class="searchForm" action="javascript:searchAct()">';
	actionArea += '<div class="searchChangeArea">'
    actionArea += '<input type="radio" name="selectSearch" id="searchDate" value="searchDate" checked="checked" onChange="searchTypeSelect(this.value)" /><label for="searchDate">기간검색</label>&nbsp;&nbsp;&nbsp;'
	actionArea += '|&nbsp;<input type="radio" name="selectSearch" id="searchMonth" value="searchMonth" onChange="searchTypeSelect(this.value)" /><label for="searchMonth">월별검색</label>&nbsp;&nbsp;&nbsp;'
	if (loginUserLevel != '7') {
	    actionArea += '|&nbsp;<input type="radio" name="selectSearch" id="searchCompany" value="searchCompany" id="searchCompany" onChange="searchTypeSelect(this.value)" /><label for="searchCompany">사업주검색</label>&nbsp;&nbsp;&nbsp;&nbsp;';
	}
	actionArea += '<select name="searchYear" onchange="searchStudy(\'lectureDay\')">';
	var i= '';
	var thisYear = today.getFullYear();
	for (i= 2015; i <= thisYear; i++) {
		if (i != thisYear) {
			actionArea += '<option>'+i+'년</option>';
		} else {
			actionArea += '<option selected="selected">'+i+'년</option>';
		}
	}
    actionArea += '</select>';
	actionArea += '<select name="searchMonth" onchange="searchStudy(\'lectureDay\')">';
	actionArea += '<option value="0">전체</option>';
	var h= '';
	var thisMonth = today.getMonth()+1; //January is 0!
	for (h = 1; h <= 12; h++) {
		if (h != thisMonth) {
			actionArea += '<option>'+h+'월</option>';
		} else {
			actionArea += '<option selected="selected">'+h+'월</option>';
		}
	}
    actionArea += '</select>';
	actionArea += '</div>';
	//actionArea += '<select name="searchMode"><option value="lectureDay">수강기간별 통계</option><option value="month">월별 통계</option></select>&nbsp;&nbsp;&nbsp;';
	actionArea += '<button type="button" onClick="searchAct()">검색</button>';
	actionArea += '<button type="button" onClick="excelAct()" style="margin-left:10px;">엑셀 다운로드</button></form>';
	actionArea += '</form></div>';
	$('#contents > h1').after(actionArea);

	//게시물 소팅부분
	var contents = '';
	contents += '<div class="scrollArea">';
	contents += '<table style="min-width:1360px;"><thead><tr>';
	contents += '<th style="width:50px;" rowspan="2">번호</th>';
	contents += '<th rowspan="2">수강기간</th>';
	contents += '<th colspan="6">환급과정</th>';
	contents += '<th colspan="5">비환급과정</th>';
	contents += '<th colspan="6">총계</th>';
	contents += '</tr>';
	contents += '<tr>';
	contents += '<th>수강인원</th>';
	contents += '<th>수료인원</th>';
	contents += '<th>미수료인원</th>';
	contents += '<th>수료율</th>';
	contents += '<th>교육비</th>';
	contents += '<th>환급액</th>';

	contents += '<th>수강인원</th>';
	contents += '<th>수료인원</th>';
	contents += '<th>미수료인원</th>';
	contents += '<th>수료율</th>';
	contents += '<th>교육비</th>';

	contents += '<th>수강인원</th>';
	contents += '<th>수료인원</th>';
	contents += '<th>미수료인원</th>';
	contents += '<th>수료율</th>';
	contents += '<th>교육비</th>';
	contents += '<th>환급액</th>';
	contents += '</tr></thead><tbody>';
	contents += '<tr><td class="notResult" colspan="20">검색값을 선택하세요.</td></tr>';
	contents += '</tbody></table>';
	contents += '</div>'
	$('#contentsArea').removeAttr('class');
	$('#contentsArea').addClass('BBSList');
	$('#contentsArea').html(contents);
	var thisYear  = today.getFullYear();
	var thisMonth = today.getMonth()+1; //January is 0!
	if (thisMonth <= 9) {
		thisMonth = '0'+thisMonth;
	}
	var checkDate = thisYear +'-'+thisMonth;
	searchStudy('lectureDay',checkDate)
}

function ajaxAct(listPage,sortData) {
	loadingAct();
	listPage = listPage ? listPage : page ;
	page     = listPage;
	sortData = sortData ? sortData : '';
	var listAjax = $.get(useApi,'page='+page+'&list='+listCount+sortData,function(data) {
		totalCount         = data.totalCount;
		var lists          = '';
		var j              = totalCount;
		var totalStudyAll  = 0;
		var totalPassOkAll = 0;
		var totalPassNoAll = 0;
		var totalPriceAll  = 0;
		var totalrPriceAll = 0;

		if (totalCount != 0 && loginUserLevel <= userLevel) {
			j = totalCount;
			$.each(data.periodStats, function() {
				lists += '<tr>';
				lists += '<td>'+j+'</td>';
				lists += '<td>'+this.lectureDay+'</td>'; 					//수강기간
				lists += '<td>'+toPriceNum(this.totalStudy1)+'</td>'; 		//수강인원
				lists += '<td>'+toPriceNum(this.totalPassOk1)+'</td>'; 		//수료인원
				lists += '<td>'+toPriceNum(this.totalPassNo1)+'</td>'; 		//미수료인원
				lists += '<td>'+Math.round(this.percent1)+'%</td>'; 		//수료율
				lists += '<td>'+toPriceNum(this.totalPrice1)+'</td>'; 		//교육비
				lists += '<td>'+toPriceNum(this.totalrPrice)+'</td>'; 		//환급비
				//비환급
				lists += '<td>'+toPriceNum(this.totalStudy2)+'</td>'; 		//수강인원
				lists += '<td>'+toPriceNum(this.totalPassOk2)+'</td>'; 		//수료인원
				lists += '<td>'+toPriceNum(this.totalPassNo2)+'</td>'; 		//미수료인원
				lists += '<td>'+Math.round(this.percent2)+'%</td>'; 		//수료율
				lists += '<td>'+toPriceNum(this.totalPrice2)+'</td>'; 		//교육비

				totalStudyAll  = eval(this.totalStudy1)  + eval(this.totalStudy2);
				totalPassOkAll = eval(this.totalPassOk1) + eval(this.totalPassOk2);
				totalPassNoAll = eval(this.totalPassNo1) + eval(this.totalPassNo2);
				totalPriceAll  = eval(this.totalPrice1)  + eval(this.totalPrice2);
				totalrPriceAll = eval(this.totalrPrice);

				//총계
				lists += '<td>'+toPriceNum(totalStudyAll)+'</td>'; 			//교육인원
				lists += '<td>'+toPriceNum(totalPassOkAll)+'</td>'; 		//수료인원
				lists += '<td>'+toPriceNum(totalPassNoAll)+'</td>'; 		//미수료인원
				if (totalStudyAll == 0) {
					var totalPercent = 0;
				} else {
					var totalPercent = eval(totalPassOkAll / totalStudyAll * 100);	
				}				
				lists += '<td>'+Math.round(totalPercent)+'%</td>'; 			//수료율
				lists += '<td>'+toPriceNum(totalPriceAll)+'</td>'; 			//교육비
				lists += '<td>'+toPriceNum(totalrPriceAll)+'</td>'; 		//환급비
				lists += '</tr>';
				j--;
			})

		} else {
			lists += '<tr><td class="notResult" colspan="20">검색 결과가 없습니다.</td></tr>'
		}
		$('.BBSList tbody').html(lists);
		pagerAct();
		loadingAct();
	})
}

//검색관련
function searchTypeSelect(types) {
	$('.searchArea div.searchChangeArea select, .searchArea div.searchChangeArea input[type="text"]').remove();
	var chageSearch =''
	if (types == 'searchDate' || types == 'searchMonth') {
		if (types == 'searchDate') {
			chageSearch += '<select name="searchYear" onchange="searchStudy(\'lectureDay\')">';
		} else if (types == 'searchMonth') {
			chageSearch += '<select name="searchYear">';
		}

		var today = new Date();
		var i= '';
		var thisYear = today.getFullYear();
		for (i= 2015; i <= thisYear; i++) {
			if(i != thisYear){
				chageSearch += '<option>'+i+'년</option>';
			}else{
				chageSearch += '<option selected="selected">'+i+'년</option>';
			}
		}
		chageSearch += '</select>';
		if (types == 'searchDate') {
			chageSearch += '<select name="searchMonth" onchange="searchStudy(\'lectureDay\')">';var h= '';
			chageSearch += '<option value="0">전체</option>';
			var thisMonth = today.getMonth()+1; //January is 0!
			for (h = 1; h <= 12; h++) {
				if (h != thisMonth) {
					chageSearch += '<option>'+h+'월</option>';
				}else{
					chageSearch += '<option selected="selected">'+h+'월</option>';
				}
			}
			chageSearch += '</select>';
		}
	} else if (types == 'searchCompany') {
		chageSearch += '<input type="text" name="searchCompany" onkeyup="searchStudy(\'company\',this)">';
	}
	$('.searchArea div.searchChangeArea').append(chageSearch)
	if (types == 'searchDate' || types == 'searchMonth') {
		var thisYear = today.getFullYear();
		var thisMonth = today.getMonth()+1; //January is 0!
		if (thisMonth <= 9) {
			thisMonth = '0'+thisMonth;
		}
		var checkDate = thisYear +'-'+thisMonth;
		if (types == 'searchDate') {
			searchStudy('lectureDay',checkDate)
		}
	}
	//actionArea += '<input type="text" name="searchCompany" onkeyup="searchStudy(\'company\',this)" />'
}

function searchStudy(types,vals) {
	if (types=='lectureDay') {
		$('select[name="lectureDay"], strong.noticeSearch, select[name="companyCode"]').remove();
		var dateChain = ''
		dateChain += $('select[name="searchYear"]').val().replace('년','') +'-';
		if (eval($('select[name="searchMonth"]').val().replace('월','')) < 10) {
			dateChain += '0'+$('select[name="searchMonth"]').val().replace('월','');
		} else {
			dateChain += $('select[name="searchMonth"]').val().replace('월','');
		}

		$.get(chainsearchApi,{'searchMode':types, 'searchDay':dateChain},function(data){
			var selectWrite = ''
			if (data.totalCount !=0) {
				selectWrite += '<select name="lectureDay" onChange="searchStudy(\'printCompany\',this);searchAct()">';
				selectWrite += '<option value="">기간을 선택해주세요</option>'
				$.each(data.searchResult,function() {
					selectWrite += '<option value="'+this.lectureStart+'~'+this.lectureEnd+'">'+this.lectureStart+'~'+this.lectureEnd+'</option>';
				})
				selectWrite += '</select>'
			} else {
				selectWrite += '<strong class="noticeSearch price">&nbsp;&nbsp;&nbsp;검색결과가 없습니다.</option>'
			}
			$('select[name="searchMonth"]').after(selectWrite)
		})

	} else if(types=='company') {
		$('select[name="companyCode"], strong.noticeSearch').remove();
		var searchName = vals.value
		if (searchName != ''&& searchName != ' ' ) {
			$.get(chainsearchApi,{'searchMode':types, 'searchName':searchName},function(data) {
				var selectWrite = ''
				if (data.totalCount !=0) {
					selectWrite += '<select name="companyCode" onChange="searchStudy(\'printDate\',this);searchAct()">';
					selectWrite += '<option value="">사업주를 선택하세요</option>'
					$.each(data.searchResult, function(){
						selectWrite += '<option value="'+this.searchCode+'">'+this.searchName+'&nbsp;|&nbsp;'+this.searchCode+'</option>';
					})
					selectWrite += '</select>'
				} else {
					selectWrite += '<strong class="noticeSearch price">&nbsp;&nbsp;&nbsp;검색결과가 없습니다.</option>'
				}
				$('input[name="searchCompany"]').after(selectWrite)

			})
		} else {
			$('.searchChangeArea select, strong.noticeSearch').remove();
		}
	} else if(types=='printCompany') {
		$('strong.noticeSearch, select[name="companyCode"]').remove();
		var searchDate = vals.value
		$.get(chainsearchApi,{'searchMode':'study', 'lectureDay':searchDate},function(data) {
			var selectWrite = ''
			if (data.totalCount !=0) {
				selectWrite += '<select name="companyCode" onChange="searchAct()">';
				selectWrite += '<option value="">사업주를 선택하세요</option>'
				$.each(data.searchResult,function(){
					selectWrite += '<option value="'+this.companyCode+'">'+this.companyName+'&nbsp;|&nbsp;'+this.companyCode+'</option>';
				})
				selectWrite += '</select>'
			} else {
				selectWrite += '<strong class="noticeSearch price">&nbsp;&nbsp;&nbsp;검색결과가 없습니다.</option>'
			}
			$('select[name="lectureDay"]').after(selectWrite)
		})
	} else if (types=='printDate') {
		$('select[name="lectureDay"], strong.noticeSearch').remove();
		var companyCode = vals.value;
		$.get(chainsearchApi,{'searchMode':'study', 'companyCode':companyCode},function(data) {
			var selectWrite = ''
			if (data.totalCount !=0) {
				selectWrite += '<select name="lectureDay" onChange="searchAct()">';
				selectWrite += '<option value="">기간을 선택해주세요</option>'
				$.each(data.searchResult,function() {
					selectWrite += '<option value="'+this.lectureStart+'~'+this.lectureEnd+'">'+this.lectureStart+'~'+this.lectureEnd+'</option>';
				})
				selectWrite += '</select>'
			} else {
				selectWrite += '<strong class="noticeSearch price">&nbsp;&nbsp;&nbsp;검색결과가 없습니다.</option>'
			}
			$('select[name="companyCode"]').after(selectWrite)
		})
	}
}

function excelAct() {
	searchValue = $('.searchForm').serialize();
	searchValue = '&'+searchValue;
	top.location.href='periodStats.php?'+searchValue;
}