//	게시판 뷰페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//게시판 보기 스크립트 시작
function viewAct(seqNumb){
	seq = seqNumb;
	//var boardSeq = seq;
	//var fileAddress = 'fileUpload/'; //추후 절대경로로 변경
	
	//상단메뉴
	//$('#contents > h1').html(titleHtml);
	$('.searchArea, .BBSCategory').remove();
	
	//게시물 소팅부분
	var viewAjax = $.get(useApi,{'seq':seq, 'boardCode':boardCode},function(data){
		var attachURL = data.attachURL;
		var views = '';
		
		//게시글 소팅
		$.each(data.board, function(){
			//제목열 소팅
			if(this.secret == 'Y'){
				views += '<div class="titleArea secret">';
			}else{
				views += '<div class="titleArea">';
			}
			views += '<h1>'
			if (this.secret == 'Y'){		
				views += '<span>[ 비밀글 ]</span>';
			}
			if (useCategory == 'Y'){		
				views += '<span>[ '+this.categoryName+' ]</span>&nbsp;';
			}
			views += this.subject+'</h1>';
			if(useName != 'N'){
				views += '<h2>'+this.userName+'<span>'+this.userID+'</span></h2>';
			}
			if(useEmail == 'Y' || usePhone == 'Y'){
				views += '<div>'
				if(useEmail == 'Y'){
					views += '<p>E-mail&nbsp;:&nbsp;<strong>'+this.email01+'@'+this.email02+'</strong></p>';
				}
				if(usePhone == 'Y'){
					views += '<p>연락처&nbsp;:&nbsp;<strong>'+this.phone01+'-'+this.phone02+'-'+this.phone03+'</strong></p>';
				}
				views += '</div>'
			}
			if(useDateView == 'Y' || useHitView == 'Y'){
				views += '<h3>'
				if(useDateView == 'Y'){
					views += this.inputDate;
				}
				if(useDateView == 'Y' && useHitView == 'Y'){
					views += '&nbsp;&nbsp;||&nbsp;&nbsp;';
				}
				if(useHitView == 'Y'){
					views += '조회수&nbsp;:&nbsp;'+this.hits;
				}
				views += '</h3>';
			}
			views += '</div>';
			
			//첨부파일 소팅
			if(eval(useFile) != 0 && !(this.attachFile01Name == null && this.attachFile02Name == null)){
				views += '<div class="fileArea">';
				for (i=1;i<=eval(useFile);i++){				
					if(eval('this.attachFile0'+i+'Name') != null){
						views += '<a href="'+attachURL+eval('this.attachFile0'+i)+'" target="_blank"><img src="../images/admin/icon_addfile.png">'+eval('this.attachFile0'+i+'Name')+'</a>';
					};					
				}
				views += '</div>'
			}
			//게시글 소팅
			views += '<div class="BBSContents">'+ this.content +'</div>'
			views += '<div class="btnArea">';
			if(this.userID == loginUserID){
				views += '<button type="button" onClick="writeAct('+this.seq+')">수정하기</button>';
			}
			views += '<button type="button" onClick="listAct('+page+')">목록보기</button>';
			if(useReply == "Y" && eval(replyPermit) >= eval(loginUserLevel)){
				views += '<button type="button" onClick="writeAct('+this.seq+',\'reply\')">답변달기</button>';
			}
			if(eval(deletePermit) >= eval(loginUserLevel) || this.userID == loginUserID || this.userID == 'guest'){
				if(this.userID == loginUserID || loginUserType == 'admin'){
					views += '<button type="button" onClick="deleteData(\''+useApi+'\','+this.seq+')">삭제하기</button>';		
				}else{
					views += '<button type="button" onClick="modalAct(\''+useApi+'\','+this.seq+',\'deletes\')">삭제하기</button>';
				}
			}
			views += '</div>';
		})
		$('#contentsView').removeAttr('class')
		$('#contentsView').addClass('BBSView')
		$('#contentsView').html(views);
		
		//댓글 기능 소팅
		if(useComment != 'N' && commentPermit >= loginUserLevel){
			var commentArea = '';
			commentArea += '<div class="commentArea">'
			if(eval(commentPermit) >= eval(loginUserLevel)){
				commentArea += '<form class="commentWrite">';
				commentArea += '<input type="hidden" name="seq" value="" />';
				commentArea += '<input type="hidden" name="boardCode" value="'+boardCode+'" />';
				commentArea += '<input type="hidden" name="boardSeq" value="'+seq+'" />';
				if(eval(commentPermit) == 10 && loginUserID == ''){
					commentArea += '<div>'
					commentArea += '<h1>작성자</h1><input type="text" name="userName" value="" /><input type="hidden" name="userID" value="guest" />';
					commentArea += '<h1>비밀번호</h1><input type="password" name="pwd" name="userID" value="" />';
					commentArea += '</div>'
				}
				if(loginUserID != ''){
					commentArea += '<div>'
					commentArea += '<h1>'+loginUserName+'</h1><input type="hidden" name="userID" value="'+loginUserID+'" /><input type="hidden" name="userName" value="'+loginUserName+'" />';
					commentArea += '</div>'
				}
				commentArea += '<textarea name="content"></textarea>';
				commentArea += '<button type="button" onClick="confirmComment()">댓글달기</button>';
				commentArea += '</form>';
			}
			commentArea += '<ul class="commentList"></ul></div>'
		}
		$('#contentsView').append(commentArea);
		commentAct(seq)
	})
}
//댓글관련 시작
function commentAct(seq){	
	var comments = '';
	var commentsAjax = $.get(commentApi,{'boardSeq':seq},function(data){
		if(data.totalCount != 0 ){
			$.each(data.comment, function(){
				comments += '<li>';
				comments += '<form  class="comment'+this.seq+'">'
				comments += '<h1>'+this.userName+'<span>'+this.userID+'</span><span>'+this.inputDate+'</span></h1>';
				if(loginUserID == this.userID || loginUserType == 'admin' || this.userID =='guest' ){
					comments += '<button type="button" onClick="';
					if(this.userID == loginUserID || loginUserType == 'admin'){
						comments += 'deleteData(\''+commentApi+'\','+this.seq+',\'comment\')';
					}else{
						comments += 'modalAct(\''+commentApi+'\','+this.seq+',\'deletes\')';
					}
					comments += '">삭제하기</button>';
				}
				comments += '<button type="button" class="modifyBtn" onClick="';
				if(loginUserID == this.userID){
					comments += 'commentModify('+this.seq+')';
				}else{
					comments += 'commentModify('+this.seq+',\'pwd\')';
				}
				comments += '">수정하기</button>';
				comments += '<p name="content">'+this.content+'</p>';
				comments += '<h3>'+this.userIP+'</h3>';
				comments += '</form>'
				comments += '</li>'
			})
		}else{
			comments += '<li class="noReply">아직 작성된 댓글이 없습니다.</li>'
		}
		$('#contentsView div.commentArea ul').html(comments)
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

//FAQ보기
function openView(viewSeq){
	$('.openView').remove();
	var openViewAjax = $.get(useApi,{'boardCode':boardCode,'seq':viewSeq},function(data){
		var openViews = '';
		openViews += '<tr class="openView"><td colspan="20"><div>'
		$.each(data.board, function(){
			openViews += this.content;
			openViews += '</div></td></tr>';
		})
		$('tr#line'+viewSeq).after(openViews)
	})
}