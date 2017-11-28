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
	writeSeq = writeSeq ? writeSeq : ''; //파일관련 스크립트 사용
	seq = writeSeq;
	//상단메뉴
	$('.searchArea').remove();
	
	//출력변수 지정
	var companyScale = '';
	var studyEnabled = '';
	var companyName = '';
	var companyCode = '';
	var hrdCode = '';
	var companyID = '';
	var ceoName = '';
	var phone01 = '';
	var phone02 = '';
	var phone03 = '';
	var fax01 = '';
	var fax02 = '';
	var fax03 = '';
	var zipCode = '';
	var address01 = '';
	var address02 = '';
	var bank = '';
	var bankNum = '';
	var kind = '';
	var part = '';
	var siteURL = '';
	var cyberURL = '';
	var managerName = '';
	var elecEmail01 = '';
	var elecEmail02 = '';
	var marketerName = '';
	var memo = '';
		
	if(seq != ''){
		var writeAjax = $.get(useApi,{'seq':seq},function(data){
			$.each(data.company, function(){
				companyScale = this.companyScale;
				studyEnabled = this.studyEnabled;
				companyName = this.companyName;
				companyCode = this.companyCode;
				hrdCode = this.hrdCode;
				companyID = this.companyID;
				ceoName = this.ceoName;
				phone01 = this.phone01;
				phone02 = this.phone02;
				phone03 = this.phone03;
				fax01 = this.fax01;
				fax02 = this.fax02;
				fax03 = this.fax03;
				zipCode = this.zipCode;
				address01 = this.address01;
				address02 = this.address02;
				bank = this.bank;
				bankNum = this.bankNum;
				kind = this.kind;
				part = this.part;
				siteURL = this.siteURL;
				cyberURL = this.cyberURL;
				managerName = this.manager.name;
				elecEmail01 = this.elecEmail01;
				elecEmail02 = this.elecEmail02;
				marketerName = this.marketer.name;
				memo = this.memo;
			})
			writePrint()
		})
	}else{
		writePrint()
	}
	
	//게시판 생성
	function writePrint(){
		var writes = '';
		writes += '<form class="writeform" method="post" action="'+useApi+'" enctype="multipart/form-data">';
		//seq값 선언
		writes += '<input type="hidden" name="seq" value="'+seq+'" />';			
		//입력영역 시작
		writes += '<ul>';
		
		//회사규모, 사이버교육센터 사용여부
		writes += '<li>';
		writes += '<div class="halfDiv">';
		writes += '<h1>회사규모</h1>';
		writes += '<select name="companyScale" class="'+companyScale+'">'+optWrite['companyScale']+'</select>';
		writes += '</div>'
		writes += '<div class="halfDiv">';
		writes += '<h1>사이버 교육센터</h1>';
		writes += '<select name="studyEnabled" class="'+studyEnabled+'">'+optWrite['enabled']+'</select>';
		writes += '</div>'			
		writes += '</li>';
		
		//회사명
		writes += '<li class="mustCheck"><h1>회사명</h1>';
		writes += '<input type="text" name="companyName" class="name" value="'+companyName+'" />';
		writes += '</li>';
		
		//사업자번호
			writes += '<li class="mustCheck"><h1>사업자번호</h1>';
			writes += '<input type="tel" name="companyCode" class="name" maxlength="10" value="'+companyCode+'"/>&nbsp;<button type="button" onclick="companyCodeCheck(\''+useApi+'\',\'companyCode\')">중복확인</button><input type="checkbox" name="companyCodeOK" value="Y" />';
		writes += '</li>';
		
		//HRD번호
		writes += '<li><h1>HRD번호</h1>';
		writes += '<input type="tel" name="hrdCode" class="mail" value="'+hrdCode+'" />';
		writes += '</li>';
					
		//사업주 아이디
		if(seq==''){
			writes += '<li class="mustCheck"><h1>사업주 아이디</h1>';
			writes += '<input type="text" name="companyID" class="name">&nbsp;<button type="button" onclick="idUseCheck(\''+useApi+'\',\'companyID\')">중복확인</button><input type="checkbox" name="idUseOk" value="Y" />';
		}else{
			writes += '<li><h1>사업주 아이디</h1>';
			writes += companyID
		}
		writes += '</li>';
					
		//대표자명
		writes += '<li><h1>대표자명</h1>';
		writes += '<input type="text" name="ceoName" class="name" value="'+ceoName+'" />';
		writes += '</li>';
		
		//대표 전화번호
		writes += '<li><h1>대표 전화번호</h1>';
		writes += '<select name="phone01" class="'+phone01+'">'+optWrite['phone01']+'</select>&nbsp;-&nbsp;';
		writes += '<input type="tel" name="phone02" class="tel" maxlength="4" value="'+phone02+'" />&nbsp;-&nbsp;';
		writes += '<input type="tel" name="phone03" class="tel" maxlength="4" value="'+phone03+'" />';
		writes += '</li>';
		
		//대표자명
		writes += '<li><h1>대표 팩스번호</h1>';
		writes += '<select name="fax01" class="'+fax01+'">'+optWrite['phone01']+'</select>&nbsp;-&nbsp;';
		writes += '<input type="tel" name="fax02" class="tel" maxlength="4" value="'+fax02+'" />&nbsp;-&nbsp;';
		writes += '<input type="tel" name="fax03" class="tel" maxlength="4" value="'+fax03+'" />';
		writes += '</li>';
		
		//주소입력
		writes += '<li><h1>주소</h1><div class="address">';
		writes += '<input name="zipCode" class="name" type="tel" maxlength="5" id="zipCodeArea"  value="'+zipCode+'" readonly="readonly" />&nbsp;<button type="button" class="findZipCode">우편번호 찾기</button><br />';
		writes += '<input name="address01" class="subject" type="text" id="address01Area" value="'+address01+'" /><br />';
		writes += '<input name="address02" class="subject" type="text" id="address02Area" value="'+address02+'" />';
		writes += '</div></li>';
		
		//계좌번호
		writes += '<li><h1>계좌번호</h1>';
		writes += '<select name="bank" class="'+bank+'">'+optWrite['bankName']+'</select>&nbsp;';
		writes += '<input type="tel" name="bankNum" class="email" value="'+bankNum+'" />';
		writes += '</li>';
		
		//업태/업종
		writes += '<li><h1>업태/업종</h1>';
		writes += '<input type="tel" name="kind" class="name" value="'+kind+'" />&nbsp;';
		writes += '<input type="tel" name="part" class="name" value="'+part+'" />';
		writes += '</li>';
		
		//홈페이지 주소
		writes += '<li><h1>홈페이지 주소</h1>';
		writes += '<input type="tel" name="siteURL" class="subject" value="'+siteURL+'" />&nbsp;';
		writes += '</li>';

		//사이버교육센터 주소
		writes += '<li><h1>사이버교육센터 주소</h1>';
		writes += '<input type="tel" name="cyberURL" class="subject" value="'+cyberURL+'" />&nbsp;';
		writes += '</li>';

		//전자세금계산서 메일, 사이트 메인 메일
		if(companyID != loginCompanyID){
			writes += '<li><h1>전자세금계산서 Email</h1>';
		} else {
			writes += '<li><h1>사이트 메인 Email</h1>';
		}
		writes += '<input class="name" name="elecEmail01" type="text" maxlength="20" value="'+elecEmail01+'" />&nbsp;@&nbsp;';
		writes += '<select name="elecEmail02Chk" class="'+elecEmail02+'" id="email02">'+optWrite['email02']+'</select>&nbsp;';
		writes += '<input type="text" name="elecEmail02" id="emails" class="name" value="'+elecEmail02+'" />';
		writes += '</li>';		

		//담당자명
		writes += '<li><h1>교육담당자명</h1>';
		writes += '<div id="managerID" class="address"><input name="userName" type="text" value="'+managerName+'" /> <button type="button" onClick="searchSelect(\'managerID\',\''+memberApi+'\')">검색</button></div>';
		//div의 아이디값과 새로 등록될 select 네임의 동일화
		writes += '</li>';
	
		//개인정보책임자,영업담당자
		if(companyID != loginCompanyID){
			writes += '<li><h1>영업담당자</h1>';
		} else {
			writes += '<li><h1>개인정보책임자</h1>';
		}
		writes += '<div id="marketerID" class="address"><input name="userName" type="text" value="'+marketerName+'" /> <button type="button" onClick="searchSelect(\'marketerID\',\''+memberApi+'\')">검색</button></div>';
		//div의 아이디값과 새로 등록될 select 네임의 동일화
		writes += '</li>';
		
		//통신판매업번호
		writes += '<li><h1>메모</h1>';
		writes += '<input type="text" name="memo" class="subject" value="'+memo+'" />&nbsp;';
		writes += '</li>';

		if(seq==''){
			writes += '<li><h1>참고사항</h1>';
			writes += '지정된 교육담당자가 없으면 등록 시 교육담당자도 동시에 생성됩니다. (ID:사업자번호, PW:1111)';
			writes += '</li>';
		}

		writes += '</ul>';
		
		//버튼영역
		writes += '<div class="btnArea">';
		writes += '<button type="button" onClick="checkMemberForm()">';
		if(seq != ''){
			writes += '수정하기';
		}else{
			writes += '등록하기';
		}
		writes += '</button>'
		writes += '<button type="button" onClick="listAct('+page+')">목록보기</button>';
		writes += '</div>';
		writes += '<script type="text/javascript" src="../js/jquery.form.min.js"></script>'
		
		writes += '</form>';
		
		$('#contentsArea').removeAttr('class')
		$('#contentsArea').addClass('BBSWrite')
		$('#contentsArea').html(writes);
		findOpt();
		emailSelect();
		$('#zipCodeArea, .findZipCode').click(function(){zipCodeFind()})
		var	mustInput = '&nbsp;&nbsp;<strong class="price">(*)</strong>';
		$('.mustCheck > h1').append(mustInput)//필수요소 사용
		$('input[name="companyID"]').keydown(function(){keyCheck('numbEng',this)})
		$('input[type="tel"]').keyup(function(){keyCheck('numb',this)})
	}
}

//공통화를 위한 페이지 막음

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
		if($('input[name="companyID"]').val() == ''){
			$('input[name="idUseOk"]').after('&nbsp;&nbsp;<strong class="price">아이디를 입력해주세요</strong>')
			checkFalse ++;
		}else if($('input[name="idUseOk"]').prop('checked') == false){
			$('input[name="idUseOk"]').after('&nbsp;&nbsp;<strong class="price">아이디 중복체크를 해주세요</strong>')
			checkFalse ++;
		}
	}
	if($('input[name="companyName"]').val() == ''){
		$('input[name="companyName"]').after('&nbsp;&nbsp;<strong class="price">회사명을 입력해주세요.</strong>')
		checkFalse ++;
	}
	if(seq==''){
		if($('input[name="companyCode"]').val() == ''){
			$('input[name="companyCodeOK"]').after('&nbsp;&nbsp;<strong class="price">사업자번호를 입력해주세요.</strong>')
			checkFalse ++;
		}else if($('input[name="companyCodeOK"]').prop('checked') == false){
			$('input[name="companyCodeOK"]').after('&nbsp;&nbsp;<strong class="price">사업자번호 중복체크를 해주세요</strong>')
			checkFalse ++;
		}
	}
	if(checkFalse == 0){
		sendData(useApi,'writeform','new')
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