//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//공통선언
var useApi = '../api/apiSendMessage.php'
var page = '';

//리스트액션
function listAct(){	
	var writes = '';
		$.get(useApi,function(data){	
			$.each(data.sendLog, function(data){
				var seq = this.seq;
				var message1 = this.message;
				var device = this.device;
				var sendType = this.sendType;

				//내용 시작
				writes += '<div class="smsSetting">';
				writes += '<form class="writeform" name="writeform" method="post" action="'+useApi+'" enctype="multipart/form-data">';			
				//seq값 선언
				writes += '<input type="hidden" name="seq" value="'+seq+'" />';
				writes += '<h1>'+device+'&nbsp;'+sendType+'</h1>';

				//입력영역 시작			
				writes += '<div class="textInputs">';
				writes += '<textarea name="message" id="ir1">'+message1+'</textarea>';
				writes += '<input type="button" onClick="sendData2('+seq+',ir1.value)" value="정보수정" />';
				writes += '<div class="clear"></div>'; 
				writes += '</div>'; 
				
				writes += '</form>'; 
				writes += '</div>'; 
				
			})
			$('#contentsArea').removeAttr('class');
			$('#contentsArea').addClass('BBSWrite');
			$('#contentsArea').html(writes);
	})
}

function sendData2(seq,message){
	var sendData = ''	
	sendData = {'seq':seq,'message':message};

	if(confirm('수정하시겠습니까?' ) == true){
		$.post(useApi,sendData,function(data){
			if(data.result == 'success'){
				alert('수정이 완료되었습니다.')
			}else{
				alert('수정 되지 않았습니다.')
			}
		})
	}
}


/*
function editorOpen(seq){
	var modalWrite = '';

	$.get(useApi,{'seq':seq},function(data){
		//var writeText = eval('data.'+writeArea);
		//alert(data.agreement)
		$.each(data.sendLog, function(data){
			modalWrite += '<div id="modal">';
			modalWrite += '<div class="modalEditor">';
			modalWrite += '<h1><strong>내용편집</strong><button type="button" onClick="modalClose()"><img src="../../images/admin/btn_close.png" alt="닫기" /></button></h1>';
			modalWrite += '<div>';
			modalWrite += '<form class="writeform" method="post" action="'+useApi+'" enctype="multipart/form-data">';
			modalWrite += '<div>'
			modalWrite += '<h1>'+this.sendType+'</h1>';	
			modalWrite += '<textarea name="message" id="ir1" rows="10" cols="100" style="width:827px; height:460px; display:none;">'+this.message+'</textarea>';
			modalWrite += '<input type="hidden" name="seq" value="'+seq+'">';
			modalWrite += '<div class="btnArea"><button type="button" onClick="submitInfo(this)">정보등록</button></div>';
			modalWrite += '</form>';
			modalWrite += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'
			modalWrite += '</div>';
			modalWrite += '</div>';
			//--모달테두리
			modalWrite += '</div>';
		})
		$('#contents').after(modalWrite);
		//editorView();	
		modalAlign();
		nhn.husky.EZCreator.createInIFrame({
			oAppRef: oEditors,
			elPlaceHolder: "ir1",
			sSkinURI: "../lib/SmartEditor/SmartEditor2Skin.html",	
			htParams : {
				bUseToolbar : true,				// 툴바 사용 여부 (true:사용/ false:사용하지 않음)
				bUseVerticalResizer : false,		// 입력창 크기 조절바 사용 여부 (true:사용/ false:사용하지 않음)
				bUseModeChanger : true,			// 모드 탭(Editor | HTML | TEXT) 사용 여부 (true:사용/ false:사용하지 않음)
				//aAdditionalFontList : aAdditionalFontSet,		// 추가 글꼴 목록
				fOnBeforeUnload : function(){
					//alert("완료!");
				}
			}, //boolean
			fOnAppLoad : function(){
				//예제 코드
				//oEditors.getById["ir1"].exec("PASTE_HTML", ["로딩이 완료된 후에 본문에 삽입되는 text입니다."]);
			},
			fCreator: "createSEditor2"
		});	
	 })
}
	
//에디터 사용시 호출
var oEditors = [];

function submitInfo(elClickedObj) {
	oEditors.getById["ir1"].exec("UPDATE_CONTENTS_FIELD", []);	// 에디터의 내용이 textarea에 적용됩니다.	
	// 에디터의 내용에 대한 값 검증은 이곳에서 document.getElementById("ir1").value를 이용해서 처리하면 됩니다.	
	try {
		//elClickedObj.form.submit();				
	} catch(e) {}
	//var sendData = $('.writeform').serialize();
	//alert($('.writeform').serialize());
	sendData()
}

function lineSMS(apiName,sendSeq,acts,types){
	//type= copys, modifys
	var sendSeq = sendSeq ? sendSeq : '' ;
	var sendData = '';
	var sendObj = '';
	if(types != 'modal'){
		sendObj = $('.line'+sendSeq+'>td');
	}else{
		sendObj = $('.modalLine'+sendSeq+'>td')
	}

	var sendSerial = sendObj.each(function(){
		var inputs = $(this).find('input, select');
		if($(this).has('input, select').length){
			sendData += inputs.attr('name');
			sendData += '=';
			sendData += inputs.val().replace(/&/g,'%26');
			sendData += '&';
		}
	})
	if(acts =="copys"){
		sendData += 'seq=';
	}else{
		sendData += 'seq='+sendSeq;
	}

	var msg = ''
	var resultMsg = ''
	if(acts =="copys"){
		msg = '복사하시겠습니까?'
		resultMsg = '복사되었습니다.'
	}else{
		msg = '수정하시겠습니까?'
		resultMsg = '수정되었습니다.'
	}
	//alert(sendData)
	if(confirm(msg)){
		$.ajax({
			method:'POST',
			url:apiName,
			dataType:'text',
			data: sendData,
			success:function(data){
				alert(resultMsg);
				if(types == 'modal'){
					$('.btnRefresh').click();
				}else{
					if(seq != ''){
						viewAct(data);
					}else{
						ajaxAct()
					}
				}
			},
			fail:function(){
				alert('정상적으로 처리되지 않았습니다.')
			}
		})
	}
}*/