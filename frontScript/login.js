function actLogin(nextPage){	
	nextPage = nextPage ? nextPage : '';
	var loginData = $('#login').serialize();
	//alert(loginData)
	var actLogins = $.ajax({
		method:'POST',
		url:'../api/apiLogin.php',
		data:loginData,
		dataType:'text',
		success:function(data){
			//alert(data)
			if (data == 'error'){
				alert('로그인에 실패하였습니다.\n\n아이디 및 비밀번호를 다시 확인 바랍니다.');
			}else if(data == 'agreement'){
				top.location.href='../member/agreementCheck.php'
			}else if(data == 'agreement2'){
				//alert('다른 기기에서 로그인 중이거나 기록이 남아있어\n다른 기기 사용자를 로그아웃하였습니다.')
				top.location.href='../member/agreementCheck.php'
			}else{
			 //alert(pageMode);
				if(pageMode=='userPage' || pageMode=='studyCenterPage'){
					if (data == 'duplication'){
						//alert('다른 기기에서 로그인 중이거나 기록이 남아있어\n다른 기기 사용자를 로그아웃하였습니다.')
						window.location.reload();
					}else{
						if(pageMode=='userPage'){
							if(nextPage != ''){
								nextPage = '/'+nextPage
								//alert(nextPage)
								top.location.href= nextPage
							}else{
								top.location.href= '/main/';
							}
						}else{
							if(nextPage != ''){
								top.location.href= nextPage;
							}else{
								top.location.href= 'index.php';
							}
						}
					}
				}else if(pageMode=='mobilePage'){
					top.location.href='../m/study.html'
				}else{					
					top.location.href='../admin/00_index.php'
				}
			}
		},
		error:function(data){
			alert('시스템 에러! 관리자에게 문의하세요')
		}
	})
}

function loginPage (types,nextAct){
	var types = types ? types : 'login';
	var pageWrites = ''
	if(types == 'login'){
		if(pageMode == 'userPage'){
			pageWrites += '<img src="../images/member/title_login.png" alt="로그인" />';
		}else if(pageMode=='studyCenterPage'){
			pageWrites += '<img src="../images/studycenter/img_login.png" alt="로그인" />';
		}else{
			pageWrites += '<h1></h1>';
		}
		pageWrites += '<form id="login" action="javascript:actLogin(\''+nextAct+'\')">';
		if(pageMode == 'userPage'){
			pageWrites += '<h1>사이트의 원활한 이용을 위해서는<br />로그인이 필요합니다.</h1>';
		}else if(pageMode == 'studyCenterPage'){
			pageWrites += '<h1>사이트의 원활한 이용을 위해서는<br />로그인이 필요합니다.</h1>';
		}
		pageWrites += '<input type="text" value="아이디" name="userID" />';
		pageWrites += '<input type="text" value="패스워드" name="pwd" />';
		pageWrites += '<button type="submit"><img src="../images/member/btn_login.png" alt="로그인" />로그인</button>';
		pageWrites += '</form>';
		pageWrites += '<a href="javascript:loginPage(\'findID\')">아이디 찾기</a> | <a href="javascript:loginPage(\'findPW\')">비밀번호 찾기</a>';
		if(pageMode != 'mobilePage'){
			if(pageMode == 'userPage'){
				pageWrites += '| <a href="/member/mypage.php">회원가입</a>';
			}else{
				pageWrites += '| <a href="mypage.php">회원가입</a>';
			}
		}
	}else if(types == 'findID'){
		if(pageMode == 'userPage'){
			pageWrites += '<img src="../images/member/title_findid.png" alt="아이디찾기" />';
		}else if(pageMode=='studyCenterPage'){
			pageWrites += '<img src="../images/studycenter/img_findid.png" alt="아이디찾기" />';
		}
		pageWrites += '<form id="login" action="javascript:findMember(\'ID\')">';
		pageWrites += '<h1>회원가입하신 정보와 일치해야 아이디를 찾을 수 있습니다.</h1>';
		pageWrites += '<div><h1>가입자명</h1><input type="text" name="userName" /></div>';
		pageWrites += '<div><h1>생년월일</h1><input type="tel" name="birth01" maxlength="2" />&nbsp;년&nbsp;&nbsp;&nbsp;<input type="tel" name="birth02" maxlength="2" />&nbsp;월&nbsp;&nbsp;&nbsp;<input type="tel" name="birth03" maxlength="2" />&nbsp;일&nbsp;<strong>(ex.92년 9월 19일)</strong></div>';
		pageWrites += '<div><h1>휴대폰</h1><input type="tel" name="mobile01" maxlength="3" />&nbsp;-&nbsp;<input type="tel" name="mobile02" maxlength="4"" />-&nbsp;<input type="tel" name="mobile03" maxlength="4"" /></div>';
		pageWrites += '<button type="submit"><img src="../images/member/btn_find.png" alt="찾기" />아이디 찾기</button>';
		pageWrites += '</form>';
		pageWrites += '<a href="javascript:loginPage(\'login\')">로그인</a> | <a href="javascript:loginPage(\'findPW\')">비밀번호 찾기</a>';
		if(pageMode != 'mobilePage'){
			if(pageMode == 'userPage'){
				pageWrites += '| <a href="/member/mypage.php">회원가입</a>';
			}else{
				pageWrites += '| <a href="mypage.php">회원가입</a>';
			}
		}
	}else if(types == 'findPW'){
		if(pageMode == 'userPage'){
			pageWrites += '<img src="../images/member/title_findpw.png" alt="비밀번호찾기" />';
		}else if(pageMode=='studyCenterPage'){
			pageWrites += '<img src="../images/studycenter/img_findpw.png" alt="비밀번호찾기" />';
		}
		pageWrites += '<form id="login" action="javascript:findMember(\'PW\')">';
		pageWrites += '<h1>비밀번호는 회원가입시 입력하신 휴대폰으로 초기화되어 전송됩니다.</h1>';
		pageWrites += '<div><h1>아이디</h1><input type="text" name="userID" /></div>';
		pageWrites += '<div><h1>가입자명</h1><input type="text" name="userName" /></div>';
		pageWrites += '<div><h1>생년월일</h1><input type="tel" name="birth01" maxlength="2" />&nbsp;년&nbsp;&nbsp;&nbsp;<input type="tel" name="birth02" maxlength="2" />&nbsp;월&nbsp;&nbsp;&nbsp;<input type="tel" name="birth03" maxlength="2" />&nbsp;일&nbsp;<strong>(ex.92년 9월 19일)</strong></div>';
		pageWrites += '<div><h1>휴대폰</h1><input type="tel" name="mobile01" maxlength="3" />&nbsp;-&nbsp;<input type="tel" name="mobile02" maxlength="4"" />-&nbsp;<input type="tel" name="mobile03" maxlength="4"" /></div>';
		pageWrites += '<button type="submit"><img src="../images/member/btn_find.png" alt="찾기" />비밀번호 찾기</button>';
		pageWrites += '</form>';
		pageWrites += '<a href="javascript:loginPage(\'login\')">로그인</a> | <a href="javascript:loginPage(\'findID\')">아이디 찾기</a>';
		if(pageMode != 'mobilePage'){
			if(pageMode == 'userPage'){
				pageWrites += '| <a href="/member/mypage.php">회원가입</a>';
			}else{
				pageWrites += '| <a href="mypage.php">회원가입</a>';
			}
		}
	}
	$('#loginArea').html(pageWrites)
	if(types == 'login'){
		loginScript();
	}
}

//아이디 비번찾기
function findMember(findObj){
	$('input[type="tel"]').each(function(){
        if($(this).val().length == 1){
			nuNumber = '0'+$(this).val();
			$(this).val(nuNumber)
		}
    });
	sendData = $('#login').serialize();
	var findMembers = $.ajax({
		method:'PUT',
		url:'../api/apiLogin.php',
		data:sendData,
		dataType:'text',
		success:function(data){
			if(findObj == 'ID'){
				if(data != 'error'){
					alert('찾으시는 아이디는 '+data+' 입니다.')
				}else{
					alert('찾으시는 아이디가 없습니다.')
				}
			}else{
				if(data != 'error'){
					alert('입력하신 휴대폰으로\n임시 비밀번호가 발송되었습니다.')
				}else{
					alert('일치하는 정보가 없습니다.')
				}
			}
		},
		error:function(data){
			alert('시스템 에러 관리자에게 문의하세요.')
		}
	})
}

function findLogin(type){
	var type = type ? type : '';
	var findDatas = '';
	var loginHTML = ''
	loginHTML += '<form id="login">';
	if(type == 'ID'){
		loginHTML += '<h1>아이디 찾기</h1>'
	}else if (type == 'PW'){
		loginHTML += '<h1>비밀번호 찾기</h1>'
	}
	if(type != 'ID'){
		loginHTML += '<input type="text" name="userID" value="아이디" />'
	}
	if(type != ''){
		loginHTML += '<input type="text" name="userName" value="이름" />'
		loginHTML += '<strong>생년월일</strong><input type="tel" class="year" name="birth01" value="" /> 년 &nbsp;&nbsp;<input type="tel" class="month" name="birth02" value="" /> 월 &nbsp;&nbsp;<input type="tel" class="month" name="birth03" value="" /> 일'
		loginHTML += '<input type="text" name="email" value="이메일" />'
	}
	if(type == ''){
		loginHTML += '<input type="text" name="pwd" value="비밀번호" />'
	}
	loginHTML += '<button type="button" ';
	if(type == ''){
		loginHTML += 'onClick="actLogin()">로그인'
	}else if(type == 'ID'){
		loginHTML += 'onClick="actLogin(\'PUT\')">아이디 찾기'
	}else{
		loginHTML += 'onClick="actLogin(\'PUT\')">비밀번호 찾기'
	}
	loginHTML += '</button>';
	loginHTML += '</form>'
	loginHTML += '<div>'
	if(type != ''){
		loginHTML += '<a href="javascript:findLogin()">로그인</a>'
	}
	if(type != 'ID'){
		loginHTML += '<a href="javascript:findLogin(\'ID\')">아이디 찾기</a>'
	}
	if(type != 'PW'){
		loginHTML += '<a href="javascript:findLogin(\'PW\')">비밀번호 찾기</a>'
	}
	loginHTML += '</div>'
	$('.loginArea').html(loginHTML);
}