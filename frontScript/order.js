//보드 정보 선언
var useApi = '../api/apiOrder.php';
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