//	게시판 뷰페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기


//게시판 보기 스크립트 시작
function writeAct(writeSeq){
	writeSeq = writeSeq ? writeSeq : '';
	seq = writeSeq //파일관련 스크립트 사용
	
	//상단메뉴	
	$('.searchArea').remove();	
	//맴버정보 기록
	var mobile01 = '';
	var mobile02 = '';
	var mobile03 = '';
	var email01 = '';
	var email02 = '';
	var subject = '';
	var content = '';
		
	var loginUserInfo = $.get('../api/apiLoginUser.php',{},function(data){
		if(loginUserID != ''){
			mobile01 = data.mobile01;
			mobile02 = data.mobile02;
			mobile03 = data.mobile03;	
			email01 = data.email01;	
			email02 = data.email02;	
		}
	}).done(function(){
			
		if(seq != ''){
			modifyAjax = $.get(useApi,{'seq':seq,'boardType':boardType},function(data){
				subject = data.consult[0].subject;
				content = data.consult[0].content;
			});			
		}
		var writes = ''
		//입력영역 시작
		writes += '<form class="writeForm" action="javascript:checkData(\'writeForm\')">';
		writes += '<input type="hidden" name="boardType" value="'+boardType+'">'
		if(loginUserID != ''){
			writes += '<input type="hidden" name="userName" value="'+loginUserName+'">'
			writes += '<input type="hidden" name="userID" value="'+loginUserID+'">'
		}else{
			writes += '<input type="hidden" name="userID" value="guest">'
		}
		writes += '<ul>';
		
		//이름
		writes += '<li><h1>이름</h1>';
		if(loginUserID != ''){
			writes += ''+loginUserName;
		}else{
			writes += '<input type="text" class="name" />';
		}
		writes += '</li>';
		
		//아이디
		if(loginUserID != ''){
			writes += '<li><h1>아이디</h1>'+loginUserID+'</li>';
		}
		writes += '<li><h1>연락처</h1>';
		writes += '<select name="phone01" class="'+mobile01+'">'+optWrite['mobile01']+'</select>&nbsp;-&nbsp;'
		writes += '<input type="tel" name="phone02" class="year" value="'+mobile02+'" />&nbsp;-&nbsp;';
		writes += '<input type="tel" name="phone03" class="year" value="'+mobile03+'" />';
		writes += '</li>'
		
		writes += '<li><h1>이메일</h1>';
		writes += '<input class="name" name="email01" type="text" maxlength="20" value="'+email01+'" />&nbsp;@&nbsp;<select name="email02Chk" class="'+email02+'" id="email02">'+optWrite['email02']+'</select>&nbsp;<input type="text" name="email02" id="emails" class="name" value="'+email02+'" />';
		writes += '</li>';
		writes += '<li><h1>제목</h1><input type="text" name="subject" class="subject" value="'+subject+'" /></li>';
		writes += '<li><h1>문의 내용</h1><textarea name="content">'+content+'</textarea></li>';
		writes += '</ul>'
		writes += '<div class="btnArea">';
		writes += '<button type="submit">';
		if(seq != ''){
			writes += '수정하기'
		}else{
			writes += '문의하기'
		}
		writes += '</button>'
		if(seq != ''){
			writes += '<button type="button" onClick="deleteData('+seq+')">삭제하기</button>'
		}
		if(modes != 'writeMode'){
			writes += '<button type="button" onclick="listAct(page)">목록으로</button>'
		}
		writes += '</div>';
		writes += '</form>';
		$('#wrap').removeAttr('class');
		$('#contentsArea').removeAttr('class')
		$('#contentsArea').addClass('BBSWrite')
		$('#contentsArea').html(writes);
		findOpt();//selct 선택자 찾기
		emailSelect();//이메일 select 호출 사용시 같이 호출	
	})
}

function checkData(writeclass){
	if($('input[name="subject"]').val() == ''){
		alert('제목을 입력해주세요')
	}else if($('textarea[name="content"]').val() == ''){
		alert('내용을 입력해주세요')
	}else{
		//alert('aa')
		sendData(useApi,writeclass);
		alert('답변이 처리 되었습니다. 작성자에게 답변 알림(문자,이메일)이 발송되었습니다.');
		top.location.reload();
	}
}