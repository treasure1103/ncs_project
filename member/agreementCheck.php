<? include '../include/header.php' ?>
<!-- 우편번호 -->
<script src="http://dmaps.daum.net/map_js_init/postcode.v2.js"></script>
<!-- //우편번호 -->
<script type="text/javascript">
$(document).ready(function(){
	$.get('../api/apiLoginUser.php',{},function(data){
		var formWrite = '';
		formWrite += '<input type="hidden" name="agreement" value="Y">';
		formWrite += '<ul>';
		formWrite += '<li>';
		formWrite += '<h1>학습자 성명</h1>';
		formWrite += '<strong>'+data.userName+'</strong>';
		formWrite += '</li>';		
		formWrite += '<li>';
		formWrite += '<h1>휴대폰 번호</h1>';
		formWrite += '<select name="mobile01">';
		formWrite += '<option value="010">010</option>';
		formWrite += '<option value="011">011</option>';
		formWrite += '<option value="016">016</option>';
		formWrite += '<option value="017">017</option>';
		formWrite += '<option value="018">018</option>';
		formWrite += '<option value="019">019</option>';
		formWrite += '</select>';
		formWrite += '&nbsp;-&nbsp;';
		formWrite += '<input type="tel" name="mobile02" value="'+data.mobile02+'" />';
		formWrite += '&nbsp;-&nbsp;';
		formWrite += '<input type="tel" name="mobile03" value="'+data.mobile03+'" />';
		formWrite += '</li>';
		/*
		formWrite += '<li>';
		formWrite += '<h1>이메일</h1>';
		formWrite += '<input type="text" name="email01" value="'+data.email01+'" />';
		formWrite += '&nbsp;@&nbsp;';
		formWrite += '<input type="text" name="email02" value="'+data.email02+'" />';
		formWrite += '</li>';
		*/
		formWrite += '<li>';
		formWrite += '<h1>새 비밀번호</h1>';
		formWrite += '<input type="password" name="passwordCheck" /> ※ 새로운 비밀번호를 입력해 주세요. 다음 로그인부터는 이 비밀번호로 로그인 하셔야 합니다.';
		formWrite += '</li>'
		formWrite += '<li>';
		formWrite += '<h1>새 비밀번호 확인</h1>';
		formWrite += '<input type="password" name="pwd" />';
		formWrite += '</li>';
		formWrite += '<li><h1>주소</h1><div class="address">';
		formWrite += '<input name="zipCode" class="name" type="tel" maxlength="5" id="zipCodeArea"  value="'+data.zipCode+'" readonly="readonly" />&nbsp;<button type="button" class="findZipCode">우편번호 찾기</button><br />';
		formWrite += '<input name="address01" class="subject" type="text" id="address01Area" value="'+data.address01+'" /><br />';
		formWrite += '<input name="address02" class="subject" type="text" id="address02Area" value="'+data.address02+'" />';
		formWrite += '</div></li>';
		formWrite += '</li>';
		formWrite += '</ul>';
		formWrite += '<div>정보가 불확실 할 시 수강에 불이익을 받으실 수 있습니다.</div>';
		$('#agreementCheck > form').append(formWrite);
		$('select[name="mobile01"]').val(data.mobile01).attr('selected','selected');
		$('#zipCodeArea, .findZipCode').click(function(){zipCodeFind()})
	})
	.done(function(){
		$.get('../api/apiSiteInfo.php',{},function(data){
			$('.agreeUse > div').html(data.agreement);
			$('.private > div').html(data.privacy);
			$('.ACS > div').html(data.acs);
		})		
	})
});

function agreeCheck(){
	if($('input[name="agreeUse"]').prop('checked') != true){
		alert('이용약관에 동의해주세요')
	}else if($('input[name="agreePrivate"]').prop('checked') != true){
		alert('개인정보 취급방침에 동의해주세요')
	}else if($('input[name="agreeACS"]').prop('checked') != true){
		alert('ACS안내 사항에 동의해주세요')
	}else if($('input[name="mobile02"]').val() == '' || $('input[name="mobile03"]').val() == ''){
		alert('휴대폰 번호를 확인해주세요')
/*
		}else if($('input[name="email01"]').val() == '' || $('input[name="email02"]').val() == ''){
		alert('이메일을 확인해주세요')
*/
	}else if($('input[name="passwordCheck"]').val() == ''){
		alert('새 비밀번호를 입력해주세요')
	}else if($('input[name="pwd"]').val() == ''){
		alert('새 비밀번호 확인을 입력해주세요')
	}else if($('input[name="pwd"]').val() == '1234'){
		alert('초기 비밀번호와 다른 비밀번호로 설정해 주세요.')
	}else if($('input[name="passwordCheck"]').val() != $('input[name="pwd"]').val()){
		alert('비밀번호 확인이 일치하지 않습니다.')
	}else if($('input[name="agree"]').prop('checked') != true){
		alert('개인정보 확인사항에 체크해주세요')
	}else if($('input[name="zipCode"]').val() == ''){
		alert('우편번호를 입력해주세요')
	}else{
		var sendData = $('form.sendForm').serialize();

		$.post('../api/apiLoginUser.php',sendData,function(data){
			if(data.result == 'success'){
				alert('정보가 반영되었습니다. 이제부터 새로 설정한 비밀번호로 로그인 하시기 바랍니다.');
				top.location.href='../main/'
			}else{
				alert('확인중 문제가 발생하였습니다')
			}
		})
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
</script>
</head>

<body id="agreementCheck">
<div class="titleArea">
  <div>
    <img src="../images/study/img_test01.png" />
    <h1>학습자 유의사항</h1>
    <h2 class="contentsName">이용약관 및 개인정보이용방침, 개인정보 확인을 해주셔야 학습을 하실 수 있습니다.</h2>
  </div>
</div>
<div class="agreeUse">
  <h1>이용약관</h1>
  <div></div>
</div>
<div class="agreeArea">
  <input type="checkbox" name="agreeUse" id="agreeUse" />
  <label for="agreeUse">이용약관에 동의합니다.</label>
</div>

<div class="private">
  <h1>개인정보취급방침</h1>
  <div></div>
</div>
<div class="agreeArea">
  <input type="checkbox" name="agreePrivate" id="agreePrivate" />
  <label for="agreePrivate">개인정보 취급방침에 동의합니다.</label>
</div>

<div class="ACS">
  <h1>ACS이용 안내</h1>
  <div></div>
</div>
<div class="agreeArea">
  <input type="checkbox" name="agreeACS" id="agreeACS" />
  <label for="agreeACS">ACS에 관하여 충분히 알았습니다.</label>
</div>

<form class="sendForm" action="javascript:sendData()">
  <h1>학습자 정보 확인</h1>
</form>
<div class="agreeArea">
  <input type="checkbox" name="agree" id="agree" />
  <label for="agree">휴대폰, 주소 정보가 일치하며, 새 비밀번호를 발급받았습니다.</label>
</div>
<div class="btnArea">
  <button onClick="agreeCheck()"><img src="../images/member/btn_ok.png" /></button>
</div>