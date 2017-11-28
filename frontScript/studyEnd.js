//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//보드 정보 선언
var useApi = '../api/apiStudyEnd.php';
//var memberApi = '../api/apiMember.php';
var chainsearchApi = '../api/apiSearch.php';
var seq = seq ? seq : '' ;
userLevel = userLevel ? userLevel :9;
var page = page ? page : 1;
var totalCount = '';
var listCount = 5; //한페이지 게시물 소팅개수
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
	actionArea += '</form></div>';
	$('#contents > h1').after(actionArea);

	//게시물 소팅부분
	var contents = ''; 
	contents += '<div class="scrollArea">';
	contents += '<table style="min-width:1360px;"><thead><tr>';
	contents += '<th style="width:50px;">번호</th>';
	contents += '<th>수강기간</th>';
	contents += '<th style="width:120px;">교육비</th>';
	contents += '<th style="width:120px;">최종 환급액</th>';
	contents += '<th style="width:120px;">수강인원</th>';

	if(loginUserLevel != 8) {
		contents += '<th style="width:120px;">첨삭현황</th>';
		contents += '<th style="width:150px;">점수확인</th>';
		contents += '<th style="width:150px;">수강마감</th>';
	} else {
		contents += '<th style="width:150px;">수료인원</th>';
	}

	contents += '<th style="width:150px;">수료증 출력</th>';
	contents += '<th style="width:200px;">수료결과보고서</th>';
	contents += '</tr></thead><tbody>';
	contents += '</tbody></table>';
	contents += '</div>'
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
	searchStudy('lectureDay',checkDate);
}

function ajaxAct(sortDatas){
	loadingAct();
	sortDatas = sortDatas ? sortDatas : '';
	if(sortDatas != ''){
		sortData = sortDatas
	}
	var listAjax = $.get(useApi,'page='+page+'&list='+listCount+sortData,function(data){
		totalCount = data.totalCount;
		var lists = '';
		var j = totalCount;
		if(page != 1){
			j = (page-1) * listCount;
		}
		if (totalCount != 0){
			j = totalCount;
			var i = 0;
			$.each(data.study, function(){
					lists += '<tr class="BBSListBg">';
					lists += '<td>'+j+'</td>';
					lists += '<td>'+this.lectureStart+' ~ '+this.lectureEnd;

					if(loginUserLevel != 8) {
						lists += '<br />('+this.tutorDeadline+'까지 첨삭)</td>';
					} else {
						lists += '</td>';
					}

					//lists += '<td>환급 : '+this.studyCount+'<br />비환급 : '+this.studyBeCount+'</td>';
					lists += '<td>&nbsp;</td>';

					if(loginUserLevel != 8) {
						//lists += '<td>중간 : '+this.midComplete+' / '+this.midSubmit+'<br />';
						//lists += '최종 : '+this.testComplete+' / '+this.testSubmit+'<br/>';
						//lists += '과제 : '+this.reportComplete+' / '+this.reportSubmit+'</td>';
						lists += '<td>&nbsp;</td>';
						lists += '<td>&nbsp;</td>';
						lists += '<td>&nbsp;</td>';
						lists += '<td>&nbsp;</td>';
						lists += '<td>&nbsp;</td>';
						lists += '<td>&nbsp;</td>';
						lists += '<td>&nbsp;</td>';
					} else {
						//lists += '<td>환급 : '+this.studyPassCount+'</td>'; 
						lists += '<td>&nbsp;</td>';
						lists += '<td>&nbsp;</td>';
						lists += '<td>&nbsp;</td>';
						lists += '<td>&nbsp;</td>';
						lists += '<td>&nbsp;</td>';
					}
					lists += '</tr>';

					$.each(this.company, function(){
						lists += '<tr>';
						lists += '<td></td>';
						lists += '<td>'+this.companyName+'</td>';
						lists += '<td>'+toPriceNum(this.totalPrice)+'</td>';
						if(this.studyCount == 0) {
							lists += '<td>0</td>';
						} else {
							if(this.studyEnd == 'Y') {
								lists += '<td>'+toPriceNum(this.totalRPrice)+'</td>';
							} else {
								lists += '<td>진행 중</td>';
							}
						}
						lists += '<td>환급 : '+this.studyCount+'<br />비환급 : '+this.studyBeCount+'</td>';

						if(loginUserLevel != 8) { // 관리자 접근시
							lists += '<td>중간 : '+this.midComplete+' / '+this.midSubmit+'<br />';
							lists += '최종 : '+this.testComplete+' / '+this.testSubmit+'<br/>';
							lists += '과제 : '+this.reportComplete+' / '+this.reportSubmit+'</td>';
							if(this.studyCount == 0) {
								lists += '<td>-</td>';
							} else {
								if(this.resultView == 'Y') {
									lists += '<td>처리자 : '+this.userIDR+'<br />마감일 : '+this.inputDateR+'<br /><button type="button" onClick="studyEnd(\''+data.study[i].lectureStart+'\',\''+data.study[i].lectureEnd+'\',\''+this.companyCode+'\',\'resultView\')" >취소하기</button></td>';
								} else {
									lists += '<td><button type="button" onClick="studyEnd(\''+data.study[i].lectureStart+'\',\''+data.study[i].lectureEnd+'\',\''+this.companyCode+'\',\'resultView\');" >확인처리</button></td>';
								}
							}
							if(this.studyCount == 0) { // 비환급
								lists += '<td>-</td>';
								lists += '<td><button onClick="window.open(\'certificate.php?lectureStart='+data.study[i].lectureStart+'&lectureEnd='+data.study[i].lectureEnd+'&companyCode='+this.companyCode+'&serviceType=3\')">비환급</button></td>';
								lists += '<td><button onClick="window.open(\'resultDoc.php?lectureStart='+data.study[i].lectureStart+'&lectureEnd='+data.study[i].lectureEnd+'&companyCode='+this.companyCode+'&serviceType=3\')">비환급</button></td>';
							} else { // 환급
								if(this.studyEnd == 'Y') {
									lists += '<td>처리자 : '+this.userIDS+'<br />마감일 : '+this.inputDateS+'<br /><button type="button" onClick="studyEnd(\''+data.study[i].lectureStart+'\',\''+data.study[i].lectureEnd+'\',\''+this.companyCode+'\',\'studyEnd\')" >취소하기</button></td>';
									if(this.studyCount != 0) {
										lists += '<td><button onClick="window.open(\'certificate.php?lectureStart='+data.study[i].lectureStart+'&lectureEnd='+data.study[i].lectureEnd+'&companyCode='+this.companyCode+'&serviceType=1\')">개인(환급)</button><br />';
									}
									if(this.studyBeCount != 0) {
										lists += '<button onClick="window.open(\'certificate.php?lectureStart='+data.study[i].lectureStart+'&lectureEnd='+data.study[i].lectureEnd+'&companyCode='+this.companyCode+'&serviceType=3\')">개인(비환급)</button><br />';
									}
									lists += '<td><button onClick="window.open(\'resultDoc.php?lectureStart='+data.study[i].lectureStart+'&lectureEnd='+data.study[i].lectureEnd+'&companyCode='+this.companyCode+'&serviceType=1\')">환급과정만</button>&nbsp;';
									lists += '<button onClick="window.open(\'resultDoc.php?lectureStart='+data.study[i].lectureStart+'&lectureEnd='+data.study[i].lectureEnd+'&companyCode='+this.companyCode+'\')">전체</button></td>';
								} else {
									lists += '<td><button type="button" onClick="studyEnd(\''+data.study[i].lectureStart+'\',\''+data.study[i].lectureEnd+'\',\''+this.companyCode+'\',\'studyEnd\');" >마감하기</button></td>';
									if(this.studyCount != 0) {
										lists += '<td><button onClick="window.open(\'certificate.php?lectureStart='+data.study[i].lectureStart+'&lectureEnd='+data.study[i].lectureEnd+'&companyCode='+this.companyCode+'&serviceType=1\')">개인(환급)</button><br />';
									}
									if(this.studyBeCount != 0) {
										lists += '<button onClick="window.open(\'certificate.php?lectureStart='+data.study[i].lectureStart+'&lectureEnd='+data.study[i].lectureEnd+'&companyCode='+this.companyCode+'&serviceType=3\')">개인(비환급)</button><br />';
									}
									lists += '<td>마감되지 않았습니다.</td>';
								}
							}

						} else { // 교육담당자 접근시
							if(this.studyCount == 0) {
								lists += '<td>-</td>';
								lists += '<td><button onClick="window.open(\'certificate.php?lectureStart='+data.study[i].lectureStart+'&lectureEnd='+data.study[i].lectureEnd+'&companyCode='+this.companyCode+'&serviceType=3\')">비환급</button></td>';
								lists += '<td><button onClick="window.open(\'resultDoc.php?lectureStart='+data.study[i].lectureStart+'&lectureEnd='+data.study[i].lectureEnd+'&companyCode='+this.companyCode+'&serviceType=3\')">비환급</button></td>';
							} else {
								if(this.studyEnd == 'Y') {
									lists += '<td>환급 : '+this.studyPassCount+'</td>';
									lists += '<td><button onClick="window.open(\'certificate.php?lectureStart='+data.study[i].lectureStart+'&lectureEnd='+data.study[i].lectureEnd+'&companyCode='+this.companyCode+'&serviceType=1\')">출력(환급)</button></td>';
									lists += '<td><button onClick="window.open(\'resultDoc.php?lectureStart='+data.study[i].lectureStart+'&lectureEnd='+data.study[i].lectureEnd+'&companyCode='+this.companyCode+'&serviceType=1\')">환급</button>&nbsp;';
									lists += '<button onClick="window.open(\'resultDoc.php?lectureStart='+data.study[i].lectureStart+'&lectureEnd='+data.study[i].lectureEnd+'&companyCode='+this.companyCode+'\')">전체</button></td>';
								} else {
									lists += '<td>진행 중</td>';
									lists += '<td>마감되지 않았습니다.</td>';
									lists += '<td>마감되지 않았습니다.</td>';
								}
							}
						}
						lists += '</tr>';
					})
					j--;
					i++;
			})
		}else{
			lists += '<tr><td class="notResult" colspan="10">검색 결과가 없습니다.</td></tr>'
		}
		$('.BBSList tbody').html(lists);
		pagerAct();
		loadingAct();
	})
}

function studyEnd(lectureStart, lectureEnd, companyCode, gubun){
	var msg = '';
	if(gubun == 'studyEnd') {
		msg = '정말 수강마감을 하시겠습니까?';
	} else {
		msg = '점수 확인처리를 하시겠습니까?';
	}
	if(confirm(msg)) {
		$.ajax({
			url:useApi,
			type:'POST',
			data:{'lectureStart':lectureStart,'lectureEnd':lectureEnd, 'companyCode':companyCode, 'gubun':gubun},
			dataType:'JSON',
			success:function(data){
				alert(data.result);
				ajaxAct();
			}
		})
	}
}

function resultDoc(lectureStart, lectureEnd, companyCode){
	if(confirm(msg)) {
		$.ajax({
			url:useApi,
			type:'GET',
			data:{'lectureStart':lectureStart,'lectureEnd':lectureEnd, 'companyCode':companyCode},
			success:function(){
				ajaxAct();
			}
		})
	}
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

