//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

function pagerAct(){
	//var nowPage = Page; //현제 페이지 지정
	var allPagerCnt = Math.ceil(totalCount/listCount)+1;
	var nowPage = Math.floor(page/pagerCount);
	var startPage = nowPage*pagerCount;
	var endPage = startPage+pagerCount;
	if(endPage > allPagerCnt){
		endPage = allPagerCnt
	}
	var nextPage = endPage;
	var prevPage = startPage - pagerCount;
	if(prevPage <= 0){
		prevPage = 1
	}
	//alert(totalCount+'/'+allPagerCnt+'/'+page+'/'+startPage+'/'+endPage+'/'+listCount)
	var pagerArea = ''
	pagerArea += '<div class="pager">'
	if(startPage >= pagerCount){
		if(pageMode =='mobilePage'){
			pagerArea += '<button type="button" title="이전" onClick="pageMove('+prevPage+')"><img src="../images/mobile/btn_prev.png"></button>';
		}else{
			pagerArea += '<button type="button" title="이전" onClick="pageMove('+prevPage+')"><img src="../images/admin/pager_prev.png" /></button>'
		}
	}
	for(i=startPage;i<endPage;i++){
		if(i == 0){
			i = 1
		}
		if(i != page){
			//page = i
			pagerArea += '<a href="javascript:pageMove('+i+')">'+i+'</a>'
		}else{
			pagerArea += '<b>'+i+'</b>'
		}
	}
	if(endPage < allPagerCnt){
		if(pageMode =='mobilePage'){
			pagerArea += '<button type="button" title="다음" onClick="pageMove('+nextPage+')"><img src="../images/mobile/btn_next.png"></button>';
		}else{
			pagerArea += '<button type="button" onClick="pageMove('+nextPage+')"><img src="../images/admin/pager_next.png" /></button>';
		}
	}
	pagerArea += '</div>'
	if($('body').find('div.pager').index() == -1){
		$('#contentsArea').append('<div class="pager"></div>')
		$('.pager').html(pagerArea)
	}else{
		$('.pager').html(pagerArea)
	}
	//$('.pager a').bind('click',function(){ page=i })
}

function pageMove(pageNum){
	page=pageNum
	ajaxAct();	
}