//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//리스트액션
function listAct(){	
	var actionArea = '';
	actionArea += '<div class="searchArea">';
	actionArea += '<form class="searchForm" action="javascript:searchAct()">';
	actionArea += '<select name="searchType">';
	actionArea += '<option value="popupType">구분</option>';
	actionArea += '<option value="subject">제목</option>';
	actionArea += '</select>&nbsp;';
	actionArea += '<input type="text" name="searchValue" />&nbsp;';
	actionArea += '<button type="button" onClick="searchAct();">검색하기</button>';
	actionArea += '</form>';
	actionArea += '<button type="button" onclick="writeAct();">새 팝업 등록</button>';
	actionArea += '</div>';

	$('#contents > h1').after(actionArea);
	
	var contents = '';
	contents += '<table><thead><tr>';
	contents += '<th style="width:60px;">번호</th>';
	contents += '<th style="width:100px;">구분</th>';
	contents += '<th style="width:100px;">사이즈</th>';
	contents += '<th>제목</th>';
	contents += '<th style="width:200px;">등록기간</th>';
	contents += '<th style="width:60px;">사용</th>';
	contents += '<th style="width:130px;">수정/삭제</th>';
	contents += '</tr></thead><tbody>';
	contents += '</tbody></table>';
	$('#contentsArea').removeAttr('class');
	$('#contentsArea').addClass('BBSList');
	$('#contentsArea').html(contents);
	ajaxAct();
}

function ajaxAct(listPage,sortData){
	listPage = listPage ? listPage : page;
	page = listPage;
	sortData = sortData ? sortData : '';
	var listAjax = $.get(useApi,'page='+page+'&list='+listCount+sortData,function(data){
		totalCount = data.totalCount;
		var lists = '';
		if (totalCount != 0){
			$.each(data.popup, function(){
				lists += '<tr>';
				lists += '<td>'+this.seq+'</td>';
				lists += '<td>'+this.popupType+'</td>';
				lists += '<td>'+this.width+'*'+this.height+'</td>';
				lists += '<td class="left" onClick="openPopup(\''+this.subject+'\',\''+this.popupURL+'\',\'_'+this.popupTarget+'\',\''+this.attachFile+'\',\''+this.width+'\',\''+this.height+'\')" style="cursor:pointer;">'+this.subject+'</td>';
				lists += '<td>'+this.startDate+' ~ '+this.endDate+'</td>';
				lists += '<td>'+this.enabled+'</td>';
				lists += '<td><button onclick="writeAct('+this.seq+');">수정</button> / <button onclick="deleteData(\''+useApi+'\','+this.seq+');">삭제</button></td>';
				lists += '</tr>';
			})
		}else{
			lists += '<tr><td class="notResult" colspan="20">아직 등록된 글이 없습니다.</td></tr>';
		}
		$('#contentsArea tbody').html(lists);
		pagerAct();
	})
}