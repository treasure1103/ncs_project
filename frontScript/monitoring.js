//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기
//보드 정보 선언
var useApi = '../api/apiMonitoring.php';
var memberApi = '../api/apiMember.php';
var chainsearchApi = '../api/apiSearch.php';
var smsApi = '../api/apiSMStutor.php';
var seq = seq ? seq : '' ;
userLevel = userLevel ? userLevel :9;
var page = page ? page : 1;
var totalCount = '';
var listCount = 10; //한페이지 게시물 소팅개수
var pagerCount = 10; //페이저카운트

//리스트 소팅
function listAct(page){
	
	//상단 액션 부분	
	var actionArea = '';
	var today = new Date();

	actionArea += '<div class="searchArea"><form class="searchForm" action="javascript:searchAct()" style="display: inline;">';
    actionArea += '<input type="radio" name="selectSearch" id="searchDate" value="searchDate" checked="checked" onChange="searchTypeSelect(this.value)" /><label for="searchDate">기간검색</label>&nbsp;&nbsp;&nbsp;'
	actionArea += '<select name="searchYear" onchange="searchStudy(\'lectureDay\')">';
	var i= '';
	var thisYear = today.getFullYear();
	for(i= 2016; i <= thisYear; i++){
		if(i != thisYear){
			actionArea += '<option>'+i+'년</option>';
		}else{
			actionArea += '<option selected="selected">'+i+'년</option>';
		}
		
	}
    actionArea += '</select>';
	actionArea += '<select name="searchMonth" onchange="searchStudy(\'lectureDay\')">';
	var h= '';
	var thisMonth = today.getMonth()+1; //January is 0!
	for(h = 1; h <= 12; h++){
		if(h != thisMonth){
			actionArea += '<option>'+h+'월</option>';
		}else{
			actionArea += '<option selected="selected">'+h+'월</option>';
		}

	}
    actionArea += '</select>';
	//actionArea += '<li>';
	actionArea += '<br />※ 강사를 재배정하려면 1. 재배정 파일 다운로드를 클릭하여 2.배정할 강사ID를 입력 후 3.파일을 등록하면 반영됩니다.';
	actionArea += '<br /><button type="button" onClick="excelAct()" >재배정 파일 다운로드</button></form>';
	actionArea += '<form action="./tutorChangeOK.php" method="post" enctype="multipart/form-data" style="display: inline;">';
	actionArea += '<input type="file" name="uploadFile" />&nbsp;<button type="submit">파일업로드</button>';
	//actionArea += '</li>';

	actionArea += '</form></div>';
	$('#contents > h1').after(actionArea);
	
	//게시물 소팅부분
	var contents = '';
	contents += '<form class="sendSMS" method="post">';
	contents += '<div class="scrollArea">'
	contents += '<table style="min-width:1360px;"><thead><tr>';
	contents += '<th style="width:40px;"><input type="checkbox" id="checkAll" onChange="checkboxAllCheck(\'checkAll\')" /><label for="checkAll"></label></th>';
	contents += '<th style="width:50px;">번호</th>';
	contents += '<th>과정명<br />수강기간</th>';
	contents += '<th style="width:105px;"><input type="checkbox" id="sendStart" onChange="checkboxAllCheck(\'sendStart\')" /><label for="sendStart">배정안내</label></th>';
	contents += '<th style="width:105px;"><input type="checkbox" id="sendEnd" onChange="checkboxAllCheck(\'sendEnd\')" /><label for="sendEnd">첨삭시작</label></th>';
	contents += '<th style="width:105px;"><input type="checkbox" id="sendPush" onChange="checkboxAllCheck(\'sendPush\')" /><label for="sendPush">미첨삭독려</label></th>';
	contents += '<th style="width:120px;">첨삭기간</th>';
	contents += '<th style="width:120px;">강사아이디<br />강사명</th>';
	contents += '<th style="width:100px;">배정인원</th>';
	contents += '<th style="width:100px;">중간평가현황<br />(응시/채점완료)</th>';
	contents += '<th style="width:100px;">최종평가현황<br />(응시/채점완료)</th>';
	contents += '<th style="width:100px;">과제현황<br />(응시/채점완료)</th>';
	contents += '<th style="width:100px;">첨삭여부</th>';
	contents += '</tr></thead><tbody>'	;
	contents += '<tr><td class="notResult" colspan="10">검색값을 선택하세요.</td></tr>'	;
	contents += '</tbody></table>';
	contents += '</div>'
	contents += '</form>';
	contents += '<div class="btnArea">';
	contents += '<button type="button" onClick="sendMessege(\'smsTutor\')">SMS 발송</button>';
	contents += '<button type="button" onClick="sendMessege(\'emailTutor\')">Email 발송</button>';
	contents += '</div>';
	$('#contentsArea').removeAttr('class');
	$('#contentsArea').addClass('BBSList');
	$('#contentsArea').html(contents);
	ajaxAct();
	var thisYear = today.getFullYear();
	var thisMonth = today.getMonth()+1; //January is 0!
	if(thisMonth <= 9){
		thisMonth = '0'+thisMonth;
	}
	var checkDate = thisYear +'-'+thisMonth;
	searchStudy('lectureDay',checkDate)
}

function ajaxAct(listPage,sortData){
	listPage = listPage ? listPage : page ;
	page = listPage;
	sortData = sortData ? sortData : '';
	var listAjax = $.get(useApi,'page='+page+'&userLevel='+userLevel+'&list='+listCount+sortData,function(data){
		totalCount = data.totalCount;
		//alert(totalCount)
		var lists = '';
		var j = totalCount;
		if(page != 1){
			j = (page-1) * listCount
		}
		if (totalCount != 0 && loginUserLevel <= userLevel){
			j = totalCount;
			$.each(data.tutor, function(){
					lists += '<tr>';
					lists += '<td><input type="checkbox" name="check['+j+']" id="check'+j+'" class="checkAll" /><label for="check'+j+'"></label></td>';
					lists += '<td>'+j+'</td>';
					lists += '<td onClick="globalModalAct(\'contentsView\',\'\',\''+this.contentsCode+'\')" style="cursor:pointer;">'+this.contentsName+'<br/>';
					lists += data.lectureStart+' ~ '+data.lectureEnd+'</td>';
					lists += '<td><input type="checkbox" id="sendStart_'+j+'" name="sendType[]" class="sendStart" value="start/'+this.tutorID+'/'+this.lectureOpenSeq+'" /><label for="sendStart_'+j+'">선택하기</label><br /><button type="button" onclick="viewMessage(\'start/'+this.tutorID+'/'+this.lectureOpenSeq+'\')">내용보기</button></td>';
					lists += '<td><input type="checkbox" id="sendEnd_'+j+'" name="sendType[]" class="sendEnd" value="end/'+this.tutorID+'/'+this.lectureOpenSeq+'" /><label for="sendEnd_'+j+'">선택하기</label><br /><button type="button"  onclick="viewMessage(\'end/'+this.tutorID+'/'+this.lectureOpenSeq+'\')">내용보기</button></td>';
					lists += '<td><input type="checkbox" id="sendPush_'+j+'" name="sendType[]" class="sendPush" value="push/'+this.tutorID+'/'+this.lectureOpenSeq+'" /><label for="sendPush_'+j+'">선택하기</label><br /><button type="button"  onclick="viewMessage(\'push/'+this.tutorID+'/'+this.lectureOpenSeq+'\')">내용보기</button></td>';
					lists += '<td>'+data.tutorDeadline+' 까지</td>';
					lists += '<td onClick="globalModalAct(\'memberView\',\'\',\''+this.tutorID+'\')" style="cursor:pointer;">'+this.tutorID+'<br/>';
					lists += this.tutorName+'</td>';
					lists += '<td>'+this.tutorCount+'</td>';
					lists += '<td>'+this.midSubmit+' / '+this.midComplete+'</td>';
					lists += '<td>'+this.testSubmit+' / '+this.testComplete+'</td>';
					lists += '<td>'+this.reportSubmit+' / '+this.reportComplete+'</td>';

					if(this.testStatus == 'Y' || this.reportStatus == 'Y') {
						var tutorComplete = '첨삭중';
					} else {
						var tutorComplete = '완료';
					}

					lists += '<td>'+tutorComplete+'</td>';
					lists += '</tr>';
					j--;
			})
		}else if(loginUserLevel > userLevel){
			lists += '<tr><td class="notResult" colspan="15">조회 권한이 없습니다.</td></tr>'
		}else{
			lists += '<tr><td class="notResult" colspan="15">검색 결과가 없습니다.</td></tr>'
		}
		$('.BBSList tbody').html(lists);
		pagerAct();
	})
}

//검색관련

function searchTypeSelect(types){
	$('.searchArea div.searchChangeArea select, .searchArea div.searchChangeArea input[type="text"]').remove();
	var chageSearch =''
	if(types == 'searchDate'){
		chageSearch += '<select name="searchYear" onchange="searchStudy(\'lectureDay\')">';
		var today = new Date();
		var i= '';
		var thisYear = today.getFullYear();
		for(i= 2016; i <= thisYear; i++){
			if(i != thisYear){
				chageSearch += '<option>'+i+'년</option>';
			}else{
				chageSearch += '<option selected="selected">'+i+'년</option>';
			}
			
		}
		chageSearch += '</select>';
		chageSearch += '<select name="searchMonth" onchange="searchStudy(\'lectureDay\')">';
		var h= '';
		var thisMonth = today.getMonth()+1; //January is 0!
		for(h = 1; h <= 12; h++){
			if(h != thisMonth){
				chageSearch += '<option>'+h+'월</option>';
			}else{
				chageSearch += '<option selected="selected">'+h+'월</option>';
			}
			
		}
		chageSearch += '</select>';	
	}else if(types == 'searchCompany'){
		chageSearch += '<input type="text" name="searchCompany" onkeyup="searchStudy(\'company\',this)">';	
	}
	$('.searchArea div.searchChangeArea').append(chageSearch)
	//actionArea += '<input type="text" name="searchCompany" onkeyup="searchStudy(\'company\',this)" />'
}

function searchStudy(types,vals){
	if(types=='lectureDay'){
		$('select[name="lectureDay"], strong.noticeSearch, select[name="companyCode"]').remove();
		var dateChain = ''
		dateChain += $('select[name="searchYear"]').val().replace('년','') +'-';
		if(eval($('select[name="searchMonth"]').val().replace('월','')) < 10){
			dateChain += '0'+$('select[name="searchMonth"]').val().replace('월','');
		}else{
			dateChain += $('select[name="searchMonth"]').val().replace('월','');
		}
		$.get(chainsearchApi,{'searchMode':types, 'searchDay':dateChain},function(data){
			var selectWrite = ''
			if(data.totalCount !=0){
				selectWrite += '<select name="lectureDay" onChange="searchStudy(\'printCompany\',this);searchAct()">';
				selectWrite += '<option value="">기간을 선택해주세요</option>'
				$.each(data.searchResult,function(){
					selectWrite += '<option value="'+this.lectureStart+'~'+this.lectureEnd+'">'+this.lectureStart+'~'+this.lectureEnd+'</option>';
				})
				selectWrite += '</select>'	
			}else{
				selectWrite += '<strong class="noticeSearch price">&nbsp;&nbsp;&nbsp;검색결과가 없습니다.</option>'
			}		
			$('select[name="searchMonth"]').after(selectWrite)
		})
	}else if(types=='company'){
		$('select[name="companyCode"], strong.noticeSearch').remove();
		var searchName = vals.value
		if( searchName != ''&& searchName != ' ' ){
			$.get(chainsearchApi,{'searchMode':types, 'searchName':searchName},function(data){
				var selectWrite = ''
					selectWrite += '<strong class="noticeSearch price">&nbsp;&nbsp;&nbsp;검색결과가 없습니다.</option>'

				$('input[name="searchCompany"]').after(selectWrite)
	
			})
		}else{
			$('.searchChangeArea select, strong.noticeSearch').remove();
		}
	}else if(types=='printDate'){
		$('select[name="lectureDay"], strong.noticeSearch').remove();
		var companyCode = vals.value;
		$.get(chainsearchApi,{'searchMode':'study', 'companyCode':companyCode},function(data){
			var selectWrite = ''
			if(data.totalCount !=0){
				selectWrite += '<select name="lectureDay" onChange="searchAct()">';
				selectWrite += '<option value="">기간을 선택해주세요</option>'
				$.each(data.searchResult,function(){
					selectWrite += '<option value="'+this.lectureStart+'~'+this.lectureEnd+'">'+this.lectureStart+'~'+this.lectureEnd+'</option>';
				})
				selectWrite += '</select>'	
			}else{
				selectWrite += '<strong class="noticeSearch price">&nbsp;&nbsp;&nbsp;검색결과가 없습니다.</option>'
			}		
			$('select[name="companyCode"]').after(selectWrite)
		})
	}
}

function viewMessage(values){
	var modalWrite =''
	modalWrite +='<div id="modal">';
	modalWrite += '<div class="messageView">';
	modalWrite += '<h1>발송메세지 확인<button type="button" onClick="modalClose()"><img src="../images/admin/btn_close.png" alt="닫기" /></button></h1>';
	modalWrite += '<div class="BBSWrite">'
	modalWrite += '<h1>메세지 구분'
	modalWrite += '<select onchange="viewMessageType(\''+values+'\',this)">';
	modalWrite += '<option value="smsTutor" selected="selected">문자메시지</option>'
	modalWrite += '<option value="emailTutor">이메일</option>'
	modalWrite += '</select>';
	modalWrite += '</h1>'
	modalWrite += '<ul>'
	modalWrite += '</ul>'
	modalWrite += '<div class="btnArea">'
	modalWrite += '<button type="button" onclick="sendMessege(\'modal\')">메세지 보내기</button>'
	modalWrite += '<button type="button" onclick="modalClose()">취소</button>'
	modalWrite += '</div>'
	modalWrite +='</div>';	
	$('#contents').after(modalWrite)
	modalAlign();
	viewMessageType(values)
}
function viewMessageType(values,obj){
	obj = obj ? obj : ''
	var types = ''
	if(obj != ''){
		types = obj.options[obj.selectedIndex].value;
	}else{
		types = 'smsTutor'
	}
	var messageWrite = ''
	$.get(smsApi,{'sendType':values,'device':types},function(data){
		messageWrite += '<li><h1>발송 대상</h1>'+data.userName+' <span>('+data.userID+')</span></li>'
		if(types == 'smsTutor'){
			messageWrite += '<li><h1>연락처</h1>'+data.receiveNum;
		}else{
			messageWrite += '<li><h1>이메일</h1>'+data.email;
		}
		messageWrite += '<li><h1>전송타입</h1>'+data.typeName;
		messageWrite += '<li>'
		messageWrite += '<form class="smsSendModal">'
		messageWrite += '<input type="hidden" name="sendType[]" value="'+values+'" />'
		messageWrite += '<input type="hidden" name="device" value="'+types+'" />'
		messageWrite += '<input type="hidden" name="messageBox" value="Y" />'
		messageWrite += '<textarea name="sendMessage" onKeyUp="checkByte(this.form);">'+data.message+'</textarea><br />';
		messageWrite += '<input type="text" name="messagebyte" value="0" size="1" maxlength="2" readonly> / 90 byte';
		messageWrite += '</form>'
		messageWrite += '</li>'
		$('#modal div.BBSWrite ul').html(messageWrite)
	})
}

function sendMessege(types){
	var sendData = ''
	if(types == 'emailTutor'){
		sendData = $('.sendSMS').serialize();
		sendData += '&device=emailTutor'
	}else if(types == 'smsTutor'){
		sendData = $('.sendSMS').serialize();
		sendData += '&device=smsTutor'
	}else if(types == 'modal'){
		sendData = $('.smsSendModal').serialize();
	}
	if(confirm('발송하시겠습니까?') == true){
		$.post(smsApi,sendData,function(data){
			if(data.result == 'success'){
				alert('발송이 완료되었습니다.')
			}else{
				alert('발송이 되지 않았습니다.')
			}
		})
	}	
}


var clearChk=true;
var limitByte = 90; //바이트의 최대크기, limitByte 를 초과할 수 없슴
// textarea에 마우스가 클릭되었을때 초기 메시지를 클리어
function clearMessage(frm){

  if(clearChk){ 
    frm.sendMessage.value="";
    clearChk=false;
  }

}

// textarea에 입력된 문자의 바이트 수를 체크
function checkByte(frm) {
   
        var totalByte = 0;
        var message = frm.sendMessage.value;

        for(var i =0; i < message.length; i++) {
                var currentByte = message.charCodeAt(i);
                if(currentByte > 128) totalByte += 2;
	else totalByte++;
        }
        frm.messagebyte.value = totalByte;

        if(totalByte > limitByte) {
                alert( limitByte+"바이트까지 전송가능합니다.");
		frm.sendMessage.value = message.substring(0,limitByte);
        }
}

function excelAct(){
	searchValue = $('.searchForm').serialize();
	searchValue = '&'+searchValue;
	top.location.href='tutorChange.php?'+searchValue;
}