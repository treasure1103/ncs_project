//	게시판 뷰페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

function writeAct(writeSeq){
	writeSeq = writeSeq ? writeSeq : '';
	seq = writeSeq; //파일관련 스크립트 사용

	//상단메뉴
	$('.searchArea').remove();
	
	//출력변수 지정
	var popupType = '';
	var width = '';
	var height = '';
	var _top = '';
	var _left = '';
	var subject = '';
	var startDate = '';
	var endDate = '';
	var popupURL= '';
	var popupTarget = '';
	var enabled = '';
	var attachFile = '';
	var imageURL = '';
		
	if(seq != ''){
		var writeAjax = $.get(useApi,{'seq':seq},function(data){
			imageURL = data.imageURL;
			$.each(data.popup, function(){				
				popupType = this.popupType;
				width = this.width;
				height = this.height;
				_top = this._top;
				_left = this._left;
				subject = this.subject;
				startDate = this.startDate;
				endDate = this.endDate;
				popupURL = this.popupURL;
				popupTarget = this.popupTarget;
				enabled = this.enabled;
				attachFile = this.attachFile;
			})
			writePrint();
		})
	}else{
		writePrint();
	}
	
	//게시판 생성
	function writePrint(){
		var writes ='';
		writes += '<form class="writeform" method="post" action="'+useApi+'" enctype="multipart/form-data">';
		
		//seq값 선언
		writes += '<input type="hidden" name="seq" value="'+seq+'" />';
		writes += '<input type="hidden" name="popupType" value="all" />';
		
		//입력영역 시작
		writes += '<ul>';
/*
		writes += '<li><h1>구분</h1>';
		writes += '<select name="popupType" class="'+popupType+'">'+optWrite['pageType']+'</select></li>';
*/		
		//제목 입력
		writes += '<li><h1>제목</h1><input type="text" name="subject" class="subject" value="'+subject+'" /></li>';

		//사이즈 입력
		writes += '<li><h1>사이즈</h1>';
		writes += '<input type="text" name="width" class="tel" value="'+width+'" /> px&nbsp;*&nbsp;';
		writes += '<input type="text" name="height" class="tel" value="'+height+'" /> px</li>';

		/*
		//팝업위치
		writes += '<li><h1>팝업위치</h1>';
		writes += '<input type="text" name="_top" class="tel" value="'+_top+'" /> px&nbsp;*&nbsp;';
		writes += '<input type="text" name="_left" class="tel" value="'+_left+'" /> px</li>';
		*/

		//내용 이미지
		writes += '<li><h1>내용 이미지</h1>';
		if(attachFile == '' || attachFile == null){
			writes += '<input type="file" name="attachFile" />'
		}else{
			writes += '<div id="attachFile" class="attachFile"><img src="'+imageURL+attachFile+'"><br /><button type="button" onclick="deleteFileAct(\'attachFile\')">첨부파일삭제</button></div><input type="checkbox" name="delFile01" value="Y" />';
		}
		writes += '</li>';

		//링크주소
		writes += '<li><h1>링크주소</h1><input type="text" name="popupURL" class="subject" value="'+popupURL+'" /></li>';

		//링크구분
		if(popupTarget == '_blank'){
			_blank = 'checked="checked"';
			_parent = '';
		} else {
			_blank = '';
			_parent = 'checked="checked"';
		}

		writes += '<li><h1>링크구분</h1>';
		writes += '<input type="radio" id="useBlank" name="popupTarget" value="_blank" '+_blank+' /><label for="useBlank">새창</label>';
		writes += '<input type="radio" id="useParent" name="popupTarget" value="_parent" '+_parent+' /><label for="useParent">기존창</label></li>';

		//사용기간
		writes += '<li><h1>사용기간</h1>';
		writes += '<div class="datePicker"><input type="text" name="startDate" class="cal" value="'+startDate+'" readonly="readonly" /></div>&nbsp;~&nbsp;';
		writes += '<div class="datePicker"><input type="text" name="endDate" class="cal"  value="'+endDate+'" readonly="readonly" /></div></li>';

		//사용여부
		if(enabled == 'Y'){
			enabledY = 'checked="checked"';
			enabledN = '';
		} else {
			enabledY = '';
			enabledN = 'checked="checked"';
		}
		writes += '<li><h1>사용여부</h1>';
		writes += '<input type="radio" id="use" name="enabled" value="Y" '+enabledY+' /><label for="use">사용</label>';
		writes += '<input type="radio" id="useless" name="enabled" value="N" '+enabledN+' /><label for="useless">사용대기</label>&nbsp;&nbsp;(확인 후 사용여부 수정 바랍니다.)</li>';
		writes += '</ul>';
		writes += '</form>';
		writes += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'
		writes += '<div class="btnArea">';
		writes += '<button type="button" onClick="multipartSendData(\'writeform\',\'new\');">';
		if(seq != ''){
			writes += '수정하기';
		}else{
			writes += '등록하기';
		}
		writes += '</button>';
		writes += '<button type="button" onClick="listAct('+page+');">목록으로</button>';
		writes += '</div>';
		$('#contentsArea').removeAttr('class');
		$('#contentsArea').addClass('BBSWrite');
		$('#contentsArea').html(writes);
		pickerAct();//데이트피커 사용	
		fileformAct();//파일 첨부 사용시	
	}

}


//작성완료
function viewAct(writeSeq){
	writeAct(writeSeq)
};