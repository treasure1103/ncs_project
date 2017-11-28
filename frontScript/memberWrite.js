//	게시판 뷰페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기


//게시판 보기 스크립트 시작
function writeAct(writeSeq){
	seq = writeSeq ? writeSeq : ''; //파일관련 스크립트 사용
	
	//상단메뉴
	$('.searchArea').remove();
	
	//출력변수 지정
	var userID = '';
	var userName = '';
	var userLevel = '';
	var birth = '';
	var sex = '';
	var phone01 = '';
	var phone02 = '';
	var phone03 = '';
	var mobile01 = '';
	var mobile02 = '';
	var mobile03 = '';
	var email01 = '';
	var email02 = '';
	var zipCode = '';
	var address01 = '';
	var address02 = '';
	var smsReceive = '';
	var emailReceive = '';
	var oldGrade = '';
	var oldLevel = '';
	var changeDate= false;
	var companyName = '';
	var department = '';
	var commission = '';
	var bank = '';
	var bankNum = '';	
		
	if(seq != ''){
		var writeAjax = $.get(useApi,{'seq':seq,'userLevel':userLevel},function(data){
			$.each(data.member, function(){
				userID = this.userID;
				userName = this.userName;
				userLevel = this.userLevel.userLevel;
				birth = this.birth;
				sex = this.sex;
				phone01 = this.phone01;
				phone02 = this.phone02;
				phone03 = this.phone03;
				mobile01 = this.mobile01;
				mobile02 = this.mobile02;
				mobile03 = this.mobile03;
				email01 = this.email01;
				email02 = this.email02;
				zipCode = this.zipCode;
				address01 = this.address01;
				address02 = this.address02;
				smsReceive = this.smsReceive;
				emailReceive = this.emailReceive;
				changeDate = this.userLevel.changeDate;
				oldGrade = this.userLevel.oldGrade;
				userGrade = this.userLevel.userGrade;
				companyName = this.company.companyName;
				department = this.company.department;
				commission = this.commission;
				bank = this.bank;
				bankNum = this.bankNum;	
				marketer = '';
			})	
			writePrint()
		})
	}else{
		writePrint()
	}
	
	//게시판 생성
	function writePrint(){
		var writes ='';
		writes += '<form class="writeform">';
		
		//seq값 선언
		writes += '<input type="hidden" name="seq" value="'+seq+'" />';
		
		//입력영역 시작
		writes += '<ul>';
		
		//아이디 입력
		writes += '<li class="mustCheck">'
		writes += '<h1>아이디</h1>';
		if(seq == ''){
			writes += '<input type="text" name="userID" class="name" style="ime-mode:disabled" />&nbsp;<button type="button" onclick="idUseCheck(\''+useApi+'\',\'userID\')">중복확인</button><input type="checkbox" name="idUseOk" value="Y" />';
		}else{
			writes += '<input type="hidden" name="userID" value="'+userID+'"><input type="text" name="userIDChange" class="name" style="ime-mode:disabled" value="'+userID+'"/>&nbsp;<button type="button" onclick="idUseCheck(\''+useApi+'\',\'userIDChange\')">중복확인</button><input type="checkbox" name="idUseOk" value="Y" />';
			if(loginUserLevel < '5') {
				writes += '&nbsp;&nbsp;&nbsp;<input type="checkbox" name="userDelete" id="userDelete" value="Y" /><label for="userDelete">회원탈퇴처리</label>';
			}
		}
		writes += '</li>';
		
		//이름입력
		writes += '<li class="mustCheck">';
		writes += '<div class="halfDiv mustCheck">';
		writes += '<h1>이름</h1><input type="text" name="userName" class="name" maxlength="20" value="'+userName+'" />';
		if(changeDate != false){
			writes += '&nbsp;&nbsp;&nbsp;&nbsp;( 회원레벨은 '+changeDate.substr(0,10)+'에&nbsp;'+oldGrade+'에서&nbsp;'+userGrade+'로 변경되었습니다.)';
		}
		writes += '</div>';

		if(seq == '') {
			writes += '<div class="halfDiv">';
			writes += '</div>';
			writes += '</li>';
		} else {
			writes += '<div class="halfDiv">';
			writes += '<h1>비밀번호 초기화</h1><button type="button" onClick="pwdReset(\''+seq+'\',\''+birth.substr(2,4)+'\')">초기화</button> ※ 생일 4자리로 변경됩니다.';
			writes += '</div>';
			writes += '</li>';
		}
		
		//생년월일,성별
		writes += '<li class="mustCheck">';
		writes += '<div class="halfDiv mustCheck">';		
		writes += '<h1>생년월일</h1><input type="text" name="birth" class="name" maxlength="6" value="'+birth+'" />';
		writes += '</div>';
		writes += '<div class="halfDiv">';		
		writes += '<h1>성별</h1><select name="sex" class="'+sex+'">'+optWrite['sexType']+'</select>';
		writes += '</div>';
		writes += '</li>';

		//비밀번호입력

		writes += '<li ><h1>비밀번호</h1><input type="password" name="pwdCheck"  class="name" maxlength="20" /></li>';
		writes += '<li ><h1>비밀번호확인</h1><input type="password" name="pwd" class="name" maxlength="20" /></li>';

		
		//회원등급 입력
		if(loginUserLevel < 5) {
			writes += '<li class="mustCheck"><h1>회원등급</h1><select name="userLevel" class="'+userLevel+'">'+optWrite['user']+optWrite['admin']+'</select>';
			if(seq != ''){
				writes += '&nbsp;&nbsp;&nbsp;&nbsp;<input type="checkbox" class="'+userLevel+'" id="changeGradeCheck"><label for="changeGradeCheck">회원등급 변경시 꼭 체크하셔야 합니다.</label>';
			}
			writes += '</li>';
		}
		
		//휴대폰입력
		writes += '<li class="mustCheck"><h1>휴대전화</h1><select name="mobile01" class="'+mobile01+'">'+optWrite['mobile01']+'</select>&nbsp;-&nbsp;<input class="tel" type="tel" name="mobile02" maxlength="4" value="'+mobile02+'" style="ime-mode:disabled;" />&nbsp;-&nbsp;<input class="tel" name="mobile03" type="tel" maxlength="4" value="'+mobile03+'" /></li>';
		
		//연락처입력
		if(phone02 == null) {
			phone02 = '';
		}
		if(phone03 == null) {
			phone03 = '';
		}
		writes += '<li><h1>연락처</h1><select name="phone01" class="'+phone01+'" >'+optWrite['phone01']+'</select>&nbsp;-&nbsp;<input class="tel" type="tel" name="phone02" maxlength="4" value="'+phone02+'" style="ime-mode:disabled;" />&nbsp;-&nbsp;<input class="tel" name="phone03" type="tel" maxlength="4" value="'+phone03+'" style="ime-mode:disabled;" /></li>';
		
		//이메일 입력
		writes += '<li><h1>Email</h1><input class="name" name="email01" type="text" maxlength="20" value="'+email01+'" />&nbsp;@&nbsp;<select name="email02Chk" class="'+email02+'" id="email02">'+optWrite['email02']+'</select>&nbsp;<input type="text" name="email02" id="emails" class="name" value="'+email02+'" /></li>';
		
		//정보수신여부
		writes += '<li><h1>정보수신</h1>';
		if(emailReceive == 'N'){
			writes += '<input type="checkbox" id="sendEmail" name="emailReceive" value="Y" /><label for="sendEmail">Email 정보</label>';
		}else{
			writes += '<input type="checkbox" id="sendEmail" name="emailReceive" value="Y" checked="checked" /><label for="sendEmail">Email 정보</label>';
		}
		if(smsReceive == 'N'){
			writes += '<input type="checkbox" id="sendSMS" name="smsReceive" value="Y" /><label for="sendSMS">SMS 정보</label>';
		}else{
			writes += '<input type="checkbox" id="sendSMS" name="smsReceive" value="Y" checked="checked" /><label for="sendSMS">SMS 정보</label>';
		}
		writes += '</li>'

		//주소입력
		if(zipCode == null) {
			zipCode = '';
		}
		if(address01 == null) {
			address01 = '';
		}
		if(address02 == null) {
			address02 = '';
		}		
		writes += '<li><h1>주소</h1><div class="address">';
		writes += '<input name="zipCode" class="name" type="tel" maxlength="5" id="zipCodeArea"  value="'+zipCode+'" readonly="readonly" />&nbsp;<button type="button" class="findZipCode">우편번호 찾기</button><br />';
		writes += '<input name="address01" class="subject" type="text" id="address01Area" value="'+address01+'" /><br />';
		writes += '<input name="address02" class="subject" type="text" id="address02Area" value="'+address02+'" />';
		writes += '</div></li>';

		if(loginUserLevel < '5') {
			//기업소속명
			writes += '<li><h1>기업/소속명</h1>';
			writes += '<div id="companyCode" class="address"><input name="companyName" type="text" value="'+companyName+'" /> <button type="button" onClick="searchSelect(\'companyCode\',\''+companyApi+'\')">검색</button></div>';
			writes += '</li>';
		}
		
		if(loginUserLevel != '7') {
			if(department == null) {
				department = '';
			}
			//부서명 영업수수료
			writes += '<li>';
			writes += '<div class="halfDiv">';
			writes += '<h1>부서명</h1>';
			writes += '<input type="text" name="department" class="name" value="'+department+'">';
			writes += '</div>';
			writes += '<div class="halfDiv">';
/*
			if(userLevel < 5) {
				writes += '<h1>영업수수료</h1>';
				writes += '<input type="tel" name="commission" class="tel" value="'+commission+'"> %';
			}
*/
			writes += '</div>';
			writes += '</li>';
		}

		//계좌번호
		if(bankNum == null) {
			bankNum = '';
		}
		writes += '<li><h1>계좌번호</h1><select name="bank" class="'+bank+'" >'+optWrite['bankName']+'</select>&nbsp;<input type="tel" name="bankNum" class="name" value="'+bankNum+'" style="ime-mode:disabled;" /></li>';

		if(userLevel == 6) { //영업팀장 지정
			writes += '<li><h1>영업팀장 지정</h1>';
			writes += '<div id="marketer" class="address"><input name="marketerName" type="text" value="'+marketer+'" /> <button type="button" onClick="searchSelect(\'marketer\',\''+useApi+'\')">검색</button></div>';
			writes += '</li>';
		}

		writes += '</ul>';
		writes += '</form>';
		writes += '<div class="btnArea">';
		writes += '<button type="button" onClick="checkMemberForm()">';
		if(seq != ''){
			writes += '수정하기'
		}else{
			writes += '등록하기'
		}
		writes += '</button>';
		writes += '<button type="button" onClick="listAct('+page+')">목록보기</button>';
		writes += '</div>';
		$('#contentsArea').removeAttr('class')
		$('#contentsArea').addClass('BBSWrite')
		$('#contentsArea').html(writes);
				
		findOpt();//selct 선택자 찾기
		emailSelect();//이메일 select 호출 사용시 같이 호출	
		$('#zipCodeArea, .findZipCode').click(function(){zipCodeFind()})
		var	mustInput = '&nbsp;&nbsp;<strong class="price">(*)</strong>';
		$('.mustCheck > h1').append(mustInput)//필수요소 사용
		$('input[name="userID"]').keydown(function(){keyCheck('numbEng',this)})
		$('input[name="phone02"],input[name="phone03"],input[name="mobile02"],input[name="mobile03"]').keyup(function(){keyCheck('numb',this)})
	}

}
function viewAct(seq){
	writeAct(seq)
}

//입력폼 체크
function checkMemberForm(){
	$('.mustCheck > strong.price').remove();
	var checkFalse = 0;
	$('.mustCheck input[type="tel"], .mustCheck input[type="text"], .mustCheck input[type="password"]').each(function(){
		if($(this).val() == ''){
			checkFalse ++;
		}
	});
	if(seq==''){
		if($('input[name="userID"]').val() == ''){
			$('input[name="idUseOk"]').after('<strong class="price">아이디를 입력해주세요</strong>')
			checkFalse ++;
		}else if($('input[name="idUseOk"]').prop('checked') == false){
			$('input[name="idUseOk"]').after('<strong class="price">아이디 중복체크를 해주세요</strong>')
			checkFalse ++;
		}
	}
	if(seq != ''){
		var chageUserLevel = $('select[name="userLevel"]').val();
		var oriUserLevel = $('#changeGradeCheck').attr('class');
		if(chageUserLevel != oriUserLevel && $('#changeGradeCheck').prop('checked') == false){
			$('select[name="userLevel"]').after('<strong class="price">변경체크를 해주셔야합니다.</strong>')
			checkFalse ++;
		}
	}
	if($('input[name="userName"]').val() == ''){
		$('input[name="userName"]').after('<strong class="price">이름을 입력해주세요.</strong>')
		checkFalse ++;
	}	
	if($('input[name="mobile02"]').val() == '' || $('input[name="mobile03"]').val() == '' ){
		$('input[name="mobile03"]').after('<strong class="price">휴대폰 번호를 입력해주세요.</strong>')
		checkFalse ++;
	}

	if($('input[name="birth"]').val() == ''){
		$('input[name="birth"]').after('<strong class="price">생년월일을 입력해주세요.</strong>')
		checkFalse ++;
	}

	/*
	if($('input[name="email01"]').val() == '' || $('input[name="email02"]').val() == '' ){
		$('input[name="email02"]').after('<strong class="price">이메일을 모두 입력해주세요.</strong>')
		checkFalse ++;
	}
	if($('input[name="pwdCheck"]').val() == ''){
		$('input[name="pwdCheck"]').after('<strong class="price">비밀번호를 입력해주세요</strong>')
		checkFalse ++;
	}else if($('input[name="pwd"]').val() == ''){
		$('input[name="pwd"]').after('<strong class="price">비밀번호를 확인해주세요</strong>')
		checkFalse ++;
	*/
	if($('input[name="pwdCheck"]').val() != $('input[name="pwd"]').val()){
		$('input[name="pwd"]').after('<strong class="price">입력한 비밀번호가 서로 일치하지 않습니다.</strong>')
		checkFalse ++;
	}
	if(checkFalse == 0){
		if($('input[name="userDelete"]').prop('checked') == true){
			sendData(useApi,'writeform','delete')
		}else{
			sendData(useApi,'writeform','new')
		}
	}
}

//우편번호 API
function zipCodeFind(){
	new daum.Postcode({
		oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 각 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var fullAddr = ''; // 최종 주소 변수
			var extraAddr = ''; // 조합형 주소 변수

			// 사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				fullAddr = data.roadAddress;

			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				fullAddr = data.jibunAddress;
			}

			// 사용자가 선택한 주소가 도로명 타입일때 조합한다.
			if(data.userSelectedType === 'R'){
				//법정동명이 있을 경우 추가한다.
				if(data.bname !== ''){
					extraAddr += data.bname;
				}
				// 건물명이 있을 경우 추가한다.
				if(data.buildingName !== ''){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 조합형주소의 유무에 따라 양쪽에 괄호를 추가하여 최종 주소를 만든다.
				fullAddr += (extraAddr !== '' ? ' ('+ extraAddr +')' : '');
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			document.getElementById('zipCodeArea').value = data.zonecode; //5자리 새우편번호 사용
			document.getElementById('address01Area').value = fullAddr;

			// 커서를 상세주소 필드로 이동한다.
			document.getElementById('address02Area').focus();
		}		
	}).open({
		popupName: 'postcodePopup', //팝업 이름을 설정(영문,한글,숫자 모두 가능, 영문 추천)
	});
}

function pwdReset(seqR,birthR){
	$.ajax({
		url: useApi,
		type:'POST',
		data:'pwdReset=Y&seq='+seqR+'&birth='+birthR,
		dataType:'text',
		success:function(data){
			alert('비밀번호를 초기화 하였습니다.');
			ajaxAct();
		},
		fail:function(){
			alert('오류가 발생하였습니다.')
		}
	})
}