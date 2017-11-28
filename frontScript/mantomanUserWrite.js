//	게시판 뷰페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기


//게시판 보기 스크립트 시작
function wirteAct(){
	
	var writes ='';
	writes += '<form class="writeform" method="post" action="'+useApi+'" enctype="multipart/form-data">';
	//seq값 선언
	writes += '<input type="hidden" name="seq" value="" />';
	writes += '<input type="hidden" name="boardType" value="'+boardType+'" />';	
	//입력영역 시작
	if(boardType == 'recruit'){
		writes += '<div class="titleArea"><h2>포트폴리오 이력서 등은 압축해서 파일에 첨부해주세요.</h2></div>'
	}else{
		writes += '<div class="titleArea"><h2>정확한 정보를 입력해주셔야 빠른 상담이 가능합니다.</h2></div>'
	}
	writes += '<ul>';			
	writes += '<li><h1>이름</h1><input type="text" name="userName" class="name" /></li>';
	
	//연락처입력
	writes += '<li><h1>연락처</h1><input class="tel" type="tel" name="phone01" />&nbsp;-&nbsp;<input class="tel" type="tel" name="phone02" maxlength="4" tabindex="1" value="" style="ime-mode:disabled;" />&nbsp;-&nbsp;<input class="tel" name="phone03" type="tel" maxlength="4" tabindex="1" value="" style="ime-mode:disabled;" /></li>';
	
	//이메일 입력
	writes += '<li><h1>Email</h1><input class="name" name="email01" type="text" maxlength="20" tabindex="1" value="" />&nbsp;@&nbsp;<input type="text" name="email02" id="emails" class="name" value="" /></li>';
	writes += '<li><h1>제목</h1><input type="text" name="subject" class="subject" /></li>';
	if(boardType == 'recruit'){
		writes += '<li><h1>파일첨부</h1><input name="attachFile01" type="file" /></li>';
	}
	writes += '<li><h1>상담내용</h1><textarea name="content"></textarea></li>';
	writes += '<div class="btnArea">';
	writes += '<button type="button" onClick="multipartSendData(\'writeform\')">';
	if(boardType == 'recruit'){
		writes += '지원하기'
	}else{
		writes += '문의하기'
	}
	writes += '</button>';
	writes += '</div>';
	writes += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'
	$('#contentsArea').html(writes);
	emailSelect();//이메일 select 호출 사용시 같이 호출	
	fileformAct();//파일 첨부 사용시
}

function multipartSendData(formName,types){
	var formName = $('form.'+formName);
	formName.ajaxForm({
		dataType:'text',
		beforeSubmit: function (data,form,option) {
			return true;
		},
		success: function(data,status){
			alert("완료되었습니다.");
			$('.writeform input,')
		},
		error: function(){
			//에러발생을 위한 code페이지
			alert("작성중 문제가 생겼습니다..");
		}
	});
	$(formName).submit();
	
};

function listAct(){
	wirteAct()
}
function viewAct(){
	wirteAct()
}