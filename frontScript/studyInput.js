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
			writes += '<form class="fileUploadform" method="post" action="studyUpload.php" enctype="multipart/form-data">';
			writes += '<ul>';
			writes += '<li>';
			writes += '<h1>등록샘플</h1>';
			writes += '<button type="button" onclick="location.href=\'../attach/docs/수강등록.xlsx\'">양식 내려받기</button>&nbsp;';
			writes += '<button type="button" onclick="location.href=\'../attach/docs/수강등록(샘플).xlsx\'">샘플보기</button>';
			writes += '&nbsp;<strong class="price">(첨부파일다운로드 확인후 작성해서 올려 주세요.)</strong>';
			writes += '</li>';
			writes += '<li>';
			writes += '<h1>파일등록</h1>';
			writes += '<input type="file" name="uploadFile" />&nbsp;<button type="submit" onClick="loadingAct();">파일업로드</button>';
			writes += '</li>';
			writes += '<li>';
			writes += '<h1>임시등록 데이터</h1>';
			writes += '<span style="width:78%;" id="tempCheck"><span>';
			writes += '</li>';
			writes += '</ul>';
			writes += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>';
			writes += '</form>';

			//개별등록,수정
			writes += '<h2>개별 등록</h2>'
			writes += '<form class="writeform" method="post">';
			writes += '<ul>';
			//기간설정
			writes += '<li>';
			writes += '<div class="halfDiv">';
			writes += '<h1>수강기간</h1>';
			writes += '<div class="datePicker"><input type="text" name="lectureStart" class="cal" value="'+startDate+'" readonly="readonly" /></div>&nbsp;~&nbsp;';
			writes += '<div class="datePicker"><input type="text" name="lectureEnd" class="cal"  value="'+endDate+'" readonly="readonly" /></div>&nbsp;';
			writes += '</div>';
			//복습기간
			writes += '<div class="halfDiv">';
			writes += '<h1>복습기간</h1>';
			writes += '종료일로부터 <input type="text" style="width:30px;" name="period" value="2"> 개월';
			writes += '</div>';
			writes += '</li>';
			//과정선택
			writes += '<li id="contentsCode">';
			writes += '<h1>과정선택</h1>';
			writes += '<input name="searchName" type="text" /> <button type="button" onClick="searchSelect(\'contentsCode\',\''+chainsearchApi+'\',\'contents\')">검색</button>';
			writes += '</li>';
			//수강생명
			writes += '<li id="userID">';
			writes += '<h1>수강생</h1>';
			writes += '<input name="userName" type="text" /> <button type="button" onClick="searchSelect(\'userID\',\''+memberApi+'\')">검색</button>';
			writes += '</li>';
			//첨삭강사
			writes += '<li id="tutorID">';
			writes += '<h1>첨삭강사</h1>';
			writes += '<input name="tutorName" type="text" /> <button type="button" onClick="searchSelect(\'tutorID\',\''+memberApi+'\',7)">검색</button>';
			writes += '</li>';	
			//서비스구분
			writes += '<li>';
			writes += '<h1>개설용도</h1>';
			writes += '<select name="serviceType">';
			writes += '<option value="1">사업주지원(환급)개설</option>';
			writes += '<option value="3">일반(비환급)개설</option>';
			writes += '<option value="9">테스트용(또는 심사용)</option>';
			writes += '</select>&nbsp;';
			writes += '</li>';
			//진도율
			writes += '<li>';
			writes += '<h1>진도율</h1>';
			writes += '<select name="progress">';
			writes += '<option value="0">0%</option>';
			writes += '<option value="100">100%</option>';
			writes += '</select>&nbsp;';
			writes += '</li>';
			/*
			//수강구분(서비스타입)
			writes += '<li>';
			writes += '<h1>수강구분</h1>';
			writes += '<select name="serviceType">';
			writes += '<option value="1">사업주지원</option>';
			writes += '<option value="2">능력개발</option>';
			writes += '<option value="3">일반(비환급)</option>';
			writes += '</select>&nbsp;';
			writes += '</li>';
			*/
			writes += '</ul>';
			writes += '<div class="btnArea">';
			writes += '<button type="button" onClick="writeStudy()">등록하기</button>';
			writes += '<button type="button" onClick="resetInput()">초기화</button>';
			writes += '</div>';
			writes += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'
			writes += '</form>';

		$('#contentsArea').removeAttr('class');
		$('#contentsArea').addClass('BBSWrite');
		$('#contentsArea').html(writes);
		pickerAct();//데이트피커 사용
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

function ajaxAct(listPage){
	listPage = listPage ? listPage : page ;
	page = listPage;
	var listAjax = $.get(useTempApi,'page='+page+'&list='+listCount,function(data){
		totalCount = data.totalCount;
		var lists = '';
		var j = totalCount;
		if(page != 1){
			j = (page-1) * listCount
		}
			if (totalCount != 0){
				j = totalCount;
				$.each(data.study,  function(){
					lists += '<tr>';
					lists += '<td>'+j+'</td>';
					lists += '<td>'+this.userID+'<br /><input type="text" name="userName" value="'+this.userName+'"></td>';
					lists += '<td><input type="text" name="birth" value="'+this.birth+'"><br /><input type="text" name="sex" value="'+this.sex+'"></td>';
					lists += '<td><input type="tel" name="mobile01" class="year" value="'+this.mobile01+'">-<input type="tel" name="mobile02" class="year" value="'+this.mobile02+'">-<input type="tel" name="mobile03" class="year" value="'+this.mobile03+'">';
					lists += '<br><input type="text" name="email01" class="name" value="'+this.email01+'">@<input type="text" name="email02" class="name" value="'+this.email02+'"></td>';
					lists += '<td><input type="text" name="companyCode" value="'+this.companyCode+'"><br />'+this.companyName+'</td>';
					lists += '<td><input type="text" name="lectureStart" value="'+this.lectureStart+'"><br /><input type="text" name="lectureEnd" value="'+this.lectureEnd+'"></td>';
					lists += '<td><input type="text" name="contentsCode" value="'+this.contentsCode+'"><br />'+this.contentsName+'</td>';
					lists += '<td><input type="text" name="tutor" value="'+this.tutor+'"><br />'+this.tutorName+'</td>';
					lists += '<td><input type="text" name="price" value="'+this.price+'"><br /><input type="text" name="rPrice" value="'+this.rPrice+'"></td>';
					lists += '<td><input type="text" name="serviceType" value="'+this.serviceType+'"><br />'+this.inputDate+'</td>';
					lists += '<td>'+this.lectureEA+'</td>';
					lists += '</tr>';
					//항목 옆에 유효성 체크 해줄것
					j--;
				})
			}
		$('.BBSWrite tbody').html(lists);
	})
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

function tempRegister(){
	window.open("tempRegister.php","임시등록","width=1300,height=800,top=0,left=0,scrollbar=yes,location=yes,menubar=no,status=no,titlebar=no,toolbar=no","esangedu")
}