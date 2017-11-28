//	게시판 뷰페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//게시판 보기 스크립트 시작
function writeAct(){
	$('.searchArea').remove();

	var matchingType = '';
	writePrint();

	//게시판 생성
	function writePrint(){
		var writes ='';

			//개별등록,수정
			writes += '<h2>영업팀장지정</h2>'
			writes += '<form class="writeform" method="post">';
			writes += '<ul>';
			
			//담당자구분
			writes += '<li id="matchingType">';
			writes += '<h1>설명</h1>';
			writes += '영업팀장(상위)는 영업담당자(하위)의 개설정보를 볼 수 있습니다.<input name="matchingType" type="hidden" value="marketer"/>';
			writes += '</li>';
			
			//담당자
			writes += '<li id="userID">';
			writes += '<h1>영업팀장 (상위)</h1>';
			writes += '<input name="userName" type="text" /> <button type="button" name="userIDbtn" onClick="searchSelect(\'userID\',\''+memberApi+'\')">검색</button>';
			writes += '</li>';

			//사업자
			writes += '<li id="matchingValue">';
			writes += '<h1>영업담당자 (하위)</h1>';
			writes += '<input name="userName" type="text" /> <button type="button" name="matchingValueBtn" onClick="searchSelect(\'matchingValue\',\''+memberApi+'\')">검색</button>';
			writes += '</li>';	

			writes += '</ul>';
			writes += '<div class="btnArea">';
			writes += '<button type="button" onClick="checkStudyForm()">등록하기</button>';
			writes += '<button type="button" onClick="resetInput()">초기화</button>';
			writes += '<button type="button" onClick="listAct()">목록으로</button>';
			writes += '</div>';
			writes += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'
			writes += '</form>';

		$('#contentsArea').addClass('BBSWrite');
		$('#contentsArea').html(writes);
		//ajaxAct();
	}
}


//수강 개별 등록
function writeStudy(){
	var sendData = $('.writeform').serialize();
	$.ajax({
		url:useApi,
		type:'POST',
		data:sendData,
		success:function(){
			alert('등록 되었습니다.');
		},
		fail:function(){
			alert('등록에 실패하였습니다.')
		}
	})
}

function resetInput(){
	$('.writeform input[type="text"]').val('');
	$('.writeform div.').html('')
	$('.writeform button[type="submit"]').html('등록하기')
}

//입력폼 체크
function checkStudyForm(){
	$('.writeform strong.price').remove();
	var checkFalse = 0;
	if(!$('.writeform select[name="userID"]').html()){
		$('.writeform button[name="userIDbtn"]').after('<strong class="price"> 영업팀장(상위)을 입력해주세요.</strong>')
		checkFalse ++;
	}
	if(!$('.writeform select[name="matchingValue"]').html()){
		$('.writeform button[name="matchingValueBtn"]').after('<strong class="price"> 영업담당자(하위)를 입력해주세요.</strong>')
		checkFalse ++;
	}
		
	if(checkFalse == 0){
		writeStudy();
	}
}

