//공통선언
var useApi = '../api/apiHoliday.php';
var seq = '';
var page = 1;


//카테고리 소팅
var optWrite = new Array();

makeOption('enabled','','') //지역번호
makeOption('holidayType','','') //공휴일,비공휴일


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

	//게시물 외 동작 및 검색
	var actionArea = ''
	actionArea += '<div class="inputArea">';
	actionArea += '<form class="writeform">';
	actionArea += '<table><tr>';
	actionArea += '<th style="width:140px;">날짜 선택</th>';
	actionArea += '<th>공휴일명</th>';
	actionArea += '<th style="width:100px">공/비</th>';
	actionArea += '<th style="width:60px;">사용여부</th>';
	actionArea += '<td rowspan="2" style="width:140px;"><button type="button" onClick="sendData(\''+useApi+'\',\'writeform\');">등록</button></td>';
	actionArea += '</tr>';
	actionArea += '<tr>';
	actionArea += '<td><div class="datePicker"><input type="text" class="cal" name="holiday" readonly="readonly" /></td>';
	actionArea += '<td><input type="text" name="name" /></td>';
	actionArea += '<td><select name="holidayType">'+optWrite['holidayType']+'</select></td>';
	actionArea += '<td><select name="enabled">'+optWrite['enabled']+'</select></td>';
	actionArea += '</tr></table>';
	actionArea += '</form>';
	actionArea += '</div>';
	actionArea += '<div id="holidayUI" class="calendarType">';
	actionArea += '<div><button type="button" onClick="viewYear(-1)"><img src="../images/admin/btn_calprev.png" alt="이전" /></button>';
	actionArea += '<h1></h1>년';
	actionArea += '<button type="button" onClick="viewYear(1)"><img src="../images/admin/btn_calnext.png" alt="다음" /></button>';
	actionArea += '</div>';
	pickerAct();//데이트피커 사용
	$('#contents > h1').after(actionArea);

	//게시물	
	var contents = '';
	contents += '<table><thead><tr>';
	contents += '<th style="width:50px;">번호</th>';
	contents += '<th style="width:140px;">날짜 선택</th>';
	contents += '<th>공휴일명</th>';
	contents += '<th style="width:100px">공/비</th>';
	contents += '<th style="width:70px;">사용여부</th>';
	contents += '<th style="width:180px;">수정/삭제</th>';
	contents += '</tr></thead><tbody>';
	contents += '</tbody></table>';
	$('#contentsArea').removeAttr('class');
	$('#contentsArea').addClass('BBSList');
	$('#contentsArea').html(contents);
	ajaxAct();
	
	
}

//리스트 소팅동작
function ajaxAct(searchyear){
	var today = new Date();
	var thisYear = today.getFullYear();
	searchyear = searchyear ? searchyear : thisYear;
	
	$('#holidayUI h1').html(searchyear)
	var listAjax = $.get(useApi,{'year':searchyear},function(data){	
		var i = 1;	
		var lists = '';
		if (data.totalCount != 0){
			$.each(data.holiday, function(){
				lists += '<tr class="line'+this.seq+'">';
				lists += '<td>'+i+'</td>';				
				lists += '<td><div class="datePicker"><input type="text" class="cal" name="holiday" readonly="readonly" value="'+this.holiday+'" /></td>';
				lists += '<td><input type="text" name="name" value="'+this.name+'" /></td>';
				lists += '<td><select name="holidayType" class="'+this.holidayType+'">'+optWrite['holidayType']+'</select></td>';
				lists += '<td><select name="enabled" class="'+this.enabled+'">'+optWrite['enabled']+'</select></td>';
				lists += '<td>';
				lists += '<button type="button" onClick="lineSendData(\''+useApi+'\','+this.seq+',\'modifys\');">수정</button> / ';
				lists += '<button type="button" onClick="lineSendData(\''+useApi+'\','+this.seq+',\'copys\');">복사</button> / ';
				lists += '<button type="button" onclick="deleteData(\''+useApi+'\','+this.seq+');">삭제</button>';
				lists += '</td></tr>';
				i++;
			})
		}else{
			lists += '<tr><td class="notResult" colspan="20">아직 등록된 글이 없습니다.</td></tr>';
		}
		$('.BBSList tbody').html(lists)
		findOpt();
		pickerAct();//데이트피커 사용	
	})
}

function viewYear(numb){
	var years = Number($('.calendarType h1').html())
	year = years + numb;
	$('.calendarType h1').html(year)
	ajaxAct(year)
}