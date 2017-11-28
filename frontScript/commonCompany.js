//	게시판 리스트페이지 스크립트
//
//	1.게시판 소팅관련된 부분은 Success프로세스에 표시한다.
//	2.게시판 퍼포먼스관련 부분은 done프로세스에서 표시한다.
//	3.기타액션은 always에서 표시한다.
//	4,2중 always는 사용을 자제하도록 한다.
//
//	작성자 : 서영기

//공통선언
var useApi = '../api/apiCompany.php'
var memberApi = '../api/apiMember.php'
var zipCode = '';
var address01 = '';
var address02 = '';

var optWrite = new Array();
makeOption('companyScale','','')
makeOption('enabled','','')
makeOption('phone01','','')
makeOption('mobile01','','')
makeOption('email02','','')
makeOption('bankName','','')

//리스트액션
function pageAct(){
	var writes = ''
	$.get(useApi,{'companyID':loginCompanyID },function(data){
		$.each(data.company, function(){
			writes += '<form class="writeform" method="post" action="'+useApi+'" enctype="multipart/form-data">';
			//seq값 선언
			writes += '<input type="hidden" name="seq" value="'+this.seq+'" />';
			//입력영역 시작
			writes += '<ul>';

			//회사규모, 사이버교육센터 사용여부
			writes += '<li>';
			writes += '<h1>회사규모</h1>';
			writes += '<select name="companyScale" class="'+this.companyScale+'">'+optWrite['companyScale']+'</select>';
			writes += '</li>'
			writes += '<li>';
			writes += '<h1>사이버 교육센터</h1>';
			writes += '<select name="studyEnabled" class="'+this.studyEnabled+'">'+optWrite['enabled']+'</select>';
			writes += '</li>';

			//회사명
			writes += '<li>';
			writes += '<h1>회사명</h1>';
			writes += '<input type="text" name="companyName" class="name" value="'+this.companyName+'" />';
			writes += '</li>'
			//writes += '<li>';
			//writes += '<h1>도장(직인)</h1>';
			//writes += '<image src="/attach/studyCenter/stamp.jpg" style="width:80px;">&nbsp;|&nbsp;<input type="file" name="stamp" class="name" /> 반드시 jpg로 등록';
			//writes += '</li>';

			//사업자번호
			writes += '<li><h1>사업자번호</h1>';
			writes += '<input type="tel" name="companyCode" class="name" value="'+this.companyCode+'" />';
			writes += '</li>';

			//HRD번호
			writes += '<li><h1>HRD번호</h1>';
			writes += '<input type="tel" name="hrdCode" class="mail" value="'+this.hrdCode+'" />';
			writes += '</li>';

			//사업주 아이디
			writes += '<li><h1>사업주 아이디</h1>'+this.companyID+'</li>';

			//대표자명
			writes += '<li><h1>대표자명</h1>';
			writes += '<input type="text" name="ceoName" class="name" value="'+this.ceoName+'" />';
			writes += '</li>';

			//대표 전화번호
			writes += '<li><h1>대표 전화번호</h1>';
			writes += '<select name="phone01" class="'+this.phone01+'">'+optWrite['phone01']+'</select>&nbsp;-&nbsp;';
			writes += '<input type="tel" name="phone02" class="tel" maxlength="4" value="'+this.phone02+'" />&nbsp;-&nbsp;';
			writes += '<input type="tel" name="phone03" class="tel" maxlength="4" value="'+this.phone03+'" />';
			writes += '</li>';

			//대표자명
			writes += '<li><h1>대표 팩스번호</h1>';
			writes += '<select name="fax01" class="'+this.fax01+'">'+optWrite['phone01']+'</select>&nbsp;-&nbsp;';
			writes += '<input type="tel" name="fax02" class="tel" maxlength="4" value="'+this.fax02+'" />&nbsp;-&nbsp;';
			writes += '<input type="tel" name="fax03" class="tel" maxlength="4" value="'+this.fax03+'" />';
			writes += '</li>';

			//주소입력
			writes += '<li><h1>주소</h1><div class="address">';
			writes += '<input name="zipCode" class="name" type="tel" maxlength="5" id="zipCodeArea"  value="'+this.zipCode+'" readonly="readonly" />&nbsp;<button type="button" class="findZipCode">우편번호 찾기</button><br />';
			writes += '<input name="address01" class="subject" type="text" id="address01Area" value="'+this.address01+'" /><br />';
			writes += '<input name="address02" class="subject" type="text" id="address02Area" value="'+this.address02+'" />';
			writes += '</div></li>';

			//계좌번호
			writes += '<li><h1>계좌번호</h1>';
			writes += '<select name="bank" class="'+this.bank+'">'+optWrite['bankName']+'</select>&nbsp;';
			writes += '<input type="tel" name="bankNum" class="email" value="'+this.bankNum+'" />';
			writes += '</li>';

			//업태/업종
			writes += '<li><h1>업태/업종</h1>';
			writes += '<input type="tel" name="kind" class="name" value="'+this.kind+'" />&nbsp;';
			writes += '<input type="tel" name="part" class="name" value="'+this.part+'" />';
			writes += '</li>';

			//홈페이지 주소
			writes += '<li><h1>홈페이지 주소</h1>';
			writes += '<input type="tel" name="siteURL" class="subject" value="'+this.siteURL+'" />&nbsp;';
			writes += '</li>';

			if(this.manager.name == null) {
				var managerName = '';
			} else {
				var managerName = this.manager.name;
			}
			//담당자명
			writes += '<li><h1>담당자명</h1>';
			writes += '<div id="managerID" class="address"><input name="userName" type="text" value="'+managerName+'" /> <button type="button" onClick="searchSelect(\'managerID\',\''+memberApi+'\')">검색</button></div>';
			//div의 아이디값과 새로 등록될 select 네임의 동일화
			writes += '</li>';

			//사이트 메인 Email
			writes += '<li><h1>사이트 메인 Email</h1>';
			writes += '<input class="name" name="elecEmail01" type="text" maxlength="20" value="'+this.elecEmail01+'" />&nbsp;@&nbsp;';
			writes += '<select name="elecEmail02Chk" class="'+this.elecEmail02+'" id="email02">'+optWrite['email02']+'</select>&nbsp;';
			writes += '<input type="text" name="elecEmail02" id="emails" class="name" value="'+this.elecEmail02+'" />';
			writes += '</li>';

			if(this.marketer.name == null) {
				var marketerName = '';
			} else {
				var marketerName = this.marketer.name;
			}
			//개인정보책임자
			writes += '<li><h1>개인정보책임자</h1>';
			writes += '<div id="marketerID" class="address"><input name="userName" type="text" value="'+marketerName+'" /> <button type="button" onClick="searchSelect(\'marketerID\',\''+memberApi+'\')">검색</button></div>';
			//div의 아이디값과 새로 등록될 select 네임의 동일화
			writes += '</li>';

			//통신판매업번호
			writes += '<li><h1>통신판매업번호</h1>';
			writes += '<input type="text" name="memo" class="subject" value="'+this.memo+'" />&nbsp;';
			writes += '</li>';

			//교육자료 업로드
			writes += '<li><h1>기업회원 교육신청<br />양식 업로드</h1>';
			writes += '<input type="file" name="requestForm" class="subject" /> * 반드시 zip 압축하여 업로드하시기 바랍니다.(파일명은 무관)';
			writes += '</li>';

			writes += '</ul>';

			//버튼영역
			writes += '<div class="btnArea">';
			writes += '<button type="button" onClick="multipartSendData(\'writeform\')">수정하기</button>'
			writes += '</div>';
			writes += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'

			writes += '</form>';
		})
		$('#contentsArea').removeAttr('class')
		$('#contentsArea').addClass('BBSWrite')
		$('#contentsArea').html(writes);
		findOpt();
		$('#zipCodeArea, .findZipCode').click(function(){zipCodeFind()})
		emailSelect();
	})
}

//공통화를 위한 페이지 막음
//작성완료
function multipartSendData(){
	$('form.writeform').ajaxForm({
		dataType:'text',
		beforeSubmit: function (data,form,option) {
			return true;
		},
		success: function(data,status){
			alert("작성이 완료되었습니다.");
			pageAct();
		},
		error: function(){
			//에러발생을 위한 code페이지
			alert("작성중 문제가 생겼습니다..");
		}
	});
	$('form.writeform').submit();

};

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