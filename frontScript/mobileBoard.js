$(document).ready(function(){
  if(seq != ''){
	  viewAct(seq)
  }else{
	  listAct(page)
  }
});


function listAct(listPage){
	listPage = listPage ? listPage : '';
	page = listPage;
	$('article').remove();
	$('header').after('<ul></ul>');
	$('body').append('<div class="pager"></div>');
	ajaxAct();
}

function ajaxAct(){
	$.get(useApi,{'boardCode':'1','list':listCount,'page':page},function(data){
		totalCount = data.totalCount;
		var lists = ''
		$.each(data.board, function(){
			if(totalCount != 0){
				lists += '<li onClick="viewAct('+this.seq+')">'
				lists += '<h1>'+this.subject+'</h1>'
				lists += '<h2>'+this.inputDate.substr(0,10)+'</h2>'
				lists += '</li>'
			}else{
				lists += '<li><h1>등록된 게시물이 없습니다.</h1></li>'
			}
		})
		$('#boardPage > ul').html(lists);
		pagerAct();
	})
}

function viewAct(viewSeq){
	seq = viewSeq ;
	$('body > ul').remove()
	$('.pager').remove()
	$('header').after('<article></article>')
	$.get(useApi,{'boardCode':'1','seq':seq},function(data){
		var views = '';
		$.each(data.board, function(){
			//제목열호출
			views += '<ul><li>';
			views += '<h1>'+this.subject+'</h1>';
			views += '<h2>'+this.inputDate.substr(0,10)+'</h2>';
			views += '</li>';
			//내용호출
			views += '<li><div>'+this.content+'</div></li>';
			views += '</ul>';
			views += '<button type="button" onClick="listAct('+page+')">목록으로</button>';			
		})
		$('#boardPage > article').html(views);
	})
	
}