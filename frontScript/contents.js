//보드 정보 선언
var sortData = '';
var useApi = '../api/apiContents.php';
var chapterApi = '../api/apiChapter.php';
var testApi = '../api/apiTest.php';
var reportApi = '../api/apiReport.php';
var sampleIDApi = '../api/apiSampleID.php';
var categoryApi = '../api/apiCategory.php';
var seq = seq ? seq : '' ;
var page = page ? page : 1;
var totalCount = '';
var listCount = 10; //한페이지 게시물 소팅개수
var pagerCount = 10; //페이저카운트
var totalCount = ''; //전체 페이지 카운트
var writeType = '';
var testType = '';
var sort01s = '';
var sort02s = '';

//사용옵션 가져오기
optWrite = new Array();
makeOption('contentsGrade','','')
makeOption('sourceType','','')
makeOption('enabled','','')
makeOption('examType','','')
makeOption('lectureCode','','','ALL')

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
