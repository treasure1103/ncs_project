//보드 정보 선언
var sortData = '';
var useApi = '../api/apiProgressInput.php';
var memberApi = '../api/apiMember.php';
var chainsearchApi = '../api/apiSearch.php';
var seq = seq ? seq : '' ;
userLevel = userLevel ? userLevel :9;
var page = page ? page : 1;
var totalCount = '';
var listCount = 10; //한페이지 게시물 소팅개수
var pagerCount = 10; //페이저카운트

//데이트 피커사용용
function dateAct(){
	function closePicker(){
		$('#datePicker').remove();
		$('.picked').removeClass('picked');		
	}
	function todaySel(){
		$('#datePicker').remove();
		var today = new Date();
		var dd = today.getDate();
		var mm = today.getMonth()+1; //January is 0!
		var yy = today.getFullYear();
		if(dd<10) {
			dd='0'+dd
		} 
		if(mm<10) {
			mm='0'+mm
		} 
		today = yy+'-' + mm+'-'+dd;
		$('.picked').val(today);
		$('.picked').removeClass('picked');
	}
	$('#datePicker').append('<p><button type="button" class="todaySel">오늘선택</button>&nbsp;&nbsp;<button type="button" class="pickerClose">닫기</button></p>')
	$('.pickerClose').click(function(){closePicker()})
	$('.todaySel').click(function(){todaySel()})
	$('#calendarTable').children('tbody').children('tr').children('td').click(function(){
		var dateSel = $(this).attr('id');
		$('.picked').val(dateSel);
		closePicker();
	})
};

function listAct(){
	writeAct();
}

//게시판 보기 스크립트 시작
function writeAct(){
	var startDate = '';
	var endDate = '';
	writePrint();

	//게시판 생성
	function writePrint(){
		var writes ='';

			//개별등록,수정
			writes += '<h2>등록</h2>'
			writes += '<form class="writeform" method="post">';
			writes += '<ul>';
			//기간설정
			writes += '<li>';
			writes += '<h1>수강기간</h1>';
			writes += '<div class="datePicker"><input type="text" name="lectureStart" class="cal" value="'+startDate+'" readonly="readonly" /></div>&nbsp;~&nbsp;';
			writes += '<div class="datePicker"><input type="text" name="lectureEnd" class="cal"  value="'+endDate+'" readonly="readonly" /></div>&nbsp;';
			writes += '</li>';
			//과정선택
			writes += '<li id="contentsCode">';
			writes += '<h1>과정선택</h1>';
			writes += '<input name="searchName" type="text" /> <button type="button" onClick="searchSelect(\'contentsCode\',\''+chainsearchApi+'\',\'contents\')">검색</button>';
			writes += '</li>';
			//수강생명
			writes += '<li id="userID">';
			writes += '<h1>수강생(이름)</h1>';
			writes += '<input name="userName" type="text" /> <button type="button" onClick="searchSelect(\'userID\',\''+memberApi+'\')">검색</button>';
			writes += '</li>';
			//진도율
			writes += '<li id="progress">';
			writes += '<h1>진도율</h1>';
			writes += '<input name="progress" type="text" /> %';
			writes += '</li>';

			writes += '</ul>';
			writes += '<div class="btnArea">';
			writes += '<button type="button" onClick="writeStudy()">등록하기</button>';
			writes += '<button type="button" onClick="resetInput()">내용 초기화</button>';
			writes += '</div>';
			writes += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'
			writes += '</form>';

		$('#contentsArea').removeAttr('class');
		$('#contentsArea').addClass('BBSWrite');
		$('#contentsArea').html(writes);
		pickerAct();//데이트피커 사용
	}
}

//수강 개별 등록
function writeStudy(){
	var sendData = $('.writeform').serialize();
	$.ajax({
		url:useApi,
		type:'POST',
		data:sendData,
		dataType:'JSON',
		success:function(data){
			if(data.result == 'success'){
				alert('등록되었습니다.');
			} else {
				alert(data.result);
			}
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