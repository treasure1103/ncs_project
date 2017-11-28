//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//리스트 소팅
function listAct(){
	
	//상단 액션 부분	
	var actionArea = '';
	actionArea += '<div class="searchArea"><form class="searchForm" action="javascript:searchAct()">';
	actionArea += '<button type="button" class="fRight" onClick="writeAct()">신규등록</button>';
	//actionArea += '<button type="button" class="fRight" onClick="alert(\'준비중입니다.\')">엑셀 다운로드</button>';
    actionArea += '<select name="searchType">';	
    actionArea += '<option value="companyName">사업주명</option>';
    actionArea += '<option value="companyID">아이디</option>';
    actionArea += '<option value="companyCode">사업자번호</option>';
    actionArea += '</select>&nbsp;';
    actionArea += '<input type="text" name="searchValue" />&nbsp;';
	actionArea += '<button type="button" onClick="searchAct()">검색하기</button>';
	actionArea += '</form>';
	actionArea += '</div>';
	$('#contents > h1').after(actionArea);
	
	//게시물 소팅부분
	var contents = '';
	contents += '<table><thead><tr>';
	contents += '<th style="width:60px;">번호</th>';
	contents += '<th style="width:120px;">사업자번호</th>';
	contents += '<th>사업주명</th>';
	contents += '<th style="width:120px;">회사규모</th>';
	contents += '<th style="width:200px;">교육담당자</th>';
	contents += '<th style="width:80px;">영업담당자</th>';
	contents += '<th style="width:100px;">등록일</th>';
	contents += '<th style="width:70px;">교육센터</th>';
	if(loginUserLevel < '5') {
	    contents += '<th style="width:120px;">수정 / 삭제</th>';
	}

	contents += '</tr></thead><tbody>'	;
	contents += '</tbody></table>';
	$('#contentsArea').removeAttr('class');
	$('#contentsArea').addClass('BBSList');
	$('#contentsArea').html(contents);
	ajaxAct();
}

function ajaxAct(sortDatas){
	sortDatas = sortDatas ? sortDatas : '';
	if(sortDatas != ''){
		sortData = sortDatas
	}
	var listAjax = $.get(useApi,'page='+page+'&list='+listCount+sortData,function(data){
		totalCount = data.totalCount;
		//alert(totalCount)
		var lists = '';
		var i = totalCount;
		if(page != 1){
			i = totalCount - ((page-1)*listCount);
		}
		if (totalCount != 0){
			$.each(data.company,  function(){
				lists += '<tr>';
				lists += '<td>'+i+'</td>';
				lists += '<td>'+this.companyCode+'</td>';
				lists += '<td onClick="globalModalAct(\'companyView\','+this.seq+')" style="cursor:pointer;">'+this.companyName+'</td>';
				lists += '<td>';
				if(this.companyScale == 'A'){
					lists += '대규모 1000인 미만'
				}else if(this.companyScale =='B'){
					lists += '대규모 1000인 이상'
				}else if(this.companyScale =='C'){
					lists += '우선지원대상'
				}
				lists += '</td>';
				lists += '<td>'+this.manager.name+'<br />'+this.manager.mobile+'<br />'+this.manager.email+'</td>';
				lists += '<td>'+this.marketer.name+'</td>';
				lists += '<td>'+this.inputDate.substr(0,10)+'</td>';
				lists += '<td>'
				if(this.studyEnabled == 'Y' ){ 
					lists += '<button type="button" onClick="writeCenter(\''+this.companyID+'\')">설정</button>'
				}else{
					lists += '사용안함'
				}
				lists += '</td>';
				if(loginUserLevel < '5') {
					lists += '<td>';
					lists += '<button type="button" onClick="writeAct('+this.seq+')">수정</button>&nbsp;';
					lists += '<button type="button" onClick="deleteData(\''+useApi+'\','+this.seq+')">삭제</button>';
					lists += '<td>';
				}
				lists += '</tr>';
				i--
			})
		}else{
			lists += '<tr><td class="notResult" colspan="20">검색 결과가 없습니다.</td></tr>'
		}
		$('.BBSList tbody').html(lists)
		pagerAct();
	})
}