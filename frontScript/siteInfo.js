//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//공통선언
var useApi = '../api/apiSiteInfo.php'
var page = '';

//리스트액션
function listAct(){
	var writes = '';
	writes += '<ul>'; 
	$.get(useApi,function(data){
		$.each(data, function(key, value){
			var keys = ''
			if(key == 'agreement'){
				keys = '이용약관';
			}else if(key == 'privacy'){
				keys = '개인정보이용방침';
			}else if(key == 'caution'){
				keys = '환급교육 유의사항';
			}else if (key == 'midCopy'){
				keys = '중간평가 유의사항';
			}else if (key == 'testCopy'){
				keys = '최종평가 유의사항';
			}else if (key == 'reportCopy'){
				keys = '과제제출 유의사항';
			}else if (key == 'acs'){
				keys = 'ACS유의사항'
			}
			writes += '<li>';
			writes += '<h1>'+keys
			writes += '<br /><br /><button type="button" onClick="editorOpen(\''+key+'\')">내용편집</button>';
			writes += '</h1>';
			writes += '<div class="textInputs">';
			writes += '<div id="example" class="examView" onClick="editorOpen(\''+key+'\')">'+value+'</div>';
			writes += '</div>'
			writes += '</li>';
		})
		writes += '</ul>'; 
		$('#contentsArea').removeAttr('class')
		$('#contentsArea').addClass('BBSWrite')
		$('#contentsArea').html(writes);
	})
}

function editorOpen (writeArea){
	var modalWrite = '';
	var keys = '';
	if(writeArea == 'agreement'){
		keys = '이용약관';
	}else if(writeArea == 'privacy'){
		keys = '개인정보이용방침';
	}else if(writeArea == 'caution'){
		keys = '환급교육 유의사항';
	}else if (writeArea == 'midCopy'){
		keys = '중간평가 유의사항';
	}else if (writeArea == 'testCopy'){
		keys = '최종평가 유의사항';
	}else if (writeArea == 'reportCopy'){
		keys = '과제제출 유의사항';
	}else if (writeArea == 'acs'){
		keys = 'ACS유의사항'
	}
	$.get(useApi,function(data){
		var writeText = eval('data.'+writeArea);
		//alert(data.agreement)
		modalWrite += '<div id="modal">';
		modalWrite += '<div class="modalEditor">';
		modalWrite += '<h1><strong>내용편집</strong><button type="button" onClick="modalClose()"><img src="../../images/admin/btn_close.png" alt="닫기" /></button></h1>';
		modalWrite += '<div>'
		modalWrite += '<form class="writeform" method="post" action="'+useApi+'" enctype="multipart/form-data">';
		modalWrite += '<form action="sample.php" method="post">';
		modalWrite += '<div>'
		modalWrite += '<h1>'+keys+'</h1>';	
		modalWrite += '<textarea name="'+writeArea+'" id="ir1" rows="10" cols="100" style="width:827px; height:460px; display:none;">'+writeText+'</textarea>';
		modalWrite += '</form>';
		modalWrite += '<div class="btnArea"><button type="button" onClick="submitInfo(this)">정보등록</button></div>';
		modalWrite += '</form>';
		modalWrite += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'
		modalWrite += '</div>'
		modalWrite += '</div>';
		//--모달테두리
		modalWrite += '</div>';
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

function sendData(){
	$('.writeform').ajaxForm({
		dataType:'text',
		beforeSubmit: function (data,form,option) {
			return true;
		},
		success: function(data,status){
			alert("작성이 완료되었습니다.");
			modalClose();
			listAct();
		},
		error: function(){
			//에러발생을 위한 code페이지
			alert("작성중 문제가 생겼습니다..");
		}
	});
	$('.writeform').submit();	
}