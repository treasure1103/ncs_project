//	관리자 UI 퍼포먼스, 레이아웃 스크립트
//	작성자 : 서영기
//

function loginScript(){
  $('input[name="userID"]').bind({
	  focus:function(){
		  if($(this).val()=='아이디'){
			  $(this).val('')
		  }
	  },
	  blur:function(){
		  if($(this).val()=='' || $(this).val()=='아이디'){
			  $(this).val('아이디')
		  }
	  }
  })
  $('input[name="pwd"]').bind({
	  focus:function(){
		  if($(this).attr('type')=='text'|| $(this).val()=='비밀번호'){
			  $(this).val('');
			  $(this).attr('type','password')
		  }
	  },
	  blur:function(){
		  if($(this).val()=='비밀번호' || $(this).val()==''){
			  $(this).attr('type','text')
			  $(this).val('비밀번호');
		  }
	  }
  })
}

function logOut(){
	$.ajax({
		url:'../api/apiLogin.php',
		type:'DELETE',
		dataType:'text',
		success: function(data){
			//alert('로그아웃되었습니다.');
			window.location.reload();
		},
		error:function(){
			alert('로그아웃이 실패하였습니다.');
		}
	})
}

function helpDesk(){
	window.open("../study/helpdesk.php","안내","top=0,left=0,menubar=no,status=no,titlebar=no,toolbar=no,scrollbars=yes,resizeable=no","study")
}
function remoteHelp(){
	window.open("http://367.co.kr/","원격제원센터","top=0,left=0,width=1000,height=500,menubar=no,status=no,titlebar=no,toolbar=no,scrollbars=yes,resizeable=no","study")
}