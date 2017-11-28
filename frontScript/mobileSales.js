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
	$('body > ul, body > button, body > article, body > form, body > div.btnArea, .pager, .searchArea').remove()
	$('header').after('<ul></ul>');
	var searchArea = '';
	searchArea += '<div class="searchArea">';
	searchArea += '<form class="searchForm">';
	searchArea += '<input type="hidden" name="searchType" value="subject" />';
	searchArea += '<input type="text" name="searchValue" onkeyup="searchAct()" />';
	searchArea += '</form>'
	searchArea += '</div>';
	$('header').after(searchArea);
	
	var actionArea = '';
	actionArea += '<div class="pager"></div>';
	actionArea += '<button type="button" onClick="writeAct()">신규등록</button>';
	$('body').append(actionArea);
	ajaxAct();
}

function ajaxAct(){
	$.get(useApi,'boardCode=4&list='+listCount+'&page='+page+searchData,function(data){
		totalCount = data.totalCount;
		var lists = ''
		if(totalCount != 0){
			$.each(data.board, function(){			
				lists += '<li onClick="viewAct('+this.seq+')">'
				lists += '<h1>'+this.subject+'</h1>'
				lists += '<h2>'+this.inputDate.substr(0,10)+'&nbsp;|&nbsp;'+this.userName+'</h2>'
				lists += '</li>'
			})
		}else{
			lists += '<li><h1>등록된 게시물이 없습니다.</h1></li>'
		}
		$('#boardPage > ul').html(lists);
		pagerAct();
	})
}

function viewAct(viewSeq){
	seq = viewSeq ;
	$('body > ul, body > button, body > article, body > form, body > div.btnArea, .pager, .searchArea').remove()
	$('header').after('<article></article>')
	$.get(useApi,{'boardCode':'4','seq':seq},function(data){
		var views = '';
		$.each(data.board, function(){
			//제목열호출
			views += '<ul><li>';
			views += '<h1>'+this.subject+'</h1>';
			views += '<h2>'+this.inputDate.substr(0,10)+'&nbsp;|&nbsp;'+this.userName+'</h2>';
			views += '</li>';
			//내용호출
			views += '<li><div style="min-height:80px;">'+this.content+'</div></li>';
			views += '</ul>';

			views += '<div class="btnArea">';
			if(this.userID == loginUserID){
				views += '<button type="button" onClick="writeAct('+seq+')">수정하기</button>';
				views += '<button type="button" onClick="deleteData(\''+useApi+'\', '+seq+')">삭제하기</button>';
			}
			views += '</div>'

			views += '<button class="endButton" type="button" onClick="listAct('+page+')">목록으로</button>';			
		})
		$('#boardPage > article').html(views);
		var commentArea = '';
		commentArea += '<div class="commentArea">'
		commentArea += '<form class="commentWrite">';
		commentArea += '<input type="hidden" name="seq" value="" />';
		commentArea += '<input type="hidden" name="boardCode" value="4" />';
		commentArea += '<input type="hidden" name="boardSeq" value="'+seq+'" />';
		commentArea += '<div>'
		commentArea += '<h1>'+loginUserName+'&nbsp;|&nbsp;'+loginUserID+'</h1><input type="hidden" name="userID" value="'+loginUserID+'" /><input type="hidden" name="userName" value="'+loginUserName+'" />';
		commentArea += '</div>'
		commentArea += '<textarea name="content"></textarea>';
		commentArea += '<button type="button" onClick="confirmComment()">댓글달기</button>';
		commentArea += '</form>';
		commentArea += '<ul class="commentList"></ul></div>'
		$('#boardPage > article div.btnArea').after(commentArea);
		commentAct(seq)
	})	
}

function writeAct(writeSeq){
	$('body > ul, body > button, body > article, body > form, body > div.btnArea, .pager, .searchArea').remove()
	writeSeq = writeSeq ? writeSeq : ''; //파일관련 스크립트 사용
		
	//기존,신규글
	var writeseq = '';
	var writeUserName = loginUserName;
	var writeUserID = '';
	var writePhone01 = '';
	var writePhone02 = '';
	var writePhone03 = '';
	var writeCategorySeq = '';
	var writeSubject = '';
	var writeContent = '';
	var attachURL = '';
	var writeUserID = loginUserID ? loginUserID : 'guest';
		
	seq = writeSeq;
	//상단메뉴
	$('.searchArea, .BBSCategory').remove();	
	
	if(seq != ''){
		var writeAjax = $.get(useApi,{'seq':seq, 'boardCode':boardCode},function(data){
			attachURL = data.attachURL;
			$.each(data.board, function(){
				writeseq = this.seq;
				writeUserName = this.userName;
				writeUserID = this.userID;
				writePhone01 = this.phone01;
				writePhone02 = this.phone02;
				writePhone03 = this.phone03;
				writeEmail01 = this.email01;
				writeEmail02 = this.email02;
				writeCategorySeq = this.categorySeq;
				writeSubject = this.subject;
				writeContent = this.content;
				writeAttachFile01 = this.attachFile01;
				writeAttachFile01Name = this.attachFile01Name;
				writeAttachFile02 = this.attachFile02;
				writeAttachFile02Name = this.attachFile02Name;
				writeSecret = this.secret;
				writeTop = this.top;					
			})	
			writePrint()
		})
	}else{
		writePrint()
	}
	//게시판 생성
	function writePrint(){
		var writes ='';
		writes += '<form class="writeform">';
		writes += '<input type="hidden" name="userID" value="'+writeUserID+'" />';
		writes += '<input type="hidden" name="userName" value="'+writeUserName+'" />';
		writes += '<input type="hidden" name="seq" value="'+writeSeq+'" />';
		writes += '<input type="hidden" name="boardCode" value="'+boardCode+'" />';
		writes += '<ul>';
		writes += '<li><h1>제목</h1>';
		writes += '<input type="text" class="subject" name="subject" value="'+writeSubject+'" />';
		writes += '</li>'
		writes += '<li><h1>작성자</h1>'+loginUserName+'&nbsp;|&nbsp;'+loginUserID+'</li>';
		writes += '<li><h1>담당연락처</h1><input type="tel"  class="tel" name="phone01" value="'+writePhone01+'">  - <input type="tel"  class="tel" name="phone02" value="'+writePhone02+'"> - <input type="tel" class="tel" name="phone03" value="'+writePhone03+'"></li>'
		writes += '<li><textarea name="content">'+writeContent+'</textarea></li>';
		writes += '</ul>'
		writes += '</form>'
		if(seq==''){
			writes += '<div class="btnArea"><button type="button" onClick="sendData(\''+useApi+'\',\'writeform\',\'new\');">작성완료</button>';
		}else{
			writes += '<div class="btnArea"><button type="button" onClick="sendData(\''+useApi+'\',\'writeform\');">수정완료</button>';
		}
		writes += '<button type="button" onClick="listAct('+page+')">목록보기</button></div>';
		$('header').after(writes);
	}
}

//댓글관련 시작
function commentAct(seq){	
	var comments = '';
	var commentsAjax = $.get(commentApi,{'boardSeq':seq},function(data){
		if(data.totalCount != 0 ){
			$.each(data.comment, function(){
				comments += '<li>';
				comments += '<form  class="comment'+this.seq+'">'
				comments += '<h1>'+this.userName+'&nbsp;|&nbsp;<span>'+this.userID+'</span>&nbsp;|&nbsp;<span>'+this.inputDate+'</span></h1>';
				comments += '<p name="content">'+this.content+'</p>';
				if(loginUserID == this.userID || this.userID =='guest' ){
					comments += '<button type="button" onClick="';
					if(this.userID == loginUserID || eval(loginUserLevel) <= 2){
						comments += 'deleteData(\''+commentApi+'\','+this.seq+',\'comment\')';
					}else{
						comments += 'modalAct(\''+commentApi+'\','+this.seq+',\'deletes\')';
					}
					comments += '">삭제하기</button>';
				}
				if(loginUserID == this.userID || this.userID == 'guest'){
					comments += '<button type="button" class="modifyBtn" onClick="';
					if(loginUserID == this.userID){
						comments += 'commentModify('+this.seq+')';
					}else{
						comments += 'commentModify('+this.seq+',\'pwd\')';
					}
					comments += '">수정하기</button>';
				}
				//comments += '<h3>'+this.userIP+'</h3>';
				comments += '</form>'
				comments += '</li>'
			})
		}else{
			comments += '<li class="noReply">아직 작성된 댓글이 없습니다.</li>'
		}
		$('div.commentArea ul').html(comments)
	})	
}

function confirmComment(commentSeq){
	commentSeq = commentSeq ? commentSeq : '';
	if(commentSeq==''){
		if($('form.commentWrite input[name="userName"]').val() == ''){
			alert('댓글에 이름을 입력해주세요')
		}else if($('form.commentWrite input[name="pwd"]').val() == ''){
			alert('댓글에 비밀번호을 입력해주세요')
		}else{
			sendData(commentApi,'commentWrite','comment');		
		}
	}else{
		var commetData = $('form.comment'+commentSeq).serialize()		
		var pwdCheck = $.post(commentApi,commetData,function(data){
			if(data.result == 'success'){
				alert('수정되었습니다.')
				commentAct(seq);
			}else{
				alert('비밀번호가 일치하지 않습니다..')
			}
		})
	}
}

function commentModify(commentSeq,usePWD,cancel){
	usePWD = usePWD ? usePWD : '';
	if(cancel != "cancel"){
		$('form.comment'+commentSeq).children('button.modifyBtn').html('수정취소')
		$('form.comment'+commentSeq).children('button.modifyBtn').removeAttr('onClick')
		$('form.comment'+commentSeq).children('button.modifyBtn').click(function(){commentModify(commentSeq,usePWD,'cancel')})
		var commentsConents = $('form.comment'+commentSeq).children('p').html();
		var commentsModifyArea = '';
		commentsModifyArea += '<div>'
		commentsModifyArea += '<input type="hidden" name="seq" value="'+commentSeq+'">'
		commentsModifyArea += '<input type="hidden" name="boardCode" value="'+boardCode+'">'
		if(usePWD != ''){
			commentsModifyArea += '<div><h1>비밀번호 확인</h1><input type="password" name="pwd" /></div>'
		}
		commentsModifyArea += '<textarea name="content">'+commentsConents+'</textArea>'
		commentsModifyArea += '<button type="button" onClick="confirmComment('+commentSeq+')">수정하기</button>';
		$('form.comment'+commentSeq).children('p').after(commentsModifyArea);
		$('form.comment'+commentSeq).children('p').css('display','none');
	}else{
		$('form.comment'+commentSeq).children('button.modifyBtn').html('수정하기')
		$('form.comment'+commentSeq).children('button.modifyBtn').removeAttr('onClick')
		$('form.comment'+commentSeq).children('button.modifyBtn').click(function(){commentModify(commentSeq,usePWD)})
		$('form.comment'+commentSeq).children('div').remove();
		$('form.comment'+commentSeq).children('p').css('display','block');
	}
	
}

function searchAct(){
	searchData = '&' + $('.searchForm').serialize();
	ajaxAct();
}