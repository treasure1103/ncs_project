//	게시판 뷰페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

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
		
			//파일등록
			writes += '<h2>파일로 업로드하기</h2>'
			writes += '<form class="fileUploadform" method="post" action="companyUpload.php" enctype="multipart/form-data">';
			writes += '<ul>';
			writes += '<li>';
			writes += '<h1>등록샘플</h1>';
			writes += '<button type="button" onclick="location.href=\'../attach/docs/company_upload_sample.xlsx\'">양식 및 샘플 내려받기</button>&nbsp;';
			writes += '&nbsp;<strong class="price">(첨부파일다운로드 확인후 작성해서 올려 주세요.)</strong>';
			writes += '</li>';
			writes += '<li>';
			writes += '<h1>파일등록</h1>';
			writes += '<input type="file" name="uploadFile" />&nbsp;<button type="submit" onClick="loadingAct();">파일업로드</button>';
			writes += '</li>';
			writes += '<li>';
			writes += '<h1>주의사항</h1>';
			writes += '등록 시 교육담당자 ID도 같이 등록되며 ID : 사업자번호, PW: 1111 으로 생성됩니다.';
			writes += '</li>';
			/*writes += '<li>';
			writes += '<h1>임시등록 데이터</h1>';
			writes += '<span style="width:78%;" id="tempCheck"><span>';
			writes += '</li>';*/
			writes += '</ul>';
			writes += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>';
			writes += '</ul>';			
			writes += '</form>';

		$('#contentsArea').removeAttr('class');
		$('#contentsArea').addClass('BBSWrite');
		$('#contentsArea').html(writes);
		//pickerAct();//데이트피커 사용
		tempCheck();
		//ajaxAct();
	}
}


function tempCheck(){
	var listAjax = $.get(useTempApi,function(data){
		if(data.totalCount == 0) {
			lists = '파일을 업로드 하세요.';
		} else {
			lists = '<span onClick="tempRegister()" style="cursor:pointer;">처리되지 않은 '+data.totalCount+'건의 데이터가 있습니다. (확인하기)</span>';
		}
		$('#tempCheck').html(lists);
	})
}


function tempRegister(){
	window.open("tempCompany.php","임시등록","width=1300,height=800,top=0,left=0,scrollbar=yes,location=yes,menubar=no,status=no,titlebar=no,toolbar=no","esangedu")
}