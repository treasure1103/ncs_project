//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//리스트액션
function listAct(listPage){
	seq = '';
	page = listPage;
	$('#contents > h1').html('회원 탈퇴 관리<span>회원 탈퇴 관리를 합니다. 탈퇴 현황은 조회만 가능하며 수정 삭제는 하실 수 없습니다.</span>');

	//refreashData() // totalCount 호출
	//게시물 소팅부분
	var listAjax = $.get(useApi,{'page':page,'list':listCount,'searchType':searchType,'searchValue':searchValue},function(data){
		var actionArea = '';
		actionArea += '<div class="searchArea">';
		actionArea += '검색하기 : ';
		actionArea += '<form class="searchForm">';
		actionArea += '<select name="searchType">';
		actionArea += '<option value="userID">아이디</option>';
		actionArea += '<option value="userName">이름</option>';
		actionArea += '</select>';
		actionArea += '<input type="text" />';
		actionArea += '<button type="button" onClick="searchAct();">검색하기</button>';
		actionArea += '</form>';
		actionArea += '</div>';

		$('div.searchArea').remove();
		$('#contents > h1').after(actionArea);

		var lists = '';
	    lists += '<table><thead><tr>';
	    lists += '<th style="width:60px;">번호</th>';
	    lists += '<th>아이디</th>';
	    lists += '<th style="width:200px;">이름</th>';
		lists += '<th style="width:160px;">탈퇴일</th>';
	    lists += '<th style="width:120px;">탈퇴사유</th>';
	    lists += '</tr></thead><tbody>';

		if (totalCount != 0){
			$.each(data.member, function(){
				lists += '<tr>';
				lists += '<td>'+this.seq+'</td>';
				lists += '<td>'+this.userID+'</td>';
				lists += '<td>'+this.userName+'</td>';
				lists += '<td>'+this.userDelete.inputDate.substr(0,10)+'</td>';
				lists += '<td><button type="button">상세보기</button></td>';
				lists += '</tr>';
			})
		}else{
			lists += '<tr><td class="notResult" colspan="20">검색된 값이 없습니다.</td></tr>';
		}
		lists += '</tbody></table>';
		$('#contentsArea').removeAttr('class');
		$('#contentsArea').addClass('BBSList');
		$('#contentsArea').html(lists);
		pagerAct();
	})
	
	//게시물 퍼포먼스관련
	.done(function(){
	})
	//기타액션
	.always(function(){
	})
}
function searchAct(){
	searchType = $('.searchForm select[name="searchType"]').val();
	searchValue = $('.searchForm input[type="text"]').val();
	refreashData();
	listAct();
}