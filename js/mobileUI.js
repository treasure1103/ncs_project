//	모바일 UI 퍼포먼스, 레이아웃 스크립트
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
			alert('로그아웃되었습니다.');
			//window.location.reload();
			top.location.href='../m/login.html'
		},
		error:function(){
			alert('로그아웃이 실패하였습니다.');
		}
	})
}

function viewMenu(){
	var menus = '';
	menus += '<nav><div>';
	menus += '<menu>';
	menus += '<li>Menu<button type="button" onClick="closeMenu()"><img src="../images/mobile/btn_hide.png" alt="닫기"></button></li>';
	menus += '<li onClick="top.location.href=\'study.html\'">내 강의실</li>';
	menus += '<li onClick="top.location.href=\'board.html\'">공지사항</li>';
	menus += '</menu>';
	menus += '<button onClick="logOut()">로그아웃</button>';
	menus += '</div></nav>';
	$('body').append(menus);
	$('nav').fadeIn('fast',function(){
		$('nav > div').animate({marginLeft:0},'fast');
	})
}

function closeMenu(){
	$('nav > div').animate({marginLeft:'-50%'},'fast',function(){
		$('nav').fadeOut('fast',function(){
			$('nav').remove();
		})
	});
}